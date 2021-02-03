@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush
<style type="text/css">
	.box-con{ padding: 10px; }
	.unit-box{
		background-color: #EEFCF7 ;
		padding: 5px; border-radius:5px;
		float:left; margin-right:5px; margin-bottom:5px; border:1px solid #e3e3e3; font-weight: normal;
	}
	.unit-box1{
		background-color: #FCE8F0;
		padding: 5px; border-radius:5px;
		float:left; margin-right:5px; margin-bottom:5px; border:1px solid #e3e3e3; font-weight: normal;
	}
	.border-top{ border-top:1px solid #999; }
</style>
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Project Wise Flat Setup </h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Setting</a></li>
		<li class="active">Project Wise Flat Setup</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<span type="button" class="btn bg-purple btn-sm pull-right create_modal" data-action="{{ route('create_project_wise_flat') }}" data-title="Project Wise Flat Setup">
						<i class="fa fa-plus" style="font-size:12px;"></i> Add New
					</span>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					@if(!empty($category_project_arr))
					@foreach($category_project_arr as $category_id => $cat_data)
					@foreach($cat_data as $area_id => $area_info)
					@foreach($area_info as $project_id => $project_info)
					@php
					$cat_row = explode("_", $project_info);
					@endphp
					<div class="box box-primary">
						<div class="box-header with-border">
							<div class="row">
								<div class="col-md-4">
									<span>Category: {{ $cat_row[0] }}</span>
								</div>
								<div class="col-md-4">
									<span>Area: {{ $cat_row[1] }}</span>
								</div>
								<div class="col-md-4">
									<span>Project Name: {{ $cat_row[2] }}</span>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12 box-con">
									@if(isset($project_flat_arr[$project_id]))
									@foreach($project_flat_arr[$project_id] as $flat)
									@php
									$flat_info = explode("_",$flat );
									@endphp

									@if($flat_info[1]==1)
									<div class="unit-box1">
									<span type="button" class="create_modal cursor-pointer" data-action="{{ route('edit_project_wise_flat',$flat_info[2]) }}" data-title="Project Wise Flat Setup"> {{ $flat_info[0] }}  <i class="fa fa-pencil" aria-hidden="true"></i> </span>	
									</div>
									@else
									<div class="unit-box">
										<span type="button" class="create_modal cursor-pointer" data-action="{{ route('edit_project_wise_flat',$flat_info[2]) }}" data-title="Project Wise Flat Setup" title="Flat Name :{{  $flat_info[0] }}, Asking Price :{{  $flat_info[3] }}, Down Payment : {{ $flat_info[4] }}, No of Installment {{ $flat_info[6] }}, Installment Amount: {{ $flat_info[5] }} " > {{ $flat_info[0] }} <i class="fa fa-pencil" aria-hidden="true"></i>  </span>	
									</div>
									@endif
									@endforeach
									@endif
									<br clear="all">
								</div>
							</div>
						</div>
					</div>
					@endforeach
					@endforeach
					@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush

@push('js_custom')
<script>
        //Initialize Select2 Elements
        $('.select2').select2();
        //Date picker
        $('.date').datepicker({
        	format: 'dd-mm-yyyy',
        	autoclose: true,
        	todayHighlight: true
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <script>
    	toastr.error('{{ $error }}');
    </script>
    @endforeach
    @endif
    @endpush

