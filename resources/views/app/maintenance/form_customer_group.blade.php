@extends('adminlte::page')


@section('title', 'Add New Customer Group')

@section('content_header')
  <h1>Add New - Customer Group</h1>
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Enter Customer Group</h3>
      {!! Form::open(['url'=>'maintenance/customer_group']) !!}
      {!! csrf_field() !!}
      <div class="box-body">
        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Group Name : </label>
          <div class="col-md-4">
            {{Form::text('group_name',null,['class'=>'form-control cust-name','required'])}}
          </div>
        </div>

      <div class="box-footer text-right">
        {{Form::submit('Add',['class'=>'btn btn-success'])}}
      </div>
    {!! Form::Close() !!}
  </div>


@stop
@section('js')
@stop