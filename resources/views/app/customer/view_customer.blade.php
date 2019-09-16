@extends('adminlte::page')

@section('title', 'Customer Lists')

@section('content_header')
    <h1>View - Customer</h1>
@stop

@section('content')
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Customer Name : </label>
          <div class="col-md-4">
            {{Form::text('search[name]',$search['name']??null,['class'=>'form-control sel-cust','placeholder'=>''])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Customer Email : </label>
          <div class="col-md-4">
            {{Form::text('search[mail]',$search['mail']??null,['class'=>'form-control','placeholder'=>''])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Group : </label>
          <div class="col-md-4">
            {{Form::select('search[customer_group]',sel_customer_group(),$search['customer_group']??null,['class'=>'form-control sel-group','placeholder'=>''])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
      </div>
    </form>
  </div>
  <div class="box box-primary box-pad" >
    <h3 class="box-title">Customer Lists</h3>
    <a href="{{url('/customer/create')}}" class='btn btn-primary btn-theme-float-right' >Add New</a>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <tr>
          <th>#</th>
          <th>Customer Name </th>
          <th>Email</th>
          <th>Customer Group</th>
          <th>Option</th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td>{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->name }}</td>
          <td>{{ $d->email }}</td>
          <td>{{ $d->customer_group }} | {{ customer_group_name($d->customer_group) }}</td>

          <td>{{ Html::link(url('customer/'.$d->id.'/edit'),'edit',['class'=>'btn btn-sm btn-success']) }}</td>
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
    $('.sel-cust').select2({
        placeholder: "Select Customer",
        allowClear: true
      });

      $('.sel-group').select2({
        placeholder: "Select Group",
        allowClear: true
      });
  });

  function confirmCancel(id){
    if( confirm('Confirm to cancel ? ') ){
      var url = "{{url('/customer')}}" + '/' + id + '/cancel';
      location.href = url;
    }
  }
</script>
@stop