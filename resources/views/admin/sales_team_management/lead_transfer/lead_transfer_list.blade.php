<div class="tab-pane active" id="lead_transfer">
	<div class="head_action"
	style="background-color: #ECF0F5; border: 1px solid #ccc; padding: 3px;">
	<div class="box-body">
		<div class="row">
			<input type="hidden" name="cmb_category" id="cmb_category" data-action="{{ route('load_area_project_size') }}"/>
			{{-- <div class="col-md-2">
					<div class="form-group">
						<label>Category</label>
						<select class="form-control required" id="cmb_category" name="cmb_category"
						data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
						<option selected="selected" value="0">Select Category</option>
						@if(!empty($project_cat))
						@foreach ($project_cat as $key => $cat)
						<option value="{{ $key }}">{{ $cat }}</option>
						@endforeach
						@endif
					</select>
				</div>
			</div> --}}

		<div class="col-md-2">
			<div class="form-group">
				<label>Area</label>
				<select class="form-control required" id="cmb_area" name="cmb_area" style="width: 100%;"
				aria-hidden="true">
				<option selected="selected" value="">Select Area</option>
			</select>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			<label>Project Name</label>
			<select class="form-control required" id="cmb_project_name" name="cmb_project_name"
			style="width: 100%;" aria-hidden="true">
			<option selected="selected" value="">Select Project Name</option>
		</select>
	</div>
</div>

<div class="col-md-2">
	<div class="form-group">
		<label>Size</label>
		<select class="form-control required" id="cmb_size" name="cmb_size" style="width: 100%;"
		aria-hidden="true">
		<option selected="selected" value="">Select Size</option>
		@if(!empty($project_area))
		@foreach ($project_area as $key => $size)
		<option value="{{ $key }}">{{ $size }}</option>
		@endforeach
		@endif
	</select>
</div>
</div>
<div class="col-md-4">
	<div class="form-group">
		<label>Transfer To<span class="text-danger"> *</span></label>
		<div id="team_member_list">
			@include('admin.components.multiple_team_member_dropdown')
		</div>
	</div>
</div>
</div>
</div>

</div>

<div class="box-body">
	<div class="table-responsive">
		<table id="lead_transfer" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					@include('admin.components.lead_list_table_header')
					<th class="text-center">Last Followup</th>
					<th class="text-center">Next Followup</th>
					<th class="text-center">
						Select
						<a href="#" class="btn bg-blue btn-block btn-xs btn-transfer"
						data-response-action="{{ route('load_transfer_leads') }}">Transfer</a></th>
						<th class="text-center">Action</th>
					</tr>
				</thead>

				<tbody>
					@if(!empty($lead_transfer_list))
					@foreach($lead_transfer_list as $row)
<!-- 					@php
					//$checkingDate= isset($followup_arr[$row->lead_pk_no]['lead_followup_datetime'])?$followup_arr[$row->lead_pk_no]['lead_followup_datetime']:date("Y-m-d");
					//$date_def = date_diff( date_create($row->created_at),date_create($checkingDate));

					@endphp -->


				<!-- 	if($date_def->format("%a")<$days) -->
					<tr>
						@include('admin.components.lead_list_table')
						<td class="text-center">{{ isset($followup_arr[$row->lead_pk_no]['lead_followup_datetime'])?$followup_arr[$row->lead_pk_no]['lead_followup_datetime']:'' }}</td>
						<td class="text-center">{{ isset($followup_arr[$row->lead_pk_no]['Next_FollowUp_date'])?$followup_arr[$row->lead_pk_no]['Next_FollowUp_date']:'' }}</td>
						<td class="text-center">
							<input type="checkbox"
							data-id="{{ $row->lead_pk_no }}"
							data-name="{{ $row->lead_id }}"
							data-category="{{ $row->project_category_pk_no }}"
							data-agent="{{ $row->lead_sales_agent_pk_no }}">
						</td>
						<td class="text-center">
							<span class="btn bg-info btn-xs lead-view" title="Lead Sold"
							data-id="{{ $row->lead_pk_no }}"
							data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i
							class="fa fa-eye"></i></span>
						</td>
					</tr>
				<!-- 	endif -->
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
	</div>
</div>
