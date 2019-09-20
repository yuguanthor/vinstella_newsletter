@extends('adminlte::page')

@section('title', 'Newsletter Lists')

@section('content_header')
    <h1>View - Newsletter Queue</h1>
@stop

@section('content')
  <div class="box box-info box-pad" >
    <form id='FormSearch' class="js-allow-double-submission">
      <h4 class="box-title">Search</h4>
      <div class="box-body">
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Newsletter Name</label>
          <div class="col-md-2">
            {{Form::text('search[name]' ,$search['name']??null,['class'=>'form-control','placeholder'=>''])}}
          </div>
        </div>
        <div class="form-group row">
          <label class="col-sm-2 col-form-label">Status</label>
          <div class="col-md-2">
            {{Form::select('search[status]', sel_mail_status() ,$search['status']??null,['class'=>'form-control','placeholder'=>''])}}
          </div>
        </div>

      </div>
      <div class="box-footer text-right">
        {{Form::Submit('Search',['class'=>'btn btn-sm btn-info'])}}
      </div>
    </form>
  </div>
  <div class="box box-primary box-pad" >
    <h3 class="box-title">Mail Lists</h3>
    <a href="{{url('/mail/create')}}" class='btn btn-primary btn-theme-float-right' >Create Mail</a>
    <div class="box-body">
      <table class="table table-hover table-theme table-valign">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="center">Email Count</th>
            <th class="center">Success</th>
            <th class="center">Failure</th>
            <th class="center">Pending</th>
            <th class="center">Status</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
        @foreach($data as $d)
        <tr>
          <td>{{$d->id}}</td>
          <td>{{$d->name}}</td>
          <td class="center">{{ newsletter_count($d->id) }}</td>
          <td class="center">{{ newsletter_count($d->id,'success') }}</td>
          <td class="center">{{ newsletter_count($d->id,'error') }}</td>
          <td class="center">{{ newsletter_count($d->id,'pending') }}</td>
          <td class="center">{{ newsletter_status_name($d->status) }}</td>
          <td>{{Html::link(url('mail/'.$d->id),'View',['class'=>'btn btn-sm btn-link'])}}</td>
        </tr>
        @endforeach
        </tbody>
      </table>
    </div>
    <div class="box-footer">
      {!! $data->appends(Input::except('page'))->links() !!}

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