@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
$user_type = Session::get('user.user_type');
@endphp

<style>
	.form-row {
		display: flex;
		flex-wrap: wrap;
		margin-right: -5px;
		margin-left: -5px;
	}

	.form-row > .col,
	.form-row > [class*=col-] {
		padding-right: 5px;
		padding-left: 5px;
	}
</style>


<form id="frmLead" action="{{ !isset($lead_data)?route('lead.store') : route('lead.update',$lead_data->lead_pk_no) }}"
	method="{{ !isset($lead_data)?'post' : 'patch' }}">
	<div class="box box-success">
		<div class="box-header with-border ">
			<h3 class="box-title">Customer Information</h3>
			{{-- <a href="{{ route('import_csv') }}" class="btn bg-green btn-sm pull-right">Import CSV</a> --}}
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_lead_id">Lead ID </label>
						<input type="text" class="form-control" id="txt_lead_id" readonly="readonly" name="txt_lead_id"
						value="" title="" placeholder="USERGROPCODE+YYMM+99999"/>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="txt_lead_date">Date<span class="text-danger"> *</span></label>
						<input type="text" class="form-control keep_me required" id="txt_lead_date" name="txt_lead_date"
						value="<?php echo date('d-m-Y H:i:s'); ?>" title="" readonly="" placeholder="Entry Date"/>
					</div>
				</div>

				<div class="col-md-6">
					<label for="txt_cus_first_name">Client Name 1<span class="text-danger"> *</span></label>
					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control required capitalize-text"
								id="customer_first_name" name="customer_first_name" value="" title=""
								placeholder="Client First Name"/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control required capitalize-text" id="customer_last_name"
								name="customer_last_name" value="" title="" placeholder="Client Last Name"/>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<label for="txt_cus_first_name">Client Name 2</label>
					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control capitalize-text" id="customer_first_name"
								name="customer_first_name2" value="" title="" placeholder="Client First Name"/>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<input type="text" class="form-control capitalize-text" id="customer_last_name"
								name="customer_last_name2" value="" title="" placeholder="Client Last Name"/>
							</div>
						</div>
					</div>
				</div>


				<div class="col-md-12">

					<div class="form-row">
						<div class="col-md-6">
							<div class="form-group">
								<div><label for="customer_phone1">Phone Number 1 <span
									class="text-danger"> *</span></label></div>
									<div class="col-xs-4" style="padding-left: 0;">
										<select class="form-control select2" name="country_code1" aria-hidden="true">
											<option selected="selected" value="0">Country Code</option>
											@if(!empty($countries))
											@foreach ($countries as $country)
											<option
											value="{{ $country->phonecode }}" {{ ($country->iso=='BD')? 'selected':'' }} >
											{{ $country->name ." (". $country->phonecode.")" }}
										</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="col-xs-8">
									<input type="text" class="form-control number-only required check_phone_no"
									id="customer_phone1" name="customer_phone1" maxlength="10"
									placeholder="Phone Number 1"/>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div><label for="customer_phone2">Phone Number 2</label></div>
								<div class="col-xs-4" style="padding-left: 0;">
									<select class="form-control select2" name="country_code2" aria-hidden="true">
										<option selected="selected" value="0">Country Code</option>
										@if(!empty($countries))
										@foreach ($countries as $key => $country)
										<option
										value="{{ $country->phonecode }}" {{ ($country->iso=='BD')? 'selected':'' }}>
										{{ $country->name ." (". $country->phonecode.")" }}
									</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="col-xs-8">
								<input type="text" class="form-control number-only check_phone_no"
								id="customer_phone2" name="customer_phone2" maxlength="10"
								placeholder="Phone Number 2"/>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label for="txt_cus_email">Customer Email<span class="text-danger"> *</span> </label>
					<input type="email" class="form-control required email-only" id="customer_email"
					name="customer_email" value="" title="Customer Email"
					placeholder="e.g. username@ruc.com"/>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label>Occupation </label>
					<select class="form-control select2" name="cmb_ocupation" style="width: 100%;"
					aria-hidden="true">
					<option selected="selected" value="0">Select Occupation</option>
					@if(!empty($ocupations))
					@foreach ($ocupations as $key => $ocupation)
					<option value="{{ $key }}">{{ $ocupation }}</option>
					@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Organization </label>
				<input type="text" class="form-control" name="organization">
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				<label>Designation </label>
				<input type="text" class="form-control" name="designation">
			</div>
		</div>


	</div>
