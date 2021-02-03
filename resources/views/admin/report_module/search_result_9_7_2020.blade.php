@php
$is_super_admin = Session::get('user.is_super_admin');
@endphp
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Search Result</h3>
		@if($is_super_admin == 1)
		<button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button>
		@endif
	</div>

	<div class="box-body">
		<table id="tbl_search_result" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th style=" min-width: 10px" class="text-center">Lead Id</th>
					<th style=" min-width: 70px" class="text-center">Customer Name</th>
					<th style=" min-width: 80px" class="texot-center">Mobile</th>
					<th style=" min-width: 100px" class="text-center">Project</th>
					<th style=" min-width: 50px" class="text-center">Agent</th>
					<th style=" min-width: 50px" class="text-center">Stage</th>
					<th style=" min-width: 50px" class="text-center">Next Followup</th>
					<th style=" min-width: 145px" class="text-center">Note</th>
					<th style=" min-width: 25px" class="text-center">Action</th>
				</tr>
			</thead>

			<tbody>
				@if(!empty($lead_data))
				@foreach($lead_data as $row)
				<tr>
					<td class="text-center">{{ $row->lead_id }}</td>
					<td class="text-center">{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
					<td class="text-center">{{ $row->phone1 }}</td>
					<td class="text-center">{{ $row->project_name }}</td>
					<td class="text-center">{{ $row->lead_sales_agent_name }}</td>
					<td>{{ isset($lead_stage_arr[$row->lead_current_stage])?$lead_stage_arr[$row->lead_current_stage]:'' }}</td>
					<td class="text-center">{{ date('d-m-Y') }}</td>
					<td class="text-center">{{ $row->next_followup_Note }}</td>
					<td class="text-center">
						<span class="btn bg-info btn-xs lead-view" title="Lead Sold" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="9" class="text-center text-danger">No Data Found</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
</div>