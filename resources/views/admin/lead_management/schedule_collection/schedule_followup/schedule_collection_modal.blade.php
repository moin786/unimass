	<div class="tab-pane table-responsive " id="schedule_collection">
		<div class="box mb-0">
			<div class="box-body">
				<div class="form-row">
					<div class="col-md-12">
						<table class="table table-bordered mb-0">
							<thead class="bg-blue">
								<th class="text-center">SL</th>
								<th class="text-center">Date</th>
								<th class="text-center">Installment</th>
								<th class="text-center">Payment amount</th>
								<th class="text-center">Payment Percentage</th>
								<th class="text-center">Payment Status</th>
							</thead>
							<tbody>
								@if(!empty($schedule_list))
								@foreach($schedule_list as $row)
								<tr>
									<td class="text-center">{{ $loop->iteration }}</td>
									<td class="text-center">{{ date("d/m/Y",strtotime($row->schedule_date)) }}</td>
									<td class="text-center">{{ $row->installment }}</td>
									<td class="text-center">{{ $row->amount }}</td>
									<td class="text-center">{{ $row->percent_of_total_apt_price }}</td>
									<td class="text-left">{{ $row->payment_status }}</td>
									
								</tr>
								@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>