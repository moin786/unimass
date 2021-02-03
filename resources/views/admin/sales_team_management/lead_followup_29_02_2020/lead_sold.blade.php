<form id="frmLeadFollowup" action="{{ route('store_lead_sold') }}" method="post">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Stage :</label>
                        <input type="text" class="form-control" id="current_stage" name="current_stage" value="{{ isset($lead_data)? $lead_stage_arr[$lead_data->lead_current_stage]:'' }}" title="" readonly="readonly" placeholder="Current Stage"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="flat">Flat :</label>
                <select class="form-control required" name="flat" style="width: 100%;" aria-hidden="true" required="required">
                    <option selected="selected" value="">Select Flat</option>
                    @if(!empty($flat_list))
                    @foreach($flat_list as $flat)
                    <option value="{{ $flat->flatlist_pk_no }}">{{ $flat->flat_name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="">Flat Cost :</label>
                <input type="text" class="form-control required number-only calculate-total-sold text-right" id="flat_cost" name="flat_cost" value="" title="" placeholder="Flat Cost"/>
            </div>

            <div class="form-group">
                <label for="">Utility :</label>
                <input type="text" class="form-control required number-only calculate-total-sold text-right" id="utility" name="utility" value="" title="" placeholder="Utility Cost"/>
            </div>
            <div class="form-group">
                <label for="">Parking :</label>
                <input type="text" class="form-control required number-only calculate-total-sold text-right" id="parking" name="parking" value="" title="" placeholder="Parking Cost"/>
            </div>
            <div class="form-group">
                <label for=""><strong>Date of Sold :</strong></label>
                <input type="text" class="form-control required datepicker" id="date_of_sold" name="date_of_sold" value="<?php echo date('d-m-Y'); ?>"
                readonly="readonly" title="" placeholder="Date of Sold"/>
            </div>
            <div class="form-group">
                <label for=""><strong>Grand Total :</strong></label>
                <input type="text" class="form-control text-right" id="grand-total"
                name="grand-total" value="" readonly="readonly" title=""
                placeholder="Grand Total"/>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block bg-green btnSaveUpdate" data-response-action="{{ route('load_followup_leads') }}">Update Lead</button>
            </div>
        </div>
    </div>
</form>
