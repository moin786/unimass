<table id="example1" class="table table-bordered table-striped table-hover data-table">
	<thead>
		<tr>
			<th style="min-width:100px">SL#</th>
			<th style="min-width:100px">ID</th>
			<th style="min-width:100px" class="text-center">Type</th>
			<th style="min-width:100px" class="text-center">Name</th>
			<th style="min-width:80px" class="text-center">Action</th>
		</tr>
	</thead>

	<tbody>

		@foreach ($lookup_data as $indexKey => $row)
		<tr>
			<td> {{ $loop->iteration }} </td>
			<td> {{ "L-" . date('Y') .'-'. $row->lookup_pk_no }} </td>
			<td> {{ isset($lookup_type[$row->lookup_type])?$lookup_type[$row->lookup_type]:"" }} </td>
			<td> {{ $row->lookup_name }} </td>
			<td class="text-center">
				<span class="btn bg-{{ ($row->lookup_row_status==1)?'success':'danger' }} btn-xs" data-id="{{ $row->lookup_pk_no }}">{{ ($row->lookup_row_status==1)?'Active':'Inactive' }}</span>
				<span class="btn bg-info btn-xs update_modal" data-action="{{ route('settings.edit',$row->lookup_pk_no) }}" data-id="{{ $row->lookup_pk_no }}"><i class="fa fa-pencil"></i></span>
				{{-- <span class="btn bg-danger btn-xs" data-id="{{ $row->lookup_pk_no }}"><i class="fa fa-close"></i></span> --}}
			</td>
		</tr>
		@endforeach

	</tbody>
</table>