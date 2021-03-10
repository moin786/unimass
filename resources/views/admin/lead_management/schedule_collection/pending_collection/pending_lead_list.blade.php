<div class="tab-pane table-responsive active" id="sold_lead">
    <table class="table table-bordered table-striped table-hover mb-0">
        <thead class="bg-blue">
        @include("admin.components.lead_list_table_header")
        <th class="text-center">Collection Information</th>

        <th class="text-center">Action</th>
        </thead>
        <tbody>
        @if(!empty($sold_lead))
            @foreach($sold_lead as $row)
                @php
                    $paid_amount = isset($schedule_arr[$row->lead_pk_no])?  $schedule_arr[$row->lead_pk_no]:0;
                    $total_amount = isset($collection_arr[$row->lead_pk_no])? $collection_arr[$row->lead_pk_no]:0;
                @endphp
                @if($total_amount != $paid_amount)
                    <tr>
                        @include("admin.components.lead_list_table")
                        <td>
                            <div><strong>Receiveable
                                    : </strong> {{ isset($collection_arr[$row->lead_pk_no])? number_format($collection_arr[$row->lead_pk_no],2) : 0 }}
                            </div>
                            <div>
                                <strong>Paid:</strong> {{ isset($schedule_arr[$row->lead_pk_no])? number_format($schedule_arr[$row->lead_pk_no],2) : 0 }}
                            </div>
                            @php
                                $due = 0;
                                $rec = isset($collection_arr[$row->lead_pk_no])? $collection_arr[$row->lead_pk_no] : 0;
                                $col = isset($schedule_arr[$row->lead_pk_no])? $schedule_arr[$row->lead_pk_no] : 0;
                                $due = $rec-$col;

                            @endphp
                            <div><strong>Due:</strong> <b> {{ number_format( $due,2)}}    </b></div>
                        </td>

                        <td>

					<span class="btn btn-xs bg-green lead-view" data-title="Followup" title="Followup" data-id="2"
                          data-action="{{ route('lead_sold_view',$row->lead_pk_no) }}"> <i class="fa fa-check"></i>
					</span>
                            <span class="btn btn-xs bg-blue lead-view" data-title="Collected Collection"
                                  title="Followup"
                                  data-id="3" data-action="{{ route('collected_collection_view',$row->lead_pk_no) }}"><i
                                    class="fa fa-bars"></i></span>
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        </tbody>
    </table>
</div>
