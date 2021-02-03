<td>{{ $row->lead_id }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
<td>{{ $row->phone1 }}</td>
<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>

<td class="text-center">
    <select id="cmbSalesAgent{{ $row->leadlifecycle_pk_no }}" class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
        <option value="">Select Cluster Head</option>
        @if(!empty($cluster_head))
        @foreach ($cluster_head as  $value)
        <option value="{{ $value->user_pk_no }}">{{ $value->user_fullname }}</option>
        @endforeach
        @endif  
    </select>
</td>
<td class="text-center" style="font-weight: bold;">
    @if($row->lead_dist_type == 1)
    Manual
    @elseif($row->lead_dist_type == 2)
    Auto
    @else
    Pending
    @endif
</td>