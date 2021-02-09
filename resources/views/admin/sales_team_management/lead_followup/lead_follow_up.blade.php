@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<section class="content-header">
	<h1>Lead followup</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Sales Team Managemen</a></li>
		<li class="active">Lead followup</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_details" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="nav-tabs-custom">

				@php
				$classActive =  	$classActive1 =$classActive2= "";
				$area =$area1 =$area2 = "false";
				if($id == "" || $id == "1"){
					$classActive = "active";
					$area = "true";
				}

				elseif($id=="2"){
					$classActive1 = "active";
					$area1 = "true";
				}

				elseif($id=="3"){
					$classActive2 = "active";
					$area2 = "true";
				}
				@endphp
				{{-- @if($userType == 1)

				<div class="alert alert-danger alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4 class="pull-left" style="margin-right: 20px;"><i class="icon fa fa-ban"></i> Forbidden!</h4>
					You are not Authorized to view this page
				</div>

				@else --}}
				<ul class="nav nav-tabs" id="tab_container">
					<li class="{{ $classActive }}"><a href="#today_followup" data-toggle="tab" data-type="1" data-action="{{ route('load_followup_leads') }}" aria-expanded="{{ $area }}">Today's Activity</a></li>
					<li class="{{ $classActive1 }}"><a href="#missed_followup" data-toggle="tab" data-type="2" data-action="{{ route('load_followup_leads') }}" aria-expanded="{{ $area1 }}">Missed Follow Up</a></li>
					<li class="{{ $classActive2 }}"><a href="#next_followup" data-toggle="tab" data-type="3" data-action="{{ route('load_followup_leads') }}" aria-expanded="{{ $area2 }}">Next Follow Up</a></li>
				</ul>
				<div class="tab-content" id="list-body">
					@if($id =="")
					@include('admin.sales_team_management.lead_followup.lead_today_follow_up')
					@elseif($id == "1")
					@include('admin.sales_team_management.lead_followup.lead_today_follow_up')
					@elseif($id == "2")
					@include('admin.sales_team_management.lead_followup.lead_missed_follow_up')
					@elseif($id == "3")
					@include('admin.sales_team_management.lead_followup.lead_next_follow_up')
					@endif
				</div>

				{{-- @endif --}}

			</div>
		</div>
	</div>
</section>
<!-- /.content -->

@endsection

@push('js_custom')

<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

<script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
	$(function () {

		$('.table').DataTable({
			"ordering": false,
		});

		$(document).on("click",".lead-sold",function (e) {
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
					$('#date_of_sold').datepicker();
				}

			});
		});

		$(document).bind("keyup",".calculate-total-sold", function (e) {
			var total_cost = 0;
			$(".calculate-total-sold").each(function(){
				total_cost += parseFloat(this.value*1);
			});
			$("#grand-total").val(total_cost);
		});

	});

</script>
@endpush
