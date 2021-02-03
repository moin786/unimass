<div class="tab-pane table-responsive" id="qc_passed">
	<table id="work_list" class="table table-bordered table-striped table-hover">
		<thead>
			<tr>
				<th class="text-center">Lead ID</th>
				<th class="text-center">Customer</th>
				<th class="text-center">Mobile</th>
				<th class="text-center">Category</th>
				<th class="text-center">Area</th>
				<th class="text-center">Project</th>
				<th class="text-center">Size</th>
				<th class="text-center">Status</th>
				<th class="text-center">Junk / Pass</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_data))
			@foreach($lead_data as $row)
			@if($row->lead_qc_flag == 1)
			<tr>
				@include('admin.lead_management.qc.lead_qc_list')
			</tr>
			@endif
			@endforeach
			@else

			@endif
		</tbody>
		<tfoot>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center">
				<button type="submit" class="btn bg-red btn-xs lead-qc-status" data-target="#qc_passed" data-type="2" id="junk">Junk</button>
			</td>
			<td></td>
		</tfoot>
	</table>
</div>