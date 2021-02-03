@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Role Based Access Control</h1>
	<ol class="breadcrumb">
		<li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Setting</a></li>
		<li class="active">Rbac</li>
	</ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
	<div class="row">
		<div class="col-xs-6">
			<div class="box">
				<!-- /.box-header -->
				<div class="box-body table-responsive">
					<table id="user-role-table" class="table table-bordered table-striped table-hover data-table">
						<thead>
							<tr>
								<th style="width: 50px;">SL</th>
								<th>User Group</th>
								<th>Total User</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody>
							@foreach($user_group as $key=>$group)
							<tr>
								<td style="width: 50px;">{{ $loop->iteration }}</td>
								<td>{{ $group }}</td>
								<td align="center">{{ (isset($user_group_count[$key]))?$user_group_count[$key]:0 }}</td>
								<td class="text-center">
									<span class="btn bg-info btn-xs rbac-pages" data-id="{{ $key }}" data-action="{{ route('rbac_pages', $key) }}"><i class="fa fa-plus"></i></span>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<div class="col-xs-6" id="rbac_pages">
			
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
		$('#user-role-table').dataTable( {
			"columnDefs": [
			{ "width": "10px", "targets": 0 }
			]
		} );

		$(document).on("click",".rbac-pages",function(){
			var role_id = $(this).attr("data-id");
			var action = $(this).attr("data-action");
			$.ajax({
				url: action,
				type: "get",
				beforeSend:function(){
					blockUI();
				},
				success: function (data) {
					$.unblockUI();
					$('#rbac_pages').html(data);
					$('#page-table').dataTable();
				}

			});
		});

		$(document).on("click",".assign-pages",function(){
			if($(this).is(':checked'))
			{
				var is_checked = 1;
			}
			else
			{
				var is_checked = 0;
			}
			var role_id = $(this).attr("data-id");
			var action = $(this).attr("data-action");
			$.ajax({
				url: action,
				type: "get",
				data: { is_checked: is_checked },
				beforeSend:function(){
					blockUI();
				},
				success: function (data) {
					$.unblockUI();
					toastr.success(data.message, data.title);
				}

			});
		});
	});
</script>
@endpush
