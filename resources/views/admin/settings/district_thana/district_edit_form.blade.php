@php

$district = (isset($district) && (!empty($district))) ? $district : [];
$district_name = ($district) ? $district->district_name : '';
$id = ($district) ? $district->id : '';

@endphp

<form id="frmUser" action="{{route('district-update')}}"
method="post">
@csrf
<input type="hidden" id="hdnUserId" name="hdnUserId" value="{{$id}}"/>
<section class="content">
	<div class="form-row">
		<div class="col-md-9">
			
			<label>District Name</label>
			<input type="text" class="form-control" id="district_name" name="district_name" title="Attribute Name" placeholder="District Name" value="{{$district_name}}">
		</div>
		<div class="col-md-3">    
			<label for=""> </label> <br>					
			<button type="submit"
			class="btn bg-purple btn-md btnSaveUpdate" data-response-action="{{route('district_thana_setup')}}" >Save</button>
			<span class="msg"></span>
		</div> 

	</div>          


</section>

</form>
