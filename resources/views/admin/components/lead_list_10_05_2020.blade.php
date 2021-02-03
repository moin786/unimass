@extends('admin.layouts.app')
@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
@php
$ses_other_user_id    = Session::get('user.ses_other_user_pk_no');
$ses_other_user_name  = Session::get('user.ses_other_full_name');

$is_ses_hod     = Session::get('user.is_ses_hod');
$is_ses_hot     = Session::get('user.is_ses_hot');
$is_team_leader = Session::get('user.is_team_leader');
$status="";

@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
	@if($ses_other_user_id =="")
	<h1>Lead List</h1>
	@else
	<h1>
		Lead List :: <span class="text-danger">{{ $ses_other_user_name }}</span>
		| <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard',$ses_other_user_id) }}">Back</a>
		| <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard') }}">Back To My Dashboard</a>
	</h1>
	@endif

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Dashboard</a></li>
		<li class="active">Lead List</li>
	</ol>
</section>

<section id="product_details" class="content">
	<div class="row">
		<div class="col-sm-10">
			<div class="box box-info">
				<div class="box-body">
					<table id="datatable" class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th style=" min-width: 10px" class="text-center">Lead Id</th>
								<th style=" min-width: 10px" class="text-center">Lead Code</th>
								<th style=" min-width: 70px">Customer Name</th>
								<th style=" min-width: 80px">Mobile</th>
								<th style=" min-width: 100px">Project</th>
								<th style=" min-width: 50px">Agent</th>
								<th style=" min-width: 50px">Stage</th>
								<th style=" min-width: 25px" class="text-center">Action</th>
							</tr>
						</thead>

						<tbody>
							@if(!empty($lead_data))
							@foreach($lead_data as $row)
							<tr>
								<td class="text-center">{{ $row->lead_pk_no }}</td>
								<td class="text-center">{{ $row->lead_id }}</td>
								<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
								<td>{{ $row->phone1 }}</td>
								<td>{{ $row->project_name }}</td>
								<td>{{ $row->lead_sales_agent_name }}</td>
								<td>{{ $lead_stage_arr[$row->lead_current_stage] }}</td>
								<td class="text-center">
									<span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
								</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="9" class="text-center text-danger">No Data Found</td>
							</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@if($ses_other_user_id == "" && ($is_ses_hod!=0 || $is_ses_hot!=0 || $is_team_leader!=0))
		<div class="col-sm-2">
			<div class="form-group">
				<label for="teamname">Team</label>
				<select name="teamname" id="teamname" data-action="{{ route('get_team_users') }}" class="form-control" style="width: 100%;" required="required" aria-hidden="true">
					<option value="0">Select</option>
					@foreach ($team_arr as $key => $team)
					<option value="{{ $key }}">{{ $team }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group">
				<label class="pull-left" style="cursor: pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
						<input type="radio" id="user_type" value="hod" name="user_type" class="flat-red"  style="position: absolute; opacity: 0;">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
					</div>
					<span style="font-size:13px; margin-top:-5px;">HOD&nbsp;</span>
				</label>
				<select name="team_name" id="team_hod" class="form-control" style="width: 100%;" required="required" aria-hidden="true" {{ $status }} >
					<option value="0">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label class="pull-left" style="cursor: pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
						<input type="radio" id="user_type" value="hot" name="user_type" class="flat-red"  style="position: absolute; opacity: 0;">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
					</div>
					<span style="font-size:13px; margin-top:-5px;">CSH&nbsp;</span>
				</label>
				<select name="team_hot" id="team_hot" class="form-control" style="width: 100%;" required="required" aria-hidden="true" {{ $status }}>
					<option value="0">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label class="pull-left" style="cursor: pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
						<input type="radio" id="user_type" value="tl" name="user_type" class="flat-red"  style="position: absolute; opacity: 0;">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
					</div>
					<span style="font-size:13px; margin-top:-5px;">HOT&nbsp;</span>
				</label>
				<select name="team_tl" id="team_tl" class="form-control" style="width: 100%;" required="required" aria-hidden="true" {{ $status }}>
					<option value="0">Select</option>
				</select>
			</div>
			<div class="form-group">
				<label class="pull-left" style="cursor: pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
						<input type="radio" id="user_type" value="agent" name="user_type" class="flat-red"  style="position: absolute; opacity: 0;">
						<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
					</div>
					<span style="font-size:13px; margin-top:-5px;">Sales Person&nbsp;</span>
				</label>
				<select name="team_agent" id="team_agent" class="form-control" style="width: 100%;" required="required" aria-hidden="true" {{ $status }} >
					<option value="0">Select</option>
				</select>
			</div>
			<div>
				<span id="switch_dashboard" data-action="{{ route('admin.dashboard') }}" class="btn bg-green btn-xs">View Dashboard</span>
			</div>
		</div>
		@endif
	</div>
</section>
@endsection

@push('js_custom')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
	$(function () {
		//Flat red color scheme for iCheck
		$('input[type="radio"].flat-red').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});
		$('#datatable').DataTable({
			"order": [[ 0, "desc" ]]
		});
		$(document).on("change", "#teamname", function(e){
			blockUI();
			var team_id = $(this).val();
			var action = $(this).attr('data-action');
			$.ajax({
				data: { team_id:team_id },
				url: action,
				type: "post",
				beforeSend:function(){
					$("#team_hod").html("");
					$("#team_hot").html("");
					$("#team_tl").html("");
					$("#team_agent").html("");
				},
				success: function (data) {
					data = $.parseJSON(data);
					var hod_list = hot_list = tl_list = agent_list = "";
					$.each(data.hod_arr, function(i, item) {
						hod_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_hod").append(hod_list);

					$.each(data.hot_arr, function(i, item) {
						hot_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_hot").append(hot_list);

					$.each(data.tl_arr, function(i, item) {
						tl_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_tl").append(tl_list);

					$.each(data.agent_arr, function(i, item) {
						agent_list += "<option value='"+i+"'>"+item+"</option>";
					});
					$("#team_agent").append(agent_list);
				}

			});
			$.unblockUI();
		});

		$(document).on("click","#switch_dashboard",function(){
			var action = $(this).attr("data-action");
			var selected_user = $('input[name="user_type"]:checked');
			if (!selected_user.val()) {
				alert('You did not select any user.');
			}
			else {
				var selected_user_id = selected_user.parents("label").siblings("select").val();
				window.location.href = action + "/" + selected_user_id;
			}


		});

	});

</script>
@endpush