<?php

namespace App\Http\Controllers\Admin;

use DB;
use Session;
use App\LookupData;
use Carbon\Carbon;
use App\LeadLifeCycleView;
use App\schedule_followup;
use App\SoldProjectSchedule;
use Illuminate\Http\Request;
use App\ProjectScheduleCollection;
use App\Http\Controllers\Controller;

class projectScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function scheduleController()
    {
    	$sold_lead = LeadLifeCycleView::where("lead_current_stage",7)->get();
    	$collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount from sold_project_schedules group by lead_pk_no");
    	$schedule_amount  = DB::select("select lead_pk_no, sum(collected_amount) as total_amount from project_schedule_collectoins where is_schedule_penalty = false group by lead_pk_no");
    	$collection_arr =[];
    	$schedule_arr =[];
    	if(!empty($collection_amount)){
    		foreach ($collection_amount as  $value) {
    			$collection_arr[$value->lead_pk_no] = $value->total_amount;
    		}
    	}
    	if(!empty($schedule_amount)){
    		foreach ($schedule_amount as  $value) {
    			$schedule_arr[$value->lead_pk_no] = $value->total_amount;
    		}
    	}

    	return view("admin.lead_management.schedule_collection.schedule_collection",compact("sold_lead","collection_arr","schedule_arr"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function salesApproval()
    {
        return view("admin.lead_management.sales_approval");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function monthWiseReceivable()
    {
        // header('Content-type: application/ms-excel');
        // header("Content-Disposition: attachment; filename=file.csv");
        // header("Pragma: no-cache");
        // header("Expires: 0");

        

        $collection_months = DB::select("select DISTINCT MONTHNAME(created_at) month_header,concat(MONTHNAME(created_at),'-',YEAR(created_at)) month
        from project_schedule_collectoins group by created_at");

        $schedules = DB::select("select IFNULL(ps.id,0) schid,tl.lead_id,tl.project_name,concat(tl.customer_firstname,tl.customer_lastname) customer_name, 
                                        pi.flat_name,ps.schedule_date,ps.installment, ps.amount receivable 
                                        from t_lead2lifecycle_vw tl 
                                        left join sold_project_schedules ps 
                                        on tl.lead_pk_no = ps.lead_pk_no 
                                        left join s_projectwiseflatlist pi 
                                        on tl.Project_pk_no = pi.project_lookup_pk_no 
                                        and tl.flatlist_pk_no = pi.flatlist_pk_no");
        return view("admin.lead_management.month_wise_receivable",compact('schedules','collection_months'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function balanceOfMonthWiseReceivable()
    {
        return view("admin.lead_management.balance_of_month_wise_receivable");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function summaryOfReceivables()
    {
        return view("admin.lead_management.summary_of_receivables");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function customerAccount()
    {
        return view("admin.lead_management.customer_account");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
        //
		$lookup_arr = [31];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status", 1)->get();
		$penalty = 0;
		foreach ($lookup_data as $value) {
			if ($value->lookup_type == 31) {
                $penalty = $value->lookup_name;
			}
		}

    	if($request->amount > $request->hdn_remaining_amount){
    		// dd(($request->amount - $request->hdn_remaining_amount));
    		$amountdiff = ($request->amount-$request->hdn_remaining_amount);

    		$project_schedule = new ProjectScheduleCollection();
    		$project_schedule->schedule_id = $request->s_id;
    		$project_schedule->collected_amount  = $request->hdn_remaining_amount;
    		$project_schedule->bank_lookup_id  = $request->cmb_bank_id;
    		$project_schedule->check_no = $request->check_no ;
    		$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    		$project_schedule->mr_no  = $request->mr_no ;
    		$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    		$project_schedule->lead_pk_no = $request->lead_pk_no;
    		$project_schedule->lead_id  = $request->lead_id;
    		$project_schedule->collect_by   = Session::get("user.ses_user_id");
    		$project_schedule->remarks   = $request->remarks;
    		$project_schedule->is_schedule_penalty   = false;
    		$project_schedule->save();

    		$sold_project_schedule = SoldProjectSchedule::find($request->s_id);
    		$sold_project_schedule->payment_status="Completed";
    		$sold_project_schedule->save();

			if (!empty($request->total_schedule_penalty) && $request->total_schedule_penalty > 0) {

				$date = Carbon::parse(date("Y-m-d",strtotime($request->hdn_schedule_date )));
				$now = Carbon::now();

				$diff = $date->diffInDays($now);

				$total_penalty = $request->hdn_installment_amount*$penalty/100;
				$year_interest = $total_penalty/360;
				$total_penalty_value = round($year_interest*$diff,2);

				$p_project_schedule = new ProjectScheduleCollection();
				$p_project_schedule->schedule_id = $project_schedule->schedule_id;
				$p_project_schedule->collected_amount  = $total_penalty_value;
				$p_project_schedule->bank_lookup_id  = $request->cmb_bank_id;
				$p_project_schedule->check_no = $request->check_no ;
				$p_project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
				$p_project_schedule->mr_no  = $request->mr_no ;
				$p_project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
				$p_project_schedule->lead_pk_no = $request->lead_pk_no;
				$p_project_schedule->lead_id  = $request->lead_id;
				$p_project_schedule->collect_by   = Session::get("user.ses_user_id");
				$p_project_schedule->remarks   = 'On due penalty';
				$p_project_schedule->is_schedule_penalty   = true;
				$p_project_schedule->save();
			}

    		// $schedule_info =  SoldProjectSchedule::where("lead_pk_no",$id)->where("payment_status","In Complete")->orderBy("id","asc")->first();

    		$incomplete_schedule = DB::table('sold_project_schedules')
    		->where('payment_status','In Complete')
    		->where('lead_pk_no', $request->lead_pk_no)
    		->whereNotIn('id', function($query) {
    			return $query->select('schedule_id')
    			->from('project_schedule_collectoins')
    			->get();
    		})
    		->get();

    		$amountrem = 0;

    		if (!$incomplete_schedule->isEmpty()) {
    			foreach($incomplete_schedule as $schedule) {
    				if ($amountdiff > $schedule->amount) {
    					
    					$project_schedule = new ProjectScheduleCollection();
    					$project_schedule->schedule_id = $schedule->id;
    					$project_schedule->collected_amount  = $schedule->amount;
						$project_schedule->bank_lookup_id  = $request->cmb_bank_id;
    					$project_schedule->check_no = $request->check_no ;
    					$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    					$project_schedule->mr_no  = $request->mr_no ;
    					$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    					$project_schedule->lead_pk_no = $request->lead_pk_no;
    					$project_schedule->lead_id  = $request->lead_id;
    					$project_schedule->collect_by   = Session::get("user.ses_user_id");
    					$project_schedule->remarks   = $request->remarks;
						$project_schedule->is_schedule_penalty   = false;
    					$project_schedule->save();

    					$amountdiff = $amountdiff-$schedule->amount;

    					$sold_project_schedule = SoldProjectSchedule::find($schedule->id);
    					$sold_project_schedule->payment_status="Completed";
    					$sold_project_schedule->save();

						if (!empty($request->total_schedule_penalty) && $request->total_schedule_penalty > 0) {

							$date = Carbon::parse(date("Y-m-d",strtotime($request->hdn_schedule_date )));
							$now = Carbon::now();
			
							$diff = $date->diffInDays($now);
			
							$total_penalty = $schedule->amount*$penalty/100;
							$year_interest = $total_penalty/360;
							$total_penalty_value = round($year_interest*$diff,2);
			
							$p_project_schedule = new ProjectScheduleCollection();
							$p_project_schedule->schedule_id = $project_schedule->schedule_id;
							$p_project_schedule->collected_amount  = $total_penalty_value;
							$p_project_schedule->bank_lookup_id  = $request->cmb_bank_id;
							$p_project_schedule->check_no = $request->check_no ;
							$p_project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
							$p_project_schedule->mr_no  = $request->mr_no ;
							$p_project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
							$p_project_schedule->lead_pk_no = $request->lead_pk_no;
							$p_project_schedule->lead_id  = $request->lead_id;
							$p_project_schedule->collect_by   = Session::get("user.ses_user_id");
							$p_project_schedule->remarks   = 'On due penalty';
							$p_project_schedule->is_schedule_penalty   = true;
							$p_project_schedule->save();
						}
    				} 
    				else {
    					if ($amountrem ==0){
    						$project_schedule = new ProjectScheduleCollection();
    						$project_schedule->schedule_id = $schedule->id;
    						$project_schedule->collected_amount  = $amountdiff;
							$project_schedule->bank_lookup_id  = $request->cmb_bank_id;
    						$project_schedule->check_no = $request->check_no ;
    						$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    						$project_schedule->mr_no  = $request->mr_no ;
    						$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    						$project_schedule->lead_pk_no = $request->lead_pk_no;
    						$project_schedule->lead_id  = $request->lead_id;
    						$project_schedule->collect_by   = Session::get("user.ses_user_id");
    						$project_schedule->remarks   = $request->remarks;
							$project_schedule->is_schedule_penalty   = false;
    						$project_schedule->save();
    						$amountrem =1 ;
							$amountdiff = $amountdiff-$schedule->amount;

							if (!empty($request->total_schedule_penalty) && $request->total_schedule_penalty > 0) {

								$date = Carbon::parse(date("Y-m-d",strtotime($request->hdn_schedule_date )));
								$now = Carbon::now();
				
								$diff = $date->diffInDays($now);
				
								$total_penalty = $schedule->amount*$penalty/100;
								$year_interest = $total_penalty/360;
								$total_penalty_value = round($year_interest*$diff,2);
				
								$p_project_schedule = new ProjectScheduleCollection();
								$p_project_schedule->schedule_id = $project_schedule->schedule_id;
								$p_project_schedule->collected_amount  = $total_penalty_value;
								$p_project_schedule->bank_lookup_id  = $request->cmb_bank_id;
								$p_project_schedule->check_no = $request->check_no ;
								$p_project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
								$p_project_schedule->mr_no  = $request->mr_no ;
								$p_project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
								$p_project_schedule->lead_pk_no = $request->lead_pk_no;
								$p_project_schedule->lead_id  = $request->lead_id;
								$p_project_schedule->collect_by   = Session::get("user.ses_user_id");
								$p_project_schedule->remarks   = 'On due penalty';
								$p_project_schedule->is_schedule_penalty   = true;
								$p_project_schedule->save();
							}
    					}
    				}
    			}
    		}	

    		
    	}else{
    		$project_schedule = new ProjectScheduleCollection();
    		$project_schedule->schedule_id = $request->s_id;
    		$project_schedule->collected_amount = $request->amount;
			$project_schedule->bank_lookup_id  = $request->cmb_bank_id;
    		$project_schedule->check_no = $request->check_no;
    		$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    		$project_schedule->mr_no  = $request->mr_no;
    		$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    		$project_schedule->lead_pk_no = $request->lead_pk_no;
    		$project_schedule->lead_id  = $request->lead_id;
    		$project_schedule->collect_by   = Session::get("user.ses_user_id");
    		$project_schedule->remarks   = $request->remarks;
			$project_schedule->is_schedule_penalty   = false;
    		$project_schedule->save();  
    		if($request->amount == $request->hdn_remaining_amount){
    			$sold_project_schedule = SoldProjectSchedule::find($request->s_id);
    			$sold_project_schedule->payment_status="Completed";
    			$sold_project_schedule->save();
    		} 
			
			if (!empty($request->total_schedule_penalty) && $request->total_schedule_penalty > 0) {

				$date = Carbon::parse(date("Y-m-d",strtotime($request->hdn_schedule_date )));
				$now = Carbon::now();

				$diff = $date->diffInDays($now);

				$total_penalty = $request->hdn_installment_amount*$penalty/100;
				$year_interest = $total_penalty/360;
				$total_penalty_value = round($year_interest*$diff,2);

				$p_project_schedule = new ProjectScheduleCollection();
				$p_project_schedule->schedule_id = $project_schedule->schedule_id;
				$p_project_schedule->collected_amount  = $total_penalty_value;
				$p_project_schedule->bank_lookup_id  = $request->cmb_bank_id;
				$p_project_schedule->check_no = $request->check_no ;
				$p_project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
				$p_project_schedule->mr_no  = $request->mr_no ;
				$p_project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
				$p_project_schedule->lead_pk_no = $request->lead_pk_no;
				$p_project_schedule->lead_id  = $request->lead_id;
				$p_project_schedule->collect_by   = Session::get("user.ses_user_id");
				$p_project_schedule->remarks   = 'On due penalty';
				$p_project_schedule->is_schedule_penalty   = true;
				$p_project_schedule->save();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function load_schedule_collection(Request $request)
    {
        $tab_type = $request->tab_type;
        $today = date("Y-m-d");
        $collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount from sold_project_schedules group by lead_pk_no");
        $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount from project_schedule_collectoins group by lead_pk_no");
        $collection_arr = [];
        $schedule_arr = [];
        if (!empty($collection_amount)) {
            foreach ($collection_amount as $value) {
                $collection_arr[$value->lead_pk_no] = $value->total_amount;
            }
        }
        if (!empty($schedule_amount)) {
            foreach ($schedule_amount as $value) {
                $schedule_arr[$value->lead_pk_no] = $value->total_amount;
            }
        }
        if ($tab_type == 1) {
            $sold_lead = LeadLifeCycleView::where("lead_current_stage", 7)->get();
            return view("admin.lead_management.schedule_collection.sold_lead", compact("sold_lead", "schedule_arr", "collection_arr"));
        }
        if ($tab_type == 2) {
            $sold_lead = DB::select("SELECT * FROM t_lead2lifecycle_vw b
    			JOIN schedule_followup a ON a.lead_pk_no=b.lead_pk_no  where next_followup_date= '$today'
    			order by a.id desc limit 1");

            return view("admin.lead_management.schedule_collection.today_followup", compact("sold_lead", "schedule_arr", "collection_arr"));
        }
        if ($tab_type == 3) {
            $sold_lead = DB::select("SELECT * FROM t_lead2lifecycle_vw b
    			JOIN schedule_followup a ON a.lead_pk_no=b.lead_pk_no  where a.next_followup_date < '$today'
    			order by a.id desc limit 1");
            return view("admin.lead_management.schedule_collection.missed_followup", compact("sold_lead", "schedule_arr", "collection_arr"));
        }
        if ($tab_type == 4) {
            $sold_lead = DB::select("SELECT * FROM t_lead2lifecycle_vw b
    			JOIN schedule_followup a ON a.lead_pk_no=b.lead_pk_no  where a.next_followup_date > '$today'
    			order by a.id desc limit 1");
            return view("admin.lead_management.schedule_collection.next_followup", compact("sold_lead", "schedule_arr", "collection_arr"));
        }

    }

    public function load_schedule_followup_modal(Request $request)
    {
        $tab_type = $request->tab_type;
        if ($tab_type == 1) {

            return view("admin.lead_management.schedule_collection.schedule_followup.schedule_followup");
        }
        if ($tab_type == 2) {
            return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal");
        }
        if ($tab_type == 3) {
            return view("admin.lead_management.schedule_collection.schedule_followup.completed_collection");
        }

    }


    public function lead_sold_view($id)
    {
        $lead_data = LeadLifeCycleView::find($id);
        $schedule_list = SoldProjectSchedule::where("lead_pk_no", $id)->get();
        $ses_user_id = Session::get("user.ses_user_id");

        $project_collection = DB::select("
										select *
										from project_schedule_collectoins
										where lead_pk_no = '$id'
								");
        $schedule_followup = schedule_followup::where("lead_pk_no", $id)->orderBy("id", "desc")->get();
        $schedule_list_info = SoldProjectSchedule::where("lead_pk_no", $id)->first();

        $schedule_id = isset($schedule_list_info->id) ? $schedule_list_info->id : 0;
        $completed_collection = DB::select("
				select project_schedule_collectoins.*,s_lookdata.*
				from project_schedule_collectoins
				inner join s_lookdata
				on project_schedule_collectoins.bank_lookup_id = s_lookdata.lookup_pk_no
				where project_schedule_collectoins.lead_pk_no = '$id'
				and project_schedule_collectoins.schedule_id='$schedule_id'
		");//ProjectScheduleCollection::where("lead_pk_no",$id)->where("schedule_id",$schedule_id)->get();

        $schedule_complete_list = SoldProjectSchedule::where("lead_pk_no", $id)->where("payment_status", "Complete")->get();

        return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal_data", compact("lead_data", "ses_user_id", "schedule_list", "project_collection", "schedule_followup", "schedule_complete_list", "completed_collection", "schedule_id"));
    }

    public function collected_collection_view($id){
		$banks = [];
		$penalty = 0;
		$lookup_arr = [30,31];


        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status", 1)->get();

        foreach ($lookup_data as $value) {
            $key = $value->lookup_pk_no;
            if ($value->lookup_type == 30) {
                $banks[$key] = $value->lookup_name;
			}

			if ($value->lookup_type == 31) {
                $penalty = $value->lookup_name;
			}
		}

    	$total_schedule_amount = SoldProjectSchedule::where("lead_pk_no",$id)->sum('amount');
    	$schedule_list = SoldProjectSchedule::where("lead_pk_no",$id)->get();

        $schedule_info = SoldProjectSchedule::where("lead_pk_no", $id)
            ->where("payment_status", "In Complete")
            ->orderBy("id", "asc")->first();
        $schedule_id = isset($schedule_info->id) ? $schedule_info->id : 0;

        $project_collection = DB::select("select sum(collected_amount) total
    		from project_schedule_collectoins
    		where lead_pk_no = '$id'
    		and schedule_id='$schedule_id'
    		group by lead_pk_no");
        $col_amount = isset($project_collection[0]->total) ? $project_collection[0]->total : 0;

        $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount from project_schedule_collectoins where lead_pk_no ='$id' group by lead_pk_no");



    	return view("admin.lead_management.schedule_collection.collected_collection_view",compact("total_schedule_amount","penalty","banks","schedule_list","schedule_info","col_amount","schedule_amount"));

    }


    public function getCompleteCollection(Request $request)
    {
        $completed_collection = DB::select("
				select project_schedule_collectoins.*,s_lookdata.*
				from project_schedule_collectoins
				inner join s_lookdata
				on project_schedule_collectoins.bank_lookup_id = s_lookdata.lookup_pk_no
				where project_schedule_collectoins.lead_pk_no = '$request->lead_pk_no'
				and project_schedule_collectoins.schedule_id='$request->schedule_id'
		");
        //$completed_collection = ProjectScheduleCollection::where("lead_pk_no",$request->lead_pk_no)->where("schedule_id",$request->schedule_id)->get();

        return view("admin.lead_management.schedule_collection.schedule_followup.completed_collection_table", compact("completed_collection"));
    }

    public function pendingCollection(Request $request)
    {
        $sold_lead = LeadLifeCycleView::where("lead_current_stage", 7)->get();
        $collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount
                        from sold_project_schedules group by lead_pk_no");
        $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount
                        from project_schedule_collectoins group by lead_pk_no");
        $collection_arr = [];
        $schedule_arr = [];
        if (!empty($collection_amount)) {
            foreach ($collection_amount as $value) {
                $collection_arr[$value->lead_pk_no] = $value->total_amount;
            }
        }
        if (!empty($schedule_amount)) {
            foreach ($schedule_amount as $value) {
                $schedule_arr[$value->lead_pk_no] = $value->total_amount;
            }
        }

        return view("admin.lead_management.schedule_collection.pending_collection.pending_collection",
            compact("sold_lead", "collection_amount", "schedule_amount", "collection_arr", "schedule_arr"));
    }

    public function load_lead_pending(Request $request)
    {
        $tab_type = $request->tab_type;
        if ($tab_type == 1) {
            $sold_lead = LeadLifeCycleView::where("lead_current_stage", 7)->get();
            $collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount
                        from sold_project_schedules group by lead_pk_no");
            $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount
                        from project_schedule_collectoins group by lead_pk_no");
            $collection_arr = [];
            $schedule_arr = [];
            if (!empty($collection_amount)) {
                foreach ($collection_amount as $value) {
                    $collection_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            if (!empty($schedule_amount)) {
                foreach ($schedule_amount as $value) {
                    $schedule_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            return view("admin.lead_management.schedule_collection.pending_collection.pending_lead_list",
                compact("sold_lead", "schedule_arr", "collection_arr"));
        }
        if ($tab_type == 2) {
            $month = intval(date('m'));
            $sold_lead = DB::select("select t_lead2lifecycle_vw.lead_pk_no, t_lead2lifecycle_vw.lead_id, t_lead2lifecycle_vw.customer_firstname,
                            t_lead2lifecycle_vw.customer_lastname,t_lead2lifecycle_vw.phone1,t_lead2lifecycle_vw.phone1_code,
                            t_lead2lifecycle_vw.phone2_code,t_lead2lifecycle_vw.phone2,
                            t_lead2lifecycle_vw.project_name,t_lead2lifecycle_vw.user_full_name,t_lead2lifecycle_vw.lead_sales_agent_name,
                            t_lead2lifecycle_vw.lead_current_stage, t_lead2lifecycle_vw.all_phone_no, t_lead2lifecycle_vw.created_at , t_lead2lifecycle_vw.created_by, t_lead2lifecycle_vw.project_size,
                            t_lead2lifecycle_vw.lead_sales_agent_pk_no
                            from  t_lead2lifecycle_vw join sold_project_schedules
                            on t_lead2lifecycle_vw.lead_pk_no = sold_project_schedules.lead_pk_no
                            where MONTH(sold_project_schedules.schedule_date) =  $month group by t_lead2lifecycle_vw.lead_pk_no, t_lead2lifecycle_vw.lead_id, t_lead2lifecycle_vw.customer_firstname,
                            t_lead2lifecycle_vw.customer_lastname,t_lead2lifecycle_vw.phone1,t_lead2lifecycle_vw.phone1_code,
                            t_lead2lifecycle_vw.phone2_code,t_lead2lifecycle_vw.phone2,
                            t_lead2lifecycle_vw.project_name,t_lead2lifecycle_vw.user_full_name,t_lead2lifecycle_vw.lead_sales_agent_name,
                            t_lead2lifecycle_vw.lead_current_stage,  t_lead2lifecycle_vw.all_phone_no , t_lead2lifecycle_vw.created_at ,  t_lead2lifecycle_vw.lead_sales_agent_pk_no,t_lead2lifecycle_vw.created_by");

            $collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount
                        from sold_project_schedules group by lead_pk_no");
            $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount
                        from project_schedule_collectoins group by lead_pk_no");
            $collection_arr = [];
            $schedule_arr = [];
            if (!empty($collection_amount)) {
                foreach ($collection_amount as $value) {
                    $collection_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            if (!empty($schedule_amount)) {
                foreach ($schedule_amount as $value) {
                    $schedule_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            return view("admin.lead_management.schedule_collection.pending_collection.monthly_collection",
                compact("sold_lead", "schedule_arr", "collection_arr"));
        }
        if ($tab_type == 3) {
            $sold_lead = LeadLifeCycleView::where("lead_current_stage", 7)->get();
            $collection_amount = DB::select("select lead_pk_no, sum(amount) as total_amount
                        from sold_project_schedules group by lead_pk_no");
            $schedule_amount = DB::select("select lead_pk_no, sum(collected_amount) as total_amount
                        from project_schedule_collectoins group by lead_pk_no");
            $collection_arr = [];
            $schedule_arr = [];
            if (!empty($collection_amount)) {
                foreach ($collection_amount as $value) {
                    $collection_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            if (!empty($schedule_amount)) {
                foreach ($schedule_amount as $value) {
                    $schedule_arr[$value->lead_pk_no] = $value->total_amount;
                }
            }
            return view("admin.lead_management.schedule_collection.pending_collection.complete_list",
                compact("sold_lead", "schedule_arr", "collection_arr"));
        }


    }
}
