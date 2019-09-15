@extends('adminlte::page')

@section('title', 'Admin')

@section('content_header')
  @if(isset($data))
    <h1>Edit - Admin</h1>
  @else
    <h1>Add New - Admin</h1>
  @endif
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Enter Admin Information</h3>
    @if(isset($data))
    {{Form::model($data, array('url' => array('admin/account', $id),'method'=>'put'))}}
    @else
    {!! Form::open(['url'=>'admin/account']) !!}
    @endif
      {!! csrf_field() !!}
      <div class="box-body">

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Name : <i style="font-size:12px">(For login purpose)</i></label>
          <div class="col-md-4">
            @if(isset($data))
              {{Form::text('',$data->name,['class'=>'form-control','readonly'])}}
            @else
              {{Form::text('name',null,['class'=>'form-control','required'])}}
            @endif
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Password : <i style="font-size:12px">(For login purpose)</i></label>
          <div class="col-md-4">
            @if(isset($data))
              {{Form::text('password',null,['class'=>'form-control',''])}}
              <i style="font-size:10px">*Leave blank remain unchange</i>
            @else
              {{Form::text('password',null,['class'=>'form-control','required'])}}
            @endif
          </div>
        </div>

        @if(isset($data))
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Login Status : <i style="font-size:12px">(For login purpose)</i></label>
            <div class="col-md-4">
              {{Form::checkbox('status','1',null,['class'=>'checkbox-theme'])}}
            </div>
          </div>
        @endif
      </div>

      <div class="box-footer text-right">
        @if(isset($data))
          {{Form::submit('Save',['class'=>'btn btn-success'])}}
        @else
          {{Form::submit('Add',['class'=>'btn btn-success'])}}
        @endif

      </div>
    {!! Form::Close() !!}
  </div>

@stop

@section('js')
@stop