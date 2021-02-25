<div class="form-row">
	<div class="col-md-6">
		<div class="box">
			<div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Schedule Collection</h3>
			</div>
			<div class="box-body">
				<div class="form-group">
					<label>Schedule List</label>
					<select class="form-control" id="cmb_project_name" name="cmb_project_name">
						<option value="">1st Installment</option>
						<option value="">2nd Installment</option>
						<option value="">3rd Installment</option>
						<option value="">4th Installment</option>
						<option value="">5th Installment</option>
						<option value="">6th Installment</option>
					</select>
				</div>

				<div class="form-group">
					<label  for="installment_amount">Installment Amount</label>
					<input type="text" class="form-control" id="installment_amount" name="" value="" placeholder="0.00">
				</div>


				<div class="form-group">
					<label  for="collected_amount">Collected Amount</label>
					<input type="text" class="form-control" id="collected_amount" name="" value="" placeholder="0.00">
				</div>

				<div class="text-right">
					<button class="btn btn-xs bg-green">Save</button>
					<button class="btn btn-xs bg-red">Close</button>
				</div>
			</div>
			<div class="col-md-6">

			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Schedule List</h3>
			</div>
			<div class="box-body">
				<ul class="todo-list">
					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">1st Installment</span>
							{{-- <small class="label label-default pull-right">0</small> --}}
						</a>
					</li>

					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">2nd Installment</span>
							{{-- <small class="label label-default pull-right">0</small> --}}
						</a>
					</li>

					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">3rd Installment</span>
							{{-- <small class="label label-default pull-right">0</small> --}}
						</a>
					</li>


					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">4th Installment</span>
							{{-- <small class="label label-default pull-right">0</small> --}}
						</a>
					</li>

					<li>
						<a href="#" class="routeSetUp">
							<span class="handle">
								<i class="fa fa-ellipsis-v"></i>
								<i class="fa fa-ellipsis-v"></i>
							</span>
							<span class="text">5th Installment</span>
							{{-- <small class="label label-default pull-right">0</small> --}}
						</a>
					</li>



				</ul>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header">
				<i class="ion ion-clipboard"></i>
				<h3 class="box-title">Collected Collection</h3>
			</div>
			<div class="box-body">
				<div class="form-row">
					<div class="col-md-4 col-md-offset-4">
						<div class="form-group">
							<label>Schedule List</label>
							<select class="form-control" id="cmb_project_name" name="cmb_project_name">
								<option value="">1st Installment</option>
								<option value="">2nd Installment</option>
								<option value="">3rd Installment</option>
								<option value="">4th Installment</option>
								<option value="">5th Installment</option>
								<option value="">6th Installment</option>
							</select>
						</div>
					</div>
					<div class="col-md-12">
						<table class="table table-bordered mb-0">
							<thead class="bg-blue">
								<th class="text-left">Installment</th>
								<th class="text-right">Installment Amount</th>
								<th class="text-right">Collected Amount</th>
								<th class="text-right">Due</th>
							</thead>
							<tbody>
								<tr>
									<td class="text-left">1st Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>
								<tr>
									<td class="text-left">2nd Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>
								<tr>
									<td class="text-left">3rd Installment</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
									<td class="text-right">0.00</td>
								</tr>


							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>