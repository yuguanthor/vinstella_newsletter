<?php

namespace App\Http\Controllers\app;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB, Excel;

class CKEditorController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  public function file_browse(){

  }

  public function file_upload(){

  }


}
