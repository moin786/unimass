@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');


$today = date("Y-m-d h:i:s"); 

@endphp
<!-- <div class="head_action" style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">
    <strong style="display: inline-block;">Auto Distribution :</strong> &nbsp &nbsp &nbsp &nbsp
    <label class="">
        &nbsp Yes &nbsp
        <input type="radio" value="1" name="auto_distribute"  class="auto_distribute" {{ ($ses_auto_dist==1)?'checked':'' }} >
    </label>

    <label class="">
        &nbsp No &nbsp
        <input type="radio" value="0" name="auto_distribute" class="auto_distribute"  {{ ($ses_auto_dist==0)?'checked':'' }}>
    </label>

    <label for="dist_date" style="display: inline-block; margin-left: 50px; margin-right: 10px">Date :</label>
    <div class="form-group" style="display: inline-block; margin-bottom: 0px !important;">
        <input type="text" class="form-control datepicker" id="dist_date" name="dist_date" value="{{ ($ses_auto_dist==1)? date('d-m-Y',strtotime($ses_dist_date)):date('d-m-Y') }}" title="" readonly="readonly" placeholder="" style="display: inline-block;"/>
    </div>
</div><br clear="all" /> -->
<div class="tab-pane active table-responsive" id="all_lead">
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
                <th class="text-center">Cluster Head</th>
                <th class="text-center">Status</th>


                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @if(!empty($return_lead_info))
            @foreach($return_lead_info as $row)

            @php 
            $date1=date_create($row->lead_cluster_head_assign_dt);
            $date2=date_create($today);
            $diff=date_diff($date1,$date2);

            $time= $diff->format("%a")*24+ $diff->format("%h") ;

            @endphp
            @if($time >= $auto_return_time->lookup_name)
            <tr>

                @include('admin.lead_management.lead_return.lead_dist_work_list')
                <td class="text-center">
                    <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                    data-id="{{ $row->lead_pk_no }}"
                    data-action="{{ route('lead_view',$row->lead_pk_no) }}"><i class="fa fa-eye"></i></span>
                    <span class="btn bg-info btn-xs distribute-lead" title="Lead Assign"
                    data-type=""
                    data-list-action="return_lead"
                    data-target="#all_lead"
                    data-id="{{ $row->leadlifecycle_pk_no }}"
                    data-action="{{ route('reassign_lead') }}">
                    <i class="fa fa-save"></i>
                </span>
            </td>
        </tr>
        @endif
        @endforeach
        @endif
    </tbody>
</table>
</div>