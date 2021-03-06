<select class="form-control required select2" id="cmbTransferTo" name="cmbTransferTo" style="width: 100%;" aria-hidden="true">
	<option value="">Select</option>
	@if(!empty($sales_agent_info))
	@foreach($sales_agent_info as $key=> $value)
	@php
	$team_name = $key;
	@endphp
	<optgroup label="{{ $key }}">
		@foreach($value as $name=>$val)

		@php
		$data = explode("_",$val);
		$prefix = "SP";

		if($data[2] == 1 ){
			$prefix= "HOD";
		}else if ($data[3] == 1 ) {
			$prefix = "BH";
		} else if ($data[4] == 1 ){
			$prefix = "TL";
		}
		@endphp
		<?php
		  if($data[0] != session()->get('user.ses_user_pk_no') && $data[2] != 1) {
		?>
		<option value="{{ $data[0] }}">{{ $prefix }} - {{$data[1]}} </option>
		<?php } ?>
		@endphp
		@endforeach
	</optgroup>
	@endforeach
	@endif
</select>