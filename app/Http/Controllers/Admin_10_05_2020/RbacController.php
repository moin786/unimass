<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\LookupData;
use App\Pages;
use App\Rback;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RbacController extends Controller
{
    public function index()
    {
        $user_groups = LookupData::where('lookup_type', 1)->get();
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }

        $role_users = DB::table('s_user')
            ->select('role_lookup_pk_no', DB::raw('count(*) as total'))
            ->groupBy('role_lookup_pk_no')
            ->get();
        $user_group_count = [];
        if (!empty($role_users)) {
            foreach ($role_users as $ug) {
                $user_group_count[$ug->role_lookup_pk_no] = $ug->total;
            }
        }
        return view('admin.settings.rbac.index', compact('user_group', 'user_group_count'));
    }

    public function rbac_pages($role_id)
    {
        $module_arr = config('static_arrays.module_arr');
        $pages = Pages::all();
        $pages_arr = [];
        foreach($pages as $page)
        {
            $pages_arr[$page->module_lookup_pk_no][$page->page_pk_no] = $page->page_name;
        }
        $role_permission_sql = Rback::where(["role_lookup_pk_no" => $role_id, "row_status" => 1])->get();
        $role_permission = [];
        foreach ($role_permission_sql as $permission) {
            $role_permission[$permission->page_pk_no] = $permission->role_lookup_pk_no;
        }

        return view('admin.settings.rbac.pages', compact('pages_arr', 'role_permission', 'role_id', 'module_arr'));
    }

    public function rbac_assign(Request $request, $role_id, $page_id)
    {
        $checkRollPermission = Rback::where(['role_lookup_pk_no' => $role_id,'page_pk_no' => $page_id])->first();
        if(empty($checkRollPermission))
        {
            $rbac = new Rback();
            $rbac->role_lookup_pk_no = $role_id;
            $rbac->page_pk_no = $page_id;
            $rbac->row_status = $request->is_checked;
            $rbac->save();
        }
        else
        {
            DB::table('s_rbac')->where(['role_lookup_pk_no' => $role_id,'page_pk_no' => $page_id])->update([
                'row_status' => $request->is_checked
            ]);
        }

        return response()->json(['message' => 'Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
    }

}
