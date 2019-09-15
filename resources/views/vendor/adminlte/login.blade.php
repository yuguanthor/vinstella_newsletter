@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <style>
        .login-page, .register-page{
            background: #83a4d4;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to bottom right, #FEFEFE, #DAF3EF);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to bottom right, #FEFEFE, #DAF3EF); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
        }
        .login-box{

        }
        .login-logo, .register-logo {
            background: #83a4d4;  /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #D0F2F0, #E9F5F4);  /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #D0F2F0, #E9F5F4); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            margin-bottom:0px;
            padding:10px;

            border: 1px solid #7eb2d1;
            border-radius:5px;
            border-bottom-right-radius: 0px;
            border-bottom-left-radius: 0px;
        }
        .login-box-body{
            border: 1px solid #7eb2d1;
            border-top:none;
            border-radius:5px;
            border-top-right-radius: 0px;
            border-top-left-radius: 0px;
        }
        .login-box-msg{
            font-size:2em;
        }

        .login-box-status-div{
            width: 360px;
            margin: 5px auto;
        }
    </style>


    <div class="login-box">


        @if($errors->has('login_status'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-exclamation-triangle"></i> Alert</h4>
            {{ $errors->first('login_status') }}
        </div>
        @endif


        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>



        <!-- /.login-logo -->
        <div class="login-box-body">
            <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                {!! csrf_field() !!}

                <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                    <input name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="Login ID">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <input type="password" name="password" class="form-control"
                           placeholder="{{ trans('adminlte::adminlte.password') }}">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit"
                                class="btn btn-primary btn-block btn-flat">{{ trans('adminlte::adminlte.sign_in') }}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
