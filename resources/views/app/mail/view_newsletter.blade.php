@extends('adminlte::page')

@section('title', 'Newsletter Lists')

@section('content_header')
  <h1>View - Newsletter Queue: [ {{$newsletter->name}} ]</h1>
@stop

@section('content')

  <div class="box box-primary box-pad" >
    <h3 class="box-title">Customer Lists</h3>
    <span class="pull-right">
      ( {{ newsletter_count($id,'success') }} / {{ newsletter_count($id) }} )
    </span>
    <div class="box-body">

      <div class="col-md-12 box-pad">
        Filter:
        {{Html::link(url('mail/'.$id.'?status=1'),'Success ('.newsletter_count($id,'success').')',['class'=>'btn btn-sm btn-success'])}}
        {{Html::link(url('mail/'.$id.'?status=2'),'Error ('.newsletter_count($id,'error').')',['class'=>'btn btn-sm btn-danger'])}}
        {{Html::link(url('mail/'.$id.'?status=0'),'Pending ('.newsletter_count($id,'pending').')',['class'=>'btn btn-sm btn-default'])}}
      </div>

      <table class="table table-hover table-theme table-valign">
        <thead>
          <tr>
            <th>No.</th>
            <th>Email</th>
            <th>Name</th>
            <th class="center">Status</th>
            <th class="center">Message</th>
            <th class="center">isQueuing</th>
          </tr>
        </thead>
        <tbody>
        @foreach($newsletter_customer as $key => $d)
        <?php
          $customer = DB::Table('customer')->where('id',$d->customer_id)->first();
        ?>
        <tr>
          <td>{{ $newsletter_customer->firstItem() + $key }}</td>
          <td>{{$customer->email}}</td>
          <td>
            {{Html::link(url('customer/'.$customer->id.'/edit'),$customer->name,['target'=>'blank'])}}

          </td>
          <td class="center">
            {{$d->status}} | {{ newsletter_status_name($d->status) }}
          </td>
          <td>{{$d->status_text}}</td>
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
      {!! $newsletter_customer->appends(Input::except('page'))->links() !!}
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