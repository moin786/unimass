<form id="frmLeadFollowup" action="{{ route('lead_follow_up.store') }}" method="post">
	<input type="hidden" name="hdn_lead_pk_no" value="{{ $lead_data->lead_pk_no }}"/>
	<input type="hidden" name="hdn_lead_followup_pk_no" value="{{ $lead_data->lead_followup_pk_no }}"/>
	<input type="hidden" name="hdn_cur_stage" value="{{ $lead_data->lead_current_stage }}"/>
	@php
	if($lead_data->lead_current_stage == 1)
	{
		$stages = [3,8,10,11];
	}
	if(in_array($lead_data->lead_current_stage, [6,9]))
	{
		$stages = [1,3];
	}
	if($lead_data->lead_current_stage == 3)
	{
		$stages = [4,5,6,9];
	}
	if($lead_data->lead_current_stage == 4)
	{
		$stages = [5,6,9];
	}
	@endphp
	<div class="row">
		<div class="col-md-8">
			@include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')
			<div class="col-md-6">
				<div class="form-group">
					<label>Change Stage :</label>
					<select class="form-control select2 select2-hidden-accessible" style="width: 100%;"
					aria-hidden="true" name="cmb_change_stage">
					<option selected="selected" value="0">Please Select Stage</option>
					@if(!empty($lead_stage_arr))
					@foreach ($lead_stage_arr as $key => $stage)
					@if( in_array($key, $stages) )
					<option value="{{ $key }}">{{ $stage }}</option>
					@endif
					@endforeach
					@endif
				</select>
			</div>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>Followup Type :</label>
			<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true"
			name="cmbFollowupType">
			<option selected="selected" value="0">Please Select Followup Type</option>
			@if(!empty($followup_type))
			@foreach ($followup_type as $key => $f_type)
			<option value="{{ $key }}">{{ $f_type }}</option>
			@endforeach
			@endif
		</select>
	</div>

	<div class="form-group">
		<label for="followup_note">Note :</label>
		<textarea class="form-control" rows="6" id="followup_note" name="followup_note" title="Note"
		placeholder="Write Followup Note ..."></textarea>
	</div>

	<div>
		<div class="box-header with-border" style="padding-left: 0 !important;">
			<h3 class="box-title">Next Followup</h3>
		</div>
		<div class="form-group">
			<label>Next Followup Date :</label>
			<div class="input-group">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" class="form-control pull-right" id="txt_followup_date" name="txt_followup_date">
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
			<label for="next_followup_note">Note :</label>
			<textarea class="form-control" rows="5" id="next_followup_note" name="next_followup_note"
			title="Note" placeholder="Next Followup Note ..."></textarea>
		</div>
	</div>
</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
	<button type="submit" class="btn btn-success btn-sm btnSaveUpdate" data-response-action="{{ route('load_followup_leads') }}" data-tab="1" >Save</button>
</div>
</form>
