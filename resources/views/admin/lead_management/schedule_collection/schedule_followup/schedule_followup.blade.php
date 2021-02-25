<div class="tab-pane active" id="schedule_followup">
	<div class="box mb-0">
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-4 col-md-offset-4">
					<div class="form-group">
						<label>Schedule List<span class="text-danger"></span></label>
						<select class="form-control" id="cmb_project_name" name="cmb_project_name">
							<option value="">Schedule List-1</option>
							<option value="">Schedule List-2</option>
							<option value="">Schedule List-3</option>
							<option value="">Schedule List-4</option>
							<option value="">Schedule List-5</option>
							<option value="">Schedule List-6</option>
						</select>
					</div>
				</div>
				
				<div class="col-md-12">
					<table class="table table-bordered mb-0">
						<thead class="bg-blue">
							<th class="text-center">Date</th>
							<th class="text-center">Followup Note</th>
							<th class="text-center">Next Followup</th>
							<th class="text-center">Visit/Meeting</th>
							<th class="text-left">Visit Note</th>
							<th class="text-left">Followup By</th>
						</thead>
						<tbody>
							<tr>
								<td class="text-center">24-02-2021</td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-left"></td>
								<td class="text-left"></td>
							</tr>
							<tr>
								<td class="text-center">24-02-2021</td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-left"></td>
								<td class="text-left"></td>
							</tr>
							<tr>
								<td class="text-center">24-02-2021</td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-center"></td>
								<td class="text-left"></td>
								<td class="text-left"></td>
							</tr>



						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="form-row mt-10">
		<div class="col-md-12">
			<div class="box mb-0">
				<div class="box-header with-border ">
					<h3 class="box-title">Lead Information</h3>
				</div>
				<div class="box-body">
					<div class="form-row">
						<div class="col-md-8">
							<table class="">
								<tbody>
									<tr>
										<th>Lead ID:</th>
										<td>L2021266</td>
										<th>Lead Date: </th>
										<td>16/02/2021</td>
									</tr>
									<tr>
										<th>Client 1 :</th>
										<td>Md. Saiful Islam</td>
										<th>Client 2 : </th>
										<td></td>
									</tr>
									<tr>
										<th>Mobile 1: </th>
										<td>8801743300335</td>
										<th>Mobile 2:</th>
										<td>880</td>
									</tr>
									<tr>
										<th>Email: </th>
										<td>ayaanenterprise04@gmail.com</td>
										<th>Size:</th>
										<td>1500-2000</td>
									</tr>
									<tr>
										<th>Project: </th>
										<td>Dale Adenia</td>
										<th>Area:</th>
										<td>Dilu Road</td>
									</tr>
									<tr>
										<th>Sales Agent: </th>
										<td>Mr. S.M. Jauhan Uddin</td>
										<th>Created by:</th>
										<td>Mr. S M Shamim Rahman</td>
									</tr>
									<tr>
										<th>Lead Current Stage: </th>
										<td>Lead</td>
									</tr>
								</tbody>
							</table>

							<div class="form-row">
								<div class="col-md-12">
									<div class="form-group">
										<label>Change Stage :</label>
										<select class="form-control" id="cmb_change_stage" name="cmb_change_stage">
											<option selected="selected" value="0">Please Select Stage</option>
											<option value="1" selected="">Lead</option>
											<option value="3">Cool</option>
											<option value="4">Warm</option>
											<option value="9">Junk</option>
											<option value="13">Hot</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-4">
							<div class="form-group hidden" id="flat_list_data">
								<label>Flat Size :</label>
								<select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true" name="flat_id">
									<option selected="selected" value="0">Select Flat Size</option>
									<option value="58">B-3</option>
								</select>
							</div>

							<div class="form-group">
								<label for="followup_note">Followup Note :</label>
								<textarea class="form-control" style="height: auto !important;" rows="3" id="followup_note" name="followup_note" title="Note" placeholder="Write Followup Note here"></textarea>
							</div>

							<div class="form-group">
								<label>Next Followup Date :</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right required" id="txt_followup_date" name="txt_followup_date">
								</div>
							</div>

							<div class="form-group">
								<label>Prefered Time :</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
									<input type="text" class="form-control pull-right" id="txt_followup_date_time" name="txt_followup_date_time">
								</div>
							</div>

							<div class="form-group">
								<label>Visit Date :</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" class="form-control pull-right " id="meeting_followup_date" name="meeting_followup_date">
								</div>
							</div>

							<div class="form-group">
								<label>Visit Prefered Time :</label>
								<div class="input-group">
									<div class="input-group-addon">
										<i class="fa fa-clock-o"></i>
									</div>
									<input type="text" class="form-control pull-right" id="meeting_followup_date_time" name="meeting_followup_date_time">
								</div>
							</div>

							<div class="form-group">
								<label for="next_followup_note">Visit Note :</label>
								<textarea class="form-control" rows="3" style="height: auto !important;" id="next_followup_note" name="next_followup_note" title="Note" placeholder="Write visit note here"></textarea>
							</div>

							<div class="form-group">
								<input type="checkbox" id="meeting_visit_confirmation" name="meeting_visit_confirmation" value="1" onclick="visit_done()">
								<label for="meeting_visit_confirmation">Visit Done?</label>
								<div id="meeting_visit_done_dt"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>