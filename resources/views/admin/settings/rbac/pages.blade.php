<div class="box">
    <div class="box-body table-responsive">
        <table id="page-table" class="table table-bordered table-striped table-hover data-table">
            <thead>
            <tr>
                <th style="width: 50px;">SL</th>
                <th>Pages</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody>
            @if(!empty($module_arr))
            @foreach($module_arr as $module_id => $module)
                <tr>
                    <th colspan="3"> &nbsp;<i class="fa fa-arrow-circle-right"></i> {{ $module }}</th>
                </tr>
                @foreach($pages_arr[$module_id] as $page_id => $page)
                    <tr>
                        <td style="width: 30px; text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $page }}</td>
                        <td style="width: 30px; text-align: center;">
                            <input type="checkbox" name="chkPage[]" class="assign-pages"
                                   value="{{ $page_id }}"
                                   data-role="{{ $role_id }}"
                                   {{ isset($role_permission[$page_id])?"checked='checked'":"" }}
                                   data-action="{{ route('rbac_assign', ['page_id'=>$page_id, 'role_id'=>$role_id]) }}"/>
                        </td>
                    </tr>
                @endforeach
            @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>