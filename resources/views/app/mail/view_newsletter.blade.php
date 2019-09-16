@extends('adminlte::page')

@section('title', 'Newsletter Lists')

@section('content_header')
  <h1>View - Newsletter Queue: [ {{$newsletter->name}} ]</h1>
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Customer Lists</h3>
    <span class="pull-right">
      ( {{ newsletter_count($newsletter->id,'success') }} / {{ newsletter_count($newsletter->id) }} )
    </span>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <thead>
          <tr>
            <th>Email</th>
            <th>Name</th>
            <th class="center">Status</th>
            <th class="center">isQueuing</th>
          </tr>
        </thead>
        <tbody>
        @foreach($newsletter_customer as $d)
        <?php
          $customer = DB::Table('customer')->where('id',$d->customer_id)->first();
        ?>
        <tr>
          <td>{{$customer->email}}</td>
          <td>{{$customer->name}}</td>
          <td class="center">
            {{$d->status}} | {{ newsletter_status_name($d->status) }}
          </td>
          <td class="center">
          @if($d->isQueuing==1)
            <i class="fa  fa-envelope"></i>
            <i class="fa  fa-angle-double-right"></i>
            <i class="fa  fa-angle-double-right"></i>
          @endif
          </td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="box-footer">

    </div>
  </div>
@stop

@section('js')
<script>
  $(function(){
    $('.sel-group').select2({
        placeholder: "Select Group",
        allowClear: true
      });
  });
</script>
@stop