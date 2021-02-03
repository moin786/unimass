<table id="datatable" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th style=" min-width: 10px" class="text-center">Team Member</th>
			<th style=" min-width: 70px" class="text-center">K1 </th>
			<th style=" min-width: 70px" class="text-center">Priority</th>
			<th style=" min-width: 80px" class="texot-center">Sold</th>
			<th style=" min-width: 100px" class="text-center">K1 To Priority Ratio</th>
			<th style=" min-width: 100px" class="text-center">Priority To Sold Ratio</th>
			<th class="text-center"></th>
		</tr>
	</thead>

	<tbody>
		@if(!empty($acr_data))
		@foreach($acr_data as $row)
		<tr>
			<td>{{ $row->user_name }}</td>
			<td class="text-center">{{ ($row->k1_count==0 || $row->k1_count == '')?'':$row->k1_count }}</td>
			<td class="text-center">{{ ($row->priority_count==0 || $row->priority_count == '')?'':$row->priority_count }}</td>
			<td class="text-center">{{ ($row->sold_count==0 || $row->sold_count == '')?'':$row->sold_count }}</td>
			<td class="text-center">{{ ($row->k1_priority_ratio==0 || $row->k1_priority_ratio == '')?'':$row->k1_priority_ratio }}</td>
			<td class="text-center">{{ ($row->priority_sold_ratio==0 || $row->priority_sold_ratio == '')?'':$row->priority_sold_ratio }}</td>
			<td class="text-center">
				<span title="View Chart" class="view-chart-acr" data-title="KPI :: ACR" data-action="{{ route('performance_chart_data',['user_id'=>$row->user_pk_no,'type'=>'acr']) }}" style="cursor: pointer;"><i class="ion ion-stats-bars"></i></span>
			</td>
		</tr>
		@endforeach
		@else
		@endif
	</tbody>
</table>