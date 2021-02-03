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

        return response()->json(['message'=>'Lookup Data created successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
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

        if($ldata->save())
    	{
    		return response()->json(['message'=>'Lookup Data updated successfully.','title'=>'Success',"positionClass" => "toast-top-right"]);
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

        $flat_list = FlatSetup::all();
        return view('admin.settings.project_wise_flat_list',compact('project_cat','project_area','project_name','project_size','flat_list'));
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
            'flat_size' => 'required'
        ]);

        $user_id = 1; //Session::get('user.ses_user_pk_no');

        $fsetup = new FlatSetup();
        $fsetup->category_lookup_pk_no = $request->category;
        $fsetup->area_lookup_pk_no = $request->area;
        $fsetup->project_lookup_pk_no = $request->project_name;
        $fsetup->size_lookup_pk_no = $request->flat_size;
        $fsetup->flat_name = $request->flat_name;
        $fsetup->flat_description = $request->flat_description;
        $fsetup->flat_status = 0;
        $fsetup->created_by = $user_id;
        $fsetup->created_at = date("Y-m-d");

        if ($fsetup->save()) {
            return response()->json(['message' => 'Data Saved Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Data Save Failed.', 'title' => 'Failed', 'positionClass' => 'toast-top-right']);
        }
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
        $fsetup->category_lookup_pk_no = $request->category;
        $fsetup->area_lookup_pk_no = $request->area;
        $fsetup->project_lookup_pk_no = $request->project_name;
        $fsetup->size_lookup_pk_no = $request->flat_size;
        $fsetup->flat_name = $request->flat_name;
        $fsetup->flat_description = $request->flat_description;
        $fsetup->flat_status = 0;
        $fsetup->created_by = $user_id;
        $fsetup->created_at = date("Y-m-d");

        if ($fsetup->save()) {
            return response()->json(['message' => 'Data Updated Successfully.', 'title' => 'Success', "positionClass" => "toast-top-right"]);
        } else {
            return response()->json(['message' => 'Data Update Failed.', 'title' => 'Failed', 'positionClass' => 'toast-top-right']);
        }
    }


}
