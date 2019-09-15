<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Storage;

class MailTemplateController extends Controller
{
  public function __construct(){$this->middleware('auth');}

  function index(Request $request){
    $data = DB::table('mail_template')->get();
    return view('app.mail_template.view_template',compact('data'));
  }
  function create(Request $request){
    return view('app.mail_template.form_template',compact('data'));
  }

  function store(Request $request){
    $post = $request->input();
    $template_id = DB::table('mail_template')->insertGetId([
      'name' => $post['name'],
      'subject' => $post['subject'],
      'body' => $post['body'],
      'created_at' => date('Y-m-d H:i:s'),
      'created_by' => \Auth::user()->id
    ]);

    $attachment = $request->attachment;
    foreach((array)$attachment as $k => $file){
      if($file==null){continue;}
      $file_name = $file->getClientOriginalName();
      $file_path = Storage::putFile("mail_template_attachment/$template_id", $file);

      $number = $k+1;
      DB::table('mail_template')
      ->where('id',$template_id)
      ->update([
        "attachment{$number}_name" => $file_name,
        "attachment{$number}_path" => $file_path
      ]);
    }

    $template = $this->mail_template_data($template_id);
    $summary = "Template [name: {$template->name}] has been added.";
    log_action('Add',$summary);
    \Session::flash('success', $summary);
    return redirect()->back();
  }

  function edit($id, Request $request){
    $template = DB::table('mail_template')->where('id',$id)->first();
    return view('app.mail_template.form_template',compact('template','id'));
  }

  function update($id, Request $request){
    $post = $request->input();
    DB::table('mail_template')
    ->where('id',$id)
    ->update([
      'name' => $post['name'],
      'subject' => $post['subject'],
      'body' => $post['body'],
      'updated_at' => date('Y-m-d H:i:s'),
      'updated_by' => \Auth::user()->id
    ]);

    $data =  DB::table('mail_template')->where('id',$id)->first();

    $att = [1,2,3];
    foreach($att as $c){
      if($request->{"attachment{$c}_disabled"} == 1){
        Storage::delete($data->{"attachment$c"."_path"});
        DB::table('mail_template')
        ->where('id',$id)
        ->update([
          "attachment{$c}_name" => null,
          "attachment{$c}_path" => null
        ]);
        unset($att[$c]);//prevent upload again at below
      }
    }

    $attachment = $request->attachment;

    foreach($att as $c){
      $file = $request->{"attachment$c"};
      if($file==null){continue;}
      Storage::delete($data->{"attachment$c"."_path"});
      $file_name = $file->getClientOriginalName();
      $file_path = Storage::putFile("mail_template_attachment/$id", $file);
      DB::table('mail_template')
      ->where('id',$id)
      ->update([
        "attachment{$c}_name" => $file_name,
        "attachment{$c}_path" => $file_path
      ]);
    }

    $template = $this->mail_template_data($id);
    $summary = "Template [name: {$template->name}] has been updated.";
    log_action('Update',$summary);
    \Session::flash('success', $summary);
    return redirect()->back();
  }

  function destroy($id){
    $template = $this->mail_template_data($id);
    DB::table('mail_template')->where('id',$id)->delete();
    Storage::deleteDirectory("mail_template_attachment/$id");
    $summary = "Template [name: {$template->name}] has been deleted.";
    log_action('Delete',$summary);
    \Session::flash('success', $summary);
    return redirect('/mail_template');
  }

  function mail_template_data($id){
    return DB::table('mail_template')->where('id',$id)->first();
  }
}
