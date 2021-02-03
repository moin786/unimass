<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('uploads/image/rupayan.ico') }}" type="image/x-icon">
    <title>RCU | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/square/blue.css') }}">
    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
    .copyright{
        position: fixed;
        bottom: 0;
        width: 100%;
        background-color: #00000073;
        color: #fff;
        padding: 10px;
        text-align: center;
    }
</style>
</head>
<body class="hold-transition login-page">
    <div id="section_login" style="background: url('{{ asset('backend/images/unimass-bg.jpg') }}') center center no-repeat; background-size: cover;">
        <div class="copyright">
            <p class="mb-0">© {{ date("Y") }} All Rights Reserved | Design & Developed by <a target="_blank" href="http://nextgenitltd.com/">NEXTGEN IT</a></p>
        </div>

        <div class="login-box" style="border:1px solid #999; background: rgba(255,255,255,.9)">
            <!-- /.login-logo -->
            <div class="login-box-body">
                <div class="login-logo mb-0 text-center">
                    <img style="width: 150px" src="{{ asset('backend/images/unimass.png') }}" alt="">
                    <a href="" style="color: #222; font-size: 30px; font-weight: 200px; display: block;"> Log In</a>
                </div>
                <p class="login-box-msg">Sign in to start your session</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group has-feedback">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <!-- <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                </label>
                            </div> -->
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-sm btn-block btn-flat" style="margin-top: 5px;">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

               <!--  <a href="#" onclick="alert('Please contact with Software Administration')">I forgot my password</a><br> -->
                {{-- <a href="" class="text-center">Register a new membership</a> --}}
            </div>
        </div>
    </div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{ asset('backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' /* optional */
        });

    });
</script>

</body>
</html>
