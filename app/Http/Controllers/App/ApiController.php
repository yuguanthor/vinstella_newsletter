<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Mail;

class ApiController extends Controller
{
  public function __construct(){
    $this->mail_limit_per_cron = 125;
  }

  function send_newsletter(){
    echo 'Start Send Newsletter function<br>';
    $mail_redirect = get_mail_redirect();

    $newsletter = DB::Table('newsletter')
              ->orderBy('ID','ASC')
              ->where('Status',0)
              ->first();

    if($newsletter == null){
      echo('No Pending Mail To Sent');
      exit;
    }

     //check first
    $customer_lists = $this->get_newsletter_customer_lists($newsletter->id);

    echo 'Get Customer Lists: '.count($customer_lists).'<br>';

    foreach($customer_lists as $d){
      $customer = DB::Table('customer')->where('id',$d->customer_id)->first();
      if($customer==null){
        $this->set_newsletter_error_message($d->id,'CUSTOMER_NOT_FOUND');
        continue;
      }
      if( !filter_var($customer->email, FILTER_VALIDATE_EMAIL)){
        $this->set_newsletter_error_message($d->id,'INVALID_EMAIL');
        continue;
      }

      $attachment=[];
      foreach([1,2,3] as $v){
        if($newsletter->{"attachment$v"."_path"}==null){continue;}
        $attachment[$v] = [
          'path' => storage_app_path($newsletter->{"attachment$v"."_path"} ),
          'name' => $newsletter->{"attachment$v"."_name"}
        ];
      }

      $body = $this->email_content_override($newsletter->body, $customer);
      $subject = $this->email_content_override($newsletter->subject, $customer);

      $data = [
        'to' => $mail_redirect==false ? $customer->email : $mail_redirect,
        'subject' => $subject,
        'body' => $body,
        'attachment' => $attachment
      ];
      set_global_newsletter_status($newsletter->id);
      set_global_newsletter_customer_queue_status($d->id,1);

      echo 'Sending Mail to '.$data['to'].'<br>';

      Mail::send('emails.mail', $data, function($m) use ($data) {
        $m->to($data['to']);
        $m->subject($data['subject']);
        if(count($data['attachment']) > 0){
          foreach($data['attachment'] as $d){
            $m->attach($d['path'], ['as' => $d['name'] ]);
          }
        }
      });

      echo '[Completed] Sent Mail to '.$data['to'].'<br>';

      DB::table('newsletter_customer')
      ->where('id',$d->id)
      ->update([
        'status'=>1,
        'executed_at' => date('Y-m-d H:i:s')
      ]);
      mail_log($data);
      set_global_newsletter_status(null);
      set_global_newsletter_customer_queue_status($d->id,0);
    }//end of loop customer lists

    //check twice, save next loop time
    $customer_lists = $this->get_newsletter_customer_lists($newsletter->id);
    echo '---completed---';
  }//end of function


  function get_newsletter_customer_lists($nid){
    $customer =  DB::Table('newsletter_customer')
          ->where('newsletter_id',$nid)
          ->where('status',0)
          ->limit( $this->mail_limit_per_cron )
          ->get();
    if(count($customer) == 0){
      DB::Table('newsletter')->where('id',$nid)->update(['status'=>'1']);
    }
    return $customer;
  }

  function set_newsletter_error_message($nc_id, $error=''){
    if($error == 'CUSTOMER_NOT_FOUND'){
      $status_text = 'Customer not found in database';
    }
    if($error == 'INVALID_EMAIL'){
      $status_text = 'Customer Email Invalid';
    }

    DB::table('newsletter_customer')
    ->where('id',$nc_id)
    ->update([
      'status'=>2,
      'status_text'=>$status_text,
      'executed_at' => date('Y-m-d H:i:s')
    ]);
  }

  function email_content_override($content, $data){
    $content = str_replace("[CUSTOMER_NAME]",$data->name, $content);
    $content = str_replace("[CUSTOMER_EMAIL]",$data->email, $content);
    $content = str_replace("[CUSTOMER_GROUP]", customer_group_name($data->customer_group), $content);
    return $content;
  }

}
