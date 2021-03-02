<div class="tab-pane table-responsive active" id="sold_lead">
	<table class="table table-bordered table-striped table-hover mb-0">
		<thead class="bg-blue">
			@include("admin.components.lead_list_table_header")
			
			<th class="text-center">Action</th>
		</thead>
		<tbody>
			@if(!empty($sold_lead))
			@foreach($sold_lead as $row)
			<tr>
				@include("admin.components.lead_list_table")
				
				<td>
					
					<span class="btn btn-xs bg-green lead-view" data-title="Followup" title="Followup" data-id="2" data-action="{{ route('lead_sold_view',$row->lead_pk_no) }}"> <i class="fa fa-check"></i>
					</span>
					<span class="btn btn-xs bg-blue lead-view" data-title="Collected Collection" title="Followup" data-id="3" data-action="{{ route('collected_collection_view',$row->lead_pk_no) }}"><i class="fa fa-bars"></i></span>
				</td>
			</tr>
			@endforeach
			@endif

		</tbody>
	</table>
</div>