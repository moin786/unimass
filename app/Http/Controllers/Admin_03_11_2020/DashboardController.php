<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;
use App\LookupData;
use App\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	public function index($other_user_id=null)
	{
		$user_info = Auth::user();

        // ASSIGN SESSION VALUE OF AUTHENTICATED USER
		session(['user.is_super_admin' => $user_info->is_super_admin]);
		session(['user.ses_user_id' => $user_info->id]);
		session(['user.ses_email' => $user_info->email]);
		session(['user.ses_user_pk_no' => $user_info->teamUser['user_pk_no']]);
		session(['user.ses_full_name' => $user_info->teamUser['user_fullname']]);
		session(['user.ses_role_lookup_pk_no' => $user_info->role]);
		session(['user.ses_role_name' => $user_info->userRole['lookup_name']]);
		session(['user.user_type' => $user_info->user_type]);
		session(['user.is_bypass' => $user_info->teamUser['is_bypass']]);
		session(['user.bypass_date' => $user_info->teamUser['bypass_date']]);
		session(['user.ses_auto_dist' => $user_info->teamUser['auto_distribute']]);
		session(['user.ses_dist_date' => $user_info->teamUser['distribute_date']]);
		if($other_user_id == null){
			session()->forget(['user.ses_other_user_pk_no','user.ses_other_full_name','user.ses_other_role_lookup_pk_no','user.ses_other_role_name','user.is_ses_other_hod','user.is_ses_other_hot','user.is_other_team_leader']);
			session()->save();
			$user_id = $user_info->teamUser['user_pk_no']*1;
		}
		else
		{
			$user_id = $other_user_id;
			$other_user = TeamUser::where("user_pk_no",$user_id)->first();
			session(['user.ses_other_user_pk_no' => $user_id]);
			session(['user.ses_other_full_name' => $other_user->user_fullname]);
			session(['user.ses_other_role_lookup_pk_no' => $other_user->role_lookup_pk_no]);
			session(['user.ses_other_role_name' => $other_user->userRole['lookup_name']]);
		}

		$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(".$user_id.") and row_status=1")[0]->hod;
		if($is_hod > 0)
		{
			if($other_user_id == null){
				session(['user.is_ses_hod' => 1]);
			}else{
				session(['user.is_ses_other_hod' => 1]);
			}
		}

		$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(".$user_id.") and row_status=1")[0]->hot;
		if($is_hot > 0)
		{
			if($other_user_id == null){
				session(['user.is_ses_hot' => 1]);
			}else{
				session(['user.is_ses_other_hot' => 1]);
			}
		}

		$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(".$user_id.") and row_status=1")[0]->team_leader;
		if($is_team_leader > 0)
		{
			if($other_user_id == null){
				session(['user.is_team_leader' => 1]);
			}else{
				session(['user.is_other_team_leader' => 1]);
			}
		}
        // END ASSIGN SESSION


		$get_all_tem_members=$user_cond='';
		if($is_team_leader > 0 || $is_hot > 0 || $is_hod > 0)
		{	
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id)")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss.",".$user_id;
		}
		else
		{
			$get_all_tem_members .= $user_id;
		}
		
		$get_all_tem_members = implode(",",array_unique(explode(",", rtrim($get_all_tem_members,", "))));
		//echo $get_all_tem_members;
		$user_type = Session::get('user.user_type');
		$is_super_admin = Session::get('user.is_super_admin');

		if($is_super_admin == 1)
		{
			$user_cond = '';
		}
		else
		{
			if($user_type == 2)
			{
				$user_cond = " and (lead_sales_agent_pk_no in(".$get_all_tem_members.") or created_by in(".$get_all_tem_members."))";
			}
			else
			{
				$user_cond = " and created_by in(".$get_all_tem_members.")";
			}
		}
		//echo $get_all_tem_members;
        // BOX COUNTER START
		$lead_count = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_k1_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0 AND COALESCE(lead_priority_flag,0)=0 AND COALESCE(lead_transfer_flag,0)=0 AND lead_current_stage in(1,10,11)
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		$k1 = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_k1_flag,0) = 1 AND COALESCE(lead_priority_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0  AND lead_current_stage=3
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		$priority = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_priority_flag,0) = 1 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		$sold = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_sold_flag,0) =1
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		$hold = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_hold_flag,0) = 1  AND lead_current_stage=5
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		$closed = DB::select("SELECT COUNT(1) total_lead
			FROM t_leadlifecycle llc
			WHERE COALESCE(lead_closed_flag,0) = 1
			AND fnc_checkdataprivs(1,1) >0 $user_cond");

		if($user_type == 2)
		{
			/*$transferred = DB::select("SELECT COUNT(1) total_lead
				FROM t_leadtransfer
				WHERE COALESCE(transfer_to_sales_agent_flag,0) = 0
				and transfer_from_sales_agent_pk_no=$user_id");

			$accepted = DB::select("SELECT COUNT(1) total_lead
				FROM t_leadtransfer
				WHERE COALESCE(transfer_to_sales_agent_flag,0) = 1
				and transfer_to_sales_agent_pk_no=$user_id");*/

			$transferred = DB::select("SELECT COUNT(1) total_lead
    				FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
    				left join s_user u on llc.created_by=u.user_pk_no
    				WHERE lt.lead_pk_no=llc.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 0
    				and transfer_from_sales_agent_pk_no=$user_id order by llc.lead_pk_no desc");
			
			$accepted = DB::select("SELECT COUNT(1) total_lead
					FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
					left join s_user u on llc.created_by=u.user_pk_no
					WHERE lt.lead_pk_no=llc.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
					and transfer_to_sales_agent_pk_no=$user_id order by llc.lead_pk_no desc");
		}
		else
		{
			$transferred = $accepted = [];
		}
        // BOX COUNTER END

        //PERFORMANCE STATISTICS START
		if($is_team_leader > 0 || $is_hot > 0 || $is_hod > 0)
		{
			$sql_cond = "team_lead_user_pk_no=$user_id and team_lead_user_pk_no!=user_pk_no";
		}
		else
		{
			$sql_cond = "user_pk_no=$user_id";
		}

		$cre_lead_count=[];
		$get_no_of_lead_by_cre = DB::select("SELECT
			source_auto_pk_no,
			DATE_FORMAT(COALESCE(created_at),'%Y-%m') AS `create_yymm`,COUNT(lead_pk_no) lead_qnty
			FROM `t_leads` where source_auto_pk_no in($get_all_tem_members)
			GROUP BY source_auto_pk_no,created_at");
		foreach ($get_no_of_lead_by_cre as $cre_lead) {
			$cre_lead_count[$cre_lead->source_auto_pk_no][$cre_lead->create_yymm] = $cre_lead->lead_qnty;
		}

		$cur_month = date("Y-m");
		$month_cond = " and yy_mm='$cur_month'";// and sold_yymm='$cur_month'
		$avt_data = DB::select("SELECT user_pk_no,team_lead_user_pk_no,user_name,yy_mm,target_amount,target_by_lead_qty,sold_yymm,sold_amt FROM kpi_avt WHERE $sql_cond $month_cond");
		$apt_data = DB::select("SELECT user_pk_no,team_lead_user_pk_no,user_name,lead2k1,k12priority,priority2sold,k12sold FROM kpi_apt WHERE $sql_cond");
		$acr_data = DB::select("SELECT user_pk_no,team_lead_user_pk_no,user_name,k1_count,k1_priority_ratio,priority_count,priority_sold_ratio,sold_count FROM kpi_acr WHERE $sql_cond");
        //PERFORMANCE STATISTICS END

        // TODO LIST START
		if($user_type == 2)
		{
			$next_followup = DB::select("SELECT COUNT(b.lead_pk_no) next_followup_cnt FROM t_lead2lifecycle_vw b  LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 LEFT JOIN s_user c ON b.created_by=c.user_pk_no  where (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) and b.lead_sales_agent_pk_no=$user_id AND a.Next_FollowUp_date > CURDATE() AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)")[0];
			
			$missed_followup = DB::select("SELECT COUNT(b.lead_pk_no) missed_followup_cnt
                FROM t_lead2lifecycle_vw b
                LEFT JOIN t_leadfollowup a ON (a.lead_pk_no=b.lead_pk_no )
                LEFT JOIN s_user c ON b.created_by=c.user_pk_no
                WHERE a.lead_followup_pk_no = (SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no)  and b.lead_sales_agent_pk_no=$user_id AND IF(a.Next_FollowUp_date>a.lead_followup_datetime, a.Next_FollowUp_date, a.lead_followup_datetime) < CURDATE()
                AND (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL)
                AND (b.lead_sold_flag=0 OR b.lead_sold_flag IS NULL)")[0];
		}
		else
		{
			$next_followup = $missed_followup = [];
		}
        // TODO LIST END

		$digital_mkt = $hotline = [];
		$lookup_data = LookupData::whereIn('lookup_type', [2,3])->get();
		foreach ($lookup_data as $value) {
			$key = $value->lookup_pk_no;
			if ($value->lookup_type == 2)
				$digital_mkt[$key] = $value->lookup_name;

			if ($value->lookup_type == 3)
				$hotline[$key] = $value->lookup_name;
		}
		return view('admin.dashboard',compact('lead_count','k1','priority','sold','hold','closed','transferred','accepted','avt_data','apt_data','acr_data','next_followup','missed_followup','digital_mkt','hotline'));
	}

	public function performance_chart_data($user_id, $type)
	{
		if($type == "avt")
		{
			$avt_chart_data = DB::select("SELECT user_name,yy_mm,target_amount,target_by_lead_qty,sold_yymm,sold_amt FROM kpi_avt WHERE user_pk_no=$user_id");
			$months_arr = config('static_arrays.months_arr');
			$chart_arr=[];
			$i=0;
			if(!empty($avt_chart_data))
			{
				foreach ($avt_chart_data as $cdata) {
					$chart_arr[$i]['month'] = $months_arr[date("m", strtotime($cdata->yy_mm))];
					$chart_arr[$i]['value'] = $cdata->target_by_lead_qty;
					$i++;
				}
			}
		}

		if($type == "apt")
		{
			$apt_chart_data = DB::select("SELECT team_lead_user_pk_no,user_name,lead2k1,k12priority,priority2sold,k12sold FROM kpi_apt WHERE user_pk_no=$user_id");
			$months_arr = config('static_arrays.months_arr');
			$chart_arr=[];
			$i=0;
			if(!empty($apt_chart_data))
			{
				$arr = [];
				foreach ($apt_chart_data as $cdata) {
					$chart_arr = [
						[
							"y" => "Lead2k1",
							"a" => ($cdata->lead2k1)?$cdata->lead2k1:'0.0000'
						],
						[
							"y" => "k12priority",
							"a" => ($cdata->k12priority)?$cdata->k12priority:'0.0000'
						],
						[
							"y" => "priority2sold",
							"a" => ($cdata->priority2sold)?$cdata->priority2sold:'0.0000'
						],
						[
							"y" => "k12sold",
							"a" => ($cdata->k12sold)?$cdata->k12sold:'0.0000'
						],

					];

					$i++;
				}
			}
		}


		if($type == "acr")
		{
			$apt_chart_data = DB::select("SELECT user_pk_no,team_lead_user_pk_no,user_name,k1_count,k1_priority_ratio,priority_count,priority_sold_ratio,sold_count FROM kpi_acr WHERE user_pk_no=$user_id");
			$months_arr = config('static_arrays.months_arr');
			$chart_arr=[];
			$i=0;
			if(!empty($apt_chart_data))
			{
				foreach ($apt_chart_data as $cdata) {
					$chart_arr[$i]['k1_count'] = 'k1_count';
					$chart_arr[$i]['k1_count_value'] = ($cdata->k1_count)?$cdata->k1_count:'0';

					$chart_arr[$i]['k1_priority_ratio'] ='k1_priority_ratio';
					$chart_arr[$i]['k1_priority_ratio_value'] = ($cdata->k1_priority_ratio)?$cdata->k1_priority_ratio:'0.0000';

					$chart_arr[$i]['priority_count'] = 'priority_count';
					$chart_arr[$i]['priority_count_value'] = ($cdata->priority_count)?$cdata->priority_count:'0';

					$chart_arr[$i]['priority_sold_ratio'] = 'priority_sold_ratio';
					$chart_arr[$i]['priority_sold_ratio_value'] = ($cdata->priority_sold_ratio)?$cdata->priority_sold_ratio:'0.0000';

					$chart_arr[$i]['sold_count'] = 'sold_count';
					$chart_arr[$i]['sold_count_value'] = ($cdata->sold_count)?$cdata->sold_count:'0';
					$i++;
				}
			}
		}

		return json_encode($chart_arr);

	}
}
