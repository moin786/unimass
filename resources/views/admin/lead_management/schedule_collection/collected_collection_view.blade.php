<div class="form-row">
	<div class="col-md-6">
		<div class="box">
			<div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Schedule Collection</h3>
			</div>
			<form id="frmLeadFollowup" action="{{ route('schedule-collection.store') }}" method="post"> @csrf
				<div class="box-body">
					<div class="form-group">
						<label>Schedule List</label>
						<select class="form-control" id="cmb_project_name" name="cmb_project_name">
							@isset($schedule_info->installment)
							<option value="">{{ $schedule_info->installment }}</option>
							@endif
						</select>
					</div>

					<div class="form-group">
						<label  for="installment_amount">Installment Amount</label>
						<input type="text" class="form-control" id="installment_amount" name="installment_amount" value="{{ isset($schedule_info->amount)? $schedule_info->amount : " " }}" placeholder="0.00" disabled>
					</div>

					<div class="form-row">
						<div class="form-group col-md-6">
							<label  for="collected_amount">Collected Amount</label>
							<input type="text" class="form-control" id="collected_amount" name="collected_amount" value="{{ $col_amount }}" placeholder="0.00" disabled>
						</div>
						<div class="form-group col-md-6">
							<label  for="remaining_amount">Remaining Amount</label>
							<input type="text" class="form-control" id="remaining_amount" name="remaining_amount" value="{{  isset($schedule_info->amount)? $schedule_info->amount- $col_amount : "0"  }}" placeholder="0.00" disabled>
							<input type="hidden" class="form-control" id="hdn_remaining_amount" name="hdn_remaining_amount" value="{{  isset($schedule_info->amount)? $schedule_info->amount- $col_amount : "0"  }}" placeholder="0.00" >
						</div>
					</div>

					<div class="form-group">
						<label>Bank</label>
						<select class="form-control" id="cmb_bank_id" name="cmb_bank_id">
							<option value="">Select Bank</option>
							@isset($banks)
							@foreach($banks as $key => $bank)
							<option value="{{$key}}">{{ $bank }}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group">
						<label  for="amount">Amount</label>
						<input type="text" class="form-control" id="amount" name="amount" value="" placeholder="0.00" >
					</div>

					<div class="form-group">
						<label  for="check_no">Check No</label>
						<input type="text" class="form-control" id="check_no" name="check_no" value="" placeholder="Check No" >
					</div>

					<div class="form-group">
						<label  for="collected_amount">Cheque Date</label>
						<input type="text" class="form-control datepicker" id="collected_amount" name="cheque_date" value="" placeholder="" >
					</div>				
					<div class="form-group">
						<label  for="received_date">Received Date</label>
						<input type="text" class="form-control datepicker" id="received_date" name="received_date" value="" placeholder="" >
					</div>
					<div class="form-group">
						<label  for="mr_no">MR No</label>
						<input type="text" class="form-control" id="mr_no" name="mr_no" value="" placeholder="MR No" >
					</div>	
					<div class="form-group">
						<label  for="mr_no">Remarks</label>
						<textarea name="remarks" id="" class="form-control"></textarea>
					</div>
					<input type="hidden" name="s_id" value="{{ isset($schedule_info->id)? $schedule_info->id :"0"}}">			
					<input type="hidden" name="lead_pk_no" value="{{ isset($schedule_info->lead_pk_no)?$schedule_info->lead_pk_no :"0"  }}">
					<input type="hidden" name="lead_id" value="{{ isset($schedule_info->lead_id)? $schedule_info->lead_id : "0" }}">			

					<div class="text-right">
						<button class="btn btn-xs bg-green btnSaveUpdate">Save</button>
						<button class="btn btn-xs bg-red">Close</button>
					</div>
				</div>
			</form>
			<div class="col-md-6">

			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="row">
			<div class="col-md-12">

				<div class="box box-primary">
					<div class="box-header">
						<i class="ion ion-clipboard"></i>
						<h3 class="box-title">Schedule List</h3>
					</div>
					<div class="box-body">
						<ul class="todo-list">
							@php
							$rec =0;
							@endphp
							@if(!empty($schedule_list))
							@foreach($schedule_list as $row)
							@php
							$rec = $rec + $row->amount;
							@endphp
							<li>
								<a href="#" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">{{ $row->installment  }}</span> 
									@if(isset($schedule_info->installment))
									@if($row->installment==$schedule_info->installment && $row->id == $schedule_info->id)
									<i class="fa fa-check" aria-hidden="true"></i>
									@else
									({{$row->payment_status}})
									@endif

									@endif
									{{-- <small class="label label-default pull-right">0</small> --}}

								</a>
							</li>
							@endforeach
							@endif

						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-12">

				<div class="box box-primary">
					<div class="box-header">
						<i class="ion ion-clipboard"></i>
						<h3 class="box-title">Summary</h3>
					</div>
					<div class="box-body">
						<ul class="todo-list">
							@php
							$due = 0;
							$rec = isset($rec)? $rec : 0;
							$col = isset($schedule_amount[0]->total_amount)? $schedule_amount[0]->total_amount: 0;
							$due = $rec-$col;

							@endphp
							
							<li>
								<a href="#" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">Receiveable amount</span>
									<small class="label  pull-right" style="color: #000;"> {{ number_format($rec,2) }}</small> 
									
									
								</a>
							</li>
							<li>
								<a href="#" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">Collection amount</span>
									<small class="label  pull-right" style="color: #000;"> {{ number_format($col,2) }}</small> 
									
									
								</a>
							</li>

							<li>
								<a href="#" class="routeSetUp">
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<span class="text">Due amount</span>
									<small class="label  pull-right" style="color: red;"> {{  number_format($due,2) }}</small> 
									
									
								</a>
							</li>
							

						</ul>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<script type="text/javascript">
	var datepickerOptions = {
		autoclose: true,
		format: 'dd-mm-yyyy',
		todayBtn: true,
		todayHighlight: true,
	};

	$('.datepicker').datepicker(datepickerOptions);
</script>