@extends('adminlte::page')

@section('title', 'Report Invoice Payment')

@section('content_header')
    <h1>Report - Invoice Payment</h1>
@stop

@section('content')
  <!-- Search -->
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Invoice Date From : </label>
          <div class="col-md-2">
            {{Form::text('search[from]',$search['from']??null,['class'=>'form-control datepicker'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Invoice Date To : </label>
          <div class="col-md-2">
            {{Form::text('search[to]',$search['to']??null,['class'=>'form-control date-to datepicker'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Customer IC : </label>
          <div class="col-md-6">
            {{Form::select('search[ic][]',sel_customer_lists(),$search['ic']??null,['class'=>'form-control sel-cust-ic','multiple'=>'multiple'])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
        {{Form::Submit('Export XLS',['class'=>'btn btn-sm btn-info','name'=>'export_xls','value'=>'export_xls'])}}
      </div>
    </form>
  </div>


  <div class="box box-primary box-pad" >
    <h3 class="box-title">Payment Report</h3>
    <div class="box-body">

      <table class="table table-bordered bold">
        <tr>
          <td class="center">Total Number</td>
          <td class="center">Total Amount (RM)</td>
        </tr>
        <tr>
          <td class="center">{{ $total_payment->count??0 }}</td>
          <td class="center">{{ number_format( $total_payment->total??0 , 2 ) }}</td>
        </tr>
      </table>

      <div class="mar-top-10"></div>

      <table class="table table-hover table-theme table-valign">
        <thead>
          <tr>
            <th class="center">#</th>
            <th class="center">Invoice Date</th>
            <th class="center">Invoice No</th>
            <th>Customer IC / Passport </th>
            <th>Customer Name </th><th class="center">Amount (RM)</th>
          </tr>
        </thead>
        <tbody>
          @forelse( $payment as $k => $d)
            <tr>
              <td class="center">{{ $payment->firstItem() + $k }}</td>
              <td>{{ display_date($d->invoice_date) }}</td>
              <td>{{$d->invoice_no}}</td>
              <td>{{$d->ic}}</td>
              <td>{{$d->customer_name}}</td>
              <td align=right>{{ number_format($d->amount,2) }}</td>
            </tr>
          @empty
            <tr>
              <td class='center' colspan=100>--NO DATA--</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="box-footer">
      @if( count($payment) > 0)
        {!! $payment->appends(Input::except('page'))->links() !!}
      @endif
    </div>
  </div>
@stop

@section('js')
<script>
 $(function(){
   $('.sel-cust-ic').select2({
      placeholder: "Select Customer",
      allowClear: true
   });

   $('#FormSearch').submit(function(){
     var to = $('.date-to').val();
     if( to == ''){
       $('.date-to').datepicker('update', new Date());
     }
   })
 })
</script>
@stop