<?php

namespace App\Http\Controllers\Admin;

use Response;
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
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
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
		return view('admin.report_module.search_engine', compact('project_cat','project_area','project_name','project_size','hotline','ocupations','digital_mkt','press_adds','billboards','project_boards','flyers','fnfs','lead_stage_arr'));
	}

	function serch_result_query($request)
	{
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
		$sql_cond .= ($request->cmb_stage > 0)?" $clause lead_current_stage=$request->cmb_stage":"";

		$clause = ($sql_cond!="")? " and":" where";
		$entry_date = (trim($request->txt_entry_date)!="")? date("Y-m-d",strtotime($request->txt_entry_date)):"";
		$entry_date_to = (trim($request->txt_entry_date_to)!="")? date("Y-m-d",strtotime($request->txt_entry_date_to)):"";
		$sql_cond .= ($entry_date!="")? " $clause created_at >='$entry_date' and created_at <='$entry_date_to'":"";

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

		return DB::select("SELECT a.*,c.next_followup_Note
			FROM t_lead2lifecycle_vw a
			LEFT JOIN (SELECT b.lead_pk_no,b.next_followup_Note,MAX(lead_followup_pk_no) AS maxid
			FROM t_leadfollowup b GROUP BY b.lead_pk_no,b.next_followup_Note) AS c
			ON a.lead_pk_no = c.maxid $sql_cond");
	}

	public function search_result(Request $request)
	{
		$lead_stage_arr = config('static_arrays.lead_stage_arr');
		$lead_data = $this->serch_result_query($request);
		return view('admin.report_module.search_result', compact('lead_data','lead_stage_arr'));

	}

	public function export_report(Request $request)
	{
		

		$lead_data = $this->serch_result_query($request);

		$headers = array(
			"Content-type" => "text/csv",
			"Content-Disposition" => "attachment; filename=file.csv",
			"Pragma" => "no-cache",
			"Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
			"Expires" => "0"
		);
		$columns = array("Lead ID","Entry Date","Customer First Name","Customer Last Name","Country Code1","Phone Number 1","Country Code2","Phone Number 2","Email","Occupation","Organization","Category","Area","Project Name","Size","Sub Source Name","Digital Marketing","Emp ID","Name","Position","Contact Number","SAC Name","SAC Note","Hotline","Customer DOB","Marriage Anniversary","Wife Name","Wife DOB","Children Name1","Children DOB1","Children Name2","Children DOB2","Children Name3","Stage","Sales Agent");

		$callback = function() use ($lead_data, $columns)
		{

			$file = fopen('php://output', 'w');
			fputcsv($file, $columns);

			foreach($lead_data as $ldata) {
				$lead_stage_arr = config('static_arrays.lead_stage_arr');
				//echo $lead_stage_arr[$ldata->lead_current_stage];die;
				$stage = isset($lead_stage_arr[$ldata->lead_current_stage])?$lead_stage_arr[$ldata->lead_current_stage]:'';
				fputcsv($file, array($ldata->lead_id, $ldata->created_at, $ldata->customer_firstname, $ldata->customer_lastname, $ldata->phone1_code, $ldata->phone1, $ldata->phone2_code, $ldata->phone2, $ldata->email_id, $ldata->occup_name, $ldata->org_name, $ldata->project_category_name, $ldata->project_area, $ldata->project_name, $ldata->project_size, $ldata->source_auto_sub, $ldata->source_digital_marketing, $ldata->source_ir_emp_id, $ldata->source_ir_name, $ldata->source_ir_position, $ldata->source_ir_contact_no, $ldata->source_sac_name, $ldata->source_sac_note, $ldata->source_hotline, $ldata->Customer_dateofbirth, $ldata->Marriage_anniversary, $ldata->customer_wife_name, $ldata->customer_wife_dataofbirth, $ldata->children_name1, $ldata->children_dateofbirth1, $ldata->children_name2, $ldata->children_dateofbirth2, $ldata->children_name3, $stage, $ldata->lead_sales_agent_name));
			}
			fclose($file);
		};

		return Response::stream($callback, 200, $headers);
	}
}