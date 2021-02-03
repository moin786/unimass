@php
$user_type = Session::get('user.user_type');
@endphp
<br />
<div class="col-md-12 table-responsive">
    <table id="" class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th style=" min-width: 126px" class="text-center">Sales Exicutive ID</th>
                <th style=" min-width: 100px" class="text-center">Name</th>
                <th style=" min-width: 100px" class="text-center">Category</th>
                <th style=" min-width: 155px" class="text-center">Area</th>
                <th style=" min-width: 100px; width: 160px;" class="text-center">Target By
                    <small>(Amount In Tk.)</small>
                </th>
                <th style=" min-width: 100px; width: 150px;" class="text-center">Target By
                    <small>(Lead)</small>
                </th>
            </thead>

            <tbody>
                @if(!empty($team_member))
                @foreach($team_member as $member)
                @php
                $area_ids = '';
                $area_ids_arr = explode(',', $member->area_lookup_pk_no);
                if(!empty($area_ids_arr))
                {
                    foreach($area_ids_arr as $area_id)
                    {
                        $area_ids .= $project_area[$area_id].', ';
                    }
                }
                $area_ids = rtrim($area_ids, ', ');
                @endphp
                <tr>
                    <input type="hidden" name="team_user[]" value="{{ $member->user_pk_no }}" />
                    <input type="hidden" name="category_id[]" value="{{ $member->category_lookup_pk_no }}" />
                    <input type="hidden" name="area_id[]" value="{{ $member->area_lookup_pk_no }}" />
                    <input type="hidden" name="target_pk_no[]" value="{{ (isset($target_arr[$member->user_pk_no]['target_pk_no']))? $target_arr[$member->user_pk_no]['target_pk_no']:'' }}" />

                    <td class="text-center">{{ $member->user_pk_no  }}</td>
                    <td class="text-center">{{ $member->user_fullname }}</td>
                    <td class="text-center">{{ isset($project_cat[$member->category_lookup_pk_no])?$project_cat[$member->category_lookup_pk_no]:'' }}</td>
                    <td class="text-center">{{ $area_ids }}</td>
                    <td class="text-center">
                        <div class="form-group">
                            <input type="text" class="form-control text-right number-only" id="target_amount"
                            name="target_amount[]" value="{{ (isset($target_arr[$member->user_pk_no]['target_pk_no']))? $target_arr[$member->user_pk_no]['target_amount']:'' }}" title="" placeholder="Enter Amount" {{ ($user_type == 1)?"disabled='disabled'":"" }} />
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="form-group">
                            <input type="text" class="form-control text-right number-only" id="target_qty"
                            name="target_qty[]"
                            value="{{ (isset($target_arr[$member->user_pk_no]['target_pk_no']))? $target_arr[$member->user_pk_no]['target_by_lead_qty']:'' }}" title="" placeholder="Enter Quantity" {{ ($user_type == 2)?"disabled='disabled'":"" }} />
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" class="text-center">
                        <button type="submit" class="btn bg-green btn-sm btnSaveUpdate">Save</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>