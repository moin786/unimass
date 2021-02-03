@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Look Up Settings </h1>

	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Setting</a></li>
		<li class="active">Look Up</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<div class="col-md-6">
						<div class="form-group">
							<label>Look Up Type</label>
							<select name="cmbLookupTypeMst" id="cmbLookupTypeMst" class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" onchange="lookup_type_wise_data(this.value)">
								<option value="">Select</option>
								@foreach ($lookup_type as $key => $type)
								<option value="{{$key}}">{{ $type }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<span type="button" class="btn bg-purple pull-right create_modal" data-action="{{ route('settings.create') }}" data-id="">
						<i class="fa fa-plus" style="font-size:12px;"></i> Add Look Up
					</span>
				</div>

				<!-- /.box-header -->
				<div class="box-body table-responsive" id="list-body">
					@include('admin.settings.lookup.lookup_list')
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
<script>
    function lookup_type_wise_data(value){
        $.ajax({
            data: {value:value},
            url: 'lookup_type_wise_data',
            type: 'get',
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
                $("#list-body").html(data);
                $('.select2').select2();
                $('.data-table').dataTable( {
                    "columnDefs": [
                        { "width": "10px", "targets": 0 }
                    ]
                } );

            },
            error: function (data) {

            }
        });
    }
</script>
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endpush

@push('js_custom')
<script>

	$(function () {
		$('.select2').select2();
		$('.data-table').dataTable( {
			"columnDefs": [
			{ "width": "10px", "targets": 0 }
			]
		} );
	});
</script>
@endpush

