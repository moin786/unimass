<form id="frmLeadFollowup" action="{{ route('store_stage_update') }}" method="post">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="current_stage">Current Stage :</label>
                        <input type="text" class="form-control" id="current_stage" name="current_stage" value="{{ isset($lead_data)? $lead_stage_arr[$lead_data->lead_current_stage]:'' }}" title="" readonly="readonly" placeholder="Current Stage"/>
                    </div>
                </div>
                @php
                if($lead_data->lead_current_stage == 1)
                {
                    $stages = [3,8,10,11];
                }
                else if(in_array($lead_data->lead_current_stage, [6,9]))
                {
                    $stages = [1,3];
                }
                else if($lead_data->lead_current_stage == 3)
                {
                    $stages = [4,5,6,9];
                }
                else if($lead_data->lead_current_stage == 4)
                {
                    $stages = [5,6,9];
                }
                else
                {
                    $stages = [4,10,11,12];
                }
                @endphp
                <div class="col-md-6">
                    <div class="form-group">
                        <label>New Stage<span class="text-danger">*</span> :</label>
                        <select class="form-control select2 select2-hidden-accessible required" name="new_stage" style="width: 100%;" aria-hidden="true">
                            <option value="">Select New Stage</option>
                            @if(!empty($lead_stage_arr))
                            @foreach ($lead_stage_arr as $key => $stage)
                            @if( in_array($key, $stages) )
                            <option value="{{ $key }}">{{ $stage }}</option>
                            @endif
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success btn-sm btnSaveUpdate" data-response-action="{{ route('load_followup_leads') }}" data-tab="1">Update Stage</button>
    </div>
</form>
