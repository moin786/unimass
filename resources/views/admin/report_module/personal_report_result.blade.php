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
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center" rowspan="2">SL</th>
                <th class="text-center" rowspan="2">Cluster Head</th>
                <th class="text-center" colspan="10">Sales FeedBack A/B/C</th>
                <th class="text-center" rowspan="2">Grand Total</th>
            </tr>
            <tr>
                <th>{{ $report_name }}</th>
                <th>SQL</th>
                @if(!empty($lead_stage_arr))
                @foreach($lead_stage_arr as $stage_id=>$stage)
                    <th>{{ $stage }}</th>
                @endforeach
                @endif
                <th>Did Not Update</th>

            </tr>
            </thead>
            <tbody>

            @if(!empty($stage_wise_members))
                @foreach($stage_wise_members as $key=>$data)
                    <tr>

                        @php
                            $sum = 0;
                        @endphp
                        <td class="text-center"> {{  $loop->iteration }} </td>
                        <td class="text-left"> {{  $data }}
                         </td>
                        <td class="text-right"> {{ isset($cluster_head_wise_count[$key]) ? $cluster_head_wise_count[$key]:0 }} </td>
                          <td class="text-right"> {{ isset($stage_wise_count[$key][3])?$stage_wise_count[$key][3]:0 }}
                         </td>
                        @foreach($lead_stage_arr as $stage_id=>$stage)
                            <td class="text-right">{{ isset($stage_wise_count[$key][$stage_id])?$stage_wise_count[$key][$stage_id]:0  }}</td>
                            @php
                                $store  = isset($stage_wise_count[$key][$stage_id])?$stage_wise_count[$key][$stage_id]:0 ;
                                $sum += $store ;
                            @endphp

                        @endforeach
                        <td class="text-right"> {{  isset($did_not_count_arr[$key])?$did_not_count_arr[$key]:0  }} </td>
                        @php
                            $store  = isset($did_not_count_arr[$key])?$did_not_count_arr[$key]: 0 ;
                            $sum += $store ;
                        @endphp
                        <td class="text-right">{{ $sum }}</td>
                    </tr>
                @endforeach
            @endif

            </tbody>

        </table>
    </div>
</div>
