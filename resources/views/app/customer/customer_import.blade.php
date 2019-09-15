@extends('adminlte::page')

@section('title', 'Customer Lists')

@section('content_header')
    <h1>View - Users</h1>
@stop

@section('content')
  <div class="box box-info box-pad" >
    {{Form::open(array('url'=>'customer/import_file','files'=>'true'))}}
      <h4 class="box-title">Import File</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Excel File To Import: </label>
          <div class="col-md-4">
            {{Form::file('file',null,['class'=>'form-control'])}}
          </div>
        </div>
      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Get File Data',['class'=>'btn btn-sm btn-info'])}}
      </div>
    {{Form::close()}}
  </div>

  <?php
    $data = DB::table('customer_to_import')->get();
  ?>
  <div class="box box-primary box-pad" >
    {{Form::open(array('url'=>'customer/import_data','files'=>'true','id'=>'FormImportData','class'=>'js-allow-double-submission'))}}
      <h3 class="box-title">File Data</h3>
      <div class="box-body">
        <table class="table table-hover table-theme table-valign">
          <thead>
            <tr>
              <th class="center" style="width:20px;"><input type='checkbox' class="checkbox-theme chk-all"></th>
              <th>Customer Name</th>
              <th>Customer Email</th>
              <th>Customer Group</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $d)
              <tr>
                <td class="center">
                  <input name="chk[]" type='checkbox' value="{{$d->id}}" class="checkbox-theme chk-child">
                </td>
                <td>{{$d->name}}</td>
                <td>{{$d->email}}</td>
                <td>{{$d->customer_group}} | {{customer_group_name($d->customer_group)}}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Import into System',['class'=>'btn btn-sm btn-info btn-import-data'])}}
      </div>
    </form>
  </div>
@stop

@section('js')
<script>
$(function(){
  $('.chk-all').change(function(){
    var chk = $(this).is(':checked');
    $('.chk-child').prop('checked',chk);
  });

  $('#FormImportData').submit(function(e){
    var msg = `Confirm to import?
    only checked row will import.
    duplicate email will not insert, but update exists customer name & group.
    `;
    if(confirm(msg)){
      return true;
    }else{
      e.preventDefault();
    }
  });
})
</script>
@stop