@if($type==1)
<td class="text-center"><input type="checkbox" name="lead_id[]" data-id="{{ $row->leadlifecycle_pk_no }}" id="lead_id" > </td>
@endif
<td>{{ $row->lead_id }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
@php 
$masking_number = substr($row->phone1,0,7);
@endphp
@if($ses_user_id == $row->created_by ||$ses_user_id == $row-> lead_sales_agent_pk_no)
<td>{{ $row->phone1 }}</td>
@else
<td>{{ $masking_number }}****</td>
@endif

<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>
<td class="text-center" style="font-weight: bold;">
	@if($row->lead_dist_type == 1)
	Manual
	@elseif($row->lead_dist_type == 2)
	Auto
	@else
	Pending
	@endif
</td>
