@php
$group_id = Session::get('user.ses_role_lookup_pk_no');
@endphp

<link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">



<form id="frmLead" action="{{ !isset($lead_data)?route('lead.store') : route('lead.update',$lead_data->lead_pk_no) }}"
  method="{{ !isset($lead_data)?'post' : 'patch' }}">
  <div class="box box-success">
    <div class="box-header with-border ">
        <h3 class="box-title">Customer Information</h3>
        @if($group_id == 74)
        <a href="{{ route('import_csv') }}" class="btn bg-green btn-sm pull-right">Import CSV</a>
        @endif
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="txt_lead_id">Lead ID </label>
                    <input type="text" class="form-control" id="txt_lead_id" readonly="readonly" name="txt_lead_id"
                    value="{{ $lead_data->lead_id }}" title="" placeholder="USERGROPCODE+YYMM+99999"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="txt_lead_date">Date<span class="text-danger"> *</span></label>
                    <input type="text" class="form-control datepicker required" id="txt_lead_date"
                    name="txt_lead_date" value="<?php echo date('d-m-Y'); ?>" title="" readonly=""
                    placeholder="Entry Date"/>
                </div>
            </div>

            <div class="col-md-6">
                <label for="txt_cus_first_name">Client Name 1<span class="text-danger"> *</span></label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control required capitalize-text"
                            id="customer_first_name" name="customer_first_name"
                            value="{{ $lead_data->customer_firstname }}" title=""
                            placeholder="Customer First Name"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control required capitalize-text" id="customer_last_name"
                            name="customer_last_name" value="{{ $lead_data->customer_lastname }}" title=""
                            placeholder="Customer Last Name"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label for="txt_cus_first_name">Client Name 2</label>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control capitalize-text" id="customer_firstname2"
                            name="customer_firstname2" value="{{ $lead_data->customer_firstname2 }}" title=""
                            placeholder="Customer First Name"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" class="form-control capitalize-text" id="customer_lastname2"
                            name="customer_lastname2" value="{{ $lead_data->customer_lastname2 }}" title=""
                            placeholder="Customer Last Name"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <div><label for="customer_phone1">Phone Number 1 <span
                                class="text-danger"> *</span></label></div>
                                <div class="col-xs-4" style="padding-left: 0;">
                                    <select class="form-control select2" name="country_code1" aria-hidden="true">
                                        <option selected="selected" value="0">Country Code</option>
                                        @if(!empty($countries))
                                        @foreach ($countries as $country)
                                        <option
                                        value="{{ $country->phonecode }}" {{ ($country->phonecode== $lead_data->phone1_code)? 'selected':'' }} >
                                        {{ $country->name ." (". $country->phonecode.")" }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-xs-8">
                                <input type="number" class="form-control number-only required check_phone_no"
                                id="customer_phone1" value="{{ $lead_data->phone1 }}" data-phn-no="{{ $lead_data->phone1 }}" name="customer_phone1"
                                maxlength="10" placeholder="Phone Number 1"/>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div><label for="customer_phone2">Phone Number 2</label></div>
                            <div class="col-xs-4" style="padding-left: 0;">
                                <select class="form-control select2" name="country_code2" aria-hidden="true">
                                    <option selected="selected" value="0">Country Code</option>
                                    @if(!empty($countries))
                                    @foreach ($countries as $key => $country)
                                    <option
                                    value="{{ $country->phonecode }}" {{ ($country->phonecode== $lead_data->phone2_code)? 'selected':'' }}>
                                    {{ $country->name ." (". $country->phonecode.")" }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xs-8">
                            <input type="number" class="form-control number-only check_phone_no"
                            id="customer_phone2" value="{{ $lead_data->phone2 }}" data-phn-no="{{ $lead_data->phone2 }}" name="customer_phone2"
                            maxlength="10" placeholder="Phone Number 2"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="txt_cus_email">Client Email<span class="text-danger"> *</span> </label>
                <input type="email" class="form-control required email-only" id="customer_email"
                name="customer_email" value="{{ $lead_data->email_id }}" title="Customer Email"
                placeholder="e.g. username@bti.com"/>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Occupation </label>
                <select class="form-control select2" name="cmb_ocupation" style="width: 100%;"
                aria-hidden="true">
                <option value="0">Select</option>
                @if(!empty($ocupations))
                @foreach ($ocupations as $key => $ocupation)
                <option
                value="{{ $key }}" {{ ($key == $lead_data->occupation_pk_no ? "selected" : " ") }}>
                {{ $ocupation }}
            </option>
            @endforeach
            @endif
        </select>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="txt_organization">Organization </label>
        <input type="email" class="form-control" id="txt_organization" name="txt_organization"
        value="{{ $lead_data->organization_pk_no }}" title="" placeholder="Organization"/>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label>Designation </label>
        <input type="text" class="form-control" name="designation"
        value="{{ $lead_data->cust_designation }}">
    </div>
</div>

</div>
</div>
<!-- /.box-body -->
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Address</h3>
    </div>
    <div class="box-body">

        <div class="form-row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="pre_holding_no">Pre. Address</label>
                    <input type="text" class="form-control" id="pre_holding_no" name="pre_holding_no"
                    value="{{ $lead_data->pre_holding_no }}" title="" placeholder="Housing/Plot No"/>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <label for="pre_road_no"></label>
                    <input type="text" class="form-control" id="pre_road_no" name="pre_road_no"
                    value="{{ $lead_data->pre_road_no }}" title="" placeholder="Road No"/>
                </div>
            </div>


            <div class="col-md-2">
                <div class="form-group">
                    <label for="pre_area"> </label>
                    <select class="form-control" id="pre_area" name="pre_area" title="Select Area">
                        <option value="0">Select Area</option>
                        @if(!empty($area))
                        @foreach ($area as $key => $value)
                        <option value="{{ $key }}" {{ ($lead_data->pre_area = $key)? 'selected' :'' }} >
                            {{ $value }}
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label for="pre_district"> </label>
                    <select class="form-control select2" id="pre_district" name="pre_district"
                    placeholder="Organization">
                    <option value="0">Select District</option>
                    @if(!empty($district))
                    @foreach ($district as $key => $value)
                    <option
                    value="{{ $key }}" {{ ($lead_data->pre_district = $key)? 'selected' :'' }}>
                    {{ $value }}
                </option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="pre_thana"> </label>
            <select class="form-control select2" id="pre_thana" name="pre_thana" title="">
                <option value="0">Select Thana</option>
                @if(!empty($thana))
                @foreach ($thana as $key => $value)
                <option value="{{ $key }}" {{ ($lead_data->pre_thana = $key)? 'selected' :'' }}>
                    {{ $value }}
                </option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="txt_size_no"></label>
            <input type="text" class="form-control" id="txt_size_no" name="txt_size_no" value="{{ $lead_data->pre_size }} "
            title="" placeholder="Current Apartment Size"/>
        </div>
    </div>                    
</div>

<div class="form-row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="per_holding_no">Per. Address</label>
            <input type="text" class="form-control" id="per_holding_no" name="per_holding_no"
            value="{{ $lead_data->per_holding_no }}" title="" placeholder="Housing/Plot No"/>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <label for="per_road_no"> </label>
            <input type="text" class="form-control" id="per_road_no" name="per_road_no"
            value="{{ $lead_data->per_road_no }}" title="" placeholder="Road No"/>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <label for="per_area"> </label>
            <select class="form-control" id="per_area" name="per_area" title="">
                <option value="0">Select Area</option>
                @if(!empty($area))
                @foreach ($area as $key => $value)
                <option value="{{ $key }}" {{ ($lead_data->per_area = $key)? 'selected' :'' }}>
                    {{ $value }}
                </option>
                @endforeach
                @endif
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="per_district"> </label>
            <select class="form-control select2" id="per_district" name="per_district" title="District">
                <option value="0">Select District</option>
                @if(!empty($district))
                @foreach ($district as $key => $value)
                <option
                value="{{ $key }}" {{ ($lead_data->per_district = $key)? 'selected' :'' }}>
                {{ $value }}
            </option>
            @endforeach
            @endif
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="per_thana"> </label>
        <select class="form-control select2" id="per_thana" name="per_thana" title="Thana">
            <option value="0">Select Thana</option>
            @if(!empty($thana))
            @foreach ($thana as $key => $value)
            <option value="{{ $key }}" {{ ($lead_data->per_thana = $key)? 'selected' :'' }}>
                {{ $value }}
            </option>
            @endforeach
            @endif
        </select>
    </div>
</div>
</div>

<div class="form-row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="office_holding_no">Office Address </label>
            <input type="text" class="form-control" id="office_holding_no" name="office_holding_no"
            value="{{ $lead_data->office_holding_no }}" title="" placeholder="Housing/Plot No"/>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <label for="office_road_no"> </label>
            <input type="text" class="form-control" id="office_road_no" name="office_road_no"
            value="{{ $lead_data->office_road_no }}" title="" placeholder="Road No"/>
        </div>
    </div>


    <div class="col-md-2">
        <div class="form-group">
            <label for="office_area"> </label>
            <select class="form-control" id="office_area" name="office_area" title=""
            placeholder="Organization">
            <option value="0">Select Area</option>
            @if(!empty($area))
            @foreach ($area as $key => $value)
            <option
            value="{{ $key }}" {{ ($key == $lead_data->office_area )? 'selected' : '' }} >
            {{ $value }}
        </option>
        @endforeach
        @endif
    </select>
</div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="office_district"> </label>
        <select class="form-control select2" id="office_district" name="office_district" title="">
            <option value="0">Select District</option>
            @if(!empty($district))
            @foreach ($district as $key => $value)
            <option
            value="{{ $key }}" {{ ($key == $lead_data->office_district )? 'selected' : '' }}>
            {{ $value }}
        </option>
        @endforeach
        @endif
    </select>
</div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label for="office_thana"> </label>
        <select class="form-control select2" id="office_thana" name="office_thana" title="">
            <option value="0">Select Thana</option>
            @if(!empty($thana))
            @foreach ($thana as $key => $value)
            <option
            value="{{ $key }}" {{ ($key == $lead_data->office_thana )? 'selected' : '' }}>
            {{ $value }}
        </option>
        @endforeach
        @endif
    </select>
</div>
</div>
</div>               
</div>
</div>
<div class="form-row">
    <div class="col-md-12">
        <label for="remarks">Remarks</label>
        <div class="form-group">
            <textarea class="form-control" style="height: 100px !important;" id="remarks" name="remarks"
            placeholder="Enter Remarks">{{ $lead_data->remarks  }}</textarea>
        </div>
    </div>
</div>




<div id="more_details" class="">
    <div class="form-row" id="appendPlace">
        <div class="col-md-12">
            <div class="box box-success">
                <dir class="box-header">
                    <h3 class="box-title">KYC Information</h3>
                </dir>
                <div class="box-body">
                    
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cust_dob">Client1 DOB</label>
                                        <input type="text" class="form-control datepicker" id="txt_cust_dob"
                                        name="txt_cust_dob" title="Client1 Date of Birth" value="{{ isset($lead_data->Customer_dateofbirth)? date('d-m-Y',strtotime($lead_data->Customer_dateofbirth)) : ' '}}" readonly="readonly"
                                        placeholder="dd-mm-yyyy"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="txt_cust_dob2">Client2 DOB</label>
                                        <input type="text" class="form-control datepicker" id="txt_cust_dob2"
                                        name="txt_cust_dob2" title="Client2 Date of Birth" value="{{ isset($lead_data->Customer_dateofbirth2)? date('d-m-Y',strtotime($lead_data->Customer_dateofbirth2)): ' ' }}" readonly="readonly"
                                        placeholder="dd-mm-yyyy"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="wife_name">Spouse Name</label>
                                <input type="text" class="form-control" id="txt_wife_name" name="txt_wife_name" value="{{ $lead_data->customer_wife_name }}"
                                title="Source Title" placeholder="Spouse Name"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="wife_dob">Spouse DOB</label>
                                <input type="text" class="form-control datepicker" id="txt_wife_dob" name="txt_wife_dob" value="{{ isset($lead_data->customer_wife_dataofbirth)? date('d-m-Y',strtotime($lead_data->customer_wife_dataofbirth)): ' ' }}"
                                title="" readonly="readonly" placeholder="dd-mm-yyyy"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="marriage_anniversary">Marriage Anniversary</label>
                                <input type="text" class="form-control datepicker" id="txt_marriage_anniversary"
                                name="txt_marriage_anniversary" title="" readonly="readonly" value="{{ isset($lead_data->Marriage_anniversary)? date('d-m-Y',strtotime($lead_data->Marriage_anniversary)): ' ' }}"
                                placeholder="dd-mm-yyyy"/>
                            </div>
                        </div>

                        <br clear="all"/><br/>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_name_1">1st Children Name</label>
                                <input type="text" class="form-control" id="txt_child_name_1" name="txt_child_name_1"
                                value="{{ $lead_data->children_name1 }}" title="Source Title" placeholder="First Children Name"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_dob_1">1st Children DOB</label>
                                <input type="text" class="form-control datepicker" id="txt_child_dob_1"
                                name="txt_child_dob_1" title="" value="{{ isset($lead_data->children_dateofbirth1)? date('d-m-Y',strtotime($lead_data->children_dateofbirth1)): ' ' }}" readonly="readonly" placeholder="dd-mm-yyyy"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_name_2">2nd Children Name</label>

                                <input type="text" class="form-control" id="txt_child_name_2" name="txt_child_name_2"
                                value="{{ $lead_data->children_name2 }}" title="Source Title" placeholder="Second Children Name"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_dob_2">2nd Children DOB</label>
                                <input type="text" class="form-control datepicker" id="txt_child_dob_2"
                                name="txt_child_dob_2" title="" value="{{ isset($lead_data->children_dateofbirth2)? date('d-m-Y',strtotime($lead_data->children_dateofbirth2)): ' ' }}" readonly="readonly" placeholder="dd-mm-yyyy"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_name_3">3rd Children Name</label>
                                <input type="text" class="form-control" id="txt_child_name_3" name="txt_child_name_3" value="{{ $lead_data->children_name3 }}" title="Source Title" placeholder="Third Children Name"/>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="txt_child_dob_3">3rd Children DOB</label>
                                <input type="text" class="form-control datepicker" id="txt_child_dob_3"
                                name="txt_child_dob_3" title="" readonly="readonly" value="{{ isset($lead_data->children_dateofbirth3)? date('d-m-Y',strtotime($lead_data->children_dateofbirth3)): ' ' }}" placeholder="dd-mm-yyyy"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="child_education">Child Education</label>
                                <input type="text" id="child_education" value="{{ $lead_data->child_education }}" name="child_education" class="form-control "
                                placeholder="Child Education"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="food_habit">Food Habit</label>
                                <input type="text" class="form-control " name="food_habit" value="{{ $lead_data->food_habit}}" id="food_habit"
                                placeholder="Food Habit"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="political_opinion">Political Opinion</label>
                                <input type="text" class="form-control" name="political_opinion" id="political_opinion" value="{{ $lead_data->political_opinion}}"
                                placeholder="Political Opinion"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="car_pre">Car Preference</label>
                                <input type="text" name="car_pre" id="car_pre" class="form-control " value="{{ $lead_data->car_preference}}"
                                placeholder="Car Preference"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="color_preference">Color Preference</label>
                                <input type="text" class="form-control" id="color_preference" name="color_preference" value="{{ $lead_data->color_preference}}"
                                placeholder="Color Preference"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="hobby">Hobby</label>
                                <input type="text" class="form-control" id="hobby" name="hobby"
                                value="{{ $lead_data->hobby}}" placeholder="Color Preference"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="traveling_history">Traveling History</label>
                                <input type="text" id="traveling_history" name="traveling_history" class="form-control " value="{{ $lead_data->traveling_history}}"
                                placeholder="Traveling History"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="memberofclub">Member of Club</label>
                                <input type="text" id="memberofclub" name="memberofclub" class="form-control "
                                value="{{ $lead_data->member_of_club}}" placeholder="Member of Club"/>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="disease_name">Disease Name</label>
                                <input type="text" class="form-control" name="disease_name" id="disease_name"
                                value="{{ $lead_data->disease_name}}" placeholder="Disease"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<div class="row">
    <div class="col-md-12">
        <div class="col-md-12 pb-15">
            <button type="submit" class="btn bg-green btn-sm btnSaveUpdate">Update</button>
            <button class="btn bg-red btn-sm" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
</form>

<script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

<script>
    $(document).on("keyup",".check_phone_no",function() {
        var phone_no = $(this).val();
        var number_length = phone_no.length;

        if(phone_no.charAt(0) == '0')
        {
            $(this).val('');
        }
        if(number_length>11){
            alert("Maximum Number limit is Eleven digits");
            $(this).val('');
        }else{

        }
    });

    
    $(document).on("focusout", ".check_phone_no", function(e){
        var phone_no = $(this).val();
        var prev_phone_no = $(this).attr("data-phn-no");
        if(phone_no != "" && (prev_phone_no != phone_no))
        {
            $.ajax({
                data: { phone_no:phone_no },
                url: "{{ route('check_if_phone_no_exist') }}",
                type: "post",
                beforeSend:function(){

                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if ( data !="" ) {
                     if(data.sales_agent_pk != data.user_id){
                        if(data.agent_name != "")
                        {
                            alert('Lead found with this Mobile No.\n\nLead ID: ' + data.lead_id + '\nCustomer Name: '+data.customer_name+'\n\nSales Person Name: '+data.agent_name+'\nPhone: '+data.agent_phone);
                        }
                        else
                        {
                            alert('Lead found with this Mobile No.\n\nLead ID: ' + data.lead_id + '\nCustomer Name: '+data.customer_name+'\n\nSales Person Information:\n\nThis Lead is not distributed yet.');
                        }
                        location.reload();
                    }

                }
            }

        });
        }
    });
</script>