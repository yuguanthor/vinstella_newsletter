@extends('adminlte::page')

@section('title', 'Payment')

@section('content_header')
    <h1>Add New - Payment</h1>
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Enter Payment Information</h3>

    <?php
    //Form::model($records, array('url' => array('information/agency', $id),'method'=>'put'))
    ?>
    {!! Form::open(['url'=>'payment']) !!}
      {!! csrf_field() !!}
      <div class="box-body">

        <div class="form-group row">
          <label for="customer_ic" class="col-sm-2 col-form-label">Customer IC/Passport : </label>
          <div class="col-md-4">
            {{Form::select('ic',sel_customer_lists(),null,['class'=>'form-control sel-customer','required','placeholder'=>'Select Customer'])}}
          </div>
          <div class="col-md-2">
            Available Amount: RM <span class='cust-availble-amount'> - </span>
          </div>
        </div>

        <div class="form-group row">
          <label for="invoice_no" class="col-sm-2 col-form-label">Invoice No : </label>
          <div class="col-md-4">
            {{Form::text('invoice_no',null,['class'=>'form-control','required'])}}
          </div>
        </div>

        <div class="form-group row">
          <label for="invoice_date" class="col-sm-2 col-form-label">Invoice Date : </label>
          <div class="col-md-4">
            {{Form::text('invoice_date',null,['class'=>'form-control datepicker','required'])}}
          </div>
        </div>

        <div class="form-group row">
          <label for="amount" class="col-sm-2 col-form-label">Amount : </label>
          <div class="col-md-4">
            {{Form::text('amount',null,['class'=>'form-control','required'])}}
          </div>
        </div>

      <div class="box-footer text-right">
        {{Form::submit('Add',['class'=>'btn btn-success'])}}
      </div>
    {!! Form::Close() !!}
  </div>

@stop

@section('js')
<script>
  $(function(){
    $('.sel-customer').select2({
      placeholder: "Select Customer"
    });

    $('.sel-customer').change(function(){
      var val = $(this).val();
      $.ajax({
        dataType:'json',
        type:'get',
        url: "{{url('ajax/get_customer_info')}}",
        data: { 'ic':val },
        success:function(data){
          $('.cust-availble-amount').html(data.remaining);
        }
      });
    })
  })

</script>
@stop