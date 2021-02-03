@php
die();
@endphp
<div class="tab-pane table-responsive" id="auto_transfer">
    @if($is_ch==1)

    <div class="head_action"
    style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">

    <div class="box-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Category<span class="text-danger"> *</span></label>
                    <select class="form-control required" id="cmb_category" name="cmb_category" data-action="{{ route('load_area_project_size') }}" aria-hidden="true">
                        <option selected="selected" value="0">Select Category</option>
                        @if(!empty($project_cat))
                        @foreach ($project_cat as $key => $cat)
                        <option value="{{ $key }}">{{ $cat }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Area<span class="text-danger"> *</span></label>
                    <select class="form-control required" id="cmb_area" name="cmb_area" style="width: 100%;" aria-hidden="true">
                        <option selected="selected" value="">Select Area</option>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Project Name<span class="text-danger"> *</span></label>
                    <select class="form-control required" id="cmb_project_name" name="cmb_project_name" style="width: 100%;" aria-hidden="true">
                        <option selected="selected" value="">Select Project Name</option>
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Size<span class="text-danger"> *</span></label>
                    <select class="form-control required" id="cmb_size" name="cmb_size" style="width: 100%;" aria-hidden="true">
                        <option selected="selected" value="">Select Size</option>
                        @if(!empty($project_area))
                        @foreach ($project_area as $key => $size)
                        <option value="{{ $key }}">{{ $size }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Transfer To<span class="text-danger"> *</span></label>
                    <select class="form-control required" id="cmbTransferTo" name="cmbTransferTo" style="width: 100%;" aria-hidden="true">
                        <option selected="selected" value="">Select Sales Agent</option>

                        @if(!empty($cluster_head_list))
                        @foreach($cluster_head_list as $cluster_head)
                        <option Value="{{ $cluster_head->user_pk_no }}" data-agent-category="{{ $cluster_head->lookup_pk_no }}">
                            {{ $cluster_head->user_fullname }} ({{ $cluster_head->lookup_name }})
                        </option>
                        @endforeach
                        @endif
                        @if(!empty($sales_agent))
                        @foreach($sales_agent as $key=>$agent)
                        <option Value="{{ $agent->user_pk_no }}" data-agent-category="{{ $agent->lookup_pk_no }}">
                            {{ $agent->user_fullname }} ({{ $agent->lookup_name }})
                        </option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
    </div>

</div>

@endif

<div class="box-body table-responsive">
    <table id="auto_transfer" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                @include('admin.components.lead_list_table_header')
                <th style=" min-width: 50px" class="text-center">Last Followup</th>                
                @if($is_ch==1)
                <th style=" min-width: 20px" class="text-center">
                    <a href="#" class="btn bg-blue btn-block btn-xs btn-transfer-accept-request" data-response-action="{{ route('load_transfer_leads') }}">Transfer </a>
                </th>
                @endif
                <th style=" min-width: 20px" class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($lead_transfer_list))
            @foreach($lead_transfer_list as $row)
            @php
            $checkingDate= '';
            if ($row->last_lead_followup_datetime != null ){
            $checkingDate =$row->last_lead_followup_datetime;
        }else{
        $checkingDate = date("Y-m-d");
    }

    $date_def = date_diff( date_create($row->created_at),date_create($checkingDate));
    
    @endphp
    @if($date_def->format("%a")>$days)
    <tr>
        @include('admin.components.lead_list_table')
        <td class="text-center">{{ $row->last_lead_followup_datetime }}</td>
        @if($is_ch==1)
        <td class="text-center">
            <input type="checkbox"
            data-id="{{ $row->lead_pk_no }}"
            data-name="{{ $row->lead_id }}"
            data-category="{{ $row->project_category_pk_no }}"
            data-agent="{{ $row->lead_sales_agent_pk_no }}">
        </td>
        @endif
        <td class="text-center">
            <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
            data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}">
            <i class="fa fa-eye"></i></span>
        </td>
    </tr>
    @endif
    @endforeach
    @endif
</tbody>
</table>
</div>
</div>