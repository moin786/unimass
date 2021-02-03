<div class="tab-pane table-responsive" id="transferred_request">
    {{-- Transferred Request Table --}}
    <div class="box-body table-responsive">
        <table id="lead_transfer_request" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    @include('admin.components.lead_list_table_header')
                    <th style=" min-width: 50px" class="text-center">Request From</th>                    
                    <th style=" min-width: 50px" class="text-center">Transfer To</th>
                    @if($is_ch==1)
                    <th style=" min-width: 20px" class="text-center">
                        <!-- <a href="#" class="btn bg-blue btn-block btn-xs btn-accept-request" >Accept</a> -->
                        <select name="" class="form-control btn-accept-request" data-response-action="{{ route('load_transfer_leads') }}" >
                            <option value="">Select</option>
                            <option value="1">Accept</option>
                            <option value="2">Rejected</option>
                        </select>
                    </th>
                    @endif
                    <th style=" min-width: 20px" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @if(!empty($lead_transfer_list))
                @foreach($lead_transfer_list as $row)
                @if(isset($max_transfer_arr[$row->lead_pk_no]) && $max_transfer_arr[$row->lead_pk_no] == 0)
                <tr>
                    @include('admin.components.lead_list_table')
                    <td class="text-center">{{ $row->from_sales_agent_name }}</td>                    
                    <td class="text-center">{{ $row->to_sales_agent_name }}</td>
                    @if($is_ch==1)
                    <td class="text-center">
                        <input type="checkbox"
                        data-trans-id="{{ $row->transfer_pk_no }}"
                        data-name="{{ $row->lead_id }}"
                        data-lead-id="{{ $row->lead_pk_no }}"
                        data-to-agent="{{ $row->transfer_to_sales_agent_pk_no }}"
                        >
                    </td>
                    @endif
                    <td class="text-center">
                        <span class="btn bg-info btn-xs lead-view" title="View Lead Details"
                        data-id="{{ $row->lead_pk_no }}" data-action="{{ route('lead_view',$row->lead_pk_no) }}">
                        <i class="fa fa-eye"></i></span>
                    </td>
                </tr>
                @endif
                @endforeach
                @endif
                
            </tbody>
        </table>
    </div>
</div>