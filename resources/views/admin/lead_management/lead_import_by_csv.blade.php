@extends('admin.layouts.app')

@push('css_lib')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style>

</style>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Lead Entry</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('lead.index') }}">Lead Management</a></li>
		<li class="active">Lead Entry</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
	<div class="box box-info">
		<!-- form start -->
		<form id="frmLead" action="{{ route('store_import_csv') }}" method="post" enctype="multipart/form-data">
			{!! csrf_field() !!}
			<div class="box-body">
				<div class="form-group">
					<label for="csv_file" class="col-sm-2 control-label">CSV File Format</label>
					<div class="col-sm-10">
						<a href="{{ asset('uploads/docs/lead_import.csv') }}" class="btn bg-green btn-xs">Download</a>
					</div>
				</div>
				<div class="form-group">
					<label for="csv_file" class="col-sm-2 control-label">CSV File</label>
					<div class="col-sm-10">
						<input type="file" id="csv_file" name="csv_file" />
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<div class="col-sm-2"></div>
				<div class="col-sm-10">
					<button type="submit" class="btn btn-info">Sign in</button>
				</div>
			</div>
			<!-- /.box-footer -->
		</form>
	</div>
</section>
@endsection


@push('js_custom')
<script>


</script>
@endpush
