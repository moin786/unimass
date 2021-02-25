<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class projectScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function scheduleController()
    {
        return view("admin.lead_management.schedule_collection.schedule_collection");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
    public function load_schedule_collection(Request $request){
        $tab_type =  $request->tab_type;
        if($tab_type == 1){
            return view("admin.lead_management.schedule_collection.sold_lead");
        }
        if($tab_type == 2){
            return view("admin.lead_management.schedule_collection.missed_followup");
        }
        if($tab_type == 3){
            return view("admin.lead_management.schedule_collection.sold_lead");
        }

    }

    public function load_schedule_followup_modal(Request $request){
        $tab_type =  $request->tab_type;
        if($tab_type == 1){
            return view("admin.lead_management.schedule_collection.schedule_followup.schedule_followup");
        }
        if($tab_type == 2){
            return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal");
        }
        if($tab_type == 3){
            return view("admin.lead_management.schedule_collection.schedule_followup.compeleted_collection");
        }

    }


    public function lead_sold_view(){
        return view("admin.lead_management.schedule_collection.schedule_followup.schedule_collection_modal_data");
    }


}