</div>
<!-- /.box-body -->


<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Address</h3>
	</div>
	<div class="box-body">
		<div class="form-row">
			<div class="col-md-2">
				<div class="form-group">
					<label for="txt_present_housing_no">Present Address</label>
					<input type="text" class="form-control" id="txt_present_housing_no"
					name="txt_present_housing_no" value="" title="" placeholder="Housing/Plot No"/>
				</div>
			</div>


			<div class="col-md-2">
				<div class="form-group">
					<label for="txt_present_road_no"></label>
					<input type="text" class="form-control" id="txt_present_road_no" name="txt_present_road_no"
					value="" title="" placeholder="Road No"/>
				</div>
			</div>


			<div class="col-md-2">
				<div class="form-group">
					<label for="txt_present_area"> </label>
					<select class="form-control" id="txt_present_area" name="txt_present_area"
					title="Select Area">
					<option value="0">Select Area</option>
					@if(!empty($area))
					@foreach ($area as $key => $value)
					<option value="{{ $key }}">{{ $value }}</option>
					@endforeach
					@endif
				</select>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label for="txt_present_district"> </label>
				<select class="form-control select2" id="txt_present_district" name="txt_present_district"
				placeholder="Organization">
				<option value="0">Select District</option>
				@if(!empty($district))
				@foreach ($district as $value)
				<option value="{{ $value->id }}">{{ $value->district_name }}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_present_thana"> </label>
			<select class="form-control select2" id="txt_present_thana" name="txt_present_thana" title="" >
				<option value="0">Select Thana</option>
				@if(!empty($thana))
				@foreach ($thana as $value)
				<option value="{{ $value->id }}">{{ $value->thana_name }}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_size_no"></label>
			<input type="text" class="form-control" id="txt_size_no" name="txt_size_no" value=""
			title="" placeholder="Current Apartment Size"/>
		</div>
	</div>
</div>

<div class="form-row">
	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_parmanent_house_no">Permanent Address</label>
			<input type="text" class="form-control" id="txt_parmanent_house_no"
			name="txt_parmanent_house_no" value="" title="" placeholder="Housing/Plot No"/>
		</div>
	</div>


	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_parmanent_road_address"> </label>
			<input type="text" class="form-control" id="txt_parmanent_road_address"
			name="txt_parmanent_road_address" value="" title="" placeholder="Road No"/>
		</div>
	</div>


	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_parmanent_area"> </label>
			<select class="form-control" id="txt_parmanent_area" name="txt_parmanent_area" title="">
				<option value="0">Select Area</option>
				@if(!empty($area))
				@foreach ($area as $key => $value)
				<option value="{{ $key }}">{{ $value }}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label for="txt_parmanent_district"> </label>
			<select class="form-control select2" id="txt_parmanent_district" name="txt_parmanent_district"
			title="District">
			<option value="0">Select District</option>
			@if(!empty($district))
			@foreach ($district as $value)
			<option value="{{ $value->id }}">{{ $value->district_name }}</option>
			@endforeach
			@endif
		</select>
	</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		<label for="txt_parmanent_thana"> </label>
		<select class="form-control select2" id="txt_parmanent_thana" name="txt_parmanent_thana"
		title="Thana">
		<option value="0">Select Thana</option>
		@if(!empty($thana))
		@foreach ($thana as $value)
		<option value="{{ $value->id }}">{{ $value->thana_name }}</option>
		@endforeach
		@endif
	</select>
</div>
</div>
</div>

<div class="form-row">
	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_organization">Office Address </label>
			<input type="text" class="form-control" id="txt_organization_housing_no"
			name="txt_organization_housing_no" value="" title="" placeholder="Housing/Plot No"/>
		</div>
	</div>


	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_organization_road_no"> </label>
			<input type="text" class="form-control" id="txt_organization_road_no"
			name="txt_organization_road_no" value="" title="" placeholder="Road No"/>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			<label for="txt_organization_area"> </label>
			<select class="form-control" id="txt_organization_area" name="txt_organization_area"
			title="" placeholder="Organization">
			<option value="0">Select Area</option>
			@if(!empty($area))
			@foreach ($area as $key => $value)
			<option value="{{ $key }}">{{ $value }}</option>
			@endforeach
			@endif
		</select>
	</div>
