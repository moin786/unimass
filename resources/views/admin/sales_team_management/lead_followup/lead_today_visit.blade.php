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
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Visit List</h1>

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Sales Team Management</a></li>
		<li class="active">Lead Today Visit</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_details" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="nav-tabs-custom">


				<div class="tab-content " id="list-body">
					@include("admin.sales_team_management.lead_followup.lead_today_visit_list")
				</div>
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

					$('#meeting_followup_date').datepicker({
						startDate: date,
						todayHighlight: true
					});
					$('#meeting_followup_date_time').timepicker();

				}

			});
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
