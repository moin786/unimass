<?php

namespace App\Http\Controllers\Admin;

use App\TeamUser;
use App\LeadLifeCycleView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadTransferController extends Controller
{
	public function index()
	{
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		$lead_data = LeadLifeCycleView::all();
		$sales_agent = TeamUser::where("user_type",2)->get();
		$lead_transfer_list = DB::select("SELECT a.*,b.* FROM t_leadfollowup a,t_lead2lifecycle_vw b WHERE a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1");
		return view('admin.sales_team_management.lead_transfer.lead_transfer', compact('lead_data','sales_agent','lead_transfer_list','lead_stage_arr'));
	}

	public function lead_create_transfer(Request $request)
	{

		$lc_id     		= $request->get('lead_pk_no');
		$lead_name 		= $request->get('lead_name');
		$cmbTransferTo 	= $request->get('cmbTransferTo');
		$create_date   	= date("Y-m-d");

		$lead_transfer 	= DB::statement(
			DB::raw("CALL proc_leadtransfer_ins ('1','$create_date',$lc_id,1,$cmbTransferTo,0,1,1,'$create_date')")
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
		$lead_transfer_list = DB::select("SELECT a.*,b.* FROM t_leadfollowup a,t_lead2lifecycle_vw b WHERE a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1");
		$sales_agent = TeamUser::where("user_type",2)->get();
		$lead_data = LeadLifeCycleView::all();
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		if($request->tab_type == 1){
			return view('admin.sales_team_management.lead_transfer.lead_transfer_list', compact('lead_data','lead_stage_arr','sales_agent','lead_transfer_list'));
		}
		if($request->tab_type == 2){
			return view('admin.sales_team_management.lead_transfer.lead_transfer_request', compact('lead_data','lead_stage_arr','sales_agent','lead_transfer_list'));
		}
		if($request->tab_type == 3){
			return view('admin.sales_team_management.lead_transfer.lead_transferred', compact('lead_data','lead_stage_arr','sales_agent','lead_transfer_list'));
		}
	}
}
