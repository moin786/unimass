@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Stage Wise Attribute</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Setting</a></li>
		<li class="active">Attribute</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box">
				<div class="box-header text-right">
					<!-- <span type="button" class="btn bg-purple btn-sm pull-right create_modal" data-modal="common-modal-sm" data-action="{{ route('Stage_wise_attribute') }}" data-title="Create stage wise attribute">
						<i class="fa fa-plus" style="font-size:12px;"></i> Create Attribute
					</span> -->

				<button class="btn bg-purple btn-sm pull-right create_modal" data-modal="common-modal-sm" data-action="{{ route('Stage_wise_attribute') }}" data-title="Create stage wise attribute" title="Create stage wise attribute"><i class="fa fa-plus" style="font-size:12px;"></i> Create Attribute</button>


				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive" id="list-body">
					@include('admin.settings.stage_wise_attribute.stage_wise_attribute_list')
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
	</div>
</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush
@push('js_custom')
<script>
	$(function () {
		//$('.data-table').DataTable();
		$('.data-table').dataTable( {
			"columnDefs": [
			{ "width": "10px", "targets": 0 }
			]
		} );
	});
</script>
@endpush