</div>

<div class="col-md-3">
	<div class="form-group">
		<label for="txt_organization_district"> </label>
		<select class="form-control select2" id="txt_organization_district" name="txt_organization_district"
		title="">
		<option value="0">Select District</option>
		@if(!empty($district))
		@foreach ($district as $value)
		<option value="{{ $value->id }}">{{ $value->district_name }}</option>
		@endforeach
		@endif
	</select>
</div>
</div>
<div class="col-md-3">
	<div class="form-group">
		<label for="txt_organization_thana"> </label>
		<select class="form-control select2" id="txt_organization_thana" name="txt_organization_thana"
		title="">
		<option value="0">Select Thana</option>
		@if(!empty($thana))
		@foreach ($thana as $value)
		<option value="{{ $value->id }}">{{ $value->thana_name }}</option>
		@endforeach
		@endif
	</select>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Project Detail</h3>
	</div>
	<div class="box-body">
		<div class="form-row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Category<span class="text-danger"> *</span></label>
					<select class="form-control required" id="cmb_category" name="cmb_category"
					data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
					<option selected="selected" value="0">Select Category</option>
					@if(!empty($project_cat))
					@foreach ($project_cat as $key => $cat)
					<option value="{{ $key }}">{{ $cat }}</option>
					@endforeach
					@endif
				</select>
			</div>
		</div>

		<div class="col-md-3">
			<div class="form-group">
				<label>Area<span class="text-danger"> *</span></label>
				<select class="form-control required" id="cmb_area" name="cmb_area" style="width: 100%;"
				aria-hidden="true">
				<option selected="selected" value="">Select Area</option>
			</select>
		</div>
	</div>

	<div class="col-md-3">
		<div class="form-group">
			<label>Project Name<span class="text-danger"> *</span></label>
			<select class="form-control required" id="cmb_project_name" name="cmb_project_name"
			style="width: 100%;" aria-hidden="true">
			<option selected="selected" value="">Select Project Name</option>
		</select>
	</div>
</div>

<div class="col-md-3">
	<div class="form-group">
		<label>Size<span class="text-danger"> *</span></label>
		<select class="form-control select2 required" id="cmb_size" name="cmb_size" style="width: 100%;"
		aria-hidden="true">
		<option selected="selected" value="">Select Size</option>
		@if(!empty($project_size))
		@foreach ($project_size as $key => $size)
		<option value="{{ $key }}">{{ $size }}</option>
		@endforeach
		@endif
	</select>
</div>
</div>
</div>
</div>
</div>

<div class="box" style="border-color:#ff851b;">
	<div class="box-header with-border">
		<h3 class="box-title">Creator Information (Auto)</h3>
	</div>
	<div class="box-body">
		<div class="form-row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_source_title">User Group</label>
					<input type="text" class="form-control keep_me" id="txt_source_title" name="txt_source_title"
					value="{{ Session::get('user.ses_role_name') }}" title="Source Title"
					readonly="readonly" placeholder="Source Title"/>
					<input class="keep_me" type="hidden" name="hdn_source_role"
					value="{{ Session::get('user.ses_role_lookup_pk_no') }}" readonly="readonly"/>
				</div>
			</div>

			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_source_name">Creator Name</label>
					<input type="text" class="form-control keep_me" id="txt_source_name" name="txt_source_name"
					value="{{ Session::get('user.ses_full_name') }}" title="Source Name"
					readonly="readonly" placeholder="Source Name"/>
					<input class="keep_me" type="hidden" name="hdn_source_id"
					value="{{ Session::get('user.ses_user_pk_no') }}" readonly="readonly"/>
				</div>
			</div>


			<input type="hidden" name="txt_cluster_head" class="keep_me" value="0">
			@if($group_id == 73)
			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_source_name">Sub Creator Name</label>
					<input type="text" class="form-control" id="sub_source_name" name="sub_source_name"
					value="" title="Source Name" placeholder="Sub Source Name"/>
				</div>
			</div>
			@endif
		</div>
	</div>
