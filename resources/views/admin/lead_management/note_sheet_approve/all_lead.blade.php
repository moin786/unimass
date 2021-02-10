@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
$today = date('Y-m-d h:i:s');
$user_id = Session::get('user.ses_user_pk_no');
$userRoleId = Session::get('user.ses_role_lookup_pk_no');
$is_hod = Session::get('user.is_ses_hod');
$is_super_admin = Session::get('user.is_super_admin');
@endphp
<form id="distribute-form">
    <div class="tab-pane active table-responsive" id="all_lead">
        <table id="work_list" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    @include('admin.components.lead_list_table_header')
                    @if ($tab == 0)
                        <th class="text-center">Assign Date</th>
                    @endif
                    @if (@$userRoleId != 551)
                        <th class="text-center">Action
                            <select name="" id="" class="btn-accept-request form-control"
                                data-response-action={{ route('load_note_sheet_list') }}>
                                <option value="0">Select</option>
                                <option value="1">Accept</option>
                                <option value="2">Reject</option>
                            </select>


                        </th>
                    @endif
                    @if ($tab == 1)
                        <th class="text-center">View</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @if (!empty($lead_data))
                    @foreach ($lead_data as $row)

                        @if ($tab == 1)
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

                                @if ($userRoleId != 551)
                                    <td class="text-center">
                                        <input type="checkbox" name="lead_life_cycle_id"
                                            data-lead-id="{{ $row->leadlifecycle_pk_no }}"
                                            value="{{ $row->leadlifecycle_pk_no }}">
                                    </td>
                                @endif
                                <td class="text-center">

                                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                                        data-id="{{ $row->lead_pk_no }}"
                                        data-action="{{ route('lead_view', $row->lead_pk_no) }}"><i
                                            class="fa fa-eye"></i></span>
                                </td>
                            </tr>
                            <!-- endif -->
                        @else
                            <tr>
                                @include('admin.components.lead_list_table')
                                @if ($tab == 0)
                                    <td>{{ date('d/m/Y', strtotime($row->lead_sales_agent_assign_dt)) }}</td>
                                @endif

                                <td class="text-center">
                                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                                        data-id="{{ $row->lead_pk_no }}"
                                        data-action="{{ route('lead_view', $row->lead_pk_no) }}"><i
                                            class="fa fa-eye"></i></span>

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
