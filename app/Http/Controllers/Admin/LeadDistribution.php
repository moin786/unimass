<?php

namespace App\Http\Controllers\Admin;

use App\FlatSetup;
use Auth;
Use Session;
use App\LeadLifeCycle;
use App\LeadLifeCycleView;
use App\LookupData;
use App\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;


class LeadDistribution extends Controller
{

	public function index($from_dt = "", $to_dt = "")
	{

		$ses_user_id = Session::get('user.ses_user_pk_no');
        //dd($ses_user_id);
		$is_hod = Session::get('user.is_ses_hod');
		$is_hot = Session::get('user.is_ses_hot');
		$userRoleID =  Session::get('user.ses_role_lookup_pk_no');

		$is_tl = Session::get('user.is_team_leader');

		$fromdate = date("Y-m-d", strtotime($from_dt));
		$todate = date("Y-m-d", strtotime($to_dt));
		if ($from_dt != "" && $todate != "") {
			$date_cond = "and created_at BETWEEN '$fromdate' AND '$todate'";
		} else if ($from_dt != "" && $todate == "") {
			$date_cond = "and created_at='$fromdate'";
		} else {
			$date_cond = "";
		}

		if ($is_hod == 1 || $is_hot == 1 || $is_tl == 1) {

			$is_team_leader = Session::get('user.is_team_leader');
			$is_ses_hod = Session::get('user.is_ses_hod');
			$is_ses_hot = Session::get('user.is_ses_hot');
			$get_all_ch_members = [];
			if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
				$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
				$get_all_team_members = explode(",", $get_all_team_member . "," . $ses_user_id);


			}

			if ($is_hod == 1) {
				$lead_data = DB::table('t_lead2lifecycle_vw')
				->whereIn('lead_cluster_head_pk_no', $get_all_team_members)
				->whereRaw("((lead_sales_agent_pk_no is NULL or lead_sales_agent_pk_no=0)" . $date_cond . ")")
				->orderBy("created_at", "desc")
				->get();


			} else {
				$lead_data = DB::table('t_lead2lifecycle_vw')
				->whereRaw("(`lead_sales_agent_pk_no` = $ses_user_id 
					OR `lead_cluster_head_pk_no` = $ses_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$ses_user_id )")
				->orderBy("created_at", "desc")
				->get();
			}

            //->whereRaw("(lead_sales_agent_pk_no is NULL or lead_sales_agent_pk_no=0)")

		} else {
			if($userRoleID == 551){
				$lead_data = DB::table("t_lead2lifecycle_vw")
				->whereRaw("( lead_sales_agent_pk_no =0) $date_cond")
				->get();
				/*dd(count($lead_data));*/
			}
			else{
				$lead_data = [];
			}
		}
        //dd($sales_agent_arr);

		$ses_user_id = Session::get('user.ses_user_pk_no');

		$get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild WHERE user_pk_no=$ses_user_id");

		$get_all_teams = "";
		if (!empty($get_team_info)) {
			foreach ($get_team_info as $team) {
				$get_all_teams .= $team->team_ids . ",";
			}
		}
		$get_all_teams = rtrim($get_all_teams, ", ");

