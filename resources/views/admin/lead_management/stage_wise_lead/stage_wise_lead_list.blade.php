@php
$ses_user_role = Session::get('user.ses_role_lookup_pk_no');
@endphp
<div class="tab-pane active table-responsive" id="all_lead">
    <table id="work_list" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                @include('admin.components.lead_list_table_header')
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($lead_data))
            @foreach($lead_data as $row)
            <tr>
                @include('admin.components.lead_list_table')
                <td class="text-center" style="font-weight: bold;">
                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                    data-id="{{ $row->lead_pk_no }}"
                    data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>

                    @if($ses_user_role == 77)
                    <span class="btn bg-info btn-xs next-followup" data-title="Lead Followup" title="Lead Followup" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_follow_up.edit',$row->lead_pk_no) }}">
                        <i class="fa fa-list"></i>
                    </span>

                    @if($row->lead_sales_agent_pk_no == $ses_user_id)
                    <span class="btn bg-info btn-xs lead-edit" data-title="Lead Edit" title="Lead Edit" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead.edit',$row->lead_pk_no) }}"><i class="fa fa-edit"></i></span>

                    <span class="btn bg-info btn-xs lead-sold" data-title="Lead Sold" title="Lead Sold" data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_sold',$row->lead_pk_no) }}"><i class="fa fa-handshake-o"></i></span>


                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
