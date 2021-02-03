@extends('admin.layouts.app')

@push('css_lib')
    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')
    @php
        $user_id = Session::get('user.ses_user_pk_no');
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Block Lead</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('lead.index') }}">Lead Management</a></li>
            <li class="active">Block Lead</li>
        </ol>
    </section>

    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <!-- <li class="active"><a href="#all_lead" data-toggle="tab" data-type="" data-action="load_dist_leads" aria-expanded="true">All Leads</a></li> -->
                        <li class="active"><a href="#manual_lead" data-toggle="tab" data-type="1" data-action="load_block_lead_list" aria-expanded="true">Block</a></li>
                        <li class=""><a href="#pending_lead" data-toggle="tab" data-type="0" data-action="load_block_lead_list" aria-expanded="false">Unblock</a></li>

                        <!-- <li class=""><a href="#auto_lead" data-toggle="tab" data-type="2" data-action="load_dist_leads" aria-expanded="false">Auto</a></li> -->
                    </ul>

                    <div class="tab-content" id="list-body">
                        @include('admin.lead_management.block_lead.all_lead')
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

            $('#work_list').DataTable();

            $(document).on("click", ".distribute-lead", function(e){
                if(confirm("Are You Sure?"))
                {
                    blockUI();

                    var leadlifecycle_id = $(this).attr('data-id');
                    var action = $(this).attr('data-action');
                    var list_action = $(this).attr('data-list-action');
                    var list_type = $(this).attr("data-type");
                    var tab = $(this).attr("data-target");

                    $.ajax({
                        data: { leadlifecycle_id : leadlifecycle_id },
                        url: action,
                        type: "get",
                        beforeSend:function(){

                        },
                        success: function (data) {
                            toastr.success(data.message, data.title);
                           // location.reload();

                        }
                    });
                    $.unblockUI();
                }
            });

        });


        $(document).on("click", ".btn-transfer", function (e) {

            if (confirm("Are You Sure?")) {
                e.preventDefault();
                var lead_pk_no = "";
                //lead_pk_no  = $("#lead_form").serialize();

               /* alert(lead_pk_no);*/
/*
                var cmb_category = $("#cmb_category").val();
                var cmb_area = $("#cmb_area").val();
                var cmb_project_name = $("#cmb_project_name").val();
                var cmb_size = $("#cmb_size").val();
                var cmbTransferTo = $("#cmbTransferTo").val();
                var agent_category = $("#cmbTransferTo").attr("data-agent-category");
             
               */
                var tab_type = '1';
                 var tab = $("ul#tab_container li.active a").attr("href");

                var work_list_rows = $("#work_list tbody tr");
                var responseAction = $(this).attr("data-response-action");
                var checked = 0;

                $(work_list_rows).each(function () {

                    if ($(this).find("input[type=checkbox]").is(':checked')) {
                        lead_pk_no = $(this).find("input[type=checkbox]").attr("data-id");
                        /*alert(lead_pk_no);*/
                        $.ajax({
                            data: {
                                lead_pk_no: lead_pk_no,
                            },
                            url: 'block_list_approved',
                            type: "get",
                            beforeSend: function () {
                                blockUI();
                            },
                            success: function (data) {
                                toastr.success(data.message, data.title);

                                 $.ajax({
                                    url: responseAction,
                                    type: "post",
                                    data: {tab_type: tab_type},
                                    beforeSend: function () {

                                    },
                                    success: function (response_data) {
                                        $('#list-body').html(response_data);
                                        $('.table').DataTable({
                                            "order": [[0, "desc"]]
                                        });
                                        $(tab).addClass("active");
                                    }
                                }).done(function () {
                                    $.unblockUI();
                                });

                            }
                        });
                        checked++;
                    }

                });

            }
        });


    </script>
@endpush
