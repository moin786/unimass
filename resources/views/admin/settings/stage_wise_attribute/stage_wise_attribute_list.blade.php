<table id="user-table" class="table table-bordered table-striped table-hover data-table">
	<thead>
		<tr>
			<th style="width: 50px;">SL</th>
			<th>Stage Name</th>
			<th>Attribute Name</th>
			<th>Attribute Type</th>
			<th>Serial</th>
			<th>Status</th>
			
			<th class="text-center">Action</th>
		</tr>
	</thead>

	<tbody>

			@foreach($data as $data)
			<tr>
				<td style="">{{ $data-> attr_pk_no }}</td>
				<td style="">{{ (!empty($data->stage_id)? $lookup_type[$data-> stage_id]: "") }}</td>
				<td style="">{{ $data-> attr_name }}</td>
				<td style="">{{ (!empty($data->attr_type))?$attr_type_value[$data->attr_type] : " " }}</td>
				<td style="">{{ $data-> attr_sl_no }}</td>
				@if( $data-> row_status == 1)
				<td style=""><span class="badge badge-success" style="background-color: green">Active</span></td>
			 	@else
			 	<td style=""><span class="badge badge-danger" style="background-color: red">Inactive</span></td>
			 	@endif
				<td width="100" class="text-center">
				<span class="btn bg-info btn-xs create_modal"  data-modal="common-modal-sm" data-id="{{ $data-> attr_pk_no }}" data-action="{{ route('Stage_wise_attribute_edit',$data-> attr_pk_no) }}"><i class="fa fa-pencil"></i></span>
			</td>		
			</tr>
		
		@endforeach
	</tbody>
</table>