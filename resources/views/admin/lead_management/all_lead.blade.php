@php
$ses_auto_dist = Session::get('user.ses_auto_dist');
$ses_dist_date = Session::get('user.ses_dist_date');
$ses_user_pk = Session::get('user.ses_user_pk_no');

@endphp
<style type="text/css">

</style>
<div class="tab-pane active table-responsive" id="all_lead">
	<form id="distribute-form">
		@if($tab!=0)
		<div class="head_action"
		style="width: 100%;" aria-hidden="true">
		<div class="box-body">
			<div class="row">
				<div class="col-md-3">
					<div class="form-group">
						{{-- <label>Distribute To CH/BH/TL<span class="text-danger"> *</span></label> --}}
						<label>Distribute To TL/SP<span class="text-danger"> *</span></label>
						<select class="form-control required select2" id="cmbTransferTo" name="cmbTransferTo" style="width: 100%;" aria-hidden="true">
							<option value="">Select</option>
							@if(!empty($sales_agent_info))
							@foreach($sales_agent_info as $key=> $value)
							@php
							$team_name = $key;
							@endphp
							<optgroup label="{{ $key }}">
								@foreach($value as $name=>$val)

								@php
								$data = explode("_",$val);
								$prefix = "SP";

								if($data[2] == 1 ){
									$prefix= "CH";
								}else if ($data[3] == 1 ) {
									$prefix = "BH";
								} else if ($data[4] == 1 ){
									$prefix = "TL";
								}
								@endphp
								<option value="{{ @$data[0].'_'.@$ses_user_pk }}">{{ @$prefix }} - {{@$data[1]}} </option>

								@endforeach
							</optgroup>
							@endforeach
							@endif
						</select>
					</div>
				</div>
				<div class="col-md-3">
					<button type="button" class="btn btn-success btn-sm distribute-lead mt-13"
					title="Distribute Lead"
					data-type="1"
					data-list-action="load_dist_leads_to_ch"
					data-target="#all_lead"
					data-action="{{ route('distribute_lead_to_ch') }}">
					Distribute
				</button>

				<!-- <input type="button" name="" value="Distriute" class="btn btn-success"> -->
			</div>
		</div>
	</div>

</div>
@endif

<table id="work_list" class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="text-center">SL</th>
			<th class="text-center">Lead ID</th>
			<th class="text-center">Create Date</th>
			<th class="text-center">Customer</th>
			<th class="text-center">Mobile</th>
			{{-- <th class="text-center">Category</th> --}}
			<th class="text-center">Area</th>
			<th class="text-center">Project</th>
			<th class="text-center">Size</th>
			{{-- <th class="text-center">Source</th> --}}
			<th class="text-center">Source</th>
			{{-- <th class="text-center">Sales Agent</th> --}}
			@if($tab!=0)
			<th class="text-center">Distribute to</th>
			@endif

		</tr>
	</thead>

	<tbody>
	
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			{{-- <td>{{ $row->project_category_name }}</td> --}}
			<td></td>
			<td></td>
			<td></td>
			{{-- <td>{{  isset($source_arr[$row->lead_entry_type]) ? $source_arr[$row->lead_entry_type] : " "   }}</td> --}}
			<td></td>
			{{-- <td class="text-center">{{ $row->lead_cluster_head_name }}</td> --}}
			
			<td class="text-center">
				<input type="checkbox" name="distribute_lead_id[]" value="">
			</td>
		</tr>
	</tbody>
</table>


</div>
</form>
<script type="text/javascript">
    $(".select2").select2();
	$.fn.dataTable.ext.errMode = 'none';    
            var datatable = $('#work_list').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax":{
					"url": "{{ route('lead.lead_distribution_list') }}",
					"dataType": "json",
					"type": "get",
                //"data":{ _token: "{{csrf_token()}}"}
            },
            "columnDefs": [{
                "targets": 1,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {

                    return `${row[1]}`;
                }
            },
            {
                "targets": 3,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {

                    return `${row[3]} ${row[4]}`;
                }
            },
            {
                "targets": 4,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {

                    return `${row[5]}`;
                }
            },
            {
                "targets": 5,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {
                    if (row[6] != null) {
                        return `${row[6]}`;
                    } else {
                        return '';
                    }
                }
            },
            {
                "targets": 6,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {
                    if (row[7] != null) {
                        return `${row[7]}`;
                    } else {
                        return '';
                    }
                }
            },
            {
                "targets": 7,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {
                    if (row[8] != null) {
                        return `${row[8]}`;
                    } else {
                        return '';
                    }
                }
            },
            {
                "targets": 8,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {
                    let sourcelead = @json($digital_mkt);
                    if (row[9] != null) {
                        return sourcelead[row[9]];
                    } else {
                        return [];
                    }
                }
            },
            // {
            //     "targets": 9,
            //     "data": "Edit",
            //     "render": function ( data, type, row, meta ) {
            //         if (row[10] != null) {
            //             return `${row[10]}`;
            //         } else {
            //             return '';
            //         }
            //     }
            // },
            {
                "targets": 9,
                "data": "Edit",
                "render": function ( data, type, row, meta ) {
                    let tab = $('.tab').attr("data-value");
                    console.log(tab)
                    if (tab !=0) {
                        return `
                        <input type="checkbox" name="distribute_lead_id[]" value="${row[10]}">
                        `;
                    } else {
                        return '';
                    }
                }
            },]
        });
</script>
