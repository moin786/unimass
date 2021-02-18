<div class="table-responsive">
  <table id="datatable2" class="table table-bordered table-striped table-hover data-table">
    <thead>
      <tr>
        <th style=" min-width: 40px" class="text-center">Sl</th>
        <th style=" min-width: 40px" class="text-center">District Name</th>
        <th style=" min-width: 130px" class="text-center">Thana Name</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>

    <tbody>
      @if(!empty($upazilas))
      @foreach($upazilas as $thana)
      <tr>
        <td>{{$thana->id}}</td>
        <td>{{$thana->district_name}}</td>
        <td>{{$thana->thana_name}}</td>
        <td class="text-center">
          <span class="btn bg-info btn-xs create_modal" data-modal="common-modal-sm" data-action="{{ route('thana.edit',$thana->id) }}" data-id="{{ $thana->id }}"><i class="fa fa-pencil"></i></span>
          <span class="btn bg-danger btn-xs" data-id=""><a href="{{ route('thana.delete',$thana->id) }}" onclick="return confirm('Are you sure you want to Delete?');"><i class="fa fa-close" data-action="{{ route('thana.delete',$thana->id) }}" data-id="{{ $thana->id }}"></i></a></span>
          
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
</div>