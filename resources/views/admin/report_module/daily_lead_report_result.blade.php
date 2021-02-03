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
        <h4>From Date: {{isset($from_date)? $from_date:""}}  To Date: {{ isset($to_date)? $to_date:""  }} </h4>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Cluster Head</th>
                <th class="text-center">MQL</th>
                <th class="text-center">Walk In</th>
                <th class="text-center">SGL</th>
                <th class="text-center">Grand Total</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($cluster_head))

                @foreach($cluster_head as $cluster)
                    @if(!empty($daily_report_data))
                        @php
                            $mql = isset($daily_report_data[$cluster->user_pk_no][1])?$daily_report_data[$cluster->user_pk_no][1]:0;
                            $walkin = isset($daily_report_data[$cluster->user_pk_no][2])?$daily_report_data[$cluster->user_pk_no][2]:0;
                            $sql = isset($daily_report_data[$cluster->user_pk_no][3])?$daily_report_data[$cluster->user_pk_no][3]:0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration  }}</td>
                            <td class="text-left">{{ $cluster->user_fullname }}</td>
                            <td class="text-right">{{  $mql  }}</td>
                            <td class="text-right">{{ $walkin  }}</td>
                            <td class="text-right">{{  $sql }}</td>
                            <td class="text-right">{{ $mql+$walkin+$sql }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif


            </tbody>

        </table>
        <h4>From Date: {{isset($pre_date)? $pre_date:""}} To Date: {{ isset($from_date)? $from_date:""  }}  </h4>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Cluster Head</th>
                <th class="text-center">MQL</th>
                <th class="text-center">Walk In</th>
                <th class="text-center">SGL</th>
                <th class="text-center">Grand Total</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($cluster_head))

                @foreach($cluster_head as $cluster)
                    @if(!empty($daily_report_com))
                        @php
                                $mql = isset($daily_report_com[$cluster->user_pk_no][1])?$daily_report_com[$cluster->user_pk_no][1]:0;
                            $walkin = isset($daily_report_com[$cluster->user_pk_no][2])?$daily_report_com[$cluster->user_pk_no][2]:0;
                            $sql = isset($daily_report_com[$cluster->user_pk_no][3])?$daily_report_com[$cluster->user_pk_no][3]:0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->iteration  }}</td>
                            <td class="text-left">{{ $cluster->user_fullname }}</td>
                            <td class="text-right">{{  $mql  }}</td>
                            <td class="text-right">{{ $walkin  }}</td>
                            <td class="text-right">{{  $sql }}</td>
                            <td class="text-right">{{ $mql+$walkin+$sql }}</td>
                        </tr>
                    @endif
                @endforeach
            @endif


            </tbody>

        </table>

    </div>
</div>
