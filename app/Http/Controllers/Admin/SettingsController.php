<?php

namespace App\Http\Controllers\Admin;

use App\LookupData;
use App\FlatSetup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class SettingsController extends Controller
{
	public function index()
	{

		$lookup_type = config('static_arrays.lookup_array');
        $lookup_data = LookupData::where("lookup_type",">",0)->get();
        return view('admin.settings.lookup.index',compact('lookup_data','lookup_type'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lookup_type = config('static_arrays.lookup_array');
        return view('admin.settings.lookup.create',compact('lookup_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$create_date = date('Y-m-d');
        $order_execute = DB::statement(
            DB::raw("CALL proc_lookdata_ins ( $request->cmbLookupType,1,'$request->txtLookupName',$request->cmbLookupStatus,1,1,'$create_date' )")
        );
        $redirectURL = 'settings';
        return response()->json(['message'=>'Lookup Data created successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage" => $redirectURL]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lookup_type = config('static_arrays.lookup_array');
        $where = array('lookup_pk_no' => $id);
        $lookup_data  = LookupData::where($where)->first();

        return view('admin.settings.lookup.create',compact('lookup_data','lookup_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ldata = LookupData::findOrFail($id);
        $ldata->lookup_name = $request->txtLookupName;
        $ldata->lookup_type = $request->cmbLookupType;
        $ldata->lookup_row_status = $request->cmbLookupStatus;
        $redirectURL = 'settings';
        if($ldata->save())
        {
          return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right","redirectPage"=>$redirectURL]);
      }
      else
      {
          return response()->json(['message'=>'Lookup Data update Failed.','title'=>'Failed',"positionClass" => "toast-top-right"]);
      }
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function load_list(Request $request)
    {
        $lookup_type = config('static_arrays.lookup_array');
        $lookup_data = LookupData::all();
        return view('admin.settings.lookup.lookup_list',compact('lookup_data','lookup_type'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function project_wise_flat()
    {
        $category_project_arr = $project_flat_arr = [];
        $flat_data = DB::select("SELECT `s_projectwiseflatlist`.`flatlist_pk_no`,`s_projectwiseflatlist`.`category_lookup_pk_no`,`s_projectwiseflatlist`.`flat_name`, `s_projectwiseflatlist`.`area_lookup_pk_no`,`s_projectwiseflatlist`.`flat_status`,
            `s_projectwiseflatlist`.`flat_asking_price`,`s_projectwiseflatlist`.`flat_down_payment`,`s_projectwiseflatlist`.`flat_installment`,`s_projectwiseflatlist`.`flat_number_installment`,
           `s_projectwiseflatlist`.`project_lookup_pk_no`, `a`.`lookup_name` AS `category_name`,`b`.`lookup_name` AS `area_name`, `c`.`lookup_name` AS `project_name`,`d`.`lookup_name` as `flat_size` 
           FROM `s_projectwiseflatlist` 
           LEFT JOIN `s_lookdata` AS `a` ON `s_projectwiseflatlist`.`category_lookup_pk_no` = `a`.`lookup_pk_no` 
           LEFT JOIN `s_lookdata` AS `b` ON `s_projectwiseflatlist`.`area_lookup_pk_no` = `b`.`lookup_pk_no` 
           LEFT JOIN `s_lookdata` AS `c`  ON  `s_projectwiseflatlist`.`project_lookup_pk_no` = `c`.`lookup_pk_no`
           LEFT JOIN `s_lookdata` AS `d`  ON  `s_projectwiseflatlist`.`size_lookup_pk_no` = `d`.`lookup_pk_no`

           ");
       
        if(!empty($flat_data))
        {
            foreach ($flat_data as $row) {
                $category_project_arr[$row->category_lookup_pk_no][$row->area_lookup_pk_no][$row->project_lookup_pk_no] = $row->category_name."_".$row->area_name."_".$row->project_name;
                $project_flat_arr[$row->project_lookup_pk_no][] = $row->flat_name.'('. $row->flat_size.')_'. $row->flat_status.'_'.$row->flatlist_pk_no.'_'.$row->flat_asking_price.'_'.$row->flat_down_payment.'_'.$row->flat_installment.'_'.$row->flat_number_installment ;
            }
        }

      
        return view('admin.settings.project_wise_flat_list',compact('project_flat_arr','category_project_arr'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_project_wise_flat()
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $key => $ldata) {
                if ($ldata->lookup_type == 4)
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 5)
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 6)
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 7)
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
            }
        }

        return view('admin.settings.flat_setup.flat_setup_form', compact('project_cat','project_area','project_name','project_size'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store_flat_setup(Request $request)
    {
        $this->validate($request,[
            'category' => 'required',
            'area' => 'required',
            'project_name' => 'required',
        ]);

        $user_id = 1; //Session::get('user.ses_user_pk_no');
        $flat_name = $request->flat_name;
        $flat_price = $request->flat_price;
        $flat_down_payment = $request->flat_down_payment;
        $flat_installment = $request->flat_installment;
        $flat_int_amount = $request->flat_int_amount;
        $flat_size = $request->flat_size;
        $status = $request->status;
        for($i=0;$i<count($request->flat_name);$i++){
            if($flat_name[$i] != null ){
                $fsetup = new FlatSetup();
                $fsetup->flat_description = " ";
                $fsetup->category_lookup_pk_no = $request->category;
                $fsetup->area_lookup_pk_no = $request->area;
                $fsetup->project_lookup_pk_no = $request->project_name;
                $fsetup->size_lookup_pk_no = $flat_size[$i];
                $fsetup->flat_name = $flat_name[$i];
                $fsetup->flat_asking_price = $flat_price[$i];
                $fsetup->flat_down_payment = $flat_down_payment[$i];
                $fsetup->flat_installment = $flat_int_amount[$i];
                $fsetup->flat_number_installment = $flat_installment[$i];
                $fsetup->flat_status = $status[$i];
                $fsetup->block_status = 0;
                $fsetup->created_by = $user_id;
                $fsetup->created_at = date("Y-m-d");
                $fsetup->save();
            }
        }
        $redirectURL = 'project_wise_flat';
        return response()->json(['message' => 'Data Saved Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right","redirectPage" => $redirectURL]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_project_wise_flat($id)
    {
        $lookup_arr = [4, 5, 6, 7];
        $lookup_data = LookupData::whereIn('lookup_type', $lookup_arr)->get();
        if (!empty($lookup_data)) {
            foreach ($lookup_data as $ldata) {
                if ($ldata->lookup_type == 4)
                    $project_cat[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 5)
                    $project_area[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 6)
                    $project_name[$ldata->lookup_pk_no] = $ldata->lookup_name;

                if ($ldata->lookup_type == 7)
                    $project_size[$ldata->lookup_pk_no] = $ldata->lookup_name;
            }
        }
        $flat_data = FlatSetup::find($id);
      
        return view('admin.settings.flat_setup.flat_setup_form', compact('project_cat','project_area','project_name','project_size','flat_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update_flat_setup(Request $request)
    {
        $this->validate($request,[
            'category' => 'required',
            'area' => 'required',
            'project_name' => 'required',
            'flat_size' => 'required',
            'flat_name' => 'required'
        ]);

        $user_id = 1; //Session::get('user.ses_user_pk_no');

        $fsetup = FlatSetup::findOrFail($request->hdnFlatSetupId);
        $flat_name = $request->flat_name;
        $flat_price = $request->flat_price;
        $flat_down_payment = $request->flat_down_payment;
        $flat_installment = $request->flat_installment;
        $flat_int_amount = $request->flat_int_amount;
        $flat_size = $request->flat_size;
        $status = $request->status;
        for($i=0;$i<count($request->flat_name);$i++){
            $fsetup->category_lookup_pk_no = $request->category;
            $fsetup->area_lookup_pk_no = $request->area;
            $fsetup->project_lookup_pk_no = $request->project_name;
            $fsetup->size_lookup_pk_no = $flat_size[$i];
            $fsetup->flat_name = $flat_name[$i];
            $fsetup->flat_asking_price = $flat_price[$i];
            $fsetup->flat_down_payment = $flat_down_payment[$i];
            $fsetup->flat_installment = $flat_int_amount[$i];
            $fsetup->flat_number_installment = $flat_installment[$i];
            $fsetup->flat_status = $status[$i];
            $fsetup->block_status = 0;
            $fsetup->updated_at = date("Y-m-d");
            $fsetup->save();
        }
        $redirectURL = 'project_wise_flat';
        return response()->json(['message' => 'Data Updated Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right","redirectPage" => $redirectURL]);
        
    }

    public function validation_setup(){
        $no_action = LookupData::where("lookup_type",23)->first();
        $max_number = LookupData::where("lookup_type",24)->first();
        $max_number = LookupData::where("lookup_type",24)->first();
        $username = LookupData::where("lookup_type",27)->first();
        $password = LookupData::where("lookup_type",28)->first();

        $return_to_call_center = LookupData::where("lookup_type",25)->first();
        return view("admin.settings.validation_setup",compact("no_action","max_number","return_to_call_center","password","username"));
    }
    public function validation_setup_store(Request $request){
        $create_date = date('Y-m-d');
        $order_execute = DB::statement(
            DB::raw("CALL proc_lookdata_ins ( $request->cmbLookupid1,1,'$request->txtLookupName',$request->cmbLookupStatus,1,1,'$create_date' )")
        );

        $order_execute = DB::statement(
            DB::raw("CALL proc_lookdata_ins ( $request->cmbLookupid2,1,'$request->txtLookupPassword',$request->cmbLookupStatus,1,1,'$create_date' )")
        );

        return response()->json(['message'=>'Lookup Data created successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
    }
    public function validation_setup_update(Request $request){
        $ldata = LookupData::where('lookup_type',27)->first();
        $ldata2 = LookupData::where('lookup_type',28)->first();

        $ldata->lookup_name = $request->txtLookupName;
        $ldata->save();

        $ldata2->lookup_name = $request->txtLookupName;
        $ldata2->save();
        return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);

    }
    public function lookup_type_wise_data(){
        $id = $_GET["value"];
        $lookup_type = config('static_arrays.lookup_array');
        $lookup_data = LookupData::where("lookup_type","=",$id)->get();
        return view('admin.settings.lookup.lookup_list',compact('lookup_data','lookup_type'));

    }


}
