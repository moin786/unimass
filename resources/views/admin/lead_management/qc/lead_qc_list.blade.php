<td>{{ $row->lead_id }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
<td>{{ $row->phone1 }}</td>
<td>{{ $row->project_category_name }}</td>
<td>{{ $row->project_area }}</td>
<td>{{ $row->project_name }}</td>
<td>{{ $row->project_size }}</td>
<td>
	@if($row->lead_qc_flag==1)
		Passed
	@endif

	@if($row->lead_qc_flag==2)
		Junk
	@endif
</td>
<td align="center">
<input type="checkbox" name="chk_qc_status[]" data-id="{{ $row->leadlifecycle_pk_no }}" 
data-name="{{ $row->lead_id }}" />
</td>
<td align="center">
<span class="btn bg-info btn-xs lead-view" title="Lead Details" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
</td>