@php
    $ses_auto_dist = Session::get('user.ses_auto_dist');
    $ses_dist_date = Session::get('user.ses_dist_date');


    $today = date("Y-m-d h:i:s");

@endphp
<div class="tab-pane active table-responsive" id="all_lead">
  <form action="" id="lead_form" method="post">
    @csrf
    <table id="work_list" class="table table-bordered table-striped table-hover">
        <thead>
        <tr>
           @if($type==1)
            <th class="text-center"> Select
                 <a href="#" class="btn bg-blue btn-block btn-xs btn-transfer"
                 data-response-action="{{ route('load_block_lead_list') }}">Approved</a>
            </th>
            @endif
            <th class="text-center">Lead ID</th>
            <th class="text-center">Customer</th>
            <th class="text-center">Mobile</th>
            <th class="text-center">Category</th>
            <th class="text-center">Area</th>
            <th class="text-center">Project</th>
            <th class="text-center">Size</th>
            <th class="text-center">Status</th>


            <th class="text-center">Approved</th>
        </tr>
        </thead>

        <tbody>
        @if(!empty($block_lead_info))
            @foreach($block_lead_info as $row)
                <tr>

                    @include('admin.lead_management.block_lead.block_lead_work_list')
                    <td class="text-center">
                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                          data-id="{{ $row->lead_pk_no }}"
                          data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
                          @if($type==1)
                        <span class="btn bg-info btn-xs distribute-lead" title="Lead Assign"
                              data-type=""
                              data-list-action="return_lead"
                              data-target="#all_lead"
                              data-id="{{ $row->leadlifecycle_pk_no }}"
                              data-action="{{ route('approved_blocked_lead') }}">
                    <i class="fa fa-save"></i>
                </span>
                @endif
                    </td>
                </tr>

            @endforeach
        @endif
        </tbody>
    </table>
    </form>
</div>
