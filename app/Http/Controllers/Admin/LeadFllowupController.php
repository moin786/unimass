<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\LookupData;
use App\LeadLifeCycle;
use App\LeadFollowUp;
use App\LeadLifeCycleView;
use App\LeadStageHistory;
use App\LeadFollowupAttribute;
use App\FlatSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadFllowupController extends Controller
{
	public function index($id = "", $from_dt = "", $to_dt = "")
	{
		$lead_stage_arr = config('static_arrays.lead_stage_arr');

		$is_team_leader = Session::get('user.is_team_leader');
		$is_ses_hod = Session::get('user.is_ses_hod');
		$is_ses_hot = Session::get('user.is_ses_hot');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$numberofdays = LookupData::where('lookup_type', 23)->orderBy('lookup_pk_no', 'desc')->first();
		$days = $numberofdays->lookup_name;
		$userRoleId = Session::get('user.ses_role_lookup_pk_no');

		$get_all_tem_members = "";
		$tranfer_condition = "";
		$today_date = date("Y-m-d");
		if ($userRoleId == 551) {
			$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.followup_Note,
				a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
				FROM t_lead2lifecycle_vw b
				LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
				)
				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
				LEFT JOIN s_user d ON a.created_by=d.user_pk_no
				WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) ");
		} else {
			if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
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
					//$tranfer_condition = "and (b.lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR b.lead_transfer_from_sales_agent_pk_no IS NULL ) ";
				}
				$get_all_team_members = rtrim(($get_all_tem_members), ", ");

			} else {
				$get_all_team_members = $ses_user_id;
				//$tranfer_condition = "and (b.lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR b.lead_transfer_from_sales_agent_pk_no IS NULL ) ";
			}

			$lead_data = [];
			if ($get_all_team_members != "") {
				$user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";
				$today_date = date("Y-m-d");
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE lead_current_stage not in(6,7,9) and (a.meeting_date != '$today_date' OR a.`meeting_date` IS NULL) $tranfer_condition  $user_cond order by b.created_at desc");

				$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE lead_current_stage not in(7) and a.meeting_date = '$today_date'  $tranfer_condition  $user_cond");
			}
		}

		$fromdate = date("Y-m-d", strtotime($from_dt));
		$todate = date("Y-m-d", strtotime($to_dt));
		if ($from_dt != "" && $todate != "") {
			$date_cond = "and b.created_at BETWEEN '$fromdate' AND '$todate'";
		} else if ($from_dt != "" && $todate == "") {
			$date_cond = "and b.created_at='$fromdate'";
		} else {
			$date_cond = "";
		}
		$userType = Session::get('user.user_type');
        //dd($userType );

		if ($id == "") {
			return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'id', 'userRoleId', 'today_meeting_data', 'userType'));
		} elseif ($id == 1) {
			if ($userRoleId == 551) {
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt, a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no  
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $date_cond $tranfer_condition");

			} else {

				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT
					MAX(lead_followup_pk_no)
					FROM
					t_leadfollowup c
					WHERE
					a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) and (a.meeting_date != '$today_date' OR a.`meeting_date` IS NULL)  $user_cond $date_cond $tranfer_condition
					AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL) and b.lead_current_stage not in(6,7,9) order by b.created_at desc");


				$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE lead_current_stage not in(7) and a.meeting_date = '$today_date' $date_cond $user_cond $tranfer_condition");

			}
			return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'id', 'userRoleId', 'today_meeting_data', 'userType'));
		} elseif ($id == 2) {
			if ($userRoleId == 551) {
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $date_cond ");

			} else {
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON (a.lead_pk_no=b.lead_pk_no )
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no 
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE a.lead_followup_pk_no = (SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no) $user_cond $date_cond
					AND (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL)
					and b.lead_current_stage not in(6,7,9)");
			}
			return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'id', 'userRoleId', 'userType'));

		} elseif ($id == 3) {
			if ($userRoleId == 551) {
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $date_cond");
				$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE lead_current_stage not in(7) and a.meeting_date > '$today_date' $date_cond $tranfer_condition");

			} else {
				$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $user_cond $date_cond");
				$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
					a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,d.user_fullname as last_followup_name,b.*
					FROM t_lead2lifecycle_vw b
					LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
					)
					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
					WHERE lead_current_stage not in(7) and a.meeting_date > '$today_date' $date_cond $user_cond $tranfer_condition");
			}

			return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'id', 'userRoleId', 'userType','today_meeting_data'));
		}

	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$create_date = date('Y-m-d');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$current_stage = ($request->hdn_cur_stage != "") ? $request->hdn_cur_stage : 0;
    	$new_stage = ($request->cmb_change_stage > 0) ? $request->cmb_change_stage : $current_stage;
    	$txt_followup_date = date("Y-m-d", strtotime($request->txt_followup_date));
    	$txt_followup_date_time = date("Y-m-d H:i:s", strtotime($request->txt_followup_date . " " . $request->txt_followup_date_time));
    	$meeting_followup_date = date("Y-m-d", strtotime($request->meeting_followup_date));
    	$meeting_followup_date_time = date("Y-m-d H:i:s", strtotime($request->meeting_followup_date . " " . $request->meeting_followup_date_time));


    	$follow_up_type = empty($request->cmbFollowupType) ? '0' : $request->cmbFollowupType;
    	$meeting_status = !empty($request->txt_meeting_status) ? $request->txt_meeting_status : "0";

    	$in_visit_meeting_done = !empty($request->meeting_visit_confirmation) ? $request->meeting_visit_confirmation : "0";

    	//txt_meeting_visit_done_dt
    	$txt_meeting_visit_done_dt = ($request->meeting_visit_confirmation == '1')? date("Y-m-d",strtotime($request->txt_meeting_visit_done_dt)):"1970-01-01";

    	$lead_followup = DB::statement(
    		DB::raw("CALL proc_leadfollowup_ins ('1','$create_date',$request->hdn_lead_pk_no,'$follow_up_type','$request->followup_note',$current_stage,1,'$txt_followup_date','$txt_followup_date_time','$request->next_followup_note',$new_stage,1,$ses_user_id,'$create_date','$meeting_status','$meeting_followup_date','$meeting_followup_date_time','$in_visit_meeting_done','$txt_meeting_visit_done_dt')")
    	);

    	if (LeadFollowUp::where('lead_followup_pk_no', '=', $request->hdn_lead_followup_pk_no)->exists()) {
    		$upd_followup = LeadFollowUp::find($request->hdn_lead_followup_pk_no);
    		$upd_followup->next_followup_flag = 0;
    		$upd_followup->save();
    	}

    	if ($new_stage > 0) {
    		DB::statement(
    			DB::raw("CALL proc_leadlifecycle_upd_stage ($request->hdn_lead_pk_no,'$create_date',$ses_user_id,$new_stage,$ses_user_id,'$request->flat_id')")
    		);
    	}

    	if ($request->cmb_change_stage == 9) {

    		$lead_lifeCycle = LeadLifeCycle::where("lead_pk_no", $request->hdn_lead_pk_no)->orderBy("leadlifecycle_pk_no", "desc")->first();
            //dd($lead_lifeCycle);

    		$lead_lifeCycle->junk_ind = 1;
    		$lead_lifeCycle->save();

    	}
    	if ($request->cmb_change_stage == 14) {
    		$lifeCycle = LeadLifeCycle::where("lead_pk_no", $request->hdn_lead_pk_no)->orderBy("leadlifecycle_pk_no", "desc")->first();
    		$lifeCycle->is_block = 1;
    		$lifeCycle->save();

    	}
    	$attribute_count = $request->attribute_type;


    	if (!empty($attribute_count)) {


    		for ($i = 0; $i < count($attribute_count); $i++) {
    			$attr_element = explode("_", $attribute_count[$i]);
    			$attr_data = DB::Table("t_leadfollowup_attribute")->where([
    				['lead_pk_no', '=', $request->hdn_lead_pk_no],
    				['attr_pk_no', '=', $attr_element[0]]
    			])->delete();


    			$textField = "text" . $attr_element[0];

                //dd( $textField);
    			$lead_attr = new LeadFollowupAttribute();
    			$lead_attr->lead_pk_no = $request->hdn_lead_pk_no;
    			$lead_attr->attr_pk_no = $attr_element[0];
    			$lead_attr->attr_type = $attr_element[1];
    			$lead_attr->attr_value = $request->input($textField);
    			$lead_attr->row_status = 1;
    			$lead_attr->save();


                //dd($textField,$request->input("text33"));
    		}
    	}

    	return response()->json(['message' => 'Lead Followup created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$page = "";
    	$lookup_data = LookupData::all();
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 17)
    			$followup_type[$key] = $value->lookup_name;
    	}

    	$lead_data = DB::select("SELECT a.*,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

    	$followup_info = DB::table("t_lead_followup_count_by_current_stage_vw")->where("lead_pk_no", $lead_data->lead_pk_no)->first();

    	$no_of_followup = LookupData::where("lookup_type", 24)->first();


    	$lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 21, 26];
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
    	$project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = $district = $thana = $area = $meeting_status = [];
    	foreach ($lookup_data as $key => $value) {
    		$key = $value->lookup_pk_no;
    		if ($value->lookup_type == 2)
    			$digital_mkt[$key] = $value->lookup_name;

    		if ($value->lookup_type == 3)
    			$hotline[$key] = $value->lookup_name;

    		if ($value->lookup_type == 4)
    			$project_cat[$key] = $value->lookup_name;

    		if ($value->lookup_type == 5)
    			$project_area[$key] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$key] = $value->lookup_name;

    		if ($value->lookup_type == 7)
    			$project_size[$key] = $value->lookup_name;

    		if ($value->lookup_type == 10)
    			$ocupations[$key] = $value->lookup_name;
    		if ($value->lookup_type == 21)
    			$area[$key] = $value->lookup_name;
    		if ($value->lookup_type == 26)
    			$meeting_status[$key] = $value->lookup_name;

    	}

    	$flat_list = FlatSetup::where('project_lookup_pk_no', $lead_data->Project_pk_no)
    	->where('category_lookup_pk_no', $lead_data->project_category_pk_no)
    	->where('size_lookup_pk_no', $lead_data->project_size_pk_no)
    	->where('flat_status', 0)
    	->where('block_status', 0)
    	->get(['flatlist_pk_no', 'flat_name']);
        // dd($no_of_followup->lookup_name);
    	$ses_user_id = Session::get('user.ses_user_pk_no');


    	$distric_arra = DB::table("districts")->get();
    	$thana_arra = DB::table("upazilas")->get();

    	foreach ($distric_arra as $key) {
    		$district[$key->id] = $key->district_name;
    	}
    	foreach ($thana_arra as $key) {
    		$thana[$key->id] = $key->thana_name;
    	}


    	$lead_transfer_data = DB::select("SELECT a.lead_pk_no,a.is_rejected,a.transfer_to_sales_agent_flag,b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, 
    		f.user_fullname from_sales_agent, g.user_fullname to_sales_agent,i.lead_transfer_flag
    		FROM t_leadtransfer a
    		LEFT JOIN t_leads j ON j.`lead_pk_no` = a.`lead_pk_no` 
    		LEFT JOIN s_lookdata b ON j.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON j.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON j.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON j.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		LEFT JOIN t_leadlifecycle i ON i.lead_pk_no = a.lead_pk_no
    		WHERE a.lead_pk_no=$id");


    	$lead_followup_data = DB::select("SELECT * from t_leadfollowup  a LEFT JOIN s_user b ON a.created_by=b.user_pk_no where lead_pk_no=$id");
    	$lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");

    	$lead_history = DB::select("SELECT * FROM t_leadshistory WHERE lead_pk_no=$id ");

    	$current_stage_attr = DB::table('t_leadstage_attribute')
    	->where('stage_id', $lead_data->lead_current_stage)
    	->where('row_status', 1)->get();

    	$current_stage_attr_data = DB::select("SELECT  a.followup_attr_pk_no,a.lead_pk_no,a.attr_pk_no,b.stage_id,a.attr_value,b.attr_name,b.attr_type
    		FROM t_leadfollowup_attribute a,t_leadstage_attribute b
    		WHERE a.attr_pk_no=b.attr_pk_no AND a.lead_pk_no='$lead_data->lead_pk_no' and b.stage_id = '$lead_data->lead_current_stage'
    		GROUP BY  a.followup_attr_pk_no,a.lead_pk_no,a.attr_pk_no,b.stage_id,a.attr_value,b.attr_name,b.attr_type");
    	$attr_val_arr = [];
    	if (!empty($current_stage_attr_data)) {
    		foreach ($current_stage_attr_data as $row) {
    			$attr_val_arr[$row->attr_pk_no] = $row->attr_value . "_" . $row->attr_type;
    		}
    	}

    	$attr_type_value = config('static_arrays.attributes_type');


    	


    	$lead_status = LookupData::where('lookup_type', 26)->get();

    	$lead_status_arr = [];
    	foreach ($lead_status as $val) {
    		$lead_status_arr[$val->lookup_pk_no] = $val->lookup_name;
    	}
    	$lead_kyc_info_history = DB::table('t_leadkychistory')->where('lead_pk_no',$id)->get();

    	return view('admin.sales_team_management.lead_followup.lead_follow_up_form', compact('lead_data', 'lead_stage_arr', 'followup_type', 'page', 'flat_list', 'followup_info', 'no_of_followup', 'ses_user_id', 'lead_history', 'lead_stage_data', 'lead_followup_data', 'lead_transfer_data', 'current_stage_attr', 'current_stage_attr_data', 'attr_type_value', 'digital_mkt', 'hotline', 'project_cat', 'project_area', 'project_name', 'project_size', 'ocupations', 'meeting_status', 'lead_transfer_data', 'lead_followup_data', 'lead_history', 'lead_status', 'attr_val_arr', 'district', 'area', 'thana', 'lead_status_arr','lead_kyc_info_history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_follow_up_from_dashboard($id, $type)
    {
    	$page = "leadlist";
    	$lookup_data = LookupData::all();
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 17)
    			$followup_type[$key] = $value->lookup_name;
    	}

    	$lead_data = DB::select("SELECT a.*,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

    	$followup_info = DB::table("t_lead_followup_count_by_current_stage_vw")->where("lead_pk_no", $lead_data->lead_pk_no)->first();

    	$no_of_followup = LookupData::where("lookup_type", 24)->first();

    	$flat_list = FlatSetup::where('project_lookup_pk_no', $lead_data->Project_pk_no)
    	->where('category_lookup_pk_no', $lead_data->project_category_pk_no)
    	->where('size_lookup_pk_no', $lead_data->project_size_pk_no)
    	->where('flat_status', 0)
    	->get(['flatlist_pk_no', 'flat_name']);


    	$lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 21, 26];
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
    	$project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = $district = $thana = $area = $meeting_status = [];
    	foreach ($lookup_data as $key => $value) {
    		$key = $value->lookup_pk_no;
    		if ($value->lookup_type == 2)
    			$digital_mkt[$key] = $value->lookup_name;

    		if ($value->lookup_type == 3)
    			$hotline[$key] = $value->lookup_name;

    		if ($value->lookup_type == 4)
    			$project_cat[$key] = $value->lookup_name;

    		if ($value->lookup_type == 5)
    			$project_area[$key] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$key] = $value->lookup_name;

    		if ($value->lookup_type == 7)
    			$project_size[$key] = $value->lookup_name;

    		if ($value->lookup_type == 10)
    			$ocupations[$key] = $value->lookup_name;
    		if ($value->lookup_type == 21)
    			$area[$key] = $value->lookup_name;
    		if ($value->lookup_type == 26)
    			$meeting_status[$key] = $value->lookup_name;

    	}


    	$distric_arra = DB::table("districts")->get();
    	$thana_arra = DB::table("upazilas")->get();

    	foreach ($distric_arra as $key) {
    		$district[$key->id] = $key->district_name;
    	}
    	foreach ($thana_arra as $key) {
    		$thana[$key->id] = $key->thana_name;
    	}


    	$lead_transfer_data = DB::select("SELECT a.lead_pk_no,a.is_rejected,a.transfer_to_sales_agent_flag,b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, 
    		f.user_fullname from_sales_agent, g.user_fullname to_sales_agent,i.lead_transfer_flag
    		FROM t_leadtransfer a
    		LEFT JOIN t_leads j ON j.`lead_pk_no` = a.`lead_pk_no` 
    		LEFT JOIN s_lookdata b ON j.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON j.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON j.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON j.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		LEFT JOIN t_leadlifecycle i ON i.lead_pk_no = a.lead_pk_no
    		WHERE a.lead_pk_no=$id");


    	$lead_followup_data = DB::select("SELECT * from t_leadfollowup a LEFT JOIN s_user b ON a.created_by=b.user_pk_no where lead_pk_no=$id");
    	$lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");

    	$lead_history = DB::select("SELECT * FROM t_leadshistory WHERE lead_pk_no=$id ");

    	$current_stage_attr = DB::table('t_leadstage_attribute')
    	->where('stage_id', $lead_data->lead_current_stage)
    	->where('row_status', 1)->get();

    	$current_stage_attr_data = DB::select("SELECT  a.followup_attr_pk_no,a.lead_pk_no,a.attr_pk_no,b.stage_id,a.attr_value,b.attr_name,b.attr_type
    		FROM t_leadfollowup_attribute a,t_leadstage_attribute b
    		WHERE a.attr_pk_no=b.attr_pk_no AND a.lead_pk_no='$lead_data->lead_pk_no' and b.stage_id = '$lead_data->lead_current_stage'
    		GROUP BY  a.followup_attr_pk_no,a.lead_pk_no,a.attr_pk_no,b.stage_id,a.attr_value,b.attr_name,b.attr_type");
    	$attr_val_arr = [];
    	if (!empty($current_stage_attr_data)) {
    		foreach ($current_stage_attr_data as $row) {
    			$attr_val_arr[$row->attr_pk_no] = $row->attr_value . "_" . $row->attr_type;
    		}
    	}

    	$attr_type_value = config('static_arrays.attributes_type');


    	$lead_transfer_data = DB::select("SELECT a.lead_pk_no,a.is_rejected,a.transfer_to_sales_agent_flag,b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, 
    		f.user_fullname from_sales_agent, g.user_fullname to_sales_agent,i.lead_transfer_flag
    		FROM t_leadtransfer a
    		LEFT JOIN t_leads j ON j.`lead_pk_no` = a.`lead_pk_no` 
    		LEFT JOIN s_lookdata b ON j.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON j.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON j.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON j.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		LEFT JOIN t_leadlifecycle i ON i.lead_pk_no = a.lead_pk_no
    		WHERE a.lead_pk_no=$id");

    	$lead_followup_data = DB::select("SELECT * from t_leadfollowup a LEFT JOIN s_user b ON a.created_by=b.user_pk_no where lead_pk_no=$id");
    	$lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");

    	$lead_history = DB::select("SELECT * FROM t_leadshistory WHERE lead_pk_no=$id ");


    	$lead_status = LookupData::where('lookup_type', 26)->get();

    	$lead_kyc_info_history = DB::table('t_leadkychistory')->where('lead_pk_no',$id)->get();


    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	return view('admin.sales_team_management.lead_followup.lead_follow_up_form', compact('lead_data', 'lead_stage_arr', 'followup_type', 'page', 'type', 'followup_info', 'no_of_followup', 'flat_list', 'ses_user_id', 'followup_info', 'no_of_followup', 'ses_user_id', 'lead_history', 'lead_stage_data', 'lead_followup_data', 'lead_transfer_data', 'current_stage_attr', 'current_stage_attr_data', 'attr_type_value', 'digital_mkt', 'hotline', 'project_cat', 'project_area', 'project_name', 'project_size', 'ocupations', 'area', 'meeting_status', 'lead_transfer_data', 'lead_followup_data', 'lead_history', 'lead_status', 'attr_val_arr', 'district', 'thana','lead_kyc_info_history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function stage_update($id)
    {
    	$lookup_data = LookupData::all();
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 17)
    			$followup_type[$key] = $value->lookup_name;
    	}
    	$lead_data = DB::select("SELECT a.*,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

    	return view('admin.sales_team_management.lead_followup.lead_stage_update', compact('lead_data', 'lead_stage_arr', 'followup_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_stage_update(Request $request)
    {
    	$this->validate($request, [
    		'new_stage' => 'required'
    	]);

    	$create_date = date('Y-m-d');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	DB::statement(
    		DB::raw("CALL proc_leadlifecycle_upd_stage ($request->lead_pk_no,'$create_date',$ses_user_id,$request->new_stage,$ses_user_id)")
    	);

    	$lstage = new LeadStageHistory();
    	$lstage->lead_pk_no = $request->lead_pk_no;
    	$lstage->project_category_pk_no = $request->lead_category_id;
    	$lstage->project_area_pk_no = $request->lead_area_id;
    	$lstage->Project_pk_no = $request->lead_project_id;
    	$lstage->project_size_pk_no = $request->lead_size_id;
    	$lstage->lead_stage_before_update = $request->lead_cur_stage_id;
    	$lstage->lead_stage_after_update = $request->new_stage;
    	$lstage->sales_agent_pk_no = $request->sales_agent_id;
    	$lstage->save();

    	return response()->json(['message' => 'Lead Stage updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_sold($id)
    {
    	$lookup_data = LookupData::all();
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 17)
    			$followup_type[$key] = $value->lookup_name;
    	}

    	$lead_data = DB::select("SELECT a.*,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 where b.lead_pk_no=$id")[0];

    	if (!empty($lead_data)) {
    		$flat_list = FlatSetup::where('project_lookup_pk_no', $lead_data->Project_pk_no)
    		->where('category_lookup_pk_no', $lead_data->project_category_pk_no)
    		->where('size_lookup_pk_no', $lead_data->project_size_pk_no)
    		->where('flat_status', 0)
    		->where('block_status', '!=', 1)
    		->get(['flatlist_pk_no', 'flat_name']);
    	}
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	return view('admin.sales_team_management.lead_followup.lead_sold', compact('lead_data', 'lead_stage_arr', 'followup_type', 'flat_list', 'ses_user_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_lead_sold(Request $request)
    {
        /*$this->validate($request,[
            'new_stage' => 'required'
        ]);*/

        $ldata = LeadLifeCycle::findOrFail($request->leadlifecycle_id);
        $ldata->lead_current_stage = 7;
        $ldata->lead_sold_flag = 1;
        $ldata->flatlist_pk_no = $request->flat;
        $ldata->lead_sold_flatcost = $request->flat_cost;
        $ldata->lead_sold_utilitycost = $request->utility;
        $ldata->lead_sold_parkingcost = $request->parking;
        $ldata->lead_sold_date_manual = date("Y-m-d", strtotime($request->date_of_sold));
        $ldata->lead_sold_sales_agent_pk_no = $request->sales_agent_id;
        $ldata->lead_sold_team_lead_pk_no = 1;
        $ldata->lead_sold_team_manager_pk_no = 1;
        $ldata->lead_sold_agreement_status = $request->lead_sold_agreement_status;
        $ldata->lead_sold_bookingmoney = $request->lead_sold_bookingmoney;

        if ($ldata->save()) {
        	$fdata = FlatSetup::findOrFail($request->flat);
        	$fdata->flat_status = 1;
        	$fdata->save();

        	return response()->json(['message' => 'Lookup Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
        	return response()->json(['message' => 'Lookup Data update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_followup_leads(Request $request)
    {
    	$is_team_leader = Session::get('user.is_team_leader');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$is_ses_hot = Session::get('user.is_ses_hot');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$numberofdays = LookupData::where('lookup_type', 23)->orderBy('lookup_pk_no', 'desc')->first();
    	$days = $numberofdays->lookup_name;
    	$userRoleId = Session::get('user.ses_role_lookup_pk_no');
    	$tranfer_condition = "";
    	$get_all_tem_members = "";
    	if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {

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
    			//$tranfer_condition = "and (b.lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR b.lead_transfer_from_sales_agent_pk_no IS NULL ) ";
    		}
    		$get_all_team_members = rtrim(($get_all_tem_members), ", ");


    	} else {
    		$get_all_team_members = $ses_user_id;
    		//$tranfer_condition = "and (b.lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR b.lead_transfer_from_sales_agent_pk_no IS NULL ) ";
    	}
    	$userRoleId = Session::get('user.ses_role_lookup_pk_no');

    	if ($userRoleId == 551) {
    		$user_cond = '';
    	} else {
    		$user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";
    	}
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	$today_date = date("Y-m-d");
    	if ($request->tab_type == 1) {

    		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,a.followup_Note,
    			a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    			FROM t_lead2lifecycle_vw b
    			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
    			)
    			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    			LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    			WHERE lead_current_stage not in(6,7,9) and (a.meeting_date != '$today_date' OR a.`meeting_date` IS NULL) $tranfer_condition  $user_cond order by b.created_at desc");

    		$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
    			a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    			FROM t_lead2lifecycle_vw b
    			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
    			)
    			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    			LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    			WHERE lead_current_stage not in(7) and a.meeting_date = '$today_date'  $tranfer_condition  $user_cond");


    		return view('admin.sales_team_management.lead_followup.lead_today_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'userRoleId', 'today_meeting_data'));
    	}
    	if ($request->tab_type == 2) {
    		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
    			a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    			FROM t_lead2lifecycle_vw b
    			LEFT JOIN t_leadfollowup a ON (a.lead_pk_no=b.lead_pk_no )
    			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    			LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    			WHERE a.lead_followup_pk_no = (SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no) $tranfer_condition $user_cond 
    			AND (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL)
                and b.lead_current_stage not in(6,7,9) order by b.created_at desc");//AND a.lead_followup_datetime<CURDATE()

    		return view('admin.sales_team_management.lead_followup.lead_missed_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'userRoleId'));

    	}
    	if ($request->tab_type == 3) {
    		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
    			a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    			FROM t_lead2lifecycle_vw b
    			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
    			)
    			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    			LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    			WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) 
    			and lead_current_stage not in(6,7,9) $tranfer_condition $user_cond order by b.created_at desc");
    		$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date, a.visit_meeting_done_dt,a.followup_Note,
    			a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    			FROM t_lead2lifecycle_vw b
    			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
    			)
    			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    			LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    			WHERE lead_current_stage not in(7) and a.meeting_date > '$today_date'  $tranfer_condition  $user_cond");



    		return view('admin.sales_team_management.lead_followup.lead_next_follow_up', compact('lead_data', 'lead_stage_arr', 'days', 'ses_user_id', 'userRoleId','today_meeting_data'));
    	}
    }
}
