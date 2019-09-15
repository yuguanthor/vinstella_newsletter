<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Excel;

class CustomerController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  public function index(Request $request){
    $search = $request->input('search');


    $data = DB::table('customer as c')
            ->orderBy('c.id','DESC')
            ;

    foreach( (array)$search as $k => $v){
      if($v != ''){
        if($k=='ic'){
          $data->where('c.ic',$v);
        }elseif($k=='customer_group'){
          $data->where('c.customer_group',$v);
        }
      }
    }

    $data = $data->paginate(20);
    return view('app.customer.view_customer',compact('data','search'));
  }
  public function create(){
    return view('app.customer.form_customer');
  }
  public function store(Request $request){
    $post = $request->input();

    //create new customer
    $create = [
      'name'=> $post['name'],
      'email'=> $post['email'],
      'customer_group'=> $post['customer_group'],
      //'ic'=> $post['ic'],
      'created_at' => date('Y-m-d'),
      'created_by' => \Auth::user()->id,
    ];
    DB::Table('customer')->insert($create);
    $summary = "Add customer [ Name:{$post['name']} ]";
    log_action('Add',$summary);

    \Session::flash('success',$summary);
    return redirect()->back();
  }
  public function edit($id){
    $data = DB::table('customer')->where('id',$id)->first();
    return view('app.customer.form_customer',compact('id','data'));

  }
  public function update($id,Request $request){
    $validatedData = $request->validate([
      'email' => 'required|email|unique:customer,email,'.$id.'|'
    ]);

    $post = $request->input();

    DB::table('customer')
    ->where('id',$id)
    ->update([
      'name'=>$post['name'],
      'email'=>$post['email'],
      'customer_group'=>$post['customer_group']
    ]);

    $d = DB::Table('customer')->where('id',$id)->first();
    $summary = "Update user [ Name:{$d->name} ]";
    log_action('Update',$summary);
    \Session::flash('success',$summary);
    return redirect()->back();
  }
  public function delete(){}

  public function customer_import(){
    return view('app.customer.customer_import');
  }
  public function customer_import_file(Request $request){
    $validatedData = $request->validate(['file' => 'required|mimes:xlsx,xls']);
    $rows = Excel::load( $request->file,function($reader){})->get();
    foreach($rows as $d){
      DB::table('customer_to_import')
      ->insert([
        'name' => $d['customer'],
        'email' => $d['email'],
        'customer_group' => group_name_to_id($d['group']),
      ]);
    }
    \Session::flash('success','Excel File has been imported');
    return redirect()->back();
  }

  public function customer_import_data(Request $request){
    $toImport = $request->input('chk');
    $count = 0;
    if($toImport != null){
      $cus_lists = DB::table('customer_to_import')
                  ->whereIn('id',$toImport)
                  ->get();
      foreach($cus_lists as $d){
        //check exists
        $chkExists = DB::table('customer')->where('email',$d->email)->first();
        if($chkExists){continue;}
        $insert = [
          'name' => $d->name,
          'email' => $d->email,
          'customer_group' => $d->customer_group,
        ];
        DB::table('customer')->insert($insert);
        $count++;
      }
    }

    DB::Table('customer_to_import')->truncate();
    \Session::flash('success', $count.' customer has been imported');
    return redirect()->back();
  }

}
