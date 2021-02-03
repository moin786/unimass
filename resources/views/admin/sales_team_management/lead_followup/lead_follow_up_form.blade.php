<link rel="stylesheet"
href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

<form id="frmLeadFollowup" action="{{ route('lead_follow_up.store') }}" method="post">
	<input type="hidden" class="keep_me" name="hdn_lead_pk_no" value="{{ $lead_data->lead_pk_no }}"/>
	<input type="hidden" class="keep_me" name="hdn_lead_followup_pk_no" value="{{ $lead_data->lead_followup_pk_no }}"/>
	<input type="hidden" class="keep_me" name="hdn_cur_stage" value="{{ $lead_data->lead_current_stage }}"/>
	@php
	if($lead_data->lead_current_stage == 1)
	{
		$stages = [$lead_data->lead_current_stage,3,8,9,10,11,13];
	}
	if($lead_data->lead_current_stage == 8)
	{
		$stages = [$lead_data->lead_current_stage,3,4,5,6];
	}
	if($lead_data->lead_current_stage == 10)
	{
		$stages = [$lead_data->lead_current_stage,3,8,11];
	}
	if($lead_data->lead_current_stage == 11)
	{
		$stages = [$lead_data->lead_current_stage,3,8,10];
	}
	if(in_array($lead_data->lead_current_stage, [6,9]))
	{
		$stages = [$lead_data->lead_current_stage,1,3];
	}
	if($lead_data->lead_current_stage == 3)
	{
		$stages = [$lead_data->lead_current_stage,4,5,6,13];
	}
	if($lead_data->lead_current_stage == 4)
	{
		$stages = [$lead_data->lead_current_stage,5,6];
	}
	if($lead_data->lead_current_stage == 5)
	{
		$stages = [$lead_data->lead_current_stage,3,4,6];
	}
	if($lead_data->lead_current_stage == 13)
	{
		$stages = [$lead_data->lead_current_stage,4, 5, 6];
	}
	if($lead_data->lead_current_stage == 14)
	{
		$stages = [$lead_data->lead_current_stage,5, 6];
	}
	@endphp


	@include("admin.components.lead_view")


	<div class="row">
		<div class="col-md-8">
			@include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')
			<div class="col-md-6">
				<div class="form-group">
					<label>Change Stage :</label>
					<select class="form-control select2 select2-hidden-accessible" style="width: 100%;"
					aria-hidden="true" id="cmb_change_stage" name="cmb_change_stage"
					onchange="getStageProperty(this.value)">
					<option selected="selected" value="0">Please Select Stage</option>
					@if(!empty($lead_stage_arr))
					@foreach ($lead_stage_arr as $key => $stage)
					@if( in_array($key, $stages) )
					<option value="{{ $key }}" {{ ($key==$lead_data->lead_current_stage)?"selected" : " " }}>{{ $stage }}</option>
					@endif
					@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="col-md-6"></div>
		<div class="col-md-6">
			<div class="form-group" id="search-container">
				@if(!empty($current_stage_attr))
				@foreach($current_stage_attr as $stage_arr)
				@php
				$is_checked = isset($attr_val_arr[$stage_arr->attr_pk_no])?"checked='checked'":"";
				$attr_value = isset($attr_val_arr[$stage_arr->attr_pk_no])?
				$attr_val_arr[$stage_arr->attr_pk_no]:"";
				$attr_value = explode("_",$attr_value);

				@endphp
				<div id="{{ $stage_arr->attr_pk_no }}1">
					<input type="checkbox" id="{{$stage_arr->attr_pk_no }}" onchange="appendDecsision(this)" name="attribute_type[]"  data-value="{{ strtolower($attr_type_value[$stage_arr->attr_type]) }}" value="{{ $stage_arr->attr_pk_no.'_'.$stage_arr->attr_type }}" {{ $is_checked }}> 
					<label for="{{$stage_arr->attr_pk_no }}" style="font-weight:400;">{{ $stage_arr->attr_name }}</label>
					@if($is_checked != "")
					@if($attr_value[1]==3)
					<input type="text" id="text{{ $stage_arr->attr_pk_no }}" class="form-control" name='text{{ $stage_arr->attr_pk_no }}' value="{{ isset($attr_value[0])? $attr_value[0]:' '  }}">
					@elseif($attr_value[1]==2)
					<input type="text" id="text{{ $stage_arr->attr_pk_no }}" class="form-control datepicker" name='text{{ $stage_arr->attr_pk_no }}' value="{{ isset($attr_value[0])? date('d-m-Y',strtotime($attr_value[0])):' ' }}">
					@endif
					@endif
				</div>
				@endforeach
				@endif

			</div>
		</div>
	</div>
	@php

	if($lead_data->lead_current_stage =='13'){
	$display_design = " ";
}else{
$display_design = "hidden";
}
@endphp
<div class="col-md-4">
	<div class="form-group {{ $display_design }}" id="flat_list_data">
		<label>Flat Size :</label>
		<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true"
		name="flat_id">
		<option selected="selected" value="0">Select Flat Size</option>
		@if(!empty($flat_list))
		@foreach($flat_list as $flat)
		<option
		value="{{ $flat->flatlist_pk_no }}" {{ ($flat->flatlist_pk_no == $lead_data->flatlist_pk_no )? 'selected' : '' }} >
		{{ $flat->flat_name }}
	</option>
	@endforeach
	@endif
