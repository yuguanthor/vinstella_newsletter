@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

<?php
  $admin_count = DB::table('users')->where('status',1)->get()->count();
  $pending_newsletter = DB::table('newsletter')->where('status',0)->get();
?>

@section('content')
    <h4>
      Newsletter on queue
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