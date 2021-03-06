<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\LeadLifeCycleView;
use App\SoldProjectSchedule;
use App\ProjectScheduleCollection;
use App\Http\Controllers\Controller;
use Session;
use DB;

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

    	return view("admin.lead_management.schedule_collection.schedule_collection",compact("sold_lead",compact("sold_lead")));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function salesApproval()
    {
        return view("admin.lead_management.sales_approval");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function monthWiseReceivable()
    {
        return view("admin.lead_management.month_wise_receivable");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function balanceOfMonthWiseReceivable()
    {
        return view("admin.lead_management.balance_of_month_wise_receivable");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function summaryOfReceivables()
    {
        return view("admin.lead_management.summary_of_receivables");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function customerAccount()
    {
        return view("admin.lead_management.customer_account");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */




    public function store(Request $request)
    {
        //

    	if($request->amount > $request->hdn_remaining_amount){
    		// dd(($request->amount - $request->hdn_remaining_amount));
    		$amountdiff = ($request->amount-$request->hdn_remaining_amount);

    		$project_schedule = new ProjectScheduleCollection();
    		$project_schedule->schedule_id = $request->s_id;
    		$project_schedule->collected_amount  = $request->hdn_remaining_amount;
    		$project_schedule->check_no = $request->check_no ;
    		$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    		$project_schedule->mr_no  = $request->mr_no ;
    		$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    		$project_schedule->lead_pk_no = $request->lead_pk_no;
    		$project_schedule->lead_id  = $request->lead_id;
    		$project_schedule->collect_by   = Session::get("user.ses_user_id");
    		$project_schedule->save();

    		$sold_project_schedule = SoldProjectSchedule::find($request->s_id);
    		$sold_project_schedule->payment_status="Complete";
    		$sold_project_schedule->save();

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
    					$project_schedule->check_no = $request->check_no ;
    					$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    					$project_schedule->mr_no  = $request->mr_no ;
    					$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    					$project_schedule->lead_pk_no = $request->lead_pk_no;
    					$project_schedule->lead_id  = $request->lead_id;
    					$project_schedule->collect_by   = Session::get("user.ses_user_id");
    					$project_schedule->save();

    					$amountdiff = $amountdiff-$schedule->amount;

    					$sold_project_schedule = SoldProjectSchedule::find($schedule->id);
    					$sold_project_schedule->payment_status="Complete";
    					$sold_project_schedule->save();
    				} 
    				else {
    					if ($amountrem ==0){
    						$project_schedule = new ProjectScheduleCollection();
    						$project_schedule->schedule_id = $schedule->id;
    						$project_schedule->collected_amount  = $amountdiff;
    						$project_schedule->check_no = $request->check_no ;
    						$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    						$project_schedule->mr_no  = $request->mr_no ;
    						$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    						$project_schedule->lead_pk_no = $request->lead_pk_no;
    						$project_schedule->lead_id  = $request->lead_id;
    						$project_schedule->collect_by   = Session::get("user.ses_user_id");
    						$project_schedule->save();
    						$amountrem =1 ;
    					}
    				}
    			}
    		}	

    		
    	}else{
    		$project_schedule = new ProjectScheduleCollection();
    		$project_schedule->schedule_id = $request->s_id;
    		$project_schedule->collected_amount = $request->amount;
    		$project_schedule->check_no = $request->check_no;
    		$project_schedule->cheque_date  = date("Y-m-d",strtotime($request->cheque_date ));
    		$project_schedule->mr_no  = $request->mr_no;
    		$project_schedule->received_date = date("Y-m-d",strtotime($request->received_date)) ;
    		$project_schedule->lead_pk_no = $request->lead_pk_no;
    		$project_schedule->lead_id  = $request->lead_id;
    		$project_schedule->collect_by   = Session::get("user.ses_user_id");
    		$project_schedule->save();  
    		if($request->amount == $request->hdn_remaining_amount){
    			$sold_project_schedule = SoldProjectSchedule::find($request->s_id);
    			$sold_project_schedule->payment_status="Complete";
    			$sold_project_schedule->save();
    		} 		
    	}

    	return response()->json(['message' => 'Lead Followup created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function load_schedule_collection(Request $request){
    	$tab_type =  $request->tab_type;
    	if($tab_type == 1){
    		return view("admin.lead_management.schedule_collection.sold_lead");
    	}
    	if($tab_type == 2){
    		return view("admin.lead_management.schedule_collection.missed_followup");
    	}
    	if($tab_type == 3){
    		return view("admin.lead_management.schedule_collection.next_followup");
    	}

    }

    public function load_schedule_followup_modal(Request $request){
    	$tab_type =  $request->tab_type;
    	if($tab_type == 1){
    		return view("admin.lead_management.schedule_collection.schedule_followup.schedule_followup");
    	}
    	if($tab_type == 2){
    		return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal");
    	}
    	if($tab_type == 3){
    		return view("admin.lead_management.schedule_collection.schedule_followup.completed_collection");
    	}

    }


    public function lead_sold_view($id){
    	$lead_data = LeadLifeCycleView::find($id);
    	$ses_user_id=Session::get("user.ses_user_id");
    	return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal_data",compact("lead_data","ses_user_id"));
    }

    public function collected_collection_view($id){
    	$schedule_list = SoldProjectSchedule::where("lead_pk_no",$id)->get();
    	$schedule_info =  SoldProjectSchedule::where("lead_pk_no",$id)->where("payment_status","In Complete")->orderBy("id","asc")->first();

    	$project_collection = DB::select("select sum(collected_amount) total from project_schedule_collectoins where lead_pk_no = '$schedule_info->lead_pk_no' group by lead_pk_no");
    	$col_amount = isset($project_collection[0]->total)? $project_collection[0]->total: 0;


    	return view("admin.lead_management.schedule_collection.collected_collection_view",compact("schedule_list","schedule_info","col_amount"));
    }


}
