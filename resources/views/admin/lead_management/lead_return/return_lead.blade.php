@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')
@php
$user_id = Session::get('user.ses_user_pk_no');
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Lead Return</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('lead.index') }}">Lead Management</a></li>
		<li class="active">Lead Return</li>
	</ol>
</section>

<section id="product_details" class="content">
	<div class="row">
		<div class="col-xs-12">
			
			<div class="nav-tabs-custom">
				

				<div class="tab-content">
					@include('admin.lead_management.lead_return.all_lead')
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('js_lib')
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
@endpush

@push('js_custom')
<script>
	$(function () {

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        	checkboxClass: 'icheckbox_minimal-blue',
        	radioClass: 'iradio_minimal-blue'
        });

        $('.datepicker').datepicker({
        	autoclose: true,
        	format: 'dd-mm-yyyy',
        	todayBtn: true,
        	todayHighlight: true
        });

        $('#work_list').DataTable();

        $(document).on("click", ".distribute-lead", function(e){
        	if(confirm("Are You Sure?"))
        	{
        		blockUI();

        		var leadlifecycle_id = $(this).attr('data-id');
        		var action = $(this).attr('data-action');
        		var list_action = $(this).attr('data-list-action');
        		var list_type = $(this).attr("data-type");
        		var tab = $(this).attr("data-target");
        		var sales_agent = $("#cmbSalesAgent"+leadlifecycle_id).val();

        		if(sales_agent=="")
        		{
        			alert("You did not select any Sales Agent");
        			$.unblockUI();
        			return;
        		}

        		$.ajax({
        			data: { leadlifecycle_id : leadlifecycle_id, sales_agent: sales_agent },
        			url: action,
        			type: "get",
        			beforeSend:function(){

        			},
        			success: function (data) {
        				toastr.success(data.message, data.title);
                        location.reload();

                   }
               });
        		$.unblockUI();
        	}
        });

    });

</script>
@endpush
