@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
@php
$user_id = Session::get('user.ses_user_pk_no');
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{$lead_name}} </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('lead.index') }}">Lead Management</a></li>
        <li class="active">{{$lead_name}} </li>
    </ol>
</section>

<section id="product_details" class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <div class="tab-content">
                        @include("admin.lead_management.stage_wise_lead.stage_wise_lead_list")
                    </div>
                </div>

            </div>
        </div>
    </section>
    @endsection

    @push('js_lib')
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>

    <script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    @endpush


    @push('js_custom')
    <script>
        $(function () {

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue'
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: true,
                todayHighlight: true
            });

            $('#work_list').DataTable(
            {
                "ordering": false
            }

            );

            $(document).on("click", ".distribute-lead", function(e){
                if(confirm("Are You Sure?"))
                {
                    blockUI();

                    var leadlifecycle_id = $(this).attr('data-id');
                    var action = $(this).attr('data-action');
                    var list_action = $(this).attr('data-list-action');
                    var list_type = $(this).attr("data-type");
                    var tab = $(this).attr("data-target");
                    var sales_agent = $("#cmbSalesAgent"+leadlifecycle_id).val();
                    if(sales_agent=="")
                    {
                        alert("You did not select any Sales Agent");
                        $.unblockUI();
                        return;
                    }

                    $.ajax({
                        data: { leadlifecycle_id : leadlifecycle_id, sales_agent: sales_agent },
                        url: action,
                        type: "get",
                        beforeSend:function(){

                        },
                        success: function (data) {
                            $.ajax({
                                data: { tab_type : list_type },
                                url: list_action,
                                type: "post",
                                beforeSend:function(){

                                },
                                success: function (data) {
                                    $(".tab-content").html(data);
                                    $(tab).addClass("active");
                                    $('.table').DataTable();
                                }

                            });
                            toastr.success(data.message, data.title);
                        }
                    });
                    $.unblockUI();
                }
            });

            $(document).on("change", ".auto_distribute", function(e){
                var user_id = "{{ $user_id }}";
                var dist_value = $(this).val();
                var dist_date = $("#dist_date").val();
                $.ajax({
                    data: { user_id : user_id, dist_value:dist_value, dist_date:dist_date },
                    url: 'lead_auto_distribute',
                    type: "get",
                    beforeSend:function(){
                        $('input[type="radio"].flat-red').iCheck({
                            checkboxClass: 'icheckbox_flat-green',
                            radioClass: 'iradio_flat-green'
                        });
                    },
                    success: function (data) {
                        toastr.success(data.message, data.title);
                    }
                });
            });

        });
        $(document).on("click",".next-followup",function (e) {
            var id = $(this).attr("data-id");
            var action = $(this).attr("data-action");
            var title = $(this).attr("data-title");

            $.ajax({
                url: action,
                type: "get",
                beforeSend:function(){
                    blockUI();
                    $('.common-modal').modal('show');
                    $('.common-modal .modal-body').html("Loading...");
                    $('.common-modal .modal-title').html(title);
                },
                success: function (data) {
                    $.unblockUI();
                    $('.common-modal .modal-body').html(data);
                    var date = new Date();
                    date.setDate(date.getDate());
                    $('#txt_followup_date').datepicker({
                        startDate: date,
                        todayHighlight: true
                    });
                    $('#txt_followup_date_time').timepicker();
                }

            });
        });

        $(document).on("click", ".lead-sold", function (e) {
            var id = $(this).attr("data-id");
            var action = $(this).attr("data-action");
            var title = $(this).attr("data-title");

            $.ajax({
                url: action,
                type: "get",
                beforeSend: function () {
                    blockUI();
                    $('.common-modal').modal('show');
                    $('.common-modal .modal-body').html("Loading...");
                    $('.common-modal .modal-title').html(title);
                },
                success: function (data) {
                    $.unblockUI();
                    $('.common-modal .modal-body').html(data);
                    $('#date_of_sold').datepicker();
                }

            });
        });

    </script>
    @endpush
