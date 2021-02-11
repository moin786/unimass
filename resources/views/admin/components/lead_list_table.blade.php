@php
$lead_stage_arr = config('static_arrays.lead_stage_arr');
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
$ses_user_role = Session::get('user.ses_role_lookup_pk_no');
$ses_user_id   = Session::get('user.ses_user_pk_no');

$phone_nos = $row->phone1_code."".$row->phone1."".(($row->phone2!="")?",".$row->phone2_code."".$row->phone2:"");

$phns = $phone_nos.",".$row->all_phone_no;
$all_phone_no = array_unique(explode(",", rtrim($phns,", ")));

$masking_number = $all_phone_nos = "";
foreach ($all_phone_no as $phn_row) {
	$masking_number .= "".substr($phn_row,0,10) . "****, ";
	$all_phone_nos .= $phn_row . ",";
}
$masking_number = rtrim($masking_number, ", ");
$all_phone_nos = rtrim($all_phone_nos, ", ");
@endphp

<td>{{ $row->lead_id }}</td>
<td>{{ date("d/m/Y",strtotime($row->created_at)) }}</td>
<td>{{ $row->customer_firstname . " " . $row->customer_lastname }}</td>
@if($ses_user_id == $row->created_by ||$ses_user_id == $row->lead_sales_agent_pk_no || $ses_user_role == 1 || $ses_user_role == 551)
<td>@php echo $all_phone_nos  @endphp</td>
@else
<td>@php echo $masking_number  @endphp</td>
@endif
<td>
	{{-- <div title="Lead Category">
		<strong>C : </strong>
		{{ $row->project_category_name }}
	</div> --}}
	<div title="Project name"><strong>P : </strong>{{ $row->project_name }}</div>
	<div title="Project Size"><strong>S : </strong>{{ $row->project_size }}</div>
</td>
<td>{{ $row->user_full_name }}</td>
<td>{{ $row->lead_sales_agent_name }}</td>
<td>{{ $lead_stage_arr[$row->lead_current_stage] }}</td>
