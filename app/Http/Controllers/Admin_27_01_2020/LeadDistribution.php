<?php

namespace App\Http\Controllers\Admin;

Use Session;
use App\LeadLifeCycle;
use App\LeadLifeCycleView;
use App\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LeadDistribution extends Controller
{
    public function index()
    {
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $is_team_leader = Session::get('user.is_team_leader');
        if($is_team_leader == 1)
        {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
            $get_all_tem_members = explode(",",$get_all_team_member.",".$ses_user_id);

            $lead_data = DB::table('t_lead2lifecycle_vw')
            ->whereIn('created_by', $get_all_tem_members)
            ->where('lead_qc_flag',1)
            ->get();

            $category_wise_agent_data = DB::table('s_user')
            ->select('s_user.user_pk_no','s_user.user_fullname','t_teambuild.category_lookup_pk_no','t_teambuild.area_lookup_pk_no')
            ->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('s_user.user_type', 2)
            ->get();

            $sales_agent = [];
            foreach ($category_wise_agent_data as $row) {
                $sales_agent[$row->category_lookup_pk_no][$row->area_lookup_pk_no][$row->user_pk_no] = $row->user_fullname;
            }
        }
        else
        {
            $lead_data = $sales_agent = [];
        }

        $lead_distribution_type = config('static_arrays.lead_distribution_type');
        return view('admin.lead_management.lead_distribution.lead_distribution', compact('lead_data', 'sales_agent', 'lead_distribution_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function distribute_lead(Request $request)
    {
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $leadlifecycle_id     = $request->get('leadlifecycle_id');
        $sales_agent 	= $request->get('sales_agent');
        $create_date   	= date("Y-m-d");

        $lead = LeadLifeCycle::findOrFail($leadlifecycle_id);
        $lead->lead_sales_agent_pk_no = $sales_agent;
        $lead->lead_dist_type = 1; // 1= Manual
        $lead->updated_by = 1;
        $lead->lead_k1_flag = 1;
        $lead->lead_k1_datetime = $create_date;
        $lead->lead_k1_by = $ses_user_id;
        $lead->updated_at = $create_date;

        if ($lead->save()) {
            return response()->json(['message'=>'Lead updated successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_dist_leads(Request $request)
    {
        $ses_user_id = Session::get('user.ses_user_pk_no');
        $is_team_leader = Session::get('user.is_team_leader');
        if($is_team_leader == 1)
        {
            $get_all_team_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$ses_user_id")[0]->team_members;
            $get_all_tem_members = explode(",",$get_all_team_member.",".$ses_user_id);
            $lead_data = DB::table('t_lead2lifecycle_vw')
            ->whereIn('created_by', $get_all_tem_members)
            ->where('lead_qc_flag',1)
            ->get();

            $category_wise_agent_data = DB::table('s_user')
            ->select('s_user.user_pk_no','s_user.user_fullname','t_teambuild.category_lookup_pk_no','t_teambuild.area_lookup_pk_no')
            ->Join('t_teambuild', 's_user.user_pk_no', '=', 't_teambuild.user_pk_no')
            ->where('s_user.user_type', 2)
            ->get();

            $sales_agent = [];
            foreach ($category_wise_agent_data as $row) {
                $sales_agent[$row->category_lookup_pk_no][$row->area_lookup_pk_no][$row->user_pk_no] = $row->user_fullname;
            }
        }
        else
        {
            $lead_data = $sales_agent = [];
        }

        //$sales_agent = TeamUser::all();
        //$lead_data = LeadLifeCycleView::all();
        if($request->tab_type == ''){
            return view('admin.lead_management.lead_distribution.all_lead', compact('lead_data','sales_agent'));
        }
        if($request->tab_type == 0){
            return view('admin.lead_management.lead_distribution.pending_lead', compact('lead_data','sales_agent'));
        }
        if($request->tab_type == 1){
            return view('admin.lead_management.lead_distribution.manual_lead', compact('lead_data','sales_agent'));
        }
        if($request->tab_type == 2){
            return view('admin.lead_management.lead_distribution.auto_lead', compact('lead_data','sales_agent'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function lead_auto_distribute(Request $request)
    {
        $user_id        = $request->get('user_id');
        $dist_value     = $request->get('dist_value');
        $dist_date    = date("Y-m-d", strtotime($request->get('dist_date')));

        $qcdata = TeamUser::findOrFail($user_id);
        $qcdata->auto_distribute = $dist_value;
        $qcdata->distribute_date = $dist_date;

        if($qcdata->save())
        {
            $msg = ($dist_value == 1)?"set":"removed";
            return response()->json(['message'=>"Auto Distribution $msg successfully",'title'=>'Success',"positionClass" => "toast-top-right"]);
        }
        else
        {
            return response()->json(['message'=>'Data did not updated successfully','title'=>'Failed',"positionClass" => "toast-top-right"]);
        }
    }
}
