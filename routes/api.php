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

Route::get('send_newsletter','App\ApiController@send_newsletter');

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