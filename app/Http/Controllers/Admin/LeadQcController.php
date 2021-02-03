<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use App\LeadLifeCycle;
use App\TeamUser;
use App\LeadLifeCycleView;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LeadQcController extends Controller
{
	public function index()
	{
		$is_team_leader = Session::get('user.is_team_leader');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$get_all_tem_members=$user_cond='';
		if($is_team_leader == 1)
		{
			$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
			$get_all_tem_members = ($get_all_team_member.",".$ses_user_id);
			$lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw WHERE created_by in($get_all_tem_members) and (lead_qc_flag>0 or lead_qc_flag is not null)");
		}
		else
		{
			$lead_data = [];
		}

		return view('admin.lead_management.qc.lead_qc', compact('lead_data','is_team_leader'));
	}

	public function lead_pass_junk(Request $request)
	{
		$lc_id     = $request->get('lead_pk_no');
		$lead_name = $request->get('lead_name');
		$qc_status = ($request->get('qc_status')=='pass')?1:2;
		$qc_date   = date("Y-m-d h:i:s");
		$user_id = Session::get('user.ses_user_pk_no');

		$qcdata = LeadLifeCycle::findOrFail($lc_id);
		$qcdata->lead_qc_flag = $qc_status;
		$qcdata->lead_qc_datetime = $qc_date;
		$qcdata->lead_qc_by = $user_id;

		$message = ($qc_status == 1)? "Lead($lead_name) QC completed successfully" : "Lead($lead_name) sent to junk successfully";
		if($qcdata->save())
		{
			return response()->json(['message'=>$message,'title'=>'Success',"positionClass" => "toast-top-right"]);
		}
		else
		{
			return response()->json(['message'=>'Lead QC Failed.','title'=>'Failed',"positionClass" => "toast-top-right"]);
		}
	}

	public function load_qc_leads(Request $request)
	{
		$is_team_leader = Session::get('user.is_team_leader');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$get_all_tem_members=$user_cond='';
		if($is_team_leader == 1)
		{
			$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
			$get_all_tem_members = ($get_all_team_member.",".$ses_user_id);

			$lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw WHERE created_by in($get_all_tem_members) and (lead_qc_flag>0 or lead_qc_flag is not null)");
		}
		else
		{
			$lead_data = [];
		}

		if($request->tab_type == 1){
			return view('admin.lead_management.qc.qc_work_list', compact('lead_data'));
		}
		if($request->tab_type == 2){
			return view('admin.lead_management.qc.lead_qc_passed', compact('lead_data'));
		}
		if($request->tab_type == 3){
			return view('admin.lead_management.qc.lead_junk', compact('lead_data'));
		}

	}

	public function lead_bypass(Request $request)
	{
		$user_info = Auth::user();
		$user_id     	= $request->get('user_id');
		$can_bypass 	= $request->get('bypass_value');
		$bypass_date   	= date("Y-m-d", strtotime($request->get('bypass_date')));

		$qcdata = TeamUser::findOrFail($user_id);
		$qcdata->is_bypass = $can_bypass;
		$qcdata->bypass_date = $bypass_date;

		if($qcdata->save())
		{
			session(['user.is_bypass' => $can_bypass]);
			session(['user.bypass_date' => $bypass_date]);
			return response()->json(['message'=>"Data updated successfully",'title'=>'Success',"positionClass" => "toast-top-right"]);
		}
		else
		{
			return response()->json(['message'=>'Data did not updated successfully','title'=>'Failed',"positionClass" => "toast-top-right"]);
		}
	}
}
