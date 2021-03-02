<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\schedule_followup;
use Session;

class ScheduleFollowupController extends Controller
{
    //
	public function store(Request $request)
	{
		$create_date = date('Y-m-d');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$txt_followup_date = date("Y-m-d", strtotime($request->txt_followup_date));
		$txt_followup_date_time = date("Y-m-d H:i:s", strtotime($request->txt_followup_date . " " . $request->txt_followup_date_time));
		$meeting_followup_date = date("Y-m-d", strtotime($request->meeting_followup_date));
		$meeting_followup_date_time = date("Y-m-d H:i:s", strtotime($request->meeting_followup_date . " " . $request->meeting_followup_date_time));


		$follow_up_type = empty($request->cmbFollowupType) ? '0' : $request->cmbFollowupType;
		$meeting_status = !empty($request->txt_meeting_status) ? $request->txt_meeting_status : "0";

		$in_visit_meeting_done = !empty($request->meeting_visit_confirmation) ? $request->meeting_visit_confirmation : "0";
		$schedule_followup 	 =  new schedule_followup();
		$schedule_followup->lead_pk_no = $request->lead_pk_no;
		$schedule_followup->lead_id = $request->lead_id;
		$schedule_followup->created_by = $ses_user_id;
		$schedule_followup->followup_date = $txt_followup_date;
		$schedule_followup->followup_time = $txt_followup_date_time;
		$schedule_followup->next_followup_date = $meeting_followup_date;
		$schedule_followup->next_followup_time = $meeting_followup_date_time;
		$schedule_followup->followup_note = $request->followup_note;
		$schedule_followup->meeting_note = $request->next_followup_note;
		$schedule_followup->is_meeting_done = $in_visit_meeting_done;
		$schedule_followup->save();

		return response()->json(['message' => 'Schedule Followup created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
	}
}
