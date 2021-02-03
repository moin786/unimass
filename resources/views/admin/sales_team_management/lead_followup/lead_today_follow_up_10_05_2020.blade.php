<input type="hidden" value="1" id="tab_type" />
<div class="tab-pane active table-responsive" id="today_followup">
    {{-- Today Followup Table --}}
    <table id="datatable1" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style=" min-width: 10px" class="text-center">ID</th>
                <th style=" min-width: 70px" class="text-center">Customer</th>
                <th style=" min-width: 80px" class="texot-center">Mobile</th>
                <th style=" min-width: 100px" class="text-center">Project</th>
                <th style=" min-width: 50px" class="text-center">Agent</th>
                <th style=" min-width: 50px" class="text-center">Stage</th>
                <th style=" min-width: 50px" class="text-center">Followup Date</th>
                <th style=" min-width: 145px" class="text-center">Note</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($lead_data))
            @foreach($lead_data as $row)
            @if(strtotime($row->Next_FollowUp_date) > strtotime($row->lead_followup_datetime) )

            @php
            $followup_date = strtotime($row->Next_FollowUp_date);
            @endphp

            @else

            @php
            $followup_date = strtotime($row->lead_followup_datetime);
            @endphp

            @endif

            @if( $followup_date == strtotime(date('d-m-Y')) )
            @php
            $followup_dt = date('d-m-Y', $followup_date);
            @endphp
            @include('admin.sales_team_management.lead_followup.lead_follow_up_list')
            @endif
            @endforeach
            @endif
        </tbody>
    </table>
</div>