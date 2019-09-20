<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB,Mail,Storage;

class MailController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  function index(Request $request){
    $search = $request->input('search');

    $data = DB::Table('newsletter');
    foreach((array)$search as $k => $v){
      if($v != ''){
        $data->where($k,'like',"%$v%");
      }
    }
    $data = $data->orderBy('id','DESC')->paginate(20);
    return view('app.mail.view_mail',compact('data','search'));
  }
  function create(Request $request){
    $search=$request->input('search');

    $customer = [];
    if(isset($search['customer_group'])){

      $customer = DB::table('customer');
      if( in_array('N',$search['customer_group'])){
        $customer->whereNull('customer_group');
        $customer->orWhereIn('customer_group',$search['customer_group']);
      }elseif( in_array('A',$search['customer_group'])){
        //get all
      }else{
        $customer->whereIn('customer_group',$search['customer_group']);
      }

      $customer->where('status',1);//active customer only
      $customer = $customer->orderBy('customer_group')->get();
    }

    $template = null;
    if(isset($search['email_template'])){
      $template = DB::table('mail_template');
      $template->where('id',$search['email_template']);
      $template = $template->first();
    }

    return view('app.mail.form_mail',compact('search','customer','template'));
  }

  function store(Request $request){
    //customer lists
    $customer_ids = $request->input('customer_id');
    if($customer_ids == null){
      \Session::flash('danger', 'Please select at least one customer for newsletter.');
      return redirect()->back();
    }

    //create newsletter data first
    $post = $request->input();

    $insert = [
      'name' => $post['name'],
      'subject' => $post['subject'],
      'body' => fixLocalSrc($post['body']),
      'template_id'=> $post['template_id'] ?? null
    ];
    $newsletter_id = DB::Table('newsletter')->insertGetId($insert);

    //update attachment to table
    $attachment = attachment_override($request);
    foreach($attachment as $k => $a){
      if($a['path']==null){continue;}

      if( isset($a['file']) && $a['file']!=null){
        $file=$a['file'];
        $file_path = Storage::putFile("mail_queue_attachment/$newsletter_id", $file);
      }else{
        $old=$a['path'];
        $new="mail_attachment/$newsletter_id";
        $content= file_get_contents( storage_app_path($a['path']) );
        $file_path = "mail_queue_attachment/$newsletter_id/{$a['name']}";
        Storage::put($file_path, $content);
      }

      DB::Table('newsletter')
      ->where('id',$newsletter_id)
      ->update([
        "attachment{$k}_name" => $a['name'],
        "attachment{$k}_path" => $file_path
      ]);
    }

    foreach($customer_ids as $cid){
      DB::table('newsletter_customer')
      ->insert([
        'newsletter_id' => $newsletter_id,
        'customer_id' => $cid,
        'status' => 0,
        'executed_at' => null,
      ]);
    }

    $newsletter = $this->newsletter_data($newsletter_id);
    $summary = "Newsletter [name: {$newsletter->name}] has been added.";
    log_action('Add',$summary);
    \Session::flash('success', $summary);
    return redirect('/mail');

  }//end of store

  function show(Request $request, $id){
    $newsletter = DB::Table('newsletter')->where('id',$id)->first();
    $newsletter_customer = DB::Table('newsletter_customer')
    ->where('newsletter_id',$id)
    ->orderBy('status','ASC')
    ->orderBy('id','ASC');

    $filter_status = $request->input('status');
    if( $filter_status != null){
      $newsletter_customer->where('status',$filter_status);
    }

    $newsletter_customer=$newsletter_customer->paginate(50);
    return view('app.mail.view_newsletter',compact('id','newsletter','newsletter_customer'));
  }

  function newsletter_data($id){
    return DB::table('newsletter')->where('id',$id)->first();
  }
}
