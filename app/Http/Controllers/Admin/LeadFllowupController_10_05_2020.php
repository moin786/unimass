<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\LookupData;
use App\LeadLifeCycle;
use App\LeadFollowUp;
use App\LeadLifeCycleView;
use App\LeadStageHistory;
use App\FlatSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadFllowupController extends Controller
{
    public function index()
    {
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $is_team_leader = Session::get('user.is_team_leader');
        $ses_user_id = Session::get('user.ses_user_pk_no');

        if($is_team_leader == 1 && $ses_user_id!="")
        {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
            $get_all_team_members = ($get_all_team_member!="")?$get_all_team_member.",".$ses_user_id:$ses_user_id;
        }
        else
        {
            $get_all_team_members = $ses_user_id;
        }

        $lead_data=[];
        if($get_all_team_members!=""){
            $user_cond = " and (b.lead_sales_agent_pk_no in(".$get_all_team_members.") or b.created_by in(".$get_all_team_members."))";
            
            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
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
            WHERE (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL)  $user_cond            
            AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)");//AND (a.lead_followup_datetime=CURDATE() OR a.lead_followup_datetime IS NULL) AND (Next_FollowUp_date=CURDATE() OR Next_FollowUp_date IS NULL)
        }
        return view('admin.sales_team_management.lead_followup.lead_follow_up', compact('lead_data', 'lead_stage_arr'));
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $create_date = date('Y-m-d');
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $current_stage = ($request->hdn_cur_stage!="")?$request->hdn_cur_stage:0;
        $new_stage = ($request->cmb_change_stage > 0)?$request->cmb_change_stage:$current_stage;
        $txt_followup_date = date("Y-m-d", strtotime($request->txt_followup_date));
        $txt_followup_date_time = date("Y-m-d H:i:s", strtotime($request->txt_followup_date ." ".$request->txt_followup_date_time));
        $lead_followup = DB::statement(
            DB::raw("CALL proc_leadfollowup_ins ('1','$create_date',$request->hdn_lead_pk_no,$request->cmbFollowupType,'$request->followup_note',$current_stage,1,'$txt_followup_date','$txt_followup_date_time','$request->next_followup_note',$new_stage,1,$ses_user_id,'$create_date')")
        );

        if(LeadFollowUp::where('lead_followup_pk_no', '=', $request->hdn_lead_followup_pk_no)->exists()) {
            $upd_followup = LeadFollowUp::find($request->hdn_lead_followup_pk_no);
            $upd_followup->next_followup_flag = 0;
            $upd_followup->save();
        }

        if($new_stage > 0){
            DB::statement(
                DB::raw("CALL proc_leadlifecycle_upd_stage ($request->hdn_lead_pk_no,'$create_date',$ses_user_id,$new_stage,$ses_user_id)")
            );
        }

        return response()->json(['message' => 'Lead Followup created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

        return view('admin.sales_team_management.lead_followup.lead_follow_up_form', compact('lead_data', 'lead_stage_arr', 'followup_type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
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
     * @param  \Illuminate\Http\Request $request
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
     * @param  int $id
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

        if(!empty($lead_data)){
            $flat_list = FlatSetup::where('project_lookup_pk_no', $lead_data->Project_pk_no)
            ->where('category_lookup_pk_no', $lead_data->project_category_pk_no)
            ->where('size_lookup_pk_no', $lead_data->project_size_pk_no)
            ->where('flat_status', 0)
            ->get(['flatlist_pk_no', 'flat_name']);
        }
        return view('admin.sales_team_management.lead_followup.lead_sold', compact('lead_data', 'lead_stage_arr', 'followup_type','flat_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_followup_leads(Request $request)
    {
        $is_team_leader = Session::get('user.is_team_leader');
        $ses_user_id = Session::get('user.ses_user_pk_no');

        if($is_team_leader == 1)
        {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
            $get_all_team_members = $get_all_team_member.",".$ses_user_id;
        }
        else
        {
            $get_all_team_members = $ses_user_id;
        }

        $user_cond = " and (b.lead_sales_agent_pk_no in(".$get_all_team_members.") or b.created_by in(".$get_all_team_members."))";

        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        if($request->tab_type == 1){
            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
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
            WHERE (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL)  $user_cond            
            AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)");//AND (a.lead_followup_datetime=CURDATE() OR a.lead_followup_datetime IS NULL) AND (Next_FollowUp_date=CURDATE() OR Next_FollowUp_date IS NULL)
            return view('admin.sales_team_management.lead_followup.lead_today_follow_up', compact('lead_data','lead_stage_arr'));
        }
        if($request->tab_type == 2){
            /*$lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.next_followup_Note,c.user_fullname agent_name,b.* FROM t_lead2lifecycle_vw b  LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.lead_followup_datetime < CURDATE() AND a.next_followup_flag=1 LEFT JOIN s_user c ON b.created_by=c.user_pk_no where (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) $user_cond AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)");*/

            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,
                a.next_followup_Note,c.user_fullname agent_name,b.*
                FROM t_lead2lifecycle_vw b
                LEFT JOIN t_leadfollowup a ON (a.lead_pk_no=b.lead_pk_no )
                LEFT JOIN s_user c ON b.created_by=c.user_pk_no
                WHERE a.lead_followup_pk_no = (SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no) $user_cond
                AND (b.lead_closed_flag=0 OR b.lead_closed_flag IS NULL)
                AND (b.lead_sold_flag=0 OR b.lead_sold_flag IS NULL)");//AND a.lead_followup_datetime<CURDATE()
            return view('admin.sales_team_management.lead_followup.lead_missed_follow_up', compact('lead_data','lead_stage_arr'));
        }
        if($request->tab_type == 3){
            $lead_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.next_followup_Note,c.user_fullname agent_name,b.* FROM t_lead2lifecycle_vw b  LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no AND a.next_followup_flag=1 LEFT JOIN s_user c ON b.created_by=c.user_pk_no  where (b.`lead_closed_flag`=0 OR b.`lead_closed_flag` IS NULL) $user_cond AND (b.`lead_sold_flag`=0 OR b.`lead_sold_flag` IS NULL)");//AND a.Next_FollowUp_date > CURDATE()
            return view('admin.sales_team_management.lead_followup.lead_next_follow_up', compact('lead_data','lead_stage_arr'));
        }
    }
}
