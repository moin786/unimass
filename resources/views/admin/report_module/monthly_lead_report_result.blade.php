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
        @if($is_super_admin == 1)
            <button type="submit" class="btn bg-blue btn-xs pull-right" id="btnExportLeads">Export to CSV</button>
        @endif
    </div>

    <div class="box-body">
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Cluster Head</th>
                <th class="text-center">MQL</th>
                <th class="text-center">Walk In</th>
                <th class="text-center">SGL</th>
                <th class="text-center">Grand Total</th>
                <th class="text-center">Visit Done</th>
                <th class="text-center">Customer Meet</th>
                <th class="text-center">Unit Confirmation Done</th>
                <th class="text-center">Sold</th>


            </tr>
            </thead>
            <tbody>
            @if(!empty($cluster_head))
                @foreach($cluster_head as $cluster)
                    @if(!empty($monthly_lead_report))
                        <tr>
                            <td class="text-center">{{ $loop->iteration  }}</td>
                            <td class="text-left">{{ $cluster->user_fullname }}</td>
                            <td class="text-right">{{  $monthly_lead_report[$cluster->user_pk_no][1]  }}</td>
                            <td class="text-right">{{ $monthly_lead_report[$cluster->user_pk_no][2]  }}</td>
                            <td class="text-right">{{  $monthly_lead_report[$cluster->user_pk_no][3] }}</td>
                            <td class="text-right">{{ $monthly_lead_report[$cluster->user_pk_no][1]+$monthly_lead_report[$cluster->user_pk_no][2]+$monthly_lead_report[$cluster->user_pk_no][3] }}</td>
                            <td class="text-right"></td><td class="text-center"></td><td class="text-right"></td ><td class="text-right"></td>
                        </tr>
                    @endif
                @endforeach
            @endif


            </tbody>

        </table>
    </div>
</div>
