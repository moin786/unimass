@extends('admin.layouts.app')


@push('css_lib')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet"
href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
<style>

</style>
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">

@endpush
@section('content')
<section class="content-header">
	<h1>Policy Setup <small>AUTO considering pre-set policies</small></h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('lead.index') }}">Settings</a></li>
		<li class="active">Policy Setup</li>
	</ol>
</section>

<!-- Main content -->
<section class="content">
<form id="frmUser1"action="{{ !isset($no_action)?route('settings.store') : route('settings.update',$no_action->lookup_pk_no) }}" method="{{ !isset($no_action)?'post' : 'patch' }}"> 
	@csrf
	<div class="box box-success " style="margin-bottom:0px">
		<input type="hidden" name="cmbLookupType" id="cmbLookupType" value="23">
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<label for="txt_lead_id">No Action taken by Sales Agent</label>
					<div style="font-weight: normal;"><small>If any sales person does not do the conversion of the Lead towards next stage or not following up the Lead, then the Lead will be reassigned/transfer to other sales agent of that category through TL/BH/CL.</small></div>
					<hr />
				</div>
				
				<span style="float:left; margin-left: 15px;">Days</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="Number" class="form-control" id="txtLookupName" name="txtLookupName" title="" placeholder="e.g. 45 Days"   value="{{ (!empty($no_action))? $no_action->lookup_name :'' }}" required />
					</div>
					<input type="hidden" name="cmbLookupStatus" id="cmbLookupStatus" value="1">
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<button type="button" class="btn btn-xs btn-success btnSaveUpdate"> Save </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<br />
<form id="frmUser2" action="{{ !isset($max_number)?route('settings.store') : route('settings.update',$max_number->lookup_pk_no) }}" method="{{ !isset($max_number)?'post' : 'patch' }}">
	@csrf
	<div class="box box-success" style="margin-bottom:0px">
		<input type="hidden" name="cmbLookupType" id="cmbLookupType" value="24">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<label for="txt_lead_id">Maximum number of Follow up in each Stage </label>
					<div style="font-weight: normal;"><small>Sales Agent can followup a Lead up to this setup without converting the Lead to next stage. After this period th Lead will be available in TL/BH/CL account.</small></div>
					<hr />
				</div>
				<span style="float:left; margin-left: 15px;">Days</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="Number" class="form-control" id="txtLookupName" name="txtLookupName" placeholder="e.g. 15 Days" value="{{ (!empty($max_number->lookup_pk_no))?  $max_number->lookup_name :'' }}" required/>
					</div>
				</div>
				<input type="hidden" name="cmbLookupStatus" id="cmbLookupStatus" value="1">
				<div class="col-md-3">
					<div class="form-group">
						<button type="button" class="btn btn-xs btn-success btnSaveUpdate"> Save </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<br />
<form id="frmUser3" action="{{ !isset($return_to_call_center)?route('settings.store') : route('settings.update',$return_to_call_center->lookup_pk_no) }}" method="{{ !isset($return_to_call_center)?'post' : 'patch' }}">
	@csrf
	<div class="box box-success" style="margin-bottom:0px">
		<input type="hidden" name="cmbLookupType" id="cmbLookupType" value="25">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<label for="txt_lead_id">Maximum Duration for Lead Auto Return to Digital & Call Center</label>
					<div style="font-weight: normal;"><small>This is applicable for Call Center Data. If no action is taken for 48 hours, the Lead will be back to Digital & Call Center</small></div>
					<hr />
				</div>
				<span style="float:left; margin-left: 15px;">Hours</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="Number" class="form-control" id="txtLookupName" name="txtLookupName" placeholder="e.g. 48 Hours" value="{{ (!empty($return_to_call_center->lookup_pk_no))?  $return_to_call_center->lookup_name :'' }}" required/>
					</div>
				</div>
				<input type="hidden" name="cmbLookupStatus" id="cmbLookupStatus" value="1">
				<div class="col-md-3">
					<div class="form-group">
						<div class="row">
							<button type="button" class="btn btn-xs btn-success btnSaveUpdate"> Save </button>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</div>
</form>
<br/>
<form id="frmUser5" action="{{ !isset($schedule_penalty)?route('settings.store') : route('settings.update',$schedule_penalty->lookup_pk_no) }}" method="{{ !isset($schedule_penalty)?'post' : 'patch' }}">
	@csrf
	<div class="box box-success" style="margin-bottom:0px">
		<input type="hidden" name="cmbLookupType" id="cmbLookupType" value="31">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<label for="txt_lead_id">Maximum percent of late schedule payment</label>
					<div style="font-weight: normal;"><small>This is applicable for CSD panel.</small></div>
					<hr />
				</div>
				<span style="float:left; margin-left: 15px;">%</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="Number" class="form-control" id="txtLookupName" name="txtLookupName" placeholder="e.g. 10%" value="{{ (!empty($schedule_penalty->lookup_pk_no))?  $schedule_penalty->lookup_name :'' }}" required/>
					</div>
				</div>
				<input type="hidden" name="cmbLookupStatus" id="cmbLookupStatus" value="1">
				<div class="col-md-3">
					<div class="form-group">
						<div class="row">
							<button type="button" class="btn btn-xs btn-success btnSaveUpdate"> Save </button>
						</div>
					</div>
				</div>
			</div>
		</div>

		
	</div>
</form>


<form id="frmUser4" action="{{ !isset($username)?route('validation.store') : route('validation.update') }}" method="{{ !isset($username)?'post' : 'post' }}">
	@csrf
	<div class="box box-success" style="margin-bottom:0px">
		<input type="hidden" name="cmbLookupid1" id="cmbLookupid1" value="27">
		<input type="hidden" name="cmbLookupid2" id="cmbLookupid2" value="28">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="form-row">
				<div class="col-md-12">
					<label for="txt_lead_id">SMS GateWay Setup</label>
					<div style="font-weight: normal;"><small>Set the Usename and Password for your SMS Gateway</small></div>
					<hr />
				</div>
				<span style="float:left; margin-left: 15px;">User Name</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="text" class="form-control" id="txtLookupName" name="txtLookupName" placeholder="e.g. Rupayan City" value="{{ (!empty($username->lookup_pk_no))?  $username->lookup_name :'' }}" required/>
					</div>
				</div>
				<span style="float:left; margin-left: 15px;">Password</span>
				<div class="col-md-2">
					<div class="form-group">
						<input type="Password" class="form-control" id="txtLookupPassword" name="txtLookupPassword" placeholder="***********" value="{{ (!empty($password->lookup_pk_no))?  $password->lookup_name :'' }}" required/>
					</div>
				</div>
				<input type="hidden" name="cmbLookupStatus" id="cmbLookupStatus" value="1">
				<div class="col-md-3">
					<div class="form-group">
						<div class="row">
							<button type="button" class="btn btn-xs btn-success btnSaveUpdate"> Save </button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

</section>
@endsection

@push('js_lib')
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script
src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
@endpush
