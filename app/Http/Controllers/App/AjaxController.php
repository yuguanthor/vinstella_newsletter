<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Mail;

class AjaxController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  function ajax_get_customer_info(Request $request){
    $ic = $request->input('ic');
    $data = DB::table('customer')->where('ic',$ic)->first();
    if($data==null) return json_encode(false);

    $data->acc = sum_customer_package($ic);
    $data->used = sum_customer_payment($ic);
    $data->remaining = $data->acc - $data->used;

    return json_encode($data);
  }

  function ajax_test_mail(Request $request){
    //check availability of test mail
    $test_mail_config = DB::Table('system_configuration')->where('config_name','test_mail_account')->first();
    if($test_mail_config==null){
      return 'ERROR: Test Mail Account is not set yet.';
    }

    $to_email = $test_mail_config->config_value;
    if( !filter_var($to_email, FILTER_VALIDATE_EMAIL)){
      return 'ERROR: Invalid Test Mail Account.';
    }

    $template_id = $request->template_id;

    //attachment overriding
    $att_count = [1,2,3];
    $attachment=[];
    if($template_id != null){
      //get old attachment
      $template = DB::table('mail_template')
                  ->where('id',$template_id)
                  ->first();
      foreach($att_count as $v){
        if($request->{"attachment{$v}_disabled"}){continue;}
        if($template->{"attachment$v"."_path"}==null){continue;}
        $attachment[$v] = [
          'path' => storage_path( 'app/'.$template->{"attachment$v"."_path"} ),
          'name' => $template->{"attachment$v"."_name"}
        ];
      }
    }

    $request_attachment = $request->attachment;
    foreach((array)$request_attachment as $k => $file){
      if($file==null){continue;}
      $number = $k+1;
      $attachment[$number] = [
          'path' => $file,
          'name' => $file->getClientOriginalName()
        ];
    }



    $data = [
      'subject' => $request->input('subject'),
      'body' => $request->input('body'),
      'attachment' => $attachment
    ];
    //dd($data);
    Mail::send('emails.mail', $data, function($m) use ($to_email,$data) {
      $m->to($to_email);
      $m->subject("System Test Mail:".$data['subject']);
      if(count($data['attachment']) > 0){
        foreach($data['attachment'] as $d){
          $m->attach($d['path'], ['as' => $d['name'] ]);
        }
      }
    });

    return $to_email;
  }//end of  function

}
