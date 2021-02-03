<?php

namespace App\Http\Controllers\Admin;

use App\LeadLifeCycleView;
use App\TeamAssign;
use Response;
use App\LookupData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use vendor\project\StatusTest;
use Session;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user_groups = LookupData::where('lookup_type', 1)->get();
    	$user_group = [];
    	if (!empty($user_groups)) {
    		foreach ($user_groups as $group) {
    			$user_group[$group->lookup_pk_no] = $group->lookup_name;
    		}
    	}

    	$lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();

    	$project_cat = $project_area = $project_name = $project_size = $press_adds = $hotline = $billboards = $project_boards = $flyers = $fnfs = array();
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 2)
    			$digital_mkt[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 3)
    			$hotline[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 4)
    			$project_cat[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 5)
    			$project_area[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 7)
    			$project_size[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 10)
    			$ocupations[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 11)
    			$press_adds[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 12)
    			$billboards[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 13)
    			$project_boards[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 14)
    			$flyers[$value->lookup_pk_no] = $value->lookup_name;

    		if ($value->lookup_type == 15)
    			$fnfs[$value->lookup_pk_no] = $value->lookup_name;
    	}
    	return view('admin.report_module.search_engine', compact('project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_stage_arr', 'user_group'));
    }

    function serch_result_query($request)
    {
    	$sql_cond = (trim($request->txt_customer_name) != "") ? " where customer_firstname like '%" . trim($request->txt_customer_name) . "%'" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= (trim($request->txt_mobile_no) != "") ? " $clause (phone1 like '%" . trim($request->txt_mobile_no) . "%' or phone2 like '%" . trim($request->txt_mobile_no) . "%')" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= (trim($request->txt_email) != "") ? " $clause email_id like '%" . trim($request->txt_email) . "%'" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbOccupation != "") ? " $clause occupation_pk_no=$request->cmbOccupation" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbOrganization != "") ? " $clause organization_pk_no=$request->cmbOrganization" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbCategory != "") ? " $clause project_category_pk_no=$request->cmbCategory" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbArea != "") ? " $clause project_area_pk_no=$request->cmbArea" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbProjectName != "") ? " $clause Project_pk_no=$request->cmbProjectName" : "";
    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbSize != "") ? " $clause project_size_pk_no=$request->cmbSize" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmbUserGroup > 0) ? " $clause source_auto_usergroup_pk_no=$request->cmbUserGroup" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$sql_cond .= ($request->cmb_stage > 0) ? " $clause lead_current_stage=$request->cmb_stage" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$entry_date = (trim($request->txt_entry_date) != "") ? date("Y-m-d", strtotime($request->txt_entry_date)) : "";
    	$entry_date_to = (trim($request->txt_entry_date_to) != "") ? date("Y-m-d", strtotime($request->txt_entry_date_to)) : "";
    	$sql_cond .= ($entry_date != "") ? " $clause a.created_at >='$entry_date' and a.created_at <='$entry_date_to'" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$txt_cus_dob_from = (trim($request->txt_cus_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_from)) : "";
    	$txt_cus_dob_to = (trim($request->txt_cus_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_dob_to)) : "";
    	$sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Customer_dateofbirth between '$txt_cus_dob_from' and '$txt_cus_dob_to'" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$txt_mar_date_from = (trim($request->txt_mar_date_from) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_from)) : "";
    	$txt_mar_date_to = (trim($request->txt_mar_date_to) != "") ? date("Y-m-d", strtotime($request->txt_mar_date_to)) : "";
    	$sql_cond .= ($request->txt_cus_dob_from != "") ? " $clause Marriage_anniversary between '$txt_mar_date_from' and '$txt_mar_date_to'" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$txt_cus_wife_dob_from = (trim($request->txt_cus_wife_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_from)) : "";
    	$txt_cus_wife_dob_to = (trim($request->txt_cus_wife_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_wife_dob_to)) : "";
    	$sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause customer_wife_dataofbirth between '$txt_cus_wife_dob_from' and '$txt_cus_wife_dob_to'" : "";

    	$clause = ($sql_cond != "") ? " and" : " where";
    	$txt_cus_child_dob_from = (trim($request->txt_cus_child_dob_from) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_from)) : "";
    	$txt_cus_child_dob_to = (trim($request->txt_cus_child_dob_to) != "") ? date("Y-m-d", strtotime($request->txt_cus_child_dob_to)) : "";
    	$sql_cond .= ($txt_cus_wife_dob_from != "") ? " $clause (children_dateofbirth1 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth2 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth3 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to')" : "";

    	return DB::select("SELECT a.*,c.next_followup_Note
    		FROM t_lead2lifecycle_vw a
    		LEFT JOIN (SELECT b.lead_pk_no,b.next_followup_Note,MAX(lead_followup_pk_no) AS maxid
    		FROM t_leadfollowup b GROUP BY b.lead_pk_no,b.next_followup_Note) AS c
    		ON a.lead_pk_no = c.maxid $sql_cond");


    }

    public function search_result(Request $request)
    {
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	$lead_data = $this->serch_result_query($request);
    	return view('admin.report_module.search_result', compact('lead_data', 'lead_stage_arr'));

    }

    public function export_report_stage_wise(Request $request)
    {
    	$lead_data = $this->serch_result_query($request);

    	$headers = array(
    		"Content-type" => "text/csv",
    		"Content-Disposition" => "attachment; filename=file.csv",
    		"Pragma" => "no-cache",
    		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    		"Expires" => "0"
    	);
    	$columns = [20 => "SL", 21 => "Cluster Head", 23 => "MQL", 22 => "SQL", 7 => 'Sold/On-board', 9 => 'Junk', 3 => 'Prospect', 13 => 'Higher Prospect', 4 => 'Priority', 5 => 'Hold', 6 => 'Closed', 25 => "Did Not Update", 26 => "Grand Total"];


    	$lead_stage_arr = [7 => 'Sold/On-board', 9 => 'Junk', 3 => 'Prospect', 13 => 'Higher Prospect', 4 => 'Priority', 5 => 'Hold', 6 => 'Closed'];
    	$cl_cond = ($request->cluster_head == "") ? '' : "AND s_user.user_pk_no = " . $request->cluster_head;

    	$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    		LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    		WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 $cl_cond
    		GROUP BY user_pk_no,user_fullname");
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));


    	$cluster_head_cond = ($request->cluster_head == "") ? '' : "AND a.lead_cluster_head_pk_no = " . $request->cluster_head;
    	/*$date_cond = ($request->from_date != "") ? " and a.appoint_date between '$from_date' and '$to_date'" : "";*/
    	$date_cond = ($request->from_date != "") ? " AND a.lead_cluster_head_assign_dt between '$from_date' AND '$to_date' " : "";

    	$lead_data = DB::select("SELECT lead_cluster_head_pk_no,lead_current_stage, COUNT(lead_pk_no) AS total_lead, c.`user_fullname` AS user_fullname  FROM t_lead2lifecycle_vw a 
    		JOIN s_user c ON c.user_pk_no = a.lead_cluster_head_pk_no  WHERE lead_entry_type= '$request->report_type' $cluster_head_cond $date_cond  
    		GROUP BY lead_cluster_head_pk_no,lead_current_stage, user_fullname");

    	$lead_source_count = DB::select("SELECT lead_cluster_head_pk_no, COUNT(lead_pk_no) AS total_lead  FROM t_lead2lifecycle_vw a 
    		JOIN s_user c ON c.user_pk_no = a.lead_cluster_head_pk_no  WHERE lead_entry_type= '$request->report_type' $cluster_head_cond $date_cond  
    		GROUP BY lead_cluster_head_pk_no");
        //dd($lead_source_count);

    	$cluster_head_wise_count = [];
    	$stage_wise_count = [];
    	if (!empty($lead_source_count)) {
    		foreach ($lead_source_count as $value) {
    			$cluster_head_wise_count[$value->lead_cluster_head_pk_no] = $value->total_lead;
    		}
    	}
    	$stage_wise_count = [];
    	if (!empty($lead_data)) {
    		foreach ($lead_data as $data) {
    			$stage_wise_count[$data->lead_cluster_head_pk_no][$data->lead_current_stage] = $data->total_lead;


    		}
    	}
    	$did_not_count = DB::select("SELECT COUNT(lead_pk_no) AS did_not_count,lead_cluster_head_pk_no FROM  t_lead2lifecycle_vw ` t_lead_followup_count_by_current_stage_vw`WHERE lead_sales_agent_pk_no = 0 and lead_entry_type='$request->report_type' GROUP BY lead_cluster_head_pk_no");
    	$did_not_count_arr = [];
    	if (!empty($did_not_count)) {
    		foreach ($did_not_count as $count) {
                # code...
    			$did_not_count_arr[$count->lead_cluster_head_pk_no] = $count->did_not_count;
    		}
    	}

    	$stage_wise_cluster_head = [];

    	if (!empty($cluster_head)) {
    		foreach ($cluster_head as $data) {
    			$stage_wise_cluster_head[$data->user_pk_no] = $data->user_fullname;


    		}
    	}

    	$callback = function () use ($lead_data, $columns, $stage_wise_cluster_head, $stage_wise_count, $did_not_count_arr, $lead_stage_arr, $cluster_head_wise_count) {

    		$file = fopen('php://output', 'w');
    		fputcsv($file, $columns);
    		$iteration = 1;

    		if (!empty($stage_wise_cluster_head)) {
    			foreach ($stage_wise_cluster_head as $key => $data) {
    				$sum = 0;
    				$row = [];
    				$store = isset($stage_wise_count[$key][3]) ? $stage_wise_count[$key][3] : 0;
    				array_push($row, $iteration);
    				array_push($row, $data);
    				array_push($row, isset($cluster_head_wise_count[$key]) ? $cluster_head_wise_count[$key] : 0);
    				array_push($row, $store);
    				foreach ($lead_stage_arr as $stage_id => $stage) {
    					$store = isset($stage_wise_count[$key][$stage_id]) ? $stage_wise_count[$key][$stage_id] : 0;
    					array_push($row, $store);


    					$sum += $store;
    				}

    				$store = isset($did_not_count_arr[$key]) ? $did_not_count_arr[$key] : 0;
    				array_push($row, $store);
    				$sum += $store;
    				array_push($row, $sum);
    				fputcsv($file, $row, ',', '"');
    				$iteration++;

    			}
    		}


    		fclose($file);
    	};

    	return Response::stream($callback, 200, $headers);
    }


    public function stage_wise_user_report()
    {

    	$lead_source = LookupData::where("lookup_type", 29)->get();
    	$lead_stage_arr = [7 => 'Sold/On-board',  4 => 'Priority',13 => 'Higher Prospect',  3 => 'Prospect',  9 => 'Junk', 6 => 'Closed',1 => 'Lead'];

    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');
    	$team_cond = "";

    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    			$team_cond = "where hod_user_pk_no = $ses_user_id";

    		} else {
    			$cluster_head = [];
    		}

    	}
    	$lookup_arr = [29, 6, 18];
    	/*$lead_stage_arr = config('static_arrays.lead_stage_arr');*/
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();

    	$project_name = $team_name = $source = array();
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 29)
    			$source[$value->lookup_id] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$value->lookup_pk_no] = $value->lookup_name;
    	}
    	$team_name_arr = DB::select("select t_teambuild.team_lookup_pk_no,t_teambuild.hod_user_pk_no,s_lookdata.lookup_name  from t_teambuild 
    		join s_lookdata on s_lookdata.lookup_pk_no = t_teambuild.team_lookup_pk_no $team_cond group by  
    		t_teambuild.hod_user_pk_no,s_lookdata.lookup_name,t_teambuild.team_lookup_pk_no");
    	$cluster_head_wise_count = [];


    	$report_name = "MQL";

    	return view("admin.report_module.stage_wise_user_report", compact("cluster_head", "lead_stage_arr", "lead_source", "report_name", "cluster_head_wise_count","team_name_arr", "project_name", "source", "lead_stage_arr"));
    }

    public function stage_wise_user_report_result(Request $request)
    {

    	$lead_stage_arr = [7 => 'Sold/On-board',  4 => 'Priority',13 => 'Higher Prospect',  3 => 'Prospect',  9 => 'Junk', 6 => 'Closed',1 => 'Lead'];
        //$lead_data = $this->serch_result_query($request);
    	$cl_cond = ($request->cluster_head == "") ? '' : "AND s_user.user_pk_no = " . $request->cluster_head;
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    	$team_name = $request->team_name;
    	$project_name = $request->project_name;
    	$stage_name = $request->stage;
    	$source = $request->source;

    	$team_cond = ($request->team_name != "") ? "and team_lookup_pk_no = $team_name" : "";
    	$project_cond = ($request->project_name != "") ? "and project_area_pk_no = $project_name" : "";
    	$stage_cond = ($request->stage != "") ? "and lead_current_stage = $stage_name" : "";
    	$source_cond = ($request->source != "") ? "and lead_entry_type = $source" : "";
    	$get_all_tem_members = '';

    	if ($is_super == 1 || $userRoleID == 551) {
    		if (empty($request->cluster_head)) {
    			$team_cond = ($request->team_name != "") ? "where team_lookup_pk_no = $team_name" : "";

    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1
    				GROUP BY user_pk_no,user_fullname");
    			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild 
    				$team_cond")[0]->team_members;
    			$get_all_tem_members .= $get_all_tem_memberss;

    		} else {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $request->cluster_head
    				GROUP BY user_pk_no,user_fullname");
    			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild where hod_user_pk_no = '$request->cluster_head'
    				$team_cond")[0]->team_members;
    			$get_all_tem_members .= $get_all_tem_memberss;
    		}

    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild where hod_user_pk_no = '$request->cluster_head'
    				$team_cond")[0]->team_members;
    			$get_all_tem_members .= $get_all_tem_memberss;

    		} else {
    			$cluster_head = [];
    		}

    	}


    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));



    	/*$date_cond = ($request->from_date != "") ? " and a.appoint_date between '$from_date' and '$to_date'" : "";*/
    	$date_cond = ($request->from_date != "") ? " AND a.lead_cluster_head_assign_dt between '$from_date' AND '$to_date' " : "";


    	$lead_data = DB::select("SELECT lead_cluster_head_pk_no,lead_current_stage, COUNT(lead_pk_no) AS total_lead, c.`user_fullname` AS user_fullname  FROM t_lead2lifecycle_vw a 
    		JOIN s_user c ON c.user_pk_no = a.lead_cluster_head_pk_no  WHERE lead_entry_type= '$request->report_type' and (lead_cluster_head_pk_no in ($get_all_tem_members) and lead_sales_agent_pk_no in ($get_all_tem_members)) $date_cond $project_cond $stage_cond $source_cond 
    		GROUP BY lead_cluster_head_pk_no,lead_current_stage, user_fullname");

    	$lead_source_count = DB::select("SELECT lead_cluster_head_pk_no, COUNT(lead_pk_no) AS total_lead,lead_current_stage  FROM t_lead2lifecycle_vw a  WHERE lead_entry_type= '$request->report_type' and (lead_cluster_head_pk_no in ($get_all_tem_members) and lead_sales_agent_pk_no in ($get_all_tem_members)) $date_cond  $project_cond $stage_cond $source_cond
    		GROUP BY lead_cluster_head_pk_no,lead_current_stage");

    	$date1 = Carbon::parse($from_date);
    	$date2 = Carbon::parse($to_date);

    	$diff = $date1->diffInDays($date2);
    	$diff = $diff + 1;
    	$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
    	$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
    	$lead_source_com = DB::select("SELECT lead_cluster_head_pk_no, COUNT(lead_pk_no) AS total_lead,lead_current_stage  FROM t_lead2lifecycle_vw a  WHERE lead_entry_type= '$request->report_type' and (lead_cluster_head_pk_no in ($get_all_tem_members) and lead_sales_agent_pk_no in ($get_all_tem_members)) $date_cond  $project_cond $stage_cond $source_cond
    		GROUP BY lead_cluster_head_pk_no,lead_current_stage");


    	echo "SELECT lead_cluster_head_pk_no, COUNT(lead_pk_no) AS total_lead,lead_current_stage  FROM t_lead2lifecycle_vw a WHERE lead_entry_type= '$request->report_type' and (lead_cluster_head_pk_no in ($get_all_tem_members) and lead_sales_agent_pk_no in ($get_all_tem_members)) $date_cond  $project_cond $stage_cond $source_cond
    	GROUP BY lead_cluster_head_pk_no,lead_current_stage";

    	$lead_source_arr = [];
    	if(!empty($lead_source_count)){
    		foreach ($lead_source_count as  $value) {
    			$lead_source_arr[$value->lead_cluster_head_pk_no][$value->lead_current_stage] =$value->total_lead;
    		}
    	}
    	$lead_source_cmp_arr = [];
    	if(!empty($lead_source_com)){
    		foreach ($lead_source_com as  $value) {
    			$lead_source_cmp_arr[$value->lead_cluster_head_pk_no][$value->lead_current_stage] =$value->total_lead;
    		}
    	}
    	



    	$report_name = '';
    	if ($request->report_type == 1) {
    		$report_name = "MQL";
    	} else if ($request->report_type == 2) {
    		$report_name = "Walk In";
    	} else if ($request->report_type == 3) {
    		$report_name = "SGL";
    	}
//dd($cluster_head_wise_count);
    	$cluster_head1 = $cluster_head;
    	return view('admin.report_module.stage_wise_user_report_result', compact('lead_data', 'lead_stage_arr', 'report_name','lead_source_count','lead_source_arr','cluster_head1','lead_source_cmp_arr','from_date','to_date','pre_date'));
    }


    public function daily_lead_report()
    {
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');
    	$team_cond = "";


    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");


    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    			$team_cond = "where hod_user_pk_no = $ses_user_id";
    		} else {
    			$cluster_head = [];
    		}

    	}

    	$lookup_arr = [29, 6, 18];
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();

    	$project_name = $team_name = $source = array();
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 29)
    			$source[$value->lookup_id] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$value->lookup_pk_no] = $value->lookup_name;
    	}
    	$team_name_arr = DB::select("select t_teambuild.team_lookup_pk_no,t_teambuild.hod_user_pk_no,s_lookdata.lookup_name  from t_teambuild 
    		join s_lookdata on s_lookdata.lookup_pk_no = t_teambuild.team_lookup_pk_no $team_cond group by  
    		t_teambuild.hod_user_pk_no,s_lookdata.lookup_name,t_teambuild.team_lookup_pk_no");

    	$cluster_head_wise_count = [];


    	return view("admin.report_module.daily_lead_report", compact("cluster_head", "team_name_arr", "project_name", "source", "lead_stage_arr"));
    }

    public function daily_lead_report_result(Request $request)
    {
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$team_name = $request->team_name;
    	$project_name = $request->project_name;
    	$stage_name = $request->stage;
    	$source = $request->source;
    	$daily_report_data = [];
    	$daily_report_com = [];

    	$get_all_tem_members = '';
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	$team_cond = ($request->team_name != "") ? "and team_lookup_pk_no = $team_name" : "";
    	$project_cond = ($request->project_name != "") ? "and project_area_pk_no = $project_name" : "";
    	$stage_cond = ($request->stage != "") ? "and lead_current_stage = $stage_name" : "";
    	$source_cond = ($request->source != "") ? "and lead_entry_type = $source" : "";
    	$lead_data_com = [];

    	if (empty($request->cluster_head)) {

    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');


    		if ($is_super == 1 || $userRoleID == 551) {
    			$team_cond = ($request->team_name != "") ? "where team_lookup_pk_no = $team_name" : "";

    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");
    			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild 
    				$team_cond")[0]->team_members;
    			$get_all_tem_members .= $get_all_tem_memberss;


    			$lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond and
    				(lead_sales_agent_pk_no in ($get_all_tem_members) and lead_cluster_head_pk_no in ($get_all_tem_members))
    				$project_cond $stage_cond $source_cond
    				order by created_at desc, lead_pk_no desc");


    			if ($request->from_date != '') {
    				$date1 = Carbon::parse($from_date);
    				$date2 = Carbon::parse($to_date);

    				$diff = $date1->diffInDays($date2);
    				$diff = $diff + 1;
    				$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
    				$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
    				$lead_data_com = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond and
    					(lead_sales_agent_pk_no in ($get_all_tem_members) and lead_cluster_head_pk_no in ($get_all_tem_members))
    					$project_cond $stage_cond $source_cond
    					order by created_at desc, lead_pk_no desc");

    			}
    			echo "SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond and
    			(lead_sales_agent_pk_no in ($get_all_tem_members) and lead_cluster_head_pk_no in ($get_all_tem_members))
    			$project_cond $stage_cond $source_cond
    			order by created_at desc, lead_pk_no desc";


    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");
    				$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild 
    					WHERE (team_lead_user_pk_no=$ses_user_id
    					OR hod_user_pk_no=$ses_user_id OR 
    					hot_user_pk_no=$ses_user_id ) $team_cond")[0]->team_members;
                    /*  echo "SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild
                                      WHERE (team_lead_user_pk_no=$ses_user_id
                                       OR hod_user_pk_no=$ses_user_id OR
                                       hot_user_pk_no=$ses_user_id ) $team_cond";die;*/


                                       $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                                       /*dd($get_all_tem_memberss);    */


                                       $lead_data = DB::table('t_lead2lifecycle_vw')
                                       ->whereRaw(" (lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or
                                       	lead_cluster_head_pk_no in(" . $get_all_tem_members . ")) " . $date_cond . " and lead_current_stage not in(6,7,9) " . $source_cond . "" . $stage_cond . "" . $project_cond . "")
                                       ->orderBy("created_at", "desc")
                                       ->get();
                                       if ($request->from_date != '') {
                                       	$date1 = Carbon::parse($from_date);
                                       	$date2 = Carbon::parse($to_date);

                                       	$diff = $date1->diffInDays($date2);
                                       	$diff = $diff + 1;
                                       	$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
                                       	$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
                                       	$lead_data_com = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond and
                                       		(lead_sales_agent_pk_no in ($get_all_tem_members) and lead_cluster_head_pk_no in ($get_all_tem_members))
                                       		$project_cond $stage_cond $source_cond
                                       		order by created_at desc, lead_pk_no desc");

                                       }

                                   } else {
                                   	$cluster_head = [];
                                   }
                               }
                               if (!empty($lead_data)) {
                               	foreach ($lead_data as $lead) {
                               		if (isset($daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
                               			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
                               		} else {
                               			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
                               		}
                               	}
                               }
                               if (!empty($lead_data_com)) {
                               	foreach ($lead_data_com as $lead) {
                               		if (isset($daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
                               			$daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
                               		} else {
                               			$daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
                               		}
                               	}
                               }


                           } else {
                           	$ses_user_id = $request->cluster_head;
                           	$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
                           		LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
                           		WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
                           		and `s_user`.`user_pk_no`= '$request->cluster_head' GROUP BY user_pk_no,user_fullname");
            /*
$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild
WHERE (hod_user_pk_no= $request->cluster_head)")[0]->team_members;*/


$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM 
	t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id
	OR hot_user_pk_no=$ses_user_id ) $team_cond")[0]->team_members;

$get_all_tem_members .= $get_all_tem_memberss;
/*dd($get_all_tem_memberss);    */


$lead_data = DB::table('t_lead2lifecycle_vw')
->whereRaw(" (lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . ")) " . $date_cond . " and lead_current_stage not in(6,7,9) 
	" . $source_cond . "" . $stage_cond . "" . $project_cond . " ")
->orderBy("created_at", "desc")
->get();
if ($request->from_date != '') {
	$date1 = Carbon::parse($from_date);
	$date2 = Carbon::parse($to_date);

	$diff = $date1->diffInDays($date2);
	$diff = $diff + 1;
	$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
	$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
	$lead_data_com = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond and
		(lead_sales_agent_pk_no in ($get_all_tem_members) and lead_cluster_head_pk_no in ($get_all_tem_members))
		$project_cond $stage_cond $source_cond
		order by created_at desc, lead_pk_no desc");

}

if (!empty($lead_data)) {
	foreach ($lead_data as $lead) {
		if (isset($daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
		} else {
			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
		}
	}
}

if (!empty($lead_data_com)) {
	foreach ($lead_data_com as $lead) {
		if (isset($daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
			$daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
		} else {
			$daily_report_com[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
		}
	}
}
}

return view('admin.report_module.daily_lead_report_result', compact('cluster_head', 'daily_report_data', 'daily_report_com', 'from_date', 'to_date', 'pre_date'));
}

public function export_daily_report(Request $request)
{
	$headers = array(
		"Content-type" => "text/csv",
		"Content-Disposition" => "attachment; filename=file.csv",
		"Pragma" => "no-cache",
		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
		"Expires" => "0"
	);
	$columns = array("SL", "Cluster Head", "MQL", "Walk-In", "SGL", "Grand Total");
	$from_date = date("Y-m-d", strtotime($request->from_date));
	$to_date = date("Y-m-d", strtotime($request->to_date));
	$daily_report_data = [];
	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";

	if (empty($request->cluster_head)) {

		$is_super = Session::get('user.is_super_admin');
		$ses_user_id = Session::get('user.ses_user_pk_no');
		$is_ses_hod = Session::get('user.is_ses_hod');
		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

		if ($is_super == 1 || $userRoleID == 551) {
			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
				GROUP BY user_pk_no,user_fullname");
		} else {
			if ($is_ses_hod == 1) {
				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
					GROUP BY user_pk_no,user_fullname");

			} else {
				$cluster_head = [];
			}

		}
            /* foreach ($cluster_head as $cluster) {


            }*/

            $sql = $mql = $walkin = 0;
            /*$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no=$cluster->user_pk_no )")[0]->team_members;*/
            //$get_all_team_members = $get_all_team_membe r . "," . $cluster->user_pk_no;
            $lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
            	WHERE  (lead_cluster_head_pk_no is not null or lead_cluster_head_pk_no !=0)  $date_cond GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no ");
            //lead_sales_agent_pk_no IN ($get_all_team_member) OR
            if (!empty($lead_data)) {
            	foreach ($lead_data as $lead) {

            		if (isset($daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
            			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
            		} else {
            			$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
            		}
            	}
            }


        } else {
        	$sql = $mql = $walkin = 0;
        	$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
        		LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
        		WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
        		GROUP BY user_pk_no,user_fullname");
        	$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no= $request->cluster_head)")[0]->team_members;

        	$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
        		WHERE lead_cluster_head_pk_no=$request->cluster_head  $date_cond 
        		GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no");
            //lead_sales_agent_pk_no IN ($get_all_team_member) OR

        	if (!empty($lead_data)) {
        		foreach ($lead_data as $lead) {
        			if (isset($daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type])) {
        				$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type]++;
        			} else {
        				$daily_report_data[$lead->lead_cluster_head_pk_no][$lead->lead_entry_type] = 1;
        			}
        		}
        	}
        }


        $callback = function () use ($daily_report_data, $columns, $cluster_head) {

        	$file = fopen('php://output', 'w');
        	fputcsv($file, $columns);
        	$iteration = 1;


        	if (!empty($cluster_head)) {
        		foreach ($cluster_head as $cluster) {
        			$grandtotal = 0;
        			$mql = isset($daily_report_data[$cluster->user_pk_no][1]) ? $daily_report_data[$cluster->user_pk_no][1] : 0;
        			$walkin = isset($daily_report_data[$cluster->user_pk_no][2]) ? $daily_report_data[$cluster->user_pk_no][2] : 0;
        			$sql = isset($daily_report_data[$cluster->user_pk_no][3]) ? $daily_report_data[$cluster->user_pk_no][3] : 0;
        			$grandtotal = $mql + $walkin + $sql;
        			fputcsv($file, array($iteration, $cluster->user_fullname, $mql, $walkin, $sql, $grandtotal));
        		}
        	}
        	fclose($file);
        };


        return Response::stream($callback, 200, $headers);
    }

    public function export_report(Request $request)
    {
    	$lead_data = $this->serch_result_query($request);

    	$headers = array(
    		"Content-type" => "text/csv",
    		"Content-Disposition" => "attachment; filename=file.csv",
    		"Pragma" => "no-cache",
    		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    		"Expires" => "0"
    	);
    	$columns = array("Lead ID", "Entry Date", "Customer First Name", "Customer Last Name", "Country Code1", "Phone Number 1", "Country Code2", "Phone Number 2", "Email", "Occupation", "Organization", "Category", "Area", "Project Name", "Size", "Source Name", "Digital Marketing", "Emp ID", "Name", "Position", "Contact Number", "SAC Name", "SAC Note", "Hotline", "Customer DOB", "Marriage Anniversary", "Wife Name", "Wife DOB", "Children Name1", "Children DOB1", "Children Name2", "Current Stage", "Current Stage Date", "Prev. Stages", "Stage Change Dates", "Sales Agent");

    	$callback = function () use ($lead_data, $columns) {
    		$history = DB::select("SELECT lead_pk_no,GROUP_CONCAT(lead_stage_before_update ORDER BY created_at ASC) lead_stage_before_update, GROUP_CONCAT(created_at ORDER BY created_at ASC) stage_update_date 
    			FROM t_leadstagehistory
    			GROUP BY lead_pk_no");
    		$his_arr = [];
    		if (!empty($history)) {
    			foreach ($history as $his) {
    				$his_arr[$his->lead_pk_no]['lead_stage_before_update'] = $his->lead_stage_before_update;
    				$his_arr[$his->lead_pk_no]['stage_update_date'] = $his->stage_update_date;
    			}
    		}
    		$file = fopen('php://output', 'w');
    		fputcsv($file, $columns);

    		foreach ($lead_data as $ldata) {
    			$lead_stage_arr = config('static_arrays.lead_stage_arr');
    			$stages = $stage_update_date = "";
    			if (isset($his_arr[$ldata->lead_pk_no]['lead_stage_before_update'])) {
    				$lead_stage_after_update = explode(",", $his_arr[$ldata->lead_pk_no]['lead_stage_before_update']);
    				foreach ($lead_stage_after_update as $stg) {
    					if (isset($lead_stage_arr[$stg])) {
    						$stages .= $lead_stage_arr[$stg] . ",";
    					}
    				}
    			}

    			if (isset($his_arr[$ldata->lead_pk_no]['stage_update_date'])) {
    				$stage_update_date = $his_arr[$ldata->lead_pk_no]['stage_update_date'];
    			}
    			$stages = rtrim($stages, ", ");
    			$stage = isset($lead_stage_arr[$ldata->lead_current_stage]) ? $lead_stage_arr[$ldata->lead_current_stage] : '';
    			$current_stage_dt = "";
    			if ($ldata->lead_current_stage == 1) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->created_at));
    			}
    			if ($ldata->lead_current_stage == 3) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->lead_k1_datetime));
    			}
    			if ($ldata->lead_current_stage == 4) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->lead_priority_datetime));
    			}
    			if ($ldata->lead_current_stage == 5) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->lead_hold_datetime));
    			}
    			if ($ldata->lead_current_stage == 6) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->lead_closed_datetime));
    			}
    			if ($ldata->lead_current_stage == 7) {
    				$current_stage_dt = date("d/m/Y", strtotime($ldata->lead_sold_datetime));
    			}

    			fputcsv($file, array($ldata->lead_id, date("d/m/Y", strtotime($ldata->created_at)), $ldata->customer_firstname, $ldata->customer_lastname, $ldata->phone1_code, $ldata->phone1, $ldata->phone2_code, $ldata->phone2, $ldata->email_id, $ldata->occup_name, $ldata->org_name, $ldata->project_category_name, $ldata->project_area, $ldata->project_name, $ldata->project_size, $ldata->source_auto_usergroup, $ldata->source_digital_marketing, $ldata->source_ir_emp_id, $ldata->source_ir_name, $ldata->source_ir_position, $ldata->source_ir_contact_no, $ldata->source_sac_name, $ldata->source_sac_note, $ldata->source_hotline, $ldata->Customer_dateofbirth, $ldata->Marriage_anniversary, $ldata->customer_wife_name, $ldata->customer_wife_dataofbirth, $ldata->children_name1, $ldata->children_dateofbirth1, $ldata->children_name2, $stage, $current_stage_dt, $stages, $stage_update_date, $ldata->lead_sales_agent_name));
    		}
    		fclose($file);
    	};

    	return Response::stream($callback, 200, $headers);
    }

    public function source_report()
    {
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			$cluster_head = [];
    		}

    	}
    	$cluster_head_wise_count = [];
    	$lead_lookup = ['112', '531', '113', '517', '533', '115'];

        // dd("select * from s_lookdata where lookup_pk_no in implode(', ', $lead_lookup)");
    	$look_data = DB::select("select * from s_lookdata where lookup_pk_no in ('112', '531', '113', '517', '533', '115')");
    	return view("admin.report_module.source_report", compact("cluster_head", "look_data"));

    }

    public function source_report_result(Request $request)
    {
    	$look_data = DB::select("select * from s_lookdata where lookup_pk_no in ('112', '531', '113', '517', '533', '115')");
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$source_report_data = $other_source_report_data = [];
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	$lead_lookup = ['112', '113', '115', '517', '531', '533'];

    	if (empty($request->cluster_head)) {
    		$fb = $news = $sms = $website = $bill = $youtube = $other = 0;


    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    		if ($is_super == 1 || $userRoleID == 551) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");

    			} else {
    				$cluster_head = [];
    			}

    		}


    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_pk_no,source_digital_marketing FROM t_lead2lifecycle_vw 
    			where(lead_cluster_head_pk_no is not null or lead_cluster_head_pk_no !=0) and lead_current_stage not in (6,7,9)  $date_cond
    			GROUP BY lead_cluster_head_pk_no,lead_pk_no,source_digital_marketing");
    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if (in_array($lead->source_digital_marketing, $lead_lookup)) {
    					if (isset($source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing])) {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing]++;
    					} else {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing] = 1;
    					}
    				} else {
    					if (isset($other_source_report_data[$lead->lead_cluster_head_pk_no]['others'])) {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others']++;
    					} else {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others'] = 1;
    					}
    				}
    			}
    		}


    	} else {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head' 
    			GROUP BY user_pk_no,user_fullname");
    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_pk_no,source_digital_marketing FROM t_lead2lifecycle_vw 
    			WHERE lead_cluster_head_pk_no=$request->cluster_head and lead_current_stage not in (6,7,9) $date_cond
    			GROUP BY lead_cluster_head_pk_no,lead_pk_no,source_digital_marketing");

    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if (in_array($lead->source_digital_marketing, $lead_lookup)) {
    					if (isset($source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing])) {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing]++;
    					} else {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing] = 1;
    					}
    				} else {
    					if (isset($other_source_report_data[$lead->lead_cluster_head_pk_no]['others'])) {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others']++;
    					} else {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others'] = 1;
    					}
    				}
    			}
    		}

    	}
        //dd($cluster_head);
    	return view('admin.report_module.source_report_result', compact('cluster_head', "look_data", "lead_lookup", 'source_report_data', 'other_source_report_data'));
    }

    public function export_csv_source_report(Request $request)
    {
    	$headers = array(
    		"Content-type" => "text/csv",
    		"Content-Disposition" => "attachment; filename=file.csv",
    		"Pragma" => "no-cache",
    		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    		"Expires" => "0"
    	);
    	$columns = array("SL", "Cluster Head", "Facebook", "Website", "Youtube", "SMS", "Newspaper", "Billboard", "Others", "Grand Total");
    	$look_data = DB::select("select * from s_lookdata where lookup_pk_no in ('112', '531', '113', '517', '533', '115')");
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$source_report_data = [];
    	$other_source_report_data = [];
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	$lead_lookup = ['112', '113', '115', '517', '531', '533'];

    	if (empty($request->cluster_head)) {
    		$fb = $news = $sms = $website = $bill = $youtube = $other = 0;


    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    		if ($is_super == 1 || $userRoleID == 551) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");

    			} else {
    				$cluster_head = [];
    			}

    		}


    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_pk_no,source_digital_marketing FROM t_lead2lifecycle_vw 
    			where(lead_cluster_head_pk_no is not null or lead_cluster_head_pk_no !=0)  $date_cond
    			GROUP BY lead_cluster_head_pk_no,lead_pk_no,source_digital_marketing");

    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if (in_array($lead->source_digital_marketing, $lead_lookup)) {
    					if (isset($source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing])) {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing]++;
    					} else {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing] = 1;
    					}
    				} else {
    					if (isset($other_source_report_data[$lead->lead_cluster_head_pk_no]['others'])) {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others']++;
    					} else {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others'] = 1;
    					}
    				}
    			}
    		}

    	} else {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
    			GROUP BY user_pk_no,user_fullname");
    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_pk_no,source_digital_marketing FROM t_lead2lifecycle_vw 
    			WHERE lead_cluster_head_pk_no=$request->cluster_head $date_cond
    			GROUP BY lead_cluster_head_pk_no,lead_pk_no,source_digital_marketing");

    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if (in_array($lead->source_digital_marketing, $lead_lookup)) {
    					if (isset($source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing])) {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing]++;
    					} else {
    						$source_report_data[$lead->lead_cluster_head_pk_no][$lead->source_digital_marketing] = 1;
    					}
    				} else {
    					if (isset($other_source_report_data[$lead->lead_cluster_head_pk_no]['others'])) {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others']++;
    					} else {
    						$other_source_report_data[$lead->lead_cluster_head_pk_no]['others'] = 1;
    					}
    				}
    			}
    		}

    	}

    	$callback = function () use ($source_report_data, $columns, $cluster_head, $other_source_report_data, $lead_lookup) {

    		$file = fopen('php://output', 'w');
    		fputcsv($file, $columns);
    		$iteration = 1;

    		if (!empty($cluster_head)) {
    			foreach ($cluster_head as $cluster) {
    				$sum = 0;
    				$row = [];
    				array_push($row, $iteration);
    				array_push($row, $cluster->user_fullname);
    				if (!empty($source_report_data)) {
    					foreach ($lead_lookup as $look => $loopup_data) {
    						$store = isset($source_report_data[$cluster->user_pk_no][$loopup_data]) ? $source_report_data[$cluster->user_pk_no][$loopup_data] : 0;
    						$sum += $store;
    						array_push($row, $store);
    					}
    					$store = isset($other_source_report_data[$cluster->user_pk_no]['others']) ? $other_source_report_data[$cluster->user_pk_no]['others'] : 0;
    					$sum += $store;
    					array_push($row, $store);

    					array_push($row, $sum);
                        //  $rowdata = json_encode($row);
    					fputcsv($file, $row, ',', '"');

    				}
    				$iteration += 1;

    			}

    		}
    		fclose($file);
    	};


    	return Response::stream($callback, 200, $headers);

    }

    public function project_report()
    {
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');
    	$team_cond = "";


    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			$cluster_head = [];
    		}

    	}
    	$lookup_arr = [29, 6, 18];
    	$lead_stage_arr = config('static_arrays.lead_stage_arr');
    	$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();

    	$project_name = $team_name = $source = array();
    	foreach ($lookup_data as $key => $value) {
    		if ($value->lookup_type == 29)
    			$source[$value->lookup_id] = $value->lookup_name;

    		if ($value->lookup_type == 6)
    			$project_name[$value->lookup_pk_no] = $value->lookup_name;
    	}
    	$team_name_arr = DB::select("select t_teambuild.team_lookup_pk_no,t_teambuild.hod_user_pk_no,s_lookdata.lookup_name  from t_teambuild 
    		join s_lookdata on s_lookdata.lookup_pk_no = t_teambuild.team_lookup_pk_no $team_cond group by  
    		t_teambuild.hod_user_pk_no,s_lookdata.lookup_name,t_teambuild.team_lookup_pk_no");


    	$cluster_head_wise_count = [];
    	$look_data = DB::select("select * from s_lookdata where lookup_type = 6");
    	$count = count($look_data);
    	return view("admin.report_module.project_report", compact("cluster_head", "look_data", 'count',"team_name_arr", "project_name", "source", "lead_stage_arr"));
    }

    public function project_report_result(Request $request)
    {
    	$look_data = DB::select("select * from s_lookdata where lookup_type = 6");
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$team_name = $request->team_name;
    	$project_name = $request->project_name;
    	$stage_name = $request->stage;
    	$source = $request->source;

    	$get_all_tem_members = '';
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	$team_cond = ($request->team_name != "") ? "and team_lookup_pk_no = $team_name" : "";
    	$project_cond = ($request->project_name != "") ? "and project_area_pk_no = $project_name" : "";
    	$stage_cond = ($request->stage != "") ? "and lead_current_stage = $stage_name" : "";
    	$source_cond = ($request->source != "") ? "and lead_entry_type = $source" : "";
    	$lead_data_com = [];

    	$project_report_data = [];
    	$project_report_com = [];
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";

    	if (empty($request->cluster_head)) {
    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    		if ($is_super == 1 || $userRoleID == 551) {
    			$team_cond = ($request->team_name != "") ? "where team_lookup_pk_no = $team_name" : "";
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");

    			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild 
    				$team_cond")[0]->team_members;
    			$get_all_tem_members .= $get_all_tem_memberss;



    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");

    			} else {
    				$cluster_head = [];
    			}

    		}

    		$lead_data = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    			FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE (lead_cluster_head_pk_no IS NOT NULL OR lead_cluster_head_pk_no !=0)  $date_cond $source_cond  $stage_cond  $project_cond 
    			GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no");
    		if ($request->from_date != '') {
    			$date1 = Carbon::parse($from_date);
    			$date2 = Carbon::parse($to_date);

    			$diff = $date1->diffInDays($date2);
    			$diff = $diff + 1;
    			$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
    			$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
    			$lead_data_com = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    				FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE (lead_cluster_head_pk_no IS NOT NULL OR lead_cluster_head_pk_no !=0)  $date_cond $source_cond  $stage_cond  $project_cond 
    				GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no");
    		}




    		if (!empty($lead_data)) {
    			foreach ($lead_data as $data) {
    				$project_report_data[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}
    		if (!empty($lead_data_com)) {
    			foreach ($lead_data_com as $data) {
    				$project_report_com[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}



    	} else {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
    			GROUP BY user_pk_no,user_fullname");
    		$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM 
    			t_teambuild WHERE (team_lead_user_pk_no=$request->cluster_head OR hod_user_pk_no=$request->cluster_head
    			OR hot_user_pk_no=$request->cluster_head ) $team_cond")[0]->team_members;

    		$get_all_tem_members .= $get_all_tem_memberss;

    		$lead_data = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    			FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE lead_cluster_head_pk_no = $request->cluster_head $date_cond $source_cond  $stage_cond  $project_cond 
    			GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no
    			");
    		if ($request->from_date != '') {
    			$date1 = Carbon::parse($from_date);
    			$date2 = Carbon::parse($to_date);

    			$diff = $date1->diffInDays($date2);
    			$diff = $diff + 1;
    			$pre_date = date("Y-m-d", strtotime(Carbon::parse($from_date)->subDays($diff)));
    			$date_cond = " AND( lead_cluster_head_assign_dt between '$pre_date' AND '$from_date' and lead_sales_agent_assign_dt between '$pre_date' AND '$from_date')";
    			$lead_data_com = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    				FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE (lead_cluster_head_pk_no IS NOT NULL OR lead_cluster_head_pk_no !=0)  $date_cond $source_cond  $stage_cond  $project_cond GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no");
    		}


    		if (!empty($lead_data)) {
    			foreach ($lead_data as $data) {
    				$project_report_data[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}
    		if (!empty($lead_data_com)) {
    			foreach ($lead_data_com as $data) {
    				$project_report_com[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}

    	}
        //dd($project_report_data[199][611]);dd($look_data);
        // dd($look_data,$project_report_data);
    	$count = count($look_data);


    	return view('admin.report_module.project_report_result', compact('cluster_head', "look_data", 'project_report_data', 'count','project_report_com','from_date','to_date','pre_date'));
    }

    public function export_csv_project_report(Request $request)
    {

    	$headers = array(
    		"Content-type" => "text/csv",
    		"Content-Disposition" => "attachment; filename=file.csv",
    		"Pragma" => "no-cache",
    		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    		"Expires" => "0"
    	);

    	$look_data = DB::select("select * from s_lookdata where lookup_type = 6");
    	$columns = [];
    	array_push($columns, "SL");
    	array_push($columns, "Cluster Head");
    	if (!empty($look_data)) {
    		foreach ($look_data as $category_name) {
    			array_push($columns, $category_name->lookup_name);
    		}
    	}
    	array_push($columns, "Grand Total");

    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$project_report_data = [];
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	if (empty($request->cluster_head)) {
    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    		if ($is_super == 1 || $userRoleID == 551) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");

    			} else {
    				$cluster_head = [];
    			}

    		}

    		$lead_data = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    			FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE (lead_cluster_head_pk_no IS NOT NULL OR lead_cluster_head_pk_no !=0)  $date_cond 
    			GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no");
    		if (!empty($lead_data)) {
    			foreach ($lead_data as $data) {
    				$project_report_data[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}


    	} else {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
    			GROUP BY user_pk_no,user_fullname");

    		$lead_data = DB::select("SELECT  t_lead2lifecycle_vw.project_name, t_lead2lifecycle_vw.Project_pk_no , t_lead2lifecycle_vw.lead_cluster_head_pk_no , COUNT(t_lead2lifecycle_vw.lead_pk_no) AS total_lead
    			FROM t_lead2lifecycle_vw JOIN s_lookdata ON t_lead2lifecycle_vw.Project_pk_no = s_lookdata.lookup_pk_no WHERE lead_cluster_head_pk_no = $request->cluster_head $date_cond  
    			GROUP BY t_lead2lifecycle_vw.lead_cluster_head_pk_no,t_lead2lifecycle_vw.project_name,s_lookdata.lookup_pk_no
    			");


    		if (!empty($lead_data)) {
    			foreach ($lead_data as $data) {
    				$project_report_data[$data->lead_cluster_head_pk_no][$data->Project_pk_no] = $data->total_lead;
    			}
    		}
    	}


    	$callback = function () use ($project_report_data, $columns, $cluster_head, $look_data) {

    		$file = fopen('php://output', 'w');
    		fputcsv($file, $columns);
    		$iteration = 1;

    		if (!empty($cluster_head)) {
    			foreach ($cluster_head as $cluster) {
    				$sum = 0;
    				$row = [];
    				array_push($row, $iteration);
    				array_push($row, $cluster->user_fullname);
    				if (!empty($project_report_data)) {
    					foreach ($look_data as $project) {
    						$store = isset($project_report_data[$cluster->user_pk_no][$project->lookup_pk_no]) ? $project_report_data[$cluster->user_pk_no][$project->lookup_pk_no] : 0;
    						$sum += $store;
    						array_push($row, $store);
    					}
    					array_push($row, $sum);
                        //  $rowdata = json_encode($row);
    					fputcsv($file, $row, ',', '"');

    				}
    				$iteration += 1;

    			}

    		}
    		fclose($file);
    	};


    	return Response::stream($callback, 200, $headers);
    }

    public function monthly_lead_report()
    {
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			$cluster_head = [];
    		}

    	}
    	$cluster_head_wise_count = [];
    	return view("admin.report_module.monthly_lead_report", compact("cluster_head"));
    }

    public function monthly_lead_report_result(Request $request)
    {
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$monthly_lead_report = [];
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";
    	if (empty($request->cluster_head)) {

    		$is_super = Session::get('user.is_super_admin');
    		$ses_user_id = Session::get('user.ses_user_pk_no');
    		$is_ses_hod = Session::get('user.is_ses_hod');
    		$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    		if ($is_super == 1 || $userRoleID == 551) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			if ($is_ses_hod == 1) {
    				$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    					LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    					WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    					GROUP BY user_pk_no,user_fullname");

    			} else {
    				$cluster_head = [];
    			}

    		}
    		foreach ($cluster_head as $cluster) {
    			$sql = $mql = $walkin = 0;
    			$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no=$cluster->user_pk_no )")[0]->team_members;
                //$get_all_team_members = $get_all_team_membe r . "," . $cluster->user_pk_no;
    			$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
    				WHERE lead_sales_agent_pk_no IN ($get_all_team_member) OR lead_cluster_head_pk_no = $cluster->user_pk_no  $date_cond GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no ");
    			if (!empty($lead_data)) {
    				foreach ($lead_data as $lead) {
    					if ($lead->lead_entry_type == 1) {
    						$mql = $mql + 1;
    					}
    					if ($lead->lead_entry_type == 2) {
    						$walkin = $walkin + 1;
    					}
    					if ($lead->lead_entry_type == 3) {
    						$sql = $sql + 1;
    					}
    				}
    			}
    			$monthly_lead_report[$cluster->user_pk_no][1] = $mql;
    			$monthly_lead_report[$cluster->user_pk_no][2] = $walkin;
    			$monthly_lead_report[$cluster->user_pk_no][3] = $sql;

    		}


    	} else {
    		$sql = $mql = $walkin = 0;
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
    			GROUP BY user_pk_no,user_fullname");
    		$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no= $request->cluster_head)")[0]->team_members;
            //$get_all_team_members = $get_all_team_membe r . "," . $cluster->user_pk_no;
    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
    			WHERE lead_sales_agent_pk_no IN ($get_all_team_member) OR lead_cluster_head_pk_no =$request->cluster_head  $date_cond 
    			GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no");

    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if ($lead->lead_entry_type == 1) {
    					$mql = $mql + 1;
    				}
    				if ($lead->lead_entry_type == 2) {
    					$walkin = $walkin + 1;
    				}
    				if ($lead->lead_entry_type == 3) {
    					$sql = $sql + 1;
    				}
    			}
    		}
    		$monthly_lead_report[$request->cluster_head][1] = $mql;
    		$monthly_lead_report[$request->cluster_head][2] = $walkin;
    		$monthly_lead_report[$request->cluster_head][3] = $sql;
    	}
    	return view('admin.report_module.monthly_lead_report_result', compact('cluster_head', 'monthly_lead_report'));
    }

    public function export_monthly_lead_report(Request $request)
    {
    	$headers = array(
    		"Content-type" => "text/csv",
    		"Content-Disposition" => "attachment; filename=file.csv",
    		"Pragma" => "no-cache",
    		"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
    		"Expires" => "0"
    	);
    	$columns = array("SL", "Cluster Head", "MQL", "Walk-In", "SGL", "Grand Total", "Visit Done", "Customer Meet", "Unit Confirmation Done", "Sold");
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));
    	$date_cond = ($request->from_date != "") ? " AND( lead_cluster_head_assign_dt between '$from_date' AND '$to_date' and lead_sales_agent_assign_dt between '$from_date' AND '$to_date')" : "";


    	if (empty($request->cluster_head)) {

    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    		foreach ($cluster_head as $cluster) {
    			$sql = $mql = $walkin = 0;
    			$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no=$cluster->user_pk_no )")[0]->team_members;
                //$get_all_team_members = $get_all_team_membe r . "," . $cluster->user_pk_no;
    			$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
    				WHERE lead_sales_agent_pk_no IN ($get_all_team_member) OR lead_cluster_head_pk_no = $cluster->user_pk_no  $date_cond GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no ");
    			if (!empty($lead_data)) {
    				foreach ($lead_data as $lead) {
    					if ($lead->lead_entry_type == 1) {
    						$mql = $mql + 1;
    					}
    					if ($lead->lead_entry_type == 2) {
    						$walkin = $walkin + 1;
    					}
    					if ($lead->lead_entry_type == 3) {
    						$sql = $sql + 1;
    					}
    				}
    			}
    			$daily_report_data[$cluster->user_pk_no][1] = $mql;
    			$daily_report_data[$cluster->user_pk_no][2] = $walkin;
    			$daily_report_data[$cluster->user_pk_no][3] = $sql;

    		}


    	} else {
    		$sql = $mql = $walkin = 0;
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 and `s_user`.`user_pk_no`= '$request->cluster_head'  
    			GROUP BY user_pk_no,user_fullname");
    		$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (hod_user_pk_no= $request->cluster_head)")[0]->team_members;
            //$get_all_team_members = $get_all_team_membe r . "," . $cluster->user_pk_no;
    		$lead_data = DB::select("SELECT lead_cluster_head_pk_no, lead_entry_type, lead_pk_no FROM t_lead2lifecycle_vw 
    			WHERE lead_sales_agent_pk_no IN ($get_all_team_member) OR lead_cluster_head_pk_no =$request->cluster_head  $date_cond 
    			GROUP BY lead_cluster_head_pk_no,lead_entry_type,lead_pk_no");

    		if (!empty($lead_data)) {
    			foreach ($lead_data as $lead) {
    				if ($lead->lead_entry_type == 1) {
    					$mql = $mql + 1;
    				}
    				if ($lead->lead_entry_type == 2) {
    					$walkin = $walkin + 1;
    				}
    				if ($lead->lead_entry_type == 3) {
    					$sql = $sql + 1;
    				}
    			}
    		}
    		$daily_report_data[$request->cluster_head][1] = $mql;
    		$daily_report_data[$request->cluster_head][2] = $walkin;
    		$daily_report_data[$request->cluster_head][3] = $sql;
    	}

    	$callback = function () use ($daily_report_data, $columns, $cluster_head) {

    		$file = fopen('php://output', 'w');
    		fputcsv($file, $columns);
    		$iteration = 1;

    		if (!empty($cluster_head)) {
    			foreach ($cluster_head as $cluster) {
    				if (!empty($daily_report_data)) {
    					fputcsv($file, array($iteration, $cluster->user_fullname, $daily_report_data[$cluster->user_pk_no][1], $daily_report_data[$cluster->user_pk_no][2], $daily_report_data[$cluster->user_pk_no][3], $daily_report_data[$cluster->user_pk_no][1] + $daily_report_data[$cluster->user_pk_no][2] + $daily_report_data[$cluster->user_pk_no][3], " ", " ", " ", " "));
    					$iteration += 1;
    				}
    			}
    		}
    		fclose($file);
    	};


    	return Response::stream($callback, 200, $headers);
    }


    public function personal_lead_report()
    {

    	$lead_source = LookupData::where("lookup_type", 29)->get();
    	$lead_stage_arr = [7 => 'Sold/On-board', 9 => 'Junk', 3 => 'Prospect', 13 => 'Higher Prospect', 4 => 'Priority', 5 => 'Hold', 6 => 'Closed'];
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');

    	if ($is_super == 1 || $userRoleID == 551) {
    		$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    			LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    			WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1 
    			GROUP BY user_pk_no,user_fullname");
    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");
    		} else {
    			$cluster_head = [];
    		}

    	}
    	$cluster_head_wise_count = [];
    	$report_name = "MQL";
    	$lead_stage_arr = [7 => 'Sold/On-board', 9 => 'Junk', 3 => 'Prospect', 13 => 'Higher Prospect', 4 => 'Priority', 5 => 'Hold', 6 => 'Closed'];
    	return view("admin.report_module.personal_report", compact("cluster_head", "lead_source", "report_name", "lead_stage_arr"));
    }


    public function personal_lead_report_result(Request $request)
    {

    	$lead_stage_arr = [7 => 'Sold/On-board', 9 => 'Junk', 3 => 'Prospect', 13 => 'Higher Prospect', 4 => 'Priority', 5 => 'Hold', 6 => 'Closed'];
        //$lead_data = $this->serch_result_query($request);
    	$cl_cond = ($request->cluster_head == "") ? '' : "AND s_user.user_pk_no = " . $request->cluster_head;
    	$is_super = Session::get('user.is_super_admin');
    	$ses_user_id = Session::get('user.ses_user_pk_no');
    	$is_ses_hod = Session::get('user.is_ses_hod');
    	$userRoleID = Session::get('user.ses_role_lookup_pk_no');
    	$from_date = date("Y-m-d", strtotime($request->from_date));
    	$to_date = date("Y-m-d", strtotime($request->to_date));


    	$cluster_head_cond = ($request->cluster_head == "") ? '' : "AND a.lead_cluster_head_pk_no = " . $request->cluster_head;
    	/*$date_cond = ($request->from_date != "") ? " and a.appoint_date between '$from_date' and '$to_date'" : "";*/
    	$date_cond = ($request->from_date != "") ? " AND a.lead_cluster_head_assign_dt between '$from_date' AND '$to_date' " : "";
    	$cluster_head = [];

    	if ($is_super == 1 || $userRoleID == 551) {
    		if (empty($request->cluster_head)) {

    			$sales_agent_arr = DB::table("t_teambuild")
    			->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
    			->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
    			->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
    			->whereRaw("t_teambuild.hot_flag = 1 or t_teambuild.hod_flag = 1 or t_teambuild.team_lead_flag = 1 ")
    			->where('t_teambuild.agent_type', 2)->get();


    			$sales_agent_info = [];
    			if (!empty($sales_agent_arr)) {
    				foreach ($sales_agent_arr as $value) {
    					$sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
    				}
    			}


    		} else {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $request->cluster_head
    				GROUP BY user_pk_no,user_fullname");

    		}

    	} else {
    		if ($is_ses_hod == 1) {
    			$cluster_head = DB::select("SELECT `s_user`.`user_pk_no`, `s_user`.`user_fullname` FROM `s_user` 
    				LEFT JOIN `t_teambuild` ON `t_teambuild`.`user_pk_no` = `s_user`.`user_pk_no` 
    				WHERE `t_teambuild`.`hod_flag` = 1 AND `t_teambuild`.`agent_type` = 2  AND `s_user`.`row_status` = 1  and `s_user`.`user_pk_no` = $ses_user_id
    				GROUP BY user_pk_no,user_fullname");


    		} else {
    			$cluster_head = [];
    		}

    	}


    	$lead_data = DB::select("SELECT lead_cluster_head_pk_no,lead_sales_agent_pk_no,lead_current_stage, COUNT(lead_pk_no) AS total_lead, c.`user_fullname` AS user_fullname  FROM t_lead2lifecycle_vw a 
    		JOIN s_user c ON (c.user_pk_no = a.lead_cluster_head_pk_no ) WHERE lead_entry_type= '$request->report_type'  $cluster_head_cond $date_cond  
    		GROUP BY lead_cluster_head_pk_no,lead_sales_agent_pk_no,lead_current_stage, user_fullname");

        //dd($lead_data);

    	$lead_source_count = DB::select("SELECT lead_sales_agent_pk_no, COUNT(lead_pk_no) AS total_lead  FROM t_lead2lifecycle_vw a 
    		JOIN s_user c ON c.user_pk_no = a.lead_sales_agent_pk_no  WHERE lead_entry_type= '$request->report_type' $cluster_head_cond $date_cond  
    		GROUP BY lead_sales_agent_pk_no");
        //dd($lead_source_count);

    	$cluster_head_wise_count = [];
    	$stage_wise_count = [];
    	if (!empty($lead_source_count)) {
    		foreach ($lead_source_count as $value) {
    			$cluster_head_wise_count[$value->lead_sales_agent_pk_no] = $value->total_lead;
    		}
    	}
        //  dd($cluster_head_wise_count);

    	if (!empty($lead_data)) {
    		foreach ($lead_data as $data) {
    			$stage_wise_count[$data->lead_sales_agent_pk_no][$data->lead_current_stage] = $data->total_lead;
    		}
    	}
        //dd($stage_wise_count);
    	$did_not_count = DB::select("SELECT COUNT(lead_pk_no) AS did_not_count,lead_cluster_head_pk_no FROM  t_lead2lifecycle_vw ` t_lead_followup_count_by_current_stage_vw`WHERE lead_sales_agent_pk_no = 0 and lead_entry_type='$request->report_type'  GROUP BY lead_cluster_head_pk_no");
    	$did_not_count_arr = [];
    	if (!empty($did_not_count)) {
    		foreach ($did_not_count as $count) {
                # code...
    			$did_not_count_arr[$count->lead_cluster_head_pk_no] = $count->did_not_count;
    		}
    	}
    	$get_all_tem_members = '';
    	$get_all_tem_memberss = DB::select("SELECT a.user_pk_no,a.hod_user_pk_no,b.user_fullname,a.team_lookup_pk_no FROM t_teambuild a join s_user b on a.user_pk_no = b.user_pk_no");

        /*  $get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        $get_all_tem_members = implode(",", array_unique(explode(",", rtrim($get_all_tem_members, ", "))));*/


        $stage_wise_members = [];
        if (!empty($get_all_tem_memberss)) {
        	foreach ($get_all_tem_memberss as $data) {
        		$stage_wise_members[$data->user_pk_no] = $data->user_fullname;

        	}
        }


        //dd($stage_wise_count);

        $report_name = '';
        if ($request->report_type == 1) {
        	$report_name = "MQL";
        } else if ($request->report_type == 2) {
        	$report_name = "Walk In";
        } else if ($request->report_type == 3) {
        	$report_name = "SGL";
        }
        //dd($cluster_head_wise_count);

        return view('admin.report_module.personal_report_result', compact('lead_data', 'lead_stage_arr', 'stage_wise_count', 'report_name', 'stage_wise_members', 'did_not_count_arr', 'cluster_head_wise_count'));
    }

}
