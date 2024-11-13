<?php

namespace App\Http\Controllers\Auth;
use Auth;
use Session;
use App\User;
use App\TeamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        $user_info = Auth::user();
        $user_info = User::where('email', $request->email)->with('teamUser')->first();
        //dd($user_info);
        // ASSIGN SESSION VALUE OF AUTHENTICATED USER
        session(['user.is_super_admin' => $user_info->is_super_admin]);
        session(['user.ses_user_id' => $user_info->id]);
        session(['user.ses_email' => $user_info->email]);
        session(['user.ses_user_pk_no' => $user_info->teamUser['user_pk_no']]);
        session(['user.ses_full_name' => $user_info->teamUser['user_fullname']]);
        session(['user.ses_role_lookup_pk_no' => $user_info->role]);
        session(['user.ses_role_name' => $user_info->userRole['lookup_name']]);
        session(['user.user_type' => $user_info->user_type]);
        session(['user.is_bypass' => $user_info->teamUser['is_bypass']]);
        session(['user.bypass_date' => $user_info->teamUser['bypass_date']]);
        session(['user.ses_auto_dist' => $user_info->teamUser['auto_distribute']]);
        session(['user.ses_dist_date' => $user_info->teamUser['distribute_date']]);
        session()->forget(['user.ses_other_user_pk_no','user.ses_other_full_name','user.ses_other_role_lookup_pk_no','user.ses_other_role_name','user.is_ses_other_hod','user.is_ses_other_hot','user.is_other_team_leader']);
            session()->save();
        $user_id = $user_info->teamUser['user_pk_no']*1;

        $loginuserid = $user_info->id;

        $s_user_info = DB::table('s_user')->where('user_id', $user_info->id)->where('user_type',1)->get();
        
        if ($s_user_info->isEmpty()) {
            if ($user_info->id != 24) {
                $hod_user = DB::table('t_teambuild')
                                ->where('user_pk_no', function($query) use($loginuserid){
                                    return $query->from('s_user')
                                        ->select('user_pk_no')
                                        ->where('user_type',2)
                                        ->where('user_id', $loginuserid)
                                        ->get();
                                })->get();

                session(['user.hod_user' => @$hod_user[0]->hod_user_pk_no]);
            }
        } else {
            $hod_user = DB::table('t_teambuild')
                                ->where('user_pk_no', function($query) use($loginuserid){
                                    return $query->from('s_user')
                                        ->select('user_pk_no')
                                        ->where('user_type',1)
                                        ->where('user_id', $loginuserid)
                                        ->get();
                                })->get();

                session(['user.hod_user' => @$hod_user[0]->hod_user_pk_no]);
        }

        $is_hod = DB::select("SELECT COUNT(1) hod FROM t_teambuild WHERE hod_user_pk_no in(".$user_id.") and row_status=1")[0]->hod;
        $is_hot = DB::select("SELECT COUNT(1) hot FROM t_teambuild WHERE hot_user_pk_no in(".$user_id.") and row_status=1")[0]->hot;
        $is_team_leader = DB::select("SELECT COUNT(1) team_leader FROM t_teambuild WHERE team_lead_user_pk_no in(".$user_id.") and row_status=1")[0]->team_leader;       
        if($is_hod > 0)
        {
            session(['user.is_ses_hod' => 1]);
        }
        if($is_hot > 0)
        {
            session(['user.is_ses_hot' => 1]);
        }
        if($is_team_leader > 0)
        {
            session(['user.is_team_leader' => 1]);
        }
       // dd($user_info);
        /*$data = session('user.is_super_admin');;
        dd($data);*/

    }
}
