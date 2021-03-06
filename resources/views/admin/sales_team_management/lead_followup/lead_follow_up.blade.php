@extends('admin.layouts.app')

@push('css_lib')
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style type="text/css">
	.istallment_percent {
		position: relative;
		right: 70px;
		top: 25px;
		z-index: 999;
	}
</style>
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
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
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


		$('body').on('focusout','#percent_of_first_installment', function(){
                $('#amount').attr('disabled',true);
                if($('#installment').val() == '') {
                    alert('No.of Installment cant not left empty');
                    $('#installment').focus();
                    return false;
                }
                
                delay(() =>{
                    if ($(this).val() == '') {
                        return false;
                    }
                    let thisval = $(this).val();
                    let amount = parseFloat($('#grand-total').val())*parseInt($(this).val())/100;
                    $('#amount').val(amount);
                    let fitstistallment = 1;
                    let noofinstallment = parseInt($('#installment').val());
                    let remainingistallment = ((noofinstallment+1)-fitstistallment)
                    let istallamount = [];
                    let second_installment = 0;
                    let other_installment = 0;
                    var istallment_obj = [];
                    var validation_array = [];
                    
                    let gulo = '';

                    istallment_obj.push({
                        installment: '1st Istallment',
                        amount: parseFloat(amount.toFixed(3)),
                        percent_of_total_apt_price: parseInt($(this).val())

                    })

                    istallamount.push(remainingistallment);
                    for(i = 1; i<=istallamount; i++) {
                        if (i == 1) {
                            continue;
                        }
                        if (i == 2) {
                            second_installment = (100-$(this).val())/(noofinstallment-1);
                            amount = $('#grand-total').val()*second_installment/100;
                            istallment_obj.push({
                                installment: '2nd Istallment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }

                        if (i == 3) {
                            gulo = 'rd';
                        } 
                        
                        if (i == 2) {
                            gulo = 'nd';
                        }

                        if (i != 3 && i!= 2) {
                            gulo = 'th';
                        }

                        if (i != 2) {
                            istallment_obj.push({
                                installment: i+gulo+ ' installment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }
                    }

                    //$(this).attr('disabled',true);
                    //$('#amount').attr('disabled',true);
                    $('body').on('click','.schegenerate', function(){
                        $('.required').each(function() {
                            if($(this).val() == '' || $(this).val() == 0) {
                                validation_array.push(1);
                                $(this).attr('style', 'border:2px solid #D44F49 !important');
                            }
                        });

                        if(validation_array.length > 0) {
                            toastr.error('You must fill up required fields', 'Validation Error');
                            return;
                        }
                        $(this).attr('disabled',true);
                        istallment_obj.forEach(function(installment) {
                            let generated_schedule = $('.generated_schedule');
                            
                            generated_schedule.append(`
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Date</span>
                                        <input type="text" name="schedule_date_save[]" class="form-control datepicker required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Installments <span class="text-danger"> *</span></span>
                                        <input type="text" name="installment_save[]" value="${installment.installment}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Amount <span class="text-danger"> *</span></span>
                                        <input type="text" name="amount_save[]" value="${installment.amount}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-text text-center istallment_percent">% <span class="text-danger"> *</span></div>
                                        <input type="text" name="percent_of_first_installment_save[]" value="${installment.percent_of_total_apt_price}" class="form-control required" placeholder="Installment" required>
                                    </div>
                                </div>
                            `);

                            $('.datepicker').datepicker();
                        });
                    });
                },1000);
            });


            $('body').on('focusout','#amount', function(){
                $('#percent_of_first_installment').attr('disabled',true);
                if($('#installment').val() == '') {
                    alert('No.of Installment cant not left empty');
                    $('#installment').focus();
                    return false;
                }
                
                delay(() =>{
                    if ($(this).val() == '') {
                        return false;
                    }
                    let thisval = $(this).val();
                    let instpercent = parseInt($(this).val())*100/parseFloat($('#grand-total').val());
                    $('#percent_of_first_installment').val(instpercent);
                    let fitstistallment = 1;
                    let amount = parseInt($(this).val());
                    let noofinstallment = parseInt($('#installment').val());
                    let remainingistallment = ((noofinstallment+1)-fitstistallment)
                    let istallamount = [];
                    let second_installment = 0;
                    let other_installment = 0;
                    var istallment_obj = [];
                    var validation_array = [];
                    
                    let gulo = '';

                    istallment_obj.push({
                        installment: '1st Istallment',
                        amount: parseFloat(amount.toFixed(3)),
                        percent_of_total_apt_price: parseFloat($('#percent_of_first_installment').val())

                    })

                    istallamount.push(remainingistallment);
                    for(i = 1; i<=istallamount; i++) {
                        if (i == 1) {
                            continue;
                        }
                        if (i == 2) {
                            second_installment = (100-parseFloat($('#percent_of_first_installment').val()))/(noofinstallment-1);
                            amount = $('#grand-total').val()*second_installment/100;
                            istallment_obj.push({
                                installment: '2nd Istallment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }

                        if (i == 3) {
                            gulo = 'rd';
                        } 
                        
                        if (i == 2) {
                            gulo = 'nd';
                        }

                        if (i != 3 && i!= 2) {
                            gulo = 'th';
                        }

                        if (i != 2) {
                            istallment_obj.push({
                                installment: i+gulo+ ' installment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }
                    }

                    $('body').on('click','.schegenerate', function(){
                        $('.required').each(function() {
                            if($(this).val() == '' || $(this).val() == 0) {
                                validation_array.push(1);
                                $(this).attr('style', 'border:2px solid #D44F49 !important');
                            }
                        });

                        if(validation_array.length > 0) {
                            toastr.error('You must fill up required fields', 'Validation Error');
                            return;
                        }
                        $(this).attr('disabled',true);
                        istallment_obj.forEach(function(installment) {
                            let generated_schedule = $('.generated_schedule');
                            
                            generated_schedule.append(`
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Date</span>
                                        <input type="text" name="schedule_date_save[]" class="form-control datepicker required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Installments <span class="text-danger"> *</span></span>
                                        <input type="text" name="installment_save[]" value="${installment.installment}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Amount <span class="text-danger"> *</span></span>
                                        <input type="text" name="amount_save[]" value="${installment.amount}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-text text-center istallment_percent">% <span class="text-danger"> *</span></div>
                                        <input type="text" name="percent_of_first_installment_save[]" value="${installment.percent_of_total_apt_price}" class="form-control required" placeholder="Installment" required>
                                    </div>
                                </div>
                            `);

                            $('.datepicker').datepicker();
                        });
                    });
                },1000);
            });

	});

</script>
@endpush
