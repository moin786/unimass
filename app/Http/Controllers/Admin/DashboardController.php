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
	public function index($other_user_id = null)
	{
		ini_set('memory_limit', '2048M');
		$user_info = Auth::user();

		$user_id = $user_info->teamUser['user_pk_no'] * 1;

		$userRoleID = Session::get('user.ses_role_lookup_pk_no');


        // ASSIGN SESSION VALUE OF AUTHENTICATED USER
		$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
		$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;
        //dd($is_hot);
		$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;
        //dd($is_hod);

		if ($other_user_id == null) {
			session()->forget(['user.ses_other_user_pk_no', 'user.ses_other_full_name', 'user.ses_other_role_lookup_pk_no', 'user.ses_other_role_name', 'user.is_ses_other_hod', 'user.is_ses_other_hot', 'user.is_other_team_leader']);
			session()->save();
            //$user_id = $user_info->teamUser['user_pk_no']*1;

			if ($is_hod > 0) {
				session(['user.is_ses_hod' => 1]);
			}


			if ($is_hot > 0) {
				session(['user.is_ses_hot' => 1]);
			}


			if ($is_team_leader > 0) {
				session(['user.is_team_leader' => 1]);
			}


		} else {
			$user_id = $other_user_id;
			$userRoleID = 0;
			$other_user = TeamUser::where("user_pk_no", $user_id)->first();
			$user_role = (!empty($other_user->userRole['lookup_name'])) ? $other_user->userRole['lookup_name'] : "0";
			session(['user.ses_other_user_pk_no' => $user_id]);
			session(['user.ses_other_full_name' => $other_user->user_fullname]);
			session(['user.ses_other_role_lookup_pk_no' => $other_user->role_lookup_pk_no]);
			session(['user.ses_other_role_name' => $user_role]);

			$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
			$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;
            //dd($is_hot);
			$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;


			if ($is_hod > 0) {
				session(['user.is_ses_other_hod' => 1]);
			}


			if ($is_hot > 0) {
				session(['user.is_ses_other_hot' => 1]);

			}


			if ($is_team_leader > 0) {
				session(['user.is_other_team_leader' => 1]);
			}
		}


        // END ASSIGN SESSION


		$get_all_tem_members = $user_cond = '';

		if ($is_hod > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id )")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
		} else if ($is_hot > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id and hod_flag != 1)")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
		} else if ($is_team_leader > 0) {
			$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

			$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
		} else {
			$get_all_tem_members .= $user_id;
		}


		$get_all_tem_members = implode(",", array_unique(explode(",", rtrim($get_all_tem_members, ", "))));
        //dd($get_all_tem_members);
		$user_type = Session::get('user.user_type');
		$is_super_admin = Session::get('user.is_super_admin');

		if ($is_super_admin == 1) {
			$user_cond = '';
		} else {

			if ($userRoleID == 551) {
				$user_cond = '';
			} else {
				if ($user_type == 2) {
                    $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";//or created_by in(" . $get_all_tem_members . ")
                } else {
                	$user_cond = " and created_by in(" . $get_all_tem_members . ")";
                }
            }

        }

        $lead_count = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage in(1,10,11) and lead_current_stage=1
        	$user_cond");

        $k1 = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=3 $user_cond");


        $priority = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE lead_current_stage=4 $user_cond");

        $sold = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE COALESCE(lead_sold_flag,0) =1 and lead_current_stage=7 $user_cond");

        $hold = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=5 $user_cond");

        $closed = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE lead_current_stage=6 $user_cond");

        $hp = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
           WHERE lead_current_stage=13 $user_cond");//and coalesce(lead_transfer_flag,0)=0
        $junk = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 $user_cond");
        $junk_mql = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=1 $user_cond");
        $junk_walkin = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=2 $user_cond");
        $junk_sgl = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=3 $user_cond");

        $transferred = $accepted = [];
        if ($user_type == 2 || $is_super_admin == 1) {

        	if ($userRoleID == 551 || $is_super_admin == 1) {
        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
        			left join s_user u on llc.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=llc.lead_pk_no  AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1
        			order by llc.lead_pk_no desc");
        	} else {

        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
        			left join s_user u on llc.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=llc.lead_pk_no AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1
        			and transfer_from_sales_agent_pk_no=$user_id order by llc.lead_pk_no desc");
        	}
        } else {

        	if ($userRoleID == 551) {
        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
        			left join s_user u on llc.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=llc.lead_pk_no  AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1
        			order by llc.lead_pk_no desc");
        	} else {
        		$transferred = $accepted = [];
        	}


        }
        // BOX COUNTER END

        //PERFORMANCE STATISTICS START

        if ($userRoleID == 551) {
        	$sql_cond = "user_pk_no=$user_id";
        } else {
        	if ($is_team_leader > 0 || $is_hot > 0 || $is_hod > 0) {
        		$sql_cond = "team_lead_user_pk_no=$user_id and team_lead_user_pk_no!=user_pk_no";
        	} else {
        		$sql_cond = "user_pk_no=$user_id";
        	}
        }
        $cre_lead_count = [];
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
        $today_followup = $next_followup = $missed_followup = 0;
        if ($user_type == 2) {

        	if ($userRoleID == 551) {
        		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) ");
        		$today_followup = $next_followup = $missed_followup = 0;
        		if (!empty($lead_data)) {
        			foreach ($lead_data as $row) {
        				if (strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime)) {
        					$followup_date = strtotime($row->Next_FollowUp_date);
        				} else {
        					$followup_date = strtotime($row->lead_followup_datetime);

        				}

        				if ($followup_date == strtotime(date('d-m-Y'))) {
        					$today_followup++;
        				}
        				if ($followup_date < strtotime(date('d-m-Y'))) {
        					$missed_followup++;
        				}
        				if (strtotime($row->Next_FollowUp_date) > strtotime(date('d-m-Y'))) {
        					$next_followup++;
        				}
        			}
        		}

        	} else {
        		$user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";
        		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $user_conds");

        		if (!empty($lead_data)) {
        			foreach ($lead_data as $row) {
        				if (strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime)) {
        					$followup_date = strtotime($row->Next_FollowUp_date);
        				} else {
        					$followup_date = strtotime($row->lead_followup_datetime);

        				}

        				if ($followup_date == strtotime(date('d-m-Y'))) {
        					$today_followup++;
        				}
        				if ($followup_date < strtotime(date('d-m-Y'))) {
        					$missed_followup++;
        				}
        				if (strtotime($row->Next_FollowUp_date) > strtotime(date('d-m-Y'))) {
        					$next_followup++;
        				}
        			}
        		}
        	}
        } else {
        	$today_followup = $next_followup = $missed_followup = 0;
        }

        // TODO LIST END
        $digital_mkt = $hotline = [];
        $lookup_data = LookupData::whereIn('lookup_type', [2, 3])->get();
        foreach ($lookup_data as $value) {
        	$key = $value->lookup_pk_no;
        	if ($value->lookup_type == 2)
        		$digital_mkt[$key] = $value->lookup_name;

        	if ($value->lookup_type == 3)
        		$hotline[$key] = $value->lookup_name;
        }


        $get_all_tem_members = null;
        $today_date = date("Y-m-d");

        if ($is_hod > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id )")[0]->team_members;
        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_hot > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id and hod_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_team_leader > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;
        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else {
        	$get_all_tem_members .= $user_id;
        }
        $get_all_team_members = rtrim(($get_all_tem_members), ", ");

        $user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";

        $ses_user_id = Session::get('user.ses_user_pk_no');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');


        if (($is_hod > 0 || $is_hot > 0 || $is_team_leader > 0) && $userRoleID != 551) {
        	$lead_data = DB::table('t_lead2lifecycle_vw')
        	->whereRaw("(lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . ")) and lead_current_stage not in(6,7,9)")
        	->orderBy("lead_cluster_head_assign_dt", "desc")
        	->get();
        	$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        		a.next_followup_Note,c.user_fullname agent_name,b.*
        		FROM t_lead2lifecycle_vw b
        		JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        		SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        		)
        		LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        		WHERE lead_current_stage not in(6,7,9) $user_conds");
            //dd(count($today_meeting_data));

        } else {
        	if ($userRoleID == 551 || $is_super_admin ==1 ) {
        		$lead_data = DB::table('t_lead2lifecycle_vw')
        		->whereNotIn('lead_current_stage', [6, 7, 9])
        		->orderBy("created_at", "desc")
        		->get();



        		$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE lead_current_stage not in(6,7,9)");

        	} else {
        		$tranfer_condition = "and (lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR lead_transfer_from_sales_agent_pk_no IS NULL ) ";
        		$tranfer_condition = "";
        		$lead_data = DB::table('t_lead2lifecycle_vw')
        		->where('lead_sales_agent_pk_no', $get_all_team_members)
        		->whereRaw("lead_current_stage not in(6,7,9)")
        		->orderBy("created_at", "desc")
                    ->get();//(lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR lead_transfer_from_sales_agent_pk_no IS NULL)  and

                    $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                    	a.next_followup_Note,c.user_fullname agent_name,b.*
                    	FROM t_lead2lifecycle_vw b
                    	JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
                    	SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
                    	)
                    	LEFT JOIN s_user c ON b.created_by=c.user_pk_no
                    	WHERE lead_current_stage not in(6,7,9) $user_conds");
                }
            }

            $all_lead_count = count($lead_data);
            $meeting_count = count($today_meeting_data);

            $mql = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 1 and lead_current_stage not in (6,7,9)  $user_cond")[0];

            $walkin = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 2 and lead_current_stage not in (6,7,9) $user_cond")[0];
            $sgl = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 3 and lead_current_stage not in (6,7,9) $user_cond")[0];
            $ses_user_id = Session::get('user.ses_user_pk_no');
            $ses_user_type = Session::get('user.user_type');
            $is_hod = Session::get('user.is_ses_hod');
            $is_hot = Session::get('user.is_ses_hot');
            $is_tl = Session::get('user.is_team_leader');

            $my_lead = DB::select("select COUNT(lead_pk_no) AS lead_count from t_lead2lifecycle_vw where lead_sales_agent_pk_no = '$ses_user_id' and lead_current_stage not in(6,7,9)")[0];

            if ($ses_user_type == 1) {

            	$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
            		->where('lead_cluster_head_pk_no', 0)
            		->where('lead_current_stage', 1)
            		->whereRaw("(source_auto_pk_no in ($get_all_team_members))")
            		->get());
            } elseif ($ses_user_type == 2) {
            	if ($other_user_id == null) {
            		if ($is_hod == 1 || $is_hot == 1 || $is_tl == 1) {
            			$is_team_leader = Session::get('user.is_team_leader');
            			$is_ses_hod = Session::get('user.is_ses_hod');
            			$is_ses_hot = Session::get('user.is_ses_hot');
            			if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            				$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
            				$get_all_team_members = explode(",", $get_all_team_member . "," . $ses_user_id);

            			}

            			if ($is_hod == 1) {
            				$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
            					->whereIn('lead_cluster_head_pk_no', $get_all_team_members)
            					->whereRaw("(lead_sales_agent_pk_no is NULL or lead_sales_agent_pk_no =0)")
            					->orderBy("lead_cluster_head_assign_dt", "desc")
            					->get());


            			} else {
            				$distribute_lead_count = count(
            					DB::table('t_lead2lifecycle_vw')
            					->whereRaw("(`lead_sales_agent_pk_no` = $ses_user_id 
            						OR `lead_cluster_head_pk_no` = $ses_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$ses_user_id )")
            					->orderBy("lead_cluster_head_assign_dt", "desc")
            					->get());
            			}


            		} else {
            			if($userRoleID == 551){

            				$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
            					->whereRaw("( lead_sales_agent_pk_no =0)")
            					->orderBy("lead_cluster_head_assign_dt", "desc")
            					->get());
            			}else{
            				$distribute_lead_count = 0;
            			}
            		}
            	} else {

            		$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
            		$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;
                //dd($is_hot);
            		$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;


            		$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
            			->whereRaw("(`lead_sales_agent_pk_no` = $other_user_id 
            				OR `lead_cluster_head_pk_no` = $other_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$other_user_id )")
            			->orderBy("lead_cluster_head_assign_dt", "desc")
            			->get());

            	}
            } else {
            	$distribute_lead_count = 0;
            }


            $source_wise_count_arr = [];
            $sub_source = LookupData::where("lookup_type", 2)->get();

            if($user_type == 1)
            {
            	$user_cond = " and t_lead2lifecycle_vw.created_by in($get_all_tem_members)";
            }
            else
            {
            	$user_cond = " AND (t_lead2lifecycle_vw.lead_sales_agent_pk_no IN($get_all_tem_members) OR t_lead2lifecycle_vw.lead_cluster_head_pk_no IN($get_all_tem_members))";
            }

            if ($is_super_admin == 1) {
            	$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`,COUNT(t_lead2lifecycle_vw.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw WHERE 
            		s_lookdata.`lookup_pk_no` = t_lead2lifecycle_vw.`source_digital_marketing` GROUP BY s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`");
            } else {
            	if ($userRoleID == 551) {
            		$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`,COUNT(t_lead2lifecycle_vw.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw WHERE 
            			s_lookdata.`lookup_pk_no` = t_lead2lifecycle_vw.`source_digital_marketing` GROUP BY s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`");

            	} else {

            		$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`,COUNT(t_lead2lifecycle_vw.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw WHERE 
            			s_lookdata.`lookup_pk_no` = t_lead2lifecycle_vw.`source_digital_marketing` $user_cond GROUP BY s_lookdata.`lookup_name`,t_lead2lifecycle_vw.`source_digital_marketing`");

            	}
            }

            foreach ($sub_source as $source) {
            	foreach ($source_wise_count as $count_info) {
            		if ($source->lookup_pk_no == $count_info->source_digital_marketing) {
            			$source_wise_count_arr[$source->lookup_name] = $count_info->digital;
            		}
            	}
            	if (!isset($source_wise_count_arr[$source->lookup_name])) {
            		$source_wise_count_arr[$source->lookup_name] = 0;
            	}
            }
        	//dd($source_wise_count_arr);
            $is_hod = Session::get('user.is_ses_hod');
            $is_hot = Session::get('user.is_ses_hot');
            $is_tl = Session::get('user.is_team_leader');
            //Project Wise Count

            $project_name = LookupData::where("lookup_type",6)->get();
            if($userRoleID==551){
            	$cond = "group by project_name";


            }else{
            	$cond= "where (t_lead2lifecycle_vw.lead_sales_agent_pk_no IN($get_all_tem_members) OR t_lead2lifecycle_vw.lead_cluster_head_pk_no IN($get_all_tem_members)) group by project_name ";
            }
            $lead_data = DB::select("select Project_pk_no,count(project_name) as total_project from t_lead2lifecycle_vw $cond ");


            $project_wise_count=[];

            if(!empty($lead_data)){
            	foreach ($lead_data as $value) {
            		$project_wise_count[$value->Project_pk_no] = $value->total_project;
            	}
            }
            
            return view('admin.dashboard', compact('lead_count', 'k1', 'sgl', 'mql', 'walkin', 'priority', 'sold', 'hold',
            	'closed', 'transferred', 'accepted', 'avt_data', 'apt_data', 'acr_data', 'next_followup', 'missed_followup',
            	'digital_mkt', 'hotline', 'hp', 'today_followup', 'all_lead_count', 'distribute_lead_count', 'is_hod', 'is_hot',
            	'is_tl', 'ses_user_type', 'is_super_admin', 'source_wise_count_arr', 'other_user_id', 'my_lead', 'meeting_count','junk','junk_mql','junk_walkin','junk_sgl','project_name','project_wise_count'));
        }

        public function getToDoListCounts($get_all_tem_members, $userRoleID)
        {

        }

        public function performance_chart_data($user_id, $type)
        {
        	if ($type == "avt") {
        		$avt_chart_data = DB::select("SELECT user_name,yy_mm,target_amount,target_by_lead_qty,sold_yymm,sold_amt FROM kpi_avt WHERE user_pk_no=$user_id");
        		$months_arr = config('static_arrays.months_arr');
        		$chart_arr = [];
        		$i = 0;
        		if (!empty($avt_chart_data)) {
        			foreach ($avt_chart_data as $cdata) {
        				$chart_arr[$i]['month'] = $months_arr[date("m", strtotime($cdata->yy_mm))];
        				$chart_arr[$i]['value'] = $cdata->target_by_lead_qty;
        				$i++;
        			}
        		}
        	}

        	if ($type == "apt") {
        		$apt_chart_data = DB::select("SELECT team_lead_user_pk_no,user_name,lead2k1,k12priority,priority2sold,k12sold FROM kpi_apt WHERE user_pk_no=$user_id");
        		$months_arr = config('static_arrays.months_arr');
        		$chart_arr = [];
        		$i = 0;
        		if (!empty($apt_chart_data)) {
        			$arr = [];
        			foreach ($apt_chart_data as $cdata) {
        				$chart_arr = [
        					[
        						"y" => "Lead2k1",
        						"a" => ($cdata->lead2k1) ? $cdata->lead2k1 : '0.0000'
        					],
        					[
        						"y" => "k12priority",
        						"a" => ($cdata->k12priority) ? $cdata->k12priority : '0.0000'
        					],
        					[
        						"y" => "priority2sold",
        						"a" => ($cdata->priority2sold) ? $cdata->priority2sold : '0.0000'
        					],
        					[
        						"y" => "k12sold",
        						"a" => ($cdata->k12sold) ? $cdata->k12sold : '0.0000'
        					],

        				];

        				$i++;
        			}
        		}
        	}


        	if ($type == "acr") {
        		$apt_chart_data = DB::select("SELECT user_pk_no,team_lead_user_pk_no,user_name,k1_count,k1_priority_ratio,priority_count,priority_sold_ratio,sold_count FROM kpi_acr WHERE user_pk_no=$user_id");
        		$months_arr = config('static_arrays.months_arr');
        		$chart_arr = [];
        		$i = 0;
        		if (!empty($apt_chart_data)) {
        			foreach ($apt_chart_data as $cdata) {
        				$chart_arr[$i]['k1_count'] = 'k1_count';
        				$chart_arr[$i]['k1_count_value'] = ($cdata->k1_count) ? $cdata->k1_count : '0';

        				$chart_arr[$i]['k1_priority_ratio'] = 'k1_priority_ratio';
        				$chart_arr[$i]['k1_priority_ratio_value'] = ($cdata->k1_priority_ratio) ? $cdata->k1_priority_ratio : '0.0000';

        				$chart_arr[$i]['priority_count'] = 'priority_count';
        				$chart_arr[$i]['priority_count_value'] = ($cdata->priority_count) ? $cdata->priority_count : '0';

        				$chart_arr[$i]['priority_sold_ratio'] = 'priority_sold_ratio';
        				$chart_arr[$i]['priority_sold_ratio_value'] = ($cdata->priority_sold_ratio) ? $cdata->priority_sold_ratio : '0.0000';

        				$chart_arr[$i]['sold_count'] = 'sold_count';
        				$chart_arr[$i]['sold_count_value'] = ($cdata->sold_count) ? $cdata->sold_count : '0';
        				$i++;
        			}
        		}
        	}

        	return json_encode($chart_arr);

        }

        public function dashboard_info(Request $request)
        {
        	ini_set('memory_limit', '2048M');
        	$user_type = Session::get('user.user_type');
        	$user_info = Auth::user();
        	$fromdate = date("Y-m-d", strtotime($request->date_from));
        $todate = date("Y-m-d", strtotime($request->date_to)); // $request->date_to
        $date_cond = !empty($request->date_from) ? "and created_at BETWEEN '$fromdate' AND '$todate'" : " ";
        $sold_cond = !empty($request->date_from) ? "and lead_sold_date_manual BETWEEN '$fromdate' AND '$todate'" : " ";
        $date_cond1 = !empty($request->date_from) ? " created_at BETWEEN '$fromdate' AND '$todate'" : " ";
        $date_cond2 = !empty($request->date_from) ? "and b.created_at BETWEEN '$fromdate' AND '$todate'" : " ";

        $date_cond3 = !empty($request->date_from) ? "and t_lead2lifecycle_vw.created_at BETWEEN '$fromdate' AND '$todate'" : " ";
        $date_cond4 = !empty($request->date_from) ? "and a.visit_meeting_done_dt BETWEEN '$fromdate' AND '$todate'" : " ";
        $user_id = $user_info->teamUser['user_pk_no'] * 1;

        $userRoleID = Session::get('user.ses_role_lookup_pk_no');


        // ASSIGN SESSION VALUE OF AUTHENTICATED USER
        $is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
        $is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;

        $is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;

        $other_user_id = Session::get('user.ses_other_user_pk_no');

        if ($other_user_id == null) {
        	session()->forget(['user.ses_other_user_pk_no', 'user.ses_other_full_name', 'user.ses_other_role_lookup_pk_no', 'user.ses_other_role_name', 'user.is_ses_other_hod', 'user.is_ses_other_hot', 'user.is_other_team_leader']);
        	session()->save();
        	if ($is_hod > 0) {
        		session(['user.is_ses_hod' => 1]);
        	}

        	if ($is_hot > 0) {
        		session(['user.is_ses_hot' => 1]);
        	}

        	if ($is_team_leader > 0) {
        		session(['user.is_team_leader' => 1]);
        	}


        } else {
        	$user_id = $other_user_id;
        	$userRoleID = 0;
        	$other_user = TeamUser::where("user_pk_no", $user_id)->first();
        	$user_role = (!empty($other_user->userRole['lookup_name'])) ? $other_user->userRole['lookup_name'] : "0";
        	session(['user.ses_other_user_pk_no' => $user_id]);
        	session(['user.ses_other_full_name' => $other_user->user_fullname]);
        	session(['user.ses_other_role_lookup_pk_no' => $other_user->role_lookup_pk_no]);
        	session(['user.ses_other_role_name' => $user_role]);

        	$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
        	$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;
        	$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;


        	if ($is_hod > 0) {
        		session(['user.is_ses_other_hod' => 1]);
        	}
        	if ($is_hot > 0) {
        		session(['user.is_ses_other_hot' => 1]);
        	}

        	if ($is_team_leader > 0) {
        		session(['user.is_other_team_leader' => 1]);
        	}
        }
        // END ASSIGN SESSION


        $get_all_tem_members = $user_cond = '';

        if ($is_hod > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id )")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_hot > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id and hod_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_team_leader > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;
        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else {
        	$get_all_tem_members .= $user_id;
        }


        $get_all_tem_members = implode(",", array_unique(explode(",", rtrim($get_all_tem_members, ", "))));

        $user_type = Session::get('user.user_type');
        $is_super_admin = Session::get('user.is_super_admin');

        if ($is_super_admin == 1) {
        	$user_cond = '';
        } else {

        	if ($userRoleID == 551) {
        		$user_cond = '';
        	} else {
        		if ($user_type == 2) {
        			$user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";
        		} else {
        			$user_cond = " and created_by in(" . $get_all_tem_members . ")";
        		}
        	}

        }

        $lead_count = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=1 $user_cond $date_cond");

        $k1 = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE  lead_current_stage=3 $user_cond $date_cond");


        $priority = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc  WHERE lead_current_stage=4
        	$user_cond $date_cond");

        $sold = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE lead_current_stage=7 $user_cond $sold_cond");

        $hold = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE lead_current_stage=5 $user_cond $date_cond");

        $closed = DB::select("SELECT COUNT(1) total_lead FROM t_lead2lifecycle_vw llc WHERE lead_current_stage=6 $user_cond $date_cond");

        $hp = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=13
        	$user_cond $date_cond");
        $junk = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 $user_cond $date_cond");
        $junk_mql = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=1 $user_cond $date_cond");
        $junk_walkin = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=2 $user_cond $date_cond");
        $junk_sgl = DB::select("SELECT COUNT(1) total_lead
        	FROM t_lead2lifecycle_vw llc
        	WHERE lead_current_stage=9 and lead_entry_type=3 $user_cond $date_cond");

        $transferred = $accepted = [];
        if ($user_type == 2 || $is_super_admin == 1) {
        	if ($userRoleID == 551 || $is_super_admin == 1) {
        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw b
        			left join s_user u on b.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=b.lead_pk_no AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1 $date_cond2
        			order by b.lead_pk_no desc");


        	} else {
        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw b
        			left join s_user u on b.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=b.lead_pk_no  AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1
        			and transfer_from_sales_agent_pk_no=$user_id $date_cond2 order by b.lead_pk_no desc");
        	}
        } else {

        	if ($userRoleID == 551) {
        		$transferred = DB::select("SELECT COUNT(1) total_lead
        			FROM t_leadtransfer lt, t_lead2lifecycle_vw b
        			left join s_user u on b.created_by=u.user_pk_no
        			WHERE lt.lead_pk_no=b.lead_pk_noAND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1 $date_cond2
        			order by b.lead_pk_no desc");


        	} else {
        		$transferred = $accepted = [];
        	}


        }
        // BOX COUNTER END

        //PERFORMANCE STATISTICS START
        if ($userRoleID == 551) {
        	$sql_cond = "user_pk_no=$user_id";
        } else {
        	if ($is_team_leader > 0 || $is_hot > 0 || $is_hod > 0) {
        		$sql_cond = "team_lead_user_pk_no=$user_id and team_lead_user_pk_no!=user_pk_no";
        	} else {
        		$sql_cond = "user_pk_no=$user_id";
        	}
        }
        $cre_lead_count = [];
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
        $today_followup = $next_followup = $missed_followup = 0;
        if ($user_type == 2) {

        	if ($userRoleID == 551) {
        		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) ");
        		$today_followup = $next_followup = $missed_followup = 0;
        		if (!empty($lead_data)) {
        			foreach ($lead_data as $row) {
        				if (strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime)) {
        					$followup_date = strtotime($row->Next_FollowUp_date);
        				} else {
        					$followup_date = strtotime($row->lead_followup_datetime);

        				}

        				if ($followup_date == strtotime(date('d-m-Y'))) {
        					$today_followup++;
        				}
        				if ($followup_date < strtotime(date('d-m-Y'))) {
        					$missed_followup++;
        				}
        				if (strtotime($row->Next_FollowUp_date) > strtotime(date('d-m-Y'))) {
        					$next_followup++;
        				}
        			}
        		}

        	} else {
        		$user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";
        		$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL) and lead_current_stage not in(6,7,9) $user_conds");

        		if (!empty($lead_data)) {
        			foreach ($lead_data as $row) {
        				if (strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime)) {
        					$followup_date = strtotime($row->Next_FollowUp_date);
        				} else {
        					$followup_date = strtotime($row->lead_followup_datetime);

        				}

        				if ($followup_date == strtotime(date('d-m-Y'))) {
        					$today_followup++;
        				}
        				if ($followup_date < strtotime(date('d-m-Y'))) {
        					$missed_followup++;
        				}
        				if (strtotime($row->Next_FollowUp_date) > strtotime(date('d-m-Y'))) {
        					$next_followup++;
        				}
        			}
        		}
        	}
        } else {
        	$today_followup = $next_followup = $missed_followup = 0;
        }

        // TODO LIST END
        $digital_mkt = $hotline = [];
        $lookup_data = LookupData::whereIn('lookup_type', [2, 3])->get();
        foreach ($lookup_data as $value) {
        	$key = $value->lookup_pk_no;
        	if ($value->lookup_type == 2)
        		$digital_mkt[$key] = $value->lookup_name;

        	if ($value->lookup_type == 3)
        		$hotline[$key] = $value->lookup_name;
        }


        $ses_user_id = Session::get('user.ses_user_pk_no');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');

        $today_date = date("Y-m-d");

        if ($is_hod > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id )")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_hot > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id and hod_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else if ($is_team_leader > 0) {
        	$get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

        	$get_all_tem_members .= $get_all_tem_memberss . "," . $user_id;
        } else {
        	$get_all_tem_members = $user_id;
        }
        $get_all_team_members = rtrim(($get_all_tem_members), ", ");

        $user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";

        if ($is_hod > 1 || $is_hot > 1 || $is_team_leader > 1) {
        	$lead_data = DB::table('t_lead2lifecycle_vw')
        	->whereRaw("(lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . ")) " . $date_cond3 . "")
        	->whereNotIn("lead_current_stage",[6,7,9])->get();

        	if ($date_cond4 == "") {
        		$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE lead_current_stage not in(6,7,9) $user_conds");
        	} else {
        		$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        			a.next_followup_Note,c.user_fullname agent_name,b.*
        			FROM t_lead2lifecycle_vw b
        			JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        			SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        			)
        			LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        			WHERE lead_current_stage not in(6,7,9) $user_conds $date_cond4");
        	}


        } else {
        	if ($userRoleID == 551 || $is_super_admin ==1) {
        		$lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_current_stage not in (6,7,9)  $date_cond");


        		if ($date_cond4 == "") {
        			$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        				a.next_followup_Note,c.user_fullname agent_name,b.*
        				FROM t_lead2lifecycle_vw b
        				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        				)
        				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        				WHERE lead_current_stage not in(6,7,9)");

        		} else {
        			$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        				a.next_followup_Note,c.user_fullname agent_name,b.*
        				FROM t_lead2lifecycle_vw b
        				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        				)
        				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        				WHERE lead_current_stage not in(6,7,9) $date_cond4");
        		}


        	} else {
        		$tranfer_condition = "and (lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR lead_transfer_from_sales_agent_pk_no IS NULL ) ";

        		$lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_sales_agent_pk_no=$ses_user_id and lead_current_stage not in (6,7,9) $date_cond3");
        		$get_all_tem_members = $user_id;
        		$get_all_team_members = rtrim(($get_all_tem_members), ", ");

        		$user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";

        		if ($date_cond4 == "") {


        			$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        				a.next_followup_Note,c.user_fullname agent_name,b.*
        				FROM t_lead2lifecycle_vw b
        				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        				)
        				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        				WHERE lead_current_stage not in(6,7,9) $user_conds");

        		} else {

        			$today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
        				a.next_followup_Note,c.user_fullname agent_name,b.*
        				FROM t_lead2lifecycle_vw b
        				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
        				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
        				)
        				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
        				WHERE lead_current_stage not in(6,7,9) $date_cond4 $user_conds");
        		}
        	}
        }
        $all_lead_count = count($lead_data);
        $meeting_count = count($today_meeting_data);

        $mql = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 1 and lead_current_stage not in(6,7,9) $user_cond $date_cond")[0];
        $walkin = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 2 and lead_current_stage not in(6,7,9)  $user_cond $date_cond")[0];
        $sgl = DB::select("SELECT COUNT(lead_pk_no) AS lead_count FROM t_lead2lifecycle_vw WHERE lead_entry_type = 3 and lead_current_stage not in(6,7,9) $user_cond $date_cond")[0];
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $ses_user_type = Session::get('user.user_type');
        $is_hod = Session::get('user.is_ses_hod');
        $is_hot = Session::get('user.is_ses_hot');
        $is_tl = Session::get('user.is_team_leader');

        $my_lead = DB::select("select COUNT(lead_pk_no) AS lead_count from t_lead2lifecycle_vw where (lead_sales_agent_pk_no = '$ses_user_id' or lead_cluster_head_pk_no = '$ses_user_id') and lead_current_stage not in(6,7,9) $date_cond")[0];

        if ($ses_user_type == 1) {
        	$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
        		->where('lead_cluster_head_pk_no', 0)
        		->where('lead_current_stage', 1)
        		->whereRaw("(source_auto_pk_no in ($get_all_team_members))")
        		->get());
        } elseif ($ses_user_type == 2) {
        	if ($other_user_id == null) {
        		if ($is_hod == 1 || $is_hot == 1 || $is_tl == 1) {
        			$is_team_leader = Session::get('user.is_team_leader');
        			$is_ses_hod = Session::get('user.is_ses_hod');
        			$is_ses_hot = Session::get('user.is_ses_hot');
        			if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
        				$get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id)")[0]->team_members;
        				$get_all_team_members = explode(",", $get_all_team_member . "," . $ses_user_id);

        			}

        			if ($is_hod == 1) {
        				$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
        					->whereIn('lead_cluster_head_pk_no', $get_all_team_members)
        					->whereRaw("(lead_sales_agent_pk_no is NULL or lead_sales_agent_pk_no =0) ")
        					->orderBy("lead_cluster_head_assign_dt", "desc")
        					->get());
        			} else {
        				$distribute_lead_count = count(
        					DB::table('t_lead2lifecycle_vw')
        					->whereRaw("((`lead_sales_agent_pk_no` = $ses_user_id 
        						OR `lead_cluster_head_pk_no` = $ses_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$ses_user_id ))")
        					->orderBy("lead_cluster_head_assign_dt", "desc")
        					->get());
        			}


        		} else {
        			$distribute_lead_count = 0;
        		}
        		if($userRoleID ==551 || $is_super_admin==1){
        			$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
        				->whereRaw("( lead_sales_agent_pk_no =0) $date_cond")
        				->orderBy("lead_cluster_head_assign_dt", "desc")
        				->get());
        		}
        	} else {

        		$is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hod;
        		$is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(" . $user_id . ") and row_status=1")[0]->hot;
                //dd($is_hot);
        		$is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(" . $user_id . ") and row_status=1")[0]->team_leader;


        		$distribute_lead_count = count(DB::table('t_lead2lifecycle_vw')
        			->whereRaw("((`lead_sales_agent_pk_no` = $other_user_id 
        				OR `lead_cluster_head_pk_no` = $other_user_id ) AND (`lead_dist_by` IS NULL OR lead_dist_by !=$other_user_id )) " . $date_cond3 . "")
        			->orderBy("lead_cluster_head_assign_dt", "desc")
        			->get());

        	}
        } else {
        	$distribute_lead_count = 0;
        }


        $source_wise_count_arr = [];
        $sub_source = LookupData::where("lookup_type", 2)->get();
        if($user_type == 1)
        {
        	$user_cond = " and b.created_by in($get_all_tem_members)";
        	/*dd($user_cond);*/
        }
        else
        {
        	$user_cond = " AND (b.lead_sales_agent_pk_no IN($get_all_tem_members) OR b.lead_cluster_head_pk_no IN($get_all_tem_members))";
        }
        if ($is_super_admin == 1) {
        	$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,b.`source_digital_marketing`,COUNT(b.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw b WHERE 
        		s_lookdata.`lookup_pk_no` = b.`source_digital_marketing` $date_cond2  GROUP BY s_lookdata.`lookup_name`,b.`source_digital_marketing`");
        } else {
        	if ($userRoleID == 551) {
        		$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,b.`source_digital_marketing`,COUNT(b.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw b WHERE 
        			s_lookdata.`lookup_pk_no` = b.`source_digital_marketing` $date_cond2 GROUP BY s_lookdata.`lookup_name`,b.`source_digital_marketing`");

        	} else {
                //$get_all_team_members = rtrim(($get_all_tem_members), ", ");
        		$source_wise_count = DB::select("SELECT s_lookdata.`lookup_name`,b.`source_digital_marketing`,COUNT(b.`source_digital_marketing`) AS digital FROM s_lookdata,t_lead2lifecycle_vw b WHERE 
        			s_lookdata.`lookup_pk_no` = b.`source_digital_marketing` $user_cond $date_cond2 GROUP BY s_lookdata.`lookup_name`,b.`source_digital_marketing`");

        	}
        }

        foreach ($sub_source as $source) {
        	foreach ($source_wise_count as $count_info) {
        		if ($source->lookup_pk_no == $count_info->source_digital_marketing) {
        			$source_wise_count_arr[$source->lookup_name] = $count_info->digital;
        		}
        	}
        	if (!isset($source_wise_count_arr[$source->lookup_name])) {
        		$source_wise_count_arr[$source->lookup_name] = 0;
        	}
        }
        //dd($source_wise_count_arr);
        $is_hod = Session::get('user.is_ses_hod');
        $is_hot = Session::get('user.is_ses_hot');
        $is_tl = Session::get('user.is_team_leader');

        $role_id = 0;
        $project_name = LookupData::where("lookup_type",6)->get();
        if($userRoleID==551){
        	$cond = "where created_at BETWEEN '$fromdate' AND '$todate' group by project_name";


        }else{
        	$cond= "where (t_lead2lifecycle_vw.lead_sales_agent_pk_no IN($get_all_tem_members) OR t_lead2lifecycle_vw.lead_cluster_head_pk_no IN($get_all_tem_members)) $date_cond group by project_name ";
        	
        }
        $lead_data = DB::select("select Project_pk_no,count(project_name) as total_project from t_lead2lifecycle_vw $cond ");

        $project_wise_count=[];

        if(!empty($lead_data)){
        	foreach ($lead_data as $value) {
        		$project_wise_count[$value->Project_pk_no] = $value->total_project;
        	}
        }
        return view('admin.dashboard_info', compact('lead_count', 'k1', 'sgl', 'mql', 'walkin', 'priority', 'sold', 'hold',
        	'closed', 'transferred', 'accepted', 'avt_data', 'apt_data', 'acr_data', 'next_followup', 'missed_followup',
        	'digital_mkt', 'hotline', 'hp', 'today_followup', 'all_lead_count', 'distribute_lead_count', 'is_hod', 'is_hot',
        	'is_tl', 'ses_user_type', 'is_super_admin', 'role_id', 'source_wise_count_arr', 'user_type', 'other_user_id', 'my_lead', 'meeting_count','junk','junk_mql','junk_walkin',   'junk_sgl','project_name','project_wise_count'));
    }
}
