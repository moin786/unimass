<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\User;
use App\LookupData;
use App\TeamAssign;
use App\TeamTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $team_arr = [];
        $teams = LookupData::where('lookup_type', 18)->get();
        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }

        $team_users = DB::table('t_teambuild')
        ->select('team_lookup_pk_no', DB::raw('count(*) as total'))
        ->groupBy('team_lookup_pk_no')
        ->get();

        $team_user_count = [];
        if (!empty($team_users)) {
            foreach ($team_users as $tu) {
                $team_user_count[$tu->team_lookup_pk_no] = $tu->total;
            }
        }

        return view('admin.settings.team_management.index', compact('team_arr', 'team_user_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->txtUserID;
        $is_hod = $request->chkIsHod;
        $is_hot = $request->chkIsHot;
        $is_team_leader = $request->chkIsTL;
        $create_date = date("Y-m-d");
        $team_users = [];
        $team_lead_id = $hod_id = $hot_id = 0;
        for ($i = 0; $i < sizeof($user_id); $i++)
        {
            $chkIsHod = $request->input('chkIsHod'.$user_id[$i]);
            if(isset($chkIsHod))
            {
                $hod_id = $user_id[$i];
            }
            $chkIsHot = $request->input('chkIsHot'.$user_id[$i]);
            if(isset($chkIsHot))
            {
                $hot_id = $user_id[$i];
            }
            $is_team_leader = $request->input('chkIsTL'.$user_id[$i]);
            if(isset($is_team_leader))
            {
                $team_lead_id = $user_id[$i];
            }
        }
        for ($i = 0; $i < sizeof($user_id); $i++) {
            $user = new TeamAssign();
            $user->team_lookup_pk_no = $request->team_name;
            $user->category_lookup_pk_no = $request->category;
            $user->area_lookup_pk_no = $request->area;
            $user->teammem_id = 1;
            $user->user_pk_no = $user_id[$i];
            $user->hod_user_pk_no = $hod_id;
            $user->hot_user_pk_no = $hot_id;
            $user->team_lead_user_pk_no = $team_lead_id;
            $user->agent_type = $request->agent_type;
            $user->hod_flag = isset($is_hod[$i]) ? 1 : 0;
            $user->hot_flag = isset($is_hot[$i]) ? 1 : 0;
            $user->team_lead_flag = isset($is_team_leader[$i]) ? 1 : 0;
            $user->row_status = 1;
            $user->created_by = Session::get('user.ses_user_pk_no');
            $user->created_at = $create_date;
            $team_users[] = $user->attributesToArray();
        }

        if (TeamAssign::insert($team_users)) {
            return response()->json(['message' => 'User added in Team successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'User add in Team Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }

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
    public function edit($team_id)
    {
        $agent_type = config('static_arrays.agent_type');
        $team_arr = [];
        $teams = LookupData::where('lookup_pk_no', $team_id)->get();
        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }

        $lookup_arr = [4, 5];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4)
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 5)
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
            }
        }

        $users = User::where('is_super_admin', '!=' , 1)->orWhereNull('is_super_admin')->get();
        $user_arr = [];
        foreach ($users as $user) {
            $user_arr[$user->teamUser['user_pk_no']] = $user->name;
        }

        $team_users = TeamAssign::where('team_lookup_pk_no', $team_id)->get();

        return view('admin.settings.team_management.create', compact('user_arr', 'team_arr', 'team_users', 'agent_type', 'team_id','project_cat','project_area'));
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
        $teammem_id = $request->teammem_id;
        $user_id = $request->txtUserID;

        $create_date = date("Y-m-d");
        $team_lead_id = $hod_id = $hot_id = 0;
        for ($i = 0; $i < sizeof($user_id); $i++)
        {
            $chkIsHod = $request->input('chkIsHod'.$user_id[$i]);
            if(isset($chkIsHod))
            {
                if($hod_id == 0)
                    $hod_id = $user_id[$i];
            }
            $chkIsHot = $request->input('chkIsHot'.$user_id[$i]);
            if(isset($chkIsHot))
            {
                if($hot_id == 0)
                    $hot_id = $user_id[$i];
            }
            $is_team_leader = $request->input('chkIsTL'.$user_id[$i]);
            if(isset($is_team_leader))
            {
                if($team_lead_id == 0)
                    $team_lead_id = $user_id[$i];
            }
        }

        for ($i = 0; $i < sizeof($user_id); $i++) {
            $is_hod = $request->input('chkIsHod'.$user_id[$i]);
            $is_hot = $request->input('chkIsHot'.$user_id[$i]);
            $is_team_leader = $request->input('chkIsTL'.$user_id[$i]);

            if ($teammem_id[$i] == "") {
                $user = new TeamAssign();
                $user->team_lookup_pk_no = $request->team_name;
                $user->teammem_id = 1;
                $user->user_pk_no = $user_id[$i];
                $user->hod_user_pk_no = $hod_id;
                $user->hot_user_pk_no = $hot_id;
                $user->team_lead_user_pk_no = $team_lead_id;
                $user->category_lookup_pk_no = $request->category;
                $user->area_lookup_pk_no = $request->area;
                $user->agent_type = $request->agent_type;
                $user->hod_flag = isset($is_hod) ? 1 : 0;
                $user->hot_flag = isset($is_hot) ? 1 : 0;
                $user->team_lead_flag = isset($is_team_leader) ? 1 : 0;
                $user->row_status = 1;
                $user->created_by = Session::get('user.ses_user_pk_no');
                $user->created_at = $create_date;
                $team_users[] = $user->attributesToArray();
            } else {
                $user = TeamAssign::findOrFail($teammem_id[$i]);
                $user->team_lookup_pk_no = $request->team_name;
                $user->user_pk_no = $user_id[$i];
                $user->hod_user_pk_no = $hod_id;
                $user->hot_user_pk_no = $hot_id;
                $user->team_lead_user_pk_no = $team_lead_id;
                $user->category_lookup_pk_no = $request->category;
                $user->area_lookup_pk_no = $request->area;
                $user->agent_type = $request->agent_type;
                $user->hod_flag = isset($is_hod) ? 1 : 0;
                $user->hot_flag = isset($is_hot) ? 1 : 0;
                $user->team_lead_flag = isset($is_team_leader) ? 1 : 0;
                $user->created_by = Session::get('user.ses_user_pk_no');
                $user->created_at = $create_date;
                $user->save();
            }
        }
        if(!empty($team_users))
        {
            TeamAssign::insert($team_users);
        }

        return response()->json(['message'=>'User added in Team successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function team_target()
    {
        $team_arr = [];
        $teams = LookupData::where('lookup_type', 18)->get();
        if (!empty($teams)) {
            foreach ($teams as $team) {
                $team_arr[$team->lookup_pk_no] = $team->lookup_name;
            }
        }
        return view('admin.sales_team_management.team_target', compact('team_arr'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function load_team_lead_by_team(Request $request)
    {
        $team_id = $request->team_id;
        $team_arr = [];
        $team_mem = TeamAssign::where('team_lookup_pk_no', $team_id)->where('team_lead_flag', 1)->get();
        if (!empty($team_mem)) {
            foreach ($team_mem as $member) {
                $team_arr[$member->user_pk_no] = $member->user['user_fullname'];
            }
        }
        return view('admin.components.team_member_dropdown', compact('team_arr'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function load_team_list_by_team(Request $request)
    {
        $team_id = $request->team_id;
        $team_target_date = date("Y-m", strtotime($request->team_target_date));
        $team_member = TeamAssign::where('team_lookup_pk_no', $team_id)->where('team_lead_flag','!=', 1)->get();
        $lookup_arr = [4, 5];
        $project_cat = $project_area = [];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4)
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 5)
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;
            }
        }

        $target_data = TeamTarget::where('teammem_pk_no', $team_id)->where('yy_mm', $team_target_date)->get();
        $target_arr = [];
        if (!empty($target_data)) {
            foreach ($target_data as $tdata) {

                $target_arr[$tdata->user_pk_no]['target_amount'] = $tdata->target_amount;
                $target_arr[$tdata->user_pk_no]['target_by_lead_qty'] = $tdata->target_by_lead_qty;
                $target_arr[$tdata->user_pk_no]['target_pk_no'] = $tdata->target_pk_no;
            }
        }

        return view('admin.sales_team_management.team_target.teammember_list_by_team', compact('team_member','project_cat','project_area','target_arr'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_team_target(Request $request)
    {
        $user_id = 1; //Session::get('user.ses_user_pk_no');

        $target_data_arr=$target_update_data_arr=[];
        $team_user = $request->team_user;
        for($i=0; $i<count($team_user);$i++){

            $target_pk = $request->target_pk_no[$i];
            if($target_pk!="")
            {
                $target = TeamTarget::find($target_pk);
            }
            else
            {
                $target = new TeamTarget();
            }

            $target->teammem_pk_no = $request->team_name;
            $target->lead_pk_no = $request->team_lead;
            $target->user_pk_no = $team_user[$i];
            $target->category_lookup_pk_no = $request->category_id[$i];
            $target->area_lookup_pk_no = $request->area_id[$i];
            $target->yy_mm = date("Y-m", strtotime($request->team_target_date));
            $target->target_amount = (trim($request->target_amount[$i]))?trim($request->target_amount[$i]):0;
            $target->target_by_lead_qty = (trim($request->target_qty[$i]))?trim($request->target_qty[$i]):0;
            $target->created_by = $user_id;
            $target->created_at = date("Y-m-d");

            if($target_pk!="")
            {
                $target->save();
            }
            else
            {
                $target_data_arr[] = $target->attributesToArray();
            }
        }

        if(!empty($target_data_arr))
        {
            TeamTarget::insert($target_data_arr);
        }

        return response()->json(['message' => 'Data Saved Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }


}