		if (!empty($get_all_teams)) {
			$sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`, 
				`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag` 
				FROM `t_teambuild` 
				INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no` 
				INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no` 
				WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams)
				AND `t_teambuild`.`agent_type` = 2 and `t_teambuild`.`user_pk_no` != $ses_user_id");
		} else {
			$sales_agent_arr = [];
		}
        //dd($sales_agent_arr,$ses_user_id);

        /*$sales_agent_arr = DB::table("t_teambuild")
        ->select("s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
        ->leftJoin("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
        ->leftJoin("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
        ->whereIn("t_teambuild.team_lookup_pk_no", [$get_all_teams])
        ->where("t_teambuild.hod_flag","!=",1)
        ->where('t_teambuild.agent_type', 2)->get();*/
        //dd($sales_agent_arr);
        $sales_agent_info = [];
        if (!empty($sales_agent_arr)) {
        	foreach ($sales_agent_arr as $value) {
        		$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
        	}
        }

        $tab = 1;
        $auto_return_time = LookupData::where("lookup_type", 25)->orderBy("lookup_pk_no", "desc")->first();
        $lead_distribution_type = config('static_arrays.lead_distribution_type');
        return view('admin.lead_management.lead_distribution.lead_distribution', compact('lead_data', 'sales_agent_arr', 'lead_distribution_type', 'is_hod', 'tab', 'is_hot', 'is_tl', 'auto_return_time', 'sales_agent_info'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function distribute_lead(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$lead_id = $request->input('lead_life_cycle_id');
    	$sales_agent = $request->input('cmbTransferTo');
    	$create_date = date("Y-m-d");
    	for ($i = 0; $i < count($lead_id); $i++) {
    		$lead = LeadLifeCycle::findOrFail($lead_id[$i]);

    		$lead->lead_sales_agent_assign_dt = $create_date;
            $lead->lead_dist_type = 1; // 1= Manual
            $lead->lead_sales_agent_pk_no = $sales_agent;

            $lead->lead_dist_by = $ses_user_id;
            $lead->updated_by = 1;
            $lead->updated_at = $create_date;
            $lead->save();

        }

        return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function distribute_junk_lead(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$lead_id = $request->input('lead_life_cycle_id');
    	$sales_agent = $request->input('cmbTransferTo');

    	$sales_agent = explode('_', $sales_agent);
    	$create_date = date("Y-m-d");

    	for ($i = 0; $i < count($lead_id); $i++) {
    		$lead = LeadLifeCycle::findOrFail($lead_id[$i]);
    		$lead->lead_cluster_head_assign_dt = $create_date;
    		$lead->lead_sales_agent_assign_dt = $create_date;
            $lead->lead_dist_type = 1; // 1= Manual



            $lead->lead_cluster_head_pk_no = $sales_agent[1];
            if($sales_agent[1] == $sales_agent[0] ){
            	$lead->lead_sales_agent_pk_no =0;
            }else{
            	$lead->lead_sales_agent_pk_no = $sales_agent[0];
            }

            $lead->distribute_to = $sales_agent[0];
            $lead->lead_current_stage = 1;
            $lead->lead_dist_by = $ses_user_id;
            $lead->lead_dist_type = 0;
            $lead->lead_k1_flag = 0; // lead_transfer_flag
            $lead->lead_transfer_flag = 0;
            $lead->lead_hold_flag = 0;
            $lead->lead_sold_flag = 0;
            $lead->lead_priority_flag = 0;
            $lead->updated_by = 1;
            $lead->updated_at = $create_date;
            $lead->save();
            $txt_followup_date_time = date("Y-m-d H:i:s", strtotime($create_date));

            $lead_followup = DB::statement(
            	DB::raw("CALL proc_leadfollowup_ins ('1','$create_date','$lead_id[$i]','0',' ', 9,1,'$create_date','$txt_followup_date_time',' ',1,1,$ses_user_id,'$create_date',null,null,null,null)")
            );

        }

        return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_dist_leads(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_team_leader = Session::get('user.is_team_leader');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');


    	$auto_return_time = LookupData::where("lookup_type", 25)->orderBy("lookup_pk_no", "desc")->first();
    	$get_all_ch_members = [];

    	if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
    		$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
    		$get_all_team_members = explode(",", $get_all_team_member . "," . $ses_user_id);

    	}

    	$tab = $request->tab_type;
    	$sales_agent_arr = [];
    	if ($request->tab_type == 0) {
    		$ses_user_id = Session::get('user.ses_user_pk_no');
            //dd($ses_user_id);
    		$is_hod = Session::get('user.is_ses_hod');
    		if ($is_hod == 1) {
    			$lead_data = DB::table('t_lead2lifecycle_vw')
    			->where('lead_cluster_head_pk_no', $ses_user_id)
    			->whereRaw("(lead_sales_agent_pk_no is NOT NULL and lead_sales_agent_pk_no !=0)")
    			->orderBy("created_at", "desc")
    			->get();


    			$category_wise_agent_data = DB::table('s_user')
    			->select('s_user.user_pk_no', 's_user.user_fullname', 't_teambuild.category_lookup_pk_no', 't_teambuildchd.area_lookup_pk_no')
    			->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
    			->Join('t_teambuildchd', 't_teambuild.teammem_pk_no', '=', 't_teambuildchd.teammem_pk_no')
    			->Join('s_lookdata', 't_teambuildchd.area_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
    			->where('s_user.user_type', 2)
    			->where('s_user.row_status', 1)
    			->where('t_teambuild.hod_flag', 0)
    			->where('t_teambuild.hot_flag', 0)
    			->where('t_teambuild.team_lead_flag', 0)
    			->where('s_lookdata.lookup_row_status', 1)
    			->whereIn('s_user.user_pk_no', $get_all_team_members)
    			->get();

    			$sales_agent = [];
    			foreach ($category_wise_agent_data as $row) {
    				$sales_agent[$row->category_lookup_pk_no][$row->area_lookup_pk_no][$row->user_pk_no] = $row->user_fullname;
    			}


    			$sales_agent_arr = DB::table("t_teambuild")
    			->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    			->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    			->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
    			->where("hod_flag", "!=", 1)
    			->whereRaw("(t_teambuild.hod_user_pk_no = '$ses_user_id' or t_teambuild.hot_user_pk_no = '$ses_user_id' or t_teambuild.team_lead_user_pk_no ='$ses_user_id')")
    			->where('t_teambuild.agent_type', 2)->get();


    		} else {
    			$lead_data = DB::table('t_lead2lifecycle_vw')
    			->where('lead_dist_by', $ses_user_id)
    			->orderBy("created_at", "desc")
    			->get();


    			$sales_agent = [];

    			$category_wise_agent_data = DB::table('s_user')
    			->select('s_user.user_pk_no', 's_user.user_fullname', 't_teambuild.category_lookup_pk_no', 't_teambuildchd.area_lookup_pk_no')
    			->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
    			->Join('t_teambuildchd', 't_teambuild.teammem_pk_no', '=', 't_teambuildchd.teammem_pk_no')
    			->Join('s_lookdata', 't_teambuildchd.area_lookup_pk_no', '=', 's_lookdata.lookup_pk_no')
    			->where('s_user.user_type', 2)
    			->where('s_user.row_status', 1)
    			->where('t_teambuild.hod_flag', 0)
    			->where('t_teambuild.hot_flag', 0)
    			->where('t_teambuild.team_lead_flag', 0)
    			->where('s_lookdata.lookup_row_status', 1)
    			->whereIn('s_user.user_pk_no', $get_all_team_members)
    			->get();

    			foreach ($category_wise_agent_data as $row) {
    				$sales_agent[$row->category_lookup_pk_no][$row->area_lookup_pk_no][$row->user_pk_no] = $row->user_fullname;
    			}


    			$sales_agent_arr = DB::table("t_teambuild")
    			->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    			->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    			->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
    			->where("hod_flag", "!=", 1)
    			->whereRaw("(t_teambuild.hod_user_pk_no = '$ses_user_id' or t_teambuild.hot_user_pk_no = '$ses_user_id' or t_teambuild.team_lead_user_pk_no ='$ses_user_id')")
    			->where('t_teambuild.agent_type', 2)->get();
    		}
    		$is_hot = Session::get('user.is_ses_hot');

    		$is_tl = Session::get('user.is_team_leader');

    		$lead_distribution_type = config('static_arrays.lead_distribution_type');
    		return view('admin.lead_management.lead_distribution.all_lead', compact('lead_data', 'sales_agent', 'lead_distribution_type', 'is_hod', 'tab', 'is_hot', 'is_tl', 'sales_agent_arr'));
    	}
    	if ($request->tab_type == 1) {
    		$is_hod = Session::get('user.is_ses_hod');
    		if ($is_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
    			if ($is_hod == 1) {
    				$lead_data = DB::table('t_lead2lifecycle_vw')
    				->whereIn('lead_cluster_head_pk_no', $get_all_team_members)
    				->whereRaw("(lead_sales_agent_pk_no is NULL or lead_sales_agent_pk_no=0)")
    				->orderBy("created_at", "desc")
    				->get();

    			} else {
    				$lead_data = DB::table('t_lead2lifecycle_vw')
    				->whereRaw("(`lead_sales_agent_pk_no` = $ses_user_id 
    					OR `lead_cluster_head_pk_no` = $ses_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$ses_user_id )")
    				->orderBy("created_at", "desc")
    				->get();
    			}

    			$sales_agent = [];

    			$get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild WHERE user_pk_no=$ses_user_id");
    			$get_all_teams = "";
    			if (!empty($get_team_info)) {
    				foreach ($get_team_info as $team) {
    					$get_all_teams .= $team->team_ids . ",";
    				}
    			}
    			$get_all_teams = rtrim($get_all_teams, ", ");
    			$sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`, 
    				`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag` 
    				FROM `t_teambuild` 
    				INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no` 
    				INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no` 
    				WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams)  
    				AND `t_teambuild`.`agent_type` = 2");

