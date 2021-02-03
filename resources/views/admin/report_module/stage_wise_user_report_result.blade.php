@php
$is_super_admin = Session::get('user.is_super_admin');
@endphp

<style type="text/css">
    .table thead tr td,
    .table thead tr th,
    .table tbody tr td,
    .table tbody tr th,
    .table tfoot tr td,
    .table tfoot tr th {
        vertical-align: middle !important;
    }
</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Search Result</h3>
        <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button>
    </div>

    <div class="box-body">
        <h4>From Date: {{isset($from_date)? $from_date:""}}  To Date: {{ isset($to_date)? $to_date:""  }}</h4>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Cluster Head</th>
                    <th class="text-center" colspan="7">Sales FeedBack</th>
                    <th class="text-center" rowspan="2">Grand Total</th>
                </tr>
                <tr>

                    @foreach($lead_stage_arr as $stage_id=>$stage)
                    <th>{{ $stage }}</th>
                    @endforeach
<!--                     <th>{{ $report_name }}</th>
                    <th>SQL</th>
                    <th>Did Not Update</th> -->

                </tr>
            </thead>
            <tbody>
                @php
                $sum=0;
                @endphp

                @if(!empty($cluster_head1))
                @foreach($cluster_head1 as $data)
                <tr>

                    @php
                    $sum = 0;
                    @endphp
                    <td class="text-center"> {{  $loop->iteration }} </td>
                    <td class="text-left"> {{  $data->user_fullname }} 
                    </td>
                    @foreach($lead_stage_arr as $stage_id=>$stage)
                    <td class="text-right">{{ isset($lead_source_arr[$data->user_pk_no][$stage_id])? $lead_source_arr[$data->user_pk_no][$stage_id]:"0" }}</td>
                    @php
                    $store = isset($lead_source_arr[$data->user_pk_no][$stage_id])? $lead_source_arr[$data->user_pk_no][$stage_id]:"0";
                    $sum +=  $store;
                    @endphp
                    @endforeach

                    <td class="text-right">{{ $sum }}</td>
                </tr>
                @endforeach
                @endif

            </tbody>

        </table>



        <h4>From Date: {{isset($pre_date)? $pre_date:""}} To Date: {{ isset($from_date)? $from_date:""  }} </h4>

        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Cluster Head</th>
                    <th class="text-center" colspan="7">Sales FeedBack</th>
                    <th class="text-center" rowspan="2">Grand Total</th>
                </tr>
                <tr>

                    @foreach($lead_stage_arr as $stage_id=>$stage)
                    <th>{{ $stage }}</th>
                    @endforeach
<!--                     <th>{{ $report_name }}</th>
                    <th>SQL</th>
                    <th>Did Not Update</th> -->

                </tr>
            </thead>
            <tbody>
                @php
                $sum=0;
                @endphp

                @if(!empty($cluster_head1))
                @foreach($cluster_head1 as $data)
                <tr>

                    @php
                    $sum = 0;
                    @endphp
                    <td class="text-center"> {{  $loop->iteration }} </td>
                    <td class="text-left"> {{  $data->user_fullname }} 
                    </td>
                    @foreach($lead_stage_arr as $stage_id=>$stage)
                    <td class="text-right">{{ isset($lead_source_cmp_arr[$data->user_pk_no][$stage_id])? $lead_source_cmp_arr[$data->user_pk_no][$stage_id]:"0" }}</td>
                    @php
                    $store = isset($lead_source_cmp_arr[$data->user_pk_no][$stage_id])? $lead_source_cmp_arr[$data->user_pk_no][$stage_id]:"0";
                    $sum +=  $store;
                    @endphp
                    @endforeach

                    <td class="text-right">{{ $sum }}</td>
                </tr>
                @endforeach
                @endif

            </tbody>

        </table>
    </div>
</div>