</select>
</div>

<div class="form-group">
	<label for="followup_note">Note :</label>
	<textarea class="form-control" style="height: auto !important;" rows="3" id="followup_note"
	name="followup_note" title="Note"
	placeholder="Write Followup Note here"></textarea>
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
			<input type="text" class="form-control pull-right required" id="txt_followup_date"
			name="txt_followup_date">
		</div>
	</div>
	<div class="form-group">
		<label>Prefered Time :</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-clock-o"></i>
			</div>
			<input type="text" class="form-control pull-right" id="txt_followup_date_time"
			name="txt_followup_date_time">
		</div>
	</div>
	<div class="form-group">
		<label>Visit Type :</label>
		<select class="form-control" id="txt_meeting_status" name="txt_meeting_status" title="Meeting Status">
			<option selected="selected" value="">Select One</option>
			@if(!empty($lead_status))
			@foreach($lead_status as $key)
			<option value="{{$key->lookup_pk_no}}"> {{ $key->lookup_name }} </option>
			@endforeach
			@endif
		</select>
	</div>

	<div class="form-group">
		<label>Visit Date :</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-calendar"></i>
			</div>
			<input type="text" class="form-control pull-right " id="meeting_followup_date"
			name="meeting_followup_date">
		</div>
	</div>
	<div class="form-group">
		<label>Visit Prefered Time :</label>
		<div class="input-group">
			<div class="input-group-addon">
				<i class="fa fa-clock-o"></i>
			</div>
			<input type="text" class="form-control pull-right" id="meeting_followup_date_time"
			name="meeting_followup_date_time">
		</div>
	</div>



	<div class="form-group">
		<label for="next_followup_note">Note :</label>
		<textarea class="form-control" rows="3" style="height: auto !important;" id="next_followup_note"
		name="next_followup_note"
		title="Note" placeholder="Write visit note here"></textarea>
	</div>

	<div class="form-group">
		<input type="checkbox" id="meeting_visit_confirmation" name="meeting_visit_confirmation" value="1" onclick="visit_done()">
		<label for="meeting_visit_confirmation">Meeting or Visit Done?</label>
		<div id="meeting_visit_done_dt"></div>
		
	</div>
</div>
</div>
</div>
@php
if($followup_info->no_of_followup >= $no_of_followup->lookup_name){

$hiddenClass = "hidden";
$hiddenClass1 = " ";
}else{
$hiddenClass = " ";
$hiddenClass1 = "hidden";
}

@endphp

