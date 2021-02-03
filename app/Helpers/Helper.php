<?php

namespace App\Helpers;
use App\LookupData;
use Illuminate\Support\Facades\DB;
class Helper
{
	public static function getLookupByCategory(int $type)
	{
		//$lookup_arr = [2,3,4,5,6,7,8,9];
		$lookup_arr = [];
		$lookup_data = LookupData::where('lookup_type', $type)->get();

		foreach ($lookup_data as $key => $value) {

			$lookup_arr[$value->lookup_type][$value->lookup_pk_no] = $value->lookup_name;
		}
		return $lookup_arr;
	}

	public static function getTeamMembers(int $tl_id)
	{
		$get_all_tem_member = DB::select("SELECT GROUP_CONCAT(user_pk_no) team_members FROM t_teambuild WHERE team_lead_user_pk_no=$tl_id")[0];
		return $get_all_tem_member->team_members.",".$tl_id;
	}

	public function sendSms($from_user = "", $to_user="", $message="",$username = "", $password="") {
        $url = "https://api.mobireach.com.bd/SendTextMessage";
        $data = [
            "Username" => "rupayan",
            "Password" => "Abcd@112233",
            "From" => "R City",
            "To" => "8801676089093",
            "Message" => "Test SMS",
        ];

        $ch = \curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}