<div class="tab-pane table-responsive" id="transferred_lead">
    {{-- Transferred Lead Table --}}
    <div class="box-body table-responsive">
        <table id="datatable3" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    @include('admin.components.lead_list_table_header')
                    <th style=" min-width: 20px" class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @if(!empty($lead_transfer_list))
                @foreach($lead_transfer_list as $row)
                <tr>
                    @include('admin.components.lead_list_table')

                    <td class="text-center">
                        <span class="btn bg-info btn-xs lead-view" title="Lead Sold"
                        data-id="{{ $row->lead_pk_no }}"
                        data-action="{{ route('lead_view',$row->lead_pk_no) }}">
                        <i class="fa fa-eye"></i>
                    </span>
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
</div>