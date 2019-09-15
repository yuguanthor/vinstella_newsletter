<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('send_newsletter',function(){
  $newsletter = DB::Table('newsletter')
              ->orderBy('ID','ASC')
              ->where('Status',0)
              ->first();

  if($newsletter == null){dd('No Pending Mail To Sent');}

  $customer_lists = DB::Table('newsletter_customer')
                    ->where('newsletter_id',$newsletter->id)
                    ->where('status',0)
                    ->limit(3)
                    ->get();

  if(count($customer_lists) == 0){
    DB::Table('newsletter')->where('id',$newsletter->id)->update(['status'=>'1']);
  }

  foreach($customer_lists as $d){
    $customer = DB::Table('customer')->where('id',$d->id)->first();

    if($customer==null){
      DB::table('newsletter_customer')
      ->where('id',$d->id)
      ->update([
        'status'=>2,
        'status_text'=>'Customer not found in database',
        'executed_at' => date('Y-m-d H:i:s')
      ]);
      continue;
    }
    if( !filter_var($customer->email, FILTER_VALIDATE_EMAIL)){
      DB::table('newsletter_customer')
      ->where('id',$d->id)
      ->update([
        'status'=>2,
        'status_text'=>'Customer Email invalid',
        'executed_at' => date('Y-m-d H:i:s')
      ]);
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

    $body = $newsletter->body;
    $body = str_replace("[CUSTOMER_NAME]",$customer->name, $body);
    $body = str_replace("[CUSTOMER_EMAIL]",$customer->email, $body);
    $body = str_replace("[CUSTOMER_GROUP]", customer_group_name($customer->customer_group), $body);

    $subject = $newsletter->subject;
    $subject = str_replace("[CUSTOMER_NAME]",$customer->name, $subject);
    $subject = str_replace("[CUSTOMER_EMAIL]",$customer->email, $subject);
    $subject = str_replace("[CUSTOMER_GROUP]", customer_group_name($customer->customer_group), $subject);

    $data = [
      'to' => config('app.test_mail_account'),
      'subject' => $subject,
      'body' => $body,
      'attachment' => $attachment
    ];
    set_global_newsletter_status($newsletter->id);
    Mail::send('emails.mail', $data, function($m) use ($data) {
      $m->to($data['to']);
      $m->subject($data['subject']);
      if(count($data['attachment']) > 0){
        foreach($data['attachment'] as $d){
          $m->attach($d['path'], ['as' => $d['name'] ]);
        }
      }
    });
    DB::table('newsletter_customer')
    ->where('id',$d->id)
    ->update([
      'status'=>1,
      'executed_at' => date('Y-m-d H:i:s')
    ]);
    mail_log($data);
    set_global_newsletter_status(null);
  }//end of loop customer lists

});

Route::get('system_backup',function(){
  $db_username = config('database.connections.mysql.username');
  $db_password = config('database.connections.mysql.password');
  $db_name = config('database.connections.mysql.database');

  $directory = storage_path('Backup');
  $file_name = 'database_backup_'.date('Ymd_His').'.sql';
  $sql_file_path = $directory.'/'.$file_name;
  dump($sql_file_path);
  //$cmd = "echo 'xxx'  > {$sql_file_path}";
  $cmd = "C:\\xampp\\mysql\\bin\\mysqldump.exe -u {$db_username} -p={$db_password} --databases {$db_name}  > {$sql_file_path}";
  dump($cmd);
  $a = exec($cmd, $output);

});