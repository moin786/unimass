@php
$ses_user_id   = Session::get('user.ses_user_pk_no');
@endphp
<tr>
	@include('admin.components.lead_list_table')	
	
	<td> {{ $row->last_followup_name }} </td>
	<td>{{ ($row->last_followup_name != "")?$followup_dt:'' }}</td>	
	<td>{{ $row->followup_Note }}</td>

	<td width="150" align="center">
		<span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}">
			<i class="fa fa-eye"></i>
		</span>

		@if($row->lead_sales_agent_pk_no == $ses_user_id)
		<span class="btn bg-info btn-xs lead-edit" data-title="Lead Edit" title="Lead Edit" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead.edit',$row->lead_pk_no) }}"><i class="fa fa-edit"></i></span>
		@endif
		@if (!Session::get('user.is_team_leader') && !Session::get('user.is_ses_hod'))
			<span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_follow_up.edit',$row->lead_pk_no) }}">
				<i class="fa fa-list"></i>
			</span>		
		@endif
		@if (!Session::get('user.is_team_leader') && !Session::get('user.is_ses_hod'))
		<span class="btn bg-info btn-xs lead-sold" data-title="Lead Sold" title="Lead Sold" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_sold',$row->lead_pk_no) }}">
			<i class="fa fa-handshake-o"></i>
		</span>
		@endif
	</td>
</tr>
