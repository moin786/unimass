<?php

namespace App\Http\Controllers\Admin;

use App\LookupData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index()
	{
		$lookup_arr = [2,3,4,5,6,7,8,9,10,11,12,13,14,15];
		$lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
		$project_cat = $project_area = $project_name = $project_size = $hotline = $billboards = $project_boards = $flyers = $fnfs = array();
		foreach ($lookup_data as $key => $value) {
			if($value->lookup_type == 2)
				$digital_mkt[$key] = $value->lookup_name;

			if($value->lookup_type == 3)
				$hotline[$key] = $value->lookup_name;

			if($value->lookup_type == 4)
				$project_cat[$key] = $value->lookup_name;

			if($value->lookup_type == 5)
				$project_area[$key] = $value->lookup_name;

			if($value->lookup_type == 6)
				$project_name[$key] = $value->lookup_name;

			if($value->lookup_type == 7)
				$project_size[$key] = $value->lookup_name;

			if($value->lookup_type == 10)
				$ocupations[$key] = $value->lookup_name;

			if($value->lookup_type == 11)
				$press_adds[$key] = $value->lookup_name;

			if($value->lookup_type == 12)
				$billboards[$key] = $value->lookup_name;

			if($value->lookup_type == 13)
				$project_boards[$key] = $value->lookup_name;

			if($value->lookup_type == 14)
				$flyers[$key] = $value->lookup_name;

			if($value->lookup_type == 15)
				$fnfs[$key] = $value->lookup_name;
		}
		return view('admin.report_module.search_engine', compact('project_cat','project_area','project_name','project_size','hotline','ocupations','digital_mkt','press_adds','billboards','project_boards','flyers','fnfs'));
	}

	public function search_result(Request $request)
	{
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		$sql_cond = ( trim($request->txt_customer_name) != "")?" where customer_firstname like '%".trim($request->txt_customer_name)."%'":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= (trim($request->txt_mobile_no)!="")?" $clause (phone1 like '%".trim($request->txt_mobile_no)."%' or phone2 like '%".trim($request->txt_mobile_no)."%')":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= (trim($request->txt_email)!="")?" $clause email_id like '%".trim($request->txt_email)."%'":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbOccupation!="")?" $clause occupation_pk_no=$request->cmbOccupation":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbOrganization!="")?" $clause organization_pk_no=$request->cmbOrganization":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbCategory!="")?" $clause project_category_pk_no=$request->cmbCategory":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbArea!="")?" $clause project_area_pk_no=$request->cmbArea":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbProjectName!="")?" $clause Project_pk_no=$request->cmbProjectName":"";
		$clause = ($sql_cond!="")? " and":" where";
		$sql_cond .= ($request->cmbSize!="")?" $clause project_size_pk_no=$request->cmbSize":"";

		$clause = ($sql_cond!="")? " and":" where";
		$txt_cus_dob_from = (trim($request->txt_cus_dob_from)!="")? date("Y-m-d",strtotime($request->txt_cus_dob_from)):"";
		$txt_cus_dob_to = (trim($request->txt_cus_dob_to)!="")? date("Y-m-d",strtotime($request->txt_cus_dob_to)):"";
		$sql_cond .= ($request->txt_cus_dob_from!="")?" $clause Customer_dateofbirth between '$txt_cus_dob_from' and '$txt_cus_dob_to'":"";

		$clause = ($sql_cond!="")? " and":" where";
		$txt_mar_date_from = (trim($request->txt_mar_date_from)!="")? date("Y-m-d",strtotime($request->txt_mar_date_from)):"";
		$txt_mar_date_to = (trim($request->txt_mar_date_to)!="")? date("Y-m-d",strtotime($request->txt_mar_date_to)):"";
		$sql_cond .= ($request->txt_cus_dob_from!="")?" $clause Marriage_anniversary between '$txt_mar_date_from' and '$txt_mar_date_to'":"";

		$clause = ($sql_cond!="")? " and":" where";
		$txt_cus_wife_dob_from = (trim($request->txt_cus_wife_dob_from)!="")? date("Y-m-d",strtotime($request->txt_cus_wife_dob_from)):"";
		$txt_cus_wife_dob_to = (trim($request->txt_cus_wife_dob_to)!="")? date("Y-m-d",strtotime($request->txt_cus_wife_dob_to)):"";
		$sql_cond .= ($txt_cus_wife_dob_from!="")?" $clause customer_wife_dataofbirth between '$txt_cus_wife_dob_from' and '$txt_cus_wife_dob_to'":"";

		$clause = ($sql_cond!="")? " and":" where";
		$txt_cus_child_dob_from = (trim($request->txt_cus_child_dob_from)!="")? date("Y-m-d",strtotime($request->txt_cus_child_dob_from)):"";
		$txt_cus_child_dob_to = (trim($request->txt_cus_child_dob_to)!="")? date("Y-m-d",strtotime($request->txt_cus_child_dob_to)):"";
		$sql_cond .= ($txt_cus_wife_dob_from!="")?" $clause (children_dateofbirth1 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth2 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to' or children_dateofbirth3 between '$txt_cus_child_dob_from' and '$txt_cus_child_dob_to')":"";

		//echo "SELECT * from t_lead2lifecycle_vw $sql_cond";
		$lead_data = DB::select("SELECT a.*,b.next_followup_Note from t_lead2lifecycle_vw a LEFT JOIN t_leadfollowup b ON a.lead_pk_no=b.lead_pk_no AND b.next_followup_flag=1 $sql_cond");
		return view('admin.report_module.search_result', compact('lead_data','lead_stage_arr'));

	}
}