@extends('adminlte::page')


@section('title', 'Customer')

@section('content_header')
  @if( isset($data) )
    <h1>Edit User</h1>
  @else
    <h1>Add New - Customer</h1>
  @endif
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Enter Customer Information</h3>

    @if(isset($data))
      {!! Form::model($data,['url'=>['customer',$id],'method'=>'PUT']) !!}
    @else
      {!! Form::open(['url'=>'customer']) !!}
    @endif
      {!! csrf_field() !!}
      <div class="box-body">

        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Name : </label>
          <div class="col-md-4">
            {{Form::text('name',null,['class'=>'form-control cust-name','required'])}}
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Email : </label>
          <div class="col-md-4">
            {{Form::text('email',null,['class'=>'form-control ','required'])}}
          </div>
        </div>

        <div class="form-group row">
          <label  class="col-sm-2 col-form-label">Group : </label>
          <div class="col-md-4">
            {{Form::select('customer_group',lists_customer_group(),null,['class'=>'form-control select2-group','required'])}}
          </div>
        </div>
      <div class="box-footer text-right">
        @if(isset($data))
          {{Form::submit('Update',['class'=>'btn btn-success'])}}
        @else
          {{Form::submit('Add',['class'=>'btn btn-success'])}}
        @endif
      </div>
    {!! Form::Close() !!}


@stop
@section('js')
<script>
  $(function(){
    $('.cust-ic').blur(function(){
      $.ajax({
        dataType:'json',
        type:'get',
        url: "{{url('ajax/get_customer_info')}}",
        data: { 'ic':$(this).val() },
        success:function(data){
          if(data != false){
            $('.cust-name').val(data.name);
            $('.div-cust-amt').show();
            $('.cust-remaining-amount').html(data.remaining);
          }else{
            $('.cust-name').val('');
            $('.div-cust-amt').hide();
          }
        }
      });
    })

    $('.select2-group').select2({
      placeholder:'Select Group',
      allowClear: true
    })
  })

</script>
@stop