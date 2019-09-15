@extends('adminlte::page')

@section('title', 'View Admin')

@section('content_header')
    <h1>View - Admin</h1>
@stop

<?php
  $admin_lists = DB::table('users')->pluck('name','id');
  $status_lists = ['1'=>'Active','0'=>'Inactive'];
?>

@section('content')
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Admin : </label>
          <div class="col-md-3">
            {{Form::select('search[admin]',$admin_lists,$search['admin']??null,['class'=>'form-control sel-name','placeholder'=>'Select Name'])}}
          </div>
        </div>

        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Status : </label>
          <div class="col-md-3">
            {{Form::select('search[status]',$status_lists,$search['status']??null,['class'=>'form-control sel-status','placeholder'=>'Select Status'])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
      </div>
    </form>
  </div>

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Admin Lists</h3>
    <a href="{{url('/admin/account/create')}}" class='btn btn-primary btn-theme-float-right' >Add New</a>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <tr>
          <th class="center">#</th>
          <th>Admin Name </th>

          <th class="center">Status</th>
          <th>Option</th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td class="center">{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->name }}</td>

          <td class="center">{!! html_status_icon($d->status) !!}</td>
          <td>
            {{Html::link(url('admin/account/'.$d->id.'/edit'),'Edit',['class'=>'btn btn-sm btn-success'])}}
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="box-footer">
      {!! $data->appends(Input::except('page'))->links() !!}
    </div>
  </div>
@stop

@section('js')
<script>
  $(function(){
    $('.sel-name').select2({
      placeholder: "Select Name",
      allowClear: true
    });
    $('.sel-status').select2({
      placeholder: "Select Status",
      allowClear: true
    });
  });

</script>
@stop