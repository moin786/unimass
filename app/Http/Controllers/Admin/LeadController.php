<?php

namespace App\Http\Controllers\Admin;

use Response;

use Session;
use App\Lead;
use App\User;
use App\Country;
use App\TeamUser;
use App\FlatSetup;
use App\LookupData;
use App\TeamAssign;
use App\LeadHistory;
use App\LeadLifeCycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\LeadRequest;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = Session::get('user.ses_user_pk_no');
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 21, 22, 26, 29];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->where("lookup_row_status", 1)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $district = $thana = $area = $reference = $lead_status = $lead_source = [];
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
            if ($value->lookup_type == 21)
                $area[$key] = $value->lookup_name;
            if ($value->lookup_type == 22)
                $reference[$key] = $value->lookup_name;
            if ($value->lookup_type == 26)
                $lead_status[$key] = $value->lookup_name;
            if ($value->lookup_type == 29)
                $lead_source[$value->lookup_id] = $value->lookup_name;
        }
        $district = DB::table("districts")->get();
        $thana = DB::table("upazilas")->get();

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

        $is_user_type = Session::get('user.user_type');

        $countries = Country::where("iso3", '!=', '')->get();

        $source_type = config("static_arrays.source");
        return view('admin.lead_management.lead_entry', compact('project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'countries', 'area', 'thana', 'district', 'reference', 'is_user_type', 'sales_agent_info', 'source_type', 'lead_status', 'lead_source'));
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
    public function store(LeadRequest $request)
    {
        //chk_digital_mark
        $verify_customer = DB::select("SELECT count(lead_id) total_lead FROM t_lead2lifecycle_vw WHERE (phone1 in('$request->customer_phone1') or phone2 in('$request->customer_phone1') ) and lead_current_stage not in(5,6,7)")[0]->total_lead; // lead_current_stage not in(6,7) and

        if ($verify_customer > 0) {
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

        $get_team_lead = DB::select("SELECT a.team_lead_user_pk_no,is_bypass,bypass_date,auto_distribute,distribute_date FROM t_teambuild a,s_user b WHERE a.user_pk_no=$user_id and a.team_lead_user_pk_no=b.user_pk_no");

        $digital_marketings = $sac_name = $sac_note = $ir_emp_name = $ir_emp_position = "";
        $ir_emp_id = 0;
        if ((isset($request->src_detail)) && $request->src_detail == 'SAC') {
            $sac_name = $request->txt_sac_name;
            $sac_note = $request->txt_sac_note;
        }

        if ((isset($request->src_detail)) && $request->src_detail == 'DM') {
            $digital_mkt = $request->Sub_Source;
            for ($i = 0; $i < count($digital_mkt); $i++) {
                $digital_marketings .= $digital_mkt[$i] . ",";
            }
            $digital_marketings = rtrim($digital_marketings, ", ");
        }
        $ir_contract_no = 0;
        if ((isset($request->hdn_source_role)) && $request->hdn_source_role == 75) {
            $ir_emp_id = $request->txt_emp_id;
            $ir_emp_name = $request->txt_emp_name;
            $ir_emp_position = $request->txt_emp_position;
            $ir_contract_no = $request->txt_contract_no;
        }

        $hotline_items = "";
        if ((isset($request->hotline))) {
            $hotline = $request->hotline;
            for ($i = 0; $i < count($hotline); $i++) {
                if ($hotline[$i] != "") {
                    if ($hotline[$i] != '0') {
                        $hotline_items .= $hotline[$i];
                    }
                }
            }
        }
        $digital_mkt = $request->Sub_Source;
        for ($i = 0; $i < count($digital_mkt); $i++) {
            $digital_marketings .= $digital_mkt[$i] . ",";
        }
        $digital_marketings = rtrim($digital_marketings, ", ");


        $c_dob = $c_dob2 = $txt_marriage_anniversary = $txt_wife_dob = $txt_child_dob_1 = $txt_child_dob_2 = $txt_child_dob_3 = date("Y-m-d", strtotime('0000-01-01'));
        if (isset($request->chkKyc)) {
            $c_dob = date("Y-m-d", strtotime($request->txt_cust_dob));
            $c_dob2 = date("Y-m-d", strtotime($request->txt_cust_dob2));
            $txt_marriage_anniversary = date("Y-m-d", strtotime($request->txt_marriage_anniversary));
            $txt_wife_dob = date("Y-m-d", strtotime($request->txt_wife_dob));
            $txt_child_dob_1 = date("Y-m-d", strtotime($request->txt_child_dob_1));
            $txt_child_dob_2 = date("Y-m-d", strtotime($request->txt_child_dob_2));
            $txt_child_dob_3 = date("Y-m-d", strtotime($request->txt_child_dob_3));
        }
        $insert_date = date("Y-m-d H:i:s");

        // sales agent assign start
        //$sales_agent = (!empty($lead_sales_agent) && $lead_sales_agent[0]->l_lead_sales_agent_pk_no) ? $lead_sales_agent[0]->l_lead_sales_agent_pk_no : 0;
        $first_name = ucwords($request->customer_first_name);
        $last_name = ucwords($request->customer_last_name);
        $first_name2 = ucwords($request->customer_first_name2);
        $last_name2 = ucwords($request->customer_last_name2);
        if ($request->hdn_source_role == 75 || $request->hdn_source_role == 203 || $request->hdn_source_role == 119) {
            if (!empty($get_team_lead)) {
                if ($get_team_lead[0]->auto_distribute == 1 && (date("Y-m-d")) <= date("Y-m-d", strtotime($get_team_lead[0]->distribute_date))) {
                    $sales_agent = DB::select(
                        DB::raw("CALL proc_getsalesagentauto_ind( $request->cmb_category, $request->cmb_area)")
                    )[0]->l_user_pk_no1 * 1;
                    $dist_type = 1;
                } else {
                    $sales_agent = ($request->sales_user_name > 0) ? $request->sales_user_name : 0;
                    $dist_type = 0;
                }
            } else {
                $sales_agent = ($request->sales_user_name > 0) ? $request->sales_user_name : 0;
                $dist_type = 0;
            }
        } else {
            if ($user_type == 2) {
                $sales_agent = $user_id;
                $dist_type = 0;
            } else {
                if (!empty($get_team_lead)) {
                    if ($get_team_lead[0]->auto_distribute == 1 && (date("Y-m-d")) <= date("Y-m-d", strtotime($get_team_lead[0]->distribute_date))) {
                        $sales_agent = DB::select(
                            DB::raw("CALL proc_getsalesagentauto_ind( $request->cmb_category, $request->cmb_area)")
                        )[0]->l_user_pk_no1 * 1;
                        $dist_type = 1;
                    } else {
                        $sales_agent = $dist_type = 0;
                    }
                } else {
                    $sales_agent = $dist_type = 0;
                }
            }
        }
        // sales agent assign end

        // Lead entry procedure
        $remarks = addslashes($request->remarks);
        $pre_area = $request->txt_present_area;
        $pre_district = $request->txt_present_district;
        $pre_thana = $request->txt_present_thana;
        $par_area = $request->txt_parmanent_area;
        $par_district = $request->txt_parmanent_district;
        $par_thana = $request->txt_parmanent_thana;
        $org_area = $request->txt_organization_area;
        $org_district = $request->txt_organization_district;
        $org_thana = $request->txt_organization_thana;


        $user_type_data = (!empty($request->Source)) ?
            $request->Source : " ";
        $lead_id = $user_type_data . "" .
            date("Y") . "" . str_pad(2, 4, '0', STR_PAD_LEFT);
        $meeting_date =
            (!empty($request->txt_meeting_date)) ? date("Y-m-d", strtotime($request->txt_meeting_date)) : "0000-01-01";
        // dd($request->txt_meeting_time);
        $meeting_time = (!empty($request->txt_meeting_time)) ? date("Y-m-d H:i:s", strtotime($request->txt_meeting_date . " " . $request->txt_meeting_time)) : "0000-01-01 00:00:00";

        $txt_meeting_status = (!empty($request->txt_meeting_status) ?
            $request->txt_meeting_status : "0");

        $lead_pk_no = DB::select(
            DB::raw("CALL proc_leads_ins ( '$lead_id','$first_name','$last_name','$first_name2','$last_name2','$request->country_code1','$request->customer_phone1','$request->country_code2','$request->customer_phone2','$request->customer_email','$request->cmb_ocupation','$request->organization','$request->designation',$request->cmb_category,$request->cmb_area,$request->cmb_project_name,$request->cmb_size,$request->hdn_source_id,$request->hdn_source_role,'$request->sub_source_name','$sac_name','$sac_note','$digital_marketings','$hotline_items','','$ir_emp_id','$ir_emp_name','$ir_emp_position',$ir_contract_no,1,'$c_dob', '$c_dob2','$request->txt_wife_name','$txt_wife_dob','$txt_marriage_anniversary','$request->txt_child_name_1','$txt_child_dob_1','$request->txt_child_name_2','$txt_child_dob_2','$request->txt_child_name_3','$txt_child_dob_3','$remarks','$request->txt_present_housing_no','$request->txt_present_road_no','$pre_area','$pre_district','$pre_thana','$request->txt_size_no','$request->txt_parmanent_house_no','$request->txt_parmanent_road_address','$par_area','$par_district','$par_thana','$request->txt_organization_housing_no','$request->txt_organization_road_no','$org_area','$org_district','$org_thana','$txt_meeting_status','$meeting_date','$meeting_time','$request->food_habit','$request->political_opinion','$request->car_pre','$request->color_preference','$request->hobby','$request->traveling_history','$request->memberofclub','$request->child_education','$request->disease_name',1,$user_id,'$insert_date' )")
        );
        /*
                $lead_pk_no  = DB::select(
                    DB::raw("CALL proc_leads_ins ( $lead_id,'$first_name','$last_name','$request->country_code1','$request->customer_phone1','$request->country_code2','$request->customer_phone2','$request->customer_email','$request->cmb_ocupation',1,$request->cmb_category,$request->cmb_area,$request->cmb_project_name,$request->cmb_size,$request->hdn_source_id,$request->hdn_source_role,'$request->sub_source_name','$sac_name','$sac_note','$digital_marketings','$hotline_items','','$ir_emp_id','$ir_emp_name','$ir_emp_position',$ir_contract_no,1,'$c_dob','$request->txt_wife_name','$txt_wife_dob','$txt_marriage_anniversary','$request->txt_child_name_1','$txt_child_dob_1','$request->txt_child_name_2','$txt_child_dob_2','$request->txt_child_name_3','$txt_child_dob_3','$remarks','$request->txt_present_housing_no','$request->txt_present_road_no','$request->txt_present_area','$request->txt_present_district','$request->txt_present_thana','$request->txt_parmanent_house_no','$request->txt_parmanent_road_address','$request->txt_parmanent_area','$request->txt_parmanent_district','$request->txt_parmanent_thana','$request->txt_organization_housing_no','$request->txt_organization_road_no','$request->txt_organization_area','$request->txt_organization_district','$request->txt_organization_thana','$request->food_habit','$request->political_opinion','$request->car_pre','$request->color_preference','$request->hobby','$request->traveling_history','$request->memberofclub','$request->child_education','$request->disease_name',1,$user_id,'$insert_date' )")
                );   */

        //Back Up Query
        /* $lead_pk_no  = DB::select(
            DB::raw("CALL proc_leads_ins ( $lead_id,'$first_name','$last_name','$request->country_code1','$request->customer_phone1','$request->country_code2','$request->customer_phone2','$request->customer_email','$request->cmb_ocupation',1,$request->cmb_category,$request->cmb_area,$request->cmb_project_name,$request->cmb_size,$request->hdn_source_id,$request->hdn_source_role,'$request->sub_source_name','$sac_name','$sac_note','$digital_marketings','$hotline_items','','$ir_emp_id','$ir_emp_name','$ir_emp_position',$ir_contract_no,1,'$c_dob','$request->txt_wife_name','$txt_wife_dob','$txt_marriage_anniversary','$request->txt_child_name_1','$txt_child_dob_1','$request->txt_child_name_2','$txt_child_dob_2','$request->txt_child_name_3','$txt_child_dob_3','$remarks',1,$user_id,'$insert_date' )")
        ); */


        $lead_pk_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_pk_no : 0;
        $lead_id = (!empty($lead_pk_no)) ? $lead_pk_no[0]->l_lead_id : 0;
        $datetime = date("Y-m-d", strtotime('00-00-0000'));
        $stage = ($user_type == 1) ? 1 : 3;

        $lead_qc_datetime = $lead_k1_datetime = 'NULL';
        $lead_qc_flag = $lead_qc_by = $lead_k1_flag = $lead_k1_by = 0;


        if (!empty($get_team_lead)) {
            if ($get_team_lead[0]->is_bypass == 1 && (date("Y-m-d", strtotime($get_team_lead[0]->bypass_date)) >= date("Y-m-d"))) {
                $lead_qc_flag = 1;
                $lead_qc_datetime = "'" . date("Y-m-d") . "'";
                $lead_qc_by = $user_id;
            }
        }

        if ($user_type == 2) {
            $lead_k1_flag = 1;
            $lead_k1_datetime = "'" . date("Y-m-d") . "'";
            $lead_k1_by = $user_id;
            $assigned_sales_agent = $user_id;
        } else {
            $assigned_sales_agent = 0;
        }
        DB::statement(
            DB::raw("CALL proc_leadlifecycle_ins ('1',$lead_pk_id,$dist_type,'$user_type_data','$request->txt_cluster_head','$insert_date',$assigned_sales_agent,'$insert_date',$stage,'$lead_qc_flag',$lead_qc_datetime,'$lead_qc_by','$lead_k1_flag',$lead_k1_datetime,'$lead_k1_by',1,$user_id,'$create_date' )")
        );

        $redirectPage = 'lead';

        return response()->json([
            'message' => 'New Lead(' . $lead_id . ') created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right",
            'redirectPage' => $redirectPage
        ]);
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
        $lookup_arr = [2, 3, 4, 5, 6, 7, 8, 9, 10, 19, 20, 21];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        $project_cat = $project_area = $project_name = $project_size = $hotline = $ocupations = $press_adds = $billboards = $project_boards = $flyers = $fnfs = $digital_mkt = $followup_type = $district = $thana = $area = $reference = [];
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
            if ($value->lookup_type == 19)
                $district[$key] = $value->lookup_name;
            if ($value->lookup_type == 20)
                $thana[$key] = $value->lookup_name;
            if ($value->lookup_type == 21)
                $area[$key] = $value->lookup_name;
        }

        //$lookup_data = LookupData::all();
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $lead_data = DB::select("SELECT a.lead_followup_pk_no,b.*
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no and a.next_followup_flag=1
    		WHERE b.lead_pk_no=$id")[0];

        $lead_transfer_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname from_sales_agent, g.user_fullname to_sales_agent
    		FROM t_leadtransferhistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.transfer_from_sales_agent_pk_no=f.user_pk_no
    		LEFT JOIN s_user g ON a.transfer_to_sales_agent_pk_no=g.user_pk_no
    		WHERE lead_pk_no=$id");

        $lead_followup_data = DB::select("SELECT lead_followup_datetime,followup_Note,Next_FollowUp_date,next_followup_Prefered_Time,next_followup_Note,lead_stage_before_followup,lead_stage_after_followup from t_leadfollowup where lead_pk_no=$id");
        $lead_stage_data = DB::select("SELECT b.lookup_name category,c.lookup_name area_name,d.lookup_name project_name,e.lookup_name size_name, f.user_fullname sales_agent,
    		a.lead_stage_before_update,a.lead_stage_after_update
    		FROM t_leadstagehistory a
    		LEFT JOIN s_lookdata b ON a.project_category_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.project_area_pk_no =c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.Project_pk_no=d.lookup_pk_no
    		LEFT JOIN s_lookdata e ON a.project_size_pk_no=e.lookup_pk_no
    		LEFT JOIN s_user f ON a.sales_agent_pk_no=f.user_pk_no
    		WHERE a.lead_pk_no=$id");
        $countries = Country::where("iso3", '!=', '')->get();

        $lookup_data = LookupData::where('lookup_type', 10)->where("lookup_row_status", 1)->get();
        $ocupations = [];
        foreach ($lookup_data as $key => $value) {
            $key = $value->lookup_pk_no;
            if ($value->lookup_type == 10)
                $ocupations[$key] = $value->lookup_name;
        }
        $distric_arra = DB::table("districts")->get();
        $thana_arra = DB::table("upazilas")->get();

        foreach ($distric_arra as $key) {
            $district[$key->id] = $key->district_name;
        }
        foreach ($thana_arra as $key) {
            $thana[$key->id] = $key->thana_name;
        }

        return view('admin.components.lead_edit', compact('lead_data', 'lead_stage_arr', 'followup_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_transfer_data', 'lead_followup_data', 'lead_stage_data', 'countries', 'area', 'thana', 'district', 'ocupations'));
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
        // return $request;
        $this->validate($request, [
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
            'customer_phone1' => 'required|string',
            'customer_phone2' => 'nullable|string',
            'customer_email' => 'required|email',
            // 'txt_organization' => 'required'
        ]);

        // return $request;
        $verify_customer = DB::select("SELECT count(lead_id) total_lead FROM t_lead2lifecycle_vw WHERE (phone1 in('$request->customer_phone1') or phone2 in('$request->customer_phone1') ) and lead_current_stage not in(5,6,7) and lead_pk_no!=$id")[0]->total_lead; // lead_current_stage not in(6,7) and

        if ($verify_customer > 0) {
            return response()->json(['type' => 'error', 'message' => 'Another Lead is already in progress with this Customer information.', 'title' => 'Error', "positionClass" => "toast-top-right"]);
            die();
        }

        $ldata = Lead::findOrFail($id);

        $lhistory = new LeadHistory();
        $lhistory->lead_pk_no = $id;
        $lhistory->customer_firstname = $ldata->customer_firstname;
        $lhistory->customer_lastname = $ldata->customer_lastname;
        $lhistory->phone1_code = $ldata->phone1_code;
        $lhistory->phone1 = $ldata->phone1;
        $lhistory->phone2_code = $ldata->phone2_code;
        $lhistory->phone2 = $ldata->phone2;
        $lhistory->email_id = $ldata->email_id;
        $lhistory->organization_pk_no = $ldata->organization_pk_no;
        $lhistory->customer_firstname2 = $ldata->customer_firstname2;
        $lhistory->customer_lastname2 = $ldata->customer_lastname2;
        $lhistory->cust_designation = $ldata->cust_designation;

        $lhistory->pre_holding_no = $ldata->pre_holding_no;
        $lhistory->pre_road_no = $ldata->pre_road_no;
        $lhistory->pre_area = $ldata->pre_area;
        $lhistory->pre_district = $ldata->pre_district;
        $lhistory->pre_thana = $ldata->pre_thana;

        $lhistory->per_holding_no = $ldata->per_holding_no;
        $lhistory->per_road_no = $ldata->per_road_no;
        $lhistory->per_area = $ldata->per_area;
        $lhistory->per_district = $ldata->per_district;
        $lhistory->per_thana = $ldata->per_thana;

        $lhistory->office_holding_no = $ldata->office_holding_no;
        $lhistory->office_road_no = $ldata->office_road_no;
        $lhistory->office_area = $ldata->office_area;
        $lhistory->office_district = $ldata->office_district;
        $lhistory->office_thana = $ldata->office_thana;
        $lhistory->remarks = $ldata->remarks;

        $kycInfo = [
            "lead_pk_no" => $ldata->lead_pk_no,
            "lead_id" => $ldata->lead_id,
            "Customer_dateofbirth" => $ldata->Customer_dateofbirth,
            "Customer_dateofbirth2" => $ldata->Customer_dateofbirth2,
            "customer_wife_name" => $ldata->customer_wife_name,
            "customer_wife_dataofbirth" => $ldata->customer_wife_dataofbirth,
            "Marriage_anniversary" => $ldata->Marriage_anniversary,
            "children_name1" => $ldata->children_name1,
            "children_dateofbirth1" => $ldata->children_dateofbirth1,
            "children_name2" => $ldata->children_name2,
            "children_dateofbirth2" => $ldata->children_dateofbirth2,
            "children_name3" => $ldata->children_name3,
            "children_dateofbirth3" => $ldata->children_dateofbirth3,
            "food_habit" => $ldata->food_habit,
            "political_opinion" => $ldata->political_opinion,
            "car_preference" => $ldata->car_preference,
            "color_preference" => $ldata->color_preference,
            "hobby" => $ldata->hobby,
            "traveling_history" => $ldata->traveling_history,
            "member_of_club" => $ldata->member_of_club,
            "child_education" => $ldata->child_education,
            "disease_name" => $ldata->disease_name,
            "created_by" => Session::get('user.ses_user_pk_no'),
            "created_at" => date('Y-m-d'),
        ];
        DB::table("t_leadkychistory")->insert($kycInfo);
        if ($lhistory->save()) {
            //$ldata = Lead::findOrFail($id);
            $ldata->customer_firstname = ucwords($request->customer_first_name);
            $ldata->customer_lastname = ucwords($request->customer_last_name);
            $ldata->phone1_code = $request->country_code1;
            $ldata->phone1 = $request->customer_phone1;
            $ldata->phone2_code = $request->country_code2;
            $ldata->phone2 = $request->customer_phone2;
            $ldata->email_id = $request->customer_email;
            $ldata->organization_pk_no = $request->txt_organization;

            $ldata->customer_firstname2 = ucwords($request->customer_firstname2);
            $ldata->customer_lastname2 = ucwords($request->customer_lastname2);
            $ldata->cust_designation = $request->designation;

            $ldata->pre_holding_no = $request->pre_holding_no;
            $ldata->pre_road_no = $request->pre_road_no;
            $ldata->pre_area = $request->pre_area;
            $ldata->pre_district = $request->pre_district;
            $ldata->pre_thana = $request->pre_thana;
            $ldata->pre_size = $request->txt_size_no;

            $ldata->per_holding_no = $request->per_holding_no;
            $ldata->per_road_no = $request->per_road_no;
            $ldata->per_area = $request->per_area;
            $ldata->per_district = $request->per_district;
            $ldata->per_thana = $request->per_thana;

            $ldata->office_holding_no = $request->office_holding_no;
            $ldata->office_road_no = $request->office_road_no;
            $ldata->office_area = $request->office_area;
            $ldata->office_district = $request->office_district;
            $ldata->office_thana = $request->office_thana;
            $ldata->remarks = $request->remarks;

            $ldata->Customer_dateofbirth  = date("Y-m-d", strtotime($request->txt_cust_dob));
            $ldata->Customer_dateofbirth2 = date("Y-m-d", strtotime($request->txt_cust_dob2));
            $ldata->customer_wife_name = $request->txt_wife_name;
            $ldata->customer_wife_dataofbirth = date("Y-m-d", strtotime($request->txt_wife_dob));
            $ldata->Marriage_anniversary     = date("Y-m-d", strtotime($request->txt_marriage_anniversary));
            $ldata->children_name1           = $request->txt_child_name_1;
            $ldata->children_dateofbirth1    = date("Y-m-d", strtotime($request->txt_child_dob_1));
            $ldata->children_name2           = $request->txt_child_name_2;
            $ldata->children_dateofbirth2   = date("Y-m-d", strtotime($request->txt_child_dob_2));
            $ldata->children_name3          = $request->txt_child_name_3;
            $ldata->children_dateofbirth3   = date("Y-m-d", strtotime($request->txt_child_dob_3));
            $ldata->food_habit              = $request->food_habit;
            $ldata->political_opinion      = $request->political_opinion;
            $ldata->car_preference       = $request->car_pre;
            $ldata->color_preference    = $request->color_preference;
            $ldata->hobby               = $request->hobby;
            $ldata->traveling_history    = $request->traveling_history;
            $ldata->member_of_club      = $request->memberofclub;
            $ldata->child_education     = $request->child_education;
            $ldata->disease_name        = $request->disease_name;
            $ldata->save();

            return response()->json(['message' => 'Lead Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Lead Data update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_list($type, $transfer_type = 0, $from_dt = "", $to_dt = "")
    {
        ini_set('memory_limit', '2048M');
        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }


        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
            $userRoleID = Session::get('user.ses_role_lookup_pk_no');
        } else {
            $is_ses_hod = $is_ses_other_hod;
            $userRoleID = 0;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_tem_members = null;

        if ($userRoleID == 551) {
            $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id )")[0]->team_members;

            $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
            $get_all_team_members = rtrim(($get_all_tem_members), ", ");
            $team_arr = [];
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                }
            }
        } else {
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
            $get_all_team_members = ltrim(rtrim(($get_all_tem_members), ", "), ", ");


            $team_arr = [];
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                }
            }
        }

        $user_type = Session::get('user.user_type');
        $is_super_admin = Session::get('user.is_super_admin');
        $is_ch = Session::get('user.is_ses_hod');

        if ($is_super_admin == 1) {
            $user_cond = '';
        } else {
            if ($userRoleID == 551) {
                $user_cond = '';
            } else {
                if ($user_type == 2) {
                    $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";
                } else {
                    $user_cond = " and llc.created_by in(" . $get_all_team_members . ")";
                }
            }
        }

        $fromdate = date("Y-m-d", strtotime($from_dt));
        $todate = date("Y-m-d", strtotime($to_dt));
        if ($from_dt != "" && $todate != "") {
            $date_cond = "and llc.created_at BETWEEN '$fromdate' AND '$todate'";
            $sold_cond = "and llc.lead_sold_date_manual BETWEEN '$fromdate' AND '$todate'";
        } else if ($from_dt != "" && $todate == "") {
            $date_cond = "and llc.created_at='$fromdate'";
            $sold_cond = "and llc.lead_sold_date_manual='$fromdate'";
        } else {
            $date_cond = "";
            $sold_cond = "";
        }

        if ($type == 1) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE lead_current_stage in(1)
             $user_cond $date_cond order by llc.created_at desc , llc.created_at desc");

            $page_title = "Leads";
        }
        if ($type == 3) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE  lead_current_stage=3
             $user_cond $date_cond order by llc.created_at desc , llc.lead_pk_no desc");
            $page_title = "Cool";
        }
        if ($type == 4) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE lead_current_stage=4
             $user_cond $date_cond order by llc.created_at desc , llc.lead_pk_no desc");
            $page_title = "Warm Leads";
        }
        if ($type == 13) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE lead_current_stage=13
             $user_cond $date_cond order by llc.created_at desc , llc.lead_pk_no desc");
            $page_title = "Hot Leads";
        }

        if ($type == 7) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE lead_current_stage=7
             $user_cond $sold_cond order by llc.created_at desc , llc.lead_pk_no desc");
            $page_title = "Sold Leads";
        }
        if ($type == 5) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE  lead_current_stage=5
             $user_cond $date_cond order by llc.created_at desc ,llc.lead_pk_no desc");
            $page_title = "Hold Leads";
        }
        if ($type == 6) {
            $lead_data = DB::select("SELECT llc.*,u.user_fullname
             FROM t_lead2lifecycle_vw llc
             left join s_user u on llc.created_by=u.user_pk_no
             WHERE lead_current_stage=6
             $user_cond $date_cond order by llc.created_at desc ,llc.lead_pk_no desc");
            $page_title = "Closed Leads";
        }

        if ($user_type == 2 || $is_super_admin == 1) {

            if ($transfer_type == 1) {

                if ($userRoleID == 551 || $is_super_admin == 1) {
                    $lead_data = DB::select("SELECT llc.*,u.user_fullname
                   FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
                   left join s_user u on llc.created_by=u.user_pk_no
                   WHERE lt.lead_pk_no=llc.lead_pk_no AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1
                   $date_cond order by  llc.created_at desc");

                    $type = 15;
                    $page_title = "Transferred ";
                } else {
                    $lead_data = DB::select("SELECT llc.*,u.user_fullname
                   FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
                   left join s_user u on llc.created_by=u.user_pk_no
                   WHERE lt.lead_pk_no=llc.lead_pk_no AND lead_transfer_flag=1  AND transfer_to_sales_agent_flag=1 and re_transfer=1
                   and transfer_from_sales_agent_pk_no=$ses_user_id $date_cond order by llc.created_at desc");
                    $type = 15;
                    $page_title = "Transferred ";
                }
            }

            if ($transfer_type == 2) {
                $lead_data = DB::select("SELECT llc.*,u.user_fullname
            FROM t_leadtransfer lt, t_lead2lifecycle_vw llc
            left join s_user u on llc.created_by=u.user_pk_no
            WHERE lt.lead_pk_no=llc.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
            and transfer_to_sales_agent_pk_no=$ses_user_id $date_cond order by llc.lead_pk_no desc");
                $type = 15;
                $page_title = "Transferred ";
            }
        }

        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        return view('admin.components.lead_list', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id', 'type', 'ses_user_id', 'user_type', 'is_ch', 'userRoleID'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function lead_list_view($type, $transfer_type = 0)
    {
        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_team_members = $user_cond = '';
        $team_arr = [];
        if ($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) {
            $get_team_info = DB::select("SELECT a.team_lookup_pk_no,b.lookup_name team_name,GROUP_CONCAT(a.user_pk_no) team_members FROM t_teambuild a,s_lookdata b WHERE a.team_lookup_pk_no=b.lookup_pk_no AND (a.team_lead_user_pk_no=$ses_user_id OR a.hod_user_pk_no=$ses_user_id OR a.hot_user_pk_no=$ses_user_id) GROUP BY a.team_lookup_pk_no,b.lookup_name");
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $team_arr[$team->team_lookup_pk_no] = $team->team_name;
                    $get_all_team_members .= ($team->team_members != "") ? $team->team_members . "," . $ses_user_id : $ses_user_id;
                }
            }
        } else {
            $get_all_team_members = $ses_user_id;
        }

        $user_type = Session::get('user.user_type');
        $is_super_admin = Session::get('user.is_super_admin');

        if ($is_super_admin == 1) {
            $user_cond = '';
        } else {
            if ($user_type == 2) {
                $user_cond = " and (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or created_by in(" . $get_all_team_members . "))";
            } else {
                $user_cond = " and created_by in(" . $get_all_team_members . ")";
            }
        }

        if ($type == 1) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_k1_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0)=0 AND COALESCE(lead_priority_flag,0)=0 AND COALESCE(lead_transfer_flag,0)=0 AND lead_current_stage in(1,10,11)
    			$user_cond");

            $page_title = "Leads";
        }
        if ($type == 3) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_k1_flag,0) = 1 AND COALESCE(lead_priority_flag,0) = 0 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0 AND lead_current_stage=3
    			$user_cond");
            $page_title = "K1 Leads";
        }
        if ($type == 4) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_priority_flag,0) = 1 AND COALESCE(lead_hold_flag,0) = 0 AND COALESCE(lead_closed_flag,0) = 0 AND COALESCE(lead_sold_flag,0) =0
    			$user_cond");
            $page_title = "Priority Leads";
        }
        if ($type == 13) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_transfer_from_sales_agent_pk_no,0) = 1
    			$user_cond");
            $page_title = "Transferred Leads";
        }
        if ($type == 14) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_transfer_flag,0) = 1
    			$user_cond");
            $page_title = "Accepted Leads";
        }
        if ($type == 7) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_sold_flag,0) =1
    			$user_cond");
            $page_title = "Sold Leads";
        }
        if ($type == 5) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_hold_flag,0) = 1 AND lead_current_stage=5
    			$user_cond");
            $page_title = "Hold Leads";
        }
        if ($type == 6) {
            $lead_data = DB::select("SELECT llc.*
    			FROM t_lead2lifecycle_vw llc
    			WHERE COALESCE(lead_closed_flag,0) = 1
    			$user_cond");
            $page_title = "Closed Leads";
        }

        if ($user_type == 2) {
            if ($transfer_type == 1) {
                $lead_data = DB::select("SELECT llc.*
    				FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
    				WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 0
    				and transfer_from_sales_agent_pk_no=$ses_user_id");
            }

            if ($transfer_type == 2) {
                $lead_data = DB::select("SELECT llc.*
    				FROM t_lead2lifecycle_vw llc,t_leadtransfer lt
    				WHERE llc.lead_pk_no=lt.lead_pk_no and COALESCE(transfer_to_sales_agent_flag,0) = 1
    				and transfer_to_sales_agent_pk_no=$ses_user_id");
            }
        }
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        return view('admin.components.lead_list_view', compact('lead_data', 'lead_stage_arr', 'page_title', 'team_arr', 'ses_other_user_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function get_team_users(Request $request)
    {
        $team_id = $request->team_id;
        $users = User::where('is_super_admin', '!=', 1)->orWhereNull('is_super_admin')->get();

        $user_arr = [];
        foreach ($users as $user) {
            $value = (!empty($user->teamUser['user_pk_no'])) ? $user->teamUser['user_pk_no'] : "0";
            $user_arr[$value] = $user->name;
        }


        $hod_arr = $hot_arr = $tl_arr = $team_user = $agent_arr = [];
        $get_team_info = TeamAssign::where('team_lookup_pk_no', $team_id)->get();


        if (!empty($get_team_info)) {
            foreach ($get_team_info as $team) {
                if ($team->hod_user_pk_no != 0)
                    $hod_arr[$team->hod_user_pk_no] = $user_arr[$team->hod_user_pk_no];
                if ($team->hot_user_pk_no != 0)
                    $hot_arr[$team->hot_user_pk_no] = $user_arr[$team->hot_user_pk_no];
                if ($team->team_lead_user_pk_no != 0)
                    $tl_arr[$team->team_lead_user_pk_no] = $user_arr[$team->team_lead_user_pk_no];
                if ($team->hod_flag == 0 && $team->hot_flag == 0 && $team->team_lead_flag == 0)
                    $agent_arr[$team->user_pk_no] = $user_arr[$team->user_pk_no];
            }

            //dd($agent_arr);
            $team_user = array(
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function load_area_project_size(Request $request)
    {
        $cat_id = $request->cat_id;
        $area_id = $request->area_id;

        $area_arr = $project_arr = $size_arr = [];
        $area_cond = ($area_id > 0) ? " and a.area_lookup_pk_no=$area_id" : "";
        $get_area_project_size_info = DB::select("SELECT a.area_lookup_pk_no,b.lookup_name area_name,a.size_lookup_pk_no,c.lookup_name size_name,a.project_lookup_pk_no,d.lookup_name project_name
    		FROM s_projectwiseflatlist a
    		LEFT JOIN s_lookdata b ON a.area_lookup_pk_no=b.lookup_pk_no
    		LEFT JOIN s_lookdata c ON a.size_lookup_pk_no=c.lookup_pk_no
    		LEFT JOIN s_lookdata d ON a.project_lookup_pk_no=d.lookup_pk_no
    		WHERE a.category_lookup_pk_no=$cat_id $area_cond and b.lookup_row_status=1 and c.lookup_row_status=1 and d.lookup_row_status=1");

        if (!empty($get_area_project_size_info)) {
            foreach ($get_area_project_size_info as $aps) {
                if ($aps->area_lookup_pk_no != "")
                    $area_arr[$aps->area_lookup_pk_no] = $aps->area_name;
                if ($aps->size_lookup_pk_no != "")
                    $size_arr[$aps->size_lookup_pk_no] = $aps->size_name;
                if ($aps->project_lookup_pk_no != "")
                    $project_arr[$aps->project_lookup_pk_no] = $aps->project_name;
            }
        }

        $category_wise_agent_data = DB::table('s_user')
            ->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('s_user.user_type', 2)
            ->where('t_teambuild.hod_flag', 0)
            ->where('t_teambuild.hot_flag', 0)
            ->where('t_teambuild.team_lead_flag', 0)
            ->where('category_lookup_pk_no', $cat_id)
            ->get();

        $sales_agent = [];
        if (!empty($category_wise_agent_data)) {
            foreach ($category_wise_agent_data as $row) {
                $sales_agent[$row->user_pk_no] = $row->user_fullname;
            }
        }

        $aps_data = array(
            'area_arr' => $area_arr,
            'size_arr' => $size_arr,
            'project_arr' => $project_arr,
        );

        return json_encode($aps_data);
    }

    public function lead_view($id)
    {
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

        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        $lead_data = DB::select("SELECT a.lead_followup_pk_no,b.*,c.user_fullname,c.user_type
    		FROM t_lead2lifecycle_vw b
    		LEFT JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no and a.next_followup_flag=1
    		LEFT JOIN s_user c on c.user_pk_no = b.created_by
    		WHERE b.lead_pk_no=$id")[0];

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

        $lead_followup_data = DB::select("SELECT * from t_leadfollowup a LEFT JOIN s_user b ON a.created_by=b.user_pk_no where lead_pk_no=$id order by a.lead_followup_datetime desc");
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

        $lead_kyc_info_history = DB::table('t_leadkychistory')->where('lead_pk_no', $id)->get();
        //dd($lead_kyc_info_history);
        $ses_user_id = Session::get('user.ses_user_pk_no');
        return view('admin.components.lead_view', compact('lead_data', 'lead_stage_arr', 'followup_type', 'project_cat', 'project_area', 'project_name', 'project_size', 'hotline', 'ocupations', 'digital_mkt', 'press_adds', 'billboards', 'project_boards', 'flyers', 'fnfs', 'lead_transfer_data', 'lead_followup_data', 'lead_stage_data', 'lead_history', 'district', 'area', 'thana', 'meeting_status', 'ses_user_id', 'lead_kyc_info_history'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function load_sales_agent_by_area(Request $request)
    {
        $ses_user_id = Session::get('user.ses_user_pk_no');

        $is_ses_hod = Session::get('user.is_ses_hod');
        if ($is_ses_hod == 1) {
            $sales_agent_arr = DB::table("t_teambuild")
                ->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
                ->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
                ->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
                ->where('t_teambuild.agent_type', 2)
                ->whereRaw("hod_flag != 1")
                ->where('t_teambuild.hod_user_pk_no', $cluster_head_id)->get();


            $sales_agent_info = [];
            if (!empty($sales_agent_arr)) {
                foreach ($sales_agent_arr as $value) {
                    $sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
                }
            }
        } else {
            $get_team_info = DB::select("SELECT GROUP_CONCAT(team_lookup_pk_no) team_ids FROM t_teambuild WHERE user_pk_no=$ses_user_id");
            $get_all_teams = "";
            if (!empty($get_team_info)) {
                foreach ($get_team_info as $team) {
                    $get_all_teams = $team->team_ids;
                }
            }

            $sales_agent_arr = DB::table("t_teambuild")
                ->join("s_lookdata", "t_teambuild.team_lookup_pk_no", "s_lookdata.lookup_pk_no")
                ->join("s_user", "s_user.user_pk_no", "t_teambuild.user_pk_no")
                ->select("s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_lookdata.lookup_name", "s_user.user_fullname", "s_user.user_pk_no", "t_teambuild.hod_flag", "t_teambuild.hot_flag", "t_teambuild.team_lead_flag")
                ->whereIn("t_teambuild.team_lookup_pk_no", [$get_all_teams])
                ->whereRaw("t_teambuild.hod_flag != 1")
                ->where('t_teambuild.agent_type', 2)->get();

            $sales_agent_info = [];
            if (!empty($sales_agent_arr)) {
                foreach ($sales_agent_arr as $value) {
                    $sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag;
                }
            }
        }


        return view('admin.components.multiple_team_member_dropdown', compact('sales_agent_info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function check_if_phone_no_exist(Request $request)
    {
        $phone_no = $request->phone_no;
        $area_id = $request->area_id;
        $user_id = Session::get("user.ses_user_pk_no");
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lead_info = DB::table('t_lead2lifecycle_vw')
            ->Join('s_user', 't_lead2lifecycle_vw.source_auto_pk_no', '=', 's_user.user_pk_no')
            ->Join('s_lookdata', 't_lead2lifecycle_vw.source_auto_usergroup_pk_no', '=', 's_lookdata.lookup_pk_no')
            ->leftJoin('t_lead_followup_count_by_current_stage_vw', 't_lead2lifecycle_vw.lead_pk_no', '=', 't_lead_followup_count_by_current_stage_vw.lead_pk_no')
            ->whereRaw("(t_lead2lifecycle_vw.phone1 = $phone_no or t_lead2lifecycle_vw.phone2 = $phone_no ) ")
            ->get(); //->whereNotIn('t_lead2lifecycle_vw.lead_current_stage', [5, 6, 7])

        $sales_agents = [];
        if (!empty($lead_info)) {
            foreach ($lead_info as $row) {
                if ($row->lead_sales_agent_pk_no != 0 || $row->lead_cluster_head_pk_no != 0) {
                    $sales_agents = array(
                        'lead_id' => $row->lead_id,
                        'customer_name' => $row->customer_firstname . " " . $row->customer_lastname,
                        'user_group' => $row->user_group_name,
                        'agent_name' => $row->lead_sales_agent_name,
                        'agent_phone' => $row->lead_sales_agent_number,
                        'user_type' => $row->user_type,
                        'last_followup_dt' => ($row->last_lead_followup_datetime != "") ? date("d/m/Y", strtotime($row->last_lead_followup_datetime)) : "No Followup done yet",
                        'current_stage' => $lead_stage_arr[$row->lead_current_stage],
                        'sales_agent_pk' => $row->lead_sales_agent_pk_no,
                        'user_id' => $user_id
                    );
                } else {
                    if ($row->lead_entry_type == 3) {
                        $sales_agents = array(
                            'lead_id' => $row->lead_id,
                            'customer_name' => $row->customer_firstname . " " . $row->customer_lastname,
                            'user_group' => '',
                            'agent_name' => $row->user_fullname,
                            'agent_phone' => $row->mobile_no,
                            'user_type' => $row->user_type,
                            'last_followup_dt' => ($row->last_lead_followup_datetime != "") ? date("d/m/Y", strtotime($row->last_lead_followup_datetime)) : "No Followup done yet",
                            'current_stage' => $lead_stage_arr[$row->lead_current_stage],
                            'sales_agent_pk' => $row->lead_sales_agent_pk_no,
                            'user_id' => $user_id
                        );
                    } else {
                        $sales_agents = array(
                            'lead_id' => $row->lead_id,
                            'customer_name' => $row->customer_firstname . " " . $row->customer_lastname,
                            'user_group' => $row->source_auto_usergroup,
                            'agent_name' => '',
                            'agent_phone' => '',
                            'user_type' => $row->user_type,
                            'last_followup_dt' => ($row->last_lead_followup_datetime != "") ? date("d/m/Y", strtotime($row->last_lead_followup_datetime)) : "No Followup done yet",
                            'current_stage' => $lead_stage_arr[$row->lead_current_stage],
                            'sales_agent_pk' => $row->lead_sales_agent_pk_no,
                            'user_id' => $user_id
                        );
                    }
                }
            }
        }
        return json_encode($sales_agents);
    }

    public function import_csv()
    {
        return view('admin.lead_management.lead_import_by_csv');
    }

    function csv_to_array($filename = '', $delimiter = ',')
    {

        if (!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    public function store_import_csv(Request $request)
    {
        $user_group_arr = ["GM(CRS)" => 73, "Sales" => 77, "DFS" => 551];
        
        $lead_stage_arr = [
            1 => 'Lead',
            3 => 'Cool',
            4 => 'Warm',
            5 => 'Hold',
            6 => 'Closed',
            7 => 'Sold/On-board',		
            9 => 'Junk',
            13 => 'Hot',
            14 => 'Block',
    
        ];
        $lookup_data = LookupData::all();
        $lookup_data_arr = $lookup_uid_data_arr = [];
        foreach ($lookup_data as $value) {
            $lookup_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            $lookup_uid_data_arr[$value->lookup_name] = $value->lookup_id;
        }

        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $district_info = DB::table("districts")->get();
        $district_arr = [];
        foreach ($district_info as $value) {
            $district_arr[$value->district_name] = $value->id;
        }

        $upazila_info = DB::table("upazilas")->get();
        $thana_arr = [];
        foreach ($upazila_info as $value) {
            $thana_arr[$value->thana_name] = $value->id;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        //dd($leadArr);
        for ($i = 0; $i < count($leadArr); $i++) {
            $customer_firstname = (string)trim($leadArr[$i]["Customer_First_Name"]);
            $customer_lastname = (string)trim($leadArr[$i]["Customer_Last_Name"]);
            $customer_firstname2 = (string)trim($leadArr[$i]["Customer_First_Name2"]);
            $customer_lastname2 = (string)trim($leadArr[$i]["Customer_Last_Name2"]);
            $phone1_code = (string)trim($leadArr[$i]["Country_Code1"]);
            $phone1 = (string)trim($leadArr[$i]["Phone_Number_1"]);
            $phone2_code = (string)trim($leadArr[$i]["Country_Code2"]);
            $phone2 = (string)trim($leadArr[$i]["Phone_Number_2"]);
            $email_id = (string)trim($leadArr[$i]["Email"]);

            $ocupation = trim($leadArr[$i]["Occupation"]);
            $organization = trim($leadArr[$i]["Organization"]);
            $designation = trim($leadArr[$i]["Designation"]);

            $pre_house_plot = trim($leadArr[$i]["Pre_House_Plot"]);
            $pre_road_no = trim($leadArr[$i]["Pre_Road_No"]);
            $pre_area_name = trim($leadArr[$i]["Pre_Area_Name"]);
            $pre_district_name = trim($leadArr[$i]["Pre_District_Name"]);
            $pre_thana_name = trim($leadArr[$i]["Pre_Thana_Name"]);
            $pre_size_no = trim($leadArr[$i]["Pre_Size_No"]);
            $par_house_plot = trim($leadArr[$i]["Par_House_Plot"]);
            $par_road_no = trim($leadArr[$i]["Par_Road_No"]);
            $par_area_name = trim($leadArr[$i]["Par_Area_Name"]);
            $par_district_name = trim($leadArr[$i]["Par_District_Name"]);
            $par_thana_name = trim($leadArr[$i]["Par_Thana_Name"]);
            $office_house_plot = trim($leadArr[$i]["Office_House_Plot"]);
            $office_road_no = trim($leadArr[$i]["Office_Road_No"]);
            $office_area_name = trim($leadArr[$i]["Office_Area_Name"]);
            $office_district_name = trim($leadArr[$i]["Office_District_Name"]);
            $office_thana_name = trim($leadArr[$i]["Office_Thana_Name"]);

            $category = trim($leadArr[$i]["Category"]);
            $area = trim($leadArr[$i]["Area"]);
            $project_name = trim($leadArr[$i]["Project_Name"]);
            $size = trim($leadArr[$i]["Size"]);

            $user_group = trim($leadArr[$i]["User_Group"]);
            $cre = trim($leadArr[$i]["Creator_name"]);
            $sub_source_name = trim($leadArr[$i]["Sub_Source_Name"]);
            $lead_entry_type = trim($leadArr[$i]["Source"]);
            $sub_source = trim($leadArr[$i]["Sub_Source"]);

            $meeting_status = trim($leadArr[$i]["Meeting_Status"]);
            $meeting_date = trim($leadArr[$i]["Meeting_Date"]);
            $prefered_time = trim($leadArr[$i]["Prefered_Time"]);

            $customer_dob = trim($leadArr[$i]["Customer_DOB"]);
            $marriage_anniversary = trim($leadArr[$i]["Marriage_Anniversary"]);
            $wife_name = trim($leadArr[$i]["Wife_Name"]);
            $wife_dob = trim($leadArr[$i]["Wife_DOB"]);
            $children_name1 = trim($leadArr[$i]["Children_Name1"]);
            $children_dob1 = trim($leadArr[$i]["Children_DOB1"]);
            $children_name2 = trim($leadArr[$i]["Children_Name2"]);
            $children_dob2 = trim($leadArr[$i]["Children_DOB2"]);
            $children_name3 = trim($leadArr[$i]["Children_Name3"]);
            $children_dob3 = trim($leadArr[$i]["Children_DOB3"]);
            $remarks = trim($leadArr[$i]["Remarks"]);

            $cluster_head = trim($leadArr[$i]["Cluster_head"]);
            $ch_assign_dt = date("Y-m-d", strtotime(trim($leadArr[$i]["Ch_Assign_Dt"])));
            $agent_name = trim($leadArr[$i]["Sales_Agent_Name"]);
            $current_stage = trim($leadArr[$i]["current_stage"]);
            $lead_followup_date = date("Y-m-d", strtotime(trim($leadArr[$i]["Lead_followup_date"])));
            $next_followup_date = date("Y-m-d", strtotime(trim($leadArr[$i]["next_followup_date"])));
            $followup_note = trim($leadArr[$i]["followup_note"]);
            $followup_type = trim($leadArr[$i]["followup_type"]);

            $lead_id = date("Y") . "" . str_pad(2, 4, '0', STR_PAD_LEFT);

            $lead_insert_proc = DB::select(
                DB::raw("CALL proc_leads_ins ( $lead_id,'$customer_firstname','$customer_lastname','$customer_firstname2','$customer_lastname2','$phone1_code','$phone1','$phone2_code','$phone2','$email_id'," . (isset($lookup_data_arr[$ocupation]) ? $lookup_data_arr[$ocupation] : 0) . ",'$organization','$designation'," . (isset($lookup_data_arr[$category]) ? $lookup_data_arr[$category] : 0) . "," . (isset($lookup_data_arr[$area]) ? $lookup_data_arr[$area] : 0) . "," . (isset($lookup_data_arr[$project_name]) ? $lookup_data_arr[$project_name] : 0) . "," . (isset($lookup_data_arr[$size]) ? $lookup_data_arr[$size] : 0) . "," . (isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0) . "," . (isset($user_group_arr[$user_group]) ? $user_group_arr[$user_group] : 0) . ",'" . ((string)isset($sub_source_name) ? $sub_source_name : '') . "','" . ((string)isset($sac_name) ? $sac_name : '') . "','" . ((string)isset($sac_note) ? $sac_note : '') . "','" . (isset($lookup_data_arr[$sub_source]) ? $lookup_data_arr[$sub_source] : 0) . "','" . (isset($hotline) ? $hotline : NULL) . "',''," . (isset($emp_id) ? $emp_id : 0) . ",'" . ((string)isset($ir_name) ? $ir_name : '') . "','" . ((string)isset($ir_position) ? $ir_position : '') . "'," . (isset($ir_contact_number) ? $ir_contact_number : 0) . ",1,'" . (isset($customer_dob) ? date("Y-m-d", strtotime($customer_dob)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($customer_dob) ? date("Y-m-d", strtotime($customer_dob)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($wife_name) ? $wife_name : '') . "','" . (isset($wife_dob) ? date("Y-m-d", strtotime($wife_dob)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($marriage_anniversary) ? date("Y-m-d", strtotime($marriage_anniversary)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($children_name1) ? $children_name1 : '') . "','" . (isset($children_dob1) ? date("Y-m-d", strtotime($children_dob1)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($children_name2) ? $children_name2 : '') . "','" . (isset($children_dob2) ? date("Y-m-d", strtotime($children_dob2)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($children_name3) ? $children_name3 : '') . "','" . (isset($children_dob3) ? date("Y-m-d", strtotime($children_dob3)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . ((string)trim($leadArr[$i]["Remarks"])) . "','$pre_house_plot','$pre_road_no'," . (isset($area_arr[$pre_area_name]) ? $area_arr[$pre_area_name] : 0) . "," . (isset($district_arr[$pre_district_name]) ? $district_arr[$pre_district_name] : 0) . "," . (isset($thana_arr[$pre_thana_name]) ? $thana_arr[$pre_thana_name] : 0) . ",'$pre_size_no','$par_house_plot','$par_road_no'," . (isset($area_arr[$par_area_name]) ? $area_arr[$par_area_name] : 0) . "," . (isset($district_arr[$par_district_name]) ? $district_arr[$par_district_name] : 0) . "," . (isset($thana_arr[$par_thana_name]) ? $thana_arr[$par_thana_name] : 0) . "
    				,'$office_house_plot','$office_road_no'," . (isset($area_arr[$office_area_name]) ? $area_arr[$office_area_name] : 0) . "," . (isset($district_arr[$office_district_name]) ? $district_arr[$office_district_name] : 0) . "," . (isset($thana_arr[$office_thana_name]) ? $thana_arr[$office_thana_name] : 0) . "," . (isset($lookup_data_arr[$meeting_status]) ? $lookup_data_arr[$meeting_status] : 0) . "
    				,'" . (isset($meeting_date) ? date("Y-m-d", strtotime($meeting_date)) : date("Y-m-d", strtotime('0000-01-01'))) . "','" . (isset($meeting_date) ? date("Y-m-d", strtotime($meeting_date)) : date("Y-m-d", strtotime('0000-01-01'))) . "','','','','','','','','','',1," . (isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0) . ",'" . (date('Y-m-d')) . "')")
            );

            $lead_mst_id = $lead_insert_proc[0]->l_lead_pk_no;
            if ($lead_mst_id != "") {
                $leadLifeCycleData =
                    [
                        'lead_pk_no' => $lead_mst_id,
                        'lead_entry_type' => (isset($lookup_uid_data_arr[$lead_entry_type]) ? $lookup_uid_data_arr[$lead_entry_type] : 0),
                        'lead_current_stage' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                        'lead_cluster_head_pk_no' => isset($user_data_arr[$cluster_head]) ? $user_data_arr[$cluster_head] : 0,
                        'lead_cluster_head_assign_dt' => $ch_assign_dt,
                        'lead_sales_agent_pk_no' => isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0,
                        'lead_sales_agent_assign_dt' => date("Y-m-d"),
                        'created_by' => isset($user_data_arr[$cre]) ? $user_data_arr[$cre] : 0,
                        'created_at' => date('Y-m-d')
                    ];

                if ($current_stage == "Prospect") {
                    $lead_k1_flag = 1;
                    $lead_k1_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;
                    $leadLifeCycleData['lead_k1_flag'] = $lead_k1_flag;
                    $leadLifeCycleData['lead_k1_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_k1_by'] = $lead_k1_by;
                }

                if ($current_stage == "Higher Prospect") {
                    $lead_k1_flag = 1;
                    $lead_k1_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_hp_flag'] = $lead_k1_flag;
                    $leadLifeCycleData['lead_hp_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_hp_by'] = $lead_k1_by;
                }

                if ($current_stage == "Priority") {
                    $lead_priority_flag = 1;
                    $lead_priority_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_priority_flag'] = $lead_priority_flag;
                    $leadLifeCycleData['lead_priority_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_priority_by'] = $lead_priority_by;
                }

                if ($current_stage == "Hold") {
                    $lead_hold_flag = 1;
                    $lead_hold_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;


                    $leadLifeCycleData['lead_hold_flag'] = $lead_hold_flag;
                    $leadLifeCycleData['lead_hold_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_hold_by'] = $lead_hold_by;
                }

                if ($current_stage == "Closed") {
                    $lead_closed_flag = 1;
                    $lead_closed_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_closed_flag'] = $lead_closed_flag;
                    $leadLifeCycleData['lead_closed_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_closed_by'] = $lead_closed_by;
                }

                if ($current_stage == "Sold") {
                    $lead_sold_flag = 1;
                    $lead_sold_by = isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0;

                    $leadLifeCycleData['lead_sold_flag'] = $lead_sold_flag;
                    $leadLifeCycleData['lead_sold_datetime'] = date("Y-m-d");
                    $leadLifeCycleData['lead_sold_by'] = $lead_sold_by;
                }

                DB::table('t_leadlifecycle')->insert($leadLifeCycleData);

                if ($current_stage != 'Lead' && $lead_followup_date != "") {
                    $leadFollowupData =
                        [
                            'lead_pk_no' => $lead_mst_id,
                            'lead_followup_datetime' => $lead_followup_date,
                            'Followup_type_pk_no' => isset($lookup_data_arr[$followup_type]) ? $lookup_data_arr[$followup_type] : 0,
                            'followup_Note' => (string)($followup_note != '') ? $followup_note : '',
                            'lead_stage_before_followup' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                            'next_followup_flag' => 1,
                            'Next_FollowUp_date' => $next_followup_date,
                            'next_followup_Note' => (string)($followup_note != '') ? $followup_note : '',
                            'lead_stage_after_followup' => isset($lead_stage_arr[$current_stage]) ? $lead_stage_arr[$current_stage] : '',
                            'created_by' => isset($user_data_arr[$agent_name]) ? $user_data_arr[$agent_name] : 0
                        ];

                    DB::table('t_leadfollowup')->insert($leadFollowupData);
                }
            } else {
                echo "Failed" . $customer_firstname . " = " . $phone1 . "<br />";
                die;
            }
        }

        return redirect()->route('lead.index');
    }

    public function return_lead()
    {
        $is_hod = 1;
        $user_id = Session::get('user.ses_user_pk_no');
        $return_lead_info = DB::table("t_lead2lifecycle_vw")->where([
            ["lead_cluster_head_pk_no", "!=", 0],
            ["lead_sales_agent_pk_no", "=", 0],
            ["lead_current_stage", "=", 1],
            ["source_auto_pk_no", "=", $user_id],
        ])->get();

        $auto_return_time = LookupData::where("lookup_type", 25)->orderBy("lookup_pk_no", "desc")->first();

        $cluster_head = DB::table("s_user")->leftjoin('t_teambuild', 't_teambuild.hod_user_pk_no', 's_user.user_pk_no')->select("s_user.user_pk_no", "s_user.user_fullname")->where([
            ['s_user.role_lookup_pk_no', '=', 77],
            ['s_user.row_status', '=', 1],
            ['t_teambuild.hod_flag', '=', 1]
        ])->get();

        return view("admin.lead_management.lead_return.return_lead", compact("return_lead_info", "auto_return_time", "cluster_head"));
    }

    public function reassign_lead(Request $request)
    {
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $leadlifecycle_id = $request->get('leadlifecycle_id');
        $sales_agent = $request->get('sales_agent');
        $create_date = date("Y-m-d");
        $lead = LeadLifeCycle::findOrFail($leadlifecycle_id);
        $lead->lead_cluster_head_pk_no = $sales_agent;
        $lead->lead_cluster_head_assign_dt = $create_date;
        $lead->lead_dist_type = 1; // 1= Manual
        $lead->updated_by = 1;
        // $lead->lead_k1_flag = 1;
        // $lead->lead_k1_datetime = $create_date;
        // $lead->lead_k1_by = $ses_user_id;
        $lead->updated_at = $create_date;

        if ($lead->save()) {
            return response()->json(['message' => 'Lead updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        }
    }

    public function all_lead($from_dt = "", $to_dt = "")
    {
        ini_set('memory_limit', '2048M');
        $is_super_admin = Session::get('user.is_super_admin');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');

        $is_team_leader = Session::get('user.is_team_leader');
        $userRoleID = Session::get('user.ses_role_lookup_pk_no');


        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');

        $lead_id_arr = $trans_arr = [];
        $fromdate = date("Y-m-d", strtotime($from_dt));
        $todate = date("Y-m-d", strtotime($to_dt));
        if ($from_dt != "" && $todate != "") {
            $date_cond = "and created_at BETWEEN '$fromdate' AND '$todate'";
        } else if ($from_dt != "" && $todate == "") {
            $date_cond = "and created_at='$fromdate'";
        } else {
            $date_cond = "";
        }
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }
        $tranfer_condition = "";
        $get_all_tem_members = "";
        if ($userRoleID == 551 || $is_super_admin == 1) {
            if ($from_dt != "" && $todate != "") {
                $date_cond = "and created_at BETWEEN '$fromdate' AND '$todate'";
            } else if ($from_dt != "" && $todate == "") {
                $date_cond = "and created_at='$fromdate'";
            } else {
                $date_cond = "";
            }

            $lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond  order by created_at desc,lead_pk_no desc ");
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
                    $tranfer_condition = "and (b.lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR b.lead_transfer_from_sales_agent_pk_no IS NULL ) ";
                }
                $get_all_team_members = rtrim(($get_all_tem_members), ", ");

                $lead_data = DB::table('t_lead2lifecycle_vw')
                    ->whereRaw(" (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . ")) " . $date_cond . "  $tranfer_condition  and lead_current_stage not in(6,7,9)")
                    ->orderBy("created_at", "desc")
                    ->orderBy("lead_pk_no", "desc")
                    ->get();
            } else {
                //$tranfer_condition = "and (lead_transfer_from_sales_agent_pk_no != $ses_user_id  OR lead_transfer_from_sales_agent_pk_no IS NULL ) ";
                $lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_sales_agent_pk_no=$ses_user_id  $date_cond and lead_current_stage not in(6,7,9) order by created_at desc,lead_pk_no desc");
            }
            $lead_id_arr = $trans_arr = [];
            if (!empty($lead_data)) {
                foreach ($lead_data as $row) {
                    $lead_id_arr[$row->lead_pk_no] = $row->lead_pk_no;
                }

                if (!empty($lead_id_arr)) {
                    $lead_trans_data = DB::select("select max(transfer_pk_no),lead_pk_no,transfer_to_sales_agent_flag,transfer_to_sales_agent_pk_no transfer_pk_no from t_leadtransfer where lead_pk_no in(" . implode(",", $lead_id_arr) . ") group by lead_pk_no,transfer_to_sales_agent_flag,transfer_to_sales_agent_pk_no");
                    if (!empty($lead_trans_data)) {
                        foreach ($lead_trans_data as $trans_row) {
                            if ($trans_row->transfer_to_sales_agent_flag != 1)
                                $trans_arr[$trans_row->lead_pk_no] = $trans_row->transfer_to_sales_agent_flag;
                        }
                    }
                }
            }
        }
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        return view("admin.lead_management.lead_distribution.user_wise_all_Lead", compact("lead_data", 'ses_user_id', 'userRoleID', 'lead_stage_arr', 'trans_arr'));
    }

    public function junk_Work_list($id = "", $from_dt = "", $to_dt = "")
    {
        $is_super_admin = Session::get('user.is_super_admin');
        $stage = config('static_arrays.lead_stage_arr');
        $user_id = Session::get('user.ses_user_pk_no');


        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');

        $is_team_leader = Session::get('user.is_team_leader');
        $userRoleID = Session::get('user.ses_role_lookup_pk_no');
        $stage_cond = '';
        $enty_cond = '';
        if ($id == "") {
            $stage = "6,9";
        }
        if ($id == "0") {
            $stage = "9";
        }
        if ($id == "1") {
            $stage = "9";
            $enty_cond = "and lead_entry_type = 1";
        }
        if ($id == "2") {
            $stage = "9";
            $enty_cond = "and lead_entry_type = 2";
        }
        if ($id == "3") {
            $stage = "9";
            $enty_cond = "and lead_entry_type = 3";
        }

        if ($is_super_admin == 1 || $userRoleID == 551) {
            $lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage in ($stage) $enty_cond order by created_at desc, lead_pk_no desc");
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
                $user_cond = " and (b.lead_sales_agent_pk_no in(" . $get_all_tem_members . ") or b.created_by in(" . $get_all_tem_members . ") or lead_cluster_head_pk_no in(" . $get_all_tem_members . "))";
                $lead_data = DB::select("select * from t_lead2lifecycle_vw b where b.lead_current_stage in ($stage) $user_cond $enty_cond order by created_at desc, lead_pk_no desc");
            } else {

                $lead_data = DB::select("select * from t_lead2lifecycle_vw b where lead_sales_agent_pk_no= $user_id and b.lead_current_stage in ($stage)  $enty_cond order by created_at desc, lead_pk_no desc");
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
        if (!empty($get_all_teams)) {
            $sales_agent_arr = DB::select("SELECT `s_lookdata`.`lookup_pk_no`, `s_lookdata`.`lookup_name`, `s_lookdata`.`lookup_name`, `s_user`.`user_fullname`, `s_user`.`user_pk_no`,
    			`t_teambuild`.`hod_flag`, `t_teambuild`.`hot_flag`, `t_teambuild`.`team_lead_flag`
    			FROM `t_teambuild`
    			INNER JOIN `s_lookdata` ON `t_teambuild`.`team_lookup_pk_no` = `s_lookdata`.`lookup_pk_no`
    			INNER JOIN `s_user` ON `s_user`.`user_pk_no` = `t_teambuild`.`user_pk_no`
    			WHERE `t_teambuild`.`team_lookup_pk_no` IN ($get_all_teams)
    			AND `t_teambuild`.`agent_type` = 2");
        } else {
            $sales_agent_arr = [];
        }

        $sales_agent_info = [];
        $team_ch = [];
        if (!empty($sales_agent_arr)) {
            foreach ($sales_agent_arr as $value) {
                $sales_agent_info[$value->lookup_name][] = $value->user_pk_no . "_" . $value->user_fullname . "_" . $value->hod_flag . "_" . $value->hot_flag . "_" . $value->team_lead_flag . "_" . $value->lookup_pk_no;
                if ($value->hod_flag == 1) {
                    $team_ch[$value->lookup_pk_no] = $value->user_pk_no;
                }
            }
        }

        $tab = 1;

        return view("admin.lead_management.junk_lead.junk_worklist", compact('lead_data', 'stage', 'tab', 'sales_agent_info', 'team_ch'));
    }

    public function stage_wise_lead_list($id, $from_dt = "", $to_dt = "")
    {
        ini_set('memory_limit', '2048M');
        $is_super_admin = Session::get('user.is_super_admin');
        $stage = config('static_arrays.lead_stage_arr');
        $user_id = Session::get('user.ses_user_pk_no');
        $user_type = Session::get('user.user_type');
        $userRoleID = Session::get('user.ses_role_lookup_pk_no');

        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $fromdate = date("Y-m-d", strtotime($from_dt));
        $todate = date("Y-m-d", strtotime($to_dt));
        if ($from_dt != "" && $todate != "") {
            $date_cond = "and created_at BETWEEN '$fromdate' AND '$todate'";
        } else if ($from_dt != "" && $todate == "") {
            $date_cond = "and created_at='$fromdate'";
        } else {
            $date_cond = "";
        }

        $is_team_leader = Session::get('user.is_team_leader');
        if ($is_super_admin == 1) {
            $lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_entry_type =$id and lead_current_stage not in(6,7,9) $date_cond order by created_at desc, lead_pk_no desc");
        } else {
            if ($userRoleID == 551) {
                $lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_entry_type =$id and lead_current_stage not in(6,7,9) $date_cond order by created_at desc, lead_pk_no desc");
            } else {
                $get_all_tem_members = "";
                if ($is_ses_hod > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id )")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                } else if ($is_ses_hot > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1)")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                } else if ($is_team_leader > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                } else {
                    $get_all_tem_members .= $ses_user_id;
                }
                $get_all_team_members = rtrim(($get_all_tem_members), ", ");

                if ($is_ses_hod > 0 || $is_ses_hot > 0 || $is_team_leader > 0) {
                    if ($user_type == 2) {
                        $lead_data = DB::table('t_lead2lifecycle_vw')
                            ->whereRaw(" (lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . ")) " . $date_cond . " and lead_current_stage not in(6,7,9)")
                            ->where('lead_entry_type', $id)
                            ->orderBy("created_at", "desc")
                            ->get();
                    } else {
                        $lead_data = DB::table('t_lead2lifecycle_vw')
                            ->whereRaw(" (source_auto_pk_no in(" . $get_all_team_members . ") " . $date_cond . " ) ")
                            ->where('lead_entry_type', $id)
                            ->whereNotIn('lead_current_stage', [6, 7, 9])
                            ->orderBy("created_at", "desc")
                            ->get();
                    }
                } else {
                    if ($user_type == 1) {
                        $lead_data = DB::select("select * from t_lead2lifecycle_vw where created_by=$get_all_tem_members and lead_entry_type= $id $date_cond and lead_current_stage not in(6,7,9) order by created_at desc, lead_pk_no desc");
                    } else {
                        $lead_data = DB::select("select * from t_lead2lifecycle_vw where lead_sales_agent_pk_no=$get_all_tem_members and lead_entry_type= $id $date_cond and lead_current_stage not in(6,7,9) order by created_at desc, lead_pk_no desc");
                    }
                }
            }
        }
        if ($id == 1) {
            $lead_name = "MQL";
        } elseif ($id == 2) {
            $lead_name = "Walk In";
        } else {
            $lead_name = "SGL";
        }
        $ses_user_id = Session::get('user.ses_user_pk_no');
        return view("admin.lead_management.stage_wise_lead.stage_wise_lead", compact('lead_data', 'stage', 'lead_name', 'ses_user_id', 'userRoleID'));
    }


    public function my_lead_list($from_dt = "", $to_dt = "")
    {
        ini_set('memory_limit', '2048M');
        $is_super_admin = Session::get('user.is_super_admin');
        $stage = config('static_arrays.lead_stage_arr');
        $user_id = Session::get('user.ses_user_pk_no');
        $user_type = Session::get('user.user_type');
        $userRoleID = Session::get('user.ses_role_lookup_pk_no');

        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }


        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }


        $fromdate = date("Y-m-d", strtotime($from_dt));
        $todate = date("Y-m-d", strtotime($to_dt));
        if ($from_dt != "" && $todate != "") {
            $date_cond = "and created_at BETWEEN '$fromdate' AND '$todate'";
        } else if ($from_dt != "" && $todate == "") {
            $date_cond = "and created_at='$fromdate'";
        } else {
            $date_cond = "";
        }

        $is_team_leader = Session::get('user.is_team_leader');
        if ($is_super_admin == 1) {
            $lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where lead_current_stage not in(6,7,9) $date_cond order by created_at,lead_pk_no DESC");
        } else {
            if ($userRoleID == 551) {
                $lead_data = DB::select("SELECT * FROM t_lead2lifecycle_vw where  lead_current_stage not in(6,7,9) $date_cond order by created_at,lead_pk_no DESC");
            } else {
                $lead_data = DB::select("select  * FROM t_lead2lifecycle_vw  where lead_sales_agent_pk_no = '$ses_user_id' and lead_current_stage not in(6,7,9) $date_cond order by created_at DESC,lead_pk_no DESC");
                /*$get_all_tem_members = "";
                $lead_cond = "";

                if ($is_ses_hod > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id )")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                    $lead_cond = "and (lead_cluster_head_pk_no = '$user_id' and (lead_sales_agent_pk_no is null or lead_sales_agent_pk_no =0 or lead_sales_agent_pk_no = $ses_user_id ))";

                } else if ($is_ses_hot > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE (team_lead_user_pk_no=$ses_user_id OR hod_user_pk_no=$ses_user_id OR hot_user_pk_no=$ses_user_id and hod_flag != 1)")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                } else if ($is_team_leader > 0) {
                    $get_all_tem_memberss = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE ((team_lead_user_pk_no=$user_id OR hod_user_pk_no=$user_id OR hot_user_pk_no=$user_id) and hod_flag != 1 and hot_flag != 1)")[0]->team_members;

                    $get_all_tem_members .= $get_all_tem_memberss . "," . $ses_user_id;
                } else {
                    $get_all_tem_members .= $ses_user_id;
                    $lead_cond = "and lead_sales_agent_pk_no = '$user_id'";
                }
                $get_all_team_members = rtrim(($get_all_tem_members), ", ");

                if ($is_ses_hod > 0 || $is_ses_hot > 0 || $is_team_leader > 0) {

                    $lead_data = DB::select("select  * FROM t_lead2lifecycle_vw  where (lead_sales_agent_pk_no = '$ses_user_id' or lead_cluster_head_pk_no = '$ses_user_id' or created_by = '$ses_user_id') and lead_current_stage not in(6,7,9) $lead_cond order by created_at DESC");


                } else {
                    $lead_data = DB::select("select  * FROM t_lead2lifecycle_vw  where (lead_sales_agent_pk_no = '$ses_user_id' or created_by = '$ses_user_id') and lead_current_stage not in(6,7,9) $lead_cond order by created_at DESC");

                }*/
            }
        }
        $lead_name = "My Lead Lists";
        $ses_user_id = Session::get('user.ses_user_pk_no');
        return view("admin.lead_management.stage_wise_lead.stage_wise_lead", compact('lead_data', 'stage', 'lead_name', 'ses_user_id', 'userRoleID'));
    }


    public function today_visit($from_dt = "", $to_dt = "")
    {
        //$ses_user_id = Session::get('user.ses_user_pk_no');
        $is_ses_hod = Session::get('user.is_ses_hod');
        $is_ses_hot = Session::get('user.is_ses_hot');

        $is_team_leader = Session::get('user.is_team_leader');
        $userRoleId = Session::get('user.ses_role_lookup_pk_no');


        $is_ses_other_hod = Session::get('user.is_ses_other_hod');
        $is_ses_other_hot = Session::get('user.is_ses_other_hot');
        $is_other_team_leader = Session::get('user.is_other_team_leader');

        $ses_other_user_id = Session::get('user.ses_other_user_pk_no');


        $fromdate = date("Y-m-d", strtotime($from_dt));
        $todate = date("Y-m-d", strtotime($to_dt));
        $today_date = date("Y-m-d");
        if ($from_dt != "" && $todate != "") {
            $date_cond = "and a.visit_meeting_done_dt BETWEEN '$fromdate' AND '$todate'";
        } else if ($from_dt != "" && $todate == "") {
            $date_cond = "and a.visit_meeting_done_dt='$fromdate'";
        } else {
            $date_cond = "";
        }
        if ($ses_other_user_id == "") {
            $ses_user_id = Session::get('user.ses_user_pk_no');
        } else {
            $ses_user_id = $ses_other_user_id;
        }

        if ($is_ses_other_hod == "" && $ses_other_user_id == "") {
            $is_ses_hod = Session::get('user.is_ses_hod');
        } else {
            $is_ses_hod = $is_ses_other_hod;
        }
        if ($is_ses_other_hot == "" && $ses_other_user_id == "") {
            $is_ses_hot = Session::get('user.is_ses_hot');
        } else {
            $is_ses_hot = $is_ses_other_hot;
        }
        if ($is_other_team_leader == "" && $ses_other_user_id == "") {
            $is_team_leader = Session::get('user.is_team_leader');
        } else {
            $is_team_leader = $is_other_team_leader;
        }

        $get_all_tem_members = "";
        if ($userRoleId == 551) {
            if ($from_dt != "" && $todate != "") {
                $date_cond = "and a.visit_meeting_done_dt BETWEEN '$fromdate' AND '$todate'";
            } else if ($from_dt != "" && $todate == "") {
                $date_cond = "and a.visit_meeting_done_dt='$fromdate'";
            } else {
                $date_cond = "";
            }

            if ($date_cond == "") {
                $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    				a.next_followup_Note,c.user_fullname agent_name,d.user_fullname as last_followup_name,b.*
    				FROM t_lead2lifecycle_vw b
    				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    				)
    				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    				LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    				WHERE lead_current_stage not in(6,7,9) $date_cond order by b.created_at desc, b.lead_pk_no desc");
            } else {
                $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    				a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
    				FROM t_lead2lifecycle_vw b
    				JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    				SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    				)
    				LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    				LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    				WHERE lead_current_stage not in(6,7,9) $date_cond order by b.created_at desc, b.lead_pk_no desc");
            }
        } else {

            if (($is_ses_hod == 1 || $is_ses_hot == 1 || $is_team_leader == 1) && $userRoleId != 551) {

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
                $get_all_team_members = rtrim(($get_all_tem_members), ", ");
                $user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";

                if ($date_cond == "") {
                    $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
    					FROM t_lead2lifecycle_vw b
    					JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    					)
    					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    					WHERE lead_current_stage not in(6,7,9) $user_conds order by b.created_at desc, b.lead_pk_no desc");
                } else {
                    $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
    					FROM t_lead2lifecycle_vw b
    					JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    					)
    					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    					WHERE lead_current_stage not in(6,7,9) $user_conds $date_cond order by b.created_at desc, b.lead_pk_no desc");
                }
            } else {
                $get_all_tem_members = "";
                $get_all_tem_members .= $ses_user_id;
                $get_all_team_members = rtrim(($get_all_tem_members), ", ");
                $user_conds = " and (b.lead_sales_agent_pk_no in(" . $get_all_team_members . ") or lead_cluster_head_pk_no in(" . $get_all_team_members . "))";
                if ($date_cond == "") {
                    $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
    					FROM t_lead2lifecycle_vw b
    					JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    					)
    					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    					WHERE lead_current_stage not in(6,7,9) $user_conds $date_cond order by b.created_at desc, b.lead_pk_no desc");
                } else {
                    $today_meeting_data = DB::select("SELECT a.lead_followup_pk_no,COALESCE(a.lead_followup_datetime, CURDATE()) lead_followup_datetime,a.next_followup_flag,a.Next_FollowUp_date,a.visit_meeting_done_dt,
    					a.next_followup_Note,c.user_fullname agent_name ,d.user_fullname as last_followup_name,b.*
    					FROM t_lead2lifecycle_vw b
    					JOIN t_leadfollowup a ON a.lead_pk_no=b.lead_pk_no  and a.lead_followup_pk_no = (
    					SELECT MAX(lead_followup_pk_no) FROM t_leadfollowup c WHERE a.lead_pk_no = c.lead_pk_no and c.visit_meeting_done = 1
    					)
    					LEFT JOIN s_user c ON b.created_by=c.user_pk_no
    					LEFT JOIN s_user d ON a.created_by=d.user_pk_no
    					WHERE lead_current_stage not in(6,7,9) $user_conds $date_cond order by b.created_at desc, b.lead_pk_no desc");
                }
            }
        }
        $id = 4;
        $lead_stage_arr = config('static_arrays.lead_stage_arr');

        return view("admin.sales_team_management.lead_followup.lead_today_visit", compact("today_meeting_data", 'ses_user_id', 'userRoleId', 'id', 'lead_stage_arr'));
    }
    public function store_import_csv1(Request $request)
    {
        $user_group_arr = ["Digital & Call Center" => 73, "Sales" => 77, "DMD" => 551];
        $lead_stage_arr = [
            'Lead' => 1,
            'Prospect' => 3,
            'Priority' => 4,
            'Hold' => 5,
            'Closed' => 6,
            'Sold' => 7,
            'Junk' => 9,
            'Higher Prospect' => 13
        ];
        $lookup_data = LookupData::all();
        $lookup_data_arr = $lookup_uid_data_arr = [];
        foreach ($lookup_data as $value) {
            $lookup_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            $lookup_uid_data_arr[$value->lookup_name] = $value->lookup_id;
        }

        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $district_info = DB::table("districts")->get();
        $district_arr = [];
        foreach ($district_info as $value) {
            $district_arr[$value->district_name] = $value->id;
        }

        $upazila_info = DB::table("upazilas")->get();
        $thana_arr = [];
        foreach ($upazila_info as $value) {
            $thana_arr[$value->thana_name] = $value->id;
        }

        $file = $request->csv_file;
        $leadArr = $this->csv_to_array($file);
        //dd($leadArr);
        for ($i = 0; $i < count($leadArr); $i++) {

            $lead_pk_no = (string)trim($leadArr[$i]["lead_pk_no"]);

            $lead_cluster_head_name = (string)trim($leadArr[$i]["lead_cluster_head_name"]);
            $lead_sales_agent_name = (string)trim($leadArr[$i]["lead_sales_agent_name"]);


            $leadLifeCycleData =
                [
                    'lead_pk_no' => $lead_pk_no,
                    'lead_sales_agent_pk_no' => isset($user_data_arr[$lead_sales_agent_name]) ? $user_data_arr[$lead_sales_agent_name] : 0,
                    'lead_cluster_head_pk_no' => isset($user_data_arr[$lead_cluster_head_name]) ? $user_data_arr[$lead_cluster_head_name] : 0,

                ];

            if (isset($user_data_arr[$lead_cluster_head_name])) {
                //echo "Lead ID : ".$lead_pk_no. " Cluster Head Name!! Cluster Head:  ".$lead_cluster_head_name ."<br>";
            } else {
                echo "Lead ID : " . $lead_pk_no . " Cluster Head Name Does Not Match !!! Cluster Head Name  " . $lead_cluster_head_name . "<br>";
            }


            if (isset($user_data_arr[$lead_sales_agent_name])) {
                //echo "Lead ID : ".$lead_pk_no. " Sales Agent  Name!! Sales Agent :  ".$lead_sales_agent_name ."<br>" ;
            } else {
                echo "Lead ID : " . $lead_pk_no . " Sales Agent Name Does Not Match !!! " . $lead_sales_agent_name  . "<br>";
            }

            DB::table('t_leadlifecycle')->where('lead_pk_no', $lead_pk_no)->update($leadLifeCycleData);
        }

        //return redirect()->route('lead.index');
    }


    //
    public function store_import_csv2(Request $request)
    {
        $user_group_arr = ["GM(CRS)" => 73, "Sales" => 77, "DFS" => 551];
        $lead_stage_arr = [
            1 => 'Lead',
            3 => 'Cool',
            4 => 'Warm',
            5 => 'Hold',
            6 => 'Closed',
            7 => 'Sold/On-board',		
            9 => 'Junk',
            13 => 'Hot',
            14 => 'Block',
    
        ];
        $lookup_data = LookupData::all();
        $lookup_data_arr = $lookup_uid_data_arr = [];
        foreach ($lookup_data as $value) {
            $lookup_data_arr[$value->lookup_name] = $value->lookup_pk_no;
            $lookup_uid_data_arr[$value->lookup_name] = $value->lookup_id;
        }

        $user_info = TeamUser::all();
        $user_data_arr = [];
        foreach ($user_info as $value) {
            $user_data_arr[$value->user_fullname] = $value->user_pk_no;
        }

        $district_info = DB::table("districts")->get();
        $district_arr = [];
        foreach ($district_info as $value) {
            $district_arr[$value->district_name] = $value->id;
        }

        $upazila_info = DB::table("upazilas")->get();
        $thana_arr = [];
        foreach ($upazila_info as $value) {
            $thana_arr[$value->thana_name] = $value->id;
        }

        $file = $request->csv_file;
        dd($user_data_arr);
        $leadArr = $this->csv_to_array($file);
        //dd($leadArr);

        $table_data = "<table>
    	<thead>
    	<tr>
    	<th>lead_pk_no
    	</th>
    	<th>customer_firstname
    	</th>
    	<th>phone1</th>
    	<th>phone2_code</th>
    	<th>phone2</th>
    	<th>project_category_pk_no</th>
    	<th>project_category_name</th>
    	<th>project_area_pk_no</th>
    	<th>project_area</th>
    	<th>Project_pk_no</th>
    	<th>project_name</th>
    	<th>project_size_pk_no</th>
    	<th>project_size</th>
    	<th>flatlist_pk_no</th>
    	<th>Creator_Name</th>
    	<th>Creator_Id
    	</th>
    	<th>Creator_Name</th>
    	<th>source_auto_usergroup_pk_no</th>
    	<th>source_auto_usergroup</th>
    	<th>lead_sales_agent_pk_no</th>
    	<th>lead_sales_agent_assign_dt</th>
    	<th>lead_cluster_head_assign_dt</th>
    	<th>lead_cluster_head_pk_no</th>
    	<th>lead_cluster_head_name</th>
    	<th>lead_sales_agent_name</th>
    	<th>lead_current_stage</th>
    	<th>lead_entry_type</th>
    	<th>Source</th>
    	</tr>
    	</thead>
    	<tbody>
    	";


        for ($i = 0; $i < count($leadArr); $i++) {

            $lead_pk_no = (string)trim($leadArr[$i]["lead_pk_no"]);

            $creator_id = (string)trim($leadArr[$i]["Creator_Id"]);

            //$lead_sales_agent_name = (string)trim($leadArr[$i]["lead_sales_agent_name"]);


            $leadLifeCycleData =
                [
                    'created_by' => isset($user_data_arr[$creator_id]) ? $user_data_arr[$creator_id] : 0,
                ];


            $lead_data =
                [
                    'created_by' => isset($user_data_arr[$creator_id]) ? $user_data_arr[$creator_id] : 0,
                    'source_auto_pk_no' => isset($user_data_arr[$creator_id]) ? $user_data_arr[$creator_id] : 0
                ];

            if (isset($user_data_arr[$creator_id])) {
                //echo "Lead ID : ".$lead_pk_no. " Sales Agent  Name!! Sales Agent :  ".$lead_sales_agent_name ."<br>" ;
            } else {
                $table_data .= "

    			<tr>

    			<th>" . (string)trim($leadArr[$i]['lead_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['customer_firstname']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['phone1']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['phone2_code']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['phone2']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_category_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_category_name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_area_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_area']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['Project_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_size_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['project_size']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['flatlist_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['Creator_Name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['Creator_Id']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['Creator_Name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['source_auto_usergroup_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['source_auto_usergroup']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_sales_agent_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_sales_agent_assign_dt']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_cluster_head_assign_dt']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_cluster_head_pk_no']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_cluster_head_name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_sales_agent_name']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_current_stage']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['lead_entry_type']) . "</th>
    			<th>" . (string)trim($leadArr[$i]['Source']) . "</th>

    			</tr>

    			";
                //echo "Lead ID : ".$lead_pk_no. "Creator ID Does Not Match !!! ".$creator_id  ."<br>";
            }



            //DB::table('t_leadlifecycle')->where('lead_pk_no',$lead_pk_no)->update($leadLifeCycleData);

            //DB::table('t_leads')->where('lead_pk_no',$lead_pk_no)->update($lead_data);

        }
        $table_data .= "</tbody></table>";
        echo $table_data;

        //return redirect()->route('lead.index');
    }
    public function remove_double_number()
    {
        $lead_stage_arr = config('static_arrays.lead_stage_arr');
        $lead_entry_type = [1 => "MQL", 2 => "Walk In", 3 => "SGL"];
        $double_number = DB::table("duplicate_phn")->get();

        $double_number_lead = [];
        $count_followup = [];
        foreach ($double_number as $value) {
            $lead_id = explode(',', $value->lead_pk_no);

            for ($i = 0; $i < count($lead_id); $i++) {
                if ($lead_id[$i] != $value->min_lead_pk_no) {
                    $double_number_lead[] = $lead_id[$i];
                }
            }
        }
        //table
        $table = "<table border='1' cellpadding='1' cellspacing='0' >
    	<tr '>
    	<th>Lead pk no</th>
    	<th>Lead ID</th>
    	<th>First Name</th>
    	<th>Last Name</th>
    	<th>phone1 Code</th>
    	<th>phone1</th>
    	<th>phone2_code</th>
    	<th>phone2</th>
    	<th>project_category_name</th>
    	<th>project_area</th>
    	<th>project_name</th>
    	<th>project_size</th>
    	<th>Creator_Name</th>
    	<th>lead_cluster_head_name</th>
    	<th>lead_cluster_head_assign_dt</th>
    	<th>lead_sales_agent_name</th>
    	<th>lead_sales_agent_assign_dt</th>
    	</tr>";
        for ($i = 0; $i < count($double_number_lead); $i++) {
            $t_lead_info = DB::table("t_lead2lifecycle_vw")->where("lead_pk_no", $double_number_lead[$i])->first();
            //dd($t_lead_info);
            $table .= "
    		<tr>
    		<td>" . $t_lead_info->lead_pk_no . "</td>
    		<td>" . $t_lead_info->lead_id . "</td>
    		<td>" . $t_lead_info->customer_firstname . "</td>
    		<td>" . $t_lead_info->customer_lastname . "</td>
    		<td>" . $t_lead_info->phone1_code . "</td>
    		<td>" . $t_lead_info->phone1 . "</td>
    		<td>" . $t_lead_info->phone2_code . "</td>
    		<td>" . $t_lead_info->phone2 . "</td>
    		<td>" . $t_lead_info->project_category_name . "</td>
    		<td>" . $t_lead_info->project_area . "</td>
    		<td>" . $t_lead_info->project_name . "</td>
    		<td>" . $t_lead_info->project_size . "</td>
    		<td>" . $t_lead_info->user_full_name . "</td>
    		<td>" . $t_lead_info->lead_cluster_head_name . "</td>
    		<td>" . $t_lead_info->lead_cluster_head_assign_dt . "</td>
    		<td>" . $t_lead_info->lead_sales_agent_name . "</td>
    		<td>" . $t_lead_info->lead_sales_agent_assign_dt . "</td>
    		</tr>";
        }
        $table .= '</table>';
        echo $table;
        /*for ($i=0; $i < count($double_number_lead) ; $i++) {
            //$t_leads = DB::table("t_leads")->where("lead_pk_no",$double_number_lead[$i])->delete();
            //$t_transfer = DB::table("t_leadlifecycle")->where("lead_pk_no",$double_number_lead[$i])->delete();
            //$t_leadfollowup = DB::table("t_leadfollowup")->where("lead_pk_no",$double_number_lead[$i])->delete();
            //$t_leadtransfer = DB::table("t_leadtransfer")->where("lead_pk_no",$double_number_lead[$i])->delete();
            //$t_leadstage_attribute = DB::table("t_leadstage_attribute")->where("lead_pk_no",$double_number_lead[$i])->delete();

        }*/
    }
    public function search_lead_data($from_dt, $to_dt)
    {
        $from_dt = date("Y-m-d", strtotime($from_dt));
        $to_dt = date("Y-m-d", strtotime($to_dt));
        /*echo "select * from t_lead2lifecycle_vw a join t_lead_followup_count_by_current_stage_vw b on a.lead_pk_no=b.lead_pk_no where coalesce(a.created_at)>= '$from_dt' and coalesce(a.created_at)<= '$to_dt'";die;*/
        $lead_data = DB::select("select * from t_lead2lifecycle_vw a join t_lead_followup_count_by_current_stage_vw b on a.lead_pk_no=b.lead_pk_no where coalesce(a.created_at)>= '$from_dt' and coalesce(a.created_at)<= '$to_dt'");

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array("SL", "Created At", "Lead ID", "Customer First Name", "Customer Last Name", "Customer First Name 2", "Customer Last Name 2", "Phone1 Code", "Phone1", "Phone2 Code", "Phone2", "Email", "Project Category Name", "Project Area", "Project Name", "Project Size", "lead Cluster Head Name", "Cluster head Assign Date", "lead Sales Agent Name", "Sales Agent Assign Date", "Lead Current Stage", "Lead Entry Type", "Source Digital Markting", "Last Follow-up Date");

        $callback = function () use ($lead_data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $lead_stage_arr = config('static_arrays.lead_stage_arr');
            $lookup_data = LookupData::whereIn('lookup_type', [2, 29])->where("lookup_row_status", 1)->get();
            $digital_mkt = $entry_type = [];
            foreach ($lookup_data as $value) {
                if ($value->lookup_type == 2) {
                    $key = $value->lookup_pk_no;
                    $digital_mkt[$key] = $value->lookup_name;
                }
                if ($value->lookup_type == 29) {
                    $key = $value->lookup_id;
                    $entry_type[$key] = $value->lookup_name;
                }
            }

            $iteration = 1;
            foreach ($lead_data as $ldata) {

                $stage = isset($lead_stage_arr[$ldata->lead_current_stage]) ? $lead_stage_arr[$ldata->lead_current_stage] : "N/A";
                $lead_entry_type = isset($entry_type[$ldata->lead_entry_type]) ? $entry_type[$ldata->lead_entry_type] : "N/A";
                $digital_mkt_data = isset($digital_mkt[$ldata->source_digital_marketing]) ? $digital_mkt[$ldata->source_digital_marketing] : "N/A";



                fputcsv($file, array(
                    $iteration,
                    date("d/m/Y", strtotime($ldata->created_at)),
                    $ldata->lead_id,
                    $ldata->customer_firstname,
                    $ldata->customer_lastname,
                    $ldata->customer_firstname2,
                    $ldata->customer_lastname2,
                    $ldata->phone1_code, $ldata->phone1, $ldata->phone2_code, $ldata->phone2, $ldata->email_id, $ldata->project_category_name, $ldata->project_area, $ldata->project_name, $ldata->project_size, $ldata->lead_cluster_head_name, date("d/m/Y", strtotime($ldata->lead_cluster_head_assign_dt)), $ldata->lead_sales_agent_name, date("d/m/Y", strtotime($ldata->lead_sales_agent_assign_dt)), $stage, $lead_entry_type, $digital_mkt_data, $ldata->last_lead_followup_datetime
                ));
                $iteration = $iteration + 1;
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
        dd("ss");
    }
    public function ch_missing_lead()
    {
        echo "select * from t_lead2lifecycle_vw where (lead_cluster_head_pk_no = 0 or lead_cluster_head_pk_no= null) and and (lead_current_stage != 1)";
        $lead_data = DB::select("select * from t_lead2lifecycle_vw where (lead_cluster_head_pk_no = 0 or lead_cluster_head_pk_no= null) and (lead_current_stage != 1)");

        $table = "<table border='1' cellpadding='1' cellspacing='0' >
        <tr '>
        <th>Sl</th>
        <th>Lead pk no</th>
        <th>Lead ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>phone1 Code</th>
        <th>phone1</th>
        <th>phone2_code</th>
        <th>phone2</th>
        <th>project_category_name</th>
        <th>project_area</th>
        <th>project_name</th>
        <th>project_size</th>

        <th>lead_cluster_head_name</th>
        <th>lead_sales_agent_name</th>
        </tr>";
        $iteration = 1;
        if (!empty($lead_data)) {
            foreach ($lead_data as $value) {

                $team_info  = DB::table("t_teambuild")
                    ->select("s_user.user_fullname", "s_user.user_pk_no")
                    ->leftJoin("s_user", "t_teambuild.hod_user_pk_no", "s_user.user_pk_no")
                    ->where("t_teambuild.user_pk_no", $value->lead_sales_agent_pk_no)
                    ->first();
                $user_full_name = "JJJ";
                $user_pk_no = "000";
                if (!empty($team_info)) {
                    $user_full_name =  $team_info->user_fullname;
                    $user_pk_no =  $team_info->user_pk_no;
                }

                $table .= "
                <tr>
                <td>" . $iteration . "</td>
                <td>" . $value->lead_pk_no . "</td>
                <td>" . $value->lead_id . "</td>
                <td>" . $value->customer_firstname . "</td>
                <td>" . $value->customer_lastname . "</td>
                <td>" . $value->phone1_code . "</td>
                <td>" . $value->phone1 . "</td>
                <td>" . $value->phone2_code . "</td>
                <td>" . $value->phone2 . "</td>
                <td>" . $value->project_category_name . "</td>
                <td>" . $value->project_area . "</td>
                <td>" . $value->project_name . "</td>
                <td>" . $value->project_size . "</td>
                <td>" . $user_pk_no . "===" . $user_full_name . "</td>
                <td>" . $value->lead_sales_agent_pk_no . "===" . $value->lead_sales_agent_name . "</td>
                </tr>";
                $iteration =  $iteration + 1;
            }
        }
        $table .= '</table>';

        echo $table;
        die;

        if (!empty($lead_data)) {
            foreach ($lead_data as $value) {
                $team_info  = TeamAssign::where("user_pk_no", $value->lead_sales_agent_pk_no)->first();
                if (!empty($team_info)) {
                    $ldata =  LeadLifeCycle::where("lead_pk_no", $value->lead_pk_no)->first();

                    $ldata->lead_cluster_head_pk_no = $team_info->hod_user_pk_no;
                    $ldata->script = date("d/m/Y");
                    $ldata->save();
                } else {
                    echo $value->lead_sales_agent_pk_no . "<br />";
                }
            }
        }
    }
}
