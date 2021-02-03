@extends('admin.layouts.app')

@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ URL::asset('backend/dist/css/custom.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Lead QC</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Lead Management</a></li>
        <li class="active">Lead Qc</li>
    </ol>
</section>
@php
$user_id = Session::get('user.ses_user_pk_no');
@endphp
<!-- Main content -->
<section id="product_details" class="content">
    <div class="row">
        <div class="col-xs-12">
            @if($is_team_leader==1)
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#qc_work_list" data-toggle="tab" data-action="load_qc_leads" data-type="1" aria-expanded="true">QC Work List</a></li>
                    <li class=""><a href="#qc_passed" data-toggle="tab" data-type="2" data-action="load_qc_leads" aria-expanded="false">QC Passed</a></li>
                    <li class=""><a href="#junk_leads" data-toggle="tab" data-type="3" data-action="load_qc_leads" aria-expanded="false">Junk Leads</a></li>
                    <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                </ul>

                <div class="tab-content">
                    @include('admin.lead_management.qc.qc_work_list')
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
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
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

        $('.table').DataTable({
            "order": [[ 0, "desc" ]]
        });

        $(document).on("click", ".lead-qc-status", function(e){
            if(confirm("Are you sure?"))
            {
                blockUI();
                e.preventDefault();
                var lead_pk_no = "";
                var work_list_rows = $("#work_list tbody tr");
                var qc_status = $(this).attr("id");
                var list_type = $(this).attr("data-type");
                var tab = $(this).attr("data-target");

                var checked = 0;
                $( work_list_rows ).each(function() {
                    if($(this).find("input[type=checkbox]").is(':checked'))
                    {
                        lead_pk_no = $(this).find("input[type=checkbox]").attr("data-id");
                        var lead_name = $(this).find("input[type=checkbox]").attr("data-name");

                        $.ajax({
                            data: { lead_pk_no : lead_pk_no, qc_status: qc_status, lead_name:lead_name },
                            url: 'lead_pass_junk',
                            type: "get",
                            beforeSend:function(){

                            },
                            success: function (data) {
                                $.ajax({
                                    data: { tab_type : list_type },
                                    url: 'load_qc_leads',
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
                        checked++;
                    }

                });

                if(checked==0)
                {
                    alert("You did not select any Lead");
                    $.unblockUI();
                    return;
                }
                $.unblockUI();
            }
        });

        $(document).on("change", ".can_bypass", function(e){
            var user_id = "{{ $user_id }}";
            var bypass_value = $(this).val();
            var bypass_date = $("#bypass_date").val();
            $.ajax({
                data: { user_id : user_id, bypass_value:bypass_value, bypass_date:bypass_date },
                url: 'lead_bypass',
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

</script>
@endpush