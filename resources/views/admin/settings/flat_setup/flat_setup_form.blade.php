<form id="frmUser" action="{{ !isset($flat_data)?route('store_flat_setup') : route('update_flat_setup',$flat_data->flatlist_pk_no) }}" method="{{ !isset($flat_data)?'post' : 'post' }}">
    <input type="hidden" id="hdnFlatSetupId" name="hdnFlatSetupId" value="{{ isset($flat_data)? $flat_data->flatlist_pk_no:'' }}"/>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    {{-- <div class="col-md-4"> --}}
                                {{-- <label>Category<span class="text-danger"> *</span></label> --}}

                                {{-- <select class="form-control required select2" name="category" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Category</option>
                                    @if(!empty($project_cat))
                                    @foreach ($project_cat as $key => $cat)

                                    @if (!empty($flat_data) && $flat_data->category_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $cat }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $cat }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select> --}}
                    {{-- </div> --}}
                    <input type="hidden" name="category" id="category" value="583"/>
                    <div class=" col-md-4 col-md-offset-2">
                            <label>Area<span class="text-danger"> *</span></label>
                            <select class="form-control required select2" name="area" style="width: 100%;" aria-hidden="true" required="required">
                                <option value="0" selected="selected" value="">Select Area</option>
                                @if(!empty($project_area))
                                @foreach ($project_area as $key => $area)
                                    @if (!empty($flat_data) && $flat_data->area_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $area }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $area }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                            </select>
                        </div>
                        <div class=" col-md-4" style="margin-bottom: 20px;">
                                <label>Project Name<span class="text-danger"> *</span></label>
                                <select class="form-control required select2" name="project_name" style="width: 100%;" aria-hidden="true" required="required">
                                    <option selected="selected" value="">Select Project Name</option>
                                    @if(!empty($project_name))
                                    @foreach ($project_name as $key => $pname)

                                    @if (!empty($flat_data) && $flat_data->project_lookup_pk_no == $key)
                                    <option value="{{ $key }}" selected>{{ $pname }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $pname }}</option>
                                    @endif

                                    @endforeach
                                    @endif
                                </select>
                        </div>
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <th>Size</th>
                                    <th>Flat Name</th>
                                    <th>Asking Price</th>
                                    <th>Down Payment</th>
                                    <th>Installment No</th>
                                    <th>Install. Amount</th>
                                    <th>Status</th>
                                    @if(empty($flat_data))
                                    <th>Action</th>
                                    @endif
                                </thead>
                                <tbody id="data-append-to">
                                    <tr id="0">
                                    <td >
                                        <select class="form-control" id="flat_size_0" name="flat_size[]" aria-hidden="true" >
                                        <option selected="selected" value="">Select Flat Size</option>
                                        @if(!empty($project_size))
                                        @foreach ($project_size as $key => $size)
                                        @if (!empty($flat_data) && $flat_data->size_lookup_pk_no == $key)
                                        <option value="{{ $key }}" selected>{{ $size }}</option>
                                        @else
                                        <option value="{{ $key }}">{{ $size }}</option>
                                        @endif
                                        @endforeach
                                        @endif
                                        </select>                                       
                                    </td>
                                    <td>
                                        <input id="flat_name_0" type="text" class="form-control" name="flat_name[]" value="{{ isset($flat_data->flat_name)? $flat_data->flat_name  : " " }}">
                                    </td>
                                    <td> 
                                       <input id="flat_price_0" type="number" class="form-control" name="flat_price[]" value="{{ isset($flat_data->flat_asking_price)? $flat_data->flat_asking_price  : " " }}" >
                                    </td>
                                    <td> 
                                       <input id="flat_down_payment_0" type="number" class="form-control" name="flat_down_payment[]"   value="{{ isset($flat_data->flat_down_payment)? $flat_data->flat_down_payment  : " " }}">
                                    </td>
                                    <td> 
                                       <input id="flat_installment_0" type="number" class="form-control" name="flat_installment[]"  value="{{ isset($flat_data->flat_number_installment)? $flat_data->flat_number_installment  : " " }}">
                                    </td>
                                    <td> 
                                       <input id="flat_int_amount_0" type="number" class="form-control" name="flat_int_amount[]"  value="{{ isset($flat_data->flat_installment)? $flat_data->flat_installment  : " " }}">
                                    </td>
                                    <td>
                                        <select  class="form-control" id="flat_status_0" name="status[]">
                                            <option value="0">Active</option>
                                            <option value="2">In Active</option>
                                        </select>
                                    </td>
                                    @if(empty($flat_data))
                                    <td id="med_td_action_0" > 
                                       <span  onclick="addMedRow(this)" class="btn btn-sm btn-success"> +Add </span>
                                    </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                            
                        </div>

                </div>
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
        class="btn bg-purple btn-sm btnSaveUpdate">{{ isset($flat_data)? 'Update':'Create' }}</button>
        <span class="msg"></span>
    </div>
</form>


<script type="text/javascript">
     function addMedRow(thisElement) {

        var row = $(thisElement).parents("tr").clone();
        var oldId = Number($(thisElement).parents("tr").attr("id"));
        var newId = $(thisElement).parents("#data-append-to").find("tr").length + 1;
        row.attr('id', newId);
        row.find('#flat_size_' + oldId).attr('id', 'flat_size_' + newId);
        row.find('#flat_status_' + oldId).attr('id', 'flat_status_' + newId);

        row.find('#flat_size_' + newId).val($(thisElement).parents('tbody').find('#flat_size_' + oldId).val());
        row.find('#flat_status_' + newId).val($(thisElement).parents('tbody').find('#flat_status_' + oldId).val());
        $(thisElement).parents('tbody').find('#flat_size_' + oldId).val('');
        $(thisElement).parents('tbody').find('#hdn_report_serial_med_' + oldId).val('');
        $(thisElement).parents('tbody').find('#flat_price_' + oldId).val('');
        $(thisElement).parents('tbody').find('#flat_down_payment_' + oldId).val('');
        $(thisElement).parents('tbody').find('#flat_installment_' + oldId).val('');
        $(thisElement).parents('tbody').find('#flat_int_amount_' + oldId).val('');
        $(thisElement).parents('tbody').find('#flat_name_' + oldId).val('');


        row.find('#med_td_action_' + oldId).attr('id', 'med_td_action_' + newId);
        $(thisElement).parents("#data-append-to").append(row);

        $('#med_td_action_' + newId).html("<span class='btn btn-danger btn-sm' onclick='removeTableRowOnly(this)'> <i class='fa fa-times'></i> </span>");

    }

    function removeTableRowOnly(thisElement) {
        if (confirm("Are you sure?")) {
            $(thisElement).parents("tr").remove();
        }
        return;
    }


</script>