@php
$is_super_admin = Session::get('user.is_super_admin');
@endphp

<style type="text/css">
	.table thead tr td,
	.table thead tr th,
	.table tbody tr td,
	.table tbody tr th,
	.table tfoot tr td,
	.table tfoot tr th {
		vertical-align: middle !important;
	}
</style>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Search Result</h3>
		@if($is_super_admin == 1)
		<button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button>
		@endif
	</div>

	<div class="box-body">

		<table id="tbl_search_result" class="table table-bordered table-striped table-hover">
			<thead>
				<tr>
					<th class="text-center" rowspan="2">SL</th>
					<th class="text-center" rowspan="2">Cluster Head</th>
					<th class="text-center" colspan="7 ">Sales FeedBack A/B/C</th>
					<th class="text-center" rowspan="2">Grand Total</th>
				</tr>
				<tr>

					@foreach($look_data as $data)
					<th class="text-center">{{ $data->lookup_name  }}</th>
					@endforeach

					<th>Others</th>

				</tr>
			</thead>
			<tbody>
                @if(!empty($lead_lookup))
				@if(!empty($cluster_head))
				@foreach($cluster_head as $cluster)
				<tr>

					@php
					$sum = $other_sum = 0;
					@endphp
					<td class="text-center"> {{  $loop->iteration }} </td>
					<td class="text-left"> {{ $cluster->user_fullname  }} </td>
					@foreach($lead_lookup as $look=>$loopup_data)
					<td class="text-right">
						{{ isset($source_report_data[$cluster->user_pk_no][$loopup_data])? $source_report_data[$cluster->user_pk_no][$loopup_data] :0 }}
					</td>
					@php
					$sum += (isset($source_report_data[$cluster->user_pk_no][$loopup_data])? $source_report_data[$cluster->user_pk_no][$loopup_data] :0);
					@endphp
					@endforeach
					<td class="text-right">
						{{ isset($other_source_report_data[$cluster->user_pk_no]['others'])? $other_source_report_data[$cluster->user_pk_no]['others'] :0 }}
						@php
						$other_sum += (isset($other_source_report_data[$cluster->user_pk_no]['others'])? $other_source_report_data[$cluster->user_pk_no]['others'] :0);
						@endphp
					</td>
					@php
                                //$sum += $source_report_data[$cluster->user_pk_no][];
					@endphp
					<td class="text-right">{{ $sum+$other_sum }}</td>
				</tr>

				@endforeach
				@endif
                @endif

			</tbody>

		</table>
	</div>
</div>
