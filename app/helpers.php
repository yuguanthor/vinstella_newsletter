<?php

function lists_customer_group(){
  return DB::table('lists_customer_group')->pluck('group_name','id');
}

function group_name_to_id($name){
  $group = DB::table('lists_customer_group')->where('group_name',$name)->first();
  return $group->id ?? null;
}

function customer_group_name($id){
  $group = DB::table('lists_customer_group')->where('id',$id)->first();
  return $group->group_name ?? 'N/A';
}

function sel_customer_group(){
  $data =  DB::table('lists_customer_group')
          ->selectRaw('
            CONCAT(id, " | ", group_name) AS display, id
          ')
          ->pluck('display','id');
  $data->prepend('No Group','N');
  return $data;
}

function sel_mail_status(){
  return [
    '0' => '0 | Pending',
    '1' => '1 | Completed',
    '2' => '2 | Error'
  ];
}

function sel_mail_template(){
  $data = DB::table('mail_template')->pluck('name','id');
  return $data;
}

function sel_customer_lists(){
  $cust_list = \DB::Table('customer')
              ->selectRaw('
                id, CONCAT(name," - ",email) AS display
              ')
              ->pluck('display','id');
  return $cust_list;
}

function sel_status_lists(){
  return ['1' => '1', '0' => '0'];
}

function customer_name($ic){
  $d = DB::table('customer')->where('ic',$ic)->first();
  return $d==null ? 'N/A' : $d->name;
}

function log_action($action,$summary){
  $insert = [
    'action' => $action,
    'summary'=> $summary,
    'created_by' => \Auth::user()->id,
    'created_at' => date('Y-m-d H:i:s')
  ];

  DB::table('action_log')->insert($insert);
}

function db_date($date, $format='d-M-Y'){
  $DateTime = \DateTime::createFromFormat($format , $date);
  if(!$DateTime) return false;
  return $DateTime->format('Y-m-d');
}

function display_date($db_date){
  $DateTime = \DateTime::createFromFormat('Y-m-d', $db_date);
  if(!$DateTime) return false;
  return $DateTime->format('d-M-Y');
}

function user_name($id){
  $u = DB::Table('users')->where('id',$id)->first();
  return $u->name ?? 'N/A';
}

function lists_users(){
  return DB::table('users')->pluck('name','id');
}

function lists_log_action(){
  return DB::table('action_log')->groupBy('action')->pluck('action','action');
}


function html_status_icon($v){
  if($v){
    return '<i class="fa fa-check green"></i>';
  }else{
    return '<i class="fa fa-times red"></i>';
  }
}

//attachment1/2/3 + template_id
function attachment_override($request){
  //attachment override
    //$request, template_id, attachment1/2/3
    $att = [1,2,3];
    if($request->template_id != null){
      $template = DB::Table('mail_template')->where('id',$request->template_id)->first();
    }
    //template
    foreach($att as $c){
      if( !isset($template) || $template==null){
        $attachment_arr[$c] = [
         "name" => null,
         "path" => null,
         "delete" => false
       ];
      }else{
        $attachment_arr[$c] = [
         "name" => $template->{"attachment{$c}_name"},
         "path" => $template->{"attachment{$c}_path"},
         "delete" => false
       ];
      }

    }

    //delete
    foreach($att as $c){
      if($request->{"attachment{$c}_disabled"} == 1){
        $attachment_arr[$c]['delete'] = true;
      }
    }

    //request
    foreach($att as $c){
      $file = $request->{"attachment$c"};
      if($file==null){continue;}
      $file_name = $file->getClientOriginalName();
      $file_path = $file->getPathName();
      $attachment_arr[$c]['name'] = $file_name;
      $attachment_arr[$c]['path'] = $file_path;
      $attachment_arr[$c]['file'] = $file;
    }
    return $attachment_arr;
}


function storage_app_path($path){
  return storage_path('app/'.$path);
}

/*
	Must contain following data:
	To,Subject,Cc,Bcc,Template,priority
*/
function MailQueue($mail_arr){
	if(isset($mail_arr['priority'])){
		$priority = $mail_arr['priority'];
		unset($mail_arr['priority']);
	}else{
		$priority = 0; // default priority
	}

	$payload = json_encode($mail_arr);
	DB::table('mail_queue')->insert([
		'payload' => $payload,
		'priority' => $priority,
		'status' => 0
		]
	);
}


function newsletter_status_name($status){
  if($status == 0){return 'Pending';}
  if($status == 1){return 'Completed';}
  if($status == 2){return 'Error';}
  return 'N/A';
}

function set_global_newsletter_status($newsletter_id){
  DB::Table('newsletter_to_send')->where('id',1)->update(['newsletter_id'=>$newsletter_id]);
}

function set_global_newsletter_customer_queue_status($newsletter_customer_id,$isQueuing=0){
  DB::Table('newsletter_to_send')
  ->where('id',1)
  ->update(['newsletter_customer_id'=>$newsletter_customer_id]);

  DB::table('newsletter_customer')
  ->where('id',$newsletter_customer_id)
  ->update(['isQueuing'=>$isQueuing]);
}

function newsletter_count($id, $type=''){
  $count = DB::table('newsletter_customer')->where('newsletter_id',$id);
  if($type=='success'){
    $count->where('status',1);
  }
  if($type=='pending'){
    $count->where('status',0);
  }
  if($type=='error'){
    $count->where('status',2);
  }
  return $count->count();
}

function mail_log($mail_arr){
  DB::Table('mail_log')
  ->insert([
    'mail_to' => $mail_arr['to'],
    'subject' => $mail_arr['subject'],
    'body' => $mail_arr['body'],
    'created_at' => date('Y-m-d H:i:s')
  ]);
}


function get_mail_redirect(){
  $mail_redirect = false;
  $config_redirect = DB::Table('system_configuration')->where('config_name','mail_redirect_account')->first();
  if($config_redirect != null &&
    filter_var($config_redirect->config_value , FILTER_VALIDATE_EMAIL)
  ){
    $needRedirect = true;
    $mail_redirect = $config_redirect->config_value;
  }
  return $mail_redirect;
}

function log_cron($name, $id=null){
  if($id == null){
    $id = DB::Table('cron_log')->insertGetId([
      'cron_name' => $name,
      'start' => date('Y-m-d H:i:s'),
    ]);
    return $id;
  }else{
    DB::Table('cron_log')->where('id',$id)->update([
      'end' => date('Y-m-d H:i:s')
    ]);
  }
}

function unsubscribe_url($customer_id){
  return url('api/unsubscribe_newsletter/'.encrypt($customer_id));
}

function fixLocalSrc($body){
  $url= url('').'/';
  $body = str_replace('src="/','src="'.$url,$body);
  return $body;
}
