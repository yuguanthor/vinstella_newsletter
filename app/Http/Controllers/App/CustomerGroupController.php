<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CustomerGroupController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  public function index(Request $request){

    $data = DB::table('lists_customer_group');
    $data = $data->paginate(50);
    return view('app.maintenance.view_customer_group',compact('data'));
  }
  public function create(){
    return view('app.maintenance.form_customer_group');
  }
  public function store(Request $request){
    $post = $request->input();

    //create new customer
    $create = [
      'group_name'=> $post['group_name']
    ];
    DB::Table('lists_customer_group')->insert($create);
    $summary = "Add customer group [ Name:{$post['group_name']} ]";
    log_action('Add',$summary);

    \Session::flash('success',$summary);
    return redirect()->back();
  }
  public function edit($id){
    dd('NOT ALLOW EDIT');
  }
  public function update($id,Request $request){
    dd('NOT ALLOW EDIT');
  }
  public function delete(){
    dd('NOT ALLOW DELETE');
  }


}