<div class="modal-footer">
	<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
	@if($page == "")
	<button id="max_folowup" type="button" class="btn btn-success btn-sm {{$hiddenClass1}}"
	data-number-of-followup=" {{ $no_of_followup->lookup_name }} "
	data-actual-followup="{{ $followup_info->no_of_followup }}" onclick="followup_alert(this)"
	data-response-action="{{ route('load_followup_leads') }}"
	data-current-stage="{{ $followup_info->lead_current_stage }}" data-tab="1"> Save
</button>
<button id="folowup" type="submit" class="btn btn-success btn-sm btnSaveUpdate {{$hiddenClass}}"
data-response-action="{{ route('load_followup_leads') }}" data-tab="1">Save
</button>
@else
<button type="submit" class="btn btn-success btn-sm btnSaveUpdateFollowup"
data-response-action="{{ route('lead_list', $type) }}">Save
</button>
@endif
</div>

</form>

<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">

	var datepickerOptions = {
		autoclose: true,
		format: 'dd-mm-yyyy',
		todayBtn: true,
		todayHighlight: true,
	};

	$('.datepicker').datepicker(datepickerOptions);

	function visit_done(){

		if($("#meeting_visit_confirmation").prop('checked') == true){
			var meeting = '<input type="text" name="txt_meeting_visit_done_dt" id="txt_meeting_visit_done_dt" class="form-control datepicker" placeholder="dd-mm-yyyy">';
			$('#meeting_visit_done_dt').html(meeting);
			$('.datepicker').datepicker(datepickerOptions);
		}else{
			$('#meeting_visit_done_dt').html('');
		}

	}

	function followup_alert(thisElement) {
		var actualNumber = $(thisElement).attr('data-actual-followup');
		var numberOfFollowup = $(thisElement).attr('data-number-of-followup');
		var currentStage = $(thisElement).attr('data-current-stage');
		var newStage = $("#cmb_change_stage").val();

		if (currentStage == newStage || newStage == 0) {
			alert("Maximum " + numberOfFollowup + " follow up is allowed in each stage.\nYou must change the stage of this Lead to proceed further.");

		} else {


		}

	}

	function getStageProperty(value) {
		if (value == 13) {
			$("#flat_list_data").removeClass("hidden");
		} else {
			$("#flat_list_data").addClass("hidden");
		}
		$.ajax({
			data: {value: value},
			url: "{{ route('stage_wise_attribute_get') }}",
			type: "get",
			success: function (data) {
				$("#search-container").html(data);
			}
		});
		var currentStage = $("#max_folowup").attr('data-current-stage');
		var newStage = $("#cmb_change_stage").val();
		var actualNumber = $("#max_folowup").attr('data-actual-followup');
		var numberOfFollowup = $("#max_folowup").attr('data-number-of-followup');
		if (actualNumber < numberOfFollowup) {
			if (currentStage == newStage || newStage == 0) {
				$("#max_folowup").removeClass("hidden");
				$("#folowup").addClass("hidden");
			} else {
				$("#max_folowup").addClass("hidden");
				$("#folowup").removeClass("hidden");
			}
		}
		if (value == 9 || value ==6) {
			$("#txt_followup_date").removeClass("required");
		}

	}

	function appendDecsision(element) {
        //var id = $(element).attr('id');
        var checkboxId = $(element).attr('id');
        var chekboxValue = $(element).attr('data-value');
        var id = $(element).parents("div").attr('id');
        textId = 'text' + checkboxId;
        if ($("#" + checkboxId).is(':checked')) {
        	if (chekboxValue == 'textbox') {
        		var textbox = "<input type='textbox' id='" + textId + "' name='" + textId + "'  class='form-control'>";
        		$(element).parents("#" + id).append(textbox);
        	} else if (chekboxValue == 'date') {
        		var textbox = "<input type='text' id='" + textId + "' class='form-control datepicker' name='" + textId + "'>";
        		$(element).parents("#" + id).append(textbox);
        		$('.datepicker').datepicker(datepickerOptions);


        	} else {
        		var textbox = "<input type='hidden' id='" + textId + "' class='form-control datepicker' name='" + textId + "' value='1'>";

        		$(element).parents("#" + id).append(textbox);

        	}

        } else {
        	$("#" + textId).remove();
        }
    }

</script>
