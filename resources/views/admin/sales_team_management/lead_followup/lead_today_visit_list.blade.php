@php
    $userRoleID= Session::get("user.ses_role_lookup_pk_no");
@endphp
<h3> Visit Lists </h3> <br>
<div class="table-responsive">
    <table id="datatable2" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                @include('admin.components.lead_list_table_header')

                <th style=" min-width: 40px" class="text-center">Last Followup By</th>
                <th style=" min-width: 40px" class="text-center">Followup Date</th>

                <th style=" min-width: 130px" class="text-center">Note</th>
                <th style=" min-width: 130px" class="text-center">Visit Date</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($today_meeting_data))
            @foreach($today_meeting_data as $row)

            @php
            $checkingDate= (!empty( $row->Next_FollowUp_date ))? $row->Next_FollowUp_date  :date("Y-m-d");
            $date_def = date_diff( date_create($row->created_at),date_create($checkingDate));
            @endphp



            @if(strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime) )

            @php
            $followup_date = strtotime($row->Next_FollowUp_date);
            @endphp

            @else

            @php
            $followup_date = strtotime($row->lead_followup_datetime);
            @endphp

            @endif


            @php
            $followup_dt = date('d/m/Y', $followup_date);
            @endphp


            @php
            $ses_user_id   = Session::get('user.ses_user_pk_no');
            @endphp
            <tr>
                @include('admin.components.lead_list_table')

                <td> {{ $row->last_followup_name }} </td>
                <td>{{ ($row->last_followup_name != "")?$followup_dt:'' }}</td>
                <td>{{ $row->next_followup_Note }}</td>
                <td>{{ ($row->visit_meeting_done_dt!="")?date("d/m/Y", strtotime($row->visit_meeting_done_dt)):"" }}</td>

                <td width="150" align="center">
                    <span class="btn bg-info btn-xs lead-view" data-title="Lead Details" title="Lead Details" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}">
                        <i class="fa fa-eye"></i>
                    </span>

                    @if($row->lead_sales_agent_pk_no == $ses_user_id)
                    <span class="btn bg-info btn-xs lead-edit" data-title="Lead Edit" title="Lead Edit" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead.edit',$row->lead_pk_no) }}"><i class="fa fa-edit"></i></span>
                    @endif
                    @if (!Session::get('user.is_team_leader') && !Session::get('user.is_ses_hod') && $userRoleID!=551)
                        <span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_follow_up.edit',$row->lead_pk_no) }}">
                            <i class="fa fa-list"></i>
                        </span>
                    @endif
                    @if (!Session::get('user.is_team_leader') && !Session::get('user.is_ses_hod') && $userRoleID!=551)
                    <span class="btn bg-info btn-xs lead-sold" data-title="Lead Sold" title="Lead Sold" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_sold',$row->lead_pk_no) }}">
                        <i class="fa fa-handshake-o"></i>
                    </span>
                    @endif
                </td>
            </tr>



            @endforeach
            @endif
        </tbody>
    </table>
</div>
