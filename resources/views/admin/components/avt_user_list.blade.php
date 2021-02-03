@php
$user_type  = Session::get('user.user_type');
$label = ($user_type==1)?"Target(by Lead)":"Target(by Amount)";
@endphp
<table id="datatable" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>Team Member</th>
			<th style="width: 60px" class="text-center">YY-MM</th>
			<th style="width: 130px" class="text-center">{{ $label }}</th>
			@if($user_type  == 2)
			<th style="width: 100px" class="text-center">Sale Amount</th>
			@else
			<th style="width: 100px" class="text-center">Total Lead</th>
			@endif
			<th style="width: 100px" class="text-center">Achv. (%)</th>
			<th style="width: 10px" class="text-center"></th>
		</tr>
	</thead>

	<tbody>
		@if(!empty($avt_data))
		@foreach($avt_data as $row)
		@php
		$qnty = ($user_type==1)?$row->target_by_lead_qty:number_format($row->target_amount,2,'.','');
		if($user_type==1)
		{
			if(isset($cre_lead_count[$row->user_pk_no][$row->yy_mm]))
			{
				$lead_count = $cre_lead_count[$row->user_pk_no][$row->yy_mm];
				$achv = ($lead_count*100)/$qnty;
			}
			else
			{
				$lead_count = 0;
				$achv = 0;
			}
		}
		else
		{
			$achv = ($row->sold_amt*100)/$qnty;
		}

		@endphp
		@if(date("m-Y",strtotime($row->yy_mm)) == date("m-Y",strtotime($row->yy_mm)))
		<tr>
			<td>{{ $row->user_name }}</td>
			<td class="text-center">{{ date("M'Y",strtotime($row->yy_mm)) }}</td>
			<td class="text-right">{{ $qnty }}</td>
			@if($user_type  == 2)
			<td class="text-right">
				{{ number_format($row->sold_amt,2,'.','') }}
			</td>
			@else
			<td class="text-right">
				{{ $lead_count }}
			</td>
			@endif
			<td class="text-right">
				@php
				if($achv >= 100)
				{
					$caret_class = 'text-green';
					$caret = "up";
				}
				if($achv < 100)
				{
					$caret_class = 'text-red';
					$caret = "";
				}
				@endphp
				<span class="description-percentage {{ $caret_class }}">
					<i class="fa fa-caret-{{ $caret }}"></i> <strong>{{ number_format($achv,2,'.','') }}</strong>
				</span>
			</td>
			<td class="text-center">
				<span title="View Chart" class="view-chart-avt" data-title="KPI :: AVT" data-action="{{ route('performance_chart_data',['user_id'=>$row->user_pk_no,'type'=>'avt']) }}" style="cursor: pointer;"><i class="ion ion-stats-bars"></i></span>
			</td>
		</tr>
		@endif
		@endforeach
		@else
		@endif
	</tbody>
</table>