@extends('admin.layouts.app')

@push('css_lib')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Search Engine</h1>

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Report Module</a></li>
		<li class="active">Search Engine</li>
	</ol>
</section>

<!-- Main content -->
<section id="search_details" class="content">
	<div class="row">
		<div class="col-xs-12">
			<form action="{{ route('export_report') }}" id="frmSearch" method="post" >
				{{ csrf_field() }}
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">Search Engine</h3>
					</div>

					{{-- Search Engin --}}
					<div class="box-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="txt_customer_name">Customer Name :</label>
									<input type="text" class="form-control" id="txt_customer_name" name="txt_customer_name" value="" title="" placeholder="Enter Customer Name"/>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="">Mobile :</label>
									<input type="text" class="form-control" id="txt_mobile_no" name="txt_mobile_no" value="" title="" placeholder="Enter Mobile No"/>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="txt_email">Email :</label>
									<input type="email" class="form-control" id="txt_email" name="txt_email" value="" title="" placeholder="Enter @mail"/>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>Occupation :</label>
									<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="cmbOccupation">
										<option value="">Select Occupation</option>
										@if(!empty($ocupations))
										@foreach ($ocupations as $key => $ocupation)
										<option value="{{ $key }}">{{ $ocupation }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="cmbOrganization">Organization :</label>
									<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="cmbOrganization">
										<option value="">Select Organization</option>
										@if(!empty($ocupations))
										@foreach ($ocupations as $key => $ocupation)
										<option value="{{ $key }}">{{ $ocupation }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>


							<div class="col-md-3">
								<div class="form-group">
									<label for="">Category :</label>
									<select class="form-control select2" name="cmbCategory" style="width: 100%;" aria-hidden="true">
										<option value="">Select Category</option>
										@if(!empty($project_cat))
										@foreach ($project_cat as $key => $cat)
										<option value="{{ $key }}">{{ $cat }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="">Area :</label>
									<select class="form-control select2" name="cmbArea" style="width: 100%;" aria-hidden="true">
										<option value="">Select Area</option>
										@if(!empty($project_area))
										@foreach ($project_area as $key => $area)
										<option value="{{ $key }}">{{ $area }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="">Project :</label>
									<select class="form-control select2" name="cmbProjectName" style="width: 100%;" aria-hidden="true">
										<option value="">Select Project Name</option>
										@if(!empty($project_name))
										@foreach ($project_name as $key => $pname)
										<option value="{{ $key }}">{{ $pname }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="">Flat Size :</label>
									<select class="form-control select2" name="cmbSize" style="width: 100%;" aria-hidden="true">
										<option value="">Select Size</option>
										@if(!empty($project_size))
										@foreach ($project_size as $key => $size)
										<option value="{{ $key }}">{{ $size }}</option>
										@endforeach
										@endif
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label>Source Type :</label>
									<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
										<option value="">Select Source Type</option>
									</select>
								</div>
							</div>

							<div class="col-md-3">
								<div class="form-group">
									<label for="txt_source_name">Source Name :</label>
									<input type="text" class="form-control" id="txt_source_name" name="txt_source_name" value="" title="" placeholder="Enter Source Name"/>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="cust_date_birth">Entry Date Range :</label>
									<div class="input-daterange input-group" id="datepicker">
										<input type="text" class="input-sm form-control datepicker" name="txt_entry_date" readonly="readonly" style="padding: 17px 0px;">
										<span class="input-group-addon">To</span>
										<input type="text" class="input-sm form-control datepicker" name="txt_entry_date_to" readonly="readonly" style="padding: 17px 0px;">
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label>Stage :</label>
									<select class="form-control select2 select2-hidden-accessible" style="width: 100%;"
									aria-hidden="true" name="cmb_stage">
									<option value="0">Please Select Stage</option>
									@php
									$stages = [1,3,4,5,6,7];
									@endphp
									@if(!empty($lead_stage_arr))
									@foreach ($lead_stage_arr as $key => $stage)
									@if( in_array($key, $stages) )
									<option value="{{ $key }}">{{ $stage }}</option>
									@endif
									@endforeach
									@endif
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Search on KYC items</h3>
				</div>

				<div class="box-body">
					<div class="row">
						<div class="col-md-3">
							<label for="cust_date_birth">Birth Date of Customer :</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_dob_from" readonly="readonly" style="padding: 17px 0px;">
								<span class="input-group-addon">To</span>
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_dob_to" readonly="readonly" style="padding: 17px 0px;">
							</div>
						</div>

						<div class="col-md-3">
							<label for="customer_marriage_anniversary">Marriage Anniversary :</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="input-sm form-control datepicker" name="txt_mar_date_from" readonly="readonly" style="padding: 17px 0px;">
								<span class="input-group-addon">To</span>
								<input type="text" class="input-sm form-control datepicker" name="txt_mar_date_to" readonly="readonly" style="padding: 17px 0px;">
							</div>
						</div>

						<div class="col-md-3">
							<label for="birth_date_cust_wife">Birth Date of Customer's Wife :</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_wife_dob_from" readonly="readonly" style="padding: 17px 0px;">
								<span class="input-group-addon">To</span>
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_wife_dob_to" readonly="readonly" style="padding: 17px 0px;">
							</div>
						</div>

						<div class="col-md-3">
							<label for="birth_date_of_customer_child">Birth Date of Customer's Children :</label>
							<div class="input-daterange input-group" id="datepicker">
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_child_dob_from" readonly="readonly" style="padding: 17px 0px;">
								<span class="input-group-addon">To</span>
								<input type="text" class="input-sm form-control datepicker" name="txt_cus_child_dob_to" readonly="readonly" style="padding: 17px 0px;">
							</div>
						</div>

						<div class="col-md-6 col-md-offset-3 mt-50 mb-10">
							<div class="col-md-9">
								<button type="submit" class="btn bg-blue btn-block" id="btnSearchLead">Search</button>
							</div>
							<div class="col-md-3 loader_con hidden">
								<img src="{{ asset('backend/images/loader.gif') }}">
							</div>
						</div>

					</div>
				</div>
			</div>
		</form>
	</div>
</div>
</section>
<section class="content">
	<div class="row">
		<div class="col-xs-12" id="search_result_con">
		</div>
	</div>
</section>
<!-- /.content -->

@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endpush
@push('js_custom')
<script>
	$(function () {
		var datepickerOptions = {
			autoclose: true,
			format: 'dd-mm-yyyy',
			todayBtn: true,
		};

		$('.datepicker').datepicker(datepickerOptions);

		$(document).on("click", "#btnSearchLead", function(e){
			e.preventDefault();
			$.ajax({
				data: $('#frmSearch').serialize(),
				url: 'search_result',
				type: 'post',
				beforeSend:function(){
					$.blockUI({
						message: '<i class="icon-spinner4 spinner"></i>',
						overlayCSS: {
							backgroundColor: '#1b2024',
							opacity: 0.8,
							zIndex: 999999,
							cursor: 'wait'
						},
						css: {
							border: 0,
							color: '#fff',
							padding: 0,
							zIndex: 9999999,
							backgroundColor: 'transparent'
						}
					});
				},
				success: function (data) {
					$.unblockUI();
					$("#search_result_con").html(data);
					$("#tbl_search_result").DataTable();
					$('.loader_con').addClass("hidden");
					$('html, body').animate({
						scrollTop: $("#search_result_con").offset().top
					}, 1000);

				},
				error: function (data) {

				}
			});
		});

		$(document).on("click", "#btnExportLeads", function(e){
			$('#frmSearch').submit()
			/*$.ajax({
				data: $('#frmSearch').submit(),
				url: 'export_report',
				type: 'post',
				beforeSend:function(){
					$.blockUI();
				},
				success: function (data) {
					$.unblockUI();

					//window.open('', '_blank');
				}
			});*/
		});


	});
</script>
@endpush