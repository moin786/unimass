<div class="tab-pane table-responsive" id="pending_lead">
    <table id="work_list" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center">Lead ID</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Mobile</th>
                <th class="text-center">Category</th>
                <th class="text-center">Area</th>
                <th class="text-center">Project</th>
                <th class="text-center">Size</th>
                <th style=" min-width: 80px" class="text-center">Sales Agent</th>
                <th class="text-center"></th>
                <th style=" min-width: 90px" class="text-center">Status</th>
                <th style=" min-width: 50px" class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($lead_data))
            @foreach($lead_data as $row)
            @if($row->lead_dist_type == 0 || $row->lead_dist_type == '')
            <tr>
                @include('admin.lead_management.lead_distribution.lead_dist_work_list')
                <td class="text-center">
                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details" data-id="{{ $row->lead_pk_no }}"
                      data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
                      <span class="btn bg-info btn-xs distribute-lead" title="Distribute Lead"
                      data-type="0"
                      data-list-action="load_dist_leads"
                      data-target="#pending_lead"
                      data-id="{{ $row->leadlifecycle_pk_no }}"
                      data-action="{{ route('distribute_lead',$row->leadlifecycle_pk_no) }}"><i class="fa fa-save"></i></span>
                  </td>
              </tr>
              @endif
              @endforeach
              @endif
          </tbody>
      </table>
  </div>