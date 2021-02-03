<tr>
	<td>{{ $row->lead_id }}</td>
	<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
	<td>{{ $row->phone1 }}</td>
	<td>{{ $row->project_name }}</td>
	<td>{{ ($row->lead_sales_agent_name=='' && $row->lead_current_stage!='')?$row->agent_name:$row->lead_sales_agent_name }}</td>
	<td>{{ $lead_stage_arr[$row->lead_current_stage] }}</td>
	<td>{{ ($row->Next_FollowUp_date!="")?date('d-m-Y', strtotime($row->Next_FollowUp_date)):"" }}</td>
	<td>{{ $row->next_followup_Note }}</td>
	<td width="150" align="center">
		<span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup"
		data-id="{{ $row->lead_pk_no }}"
		data-action="{{ route('lead_follow_up.edit',$row->lead_pk_no) }}">
		<i class="fa fa-list"></i>
	</span>
	<span class="btn bg-info btn-xs update_modal" data-title="Lead Stage Update" title="Lead Stage Update"
	data-id="{{ $row->lead_pk_no }}"
	data-action="{{ route('stage_update',$row->lead_pk_no) }}">
	<i class="fa fa-retweet"></i>
</span>
<span class="btn bg-info btn-xs lead-sold" data-title="Lead Sold" title="Lead Sold"
data-id="{{ $row->lead_pk_no }}"
data-action="{{ route('lead_sold',$row->lead_pk_no) }}">
<i class="fa fa-handshake-o"></i>
</span>
<span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details"
data-id="{{ $row->lead_pk_no }}"
data-action="{{ route('lead_view',$row->lead_pk_no) }}">
<i class="fa fa-eye"></i>
</span>
</td>
</tr>