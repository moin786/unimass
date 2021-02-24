<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DistrictThanaController extends Controller
{
    public function districtThana(Request $request) {
        if (!isset($request->district_id) || empty($request->district_id)) {
            return response()->json(['err' => 'District missing'],406);
        }

        $upazilas = DB::table('upazilas')->where('district_id', $request->district_id)->get();

        return $upazilas;
    }
}
