<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\LookupData;
use App\User;
use App\TeamAssign;
use App\Country;
use App\FlatSetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = [];
        foreach ($lookup_data as $value) {
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

            if ($value->lookup_type == 11)
                $press_adds[$key] = $value->lookup_name;

            if ($value->lookup_type == 12)
                $billboards[$key] = $value->lookup_name;

            if ($value->lookup_type == 13)
                $project_boards[$key] = $value->lookup_name;

            if ($value->lookup_type == 14)
                $flyers[$key] = $value->lookup_name;

            if ($value->lookup_type == 15)
                $fnfs[$key] = $value->lookup_name;
        }

        $countries = Country::where("iso3",'!=','')->get();
        return view('admin.lead_management.lead_entry', compact('project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs','countries'));
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
        $this->validate($request,[
            'customer_first_name' => 'required',
            'customer_last_name' => 'required',
            'customer_email' => 'required|email|max:255|regex:/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
            'customer_phone1' => 'required|numeric',
            'cmb_category' => 'required',
            'cmb_area' => 'required',
            'cmb_project_name' => 'required',
            'cmb_size' => 'required'
        ]);

        $verify_customer = DB::select("SELECT count(lead_id) total_lead FROM t_lead2lifecycle_vw WHERE lead_current_stage not in(6,7) and (phone1 in('$request->customer_phone1') or phone2 in('$request->customer_phone1') or email_id in('$request->customer_email'))")[0]->total_lead;

        if($verify_customer > 0)
        {
            return response()->json(['type' => 'error', 'message' => 'Another Lead is already in progress with this Customer information.', 'title' => 'Error', "positionClass" => "toast-top-right"]);
            die();
        }

        $create_date = date('Y-m-d');
        $user_id = Session::get('user.ses_user_pk_no');
        $user_type = Session::get('user.user_type');
        $is_bypass = Session::get('user.is_bypass');
        $bypass_date = Session::get('user.bypass_date');
        $ses_auto_dist = Session::get('user.ses_auto_dist');
        $ses_dist_date = Session::get('user.ses_dist_date');

        $digital_marketings = $sac_name = $sac_note = $ir_emp_id = $ir_emp_name = $ir_emp_position = "";
        if((isset($request->src_detail)) && $request->src_detail == 'SAC') {
            $sac_name = $request->txt_sac_name;
            $sac_note = $request->txt_sac_note;
        }

        if((isset($request->src_detail)) && $request->src_detail == 'DM') {
            $digital_mkt = $request->chk_digital_mark;
            for ($i = 0; $i < count($digital_mkt); $i++) {
                $digital_marketings .= $digital_mkt[$i] . ",";
            }
            $digital_marketings = rtrim($digital_marketings, ", ");
        }
        $ir_contract_no = 0;
        if((isset($request->src_detail)) && $request->src_detail == 'IR') {
            $ir_emp_id = $request->txt_emp_id;
            $ir_emp_name = $request->txt_emp_name;
            $ir_emp_position = $request->txt_emp_position;
            $ir_contract_no = $request->txt_contract_no;
        }

        $hotline_items = "";
        $hotline_item = (isset($request->chk_hotline_item)) ? $request->chk_hotline_item : [];
        if (!empty($hotline_item)) {
            for ($i = 0; $i < count($hotline_item); $i++) {
                $hotline_items .= $hotline_item[$i] . ",";
            }
        }

        $c_dob = $txt_marriage_anniversary = $txt_wife_dob = $txt_child_dob_1 = $txt_child_dob_2 = $txt_child_dob_3 = date("Y-m-d", strtotime('0000-01-01'));
        if(isset($request->chkKyc)) {
            $c_dob = date("Y-m-d", strtotime($request->txt_cust_dob));
            $txt_marriage_anniversary = date("Y-m-d", strtotime($request->txt_marriage_anniversary));
            $txt_wife_dob = date("Y-m-d", strtotime($request->txt_wife_dob));
            $txt_child_dob_1 = date("Y-m-d", strtotime($request->txt_child_dob_1));
            $txt_child_dob_2 = date("Y-m-d", strtotime($request->txt_child_dob_2));
            $txt_child_dob_3 = date("Y-m-d", strtotime($request->txt_child_dob_3));
        }
        $insert_date = date("Y-m-d");

        $lead_id = date("Y") . "" . str_pad(2, 4, '0', STR_PAD_LEFT);

        if($ses_auto_dist == 1 && $ses_dist_date <= date("Y-m-d"))
        {
            $lead_sales_agent = DB::select(
                DB::raw("CALL proc_getsalesagentauto( $request->cmb_category, $request->cmb_area)")
            );
        }
        $sales_agent = (!empty($lead_sales_agent) && $lead_sales_agent[0]->l_lead_sales_agent_pk_no) ? $lead_sales_agent[0]->l_lead_sales_agent_pk_no : 0;
        $first_name = ucwords($request->customer_first_name);
        $last_name = ucwords($request->customer_last_name);
        $sales_agent = 0;
        $lead_pk_no = DB::select(
            DB::raw("CALL proc_leads_ins ( $lead_id,'$first_name','$last_name','$request->country_code1','$request->customer_phone1','$request->country_code2','$request->customer_phone2','$request->customer_email','$request->cmb_ocupation',1,$request->cmb_category,$request->cmb_area,$request->cmb_project_name,$request->cmb_size,$request->hdn_source_role,$request->hdn_source_id,'$sac_name','$sac_note','$digital_marketings','$hotline_items','','$ir_emp_id','$ir_emp_name','$ir_emp_position',$ir_contract_no,1,'$c_dob','$request->txt_wife_name','$txt_marriage_anniversary','$txt_wife_dob','$request->txt_child_name_1','$txt_child_dob_1','$request->txt_child_name_2','$txt_child_dob_2','$request->txt_child_name_3','$txt_child_dob_3',1,$user_id,'$insert_date' )")
        );

        $lead_pk_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_pk_no : 0;
        $lead_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_id : 0;
        $datetime = date("Y-m-d",strtotime('00-00-0000'));
        $stage = ($user_type == 1)? 1:3;
        $lead_qc_datetime = $lead_k1_datetime = 'NULL';
        $lead_qc_flag = $lead_qc_by = $lead_k1_flag = $lead_k1_by = 0;

        if($is_bypass == 1 && $bypass_date <= date("Y-m-d"))
        {
            $lead_qc_flag = 1;
            $lead_qc_datetime = "'".date("Y-m-d")."'";
            $lead_qc_by = $user_id;
        }

        if($user_type == 2)
        {
            $lead_k1_flag = 1;
            $lead_k1_datetime = "'".date("Y-m-d")."'";
            $lead_k1_by = $user_id;
        }

        DB::statement(
            DB::raw("CALL proc_leadlifecycle_ins ( '1'," . $lead_pk_id . ",$sales_agent,$stage,'$lead_qc_flag',$lead_qc_datetime,'$lead_qc_by','$lead_k1_flag',$lead_k1_datetime,'$lead_k1_by',1,$user_id,'$create_date' )")
        );

        return response()->json(['message' => 'New Lead(' . $lead_id . ') created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_list($type)
    {
        $ses_other_user_id  = Session::get('user.ses_other_user_pk_no');
        if($ses_other_user_id==""){
            $ses_user_id        = Session::get('user.ses_user_pk_no');
        }
        else
        {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod      = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot      = Session::get('user.is_ses_other_hot');
        $is_other_team_leader  = Session::get('user.is_other_team_leader');

        if($is_ses_other_hod=="" && $ses_other_user_id==""){
            $is_ses_hod      = Session::get('user.is_ses_hod');
        }else{
            $is_ses_hod      = $is_ses_other_hod;
        }
        if($is_ses_other_hot=="" && $ses_other_user_id==""){
            $is_ses_hot      = Session::get('user.is_ses_hot');
        }else{
            $is_ses_hot      = $is_ses_other_hot;
        }
        if($is_other_team_leader=="" && $ses_other_user_id==""){
            $is_team_leader  = Session::get('user.is_team_leader');
        }else{
            $is_team_leader      = $is_other_team_leader;
        }

        $get_all_team_members = $user_cond = '';
        $team_arr = [];
        if($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1)
        {
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if(!empty($get_team_info))
            {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                    $get_all_team_members .= ($team->team_members!="")?$team->team_members.",".$ses_user_id:$ses_user_id;
                }
            }

        }
        else
        {
            $get_all_team_members = $ses_user_id;
        }

        $user_type = Session::get('user.user_type');

        if($user_type == 2)
        {
            $user_cond = " and (lead_sales_agent_pk_no in(".$get_all_team_members.") or created_by in(".$get_all_team_members."))";
        }
        else
        {
            $user_cond = " and created_by in(".$get_all_team_members.")";
        }

        if($type == 1)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_k1_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0)=0
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Leads";
        }
        if($type == 3)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_k1_flag,0) = 1 AND COALESCE(lead_priority_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "K1 Leads";
        }
        if($type == 4)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_priority_flag,0) = 1 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Priority Leads";
        }
        if($type == 13)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_transfer_from_sales_agent_pk_no,0) = 1
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Transferred Leads";
        }
        if($type == 14)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_transfer_flag,0) = 1
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Accepted Leads";
        }
        if($type == 7)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_sold_flag,0) =1
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Sold Leads";
        }
        if($type == 5)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_hold_flag,0) = 1
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Hold Leads";
        }
        if($type == 6)
        {
            $lead_data = DB::select("SELECT llc.*
                FROM t_lead2lifecycle_vw llc
                WHERE COALESCE(lead_closed_flag,0) = 1
                AND fnc_checkdataprivs(1,1) >0 $user_cond");
            $page_title = "Closed Leads";
        }

        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        return view('admin.components.lead_list', compact('lead_data', 'lead_stage_arr','page_title','team_arr','ses_other_user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function get_team_users(Request $request)
    {
        $team_id = $request->team_id;
        $users = User::where('is_super_admin', '!=' , 1)->orWhereNull('is_super_admin')->get();
        $user_arr = [];
        foreach ($users as $user) {
            $user_arr[$user->teamUser['user_pk_no']] = $user->name;
        }

        $hod_arr = $hot_arr = $tl_arr = $team_user = $agent_arr = [];
        $get_team_info = TeamAssign::where('team_lookup_pk_no', $team_id)->get();

        if(!empty($get_team_info))
        {
            foreach ($get_team_info as $team) {
                if($team->hod_user_pk_no!=0)
                    $hod_arr[$team->hod_user_pk_no]         = $user_arr[$team->hod_user_pk_no];
                if($team->hot_user_pk_no!=0)
                    $hot_arr[$team->hot_user_pk_no]         = $user_arr[$team->hot_user_pk_no];
                if($team->team_lead_user_pk_no!=0)
                    $tl_arr[$team->team_lead_user_pk_no]    = $user_arr[$team->team_lead_user_pk_no];
                if($team->hod_flag==0 && $team->hot_flag==0 && $team->team_lead_flag==0)
                    $agent_arr[$team->user_pk_no]           = $user_arr[$team->user_pk_no];
            }

            $team_user =  array (
                'hod_arr' => $hod_arr,
                'hot_arr' => $hot_arr,
                'tl_arr' => $tl_arr,
                'agent_arr' => $agent_arr
            );
        }

        return json_encode($team_user);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function load_area_project_size(Request $request)
    {
        $cat_id = $request->cat_id;

        $area_arr = $project_arr = $size_arr = [];
        $get_area_project_size_info = DB::select("SELECT a.area_lookup_pk_no,b.lookup_name area_name,a.size_lookup_pk_no,c.lookup_name size_name,a.project_lookup_pk_no,d.lookup_name project_name
            FROM s_projectwiseflatlist a
            LEFT JOIN s_lookdata b ON a.area_lookup_pk_no=b.lookup_pk_no
            LEFT JOIN s_lookdata c ON a.size_lookup_pk_no=c.lookup_pk_no
            LEFT JOIN s_lookdata d ON a.project_lookup_pk_no=d.lookup_pk_no
            WHERE a.category_lookup_pk_no=$cat_id");

        if(!empty($get_area_project_size_info))
        {
            foreach ($get_area_project_size_info as $aps) {
                if($aps->area_lookup_pk_no!="")
                    $area_arr[$aps->area_lookup_pk_no]         = $aps->area_name;
                if($aps->size_lookup_pk_no!="")
                    $size_arr[$aps->size_lookup_pk_no]         = $aps->size_name;
                if($aps->project_lookup_pk_no!="")
                    $project_arr[$aps->project_lookup_pk_no]   = $aps->project_name;
            }

            $aps_data =  array (
                'area_arr' => $area_arr,
                'size_arr' => $size_arr,
                'project_arr' => $project_arr
            );
        }

        return json_encode($aps_data);

    }

    public function lead_view($id)
    {
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = [];
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

            if ($value->lookup_type == 11)
                $press_adds[$key] = $value->lookup_name;

            if ($value->lookup_type == 12)
                $billboards[$key] = $value->lookup_name;

            if ($value->lookup_type == 13)
                $project_boards[$key] = $value->lookup_name;

            if ($value->lookup_type == 14)
                $flyers[$key] = $value->lookup_name;

            if ($value->lookup_type == 15)
                $fnfs[$key] = $value->lookup_name;
        }

        $lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $lead_data = DB::select("SELECT a.lead_followup_pk_no,b.*
            FROM t_lead2lifecycle_vw b
            LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no and a.next_followup_flag=1
            WHERE b.lead_pk_no=$id")[0];

        return view('admin.components.lead_view', compact('lead_data', 'lead_stage_arr', 'followup_type','project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs'));
    }
}
