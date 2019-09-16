@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

<?php
  $admin_count = DB::table('users')->where('status',1)->get()->count();
  $pending_newsletter = DB::table('newsletter')->where('status',0)->get();

  $mail_redirect = get_mail_redirect();
  $previous_cron = DB::table('cron_log')->orderBy('id','DESC')->first();
  $next_cron = false;
  if($previous_cron != null){
    $previous_time = $previous_cron->start;
    $next_cron = strtotime($previous_cron->start. '+ 15 minutes');
    $next_cron = date('Y-m-d H:i:s',$next_cron);
  }

?>

@section('content')
    <h4>
      Newsletter on queue
      @if($mail_redirect!=false)
        <br><span class="italic small">* [All Mail Out will redirect to <b>{{$mail_redirect}}</b>]</span>
      @endif
      {{Html::link(url('api/send_newsletter'),'Send Newsletter (Manual)',['class'=>'btn btn-sm btn-warning pull-right','target'=>'blank'])}}
    </h4>

    <div class="row">
      @forelse($pending_newsletter as $d)
        <?php
          $sending = DB::Table('newsletter_to_send')->first()->newsletter_id;
          $bgColor = $sending == $d->id ? 'bg-red' : 'bg-aqua' ;
          $icon = $sending == $d->id ? 'fa-envelope animate-lr' : 'fa-envelope' ;
        ?>
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon {{$bgColor}}"><i class="fa {{$icon}}"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">{{$d->name}}</span>
              <span class="info-box-number">
                  Total: {{ newsletter_count($d->id,'all') }}
                  <div class="info-box-desc">
                    <span class="pull-left text-theme-success">Success : {{ newsletter_count($d->id,'success') }}</span>
                    <span class="pull-right text-theme-pending">Pending : {{ newsletter_count($d->id,'pending') }}</span>
                  </div>

                </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        @empty
        <div class="alert alert-info alert-no-mail">No pending mail</div>
        @endforelse

        <center>
          [Next Cron Time: {{$next_cron}}]
          <div>
            <span class="minutes" id="minute">-</span> Minutes
            <span class="seconds" id="second">-</span> Seconds
          </div>
        </center>

        <div class="col-md-12" style="border-top:1px solid black;margin-bottom:10px"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-person"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Admin</span>
              <span class="info-box-number">{{$admin_count}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
@stop


@section('js')
<script>
  $(function(){
    var deadline = new Date("{{$next_cron}}").getTime();
    var x = setInterval(function() {
      var now = new Date().getTime();
      var t = deadline - now;
      var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((t % (1000 * 60)) / 1000);
      document.getElementById("minute").innerHTML = minutes;
      document.getElementById("second").innerHTML =seconds;
      if (t < 0) {
        clearInterval(x);
        document.getElementById("minute").innerHTML ='0' ;
        document.getElementById("second").innerHTML = '0'; }
      }, 1000
    );

  })

</script>
@stop