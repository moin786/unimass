<input type="hidden" value="3" id="tab_type" />
<div class="tab-pane table-responsive" id="next_followup">
    {{-- Next Followup Table --}}
    <table id="datatable3" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style=" min-width: 10px" class="text-center">ID</th>
                <th style=" min-width: 50px" class="text-center">Customer</th>
                <th style=" min-width: 80px" class="text-center">Mobile</th>
                <th style=" min-width: 100px" class="text-center">Project</th>
                <th style=" min-width: 50px" class="text-center">Agent</th>
                <th style=" min-width: 50px" class="text-center">Stage</th>
                <th style=" min-width: 50px" class="text-center">Last Followup</th>
                <th style=" min-width: 145px" class="text-center">Note</th>
                <th style=" min-width: 25px" class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
        @if(!empty($lead_data))
            @foreach($lead_data as $row)
            @if(date('d-m-Y', strtotime($row->Next_FollowUp_date)) > date('d-m-Y'))
            @include('admin.sales_team_management.lead_followup.lead_follow_up_list')
            @endif
            @endforeach
        @endif
        </tbody>
    </table>
</div>