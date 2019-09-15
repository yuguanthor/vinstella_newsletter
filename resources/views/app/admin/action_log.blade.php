@extends('adminlte::page')

@section('title', 'Action Log')

@section('content_header')
    <h1>Action Log</h1>
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
          <label class="col-sm-2 col-form-label">By : </label>
          <div class="col-md-2">
            {{Form::select('search[uid]',lists_users(),$search['uid']??null,['class'=>'form-control','placeholder'=>'-'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Action : </label>
          <div class="col-md-2">
            {{Form::select('search[action]',lists_log_action(),$search['action']??null,['class'=>'form-control','placeholder'=>'-'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Summary : </label>
          <div class="col-md-4">
            {{Form::text('search[summary]',$search['summary']??null,['class'=>'form-control'])}}
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
          <th>By</th>
          <th>Action</th>
          <th>Summary</th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td>{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->created_at }}</td>
          <td>{{ user_name($d->created_by) }}</td>
          <td>{{ $d->action }}</td>
          <td>{{ $d->summary }}</td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="box-footer">
      {!! $data->appends(Input::except('page'))->links() !!}
    </div>
  </div>
@stop
