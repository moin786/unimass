<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\TeamUser;
use App\LeadLifeCycleView;
use App\LeadTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadTransferController extends Controller
{
	public function index()
	{
		$user_id        = Session::get('user.ses_user_pk_no');
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		$lead_data = LeadLifeCycleView::all();
		$sales_agent = TeamUser::where("user_type",2)->get();
		$lead_transfer_list = DB::select("SELECT a.*,b.*
			FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
			WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
			AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1
			UNION ALL
			SELECT a.*,b.*
			FROM t_leadfollowup a,t_lead2lifecycle_vw b
			WHERE a.lead_pk_no=b.lead_pk_no
			AND b.lead_sales_agent_pk_no=$user_id AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL)");

		return view('admin.sales_team_management.lead_transfer.lead_transfer', compact('lead_data','sales_agent','lead_transfer_list','lead_stage_arr'));
	}

	public function lead_create_transfer(Request $request)
	{
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
		$user_id        = Session::get('user.ses_user_pk_no');
		$sales_agent 	= TeamUser::where("user_type",2)->get();
		$lead_stage_arr = config('static_arrays.lead_stage_arr');

		if($request->tab_type == 1){
			$lead_transfer_list = DB::select("SELECT a.*,b.*
				FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1
				UNION ALL
				SELECT a.*,b.*
				FROM t_leadfollowup a,t_lead2lifecycle_vw b
				WHERE a.lead_pk_no=b.lead_pk_no
				AND b.lead_sales_agent_pk_no=$user_id AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL)");
			return view('admin.sales_team_management.lead_transfer.lead_transfer_list', compact('lead_stage_arr','sales_agent','lead_transfer_list'));
		}
		if($request->tab_type == 2){
			$lead_transfer_list = DB::select("SELECT a.*,b.*,c.transfer_pk_no
				FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=0");
			return view('admin.sales_team_management.lead_transfer.lead_transfer_request', compact('lead_stage_arr','sales_agent','lead_transfer_list'));
		}
		if($request->tab_type == 3){
			$lead_transfer_list = DB::select("SELECT a.*,b.*
				FROM t_leadfollowup a,t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE a.lead_pk_no=b.lead_pk_no AND b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_from_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=1");
			return view('admin.sales_team_management.lead_transfer.lead_transferred', compact('lead_stage_arr','sales_agent','lead_transfer_list'));
		}
	}

	public function accept_transfer(Request $request)
	{
		$user_id        = Session::get('user.ses_user_pk_no');
		$transfer_id    = $request->get('transfer_id');

		$lead_transfer  = LeadTransfer::findOrFail($transfer_id);
		$lead_transfer->transfer_to_sales_agent_flag  = 1;

		if ($lead_transfer->save()) {
			return response()->json(['message'=>'Lead Request Accepted successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
		}
	}

}
