@extends('adminlte::page')

@section('title', 'Mail Template Lists')

@section('content_header')
    <h1>View - Mail Template</h1>
@stop

@section('content')
  <div class="box box-primary box-pad" >
    <h3 class="box-title">Template Lists</h3>
    <a href="{{url('/mail_template/create')}}" class='btn btn-primary btn-theme-float-right' >Create Mail Template</a>
    <div class="box-body">
      <table class="table table-valign table-theme">
        <thead>
          <tr>
            <th>ID</th>
            <th>Template Name</th>
            <th>Option</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
          <tr>
            <td>{{$d->id}}</td>
            <td>{{$d->name}}</td>
            <td>{{Html::link(url('/mail_template/'.$d->id.'/edit'),'Edit',['class'=>'btn btn-sm btn-success'])}}</td>
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