@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ URL::asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

@endpush

@section('content')
@php
$user_id = Session::get('user.ses_user_pk_no');
$userRoleId = Session::get('user.ses_role_lookup_pk_no');
@endphp
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>Lead Distribution</h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('lead.index') }}">Lead Management</a></li>
		<li class="active">Lead Entry</li>
	</ol>
</section>

<section id="product_details" class="content">
	<div class="row">
		<div class="col-xs-12">
			@if($is_hod == 1 || $is_hot == 1 || $is_tl == 1 || $userRoleId ==551 )
			<div class="nav-tabs-custom">
                @if($userRoleId !=551)
                <ul class="nav nav-tabs">
                   <!-- <li class="active"><a href="#all_lead" data-toggle="tab" data-type="" data-action="load_dist_leads" aria-expanded="true">All Leads</a></li> -->
                   <li class="active"><a href="#manual_lead" data-toggle="tab" data-type="1" data-action="load_dist_leads" aria-expanded="true">Pending</a></li>
                   <li class=""><a href="#pending_lead" data-toggle="tab" data-type="0" data-action="load_dist_leads" aria-expanded="false">Completed</a></li>

                   <!-- <li class=""><a href="#auto_lead" data-toggle="tab" data-type="2" data-action="load_dist_leads" aria-expanded="false">Auto</a></li> -->
               </ul>
               @endif

               <div class="tab-content">
                 @include('admin.lead_management.lead_distribution.all_lead')
             </div>
         </div>
         @else
         <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4 class="pull-left" style="margin-right: 20px;"><i class="icon fa fa-ban"></i> Forbidden!</h4>
            You are not Authorized to view this page
        </div>
        @endif
    </div>
</div>
</section>
@endsection

@push('js_lib')
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

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

        $('#work_list').DataTable(
        {
            "ordering": false
        }

        );

        $(document).on("click", ".distribute-lead", function(e){
        	if(confirm("Are You Sure?"))
        	{
        		blockUI();

                var formData = $("#distribute-form").serialize();

                var action = $(this).attr('data-action');
                var list_action = $(this).attr('data-list-action');
                var list_type = $(this).attr("data-type");
                var tab = $(this).attr("data-target");
                var sales_agent = $("#cmbTransferTo").val();
                if(sales_agent=="")
                {
                 alert("You did not select any Sales Agent");
                 $.unblockUI();
                 return;
             }

             $.ajax({
                 data: formData,
                 url: action,
                 type: "post",
                 beforeSend:function(){

                 },
                 success: function (data) {
                    $.ajax({
                       data: { tab_type : list_type },
                       url: list_action,
                       type: "post",
                       beforeSend:function(){

                       },
                       success: function (data) {
                          $(".tab-content").html(data);
                          $(tab).addClass("active");
                          $('.table').DataTable({
                            "ordering": false
                        });
                      }

                  });
                    toastr.success(data.message, data.title);
                }
            });
             $.unblockUI();
         }
     });

        $(document).on("change", ".auto_distribute", function(e){
        	var user_id = "{{ $user_id }}";
        	var dist_value = $(this).val();
        	var dist_date = $("#dist_date").val();
        	$.ajax({
        		data: { user_id : user_id, dist_value:dist_value, dist_date:dist_date },
        		url: 'lead_auto_distribute',
        		type: "get",
        		beforeSend:function(){
        			$('input[type="radio"].flat-red').iCheck({
        				checkboxClass: 'icheckbox_flat-green',
        				radioClass: 'iradio_flat-green'
        			});
        		},
        		success: function (data) {
        			toastr.success(data.message, data.title);
        		}
        	});
        });

    });

</script>
@endpush
