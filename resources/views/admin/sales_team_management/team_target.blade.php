@extends('admin.layouts.app')

@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet"
href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>Team Target</h1>

    <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Sales Team Management</a></li>
        <li class="active">Team Target</li>
    </ol>
</section>

<!-- Main content -->
<section id="product_details" class="content">
    <div class="row">
        <div class="col-xs-12">
            @if(!empty($team_arr))
            <div class="box box-success">
                <div class="box-body">
                    <form id="frmTeamTarget" action="{{ route('store_team_target') }}" method="post">
                        <div class="head_action"
                        style="background-color: #ECF0F5;border: 1px solid #ccc; text-align: center; padding: 3px;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-md-4"><label>Team Name</label></div>
                                <div class="col-md-8">
                                    <select class="form-control select2" id="team_name" name="team_name"
                                    style="width: 100%;"
                                    aria-hidden="true">
                                    <option selected="selected" value="0">Select Team</option>

                                    @foreach ($team_arr as $team_id => $team)
                                    <option value="{{ $team_id }}">{{ $team }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <div class="col-md-4"><label>Team Lead</label></div>
                            <div class="col-md-8" id="team_name_td">
                                <select class="form-control select2" name="team_lead" style="width: 100%;"
                                aria-hidden="true">
                                <option selected="selected" value="0">Select Team Lead</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="col-md-4"><label for="team_target_date">Target Date</label></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control datepicker keep_me" id="team_target_date"
                            name="team_target_date" value="<?php echo date('d-m-Y'); ?>" title=""
                            readonly="readonly" placeholder=""/>
                        </div>
                    </div>
                </div>
                <br clear="all"/>
            </div>
            <div class="row" id="team_list"></div>
            @else
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4 class="pull-left" style="margin-right: 20px;"><i class="icon fa fa-ban"></i> Forbidden!</h4>
                You are not Authorized to view this page
            </div>
            @endif
        </form>
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
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    $(function () {
        $("#team_target_date").datepicker( {
            format: "dd-mm-yyyy",
        });

        $(document).on("change", "#team_name", function () {
            blockUI();
            var team_id = $(this).val();
            var team_target_date = $("#team_target_date").val();
            $.ajax({
                data: {team_id: team_id},
                url: 'load_team_lead_by_team',
                type: "post",
                beforeSend: function () {

                },
                success: function (data) {
                    $("#team_name_td").html(data);
                }

            });

            $.ajax({
                data: {team_id: team_id,team_target_date:team_target_date},
                url: 'load_team_list_by_team',
                type: "post",
                beforeSend: function () {

                },
                success: function (data) {
                    $("#team_list").html(data);
                    $('.table').DataTable({
                        "bPaginate": false
                    });
                }

            });

            $.unblockUI();
        });
    });
</script>

@endpush
