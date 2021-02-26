<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use DB;
use App\district;
use App\Thana;

class DistrictController extends Controller
{
    public function district_thana_setup()
	{
		$ditrict = district::all();
        $upazilas = DB::table('upazilas')->select('upazilas.id','upazilas.thana_name','districts.district_name')
            ->leftjoin('districts','upazilas.district_id','districts.id')->get();
        return view('admin.settings.district_thana.district_thana_setup',compact("ditrict",'upazilas'));

    }
    public function add_district_thana_popup()
	{
		$district = DB::table('districts')->get();
        return view('admin.settings.district_thana.create_new_thana_district_popup',compact("district"));

    }

    public function storeDistrict(Request $request){
    	$this->validate($request,[
            'district_name' => 'required|unique:districts',
        ]);
        $dis_name = Str::ucfirst($request->district_name);
    	$data = array();
    	$data['district_name'] = $dis_name;
        $redirectURL = 'district_thana_setup';

        DB::table('districts')->insert($data);
        
          return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);
      
     
    }
     public function edit_district(Request $request ,$id)
	{
		$district = DB::table('districts')->where('id',$id)->first();
        return view('admin.settings.district_thana.district_edit_form',compact("district"));

    }
    public function updateDistrict(Request $request){
    	$this->validate($request,[
            'district_name' => 'required',
        ]);

    	$data = array();
    	$data['district_name'] = $request->district_name;
        $redirectURL = 'district_thana_setup';

        DB::table('districts')->where('id',$request->hdnUserId)->update($data);
        
          return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);
      
     
    }
    public function delete($id){
    	DB::table('districts')->where('id',$id)->delete();
    	$redirectURL = 'district_thana_setup';
        return back()->with('message',"Data Deleted successfully");
    	 return response()->json(['message'=>'Data Deleted successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);
    }


    //thana 
     public function all_thana()
	{
		$upazilas = Thana::all();
        return view('admin.settings.district_thana.thana_list',compact("upazilas"));

    }
    public function getThanaByDistrict(Request $request){
        $data = DB::table('upazilas')->where('district_id',$request->district_value)->first();
        return view('admin.settings.district_thana.create_new_thana_district_popup',compact("data"));

    }

    public function storeThana(Request $request){
    	$this->validate($request,[
            'district_id' => 'required',
            'Thana'		   => 'required'
        ]);

    	$data = array();
    	$data['district_id'] = $request->district_id;
    	$data['thana_name'] = $request->Thana;
        $redirectURL = 'district_thana_setup';

        DB::table('upazilas')->insert($data);
        
          return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);     
     
    }
    public function edit_thana(Request $request ,$id)
    {   $district = DB::table('districts')->get();
        $thana = DB::table('upazilas')->where('id',$id)->first();
        return view('admin.settings.district_thana.edit_thana',compact("thana",'district'));

    }
    public function updateThana(Request $request){
        $this->validate($request,[
            'district_id' => 'required',
            'Thana'         => 'required'
        ]);

        $data = array();
        $data['district_id'] = $request->district_id;
        $data['thana_name'] = $request->Thana;
        $redirectURL = 'district_thana_setup';

        DB::table('upazilas')->where('id',$request->id)->update($data);
        
          return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);     
     
    }
     public function deleteThana($id){
        DB::table('upazilas')->where('id',$id)->delete();
        $redirectURL = 'district_thana_setup';
        return back()->with('message',"Data Deleted successfully");
        return response()->json(['message'=>'Data Deleted successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);
    }
}
