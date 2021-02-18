@php

$thana = (isset($thana) && (!empty($thana))) ? $thana : [];
$thana_name = ($thana) ? $thana->thana_name : '';
$district_id = ($thana) ? $thana->district_id : '';
$id = ($thana) ? $thana->id : '';

@endphp
<section class="content">


	<form id="newForm" action="{{route('thana-update')}}"
	method="POST">
	@csrf
	<input type="hidden" id="hdnUserId" name="id" value="{{$id}}"/>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label>District Name <span class="text-danger">*</span></label>
				<select name="district_id" id="district " class="form-control" aria-hidden="true">
					<option value="0">Select</option>
					@if(!empty($district))
					@foreach ($district as $name)

					<option value="{{$name->id}}" {{$district_id== $name->id ? 'selected' : ''  }}>{{$name->district_name}}</option>

					@endforeach
					@endif
				</select>
			</div>
		</div> 
	</div>
	<div class="row">
		<div class="col-md-12" id="thana_name">
			<div class="form-group" >
				<label>Thana Name <span class="text-danger">*</span></label>
				<input type="text" class="form-control" id="Thana_name" name="Thana" title="Thana Name" placeholder="Thana Name" value="{{$thana_name}}">
			</div>
		</div> 
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
		<button type="submit"
		class="btn bg-purple btn-sm btnSaveUpdate" data-response-action="{{route('district_thana_setup')}}" >Save</button>
		<span class="msg"></span>
	</div>          

</form>
</section>

