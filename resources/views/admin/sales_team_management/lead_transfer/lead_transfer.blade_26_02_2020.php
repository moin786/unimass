@extends('admin.layouts.app')

@push('css_lib')
        <!-- DataTables -->
<link rel="stylesheet"
      href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
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
            <ul class="nav nav-tabs">
                <li class="active"><a href="#lead_transfer" data-toggle="tab" data-type="1" data-action="load_transfer_leads" aria-expanded="true">Lead Transfer</a></li>
                <li><a href="#transferred_request" data-toggle="tab" data-type="2" data-action="load_transfer_leads" aria-expanded="false">Transferred Request</a></li>
                <li><a href="#transferred_lead" data-toggle="tab" data-type="3" data-action="load_transfer_leads" aria-expanded="false">Transferred Lead</a></li>
            </ul>
            <div class="tab-content">
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
<script>
    $(function () {
        $('.table').DataTable();

        $(document).on("click", ".btn-transfer", function (e) {
            e.preventDefault();
            var lead_pk_no = "";
            var work_list_rows = $("#lead_transfer tbody tr");
            var cmbTransferTo = $("#cmbTransferTo").val();
            if (cmbTransferTo == "") {
                alert("You did not select any Sales Agent");
                return;
            }
            var checked = 0;
            $(work_list_rows).each(function () {

                if ($(this).find("input[type=checkbox]").is(':checked')) {
                    lead_pk_no = $(this).find("input[type=checkbox]").attr("data-id");
                    var lead_name = $(this).find("input[type=checkbox]").attr("data-name");
                    $.ajax({
                        data: {lead_pk_no: lead_pk_no, cmbTransferTo: cmbTransferTo, lead_name: lead_name},
                        url: 'lead_create_transfer',
                        type: "post",
                        beforeSend: function () {
                            blockUI();
                        },
                        success: function (data) {
                            $.unblockUI();
                            toastr.success(data.message, data.title);
                        }

                    });
                    checked++;
                }

            });

            if (checked == 0) {
                alert("You did not select any Lead");
                return;
            }

        });

        $(document).on("click", ".btn-accept-request", function (e) {
            e.preventDefault();
            var lead_pk_no = "";
            var work_list_rows = $("#lead_transfer_request tbody tr");
            var transfer_id = $(this).attr("#data-trans-id");

            var checked = 0;
            $(work_list_rows).each(function () {

                if ($(this).find("input[type=checkbox]").is(':checked')) {
                    var transfer_id = $(this).find("input[type=checkbox]").attr("data-trans-id");
                    $.ajax({
                        data: {transfer_id: transfer_id},
                        url: "{{ route('accept_transfer') }}",
                        type: "post",
                        beforeSend: function () {
                            blockUI();
                        },
                        success: function (data) {
                            $.unblockUI();
                            toastr.success(data.message, data.title);
                        }

                    });
                    checked++;
                }

            });

            if (checked == 0) {
                alert("You did not select any Lead");
                return;
            }

        });

    });


</script>
@endpush
