<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class StageWiseAttributeController extends Controller
{
	public function index(){

		$lookup_type = config('static_arrays.lead_stage_arr');
		$attr_type_value = config('static_arrays.attributes_type');

		$data = DB::table('t_leadstage_attribute')->get();
		// dd($data);
		return view('admin.settings.stage_wise_attribute.index',['data' => $data,'lookup_type' => $lookup_type,'attr_type_value' => $attr_type_value ]);
	}

	public function create(){
		$lookup_type = config('static_arrays.lead_stage_arr');
		$attr_type = config('static_arrays.attributes_type');
		//dd($lookup_type);
		return view('admin.settings.stage_wise_attribute.create',['lookup_type' => $lookup_type,'attr_type'=>$attr_type]);
	}


	public function allData(){
		$data = DB::table('t_leadstage_attribute')->get();
	}

	public function store(Request $request){
		$data = DB::table('t_leadstage_attribute')->insert(
    	array('attr_type' => $request->stage_name,'attr_sl_no' => $request->serial_number , 'stage_id' => $request->attribute_type , 'attr_name' => $request->attribute_name, 'row_status' => $request->status)
		);
		$redirectURL = 'Stage_wise_attribute_list'; 
		return response()->json(['message' => 'Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right","redirectPage" =>$redirectURL]);
	}

	public function editlist($id){
	$lookup_type = config('static_arrays.lead_stage_arr');
	$attr_type = config('static_arrays.attributes_type');
	$data = $data = DB::table('t_leadstage_attribute')->where('attr_pk_no',$id)->first();
	return view('admin.settings.stage_wise_attribute.create',['lookup_type' => $lookup_type,'data' =>$data,'attr_type' => $attr_type]);
	}

	public function upDate(Request $request,$id){
			$data = DB::table('t_leadstage_attribute')->where('attr_pk_no',$id)->update(
	    	array('attr_type' => $request->stage_name,'attr_sl_no' => $request->serial_number , 'stage_id' => $request->attribute_type , 'attr_name' => $request->attribute_name,'row_status' => $request->status)
			);
			//dd($data);
			$redirectURL = 'Stage_wise_attribute_list'; 
			return response()->json(['message' => 'Data updated successfully.', 'title' => 'Success', "positionClass" => "toast-top-right", "redirectPage" =>$redirectURL]);
		}
	public function stage_wise_attribute_get(){

		$lookup_type = config('static_arrays.lead_stage_arr');
		$attr_type_value = config('static_arrays.attributes_type');

		 $data =  DB::table('t_leadstage_attribute')
				 ->where('stage_id',$_GET['value'])
				 ->where('row_status',1)->get();
		 $attribute ='';
		 foreach($data as $item){
		 	$attribute .= '
            <div id="'.$item->attr_pk_no.'1">
		 	<input type="checkbox" id="'.$item->attr_pk_no.'" class="form-group" name="attribute_type[]" onchange="appendDecsision(this)" data-value="'.strtolower($attr_type_value[$item->attr_type]).'" value="'.$item->attr_pk_no.'_'.$item->attr_type.'" >
					<label for="'.$item->attr_pk_no.'" style="font-weight:400;"> '.$item->attr_name.' </label> <br>
					</div>
					  
					';
		 }
		 echo $attribute;
	}

}
