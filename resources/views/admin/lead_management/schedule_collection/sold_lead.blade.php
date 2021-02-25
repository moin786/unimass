<div class="tab-pane table-responsive active" id="sold_lead">
	<table class="table table-bordered table-striped table-hover mb-0">
		<thead class="bg-blue">
			<th class="text-center">Lead ID</th>
			<th class="text-center">Create Date</th>
			<th class="text-left">Customer</th>
			<th class="text-center">Mobile</th>
			<th class="text-left">Project Details</th>
			<th class="text-left">Created By</th>
			<th class="text-left">Assign to</th>
			<th class="text-center">Stage</th>
			<th class="text-center">Contact</th>
			<th class="text-center">Action</th>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>1</td>
				<td>
					<button class="btn btn-xs bg-aqua"><i class="fa fa-eye"></i></button>
					<span class="btn btn-xs bg-green lead-view" data-title="Followup" title="Followup" data-id="2" data-action="{{ route('lead_sold_view') }}"> <i class="fa fa-check"></i>
					</span>
					<span class="btn btn-xs bg-blue lead-view" data-title="Collected Collection" title="Followup" data-id="3" data-action="{{ route('collected_collection_view') }}"><i class="fa fa-bars"></i></span>
				</td>
			</tr>

		</tbody>
	</table>
</div>