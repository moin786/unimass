<input type="hidden" value="3" id="tab_type" />
<div class="tab-pane table-responsive" id="next_followup" style="display: block;">
    {{-- Next Followup Table --}}
    <table id="datatable3" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                @include('admin.components.lead_list_table_header')
                <th style=" min-width: 40px" class="text-center">Followup By</th>
                <th style=" min-width: 40px" class="text-center">Next Followup</th>                

                <th style=" min-width: 130px" class="text-center">Note</th>
                <th style=" min-width: 25px" class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($lead_data))
            @foreach($lead_data as $row)
            @php
            $checkingDate= (!empty( $row->Next_FollowUp_date ))? $row->Next_FollowUp_date  :date("Y-m-d");
            $date_def = date_diff( date_create($row->created_at),date_create($checkingDate));                       
            @endphp
            
            @if(strtotime($row->Next_FollowUp_date) > strtotime(date('d-m-Y')))
            @php
            $followup_dt = date('d/m/Y', strtotime($row->Next_FollowUp_date));
            @endphp
            @include('admin.sales_team_management.lead_followup.lead_follow_up_list')
            @endif
            
            @endforeach
            @endif
        </tbody>
    </table>
    @include("admin.sales_team_management.lead_followup.lead_today_visit_list")

</div>