@extends('adminlte::page')

@section('title', 'Newsletter')

@section('content_header')
  <h1>Create Newsletter</h1>
@stop

@section('content')
  <?php
    $customer_group = $search['customer_group'] ?? null;
    $email_template = $search['email_template'] ?? null;
  ?>
  <div class="box box-primary box-pad" >
    <h3 class="box-title">Filter Customer Group</h3>
    {!! Form::open(['url'=>'mail/create','method'=>'GET']) !!}
      <div class="box-body">
        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Customer Group : </label>
          <div class="col-md-10">
            @php( $sel_customer_group = sel_customer_group() )
            @php( $sel_customer_group->prepend('All','A') )
            {{Form::select('search[customer_group][]',$sel_customer_group,$customer_group,['class'=>'form-control select2-group','required','multiple'=>'true'])}}
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Email Template : </label>
          <div class="col-md-10">
            {{Form::select('search[email_template]',sel_mail_template(),$email_template,['class'=>'form-control select2-template','placeholder'=>'Select a Template'])}}
            <i>*Non-mandatory</i>
          </div>

        </div>
      </div>
      <div class="box-footer text-center">
        {{Form::submit('View',['class'=>'btn btn-success'])}}
      </div>
    {!! Form::Close() !!}
  </div>

  @if(count($customer) != 0)
  <div class="box box-primary box-pad clearfix">
    <h3 class="box-title">Prepare Newsletter</h3>
    {{Form::open(['url'=>'mail','id'=>'FormEmail','files'=>true])}}
      <div class="box-header">
        <button class="btn btn-sm btn-default toggle-email-lists" type="button" data-toggle="collapse" data-target="#collapseEmailLists" aria-expanded="false" aria-controls="collapseExample">
          <i class="fa fa-bars"></i> Email To Send ( <span class="customer_chk_count">{{$customer->count()}}</span> )
        </button>
      </div>
      <div class="box-body">

        <div id="collapseEmailLists" class="collapse side-email-lists">
          <table class="table table-theme table-valign table-striped table-small">
            <thead>
              <tr>
                <th>{{Form::checkbox('',1,1,['class'=>'chk-all'])}}</th>
                <th>Email ({{$customer->count()}})</th>
                <th>Name</th>
              </tr>
            </thead>
            @php($setGroup='')
            <tbody>
              @foreach($customer as $d)
                @if($setGroup != $d->customer_group)
                  <tr>
                    <td></td>
                    <td>{{$d->customer_group}} | {{customer_group_name($d->customer_group)}} </td>
                    <td></td>
                  </tr>
                @endif
                <tr>
                  <td>{{Form::checkbox('customer_id[]',$d->id,1,['class'=>'chk-child'])}}</td>
                  <td>{{$d->email}}</td>
                  <td>{{$d->name}}</td>
                </tr>
                @php($setGroup=$d->customer_group)
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="col-md-12">
          <span class="email-title-text">Newsletter Name <i>(For display in dashboard/reference only)</i></span>
          {{Form::text('name',$template->subject??null,['class'=>'form-control','required','placeholder'=>'Newsletter Name'])}}
          <br><br>

          <span class="email-title-text">Subject</span>
          {{Form::text('subject',$template->subject??null,['class'=>'form-control','required','placeholder'=>'Email Subject'])}}
          <br>

          <div class="ckeditor-wrapper-div">
            <span class="email-title-text">Body</span>
            {{Form::textarea('body',$template->body??null,['id'=>'email-body','required'])}}

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
        {{Form::Button('Test Mail',['onclick'=>'TestMail();','class'=>'btn btn-info center'])}}
        {{Form::submit('Queue Newsletter',['onclick'=>'QueueMail();','class'=>'btn btn-success pull-right'])}}
      </div>
    </form>
  </div>

  @endif


@stop
@section('js')
@include('app.mail.common_js')
<script>
$(function(){
  $('.select2-group').select2({
    placeholder:'Please select at least 1 group',
    allowClear:true
  });
  $('.select2-customer').select2({
    placeholder:'Please select at least 1 customer',
    allowClear:true
  });
  $('.select2-template').select2({
    placeholder:'Select Template',
    allowClear:true
  });
  $('.chk-all').change(function(){
    var chk = $(this).is(':checked');
    $('.chk-child').prop('checked',chk);
    updateChkCount();
  })
  $('.chk-child').change(function(){
    updateChkCount();
  });

  $(document).click(function (event) {
    var clickover = $(event.target);
    var _opened = $("#collapseEmailLists").hasClass("in");
    if (
      _opened === true && !clickover.hasClass("side-email-lists") &&
      !$(event.target).closest('#collapseEmailLists').length
    ) {
      $('#collapseEmailLists').collapse('toggle');
    }
  });

  CKEDITOR.instances['email-body'].on('contentDom', function() {
      this.document.on('click', function(event){
          //your code
          $('#collapseEmailLists').collapse('hide');
      });
  });

});

function updateChkCount(){
  var count = $('.chk-child').filter(':checked').length;
  $('.customer_chk_count').html(count);
}

</script>
@stop