@php

$data = (isset($data) && (!empty($data))) ? $data : [];


$attr_type_data = ($data) ? $data->attr_type : '';
$attr_sl_no = ($data) ? $data->attr_sl_no : '';
$attr_name = ($data) ? $data->attr_name : '';
$stage_id = ($data) ? $data->stage_id : '';
$attr_pk_no = ($data) ? $data->attr_pk_no : '';

$status = ($data) ? $data->row_status : ' ';
@endphp
<form id="frmUser" action="{{ empty($attr_pk_no) ? route('stage_wise_store') : route('stage_wise_update',$attr_pk_no) }}"
      method="{{ empty($attr_pk_no)?'post' : 'patch' }}">
    @csrf
    <!-- <input type="hidden" id="hdnUserId" name="hdnUserId" value=" {{ $attr_pk_no }} "/> -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Stage Name</label>
                    <select name="attribute_type" id="cmbLookupTypeMst" class="form-control" aria-hidden="true">
                         <option value="0">Select</option>
                         @if(!empty($lookup_type))
                            @foreach ($lookup_type as $key => $type)

                               <option value="{{$key}}" {{$stage_id == $key ?  'selected' : ''}}>{{$type}}</option>

                            @endforeach
                            @endif
                    </select>
                </div>
            </div> 
             <div class="col-md-12">
                <div class="form-group">
                    <label>Attribute Name</label>
                    <input type="text" class="form-control" id="attribute_name" name="attribute_name" title="Attribute Name" placeholder="Attribute Name"value="{{$attr_name}}">
                </div>
            </div>           
    </div>
     <div class="row">  
            <div class="col-md-12">
                <div class="form-group">
                    <label>Attribute Type</label>
                    <select name="stage_name" id="cmbLookupTypeMst" class="form-control"  aria-hidden="true">
                        <option value="0"  {{$stage_id == 0 ?  'selected' : ''}}>Select</option>
                        @if(!empty($attr_type))
                       @foreach ($attr_type as $key => $type)

                               <option value="{{$key}}" {{$attr_type_data == $key ?  'selected' :''}}>{{$type}}</option>

                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Serial Number</label>
                    <input type="text" class="form-control" id="serial_number" name="serial_number" title="Serial Number" placeholder="Serial Number" value="{{$attr_sl_no}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control required" id="">
                        <option  value="0" {{$status == 0 ?  'selected' : ''}}>Select</option>
                        <option value="1" {{$status == 1 ?  'selected' : ''}} >Active</option> 
                        <option value="2" {{$status == 2 ?  'selected' : ''}} >Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </section>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
        <button type="submit"
                class="btn bg-purple btn-sm btnSaveUpdate" data-response-action="{{route('Stage_wise_attribute_list')}}" >Save</button>
        <span class="msg"></span>
    </div>
</form>