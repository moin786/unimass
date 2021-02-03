<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{env('APP_FIRST_NAME')}} {{env('APP_LAST_NAME')}}@yield('title')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Google Font -->

    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/jvectormap/jquery-jvectormap.css') }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

    @stack('css_lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/toastr.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('backend/dist/css/AdminLTE.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
     <link rel="stylesheet" href="{{ asset('backend/dist/css/skins/_all-skins.min.css') }}">

     <!-- Custom CSS -->
     <link rel="stylesheet" href="{{ asset('backend/dist/css/custom.css') }}">

     <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>

<![endif]-->

@stack('css_custom')
</head>

<body class="hold-transition skin-green-light sidebar-mini">
    <div class="wrapper">

        @include('admin.layouts.partial.header')

        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            @include('admin.layouts.partial.sidenav')

            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            @include('admin.layouts.partial.footer')
        </footer>

    <!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
 </div>
 <!-- ./wrapper -->
@include('admin.components.modal')
 <!-- jQuery 3 -->
 <script src="{{ asset('backend/bower_components/jquery/dist/jquery.min.js') }}"></script>
 <!-- jQuery UI 1.11.4 -->
 <script src="{{ asset('backend/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
 <script>
    $.widget.bridge('uibutton', $.ui.button);

</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('backend/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('backend/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('backend/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('backend/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('js/jquery.blockUI.js') }}"></script>
@stack('js_lib')

<!-- AdminLTE App -->
<script src="{{ asset('js/toastr.min.js') }}"></script>
<script src="{{ asset('backend/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/helper.js') }}"></script>

@stack('js_custom')

</body>

</html>