</div>

<!-- <div class="box" style="border-color:#ff851b;">
	<div class="box-header with-border">
		<h3 class="box-title">Source</h3>
	</div>
	<div class="box-body">
		@if(!empty($lead_source))
		@foreach ($lead_source as $key=>$value)
		@if($user_type == 2 && ($key == 2 || $key == 3))
		<div class="col-md-3">
			<div class="form-group">
				<label style="cursor:pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
					style="position: relative; margin-right:10px; margin-bottom:6px;">
					<input type="radio" id="Source" value="{{ $key }}"
					name="Source" class="flat-red"
					style="position: absolute; opacity: 0;">
					<ins class="iCheck-helper"
					style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
				</div>

				<span style="font-size:14px; margin-top:-5px;">
					&nbsp;{{ $value }}
				</span>
			</label>
		</div>
	</div>
	@elseif($user_type == 1 && ($key == 1 || $key == 2))
	<div class="col-md-3">
		<div class="form-group">
			<label style="cursor:pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
				style="position: relative; margin-right:10px; margin-bottom:6px;">
					<input type="radio" id="Source" value="{{ $key }}"
				name="Source" class="flat-red"
				style="position: absolute; opacity: 0;">
					<ins class="iCheck-helper"
				style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
				</div>

				<span style="font-size:14px; margin-top:-5px;">
				&nbsp;{{ $value }}
				</span>
			</label>
		</div>
	</div>
@endif
@endforeach
@endif
</div>
</div> -->


<div class="box" style="border-color:#ff851b;">
	<div class="box-header with-border">
		<label class="" style="cursor: pointer;">

		</label>
		<h3 class="box-title">Source</h3>
	</div>
	<div class="box-body">
		@if(!empty($digital_mkt))
		@foreach ($digital_mkt as $key=>$digi)
		<div class="col-md-3">
			<div class="form-group">
				<label style="cursor:pointer;">
					<div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
					style="position: relative; margin-right:10px; margin-bottom:6px;">
					<input type="radio" id="Sub_Source" value="{{ $key }}" name="Sub_Source[]"
					class="flat-red" style="position: absolute; opacity: 0;" required>
					<ins class="iCheck-helper"
					style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
				</div>

				<span style="font-size:14px; margin-top:-5px;">
					&nbsp;{{ $digi }}
				</span>
			</label>
		</div>
	</div>
	@endforeach
	@endif
</div>
</div>


