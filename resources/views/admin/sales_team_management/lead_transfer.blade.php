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
                <li class="active"><a href="#lead_transfer" data-toggle="tab" aria-expanded="true">Lead Transfer</a>
                </li>
                <li><a href="#transferred_request" data-toggle="tab" aria-expanded="false">Transferred Request</a></li>
                <li><a href="#transferred_lead" data-toggle="tab" aria-expanded="false">Transferred Lead</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active table-responsive" id="lead_transfer">
                    <div class="head_action"
                         style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">
                        <strong style="display: inline-block;">Transfer To :</strong> &nbsp &nbsp &nbsp &nbsp
                        <div class="form-group" style="display: inline-block;">
                            <select id="cmbTransferTo" class="form-control select2 select2-hidden-accessible"
                                    style="width: 100%;" aria-hidden="true">
                                <option selected="selected" value="">Please Select Agent</option>
                                @foreach($sales_agent as $key=>$agent)
                                    <option Value="{{ $agent->user_pk_no }}">{{ $agent->user_fullname }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    {{-- Lead Transfer Table --}}
                    <div class="box-body table-responsive">
                        <table id="lead_transfer" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style=" min-width: 30px" class="text-center">ID</th>
                                <th style=" min-width: 50px" class="text-center">Customer</th>
                                <th style=" min-width: 80px" class="text-center">Mobile</th>
                                <th style=" min-width: 50px" class="text-center">Project</th>
                                <th style=" min-width: 50px" class="text-center">Agent</th>
                                <th style=" min-width: 51px" class="text-center">Stage</th>
                                <th style=" min-width: 50px" class="text-center">Sales Executive</th>
                                <th style=" min-width: 50px" class="text-center">Last Followup</th>
                                <th style=" min-width: 100px" class="text-center">Note</th>
                                <th style=" min-width: 50px" class="text-center">Next Followup</th>
                                <th style=" min-width: 20px" class="text-center">Select</th>
                                <th style=" min-width: 20px" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(!empty($lead_data))
                                @foreach($lead_transfer_list as $row)
                                    <tr>
                                        <td class="text-center">{{ $row->lead_id }}</td>
                                        <td class="text-center">{{ $row->customer_firstname . " " .$row->customer_lastname }}</td>
                                        <td class="text-center">{{ $row->phone1 }}</td>
                                        <td class="text-center">{{ $row->project_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_current_stage_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_followup_datetime }}</td>
                                        <td class="text-center">{{ $row->followup_Note }}</td>
                                        <td class="text-center">{{ $row->Next_FollowUp_date }}</td>
                                        <td class="text-center"><input type="checkbox" data-id="{{ $row->lead_pk_no }}"
                                                                       data-name="{{ $row->lead_id }}"></td>
                                        <td class="text-center">
                                            <span class="btn bg-info btn-xs lead-view" title="Lead Sold"
                                                  data-id="{{ $row->lead_pk_no }}"
                                                  data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
                                                        class="fa fa-eye"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="10"></td>
                                <td class="text-center">
                                    <a href="#" class="btn bg-blue btn-block btn-xs btn-transfer">Transfer</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane table-responsive" id="transferred_request">
                    {{-- Transferred Request Table --}}
                    <div class="box-body table-responsive">
                        <table id="datatable2" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style=" min-width: 30px" class="text-center">ID</th>
                                <th style=" min-width: 50px" class="text-center">Customer</th>
                                <th style=" min-width: 80px" class="text-center">Mobile</th>
                                <th style=" min-width: 50px" class="text-center">Project</th>
                                <th style=" min-width: 50px" class="text-center">Agent</th>
                                <th style=" min-width: 51px" class="text-center">Stage</th>
                                <th style=" min-width: 50px" class="text-center">Sales Executive</th>
                                <th style=" min-width: 50px" class="text-center">Last Followup</th>
                                <th style=" min-width: 100px" class="text-center">Note</th>
                                <th style=" min-width: 50px" class="text-center">Next Followup</th>
                                <th style=" min-width: 20px" class="text-center">Accept</th>
                                <th style=" min-width: 20px" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(!empty($lead_data))
                                @foreach($lead_transfer_list as $row)
                                    <tr>
                                        <td class="text-center">{{ $row->lead_id }}</td>
                                        <td class="text-center">{{ $row->customer_firstname . " " .$row->customer_lastname }}</td>
                                        <td class="text-center">{{ $row->phone1 }}</td>
                                        <td class="text-center">{{ $row->project_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_current_stage_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_followup_datetime }}</td>
                                        <td class="text-center">{{ $row->followup_Note }}</td>
                                        <td class="text-center">{{ $row->Next_FollowUp_date }}</td>
                                        <td class="text-center"><input type="checkbox" data-id="{{ $row->lead_pk_no }}"
                                                                       data-name="{{ $row->lead_id }}"></td>
                                        <td class="text-center">
                                            <span class="btn bg-info btn-xs lead-view" title="Lead Sold"
                                                  data-id="{{ $row->lead_pk_no }}"
                                                  data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
                                                        class="fa fa-eye"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="10"></td>
                                <td class="text-center">
                                    <a href="#" class="btn bg-blue btn-block btn-xs">Accept</a>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.tab-pane -->

                <div class="tab-pane table-responsive" id="transferred_lead">
                    {{-- Transferred Lead Table --}}
                    <div class="box-body table-responsive">
                        <table id="datatable3" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style=" min-width: 30px" class="text-center">ID</th>
                                <th style=" min-width: 50px" class="text-center">Customer</th>
                                <th style=" min-width: 80px" class="text-center">Mobile</th>
                                <th style=" min-width: 50px" class="text-center">Project</th>
                                <th style=" min-width: 50px" class="text-center">Agent</th>
                                <th style=" min-width: 51px" class="text-center">Stage</th>
                                <th style=" min-width: 50px" class="text-center">Sales Executive</th>
                                <th style=" min-width: 50px" class="text-center">Last Followup</th>
                                <th style=" min-width: 100px" class="text-center">Note</th>
                                <th style=" min-width: 50px" class="text-center">Next Followup</th>
                                <th style=" min-width: 20px" class="text-center">Status</th>
                                <th style=" min-width: 20px" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(!empty($lead_data))
                                @foreach($lead_transfer_list as $row)
                                    <tr>
                                        <td class="text-center">{{ $row->lead_id }}</td>
                                        <td class="text-center">{{ $row->customer_firstname . " " .$row->customer_lastname }}</td>
                                        <td class="text-center">{{ $row->phone1 }}</td>
                                        <td class="text-center">{{ $row->project_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_current_stage_name }}</td>
                                        <td class="text-center">{{ $row->lead_sales_agent_name }}</td>
                                        <td class="text-center">{{ $row->lead_followup_datetime }}</td>
                                        <td class="text-center">{{ $row->followup_Note }}</td>
                                        <td class="text-center">{{ $row->Next_FollowUp_date }}</td>
                                        <td class="text-center"><input type="checkbox" data-id="{{ $row->lead_pk_no }}"
                                                                       data-name="{{ $row->lead_id }}"></td>
                                        <td class="text-center">
                                            <span class="btn bg-info btn-xs lead-view" title="Lead Sold"
                                                  data-id="{{ $row->lead_pk_no }}"
                                                  data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
                                                        class="fa fa-eye"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
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

    });


</script>
@endpush
