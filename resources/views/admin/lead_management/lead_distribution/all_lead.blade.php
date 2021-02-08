@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
$today = date("Y-m-d h:i:s");
@endphp
<form id="distribute-form">
    <div class="tab-pane active table-responsive" id="all_lead">
        @if($tab!=0)
        <div class="head_action" style="text-align: left; border-bottom: 1px solid #ccc; margin-bottom:10px;">
           @if($userRoleId !=551)
           <div class="box-body ">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Distribute To<span class="text-danger"> *</span></label>
                        @include('admin.components.multiple_team_member_dropdown')
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-sm btn-success btn-md distribute-lead mt-13"
                    title="Distribute Lead"
                    data-type="1" data-list-action="load_dist_leads" data-target="#all_lead"
                    data-action="{{ route('distribute_lead') }}">
                    Distribute
                </button>

                <!-- <input type="button" name="" value="Distriute" class="btn btn-success"> -->
            </div>
        </div>
    </div>
    @endif

</div>
@endif
<table id="work_list" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            @include('admin.components.lead_list_table_header')
            @if($tab==0)
            <th class="text-center">Assign Date</th>
            @endif
            <th class="text-center">Status</th>
            @if(@$userRoleId !=551)
            <th class="text-center">Action</th>
            @endif
            @if($tab==1)
            <th class="text-center">View</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @if(!empty($lead_data))
        @foreach($lead_data as $row)

        @if($tab==1)
<!-- 
                        php
                            $date1=date_create($row->lead_cluster_head_assign_dt);
                            $date2=date_create($today);
                            $diff=date_diff($date1,$date2);

                            $time= $diff->format("%a")*24+ $diff->format("%h") ;

                        endphp
                        if($time <= $auto_return_time->lookup_name) -->

                        <tr>
                            @include('admin.components.lead_list_table')
                            <td class="text-center" style="font-weight: bold;">
                                @if($row->lead_dist_type == 1)
                                Manual
                                @elseif($row->lead_dist_type == 2)
                                Auto
                                @else
                                Pending
                                @endif
                            </td>
                            @if($userRoleId !=551)
                            <td class="text-center">
                                <input type="checkbox" name="lead_life_cycle_id[]" value="{{ $row->leadlifecycle_pk_no  }}">
                            </td>
                            @endif  
                            <td class="text-center">

                                <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                                data-id="{{ $row->lead_pk_no }}"
                                data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
                            </td>
                        </tr>
                        <!-- endif -->
                        @else
                        <tr>
                            @include('admin.components.lead_list_table')
                            @if($tab==0)
                            <td>{{ date("d/m/Y",strtotime($row->lead_sales_agent_assign_dt)) }}</td>
                            @endif
                            <td class="text-center" style="font-weight: bold;">
                                @if($row->lead_dist_type == 1)
                                Manual
                                @elseif($row->lead_dist_type == 2)
                                Auto
                                @else
                                Pending
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                                data-id="{{ $row->lead_pk_no }}"
                                data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>

                            </td>
                        </tr>

                        @endif
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </form>
        <script type="text/javascript">
            $(".select2").select2();
        </script>