<style>
  .flash-message{font-size:15px;}

  .container-fluid{padding:0;}

  .alert{
      padding:10px;
      margin-bottom:10px;
  }

  .btn-dismiss-alert-box{
      float:right;
      background:none;
      border:none;
      position:relative;
      top:3px;
      font-size:12px;
  }
</style>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@if ( !$errors->any() && isset($errors) && count($errors) > 0)
  <div class="alert alert-info alert-dismissible" role="alert">
    {!! alertBox_CloseBtn() !!}
    <ul>
      @foreach ($errors as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>

  </div>
@endif

@if (session('status'))
  <div class="alert alert-info alert-dismissible" role="alert">
    {!! alertBox_CloseBtn() !!}
    {{ session('status') }}

  </div>
@endif

@foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(Session::has($msg))
    <div class="alert alert-{{$msg}} alert-dismissible" role="alert">
      {!! alertBox_CloseBtn() !!}
      {!! nl2br(Session::get($msg)) !!}

    </div> <!-- end .flash-message -->
  @endif
@endforeach


<?php
  function alertBox_CloseBtn(){
    return '
      <button type="button" class="btn-dismiss-alert-box" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true"><i class="fa fa-times"></i></span>
      </button>
    ';
  }
?>