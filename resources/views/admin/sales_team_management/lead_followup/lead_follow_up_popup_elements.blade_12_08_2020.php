<div class="col-md-6">
    <div class="form-group">
        <label for="lead_id">Lead ID :</label>
        <input type="text" class="form-control keep_me" id="lead_id" name="lead_id"
               value="{{ isset($lead_data)? $lead_data->lead_id:'' }}" title="" readonly="readonly" readonly="readonly"
               placeholder=""/>
        <input type="hidden" class="keep_me" name="lead_pk_no" value="{{ isset($lead_data)? $lead_data->lead_pk_no:'' }}" readonly="readonly"/>
        <input type="hidden" class="keep_me" name="leadlifecycle_id" value="{{ isset($lead_data)? $lead_data->leadlifecycle_pk_no:'' }}" readonly="readonly"/>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="customer">Customer :</label>
        <input type="text" class="form-control keep_me" id="customer" name="customer"
               value="{{ isset($lead_data)? $lead_data->customer_firstname .' '. $lead_data->customer_lastname:'' }}" readonly="readonly" placeholder="Customer"/>
        <input type="hidden" class="keep_me" name="leadlifecycle_id" value="{{ isset($lead_data)? $lead_data->leadlifecycle_pk_no:'' }}" readonly="readonly"/>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="lead_category">Category :</label>
        <input type="text" class="form-control keep_me" id="lead_category" name="lead_category" value="{{ isset($lead_data)? $lead_data->project_category_name:'' }}" title="" readonly="readonly" placeholder="Category"/>
        <input type="hidden" class="keep_me" name="lead_category_id" value="{{ isset($lead_data)? $lead_data->project_category_pk_no:'' }}"  readonly="readonly"/>
        <input type="hidden" class="keep_me" name="lead_project_id" value="{{ isset($lead_data)? $lead_data->Project_pk_no:'' }}"  readonly="readonly"/>
        <input type="hidden" class="keep_me" name="lead_size_id" value="{{ isset($lead_data)? $lead_data->project_size_pk_no:'' }}"  readonly="readonly"/>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="area">Area :</label>
        <input type="text" class="form-control keep_me" id="area" name="area"
               value="{{ isset($lead_data)? $lead_data->project_area:'' }}" title="" readonly="readonly"
               placeholder="Area"/>
        <input type="hidden" class="keep_me" name="lead_area_id" value="{{ isset($lead_data)? $lead_data->project_area_pk_no:'' }}"
               readonly="readonly"/>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="sales_agent">Sales Agent :</label>
        <input type="text" class="form-control keep_me" id="sales_agent" name="sales_agent"
               value="{{ isset($lead_data)? $lead_data->lead_sales_agent_name:'' }}" title="" readonly="readonly"
               placeholder="Sales Agent"/>
        <input type="hidden" class="keep_me" name="sales_agent_id" value="{{ isset($lead_data)? $lead_data->lead_sales_agent_pk_no:'' }}"
               readonly="readonly"/>
    </div>
</div>