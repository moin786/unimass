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
						<input type="text" class="form-control" id="installment_amount" name="installment_amount" value="{{ $schedule_info->amount }}" placeholder="0.00" disabled>
					</div>

					<div class="form-row">
						<div class="form-group col-md-6">
							<label  for="collected_amount">Collected Amount</label>
							<input type="text" class="form-control" id="collected_amount" name="collected_amount" value="{{ $col_amount }}" placeholder="0.00" disabled>
						</div>
						<div class="form-group col-md-6">
							<label  for="remaining_amount">Remaining Amount</label>
							<input type="text" class="form-control" id="remaining_amount" name="remaining_amount" value="{{  $schedule_info->amount- $col_amount  }}" placeholder="0.00" disabled>
							<input type="hidden" class="form-control" id="hdn_remaining_amount" name="hdn_remaining_amount" value="{{  $schedule_info->amount- $col_amount  }}" placeholder="0.00" >
						</div>
					</div>

					<div class="form-group">
						<label  for="amount">Amount</label>
						<input type="text" class="form-control" id="amount" name="amount" value="" placeholder="0.00" >
					</div>

					<div class="form-group">
						<label  for="check_no">Check No</label>
						<input type="text" class="form-control" id="check_no" name="check_no" value="" placeholder="0.00" >
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
					<input type="hidden" name="s_id" value="{{ $schedule_info->id }}">			
					<input type="hidden" name="lead_pk_no" value="{{ $schedule_info->lead_pk_no }}">
					<input type="hidden" name="lead_id" value="{{ $schedule_info->lead_id }}">			

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
		<div class="box box-primary">
			<div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Schedule List</h3>
			</div>
			<div class="box-body">
				<ul class="todo-list">
					@if(!empty($schedule_list))
					@foreach($schedule_list as $row)
					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">{{ $row-> installment  }}</span> 
							@if($row-> installment==$schedule_info->installment)
							<i class="fa fa-check" aria-hidden="true"></i>
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
				<h3 class="box-title">Collected Collection</h3>
			</div>
			<div class="box-body">
				<div class="form-row">
					<div class="col-md-4 col-md-offset-4">
						<div class="form-group">
							<label>Schedule List</label>
							<select class="form-control" id="cmb_project_name" name="cmb_project_name">
								<option value="">1st Installment</option>
								<option value="">2nd Installment</option>
								<option value="">3rd Installment</option>
								<option value="">4th Installment</option>
								<option value="">5th Installment</option>
								<option value="">6th Installment</option>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<table class="table table-bordered mb-0">
							<thead class="bg-blue">
								<th class="text-left">Installment</th>
								<th class="text-right">Installment Amount</th>
								<th class="text-right">Collected Amount</th>
								<th class="text-right">Due</th>
							</thead>
							<tbody>
								<tr>
									<td class="text-left">1st Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>
								<tr>
									<td class="text-left">2nd Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>
								<tr>
									<td class="text-left">3rd Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>


							</tbody>
						</table>
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