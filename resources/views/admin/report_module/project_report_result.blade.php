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

    <div class="box-body table-responsive">
        <h4>From Date: {{isset($from_date)? $from_date:""}}  To Date: {{ isset($to_date)? $to_date:""  }} </h4>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Cluster Head</th>
                    <th class="text-center" colspan="{{$count}} ">Project Name</th>
                    <th class="text-center" rowspan="2">Grand Total</th>
                </tr>
                <tr>

                    @foreach($look_data as $data)
                    <th class="text-center">{{ $data->lookup_name  }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>

                @if(!empty($cluster_head))
                @if(!empty($project_report_data))
                @foreach($cluster_head as $cluster)
                <tr>

                    @php
                    $sum = 0;
                    @endphp
                    <td  class="text-center"> {{  $loop->iteration }} </td>
                    <td  class="text-left"> {{ $cluster->user_fullname  }} </td>
                    @foreach($look_data as $project)
                    <td  class="text-right">{{ isset($project_report_data[$cluster->user_pk_no][$project->lookup_pk_no])?$project_report_data[$cluster->user_pk_no][$project->lookup_pk_no]:0  }}</td>
                    @php
                    $store  = isset($project_report_data[$cluster->user_pk_no][$project->lookup_pk_no])?$project_report_data[$cluster->user_pk_no][$project->lookup_pk_no]:0 ;
                    $sum += $store ;
                    @endphp

                    @endforeach

                    <td class="text-right">{{ $sum }}</td>
                </tr>

                @endforeach
                @endif
                @endif

            </tbody>
        </table>
        <h4>From Date: {{isset($pre_date)? $pre_date:""}}  To Date: {{ isset($from_date)? $from_date:""  }} </h4>
        <table id="tbl_search_result" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th class="text-center" rowspan="2">SL</th>
                    <th class="text-center" rowspan="2">Cluster Head</th>
                    <th class="text-center" colspan="{{$count}} ">Project Name</th>
                    <th class="text-center" rowspan="2">Grand Total</th>
                </tr>
                <tr>

                    @foreach($look_data as $data)
                    <th class="text-center">{{ $data->lookup_name  }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>

                @if(!empty($cluster_head))
                @if(!empty($project_report_com))
                @foreach($cluster_head as $cluster)
                <tr>

                    @php
                    $sum = 0;
                    @endphp
                    <td  class="text-center"> {{  $loop->iteration }} </td>
                    <td  class="text-left"> {{ $cluster->user_fullname  }} </td>
                    @foreach($look_data as $project)
                    <td  class="text-right">{{ isset($project_report_com[$cluster->user_pk_no][$project->lookup_pk_no])?$project_report_com [$cluster->user_pk_no][$project->lookup_pk_no]:0  }}</td>
                    @php
                    $store  = isset($project_report_com[$cluster->user_pk_no][$project->lookup_pk_no])?$project_report_com[$cluster->user_pk_no][$project->lookup_pk_no]:0 ;
                    $sum += $store ;
                    @endphp

                    @endforeach

                    <td class="text-right">{{ $sum }}</td>
                </tr>

                @endforeach
                @endif
                @endif

            </tbody>
        </table>

    </div>
</div>
