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
		$prefix = "SA";

		if($data[2] == 1 ){
			$prefix= "CH";
		}else if ($data[3] == 1 ) {
			$prefix = "BH";
		} else if ($data[4] == 1 ){
			$prefix = "TL";
		}
		@endphp
		<option value="{{ $data[0] }}">{{ $prefix }} - {{$data[1]}} </option>
		@endforeach
	</optgroup>
	@endforeach
	@endif
</select>