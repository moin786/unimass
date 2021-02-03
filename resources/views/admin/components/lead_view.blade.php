{{-- Customer Basic --}}
<style type="text/css">
    h5 {
        font-weight: normal;
    }

    .box-body {
        overflow-x: hidden;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#lead_followup_history" data-toggle="tab" data-type="0" data-action="load_dist_leads" aria-expanded="true">Lead Follwup History</a>
                </li>
                <li class="">
                    <a href="#lead_information" data-toggle="tab" data-type="1" data-action="load_dist_leads" aria-expanded="true">Lead Information</a>
                </li>

                <li class="">
                    <a href="#lead_transfer_history" data-toggle="tab" data-type="0" data-action="load_dist_leads" aria-expanded="true">Lead Transfer History</a>
                </li>
                <li class="">
                    <a href="#lead_history" data-toggle="tab" data-type="0" data-action="load_dist_leads" aria-expanded="true">Lead Edit History</a>
                </li>
                <li class="">
                    <a href="#lead_kyc_history" data-toggle="tab" data-type="0" data-action="load_dist_leads" aria-expanded="true">Lead KYC Edit History</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane  table-responsive" id="lead_information">

                    <div class="box ">
                        <div class="box-header with-border ">
                            <h3 class="box-title">Customer Information</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 p-0">
                                    <div class="col-md-4">
                                        <label for="lead_id">Lead ID :</label>
                                        <h5>{{ $lead_data->lead_id }}</h5>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="cus_entry_date">Date :</label>
                                        <h5>{{ date("d/m/Y", strtotime($lead_data->created_at)) }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lead_entry_type"> Lead Source :</label>
                                        @if($lead_data->lead_entry_type ==1 )
                                        <h5>MQL</h5>
                                        @elseif($lead_data->lead_entry_type == 2)
                                        <h5>Walk In</h5>
                                        @elseif($lead_data->lead_entry_type ==3)
                                        <h5>SGL</h5>
                                        @else
                                        <h5></h5>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-md-12 p-0">
                                    <div class="col-md-4">
                                        <label for="">Client Name 1:</label>
                                        <h5 style="text-transform: capitalize;">{{ $lead_data->customer_firstname }}</h5>
                                        <h5 style="text-transform: capitalize;">{{ $lead_data->customer_lastname }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="">Client Name 2:</label>
                                        <h5 style="text-transform: capitalize;">{{ $lead_data->customer_firstname2 }}</h5>
                                        <h5 style="text-transform: capitalize;">{{ $lead_data->customer_lastname2 }}</h5>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="">Phone Number1 :</label>
                                    @php
                                    $masking_number = substr($lead_data->phone1,0,7);
                                    @endphp
                                    @if($ses_user_id == $lead_data->created_by ||$ses_user_id == $lead_data-> lead_sales_agent_pk_no)

                                    <h5>{{ $lead_data->phone1_code }}{{ $lead_data->phone1 }}</h5>
                                    @else
                                    <h5>{{ $lead_data->phone1_code }}{{ $masking_number }}****</h5>
                                    @endif

                                </div>
                                <div class="col-md-4">
                                    <label for="">Phone Number2 :</label>
                                    @php
                                    $masking_number = substr($lead_data->phone2,0,7);
                                    @endphp
                                    @if($ses_user_id == $lead_data->created_by ||$ses_user_id == $lead_data-> lead_sales_agent_pk_no)

                                    <h5>{{ $lead_data->phone2_code }}{{ $lead_data->phone2 }}</h5>
                                    @else
                                    @if(!empty($masking_number))
                                    <h5>{{ $lead_data->phone1_code }}{{ $masking_number }}****</h5>
                                    @else
                                    <h5></h5>
                                                    @endif
                                    @endif
                                </div>

                                <div class="col-md-4">
                                    <label for="cus_email">Customer Email :</label>
                                    <h5>{{ $lead_data->email_id }}</h5>
                                </div>

                                <div class="col-md-4">
                                    <label>Occupation :</label>
                                    <h5>{{ $lead_data->occup_name }}</h5>
                                </div>

                                <div class="col-md-4">
                                    <label for="cus_occ_org">Organization :</label>
                                    <h5>{{ $lead_data->organization_pk_no }}</h5>
                                </div>
                                <div class="col-md-4">
                                    <label for="cus_occ_org">Designation :</label>
                                    <h5>{{ $lead_data->cust_designation }}</h5>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>


                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Client Address</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">House No</th>
                                        <th scope="col">Road No</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Thana</th>
                                        <th scope="col">Size</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <tr>
                                        <td scope="row">Present</td>
                                        <td scope="row">{{ $lead_data->pre_holding_no}}</td>
                                        <td scope="row">{{ $lead_data->pre_road_no }}</td>
                                        <td scope="row">{{ isset($area[$lead_data->pre_area])? $area[$lead_data->pre_area]: '' }}</td>
                                        <td scope="row">{{ isset($district[$lead_data->pre_district])?$district[$lead_data->pre_district]:'' }}</td>
                                        <td scope="row">{{ isset($thana[$lead_data->pre_thana])? $thana[$lead_data->pre_thana]:'' }}</td>
                                        <td scope="row">{{ $lead_data->pre_size }}</td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Permament</td>
                                        <td scope="row">{{ $lead_data->per_holding_no }}</td>
                                        <td scope="row">{{ $lead_data->per_road_no}}</td>
                                        <td scope="row">{{ isset($area[$lead_data->per_area])?$area[$lead_data->per_area]:'' }}</td>
                                        <td scope="row">{{ isset($district[$lead_data->per_district])?$district[$lead_data->per_district]:'' }}</td>
                                        <td scope="row">{{ isset($thana[$lead_data->per_thana])?$thana[$lead_data->per_thana]:'' }}</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td scope="row">Official</td>
                                        <td scope="row">{{ $lead_data->office_holding_no}}</td>
                                        <td scope="row">{{ $lead_data->office_road_no }}</td>
                                        <td scope="row">{{ isset($area[$lead_data->office_area])?$area[$lead_data->office_area]:'' }}</td>
                                        <td scope="row">{{ isset($district[$lead_data->office_district])? $district[$lead_data->office_district]:'' }}</td>
                                        <td scope="row">{{ isset($thana[$lead_data->office_thana])? $thana[$lead_data->office_thana]:'' }}</td>
                                        <td></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>


                    {{-- Project Detail --}}
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Project Detail</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Category :</label>
                                    <h5>{{ $lead_data->project_category_name }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <label>Area :</label>
                                    <h5>{{ $lead_data->project_area }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label>Project Name :</label>
                                    <h5>{{ $lead_data->project_name }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label>Size :</label>
                                    <h5>{{ $lead_data->project_size }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Source Detail (Auto) --}}
                    <div class="box" style="border-color:#ff851b;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Creator Information(Auto)</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Creator Title :</label>
                                    <h5>{{ $lead_data->source_auto_usergroup }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <label>Creator Name :</label>
                                    <h5>{{ $lead_data->user_full_name }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label>Cluster/BH/TL :</label>
                                    <h5>{{ $lead_data->lead_cluster_head_name }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label>Cluster Head Assign Date :</label>
                                    <h5>{{ (!empty($lead_data->lead_cluster_head_name))? date("d/m/Y", strtotime($lead_data->lead_cluster_head_assign_dt)): " " }}</h5>
                                </div>
                                @if(!empty($lead_data->lead_sales_agent_name))
                                <div class="col-md-6">
                                    <label>Sales Agent Name :</label>
                                    <h5>{{ $lead_data->lead_sales_agent_name }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label>Sales Agent Assign Date :</label>
                                    <h5>{{ (!empty($lead_data->lead_sales_agent_name))? date("d/m/Y", strtotime($lead_data->lead_sales_agent_assign_dt)): " " }}</h5>
                                </div>
                                @else
                                @if(!empty($lead_data->user_type) && $lead_data->user_type ==2)
                                <div class="col-md-6">
                                    <label>Sales Agent Name :</label>
                                    <h5>{{ $lead_data->user_fullname }}</h5>
                                </div>
                                <div class="col-md-6">
                                    <label>Sales Agent Assign Date :</label>
                                    <h5>{{ (!empty($lead_data->user_fullname))? date("d/m/Y", strtotime($lead_data->created_at)): " " }}</h5>
                                </div>
                                @else
                                <div class="col-md-6">
                                    <label>Sales Agent Name :</label>

                                </div>
                                <div class="col-md-6">
                                    <label>Sales Agent Assign Date :</label>
                                </div>
                                @endif

                                @endif
                                @if($lead_data->source_auto_usergroup_pk_no == 73)
                                <div class="col-md-4">
                                    <label>Sub Source Name :</label>
                                    <h5>{{ $lead_data->source_auto_sub }}</h5>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="box" style="border-color:#ff851b;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Meeting Information</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Lead Status</label>

                                    <h6>{{ (!empty($lead_data->meeting_status))? $meeting_status[$lead_data->meeting_status]: "N/A" }}</h6>
                                </div>

                                <div class="col-md-4">
                                    <label>Date </label>
                                    <h6>{{ (!empty($lead_data->meeting_status))? date("d/m/Y", strtotime($lead_data->meeting_date)): "N/A" }}</h6>
                                </div>
                                <div class="col-md-4">
                                    <label>Time </label>
                                    <h6>{{(!empty($lead_data->meeting_status))? date('h:i a', strtotime($lead_data->meeting_time)): "N/A" }}</h6>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Source Detail --}}
                    @if($lead_data->source_auto_usergroup_pk_no == 119)
                    <div class="box" style="border: 0px;">
                        <div class="box-header">
                            <h3 class="box-title">SAC</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="src_name">Name :</label>
                                    <h5>{{ $lead_data->source_sac_name }}</h5>
                                </div>
                                <div class="col-md-12">
                                    <label for="src_note">Note :</label>
                                    <h5>{{ $lead_data->source_sac_note }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sales Executive</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_executive_user_group">User Group :</label>
                                        <h5>{{ $lead_data->user_group_name }}</h5>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_ex_user">User Name :</label>
                                        <h5>{{ $lead_data->lead_sales_agent_name }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    <div class="box" style="border: 0px;">
                        <div class="box-header">
                            <h3 class="box-title">Lead Sub Source</h3>
                        </div>
                        <div class="box-body">

                            @if(isset($digital_mkt[$lead_data->source_digital_marketing]))
                            <div class="form-group" style="margin: 0;">
                                <label style="cursor:pointer;">
                                    <span style="font-size:12px; margin-top:-5px;">
                                        {{ $digital_mkt[$lead_data->source_digital_marketing]
                                        }}
                                    </span>
                                </label>
                            </div>
                            @endif

                        </div>
                    </div>


                    @if($lead_data->source_auto_usergroup_pk_no == 75)
                    <div class="box" style="border: 0px;">
                        <div class="box-header">
                            <h3 class="box-title">Internal Reference</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="sale_agent">Emp ID :</label>
                                    <h5>{{ $lead_data->source_ir_emp_id }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="emp_name">Name :</label>
                                    <h5>{{ $lead_data->source_ir_name }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="emp_position">Position :</label>
                                    <h5>{{ $lead_data->source_ir_position }}</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="emp_contact">Contact Number :</label>
                                    <h5>{{ $lead_data->source_ir_contact_no }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif


                    @if($lead_data->source_auto_usergroup_pk_no == 76)
                    @php
                    $source_hotline = $lead_data->source_hotline;
                    @endphp
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Hotline: </h3>
                            <h5>{{ $source_hotline }}</h5>
                        </div>
                    </div>
                    @endif

                    {{-- Sales Executive --}}
                    @if($lead_data->source_auto_usergroup_pk_no == 75)
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sales Executive</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_executive_user_group">User Group :</label>
                                        <h5>{{ $lead_data->user_group_name }}</h5>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sale_ex_user">User Name :</label>
                                        <h5>{{ $lead_data->lead_sales_agent_name }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- More Details --}}
                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-header with-border">
                            <h3 class="box-title">More Datails (KYC) </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="">Customer DOB :</label>
                                    <h5>{{ ($lead_data->Customer_dateofbirth!='0000-01-01')?date("d/m/Y", strtotime($lead_data->Customer_dateofbirth)):'' }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Spouse Name :</label>
                                    <h5>{{ $lead_data->customer_wife_name }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Spouse DOB:</label>
                                    <h5>{{ ($lead_data->customer_wife_dataofbirth!='0000-01-01')?date("d/m/Y", strtotime($lead_data->customer_wife_dataofbirth)):'' }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Marriage Anniversary :</label>
                                    <h5>{{ ($lead_data->Marriage_anniversary!='0000-01-01')?date("d/m/Y", strtotime($lead_data->Marriage_anniversary)):'' }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Children Name 1 :</label>
                                    <h5>{{ $lead_data->children_name1 }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Children DOB 1 :</label>
                                    <h5>{{ ($lead_data->children_dateofbirth1!='0000-01-01')?date("d/m/Y", strtotime($lead_data->children_dateofbirth1)):'' }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Children Name 2 :</label>
                                    <h5>{{ $lead_data->children_name2 }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Children DOB 2 :</label>
                                    <h5>{{ ($lead_data->children_dateofbirth2!='0000-01-01')?date("d/m/Y", strtotime($lead_data->children_dateofbirth1)):'' }}</h5>
                                </div>
                                <div class="col-md-3">
                                    <label for="">Children Name 3 :</label>
                                    <h5>{{ $lead_data->children_name3 }}</h5>
                                </div>

                                <div class="col-md-3">
                                    <label for="">Children DOB 3 :</label>
                                    <h5>{{ ($lead_data->children_dateofbirth3!='0000-01-01')?date("d/m/Y", strtotime($lead_data->children_dateofbirth3)):'' }}</h5>
                                </div>
                                <div class="col-md-12 p-0">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="food_habit">Food Habit</label>
                                            <h5>{{ $lead_data->food_habit }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="political_opinion">Political Opinion</label>
                                            <h5>{{ $lead_data->political_opinion }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="car_pre">Car Preference</label>
                                            <h5>{{ $lead_data->car_preference }}</h5>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="color_preference">Color Preference</label>
                                            <h5>{{ $lead_data->color_preference }}</h5>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="hobby">Hobby</label>
                                            <h5>{{ $lead_data->hobby }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="traveling_history">Traveling History</label>
                                            <h5>{{ $lead_data->traveling_history }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="memberofclub">Member of Club</label>
                                            <h5>{{ $lead_data->member_of_club }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="child_education">Child Education</label>
                                            <h5>{{ $lead_data->child_education }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="disease_name">Disease Name</label>
                                            <h5>{{ $lead_data->disease_name }}</h5>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box" style="border-color:#9384ff;margin-bottom: 0">
                        <div class="box-header with-border">
                            <h3 class="box-title">Remarks</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12"><h5 style="font-weight: 300px;"> {{ $lead_data->remarks }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>


                    @if(!empty($lead_stage_data))
                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Stage Update History</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Category</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">Project</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">Sales Agent</th>
                                        <th scope="col">Stage Before</th>
                                        <th scope="col">Stage After</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_stage_data as $stage_data)
                                    <tr>
                                        <td scope="row">{{ $stage_data->category }}</td>
                                        <td scope="row">{{ $stage_data->area_name }}</td>
                                        <td scope="row">{{ $stage_data->project_name }}</td>
                                        <td scope="row">{{ $stage_data->size_name }}</td>
                                        <td scope="row">{{ $stage_data->sales_agent }}</td>
                                        <td scope="row">{{ $lead_stage_arr[$stage_data->lead_stage_before_update] }}</td>
                                        <td scope="row">{{ $lead_stage_arr[$stage_data->lead_stage_after_update] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                </div>
                <div class="tab-pane active table-responsive" id="lead_followup_history">
                    @if(!empty($lead_followup_data))
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Followup Note</th>
                                <th scope="col">Next Followup</th>
                                <th scope="col">Visit / Meeting</th>                                
                                <th scope="col">Visit Note</th>
                                <th scope="col">Before Stage</th>
                                <th scope="col">After Stage</th>
                                <th scope="col">FollowUp By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lead_followup_data as $followup)
                            <tr>
                                <td scope="row">{{ date("d/m/Y", strtotime($followup->lead_followup_datetime)) }}</td>

                                <td scope="row" style="width: 200px;">{{ $followup->followup_Note }}</td>
                                <td scope="row">{{ ($followup->Next_FollowUp_date != '1970-01-01')?date("d/m/Y", strtotime($followup->Next_FollowUp_date)):"" }}</td>
                                <td> 
                                    <div><strong>{{ isset($meeting_status[$followup->meeting_status])? $meeting_status[$followup->meeting_status] : "N/A"  }}</strong></div>
                                    <div>{{ (!empty($followup->meeting_status))? date("d/m/Y",strtotime($followup->meeting_date)) : "" }}</div>
                                    <div>{{ (!empty($followup->meeting_status))? date("H:i a",strtotime($followup->meeting_time)) : "" }}</div>
                                </td>                                
                                <td scope="row" style="width: 200px;">{{ $followup->next_followup_Note }}</td>
                                <td scope="row">{{ $lead_stage_arr[$followup->lead_stage_before_followup] }}</td>
                                <td scope="row">{{ $lead_stage_arr[$followup->lead_stage_after_followup] }}</td>
                                <td scope="row">{{ $followup->user_fullname }}</td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-danger text-center"><h5>No Follow up data found</h5></div>
                    @endif
                </div>
                <div class="tab-pane  table-responsive" id="lead_transfer_history">
                    @if(!empty($lead_transfer_data))
                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Category</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">Project</th>
                                        <th scope="col">Size</th>
                                        <th scope="col">From Agent</th>
                                        <th scope="col">To Agent</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_transfer_data as $trans_hist)
                                    <tr>
                                        <td scope="row">{{ $trans_hist->category }}</td>
                                        <td scope="row">{{ $trans_hist->area_name }}</td>
                                        <td scope="row">{{ $trans_hist->project_name }}</td>
                                        <td scope="row">{{ $trans_hist->size_name }}</td>
                                        <td scope="row">{{ $trans_hist->from_sales_agent }}</td>
                                        <td scope="row">{{ $trans_hist->to_sales_agent }}</td>
                                        <td scope="row">
                                            @if( $trans_hist->transfer_to_sales_agent_flag==1)
                                            Approved
                                            @endif
                                            @if($trans_hist->is_rejected==1)
                                            <span class="btn btn-danger btn-xs">Rejected</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-danger text-center"><h5>No Transfer data found</h5></div>
                    @endif
                </div>
                <div class="tab-pane  table-responsive" id="lead_history">
                    @if(!empty($lead_history))
                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Lead History</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Edit Date</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Phone 1</th>
                                        <th scope="col">Phone 2</th>
                                        <th scope="col">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_history as $lead_hist)
                                    <tr>
                                        <td scope="row">{{ date("d/m/Y", strtotime($lead_hist->created_at)) }}</td>
                                        <td scope="row">{{ $lead_hist->customer_firstname}} {{$lead_hist->customer_lastname }}</td>
                                        <td scope="row">{{ $lead_hist->phone1 }}</td>
                                        <td scope="row">{{ $lead_hist->phone2 }}</td>
                                        <td scope="row">{{ $lead_hist->email_id }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Permanent Information</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th scope="col">Holding No</th>
                                        <th scope="col">Road No</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Thana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_history as $lead_hist)
                                    <tr>
                                        <td scope="row">{{ $lead_hist->per_holding_no }}</td>
                                        <td scope="row">{{ $lead_hist->per_road_no }}</td>
                                        <td scope="row">{{ (isset($area[$lead_hist->per_area]))?$area[$lead_hist->per_area]:" " }}</td>
                                        <td scope="row">{{ (isset($district[$lead_hist->per_district]))? $district[$lead_hist->per_district]:"" }}</td>
                                        <td scope="row">{{ (isset($thana[$lead_hist->per_thana]))? $thana[$lead_hist->per_thana]: " " }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Present Information</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th scope="col">Holding No</th>
                                        <th scope="col">Road No</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Thana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_history as $lead_hist)
                                    <tr>
                                        <td scope="row">{{ $lead_hist->pre_holding_no }}</td>
                                        <td scope="row">{{ $lead_hist->pre_road_no }}</td>
                                        <td scope="row">{{ (isset($area[$lead_hist->pre_area]))?$area[$lead_hist->pre_area]:" " }}</td>
                                        <td scope="row">{{ (isset($district[$lead_hist->pre_district]))?$district[$lead_hist->pre_district]:" " }}</td>
                                        <td scope="row">{{ (isset($thana[$lead_hist->pre_thana]))?$thana[$lead_hist->pre_thana]: " " }}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Office Information</h3>
                        </div>
                        <div class="box-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>

                                        <th scope="col">Holding No</th>
                                        <th scope="col">Road No</th>
                                        <th scope="col">Area</th>
                                        <th scope="col">District</th>
                                        <th scope="col">Thana</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_history as $lead_hist)
                                    <tr>
                                        <td scope="row">{{ $lead_hist->office_holding_no }}</td>
                                        <td scope="row">{{ $lead_hist->office_road_no }}</td>
                                        <td scope="row">{{ (isset($area[$lead_hist->office_area]))?$area[$lead_hist->office_area]: " " }}</td>
                                        <td scope="row">{{ ( isset($district[$lead_hist->office_district]) )? $district[$lead_hist->office_district]: " " }}</td>
                                        <td scope="row">{{ (isset($thana[$lead_hist->office_thana]))? $thana[$lead_hist->office_thana]:" " }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="text-danger text-center"><h5>No Change History found</h5></div>
                    @endif
                </div>

                <div class="tab-pane  table-responsive" id="lead_kyc_history">
                    @if(!empty($lead_kyc_info_history))
                    <div class="box" style="border-color:#9384ff;">
                        <div class="box-header with-border">
                            <h3 class="box-title">Lead History</h3>
                        </div>
                        <div class="box-body "> 
                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Edit Date</th>
                                        <th scope="col">Client1 DOB</th>
                                        <th scope="col">Client2 DOB</th>
                                        <th scope="col">Spouse Name</th>
                                        <th scope="col">Spouse DOB</th>
                                        <th scope="col">Marriage Anniversary</th>
                                        <th scope="col">1st Children Name</th>                                    
                                        <th scope="col">1st Children DOB</th>                                        
                                        <th scope="col">2nd Children Name</th>                                      
                                        <th scope="col">2nd Children DOB</th>
                                        <th scope="col">3rd Children Name</th>                                  
                                        <th scope="col">3rd Children DOB</th>
                                        <th scope="col">Child Education</th>
                                        <th scope="col">Food Habit</th>
                                        <th scope="col">Political Opinion</th>
                                        <th scope="col">Car Preference</th>
                                        <th scope="col">Color Preference</th>
                                        <th scope="col">Hobby</th>
                                        <th scope="col">Traveling History</th>
                                        <th scope="col">Member of Club</th>
                                        <th scope="col">Disease Name</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lead_kyc_info_history as $lead_kyc_hist)
                                    <tr>
                                        <td scope="row">{{ date("d/m/Y", strtotime($lead_kyc_hist->created_at)) }}</td>
                                        <td scope="row">{{ ($lead_kyc_hist->Customer_dateofbirth!='1970-01-01')?date("d/m/Y", strtotime($lead_kyc_hist->Customer_dateofbirth)):'' }}</td>
                                        <td scope="row">{{ (($lead_kyc_hist->Customer_dateofbirth2!='1970-01-01') || ($lead_kyc_hist->Customer_dateofbirth2!='0000-01-01'))?date("d/m/Y", strtotime($lead_kyc_hist->Customer_dateofbirth2)):'' }}</td>
                                        <td scope="row">{{ $lead_kyc_hist->customer_wife_name }}</td>
                                        <td scope="row">{{ (($lead_kyc_hist->customer_wife_dataofbirth!='1970-01-01') || ($lead_kyc_hist->customer_wife_dataofbirth!='0000-01-01'))?date("d/m/Y", strtotime($lead_kyc_hist->customer_wife_dataofbirth)):'' }}</td>
                                        <td scope="row">{{ (($lead_kyc_hist->Marriage_anniversary!='1970-01-01')|| ($lead_kyc_hist->Marriage_anniversary!='0000-01-01'))?date("d/m/Y", strtotime($lead_kyc_hist->Marriage_anniversary)):'' }}</td>
                                        <td scope="row">{{ $lead_kyc_hist->children_name1  }} </td>
                                        <td scope="row">{{ (($lead_kyc_hist->children_dateofbirth1!='1970-01-01') || ($lead_kyc_hist->children_dateofbirth1!='0000-01-01'))?date("d/m/Y", strtotime($lead_kyc_hist->children_dateofbirth1)):'' }}</td>
                                        <td scope="row">{{ $lead_kyc_hist->children_name2  }} </td>
                                        <td scope="row">{{ ($lead_kyc_hist->children_dateofbirth2!='1970-01-01') || ($lead_kyc_hist->children_dateofbirth2!='0000-01-01')?date("d/m/Y", strtotime($lead_kyc_hist->children_dateofbirth2)):'' }}</td>
                                        <td scope="row">{{ $lead_kyc_hist->children_name3  }} </td>
                                        <td scope="row">{{ ($lead_kyc_hist->children_dateofbirth3!='1970-01-01')|| ($lead_kyc_hist->children_dateofbirth3!='0000-01-01')?date("d/m/Y", strtotime($lead_kyc_hist->children_dateofbirth3)):'' }}</td>
                                        <td scope="row"> {{ $lead_kyc_hist->child_education  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->food_habit  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->political_opinion  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->car_preference  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->color_preference  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->hobby  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->traveling_history  }} </td>
                                        <td scope="row"> {{ $lead_kyc_hist->member_of_club  }} </td><td scope="row"> {{ $lead_kyc_hist->disease_name  }} </td>




                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-danger text-center"><h5>No Change History found</h5></div>
                    @endif
                </div>





            </div>
        </div>
    </div>
</div>
