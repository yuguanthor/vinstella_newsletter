<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class AdminController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  public function index(Request $request){
    $search = $request->input('search');
    $data = DB::table('users AS u')
            ->selectRaw('u.id,u.name, u.status');

    foreach((array)$search as $k => $v){
      if($v!=''){
        if($k=='admin'){
          $data->where('u.id',$v);
        }elseif($k=='status'){
          $data->where('u.status',$v);
        }
      }
    }

    $data = $data->paginate(20);
    return view('app.admin.view_admin',compact('data','search'));
  }
  public function create(){
    return view('app.admin.form_admin');
  }
  public function store(Request $request){
    $post = $request->input();

    $insert = [
      'name' => $post['name'],
      'username' => $post['name'],
      'email' => $post['name'],
      'status' => 1,
      'created_at' => date('Y-m-d H:i:s'),
      'password' => bcrypt($post['password']),
    ];

    $id = DB::table('users')->insertGetid($insert);

    $summary = "Add Admin [ Name:{$post['name']} ]";
    log_action('Add',$summary);
    \Session::flash('success',$summary);
    return redirect()->back();
  }
  public function edit($id){
    $data = DB::table('users as u')
    ->selectRaw('
      u.name,
      u.email,
      u.status
    ')
    ->where('u.id',$id)
    ->first();

    return view('app.admin.form_admin',compact('data','id'));
  }
  public function update(Request $request,$id){
    $post = $request->input();
    $status = isset($post['status']) ? 1 : 0 ;

    DB::table('users')
    ->where('id',$id)
    ->update(['status'=>$status]);

    if($post['password'] != ''){
      DB::table('users')
      ->where('id',$id)
      ->update([
        'password' => bcrypt($post['password']),
      ]);
    }

    $d = DB::Table('users')->where('id',$id)->first();
    $summary = "Update Admin [ Name:{$d->name} ]";
    log_action('Update',$summary);
    \Session::flash('success',$summary);
    return redirect()->back();
  }

  public function delete(){}

  function action_log(Request $request){
    $search = $request->input('search');
    $data = DB::table('action_log');

    foreach((array)$search as $k => $v){
      if($v!=''){
        if($k=='date'){
          $data->whereRaw("( DATE_FORMAT(created_at,'%Y-%m-%d') = '".db_date($v)."' )");
        }elseif($k=='uid'){
          $data->where('created_by',$v);
        }else{
          $data->where($k,'like',"%$v%");
        }
      }
    }
    $data=$data->orderBy('id','DESC')->paginate(10);
    return view('app.admin.action_log',compact('data','search'));
  }

  function mail_log(Request $request){
    $search = $request->input('search');
    $data = DB::table('mail_log');

    foreach((array)$search as $k => $v){
      if($v!=''){
        if($k=='date'){
          $data->whereRaw("( DATE_FORMAT(created_at,'%Y-%m-%d') = '".db_date($v)."' )");
        }else{
          $data->where($k,'like',"%$v%");
        }
      }
    }
    $data=$data->orderBy('id','DESC')->paginate(10);
    return view('app.admin.mail_log',compact('data','search'));
  }

  function mail_log_body_html($id){
    $data = DB::table('mail_log')->where('id',$id)->first();
    if($data==null){abort(404);}
    return view('app.admin.mail_log_body_html',compact('data'));
  }

  function system_configuration(){
    $this->create_sys_config('test_mail_account');
    $this->create_sys_config('mail_redirect_account');
    $sys_config = DB::table('system_configuration')->get();
    return view('app.admin.system_configuration',compact('sys_config'));
  }
  function update_system_configuration(Request $request){
    $config = $request->input('config');
    foreach($config as $name => $value){
      DB::Table('system_configuration')
      ->where('config_name',$name)
      ->update(['config_value' => $value]);
    }

    $summary = "System Configuration has been updated.";
    log_action('Update',$summary);
    \Session::flash('success',$summary);
    return redirect()->back();
  }
  //create if not exists
  function create_sys_config($config_name){
    $chk = DB::Table('system_configuration')->where('config_name',$config_name)->first();
    if($chk==null){
      DB::Table('system_configuration')->insert(['config_name' => $config_name]);
    }
  }


}