<div class="box" style="border-color:navy;">
	<div class="box-header with-border">
		<h3 class="box-title">Meeting Information</h3>
	</div>
	<div class="box-body">
		<div class="form-row">
			<div class="col-md-4">
				<div class="form-group">
					<label for="txt_meeting_status">Meeting Status</label>
					<select class="form-control" id="txt_meeting_status" name="txt_meeting_status"
					title="Meeting Status">
					<option selected="selected" value="">Select One</option>
					@if(!empty($lead_status))
					@foreach($lead_status as $key=>$status)
					<option value="{{$key}}"> {{ $status }} </option>
					@endforeach
					@endif
				</select>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label for="txt_meeting_date">Date :</label>
				<input type="text" class="form-control datepicker" id="txt_meeting_date" name="txt_meeting_date"
				value="" title="Source Name" placeholder="dd-mm-yyyy"/>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="txt_meeting_time">Prefered Time :</label>
				<div class="input-group">
					<div class="input-group-addon">
						<i class="fa fa-clock-o"></i>
					</div>
					<input type="text" class="form-control timepicker pull-right" id="txt_meeting_time"
					name="txt_meeting_time" placeholder="">
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<div class="box" style="border-color:#444444;">
	<div class="box-header with-border  text-center">

		<label class="" style="cursor: pointer;">
			<input type="checkbox" id="chkKyc" name="chkKyc"/> More Details (Dossier/KYC)
		</label>
	</div>

	<div id="more_details" class="box-body hidden">
		<div class="form-row" id="appendPlace">
			<div class="col-md-12">
				<div class="form-row">
					<div class="col-md-3">

						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="cust_dob">Client1 DOB</label>
									<input type="text" class="form-control datepicker" id="txt_cust_dob"
									name="txt_cust_dob" title="" readonly="readonly"
									placeholder="dd-mm-yyyy"/>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="txt_cust_dob2">Client2 DOB</label>
									<input type="text" class="form-control datepicker" id="txt_cust_dob2"
									name="txt_cust_dob2" title="" readonly="readonly"
									placeholder="dd-mm-yyyy"/>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="wife_name">Spouse Name</label>
							<input type="text" class="form-control" id="txt_wife_name" name="txt_wife_name" value=""
							title="Source Title" placeholder="Spouse Name"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="wife_dob">Spouse DOB</label>
							<input type="text" class="form-control datepicker" id="txt_wife_dob" name="txt_wife_dob"
							title="" readonly="readonly" placeholder="dd-mm-yyyy"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="marriage_anniversary">Marriage Anniversary</label>
							<input type="text" class="form-control datepicker" id="txt_marriage_anniversary"
							name="txt_marriage_anniversary" title="" readonly="readonly"
							placeholder="dd-mm-yyyy"/>
						</div>
					</div>

					<br clear="all"/><br/>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_name_1">1st Children Name</label>
							<input type="text" class="form-control" id="txt_child_name_1" name="txt_child_name_1"
							value="" title="Source Title" placeholder="First Children Name"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_dob_1">1st Children DOB</label>
							<input type="text" class="form-control datepicker" id="txt_child_dob_1"
							name="txt_child_dob_1" title="" readonly="readonly" placeholder="dd-mm-yyyy"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_name_2">2nd Children Name</label>

							<input type="text" class="form-control" id="txt_child_name_2" name="txt_child_name_2"
							value="" title="Source Title" placeholder="Second Children Name"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_dob_2">2nd Children DOB</label>
							<input type="text" class="form-control datepicker" id="txt_child_dob_2"
							name="txt_child_dob_2" title="" readonly="readonly" placeholder="dd-mm-yyyy"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_name_3">3rd Children Name</label>
							<input type="text" class="form-control" id="txt_child_name_3" name="txt_child_name_3"
							value="" title="Source Title" placeholder="Third Children Name"/>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-group">
							<label for="txt_child_dob_3">3rd Children DOB</label>
							<input type="text" class="form-control datepicker" id="txt_child_dob_3"
							name="txt_child_dob_3" title="" readonly="readonly" placeholder="dd-mm-yyyy"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="child_education">Child Education</label>
							<input type="text" id="child_education" name="child_education" class="form-control "
							placeholder="Child Education"/>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="food_habit">Food Habit</label>
							<input type="text" class="form-control " name="food_habit" id="food_habit"
							placeholder="Food Habit"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="political_opinion">Political Opinion</label>
							<input type="text" class="form-control" name="political_opinion" id="political_opinion"
							placeholder="Political Opinion"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="car_pre">Car Preference</label>
							<input type="text" name="car_pre" id="car_pre" class="form-control "
							placeholder="Car Preference"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="color_preference">Color Preference</label>
							<input type="text" class="form-control" id="color_preference" name="color_preference"
							placeholder="Color Preference"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="hobby">Hobby</label>
							<input type="text" class="form-control" id="hobby" name="hobby"
							placeholder="Color Preference"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="traveling_history">Traveling History</label>
							<input type="text" id="traveling_history" name="traveling_history" class="form-control "
							placeholder="Traveling History"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="memberofclub">Member of Club</label>
							<input type="text" id="memberofclub" name="memberofclub" class="form-control "
							placeholder="Member of Club"/>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="disease_name">Disease Name</label>
							<input type="text" class="form-control" name="disease_name" id="disease_name"
							placeholder="Disease"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-row">
	<div class="col-md-12">
		<label for="remarks">Remarks</label>
		<div class="form-group">
			<textarea class="form-control" style="height: 100px !important;" id="remarks" name="remarks"
			placeholder="Enter Remarks"></textarea>
		</div>
	</div>
</div>
<br/>
<div class="form-row mt-50">
	<div class="col-md-12">
		<div class="col-md-12 pb-15">
			<button type="submit" class="btn bg-green btn-sm btnSaveUpdate" >Save</button>
			<a href="#" class="btn bg-red btn-sm">Cancel</a>
		</div>
	</div>
</div>
</div>
</form>
