@php
$is_bypass = Session::get('user.is_bypass');
$bypass_date = Session::get('user.bypass_date');
@endphp
<div class="head_action" style="background-color: #ECF0F5; text-align: right; border: 1px solid #ccc; padding: 3px;">
	<strong style="display: inline-block;">Bypass :</strong> &nbsp &nbsp &nbsp &nbsp
	<label class="">
		&nbsp Yes &nbsp
		<div class="iradio_flat-green {{ ($is_bypass==1)?'checked':'' }}" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
			<input type="radio" value="1" name="can_bypass" {{ ($is_bypass==0)?'checked':'' }} class="can_bypass flat-red"  style="position: absolute; opacity: 0;">
			<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
		</div>
	</label>

	<label class="">
		&nbsp No &nbsp
		<div class="iradio_flat-green {{ ($is_bypass==0)?'checked':'' }}" aria-checked="false" aria-disabled="false" style="position: relative; margin-right:10px; margin-bottom:6px;">
			<input type="radio" value="0" name="can_bypass" {{ ($is_bypass==0)?'checked':'' }} class="can_bypass flat-red"  style="position: absolute; opacity: 0;">
			<ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
		</div>
	</label>

	<label for="bypass_date" style="display: inline-block; margin-left: 50px; margin-right: 10px">Date :</label>
	<div class="form-group" style="display: inline-block; margin-bottom: 0px !important;">
		<input type="text" class="form-control datepicker" id="bypass_date" name="bypass_date" value="{{ ($is_bypass==1)? date('d-m-Y',strtotime($bypass_date)):date('d-m-Y') }}" title="" readonly="readonly" placeholder="" style="display: inline-block;"/>
	</div>
</div><br clear="all" />

<div class="tab-pane active table-responsive" id="qc_work_list">
	<table id="work_list" class="table table-bordered table-striped table-hover work_list">
		<thead>
			<tr>
				<th class="text-center">Lead ID</th>
				<th class="text-center">Customer</th>
				<th class="text-center">Mobile</th>
				<th class="text-center">Category</th>
				<th class="text-center">Area</th>
				<th class="text-center">Project</th>
				<th class="text-center">Size</th>
				<th class="text-center">Status</th>
				<th class="text-center">Junk / Pass</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>

		<tbody>
			@if(!empty($lead_data))
			@foreach($lead_data as $row)
			@if($row->lead_qc_flag == 0 || $row->lead_qc_flag == '')
			<tr>
				@include('admin.lead_management.qc.lead_qc_list')
			</tr>
			@endif
			@endforeach
			@endif
		</tbody>
		<tfoot>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="text-center">
				<button type="submit" class="btn bg-red btn-xs lead-qc-status" data-target="#qc_work_list" data-type="1" id="junk">Junk</button>
				<button type="submit" class="btn bg-green btn-xs lead-qc-status" data-target="#qc_work_list" data-type="1" id="pass">Pass</button>
			</td>
			<td></td>
		</tfoot>
	</table>
</div>