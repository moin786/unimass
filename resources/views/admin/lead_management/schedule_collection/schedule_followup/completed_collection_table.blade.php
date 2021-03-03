@if(!empty($completed_collection))
@foreach($completed_collection as $row)
<tr>
	<td class="text-center">{{ date("d/m/Y",strtotime($row->created_at)) }}</td>
	<td class="text-center">{{ $row->lead_id }}</td>
	<td class="text-center">{{ $row->collected_amount }}</td>
	<td class="text-center">{{ $row->check_no }}</td>
	<td class="text-left">{{ date("d/m/Y",strtotime($row->cheque_date)) }}</td>
	<td class="text-left">{{ date("d/m/Y",strtotime($row->received_date)) }}</td>
	<td class="text-left">{{ $row->mr_no }}</td>
	<td class="text-left">{{ $row->remarks }}</td>
</tr>
@endforeach
@else
<tr>
	<td colspan="8" class="text-center">No Data Found</td>
</tr>
@endif