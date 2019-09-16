<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  function download_mail_template_attachment(Request $request){
    $id = $request->input('id');
    $number = $request->input('attachment');
    if($id==null || $number==null){abort(404);}

    $data = DB::table('mail_template')->where('id',$id)->first();
    $name = $data->{"attachment".$number."_name"};
    $path = $data->{"attachment".$number."_path"};
    if(!Storage::exists($path)){dd('File is removed.');}
    return Storage::download($path,$name);
  }

  function download_customer_import_excel_layout(){
    $path = "public/customer_import_layout.xls";
    $name = "customer_import_layout.xls";
    return Storage::download($path,$name);
  }

}
