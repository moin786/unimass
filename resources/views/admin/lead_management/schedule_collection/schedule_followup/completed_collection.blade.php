<div class="tab-pane table-responsive " id="compeleted_collection">
	<div class="box mb-0">
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-4 col-md-offset-4">
					<div class="form-group">
						<label>Schedule List<span class="text-danger"></span></label>
						<select class="form-control" id="cmb_project_name" name="cmb_project_name" onchange="getCompleteCollection(this.value)">
							<option value="0">Select One</option>
							@if(!empty($schedule_complete_list))
							@foreach($schedule_complete_list as $complete_schedule)
							<option value="{{ $complete_schedule->id }}" {{ ($complete_schedule->id== $schedule_id)?"selected": " " }}>{{ $complete_schedule->installment }}</option>
							@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="col-md-12">
					<table class="table table-bordered mb-0">
						<thead class="bg-blue">
							<th class="text-center">Collection Date</th>
							<th class="text-center">Lead Id</th>
							<th class="text-center">Collected Amount</th>
							<th class="text-center">Check_no</th>
							<th class="text-left">Cheque Date</th>
							<th class="text-left">Received Date</th>
							<th class="text-left">Mr No</th>
							<th class="text-left">Remarks</th>
						</thead>
						<tbody id="complete_collection_table">
							@include("admin.lead_management.schedule_collection.schedule_followup.completed_collection_table")
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>