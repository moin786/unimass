
<table id="user-table" class="table table-bordered table-striped table-hover data-table">
   <thead>
    <tr>
        <th style=" min-width: 20%" class="text-center">Id</th>
        <th style=" min-width: 50" class="text-center">District Name</th>           
        <th style=" min-width: 30%" class="text-center">Action</th>
    </tr>
</thead>

<tbody>
    @if(!empty($ditrict))
    @foreach($ditrict as $value)
    <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->district_name }} </td>
        <td class="text-center">
            <span class="btn bg-info btn-xs create_modal" data-modal="common-modal-sm" data-action="{{ route('district.edit',$value->id) }}" data-id="{{ $value->id }}"><i class="fa fa-pencil"></i></span>
            <span class="btn bg-danger btn-xs" data-id=""><a href="{{ route('district.delete',$value->id) }}" onclick="return confirm('Are you sure you want to Delete?')"><i class="fa fa-close" data-action="{{ route('district.delete',$value->id) }}" data-id="{{ $value->id }}"></i></a></span>
        </td>
    </tr>
    @endforeach
    @endif
</tbody>

</table>
