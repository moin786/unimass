<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\TeamUser;
use App\LookupData;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
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

        $users = User::latest()->get()->except(Auth::id());
        return view('admin.users.index', compact('users', 'user_group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_type = config('static_arrays.agent_type');
        $lookup_arr = [1, 4, 5];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 1)
                    $user_group[$ldata->lookup_pk_no] = $ldata->lookup_name;
            }
        }
        $users = User::latest()->get()->except(Auth::id());
        return view('admin.users.create', compact('users', 'user_group','user_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cmbUserGroup' => 'required',
            'txtUserName' => 'required',
            'txtEmail' => 'required|email',
            'txtContract' => 'required',
            'pwdPassword' => 'required'
        ]);

        $user = new User();
        $user->name = $request->txtUserName;
        $user->role = $request->cmbUserGroup;
        $user->user_type = $request->cmbUserType;
        $user->email = $request->txtEmail;
        $user->phone = $request->txtContract;
        $user->address = $request->txtAddress;
        $user->password = Hash::make($request->pwdPassword);

        if ($user->save()) {
            $t_user = new TeamUser();
            $t_user->user_id = $user->id;
            $t_user->User_name = $request->txtUserName;
            $t_user->user_fullname = $request->txtUserName;
            $t_user->employee_id = '';
            $t_user->role_lookup_pk_no = $request->cmbUserGroup;
            $t_user->user_type = $request->cmbUserType;
            $t_user->email_id = $request->txtEmail;
            $t_user->mobile_no = $request->txtContract;
            $t_user->address = $request->txtAddress;
            if ($t_user->save()) {
                return response()->json(['message' => 'User created successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
            }
        } else {
            return response()->json(['message' => 'User create Failed.', 'title' => 'Failed', 'positionClass' => 'toast-top-right']);
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
    public function edit($id)
    {
        $user_type = config('static_arrays.agent_type');
        $user_groups = LookupData::where('lookup_type', 1)->get();
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }
        $user = User::findOrFail($id);
        return view('admin.users.create', compact('user', 'user_group','user_type'));
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
        $user = User::findOrFail($request->hdnUserId);
        $user->name = $request->txtUserName;
        $user->role = $request->cmbUserGroup;
        $user->user_type = $request->cmbUserType;
        $user->email = $request->txtEmail;
        $user->phone = $request->txtContract;
        $user->address = $request->txtAddress;
        $user->status = $request->cmbUserStatus;
        $user->password = Hash::make($request->pwdPassword);

        if ($user->save()) {
            if (TeamUser::where('user_id', '=', $request->hdnUserId)->exists()) {
                $t_user = TeamUser::where('user_id', '=', $request->hdnUserId)->first();
            } else {
                $t_user = new TeamUser();
                $t_user->user_id = $user->id;
            }

            $t_user->User_name = $request->txtUserName;
            $t_user->user_fullname = $request->txtUserName;
            $t_user->employee_id = '';
            $t_user->role_lookup_pk_no = $request->cmbUserGroup;
            $t_user->user_type = $request->cmbUserType;
            $t_user->email_id = $request->txtEmail;
            $t_user->mobile_no = $request->txtContract;
            $t_user->address = $request->txtAddress;
            $t_user->row_status = $request->cmbUserStatus;

            if ($t_user->save()) {
                return response()->json(['message' => 'User updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
            } else {
                return response()->json(['message' => 'User update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
            }
        } else {
            return response()->json(['message' => 'User update Failed.', 'title' => 'Failed', "positionClass" => "toast-top-right"]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_users(Request $request)
    {
        $user_groups = LookupData::where('lookup_type', 1)->get();
        $user_group = [];
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }

        $users = User::latest()->get()->except(Auth::id());
        return view('admin.users.user_list', compact('users', 'user_group'));
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
}
