@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet"
          href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
          <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <style>
        .table thead tr th {
            font-size: 12px !important;
            padding: 4px;
        }

        .table tbody tr td {
            padding: 2px !important;
            font-size: 12px !important;
        }

        table.dataTable thead .sorting:after {
            opacity: 0.2;
            content: "\e150";
            font-size: 12px;
            text-align: center;
            line-height: 12px;
        }

        table.dataTable thead .sorting_asc:after {
            content: "\e155";
            font-size: 12px;
            text-align: center;
            line-height: 12px;
        }

        table.dataTable thead > tr > th.sorting_asc,
        table.dataTable thead > tr > th.sorting_desc,
        table.dataTable thead > tr > th.sorting,
        table.dataTable thead > tr > td.sorting_asc,
        table.dataTable thead > tr > td.sorting_desc,
        table.dataTable thead > tr > td.sorting {
            padding-right: 24px;
        }


    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Lead Transfer</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Sales Team Management</a></li>
            <li class="active">Lead Transfer</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="tab_container">
                    <li class="active"><a href="#lead_transfer" data-toggle="tab" data-type="1"
                                          data-action="load_transfer_leads" aria-expanded="true">Lead Transfer</a></li>
                    @if($is_ch==1)
                        <li><a href="#transferred_request" data-toggle="tab" data-type="4"
                               data-action="load_transfer_leads" aria-expanded="false">Transferred Request</a></li>
                    @else
                        <li><a href="#transferred_request" data-toggle="tab" data-type="2"
                               data-action="load_transfer_leads" aria-expanded="false">Transferred Request</a></li>
                    @endif

                    <li><a href="#transferred_lead" data-toggle="tab" data-type="3" data-action="load_transfer_leads"
                           aria-expanded="false">Approved</a></li>

                    <li><a href="#rejected_lead" data-toggle="tab" data-type="6" data-action="load_transfer_leads"
                           aria-expanded="false">Rejected</a></li>         
                    @if($is_ch==1)
                        <li><a href="#auto_transfer" data-toggle="tab" data-type="5" data-action="load_transfer_leads"
                               aria-expanded="false">Auto Transfer</a></li>
                    @endif
                </ul>
                <div class="tab-content" id="list-body">
                    @include('admin.sales_team_management.lead_transfer.lead_transfer_list')
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js_custom')
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            $('.table').DataTable(
                {
                    "ordering": false
                });
            $('.select2').select2();
            $(document).on("click", ".btn-transfer", function (e) {
                if (confirm("Are You Sure?")) {
                    e.preventDefault();
                    var lead_pk_no = "";
                    var work_list_rows = $("#lead_transfer tbody tr");
                    var cmb_category = $("#cmb_category").val();
                    var cmb_area = $("#cmb_area").val();
                    var cmb_project_name = $("#cmb_project_name").val();
                    var cmb_size = $("#cmb_size").val();
                    var cmbTransferTo = $("#cmbTransferTo").val();
                    var agent_category = $("#cmbTransferTo").attr("data-agent-category");
                    if (cmbTransferTo == 0) {
                        alert("You did not select any Sales Agent");
                        return;
                    }

                    var responseAction = $(this).attr("data-response-action");
                    var tab_type = $("ul#tab_container li.active a").attr("data-type");
                    var tab = $("ul#tab_container li.active a").attr("href");
                    var checked = 0;
                    $(work_list_rows).each(function () {

                        if ($(this).find("input[type=checkbox]").is(':checked')) {
                            lead_pk_no = $(this).find("input[type=checkbox]").attr("data-id");
                            var lead_name = $(this).find("input[type=checkbox]").attr("data-name");
                            var category = $(this).find("input[type=checkbox]").attr("data-category");
                            var agent = $(this).find("input[type=checkbox]").attr("data-agent");
                            $.ajax({
                                data: {
                                    lead_pk_no: lead_pk_no,
                                    cmbTransferTo: cmbTransferTo,
                                    lead_name: lead_name,
                                    category: category,
                                    cmb_category: cmb_category,
                                    cmb_area: cmb_area,
                                    cmb_project_name: cmb_project_name,
                                    cmb_size: cmb_size,
                                    agent: agent
                                },
                                url: 'lead_create_transfer',
                                type: "post",
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

                    if (checked == 0) {
                        alert("Please select the leads you would like to transfer.");
                       
                    }
                }
            });

            $(document).on("change", "#cmb_category", function (e) {
                blockUI();
                var cat_id = $(this).val();
                var action = $(this).attr('data-action');
                $.ajax({
                    data: {cat_id: cat_id},
                    url: action,
                    type: "post",
                    beforeSend: function () {
                        $("#cmb_area").html("");
                        $("#cmb_project_name").html("");
                        //$("#cmb_size").html("");
                       // $("#cmbTransferTo").html("");
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        var area_list = size_list = project_list = agent_list = "<option value='0'>Select</option>";
                        $.each(data.area_arr, function (i, item) {
                            area_list += "<option value='" + i + "'>" + item + "</option>";
                        });
                        $("#cmb_area").append(area_list);

                      

                        $.each(data.project_arr, function (i, item) {
                            project_list += "<option value='" + i + "'>" + item + "</option>";
                        });
                        $("#cmb_project_name").append(project_list);

                        $.each(data.sales_agent, function (i, item) {
                            agent_list += "<option value='" + i + "'>" + item + "</option>";
                        });
                        //$("#cmbTransferTo").append(agent_list);
                    }

                });
                $.unblockUI();
            });

            $(document).on("change", "#cmb_area", function (e) {
                blockUI();
                var area_id = $(this).val();
                var cat_id = $("#cmb_category").val();
                var project_list = "<option value='0'>Select</option>";
                var agent_list = "<option value='0'>Select</option>";
                $.ajax({
                    data: {cat_id: cat_id, area_id: area_id},
                    url: "{{ route('load_area_project_size') }}",
                    type: "post",
                    beforeSend: function () {
                        $("#cmb_project_name").html("");
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        $.each(data.project_arr, function (i, item) {
                            project_list += "<option value='" + i + "'>" + item + "</option>";
                        });
                        $("#cmb_project_name").append(project_list);
                    }
                });
                $.unblockUI();
            });

            $(document).on("change", ".btn-accept-request", function (e) {
                if (confirm("Are you sure?")) {
                    e.preventDefault();
                    var lead_pk_no = "";
                    var work_list_rows = $("#lead_transfer_request tbody tr");
                    var transfer_id = $(this).attr("#data-trans-id");

                    var responseAction = $(this).attr("data-response-action");
                    var tab_type = $("ul#tab_container li.active a").attr("data-type");
                    var tab = $("ul#tab_container li.active a").attr("href");
                    var accept_reject_ind = $(this).val();



                    var checked = 0;
                    $(work_list_rows).each(function () {

                        if ($(this).find("input[type=checkbox]").is(':checked')) {
                            var transfer_id = $(this).find("input[type=checkbox]").attr("data-trans-id");
                            var lead_id = $(this).find("input[type=checkbox]").attr("data-lead-id");
                            var to_agent = $(this).find("input[type=checkbox]").attr("data-to-agent");
                            $.ajax({
                                data: {transfer_id: transfer_id, lead_id: lead_id, to_agent: to_agent,accept_reject_ind:accept_reject_ind},
                                url: "{{ route('accept_transfer') }}",
                                type: "post",
                                beforeSend: function () {
                                    blockUI();
                                },
                                success: function (data) {
                                    $.unblockUI();
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

                    if (checked == 0) {
                        alert("You did not select any Lead");
                        return;
                    }
                }
            });
            function accept_or_reject_request(element){
                alert($(element).val());

            }



        });


        $(document).on("click", ".btn-transfer-accept-request", function (e) {

            if (confirm("Are You Sure?")) {
                e.preventDefault();
                var lead_pk_no = "";
                var work_list_rows = $("#auto_transfer tbody tr");
                var cmb_category = $("#cmb_category").val();
                var cmb_area = $("#cmb_area").val();
                var cmb_project_name = $("#cmb_project_name").val();
                var cmb_size = $("#cmb_size").val();
                var cmbTransferTo = $("#cmbTransferTo").val();
                var agent_category = $("#cmbTransferTo").attr("data-agent-category");
                if (cmbTransferTo == 0) {
                    alert("You did not select any Sales Agent");
                    return;
                }

                var responseAction = $(this).attr("data-response-action");
                var tab_type = $("ul#tab_container li.active a").attr("data-type");
                var tab = $("ul#tab_container li.active a").attr("href");
                var checked = 0;
                $(work_list_rows).each(function () {

                    if ($(this).find("input[type=checkbox]").is(':checked')) {
                        lead_pk_no = $(this).find("input[type=checkbox]").attr("data-id");
                        var lead_name = $(this).find("input[type=checkbox]").attr("data-name");
                        var category = $(this).find("input[type=checkbox]").attr("data-category");
                        var agent = $(this).find("input[type=checkbox]").attr("data-agent");
                        $.ajax({
                            data: {
                                lead_pk_no: lead_pk_no,
                                cmbTransferTo: cmbTransferTo,
                                lead_name: lead_name,
                                category: category,
                                cmb_category: cmb_category,
                                cmb_area: cmb_area,
                                cmb_project_name: cmb_project_name,
                                cmb_size: cmb_size,
                                agent: agent
                            },
                            url: 'ch_accept_transfer',
                            type: "post",
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
