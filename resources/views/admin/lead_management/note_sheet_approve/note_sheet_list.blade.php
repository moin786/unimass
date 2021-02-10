@extends('admin.layouts.app')

@push('css_lib')
    <link rel="stylesheet"
        href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

@endpush

@section('content')
    @php
    $user_id = Session::get('user.ses_user_pk_no');
    $userRoleId = Session::get('user.ses_role_lookup_pk_no');
    $is_hod = Session::get('user.is_ses_hod');
    $is_super_admin = Session::get('user.is_super_admin');
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Note Sheet Approval</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('lead.index') }}">Lead Management</a></li>
            <li class="active">Note Sheet Approval</li>
        </ol>
    </section>

    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="nav-tabs-custom">
                    @if ($userRoleId == 551 || $is_super_admin == 1 || $is_hod == 1)
                        <ul class="nav nav-tabs">
                            <!-- <li class="active"><a href="#all_lead" data-toggle="tab" data-type="" data-action="load_note_sheet_list" aria-expanded="true">All Leads</a></li> -->
                            <li class="active"><a href="#manual_lead" data-toggle="tab" data-type="1"
                                    data-action="load_note_sheet_list" aria-expanded="true">Pending</a></li>
                            <li class=""><a href="#pending_lead" data-toggle="tab" data-type="0"
                                    data-action="load_note_sheet_list" aria-expanded="false">Completed</a></li>

                            <!-- <li class=""><a href="#auto_lead" data-toggle="tab" data-type="2" data-action="load_dist_leads" aria-expanded="false">Auto</a></li> -->
                        </ul>

                        <div class="tab-content">
                            @include('admin.lead_management.note_sheet_approve.all_lead')
                        </div>
                </div>
            @else
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4 class="pull-left" style="margin-right: 20px;"><i class="icon fa fa-ban"></i> Forbidden!</h4>
                    You are not Authorized to view this page
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('js_lib')
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

@endpush

@push('js_custom')
    <script>
        $(function() {

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

            $('.table').DataTable({
                    "ordering": false
                }

            );



        });
        $(document).on("change", ".btn-accept-request", function(e) {
            if (confirm("Are you sure?")) {
                e.preventDefault();
                var lead_pk_no = "";
                var work_list_rows = $("#work_list tbody tr");
                var transfer_id = $(this).attr("#data-trans-id");

                var responseAction = $(this).attr("data-response-action");
                var tab_type = $("ul#tab_container li.active a").attr("data-type");
                var tab = $("ul#tab_container li.active a").attr("href");
                var accept_reject_ind = $(this).val();
                var checked = 0;
                $(work_list_rows).each(function() {

                    if ($(this).find("input[type=checkbox]").is(':checked')) {
                        var lead_life_cycle_id = $(this).find("input[type=checkbox]").attr("data-lead-id");
                        var to_agent = $(this).find("input[type=checkbox]").attr("data-to-agent");
                        $.ajax({
                            data: {
                                lead_life_cycle_id: lead_life_cycle_id,
                                accept_reject_ind: accept_reject_ind,
                                tab_type: 1,
                            },
                            url: "{{ route('note_sheet_approve') }}",
                            type: "post",
                            beforeSend: function() {
                                blockUI();
                            },
                            success: function(data) {
                                $.unblockUI();
                                toastr.success(data.message, data.title);
                                $.ajax({
                                    url: responseAction,
                                    type: "post",
                                    data: {
                                        tab_type: 1
                                    },
                                    beforeSend: function() {

                                    },
                                    success: function(response_data) {

                                        $('#all_lead').html(response_data);
                                        $('.table').DataTable({
                                            "ordering": false
                                        });
                                        $(tab).addClass("active");
                                    }
                                }).done(function() {
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

    </script>
@endpush
