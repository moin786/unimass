<div class="box">
    <div class="box-header">
        <h3 class="box-title">Lead Information</h3>
    </div>
    <div class="box-body no-padding">
        <table class="table">
            <tbody>
                <tr>
                    <th>Lead ID:</th>
                    <td>
                        {{ isset($lead_data) ? $lead_data->lead_id : '' }}
                        <input type="hidden" class="keep_me" name="lead_pk_no"
                            value="{{ isset($lead_data) ? $lead_data->lead_pk_no : '' }}" readonly="readonly" />
                        <input type="hidden" class="keep_me" name="leadlifecycle_id"
                            value="{{ isset($lead_data) ? $lead_data->leadlifecycle_pk_no : '' }}"
                            readonly="readonly" />
                    </td>
                    <th>Lead Date: </th>
                    <td>{{ isset($lead_data) ? date('d/m/Y', strtotime($lead_data->created_at)) : '' }}</td>
                </tr>
                <tr>
                    <th>Client 1 :</th>
                    <td>
                        {{ isset($lead_data) ? $lead_data->customer_firstname . ' ' . $lead_data->customer_lastname : '' }}
                    </td>
                    <th>Client 2 : </th>
                    <td>
                        {{ isset($lead_data) ? $lead_data->customer_firstname2 . ' ' . $lead_data->customer_lastname2 : '' }}
                    </td>
                </tr>



                @php
                    $masking_number = substr($lead_data->phone1, 0, 7);
                    $masking_number1 = substr($lead_data->phone2, 0, 7);
                @endphp


                <tr>
                    <th>Mobile 1: </th>
                    @if ($ses_user_id == $lead_data->created_by || $ses_user_id == $lead_data->lead_sales_agent_pk_no)
                        <td>{{ isset($lead_data) ? $lead_data->phone1_code . '' . $lead_data->phone1 : '' }}</td>
                    @else
                        <td>{{ $masking_number }}****</td>
                    @endif
                    <th>Mobile 2:</th>
                    @if ($ses_user_id == $lead_data->created_by || $ses_user_id == $lead_data->lead_sales_agent_pk_no)
                        <td>{{ isset($lead_data) ? $lead_data->phone2_code . '' . $lead_data->phone2 : '' }}</td>
                    @else
                        <td>{{ !empty($masking_number1) ? $masking_number1 . '****' : ' ' }}</td>
                    @endif
                </tr>
                <tr>
                    <th>Email: </th>
                    <td>{{ isset($lead_data) ? $lead_data->email_id : '' }}</td>
                    <th>Size:</th>
                    <td>
                        {{ isset($lead_data) ? $lead_data->project_size : '' }}
                    </td>
                    <input type="hidden" class="keep_me" name="lead_category_id"
                        value="{{ isset($lead_data) ? $lead_data->project_category_pk_no : '' }}" readonly="readonly" />
                    <input type="hidden" class="keep_me" name="lead_project_id"
                        value="{{ isset($lead_data) ? $lead_data->Project_pk_no : '' }}" readonly="readonly" />
                    <input type="hidden" class="keep_me" name="lead_size_id"
                        value="{{ isset($lead_data) ? $lead_data->project_size_pk_no : '' }}" readonly="readonly" />

                </tr>
                <tr>
                    <th>Project: </th>
                    <td>{{ isset($lead_data) ? $lead_data->project_name : '' }}</td>
                    <th>Area:</th>
                    <td>{{ isset($lead_data) ? $lead_data->project_area : '' }}</td>
                </tr>
                <tr>
                    <th>Sales Agent: </th>
                    <td>
                        {{ isset($lead_data) ? $lead_data->lead_sales_agent_name : '' }}
                        <input type="hidden" class="keep_me" name="sales_agent_id"
                            value="{{ isset($lead_data) ? $lead_data->lead_sales_agent_pk_no : '' }}"
                            readonly="readonly" />
                    </td>
                    <th>Created by:</th>
                    <td>{{ isset($lead_data) ? $lead_data->user_full_name : '' }}</td>
                </tr>
                <tr>
                    <th>Lead Current Stage: </th>
                    <td>{{ isset($lead_stage_arr[$lead_data->lead_current_stage]) ? $lead_stage_arr[$lead_data->lead_current_stage] : ' ' }}
                    </td>


                </tr>
            </tbody>
        </table>
    </div>
</div>
