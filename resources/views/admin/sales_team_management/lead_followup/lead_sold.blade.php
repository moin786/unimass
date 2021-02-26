@php
$data = isset($lead_data) && !empty($lead_data) ? $lead_data : [];
$flatlist_pk = $data ? $data->flatlist_pk_no : '';
@endphp

<form id="frmLeadFollowup" action="{{ route('store_lead_sold') }}" method="post">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                @include('admin.sales_team_management.lead_followup.lead_follow_up_popup_elements')

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Stage :</label>
                        <input type="text" class="form-control" id="current_stage" name="current_stage"
                            value="{{ isset($lead_data) ? $lead_stage_arr[$lead_data->lead_current_stage] : '' }}"
                            title="" readonly="readonly" placeholder="Current Stage" />
                    </div>
                </div>
            </div>
            <div class="row" style="border-bottom: 1px solid #333; padding-bottom: 5px; padding-top: 10px;">
               
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">Installments</span>
                        <input type="text" name="installment" id="installment" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text">Amount</span>
                        <input type="text" name="amount" id="amount" class="form-control" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-text text-center">%</div>
                        <input type="text" name="percent_of_first_installment" id="percent_of_first_installment" class="form-control" placeholder="1st Installment">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <button type="button" class="btn btn-block bg-green schegenerate" style="margin-top:16px;">Generate</button>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="generated_schedule">
                    
                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="flat">Apartment <span class="text-danger"> *</span></label>
                <select class="form-control required" name="flat" style="width: 100%;" aria-hidden="true"
                    required="required">
                    <option selected="selected" value="">Select Apartment</option>
                    @if (!empty($flat_list))
                        @foreach ($flat_list as $flat)
                            <option value="{{ $flat->flatlist_pk_no }}"
                                {{ $flatlist_pk == $flat->flatlist_pk_no ? 'selected' : '' }}>{{ $flat->flat_name }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Apartment Cost <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required number-only calculate-total-sold text-right"
                            id="flat_cost" name="flat_cost" value="" title="" placeholder="Apartment Cost" />
                    </div>
                    <div class="col-md-6">
                        <label for="">Reserve Fund <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required number-only calculate-total-sold text-right"
                            id="flat_cost" name="reserve_money" value="" title="" placeholder="Reserve Fund" />
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="">Utility <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required number-only calculate-total-sold text-right"
                            id="utility" name="utility" value="" title="" placeholder="Utility Cost" />
                    </div>
                    <div class="col-md-6">

                        <label for="">Parking <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required number-only calculate-total-sold text-right"
                            id="parking" name="parking" value="" title="" placeholder="Parking Cost" />

                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="">Booking Money <span class="text-danger"> *</span></label>
                        <input type="text" class="form-control required number-only text-left" id="utility"
                            name="lead_sold_bookingmoney" value="" title="" placeholder="Booking Money" />
                    </div>
                    <div class="col-md-12">
                        <label for="">Agreement Status :</label>
                        <select class="form-control" name="lead_sold_agreement_status">
                            <option value="0">Select One</option>
                            <option value="Done">Done</option>
                            <option value="Not Done">Not Done</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for=""><strong>Date of Sale <span class="text-danger"> *</span></strong></label>
                <input type="text" class="form-control required datepicker" id="date_of_sold" name="date_of_sold"
                    value="<?php echo date('d-m-Y'); ?>" readonly="readonly" title=""
                    placeholder="Date of Sold" />
            </div>
            <div class="form-group">
                <label for=""><strong>Grand Total :</strong></label>
                <input type="text" class="form-control text-right" id="grand-total" name="grand-total" value=""
                    readonly="readonly" title="" placeholder="Grand Total" />
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-block bg-green btnSaveUpdate"
                    data-response-action="{{ route('load_followup_leads') }}">Update Lead</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
      
        $('.datepicker').datepicker();
    });
</script>