    			$sales_agent_info = [];
    			if (!empty($sales_agent_arr)) {
    				foreach ($sales_agent_arr as $value) {
    					$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
    				}
    			}

    		} else {
    			$lead_data = $sales_agent = [];
    		}

    		$is_hot = Session::get('user.is_ses_hot');
    		$is_tl = Session::get('user.is_team_leader');

    		$auto_return_time = LookupData::where("lookup_type", 25)->orderBy("lookup_pk_no", "desc")->first();
    		$lead_distribution_type = config('static_arrays.lead_distribution_type');
    		return view('admin.lead_management.lead_distribution.all_lead', compact('lead_data', 'sales_agent', 'lead_distribution_type', 'is_hod', 'tab', 'is_hot', 'is_tl', 'auto_return_time', 'sales_agent_arr', 'sales_agent_info'));
    	}

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_junk_leads(Request $request)
    {
    	$is_super_admin = Session::get('user.is_super_admin');
    	$stage = config('static_arrays.lead_stage_arr');
    	$user_id = Session::get('user.ses_user_pk_no');


    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');

    	$is_team_leader = Session::get('user.is_team_leader');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    	if ($is_super_admin == 1 || $userRoleID == 551) {
    		$lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage = 9 order by created_at desc,lead_pk_no desc");
    		$user_cond ="";

    	} else {

    		if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
    			$get_all_tem_members = "";

    			if ($is_ses_hod > 0) {
    				$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id )")[0]->team_members;

    				$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
    			} else if ($is_ses_hot > 0) {
    				$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id and hod_flag != 1)")[0]->team_members;

    				$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
    			} else if ($is_team_leader > 0) {
    				$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

    				$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
    			} else {
    				$get_all_tem_members .= $user_id;
    			}
    			$user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";
                //echo "select * from t_lead2lifecycle_vw b where b.lead_current_stage in(6,9) $user_cond";
                //$lead_data = DB::select("select * from t_lead2lifecycle_vw b where b.lead_current_stage =9 $user_cond");
    		} else {
    			$lead_data = DB::table('t_lead2lifecycle_vw')
    			->where('lead_sales_agent_pk_no', $user_id)
    			->where('lead_current_stage', 9)
    			->orderBy("created_at", "desc")
    			->get();
    		}
    	}
    	if ($is_super_admin == 1 || $userRoleID == 551) {
    		$team_cond = "";
    	} else {
    		$team_cond = "WHERE user_pk_no=$user_id";

    	}
    	$get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild $team_cond");

    	$get_all_teams = "";
    	if (!empty($get_team_info)) {
    		foreach ($get_team_info as $team) {
    			$get_all_teams .= $team->team_ids . ",";
    		}
    	}
    	$get_all_teams = rtrim($get_all_teams, ", ");
        // dd($get_all_teams);
    	if (!empty($get_all_teams)) {
    		$sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_pk_no`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`, 
    			`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag` 
    			FROM `t_teambuild` 
    			INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no` 
    			INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no` 
    			WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams)  
    			AND `t_teambuild`.`agent_type` = 2");
            //AND t_teambuild.hod_flag != 1
    	} else {
    		$sales_agent_arr = [];
    	}

    	$sales_agent_info = [];
    	$team_ch = [];
    	if (!empty($sales_agent_arr)) {
    		foreach ($sales_agent_arr as $value) {
    			$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag . "_" . $value->lookup_pk_no ;

    			if($value->hod_flag == 1){
    				$team_ch[$value->lookup_pk_no] = $value->user_pk_no;
    			}
    		}
    	}

    	$tab = 1;


    	$tab = $request->tab_type;
    	$sales_agent_arr = [];
    	if ($request->tab_type == 0) {
    		$lead_data = DB::select("select * from t_lead2lifecycle_vw b where junk_ind=1 and (distribute_to is not null or distribute_to = 0 )  $user_cond  order by created_at desc,lead_pk_no desc");

    	}
    	if ($request->tab_type == 1) {
    		$lead_data = DB::select("select * from t_lead2lifecycle_vw b where b.lead_current_stage = 9 $user_cond  order by created_at desc,lead_pk_no desc");
    	}
    	$stage = config('static_arrays.lead_stage_arr');
    	return view('admin.lead_management.junk_lead.junk_work_list_data', compact('lead_data','stage','tab', 'sales_agent_info','team_ch'));


    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function lead_auto_distribute(Request $request)
    {
    	$user_info = Auth::user();
    	$user_id = $request->get('user_id');
    	$dist_value = $request->get('dist_value');
    	$dist_date = date("Y-m-d", strtotime($request->get('dist_date')));

    	$qcdata = TeamUser::findOrFail($user_id);
    	$qcdata->auto_distribute = $dist_value;
    	$qcdata->distribute_date = $dist_date;

    	if ($qcdata->save()) {
    		session(['user.ses_auto_dist' => $dist_value]);
    		session(['user.ses_dist_date' => $dist_date]);
    		$msg = ($dist_value == 1) ? "set" : "removed";
    		return response()->json(['message' => "Auto Distribution $msg successfully", 'title' => 'Success', "positionClass" => "toast-top-right"]);
    	} else {
    		return response()->json(['message' => 'Data did not updated successfully', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
    	}
    }

    public function lead_distribution_cre()
    {
    	$is_hod = $is_hot = $is_tl = 0;
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');
    	$is_team_leader = Session::get('user.is_team_leader');

    	$get_all_tem_members ="";
    	if ($is_ses_hod > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id ) and agent_type=2")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss;// . "," . $ses_user_id;
    	} else if ($is_ses_hot > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1 and agent_type=1 )")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss;// . "," . $ses_user_id;
    	} else if ($is_team_leader > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id) and hod_flag != 1 and hot_flag != 1 and agent_type=1 )")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss;// . "," . $ses_user_id;

    	} else {
    		$get_all_tem_members .= $ses_user_id;
    	}
    	$get_all_team_members = $get_all_tem_members;//rtrim(($get_all_tem_members), ", ");




    	$lead_data = DB::table('t_lead2lifecycle_vw')
    	->where('lead_cluster_head_pk_no', 0)
    	->where('lead_current_stage', 1)
    	->whereRaw("(source_auto_pk_no in(" . $get_all_team_members . "))")
    	->get();

    	$sales_agent_arr = DB::table("t_teambuild")
    	->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    	->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    	->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag","s_lookdata.lookup_pk_no")
    	->where("s_user.row_status",1)
    	->where('t_teambuild.agent_type', 2)->get();

    	$sales_agent_info = [];
    	$team_ch = [];
    	if (!empty($sales_agent_arr)) {
    		foreach ($sales_agent_arr as $value) {
    			$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag. "_" . $value->lookup_pk_no;
    			if ($value->hod_flag == 1) {
    				$team_ch[$value->lookup_pk_no] = $value->user_pk_no;
    			}
    		}
    	}

    	$lookup_data = LookupData::where('lookup_type', 2)->where("lookup_row_status", 1)->get();

    	foreach ($lookup_data as $value) {
    		$key = $value->lookup_pk_no;
    		if ($value->lookup_type == 2)
    			$digital_mkt[$key] = $value->lookup_name;
    	}


    	$tab = 1;
    	return view("admin.lead_management.lead_distribution", compact("is_hod", "is_hot", "is_tl", "tab", 'lead_data', 'sales_agent_info','digital_mkt','team_ch'));
    }


    public function distribute_lead_to_ch(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$lead_id = $request->input('distribute_lead_id');

    	$sales_agent = $request->input('cmbTransferTo');
    	$sales_agent_data = explode("_", $sales_agent);
    	$create_date = date("Y-m-d");
        /*$helper = new Helper();
        $helper->sendSms();*/
        //dd($lead_id,$sales_agent);
        for ($i = 0; $i < count($lead_id); $i++) {

        	$lead = LeadLifeCycle::findOrFail($lead_id[$i]);
        	if($sales_agent_data[1]==$sales_agent_data[0] )
        		$lead->lead_sales_agent_pk_no = 0;
        	else{
        		$lead->lead_sales_agent_pk_no = $sales_agent_data[0];

        		$lead->lead_sales_agent_assign_dt = $create_date;
        	}
        	$lead->lead_cluster_head_pk_no = $sales_agent_data[1];
        	$lead->lead_cluster_head_assign_dt = $create_date;

            $lead->lead_dist_type = 0; // 1= Manual
            $lead->updated_by = 1;


            // $lead->lead_k1_flag = 1;
            // $lead->lead_k1_datetime = $create_date;
            // $lead->lead_k1_by = $ses_user_id;
            $lead->updated_at = $create_date;
            $lead->save();
        }

        return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

    }

    public function load_dist_leads_to_ch(Request $request)
    {
    	$tab = $request->tab_type;
    	$ses_user_id = Session::get('user.ses_user_pk_no');

    	$sales_agent_arr = DB::table("t_teambuild")
    	->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    	->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    	->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag","s_lookdata.lookup_pk_no")
    	->where("s_user.row_status",1)
    	->where('t_teambuild.agent_type', 2)->get();

    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');
    	$is_team_leader = Session::get('user.is_team_leader');

    	$get_all_tem_members ="";
    	if ($is_ses_hod > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id ) and agent_type=2")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
    	} else if ($is_ses_hot > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1 and agent_type=1 )")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
    	} else if ($is_team_leader > 0) {
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id) and hod_flag != 1 and hot_flag != 1 and agent_type=1 )")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;

    	} else {
    		$get_all_tem_members .= $ses_user_id;
    	}
    	$get_all_team_members = rtrim(($get_all_tem_members), ", ");








    	$team_ch= [];
    	$sales_agent_info = [];
    	if (!empty($sales_agent_arr)) {
    		foreach ($sales_agent_arr as $value) {
    			$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag."_".$value->lookup_pk_no;
    			if ($value->hod_flag == 1) {
    				$team_ch[$value->lookup_pk_no] = $value->user_pk_no;
    			}
    		}
    	}
    	if ($request->tab_type == 0) {
       /*     $lead_data = DB::table('t_lead2lifecycle_vw')
            ->where([
                ['lead_cluster_head_pk_no', '!=', 0],
                ['lead_current_stage', '=', 1],
                ["source_auto_pk_no", $ses_user_id]
            ])->get();*/
            $lead_data = DB::table('t_lead2lifecycle_vw')
            ->where([['lead_cluster_head_pk_no', '!=', 0]])
            ->where('lead_current_stage', 1)
            ->whereRaw("(source_auto_pk_no in(" . $get_all_team_members . "))")
            ->get();
        }
        if ($request->tab_type == 1) {

        	$lead_data = DB::table('t_lead2lifecycle_vw')
        	->where('lead_cluster_head_pk_no', 0)
        	->where('lead_current_stage', 1)
        	->whereRaw("(source_auto_pk_no in(" . $get_all_team_members . "))")
        	->get();

        }

        $lookup_data = LookupData::where('lookup_type', 2)->where("lookup_row_status", 1)->get();

        foreach ($lookup_data as $value) {
        	$key = $value->lookup_pk_no;
        	if ($value->lookup_type == 2)
        		$digital_mkt[$key] = $value->lookup_name;
        }
        $lead_distribution_type = config('static_arrays.lead_distribution_type');
        return view('admin.lead_management.all_lead', compact('lead_data', 'lead_distribution_type', 'tab', 'lead_data', 'sales_agent_info','digital_mkt','team_ch'));


    }

    public function block_lead_list()
    {
    	$block_lead_info = LeadLifeCycleView::where([
    		['is_approved', "=", null],
    		['is_block', "=", 1],
    		["flatlist_pk_no", "!=", null]
    	])->get();
    	$ses_user_id = Session::get('user.ses_user_pk_no');

    	$type = 1;
    	return view("admin.lead_management.block_lead.block_lead_list", compact('block_lead_info', 'type', 'ses_user_id'));
    }

    public function load_block_lead_list(Request $request)
    {

    	$tab = $request->tab_type;
    	$ses_user_id = Session::get('user.ses_user_pk_no');

    	$sales_agent_arr = DB::table("t_teambuild")
    	->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    	->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    	->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
    	->whereRaw("t_teambuild.hot_flag = 1 or t_teambuild.hod_flag = 1 or t_teambuild.team_lead_flag = 1 ")
    	->where('t_teambuild.agent_type', 2)->get();

    	$sales_agent_info = [];
    	$type = $request->tab_type;
    	if (!empty($sales_agent_arr)) {
    		foreach ($sales_agent_arr as $value) {
    			$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
    		}
    	}
    	if ($request->tab_type == 0) {
    		$block_lead_info = LeadLifeCycleView::where('is_block', 1)->where('is_approved', 1)->get();
    	}
    	if ($request->tab_type == 1) {
    		$block_lead_info = LeadLifeCycleView::where([
    			['is_approved', "=", null],
    			['is_block', "=", 1],
    			["flatlist_pk_no", "!=", null]
    		])->get();
    	}
    	$lead_distribution_type = config('static_arrays.lead_distribution_type');
    	return view('admin.lead_management.block_lead.all_lead', compact("block_lead_info", "type", 'ses_user_id'));
    }

    public function approved_blocked_lead(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$ldata = LeadLifeCycle::find($request->leadlifecycle_id);


    	$ldata->is_approved = 1;
    	$ldata->is_approved_by = $ses_user_id;
    	$flat_setup = FlatSetup::find($ldata->flatlist_pk_no);
    	$flat_setup->block_status = 1;
    	$flat_setup->save();
    	$ldata->save();
    	return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

    }

    public function block_list_approved(Request $request)
    {
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$ldata = LeadLifeCycle::find($request->lead_pk_no);
    	$ldata->is_approved = 1;
    	$ldata->is_approved_by = $ses_user_id;
    	$flat_setup = FlatSetup::find($ldata->flatlist_pk_no);
    	$flat_setup->block_status = 1;
    	$flat_setup->save();
    	$ldata->save();
    	return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);


    }

}
