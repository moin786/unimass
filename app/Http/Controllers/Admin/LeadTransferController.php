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
use App\TeamAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadTransferController extends Controller
{
	public function index()
	{
		$user_id = Session::get('user.ses_user_pk_no');
		$is_ch = Session::get('user.is_ses_hod');
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		$userRoleId = Session::get('user.ses_role_lookup_pk_no');

		$lookup_arr = [4, 7];
		$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status", 1)->get();
		foreach ($lookup_data as $value) {
			$key = $value->lookup_pk_no;
			if ($value->lookup_type == 4)
				$project_cat[$key] = $value->lookup_name;

			if ($value->lookup_type == 7)
				$project_area[$key] = $value->lookup_name;
		}

		$ses_user_id = Session::get('user.ses_user_pk_no');

		$get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild");

		$get_all_teams = "";
		if (!empty($get_team_info)) {
			foreach ($get_team_info as $team) {
				$get_all_teams .= $team->team_ids.",";
			}
		}
		$get_all_teams = rtrim($get_all_teams, ", ");
		if(!empty($get_all_teams)){
			$sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`, 
				`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag` 
				FROM `t_teambuild` 
				INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no` 
				INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no` 
				WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams) 
                AND `t_teambuild`.`agent_type` = 2");//AND t_teambuild.hod_flag != 1 
		}else{
			$sales_agent_arr =[];
		}

		$sales_agent_info = []; 
		if (!empty($sales_agent_arr)) {
			foreach ($sales_agent_arr as $value) {
				$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
			}
		}

		$numberofdays = LookupData::where('lookup_type', 23)->orderBy('lookup_pk_no', 'desc')->first();

		$days = $numberofdays->lookup_name;
		$is_team_leader = Session::get('user.is_team_leader');
		$is_ses_hod = Session::get('user.is_ses_hod');
		$is_ses_hot = Session::get('user.is_ses_hot');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$get_all_tem_members = "";
		if ($is_ses_hod > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id )")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
		} else if ($is_ses_hot > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1)")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
		} else if ($is_team_leader > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
		} else {
			$get_all_tem_members .= $ses_user_id;
		}

		$get_all_team_members = rtrim(($get_all_tem_members),", ");

		if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
			$lead_transfer_list = DB::select("select * from (SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name
				FROM t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no in (".$get_all_team_members.") AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9) 
				UNION ALL
				SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name
				FROM t_lead2lifecycle_vw b
				WHERE (b.lead_cluster_head_pk_no in (" .$get_all_team_members. ") or b.lead_sales_agent_pk_no in (" .$get_all_team_members .")) AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL) and b.lead_current_stage not in(6,7,9)) x  order by x.created_at desc,x.lead_pk_no desc");

		} else {
			$lead_transfer_list = DB::select("select * from (SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name
				FROM t_lead2lifecycle_vw b,t_leadtransfer c
				WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
				AND c.transfer_to_sales_agent_pk_no=$get_all_team_members AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9)
				UNION ALL
				SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name
				FROM t_lead2lifecycle_vw b
				WHERE b.lead_sales_agent_pk_no=$get_all_team_members AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL) and b.lead_current_stage not in(6,7,9)) x  order by x.created_at desc,x.lead_pk_no desc");
		}


		$transfer_lead_arr = [];
		foreach ($lead_transfer_list as $transfer) {
			$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
		}

		$followup_arr = [];
		if (!empty($transfer_lead_arr)) {
			$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(" . implode(",", $transfer_lead_arr) . ")");
			foreach ($lead_followup as $followup) {
				$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
				$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
				$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
			}
		}
		$cluster_head_list = [];
		if ($is_ch == 1) {

			$cluster_head_list = DB::table('s_user')
			->select('s_user.user_pk_no', 's_user.user_fullname', 's_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
			->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
			->Join('s_lookdata', 't_teambuild.category_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
			->where('s_user.user_type', 2)
			->where('s_user.row_status', 1)
			->where('s_user.row_status', 1)
			->where('t_teambuild.hod_flag', 1)
			->get();

		}
		return view('admin.sales_team_management.lead_transfer.lead_transfer', compact('sales_agent_info', 'lead_transfer_list', 'lead_stage_arr', 'project_cat', 'project_area', 'followup_arr', 'is_ch', 'cluster_head_list', 'days', 'ses_user_id', 'userRoleId','is_ses_hot','is_team_leader'));
	}

	public function lead_create_transfer(Request $request)
	{
		$user_id = Session::get('user.ses_user_pk_no');
		$team_info = TeamAssign::where('user_pk_no', $user_id)->select('hod_user_pk_no')->first();
		$ldata = Lead::findOrFail($request->lead_pk_no);
		$lead_category = $request->get('category');
        $cmb_area = (!empty($request->get('cmb_area')) ? $request->get('cmb_area') : $ldata->project_area_pk_no); //$request->get('cmb_area')
        $cmb_project_name = (!empty($request->get('cmb_project_name')) ? $request->get('cmb_project_name') : $ldata->Project_pk_no);     //$request->get('cmb_project_name')
        $cmb_size = (!empty($request->get('cmb_size')) ? $request->get('cmb_size') : $ldata->project_size_pk_no); // $request->get('cmb_size')
        $agent_category = (!empty($request->get('cmb_category')) ? $request->get('cmb_category') : $ldata->project_category_pk_no); // $request->get('cmb_category')

        //if($lead_category != $agent_category)
        //{

        $ldata->project_category_pk_no = $agent_category;
        $ldata->project_area_pk_no = $cmb_area;
        $ldata->Project_pk_no = $cmb_project_name;
        $ldata->project_size_pk_no = $cmb_size;


        if ($ldata->save()) {
            /*$lcdata = LeadLifeCycle::findOrFail($request->lead_pk_no);
            $lcdata->lead_sales_agent_pk_no 	= $request->get('cmbTransferTo');
            $lcdata->lead_sales_agent_assign_dt = date("Y-m-d");
            $lcdata->save();*/

            $ltdata = new TransferHistory();
            $ltdata->lead_pk_no = $request->lead_pk_no;
            $ltdata->project_category_pk_no = $lead_category;
            $ltdata->project_area_pk_no = $cmb_area;
            $ltdata->Project_pk_no = $cmb_project_name;
            $ltdata->project_size_pk_no = $cmb_size;
            $ltdata->transfer_from_sales_agent_pk_no = $request->get('agent');
            $ltdata->transfer_to_sales_agent_pk_no = $request->get('cmbTransferTo');
            $ltdata->ch_user_no_pk = $team_info->hod_user_pk_no;
            $ltdata->save();
        }
        //}

        //$user_id        = Session::get('user.ses_user_pk_no');
        $lc_id = $request->get('lead_pk_no');
        $lead_name = $request->get('lead_name');
        $cmbTransferTo = $request->get('cmbTransferTo');
        $create_date = date("Y-m-d");

        if (Session::get('user.is_ses_hod') == 1) {
        	$approve_flag = 1;
            $llcdata = LeadLifeCycle::where("lead_pk_no", $request->get('lead_pk_no'))->first();
            //$llcdata->lead_cluster_head_pk_no = 0;
            $llcdata->lead_sales_agent_pk_no = $request->get('cmbTransferTo');
            $llcdata->lead_sales_agent_assign_dt = date('Y-m-d');
            $llcdata->save();    
        } else {
        	$approve_flag = 0;
        }

        $lead_transfer = DB::statement(
        	DB::raw("CALL proc_leadtransfer_ins ('1','$create_date',$lc_id,$user_id,$cmbTransferTo,$approve_flag,1,$team_info->hod_user_pk_no,1,$user_id,'$create_date')")
        );

        return response()->json(['message' => 'Lead transfered successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_transfer_leads(Request $request)
    {
    	$is_team_leader = Session::get('user.is_team_leader');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ch = Session::get('user.is_ses_hod');
    	$userRoleId = Session::get('user.ses_role_lookup_pk_no');
    	$lookup_arr = [4, 7];
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
    	foreach ($lookup_data as $value) {
    		$key = $value->lookup_pk_no;
    		if ($value->lookup_type == 4)
    			$project_cat[$key] = $value->lookup_name;

    		if ($value->lookup_type == 7)
    			$project_area[$key] = $value->lookup_name;
    	}

    	$user_id = Session::get('user.ses_user_pk_no');


    	$get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild");

    	$get_all_teams = "";
    	if (!empty($get_team_info)) {
    		foreach ($get_team_info as $team) {
    			$get_all_teams .= $team->team_ids.",";
    		}
    	}
    	$get_all_teams = rtrim($get_all_teams, ", ");
       // dd($get_all_teams);
    	if(!empty($get_all_teams)){
    		$sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`, 
    			`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag` 
    			FROM `t_teambuild` 
    			INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no` 
    			INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no` 
    			WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams)   
    			AND `t_teambuild`.`agent_type` = 2");
    	}else{
    		$sales_agent_arr =[];
    	}

        /*$sales_agent_arr = DB::table("t_teambuild")
        ->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
        ->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
        ->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
        ->whereRaw("t_teambuild.hot_flag = 1 or t_teambuild.hod_flag = 1 or t_teambuild.team_lead_flag = 1 ")
        ->where('t_teambuild.agent_type', 2)->get();*/

        $sales_agent_info = []; 
        if (!empty($sales_agent_arr)) {
        	foreach ($sales_agent_arr as $value) {
        		$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
        	}
        }





        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $numberofdays = LookupData::where('lookup_type', 23)->orderBy('lookup_pk_no', 'desc')->first();
        $days = $numberofdays->lookup_name;

        $get_all_tem_members = "";
        if ($is_ses_hod > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id )")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
        } else if ($is_ses_hot > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
        } else if ($is_team_leader > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
        } else {
        	$get_all_tem_members .= $ses_user_id;
        }
        $get_all_team_members = rtrim(($get_all_tem_members),", ");        

        if ($request->tab_type == 1) {
        	if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
        		$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name,b.project_size
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.transfer_to_sales_agent_pk_no in (".$get_all_team_members.") AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9) 
        			UNION ALL
        			SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.phone2,b.phone2_code,b.all_phone_no,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name,b.project_size
        			FROM t_lead2lifecycle_vw b
        			WHERE (b.lead_cluster_head_pk_no in (".$get_all_team_members.") or b.lead_sales_agent_pk_no in (".$get_all_team_members.")) AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL)  and b.lead_current_stage not in(6,7,9)");
                //and lead_dist_type=0
        	} else {
        		$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.phone1_code,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name,b.project_size,b.phone2,b.phone2_code,b.all_phone_no
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.transfer_to_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND c.transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9)
        			UNION ALL
        			SELECT b.customer_firstname,b.customer_lastname,b.phone1_code,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,b.created_at,b.created_by,b.user_full_name,b.project_size,b.phone2,b.phone2_code,b.all_phone_no
        			FROM t_lead2lifecycle_vw b
        			WHERE b.lead_sales_agent_pk_no=$user_id AND (b.lead_transfer_flag=0 OR b.lead_transfer_flag IS NULL) and b.lead_current_stage not in(6,7,9)");
        	}


        	$transfer_lead_arr = [];
        	foreach ($lead_transfer_list as $transfer) {
        		$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
        	}

        	$followup_arr = [];
        	if (!empty($transfer_lead_arr)) {
        		$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(" . implode(",", $transfer_lead_arr) . ")");
        		foreach ($lead_followup as $followup) {
        			$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
        			$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
        			$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
        		}
        	}
        	$cluster_head_list = [];
        	if ($is_ch == 1) {

        		$cluster_head_list = DB::table('s_user')
        		->select('s_user.user_pk_no', 's_user.user_fullname', 's_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
        		->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
        		->Join('s_lookdata', 't_teambuild.category_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
        		->where('s_user.user_type', 2)
        		->where('s_user.row_status', 1)
        		->where('s_user.row_status', 1)
        		->where('t_teambuild.hod_flag', 1)
        		->get();

        	}

        	return view('admin.sales_team_management.lead_transfer.lead_transfer_list', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'project_cat', 'project_area', 'followup_arr', 'days', 'ses_user_id', 'cluster_head_list', 'is_ch', 'userRoleId'));
        }
        if ($request->tab_type == 2) {
        	$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.project_name, b.lead_sales_agent_name,b.lead_current_stage,
        		b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,
        		c.transfer_pk_no,c.transfer_to_sales_agent_pk_no,t.user_fullname AS to_sales_agent_name,
        		c.transfer_from_sales_agent_pk_no,f.user_fullname AS from_sales_agent_name,b.created_by,b.created_at,b.user_full_name,b.project_size,b.phone2,b.phone2_code,b.all_phone_no
        		FROM t_lead2lifecycle_vw b,t_leadtransfer c 
        		LEFT JOIN s_user f ON c.transfer_from_sales_agent_pk_no=f.user_pk_no
        		LEFT JOIN s_user t ON c.transfer_to_sales_agent_pk_no=t.user_pk_no
        		WHERE c.lead_pk_no=b.lead_pk_no AND c.transfer_from_sales_agent_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=0
        		AND b.lead_current_stage NOT IN(6,7,9) 
        		GROUP BY b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,
        		b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,
        		c.transfer_pk_no,c.transfer_to_sales_agent_pk_no,t.user_fullname,f.user_fullname,c.transfer_from_sales_agent_pk_no,b.phone2,b.phone2_code,b.all_phone_no");




        	$transfer_lead_arr = [];
        	foreach ($lead_transfer_list as $transfer) {
        		$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
        	}

        	$followup_arr = [];
        	if (!empty($transfer_lead_arr)) {
        		$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(" . implode(",", $transfer_lead_arr) . ")");
        		foreach ($lead_followup as $followup) {
        			$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
        			$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
        			$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
        		}
        	}

            $max_transfer_arr = [];
            if (!empty($transfer_lead_arr)) {
                $lead_max_trans = DB::select("SELECT lead_pk_no,max(transfer_pk_no) transfer_pk_no,transfer_to_sales_agent_flag from t_leadtransfer where lead_pk_no in(" . implode(",", $transfer_lead_arr) . ") AND transfer_to_sales_agent_flag=0 group by lead_pk_no,transfer_to_sales_agent_flag ");
                foreach ($lead_max_trans as $max_trans) {
                    $max_transfer_arr[$max_trans->lead_pk_no] = $max_trans->transfer_to_sales_agent_flag;
                }
            }

            $is_ch = 0;
            return view('admin.sales_team_management.lead_transfer.lead_transfer_request', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'followup_arr', 'is_ch', 'ses_user_id', 'userRoleId','max_transfer_arr'));
        }
        if ($request->tab_type == 3) {
        	$is_ch = Session::get('user.is_ses_hod');
        	if ($is_ch == 1) {


        		$lead_transfer_list = DB::select("SELECT b.*
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE  b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.ch_user_pk_no=$user_id AND c.transfer_to_sales_agent_flag=1 AND transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9)");

        	} else {
        		$lead_transfer_list = DB::select("SELECT b.*
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.transfer_from_sales_agent_pk_no=$user_id AND c.transfer_to_sales_agent_flag=1 AND transfer_to_sales_agent_flag=1 and b.lead_current_stage not in(6,7,9)");

        	}
        	return view('admin.sales_team_management.lead_transfer.lead_transferred', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'ses_user_id', 'userRoleId'));

        }
        if ($request->tab_type == 4) {
        	$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.project_name, b.lead_sales_agent_name,b.lead_current_stage,
        		b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,
        		c.transfer_pk_no,c.transfer_to_sales_agent_pk_no,t.user_fullname AS to_sales_agent_name,
        		c.transfer_from_sales_agent_pk_no,f.user_fullname AS from_sales_agent_name,b.created_by,b.created_at,b.user_full_name,b.project_size,b.phone2,b.phone2_code,b.all_phone_no
        		FROM t_lead2lifecycle_vw b,t_leadtransfer c 
        		LEFT JOIN s_user f ON c.transfer_from_sales_agent_pk_no=f.user_pk_no
        		LEFT JOIN s_user t ON c.transfer_to_sales_agent_pk_no=t.user_pk_no
        		WHERE c.lead_pk_no=b.lead_pk_no AND c.ch_user_pk_no=$user_id AND b.lead_transfer_flag=1 AND transfer_to_sales_agent_flag=0 
        		AND b.lead_current_stage NOT IN(6,7,9) and (c.is_rejected !=1 or c.is_rejected is null) 
        		GROUP BY b.customer_firstname,b.customer_lastname,b.phone1,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,
        		b.lead_current_stage_name,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_category_pk_no,b.lead_sales_agent_pk_no,
        		c.transfer_pk_no,c.transfer_to_sales_agent_pk_no,t.user_fullname,f.user_fullname,c.transfer_from_sales_agent_pk_no,b.phone2,b.phone2_code,b.all_phone_no");
        	$transfer_lead_arr = [];
        	foreach ($lead_transfer_list as $transfer) {
        		$transfer_lead_arr[$transfer->lead_pk_no] = $transfer->lead_pk_no;
        	}
        	$is_ch = 1;
        	$followup_arr = [];
        	if (!empty($transfer_lead_arr)) {
        		$lead_followup = DB::select("SELECT a.lead_pk_no,a.lead_followup_datetime,a.followup_Note,a.Next_FollowUp_date from t_leadfollowup a where a.lead_pk_no in(" . implode(",", $transfer_lead_arr) . ")");
        		foreach ($lead_followup as $followup) {
        			$followup_arr[$followup->lead_pk_no]['lead_followup_datetime'] = $followup->lead_followup_datetime;
        			$followup_arr[$followup->lead_pk_no]['followup_Note'] = $followup->followup_Note;
        			$followup_arr[$followup->lead_pk_no]['Next_FollowUp_date'] = $followup->Next_FollowUp_date;
        		}
        	}
            $max_transfer_arr = [];
            if (!empty($transfer_lead_arr)) {
                $lead_max_trans = DB::select("SELECT lead_pk_no,max(transfer_pk_no) transfer_pk_no,transfer_to_sales_agent_flag from t_leadtransfer where lead_pk_no in(" . implode(",", $transfer_lead_arr) . ") and transfer_to_sales_agent_flag=0 group by lead_pk_no,transfer_to_sales_agent_flag ");
                foreach ($lead_max_trans as $max_trans) {
                    $max_transfer_arr[$max_trans->lead_pk_no] = $max_trans->transfer_to_sales_agent_flag;
                }
            }
            return view('admin.sales_team_management.lead_transfer.lead_transfer_request', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'followup_arr', 'is_ch', 'ses_user_id', 'userRoleId','max_transfer_arr'));
        }
        if ($request->tab_type == 5) {
        	$cluster_head_list = [];
        	$is_ch = Session::get('user.is_ses_hod');
        	if ($is_ch == 1) {

        		$cluster_head_list = DB::table('s_user')
        		->select('s_user.user_pk_no', 's_user.user_fullname', 's_lookdata.lookup_pk_no', 's_lookdata.lookup_name')
        		->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
        		->Join('s_lookdata', 't_teambuild.category_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
        		->where('s_user.user_type', 2)
        		->where('s_user.row_status', 1)
        		->where('s_user.row_status', 1)
        		->where('t_teambuild.hod_flag', 1)
        		->get();

        		$team_member = TeamAssign::where("hod_user_pk_no", $user_id)->select("user_pk_no")->get();
        		$team_array = array();
        		foreach ($team_member as $value) {
        			$team_array[$value->user_pk_no] = $value->user_pk_no;
        		}

        		$team_array = implode(',', $team_array);
        		$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.lead_sales_agent_pk as lead_sales_agent_pk_no,b.last_lead_followup_datetime,b.created_at,b.project_category_pk_no,b.created_by,b.user_full_name,b.phone2,b.phone2_code,b.all_phone_no
        			FROM t_lead_followup_count_by_current_stage_vw b LEFT JOIN t_leadtransfer c 
        			on c.lead_pk_no = b.lead_pk_no and (b.lead_sales_agent_pk in ($team_array)) and c.transfer_to_sales_agent_flag =0 and b.lead_current_stage not in(6,7,9)");


        	} else {
        		$lead_transfer_list = DB::select("SELECT b.customer_firstname,b.customer_lastname,b.phone1,b.phone1_code,b.project_name,b.lead_sales_agent_name,b.lead_current_stage,b.lead_pk_no,b.lead_id,b.project_category_name,b.project_size,b.lead_sales_agent_pk as lead_sales_agent_pk_no,b.last_lead_followup_datetime,b.created_at,b.created_by,b.user_full_name,b.phone2,b.phone2_code,b.all_phone_no
        			FROM t_lead_followup_count_by_current_stage_vw b
        			WHERE b.lead_sales_agent_pk=$user_id and b.lead_current_stage not in(6,7,9)");
        	}

        	return view('admin.sales_team_management.lead_transfer.auto_transfer', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'is_ch', 'days', 'project_area', 'project_cat', 'cluster_head_list', 'ses_user_id', 'userRoleId'));
        }


        if ($request->tab_type == 6) {
        	$is_ch = Session::get('user.is_ses_hod');
        	if ($is_ch == 1) {


        		$lead_transfer_list = DB::select("SELECT b.*
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE  b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.ch_user_pk_no=$user_id  and b.lead_current_stage not in(6,7,9) and c.is_rejected =1");



        	} else {
        		$lead_transfer_list = DB::select("SELECT b.*
        			FROM t_lead2lifecycle_vw b,t_leadtransfer c
        			WHERE b.lead_pk_no=c.lead_pk_no AND c.re_transfer=1
        			AND c.transfer_from_sales_agent_pk_no=$user_id and c.is_rejected =1");

        	}
        	return view('admin.sales_team_management.lead_transfer.rejected_lead', compact('lead_stage_arr', 'sales_agent_info', 'lead_transfer_list', 'ses_user_id', 'userRoleId'));

        }


    }

    public function accept_transfer(Request $request)
    {
    	$user_id = Session::get('user.ses_user_pk_no');
    	$transfer_id = $request->get('transfer_id');
    	$to_agent = $request->get('to_agent');
    	$to_agent = $request->get('to_agent');
    	$accept_reject_ind =  $request->get('accept_reject_ind');

    	if($accept_reject_ind ==1){
    		$lead_transfer = LeadTransfer::findOrFail($transfer_id);
    		$lead_transfer->transfer_to_sales_agent_flag = 1;

    		if ($lead_transfer->save()) {
    			$llcdata = LeadLifeCycle::where("lead_pk_no", $request->get('lead_id'))->first();
    			$llcdata->lead_sales_agent_pk_no = $to_agent;
    			$llcdata->lead_sales_agent_assign_dt = date('Y-m-d');
    			$llcdata->save();
    			return response()->json(['message' => 'Lead Request Accepted successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    		}  
    	}


    	if($accept_reject_ind==2){
    		$lead_transfer = LeadTransfer::findOrFail($transfer_id);
    		$lead_transfer->is_rejected = 1;

    		if ($lead_transfer->save()) {
    			$llcdata = LeadLifeCycle::where("lead_pk_no", $request->get('lead_id'))->first();
    			$llcdata->is_rejected = 1;
    			$llcdata->lead_transfer_flag = 0;
    			$llcdata->save();

    			DB::table('t_leadtransferhistory')
    			->where("lead_pk_no", $request->get('lead_id'))
    			->orderBy('transhistory_pk_no', 'desc')
    			->take(1)
    			->update(['is_rejected' => 1]);

    			return response()->json(['message' => 'Lead Request Accepted successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    		}  
    	}
    }

    public function ch_accept_transfer(Request $request)
    {
    	$user_id = Session::get('user.ses_user_pk_no');
    	$team_info = TeamAssign::where('user_pk_no', $user_id)->select('hod_user_pk_no')->first();

    	$lead_category = $request->get('category');
    	$cmb_area = $request->get('cmb_area');
    	$cmb_project_name = $request->get('cmb_project_name');
    	$cmb_size = $request->get('cmb_size');
    	$agent_category = $request->get('cmb_category');

        //if($lead_category != $agent_category)
        //{
    	$ldata = Lead::findOrFail($request->lead_pk_no);
    	$ldata->project_category_pk_no = $agent_category;
    	$ldata->project_area_pk_no = $cmb_area;
    	$ldata->Project_pk_no = $cmb_project_name;
    	$ldata->project_size_pk_no = $cmb_size;

    	if ($ldata->save()) {
            /*$lcdata = LeadLifeCycle::findOrFail($request->lead_pk_no);
            $lcdata->lead_sales_agent_pk_no 	= $request->get('cmbTransferTo');
            $lcdata->lead_sales_agent_assign_dt = date("Y-m-d");
            $lcdata->save();*/

            $ltdata = new TransferHistory();
            $ltdata->lead_pk_no = $request->lead_pk_no;
            $ltdata->project_category_pk_no = $lead_category;
            $ltdata->project_area_pk_no = $cmb_area;
            $ltdata->Project_pk_no = $cmb_project_name;
            $ltdata->project_size_pk_no = $cmb_size;
            $ltdata->transfer_from_sales_agent_pk_no = $request->get('agent');
            $ltdata->transfer_to_sales_agent_pk_no = $request->get('cmbTransferTo');
            $ltdata->ch_user_no_pk = $team_info->hod_user_pk_no;
            $ltdata->save();
        }
        //}

        //$user_id        = Session::get('user.ses_user_pk_no');
        $lc_id = $request->get('lead_pk_no');
        $lead_name = $request->get('lead_name');
        $cmbTransferTo = $request->get('cmbTransferTo');
        $create_date = date("Y-m-d");

        $lead_transfer = DB::statement(
        	DB::raw("CALL proc_leadtransfer_ins ('1','$create_date',$lc_id,$user_id,$cmbTransferTo,0,1,$team_info->hod_user_pk_no,1,$user_id,'$create_date')")
        );

        $transfer_id = LeadTransfer::where('lead_pk_no', $lc_id)->orderBy("transfer_pk_no", "desc")->first();

        $user_id = Session::get('user.ses_user_pk_no');
        $to_agent = $request->get('cmbTransferTo');

        $lead_transfer = LeadTransfer::findOrFail($transfer_id->transfer_pk_no);
        $lead_transfer->transfer_to_sales_agent_flag = 1;
        //dd($request->get('lead_id'));
        if ($lead_transfer->save()) {
        	$llcdata = LeadLifeCycle::where("lead_pk_no", $lc_id)->first();
        	$llcdata->lead_sales_agent_pk_no = $to_agent;
        	$llcdata->lead_sales_agent_assign_dt = date('Y-m-d');
        	$llcdata->save();
        	return response()->json(['message' => 'Lead Request Accepted successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        }

    }
}
