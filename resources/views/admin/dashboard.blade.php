@extends('admin.layouts.app')

@push('css_lib')
{{-- Daterange picker --}}
<link rel="stylesheet"
href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

<!-- Morris chart -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/morris.js/morris.css') }}">
<style>
    .align-items-center {
        align-items: center;
    }

    .m-0 {
        margin: 0 !important;
    }

    .bg-transparent {
        background: transparent !important;
    }

    .daterangepicker .daterangepicker_input i {
        position: absolute;
        left: 8px;
        top: 5px;
    }

    .daterangepicker .input-mini {
        padding: 0 6px 0 28px !important;
    }

    .daterangepicker .btn-sm {
        padding: 2px 10px;
    }

    .table tbody tr td,
    .table tbody tr th,
    .table tfoot tr td,
    .table tfoot tr th {
        padding: 10px !important;
    }

    .btn-group-sm > .btn, .btn-sm {
        padding: 2px 10px;
    }

</style>
@endpush

@section('content')

@php
$ses_other_user_id  = Session::get('user.ses_other_user_pk_no');
$ses_other_user_name  = Session::get('user.ses_other_full_name');
$ses_role_lookup_pk_no  = Session::get('user.ses_role_lookup_pk_no');
$user_type  = Session::get('user.user_type');
$role_id = Session::get('user.ses_role_lookup_pk_no');
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="form-row align-items-center">
        <div class="col-md-3">

            <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-md-4">
            <div class="">
                <div class="input-group">
                    <span class="input-group-addon">From</span>
                    <input type="text" class="form-control datepicker" id="date_from" name="" value=""
                    placeholder="dd-mm-yyyy">
                    <span class="input-group-addon">To</span>
                    <input type="text" class="form-control datepicker" id="date_to" name="" value=""
                    placeholder="dd-mm-yyyy">
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <input type="button" class="btn btn-success btn-sm " id="btnSerachInfo" name="" value="search"
            placeholder="dd/mm/yyyy">

        </div>
        <div class="col-md-3">
            <ol class="breadcrumb p-0 m-0 text-right bg-transparent">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">LeadAgent-Dashboard</li>
            </ol>
        </div>
    </div>

</section>



<!-- Main content -->
<section class="content" id="dashboard_info">


    @if($ses_other_user_id !="")
    <div class="row">
        <div class="col-md-12">
            <div class="" style="background-color: #fff;
            padding: 10px; text-align: center;">

            <span class="text-danger">{{ $ses_other_user_name }}</span> | <a class="btn btn-xs btn-danger"
            href="{{ route('admin.dashboard') }}">Back
        To My Dashboard</a>
    </div>
</div>
</div>
@endif

@include("admin.dashboard_info")


</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- Morris.js charts -->
<script src="{{ asset('backend/bower_components/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/morris.js/morris.min.js') }}"></script>

{{-- Daterange --}}
<script
src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('backend/dist/js/pages/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('backend/dist/js/demo.js') }}"></script>
@endpush

@push('js_custom')
<script>

  const onHashChange = useCallback(() => {
    const confirm = window.confirm(
      'Warning - going back will cause you to loose unsaved data. Really go back?',
      );
    window.removeEventListener('hashchange', onHashChange);
    if (confirm) {
      setTimeout(() => {
        window.history.go(-1);
    }, 1);
  } else {
      window.location.hash = 'no-back';
      setTimeout(() => {
        window.addEventListener('hashchange', onHashChange);
    }, 1);
  }
}, []);

  useEffect(() => {
    window.location.hash = 'no-back';
    setTimeout(() => {
      window.addEventListener('hashchange', onHashChange);
  }, 1);
    return () => {
      window.removeEventListener('hashchange', onHashChange);
  };
}, []);

</script>


<script>
    $(function () {
        $('.datepicker').datepicker({
            autoclose: true,
            todayBtn: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });

        $(document).on("click", ".small-box-footer", function (e) {
            e.preventDefault();
            var redirect_to = $(this).attr("href");
            var date_from = $("#date_from").val();
            var date_to = $("#date_to").val();

            fdate_param = tdate_param = "";
            if (date_from != "") {
                var fdate_param = "/0/" + date_from;
                var tdate_param = "/" + date_to;
            }
            window.location = redirect_to + fdate_param + tdate_param;
        });
        $(document).on("click", ".routeSetUp", function (e) {
            e.preventDefault();
            var redirect_to = $(this).attr("href");
            var date_from = $("#date_from").val();
            var date_to = $("#date_to").val();

            fdate_param = tdate_param = "";
            if (date_from != "") {
                var fdate_param = "/" + date_from;
                var tdate_param = "/" + date_to;
            }
            
            window.location = redirect_to + fdate_param + tdate_param;
        });

        $(document).on("click", "#btnSerachInfo", function (e) {
            e.preventDefault();
            var date_from = $("#date_from").val();
            var date_to = $("#date_to").val();
            if (date_from == "") {
                alert("You did not select any Date");
            } else {
                $.ajax({
                    data: {date_from: date_from, date_to: date_to},
                    url: "{{ route('dashboard_info') }}",
                    type: 'post',
                    beforeSend: function () {
                        $.blockUI({
                            message: '<i class="icon-spinner4 spinner"></i>',
                            overlayCSS: {
                                backgroundColor: '#1b2024',
                                opacity: 0.8,
                                zIndex: 999999,
                                cursor: 'wait'
                            },
                            css: {
                                border: 0,
                                color: '#fff',
                                padding: 0,
                                zIndex: 9999999,
                                backgroundColor: 'transparent'
                            }
                        });
                    },
                    success: function (data) {
                        $.unblockUI();
                        $("#dashboard_info").html(data);

                    },
                    error: function (data) {

                    }
                });
            }
        });
    });
</script>

@endpush
