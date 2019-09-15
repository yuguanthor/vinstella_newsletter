@extends('adminlte::page')

@section('title', 'Payment Lists')

@section('content_header')
    <h1>View - Payments</h1>
@stop

@section('content')
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Invoice No : </label>
          <div class="col-md-2">
            {{Form::text('search[invoice_no]',$search['invoice_no']??null,['class'=>'form-control'])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Customer IC : </label>
          <div class="col-md-4">
            {{Form::select('search[ic]',sel_customer_lists(),$search['ic']??null,['class'=>'form-control sel-cust','placeholder'=>'Select Customer'])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
      </div>
    </form>
  </div>

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Payments Lists</h3>
    <a href="{{url('/payment/create')}}" class='btn btn-primary btn-theme-float-right' >Add New</a>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <tr>
          <th>#</th>
          <th>Customer IC / Passport </th>
          <th>Customer Name </th>
          <th>Invoice No</th>
          <th>Invoice Date</th>
          <th>Amount (RM)</th>
          <th>Cancel</th>
          <th>Option</th>
        </tr>
        @foreach($data as $key => $d)
        <tr>
          <td>{{ $data->firstItem() + $key }}</td>
          <td>{{ $d->ic }}</td>
          <td>{{ customer_name($d->ic) }}</td>
          <td>{{ $d->invoice_no }}</td>
          <td>{{ display_date($d->invoice_date) }}</td>
          <td>{{ $d->amount }}</td>
          <td>{{ $d->cancel }}</td>
          <td>
            @if($d->cancel)
              <i>Cancelled</i>
            @else
              {{Form::Button('Cancel',['class'=>'btn btn-sm btn-danger','onclick'=>'confirmCancel("'.$d->id.'");'])}}
            @endif
          </td>
        </tr>
        @endforeach
      </table>
    </div>
    <div class="box-footer">

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
  });

  function confirmCancel(id){
    if( confirm('Confirm to cancel ? ') ){
      var url = "{{url('/payment')}}" + '/' + id + '/cancel';
      location.href = url;
    }
  }
</script>
@stop