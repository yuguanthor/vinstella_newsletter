@extends('adminlte::page')

@section('title', 'System Configuration')

@section('content_header')
  <h1>System Configuration</h1>
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Edit Config Value</h3>
    {!! Form::open(['url'=>'admin/system_configuration','method'=>'POST']) !!}
      {!! csrf_field() !!}
      <div class="box-body">
        @foreach($sys_config as $d)
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">{{$d->config_name}} :</label>
          <div class="col-md-4">
            {{Form::text("config[$d->config_name]",$d->config_value,['class'=>'form-control'])}}
          </div>
        </div>
        @endforeach
      </div>

      <div class="box-footer text-right">
        {{Form::submit('Save',['class'=>'btn btn-success'])}}
      </div>
    {!! Form::Close() !!}
  </div>

@stop

@section('js')
@stop