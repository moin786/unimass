@extends('admin.layouts.app')

@push('css_lib')

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
        .content_text {
            padding: 15px;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px;
        }

        .heading {
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Project Wise Report</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Report Module</a></li>
            <li class="active">Project Wise Report</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="search_details" class="content_text" style="padding-bottom: 0px; ">
        <div class="row">
            <div class="col-xs-12">
                <form action="{{ route('export_csv_project_report') }}" id="frmSearch" method="post">
                    {{ csrf_field() }}
                    <div class="box box-success mb-0">
                        <div class="box-header with-border">
                            <h3 class="box-title">Search Panel</h3>
                        </div>

                        {{-- Search Engin --}}
                        <div class="box-body">
                            <div class="form-row">
                                @include("admin.report_module.search_panel")
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12" id="search_result_con">
                @include("admin.report_module.project_report_result")
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@push('js_lib')

    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script
        src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endpush
@push('js_custom')
    <script>
        $(function () {
            var datepickerOptions = {
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: true,
                todayHighlight: true,
            };
            $('.select2').select2();

            $('.datepicker').datepicker(datepickerOptions);

            $(document).on("click", "#btnSearchReport", function (e) {
                e.preventDefault();
                $.ajax({
                    data: $('#frmSearch').serialize(),
                    url: 'project_report_result',
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
                        $("#search_result_con").html(data);
                        /*$('#tbl_search_result').DataTable({
                            "order": false,
                            bSort: false,
                            "pageLength": 50
                        });*/
                        $('.loader_con').addClass("hidden");
                        $('html, body').animate({
                            scrollTop: $("#search_result_con").offset().top
                        }, 1000);

                    },
                    error: function (data) {

                    }
                });
            });

            $(document).on("click", "#btnExportLeads", function (e) {
                $('#frmSearch').submit()
                /*$.ajax({
                    data: $('#frmSearch').submit(),
                    url: 'export_report',
                    type: 'post',
                    beforeSend:function(){
                        $.blockUI();
                    },
                    success: function (data) {
                        $.unblockUI();

                        //window.open('', '_blank');
                    }
                });*/
            });


        });
    </script>
@endpush
