@extends('adminlte::page')

@section('title', 'Mail Log')

@section('content_header')
    <h1>Mail Log</h1>
@stop

@section('content')
  <div class="box box-info box-pad" >
    <form id='FormSearch'>
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Date : </label>
          <div class="col-md-2">
            {{Form::text('search[date]',$search['date']??null,['class'=>'form-control datepicker'])}}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Mail To : </label>
          <div class="col-md-2">
            {{Form::text('search[maiL_to]',$search['maiL_to']??null,['class'=>'form-control'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Subject : </label>
          <div class="col-md-2">
            {{Form::text('search[subject]',$search['subject']??null,['class'=>'form-control'])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
      </div>
    </form>
  </div>

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Action Log Lists</h3>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <tr>
          <th>#</th>
          <th>Date Time </th>
          <th>Mail To</th>
          <th>Subject</th>
          <th>Body</th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td>{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->created_at }}</td>
          <td>{{ $d->mail_to }}</td>
          <td>{{ $d->subject }}</td>
          <td> {{Html::link( url('admin/mail_log/'.$d->id.'/view_body_html'), 'View',['target'=>'blank'])}} </td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="box-footer">
      {!! $data->appends(Input::except('page'))->links() !!}
    </div>
  </div>
@stop
