@extends('adminlte::page')

@section('title', 'Customer Group Lists')

@section('content_header')
    <h1>View - Customer Group</h1>
@stop

@section('content')
  <div class="box box-primary box-pad" >
    <h3 class="box-title">Customer Group Lists</h3>
    <a href="{{url('/maintenance/customer_group/create')}}" class='btn btn-primary btn-theme-float-right' >Add New</a>
    <div class="box-body">
      <p>
        Please be reminded.<br>
        During <b>[Import Customer]</b>,<br>
        To capture excel's customer group field, the customer group value must be <b>added first</b> in system.<br>
        and the value in excel must be <b>equal</b> to system ( including <b>case</b> & <b>spacing</b> )<br>
        if not, the customer group will not be captured by system.
      </p>
      <table class="table table-hover table-theme table-valign">
        <tr>
          <th>#</th>
          <th>Group Name </th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td>{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->group_name }}</td>
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

@stop