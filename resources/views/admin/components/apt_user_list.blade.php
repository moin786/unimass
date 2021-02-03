<table id="datatable" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th style=" min-width: 10px" class="text-center">Team Member</th>
			<th style=" min-width: 70px" class="text-center">Lead To K1</th>
			<th style=" min-width: 80px" class="texot-center">k1 To priority</th>
			<th style=" min-width: 100px" class="text-center">priority To sold</th>
			<th style=" min-width: 100px" class="text-center">k1 To sold</th>
			<th class="text-center"></th>
		</tr>
	</thead>

	<tbody>
		@if(!empty($apt_data))
		@foreach($apt_data as $row)
		<tr>
			<td>{{ $row->user_name }}</td>
			<td class="text-center">{{ ($row->lead2k1==0 || $row->lead2k1 == '')?'':$row->lead2k1 }}</td>
			<td class="text-center">{{ ($row->k12priority==0 || $row->k12priority == '')?'':$row->k12priority }}</td>
			<td class="text-center">{{ ($row->priority2sold==0 || $row->priority2sold == '')?'':$row->priority2sold }}</td>
			<td class="text-center">{{ ($row->k12sold==0 || $row->k12sold == '')?'':$row->k12sold }}</td>
			<td class="text-center">
				<span title="View Chart" class="view-chart-apt" data-title="KPI :: APT" data-action="{{ route('performance_chart_data',['user_id'=>$row->user_pk_no,'type'=>'apt']) }}" style="cursor: pointer;"><i class="ion ion-stats-bars"></i></span>
			</td>
		</tr>
		@endforeach
		@else
		@endif
	</tbody>
</table>