@extends('adminlte::page')

@section('title', 'Report Customer')

@section('content_header')
    <h1>Report - Customer</h1>
@stop

@section('content')
  <!-- Search -->
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
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
        {{Form::Submit('Export XLS',['class'=>'btn btn-sm btn-info','name'=>'export_xls'])}}
      </div>
    </form>
  </div>

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Customer Report until <b>{{ display_date($to) }}</b> </h3>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <thead>
          <tr>
            <th class="center">#</th>
            <th>Customer IC / Passport </th>
            <th>Customer Name </th>
            <th class="center">Accumulated (RM)</th>
            <th class="center">Used (RM)</th>
            <th class="center">Remaining (RM)</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $setIc='';
            $count_customer=1;
            $count_invoice=1;
          ?>
          @foreach($payment as $k => $p)
            @if($setIc=='' || $setIc!=$p->ic)
            <tr class="tr-body-header bold">
              <td class="center">{{$count_customer}}</td>
              <td class="">{{$p->ic}}</td>
              <td class="">{{$p->customer_name}}</td>
              <td align=right>{{ number_format( sum_customer_package($p->ic, $to) , 2 ) }}</td>
              <td align=right>{{ number_format( sum_customer_payment($p->ic, $to) , 2 ) }}</td>
              <td class="" align=right>{{ number_format( customer_remaining($p->ic, $to) , 2 ) }}</td>
            </tr>
            <tr class="tr-no-padding">
              <td colspan=2></td>
              <td>Invoice Date</td>
              <td>Invoice No</td>
              <td align=right>Invoice Amount (RM)</td>
            </tr>
            <?php
              $count_customer++;
              $count_invoice=1;
            ?>
            @endif
            <tr>
              <td colspan=2 align=right>
                <span class="italic rounded">{{$count_invoice}}</span>
              </td>
              <td>{{ display_date($p->invoice_date) }}</td>
              <td>{{$p->invoice_no}}</td>

              <td align=right>{{ number_format($p->amount,2) }}</td>
            </tr>
            <?php
              $setIc=$p->ic;
              $count_invoice++;
            ?>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="box-footer">
      @if($payment!=[])
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