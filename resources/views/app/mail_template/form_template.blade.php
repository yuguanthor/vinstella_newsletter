@extends('adminlte::page')


@section('title', 'Mail Template')

@section('content_header')
  <h1>Create Mail Template</h1>
@stop

@section('content')

  <div class="box box-primary box-pad clearfix">
    <h3 class="box-title">Email Template</h3>
    @if(isset($template))
     {!! Form::model($template,['url'=>['mail_template',$id],'id'=>'FormEmail','method'=>'PUT','files'=>true]) !!}
    @else
    {{Form::open(['url'=>'mail_template','files'=>true,'id'=>'FormEmail'])}}
    @endif
      @csrf
      <div class="box-body">
        <div class="col-md-12">
          <span class="email-title-text">Template Name <i>(for easy to recognise)</i></span>
          {{Form::text('name',null,['class'=>'form-control','placeholder'=>'Template Name','required'])}}
          <br><br>
          <span class="email-title-text">Subject</span>
          {{Form::text('subject',null,['class'=>'form-control','placeholder'=>'Email Subject','required'])}}
          <br>
          <div class="ckeditor-wrapper-div">
            <span class="email-title-text">Body</span>
            {{Form::textarea('body',null,['id'=>'email-body'])}}

            <div class="ckeditor-custom-control-div">
              {{Form::Button('Customer Name',['onclick'=>'ck_append_text("name");','class'=>'btn btn-sm btn-default'])}}
              {{Form::Button('Customer Email',['onclick'=>'ck_append_text("email");','class'=>'btn btn-sm btn-default'])}}
              {{Form::Button('Customer Group',['onclick'=>'ck_append_text("group");','class'=>'btn btn-sm btn-default'])}}
            </div>
          </div>
          <br>

          @php($att = [1,2,3])
          <div class="attachment-wrapper">
            <h4>Attachment</h4>
            @if(isset($template))
              {{Form::hidden('template_id',$template->id)}}
              <div>*Choose new file to override old file</div>
              @foreach($att as $v)
                @php($download_param = "id={$template->id}&attachment=$v")
                <div class="attachment-div">
                  <span class="email-title-text">{{$v}}. :</span>
                  <a href='{{url("/download/mail_template_attachment")."?".$download_param}}' class="template_attachment">
                    {{ $template->{"attachment{$v}_name"} }}
                  </a>
                  {{Form::file('attachment'.$v,['class'=>'inline input-attachment'])}}
                  {{Form::Button('Remove',['class'=>'btn btn-sm btn-danger btn-remove-attachment'])}}
                  {{Form::Button('Add',['class'=>'btn btn-sm btn-success btn-allow-attachment','style'=>'display:none'])}}
                </div >
              @endforeach

            @else

              @foreach($att as $v)
                <span class="email-title-text">{{$v}}. :</span>
                {{Form::file('attachment[]',['class'=>'inline'])}}<br>
              @endforeach

            @endif

          </div><!-- Attachment module -->

        </div>
      </div>

      <div class="box-footer text-center">
        {{Form::submit('Save',['class'=>'btn btn-success'])}}
      </div>
    </form>
  </div>

  @if(isset($template))
    {{Form::open(['url'=>['mail_template',$id],'method'=>'DELETE','id'=>'FormDeleteTemplate','class'=>'text-right js-allow-double-submission'])}}
      {{Form::Submit('Delete',['class'=>'btn btn-sm btn-danger'])}}
    {{Form::close()}}
  @endif



@stop
@section('js')
@include('app.mail.common_js')
<script>
$(function(){
  $('#FormDeleteTemplate').submit(function(){
    return confirm('Confirm to delete this mail template?');
  })
})
</script>
@stop