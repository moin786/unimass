<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\TeamUser;
use App\LookupData;
use App\Lead;
use App\LeadLifeCycle;
use App\LeadLifeCycleView;
use App\LeadTransfer;
use App\TransferHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadTransferController extends Controller
{
	public function index()
	{
		$user_id        = Session::get('user.ses_user_pk_no');
		$lead_stage_arr = config('static_arrays.lead_stage_arr');

		$lookup_arr = [4,7];
		$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status",1)->get();
		foreach ($lookup_data as $value) {
			$key = $value->lookup_pk_no;
			if ($value->lookup_type == 4)
				$project_cat[$key] = $value->lookup_name;

			if ($value->lookup_type == 7)
				$project_area[$key] = $value->lookup_name;
		}

		$sales_agent = DB::table('s_user')
		->select('s_user.user_pk_no','s_user.user_fullname','s_lookdata.lookup_pk_no','s_lookdata.lookup_name')
		->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
		->Join('s_lookdata', 't_teambuild.category_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
		->where('s_user.user_type', 2)
		->where('s_user.row_status', 1)
		->where('s_user.row_status', 1)
		->where('t_teambuild.hod_flag', 0)
		->where('t_teambuild.hot_flag', 0)
		->where('t_teambuild.team_lead_flag', 0)
		->get();

		/*$lead_transfer_list = DB::select("SELECT a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date,b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
			FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
			WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
			AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1
			UNION ALL
			SELECT a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date,b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
			FROM t_leadfollowup a,t_lead2lifecycle_vw b
			WHERE a.lead_pk_no=b.lead_pk_no
			AND (b.lead_sales_agent_pk_no=$user_id or b.created_by=$user_id) AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL)
			UNION ALL
			SELECT '' AS lead_followup_datetime,'' AS followup_Note,'' AS Next_FollowUp_date,b.customer_firstname,b.customer_lastname,
			b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,
			b.lead_sales_agent_pk_no
			FROM t_lead2lifecycle_vw b WHERE b.lead_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1
			AND b.lead_sales_agent_pk_no!=lead_transfer_from_sales_agent_pk_no");*/

			$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
				FROM t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(7)
				UNION ALL
				SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
				FROM t_lead2lifecycle_vw b
				WHERE (b.lead_sales_agent_pk_no=$user_id or b.created_by=$user_id) AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL) and b.lead_current_stage not in(7)");

			$transfer_lead_arr = [];
			foreach ($lead_transfer_list as $transfer) {
				$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
			}

			$followup_arr = [];
			if(!empty($transfer_lead_arr))
			{
				$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(".implode(",", $transfer_lead_arr).")");
				foreach ($lead_followup as $followup) {
					$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
					$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
					$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
				}
			}

			return view('admin.sales_team_management.lead_transfer.lead_transfer', compact('sales_agent','lead_transfer_list','lead_stage_arr','project_cat','project_area','followup_arr'));
		}

		public function lead_create_transfer(Request $request)
		{
			$lead_category  	= $request->get('category');
			$cmb_area     		= $request->get('cmb_area');
			$cmb_project_name   = $request->get('cmb_project_name');
			$cmb_size     		= $request->get('cmb_size');
			$agent_category     = $request->get('cmb_category');

			//if($lead_category != $agent_category)
			//{
			$ldata = Lead::findOrFail($request->lead_pk_no);
			$ldata->project_category_pk_no 	= $agent_category;
			$ldata->project_area_pk_no 		= $cmb_area;
			$ldata->Project_pk_no 			= $cmb_project_name;
			$ldata->project_size_pk_no 		= $cmb_size;

			if ($ldata->save()) {
				/*$lcdata = LeadLifeCycle::findOrFail($request->lead_pk_no);
				$lcdata->lead_sales_agent_pk_no 	= $request->get('cmbTransferTo');
				$lcdata->lead_sales_agent_assign_dt = date("Y-m-d");
				$lcdata->save();*/

				$ltdata = new TransferHistory();
				$ltdata->lead_pk_no 						= $request->lead_pk_no;
				$ltdata->project_category_pk_no 			= $lead_category;
				$ltdata->project_area_pk_no 				= $cmb_area;
				$ltdata->Project_pk_no 						= $cmb_project_name;
				$ltdata->project_size_pk_no 				= $cmb_size;
				$ltdata->transfer_from_sales_agent_pk_no 	= $request->get('agent');
				$ltdata->transfer_to_sales_agent_pk_no 		= $request->get('cmbTransferTo');
				$ltdata->save();
			}
		//}

			$user_id        = Session::get('user.ses_user_pk_no');
			$lc_id     		= $request->get('lead_pk_no');
			$lead_name 		= $request->get('lead_name');
			$cmbTransferTo 	= $request->get('cmbTransferTo');
			$create_date   	= date("Y-m-d");

			$lead_transfer 	= DB::statement(
				DB::raw("CALL proc_leadtransfer_ins ('1','$create_date',$lc_id,$user_id,$cmbTransferTo,0,1,1,$user_id,'$create_date')")
			);

			return response()->json(['message'=>'Lead transfered successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
		}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function load_transfer_leads(Request $request)
	{
		$lookup_arr = [4,7];
		$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
		foreach ($lookup_data as $value) {
			$key = $value->lookup_pk_no;
			if ($value->lookup_type == 4)
				$project_cat[$key] = $value->lookup_name;

			if ($value->lookup_type == 7)
				$project_area[$key] = $value->lookup_name;
		}

		$user_id        = Session::get('user.ses_user_pk_no');
		$sales_agent = DB::table('s_user')
		->select('s_user.user_pk_no','s_user.user_fullname','s_lookdata.lookup_pk_no','s_lookdata.lookup_name')
		->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
		->Join('s_lookdata', 't_teambuild.category_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
		->where('s_user.user_type', 2)
		->where('s_user.row_status', 1)
		->where('t_teambuild.hod_flag', 0)
		->where('t_teambuild.hot_flag', 0)
		->where('t_teambuild.team_lead_flag', 0)
		->get();
		$lead_stage_arr = config('static_arrays.lead_stage_arr');

		if($request->tab_type == 1){
			$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
				FROM t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(7)
				UNION ALL
				SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no
				FROM t_lead2lifecycle_vw b
				WHERE (b.lead_sales_agent_pk_no=$user_id or b.created_by=$user_id) AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL) and b.lead_current_stage not in(7)");

			$transfer_lead_arr = [];
			foreach ($lead_transfer_list as $transfer) {
				$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
			}

			$followup_arr = [];
			if(!empty($transfer_lead_arr))
			{
				$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(".implode(",", $transfer_lead_arr).")");
				foreach ($lead_followup as $followup) {
					$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
					$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
					$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
				}
			}
			return view('admin.sales_team_management.lead_transfer.lead_transfer_list', compact('lead_stage_arr','sales_agent','lead_transfer_list','project_cat','project_area','followup_arr'));
		}
		if($request->tab_type == 2){
			$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,
				b.lead_sales_agent_name,b.lead_current_stage,b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,c.transfer_pk_no,c.transfer_to_sales_agent_pk_no
				FROM t_leadtransfer c,t_lead2lifecycle_vw b
				WHERE c.lead_pk_no=b.lead_pk_no AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=0 and b.lead_current_stage not in(7) group by b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,c.transfer_pk_no,c.transfer_to_sales_agent_pk_no");
			$transfer_lead_arr = [];
			foreach ($lead_transfer_list as $transfer) {
				$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
			}

			$followup_arr = [];
			if(!empty($transfer_lead_arr))
			{
				$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(".implode(",", $transfer_lead_arr).")");
				foreach ($lead_followup as $followup) {
					$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
					$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
					$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
				}
			}
			return view('admin.sales_team_management.lead_transfer.lead_transfer_request', compact('lead_stage_arr','sales_agent','lead_transfer_list','followup_arr'));
		}
		if($request->tab_type == 3){
			$lead_transfer_list = DB::select("SELECT a.*,b.*
				FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_from_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(7)");
			return view('admin.sales_team_management.lead_transfer.lead_transferred', compact('lead_stage_arr','sales_agent','lead_transfer_list'));
		}
	}

	public function accept_transfer(Request $request)
	{
		$user_id        = Session::get('user.ses_user_pk_no');
		$transfer_id    = $request->get('transfer_id');
		$to_agent    	= $request->get('to_agent');

		$lead_transfer  = LeadTransfer::findOrFail($transfer_id);
		$lead_transfer->transfer_to_sales_agent_flag  = 1;

		if ($lead_transfer->save()) {
			$llcdata  = LeadLifeCycle::findOrFail($request->get('lead_id'));
			$llcdata->lead_sales_agent_pk_no  		= $to_agent;
			$llcdata->lead_sales_agent_assign_dt  	= date('Y-m-d');
			$llcdata->save();
			return response()->json(['message'=>'Lead Request Accepted successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
		}
	}

}
