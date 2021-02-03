@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
@endphp
<form id="junk-distribute-form">
	<div class="tab-pane active" id="all_lead">
		@if($tab!=0 )
		@if($user_type == 1 || $userRoleID == 551)
		<div class="head_action" style="text-align: left; border-bottom: 1px solid #ccc; margin-bottom:10px;">
			<div class="box-body ">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label>Distribute To<span class="text-danger"> *</span></label>
							<select class="form-control required select2" id="cmbTransferTo" name="cmbTransferTo" style="width: 100%;" aria-hidden="true">
								<option value="">Select</option>
								@if(!empty($sales_agent_info))
								@foreach($sales_agent_info as $key=> $value)
								@php
								$team_name = $key;
								@endphp
								<optgroup label="{{ $key }}">
									@foreach($value as $name=>$val)

									@php
									$data = explode("_",$val);
									$prefix = "SA";

									if($data[2] == 1 ){
									$prefix= "CH";
								}else if ($data[3] == 1 ) {
								$prefix = "BH";
							} else if ($data[4] == 1 ){
							$prefix = "TL";
						}
						@endphp
						<option value="{{ $data[0].'_'.$team_ch[$data[5]]  }}" >{{ $prefix }} - {{$data[1]}} </option>
						@endforeach
					</optgroup>
					@endforeach
					@endif
				</select>

			</div>
		</div>
		<div class="col-md-3">
			<button type="button" class="btn btn-sm btn-success btn-md distribute-lead mt-13"
			title="Distribute Junk Lead"
			data-type="1" data-list-action="load_junk_leads" data-target="#all_lead"
			data-action="{{ route('distribute_junk_lead') }}">
			Distribute
		</button>
	</div>
</div>
</div>
@endif
</div>
@endif
<div class="table-responsive">
	<table id="work_list" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				@include('admin.components.lead_list_table_header')
				@if($tab!=0)
				@if($user_type == 1 || $userRoleID == 551)
				<th>Action</th>
				@endif
				@endif
				<th class="text-center">View</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($lead_data))
			@foreach($lead_data as $row)
			<tr>
				@include('admin.components.lead_list_table')
				@if($tab!=0)
				@if($user_type == 1 || $userRoleID == 551)
				<td class="text-center">
					<input type="checkbox" name="lead_life_cycle_id[]" value="{{ $row->leadlifecycle_pk_no  }}">
				</td>
				@endif
				@endif
				<td class="text-center">
					<span class="btn bg-info btn-xs lead-view" title="View Lead Details"
					data-id="{{ $row->lead_pk_no }}"
					data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>
</div>
</form>
