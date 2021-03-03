<div class="tab-pane active" id="schedule_followup">
	<div class="box mb-0">
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<table class="table table-bordered mb-0">
						<thead class="bg-blue">
							<th class="text-center">SL</th>
							<th class="text-center">Date</th>
							<th class="text-center">Followup Note</th>
							<th class="text-center">Next Followup Date</th>
							<th class="text-center">Meeting Date</th>
							<th class="text-left">Visit Note</th>
						</thead>
						<tbody>
							@if(!empty($schedule_followup))
							@foreach($schedule_followup as $followup)
							<tr>
								<td class="text-center">{{ $loop->iteration }}</td>
								<td class="text-center">{{ date("d/m/Y",strtotime($followup->created_at))}}</td>
								<td class="text-center">{{ $followup->followup_note }}</td>
								<td class="text-center">{{ date("d/m/Y",strtotime($followup->next_followup_date)) }}</td>
								<td class="text-center">{{ date("d/m/Y",strtotime($followup->visit_date)) }}</td>
								<td class="text-left">{{ $followup->meeting_note	 }}</td>
							</tr>
							@endforeach
							@else
							<tr>
								<td colspan="6" class="text-center">No data found</td>
							</tr>
							@endif
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<form id="scheduleFollowup" action="{{ route("store_schedule_followup.store") }}" method="post">
		@csrf
		<div class="form-row mt-10">
			<div class="col-md-12">
				<div class="box mb-0">
					<div class="box-header with-border ">
						<h3 class="box-title">Lead Information</h3>
					</div>
					<div class="box-body">
						<div class="form-row">
							<div class="col-md-8">
								<table class="table">
									<tbody>
										<tr>
											<th>Lead ID:</th>
											<td>
												{{ isset($lead_data) ? $lead_data->lead_id : '' }}
												<input type="hidden" class="keep_me" name="lead_pk_no" id="lead_pk_no"
												value="{{ isset($lead_data) ? $lead_data->lead_pk_no : '' }}" />
												<input type="hidden" class="keep_me" name="lead_id"
												value="{{ isset($lead_data) ? $lead_data->lead_id : '' }}" />
												<input type="hidden" class="keep_me" name="leadlifecycle_id"
												value="{{ isset($lead_data) ? $lead_data->leadlifecycle_pk_no : '' }}"
												readonly="readonly" />
											</td>
											<th>Lead Date: </th>
											<td>{{ isset($lead_data) ? date('d/m/Y', strtotime($lead_data->created_at)) : '' }}</td>
										</tr>
										<tr>
											<th>Client 1 :</th>
											<td>
												{{ isset($lead_data) ? $lead_data->customer_firstname . ' ' . $lead_data->customer_lastname : '' }}
											</td>
											<th>Client 2 : </th>
											<td>
												{{ isset($lead_data) ? $lead_data->customer_firstname2 . ' ' . $lead_data->customer_lastname2 : '' }}
											</td>
										</tr>



										@php
										$masking_number = substr($lead_data->phone1, 0, 7);
										$masking_number1 = substr($lead_data->phone2, 0, 7);
										@endphp


										<tr>
											<th>Mobile 1: </th>
											@if ($ses_user_id == $lead_data->created_by || $ses_user_id == $lead_data->lead_sales_agent_pk_no)
											<td>{{ isset($lead_data) ? $lead_data->phone1_code . '' . $lead_data->phone1 : '' }}</td>
											@else
											<td>{{ $masking_number }}****</td>
											@endif
											<th>Mobile 2:</th>
											@if ($ses_user_id == $lead_data->created_by || $ses_user_id == $lead_data->lead_sales_agent_pk_no)
											<td>{{ isset($lead_data) ? $lead_data->phone2_code . '' . $lead_data->phone2 : '' }}</td>
											@else
											<td>{{ !empty($masking_number1) ? $masking_number1 . '****' : ' ' }}</td>
											@endif
										</tr>
										<tr>
											<th>Email: </th>
											<td>{{ isset($lead_data) ? $lead_data->email_id : '' }}</td>
											<th>Size:</th>
											<td>
												{{ isset($lead_data) ? $lead_data->project_size : '' }}
											</td>
											<input type="hidden" class="keep_me" name="lead_category_id"
											value="{{ isset($lead_data) ? $lead_data->project_category_pk_no : '' }}" readonly="readonly" />
											<input type="hidden" class="keep_me" name="lead_project_id"
											value="{{ isset($lead_data) ? $lead_data->Project_pk_no : '' }}" readonly="readonly" />
											<input type="hidden" class="keep_me" name="lead_size_id"
											value="{{ isset($lead_data) ? $lead_data->project_size_pk_no : '' }}" readonly="readonly" />

										</tr>
										<tr>
											<th>Project: </th>
											<td>{{ isset($lead_data) ? $lead_data->project_name : '' }}</td>
											<th>Area:</th>
											<td>{{ isset($lead_data) ? $lead_data->project_area : '' }}</td>
										</tr>
										<tr>
											<th>Sales Agent: </th>
											<td>
												{{ isset($lead_data) ? $lead_data->lead_sales_agent_name : '' }}
												<input type="hidden" class="keep_me" name="sales_agent_id"
												value="{{ isset($lead_data) ? $lead_data->lead_sales_agent_pk_no : '' }}"
												readonly="readonly" />
											</td>
											<th>Created by:</th>
											<td>{{ isset($lead_data) ? $lead_data->user_full_name : '' }}</td>
										</tr>
										<tr>
											<th>Lead Current Stage: </th>
											<td>Sold
											</td>


										</tr>
									</tbody>
								</table>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label for="followup_note">Followup Note :</label>
									<textarea class="form-control" style="height: auto !important;" rows="3" id="followup_note" name="followup_note" title="Note" placeholder="Write Followup Note here"></textarea>
								</div>

								<div class="form-group">
									<label>Next Followup Date :</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker required" id="txt_followup_date" name="txt_followup_date">
									</div>
								</div>

								<div class="form-group">
									<label>Prefered Time :</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" class="form-control pull-right" id="txt_followup_date_time" name="txt_followup_date_time">
									</div>
								</div>

								<div class="form-group">
									<label>Meeting Date :</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input type="text" class="form-control pull-right datepicker " id="meeting_followup_date" name="meeting_followup_date">
									</div>
								</div>

								<div class="form-group">
									<label>Meeting Time :</label>
									<div class="input-group">
										<div class="input-group-addon">
											<i class="fa fa-clock-o"></i>
										</div>
										<input type="text" class="form-control pull-right" id="meeting_followup_date_time" name="meeting_followup_date_time">
									</div>
								</div>

								<div class="form-group">
									<label for="next_followup_note">Meeting Note :</label>
									<textarea class="form-control" rows="3" style="height: auto !important;" id="next_followup_note" name="next_followup_note" title="Note" placeholder="Write visit note here"></textarea>
								</div>

								<div class="form-group">
									<input type="checkbox" id="meeting_visit_confirmation" name="meeting_visit_confirmation" value="1" >
									<label for="meeting_visit_confirmation">Meeting Done?</label>
									<div id="meeting_visit_done_dt"></div>
								</div>
							</div>

							<div class="col-md-offset-8 col-md-4 ">	
								<button type="button" class="btn btn-xs bg-red " data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-xs bg-blue btnSaveUpdate">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">
	var datepickerOptions = {
		autoclose: true,
		format: 'dd-mm-yyyy',
		todayBtn: true,
		todayHighlight: true,
	};

	$('.datepicker').datepicker(datepickerOptions);
	$('#txt_followup_date_time').timepicker();
	$('#meeting_followup_date_time').timepicker();
</script>