@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
$ses_user_role = Session::get('user.ses_role_lookup_pk_no');
$ses_user_id   = Session::get('user.ses_user_pk_no');
@endphp
<div class="tab-pane active table-responsive" id="all_lead">
	<table id="work_list" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				@include('admin.components.lead_list_table_header')
				<th class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_data))
			@foreach($lead_data as $row)
			@php 
			if($row->lead_transfer_flag==1 && isset($trans_arr[$row->lead_pk_no])){
				$transfer_color = "background-color: rgba(254,37,37,.1) !important;";
				$trans_title = "This Lead is requested to Transfer and waiting for approval from Cluster Head";
			}
			else
			{
				$transfer_color=$trans_title = "";
			}
			@endphp
			<tr style="{{ $transfer_color }}" title="{{ $trans_title }}">
				@include('admin.components.lead_list_table')
				<td class="text-center" style="font-weight: bold;">
					<span class="btn bg-info btn-xs lead-view" title="View Lead Details" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>					
					
					@if($ses_user_role == 77)
					<span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_follow_up.edit',$row->lead_pk_no) }}">
						<i class="fa fa-list"></i>
					</span>

					@if($row->lead_sales_agent_pk_no == $ses_user_id)
					<span class="btn bg-info btn-xs lead-edit" data-title="Lead Edit" title="Lead Edit" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead.edit',$row->lead_pk_no) }}"><i class="fa fa-edit"></i></span>
					@endif
					@endif

					@if($row->lead_transfer_flag==1 && isset($trans_arr[$row->lead_pk_no]))
					<span class="btn btn-danger btn-xs" title="This Lead is requested to Transfer and waiting for approval from Cluster Head">T</span>
					@endif
					
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>
</div>
