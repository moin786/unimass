<td>{{ $row->lead_id }}</td>
<td>{{ date("d/m/Y",strtotime($row->created_at)) }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
@if($tab==0)
@php 
$masking_number = substr($row->phone1,0,7);
@endphp
<td>{{ $masking_number }}****</td>

@else
<td>{{ $row->phone1 }}</td>

@endif
<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>
<td class="text-center">{{ $row->lead_sales_agent_name }}</td>

@if($tab==0)
<td>{{ date("d/m/Y",strtotime($row->lead_sales_agent_assign_dt)) }}</td>
@endif
<td class="text-center" style="font-weight: bold;">
	@if($row->lead_dist_type == 1)
	Manual
	@elseif($row->lead_dist_type == 2)
	Auto
	@else
	Pending
	@endif
</td>