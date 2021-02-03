
<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

<form id="frmUser" action="{{ !isset($lookup_data)?route('settings.store') : route('settings.update',$lookup_data->lookup_pk_no) }}" method="{{ !isset($lookup_data)?'post' : 'patch' }}">
    <input type="hidden" id="hdnLookupId" name="hdnLookupId" value=" {{ isset($lookup_data)? $lookup_data->lookup_pk_no:'' }} " />
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="cmbLookupType">Look Up Type : <small style="color:red">*</small></label>
                                <select name="cmbLookupType" id="cmbLookupType" class="form-control select2" style="width: 100%;" aria-hidden="true">
                                    <option value="">Select</option>
                                    @foreach ($lookup_type as $key => $type)
                                    @if (!empty($lookup_data) && $lookup_data->lookup_type == $key)
                                    <option value="{{ $key }}" selected>{{ $type }}</option>
                                    @else
                                    <option value="{{ $key }}">{{ $type }}</option>
                                    @endif

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="lookup_name">Look Up Name : <small style="color:red">*</small></label>
                                <input type="text" class="form-control" id="txtLookupName" name="txtLookupName" title="Lookup Name" placeholder="Lookup Name" value="{{ !empty($lookup_data)? $lookup_data->lookup_name:'' }}" tabindex="">
                            </div>
                            <div class="form-group">
                                <label for="cmbLookupStatus">Status
                                    <small style="color:red">*</small>
                                </label>
                                <select name="cmbLookupStatus" id="cmbLookupStatus" class="form-control"
                                style="width: 100%;" required="required" aria-hidden="true">
                                <option value="">Select</option>
                                <option value="1" {{ !empty($lookup_data)?($lookup_data->lookup_row_status == 1)? 'selected="selected"':'':'' }}>Active</option>
                                <option value="0" {{ !empty($lookup_data)?($lookup_data->lookup_row_status == 0)? 'selected="selected"':'':'' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal-footer">
    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-success btnSaveUpdate" data-response-action="{{ route('lookup_list') }}">{{ isset($lookup_data)? 'Update Look Up':'Save Look Up' }}</button>
    <span class="msg"></span>
</div>
</form>
<script>
    $('.select2').select2();
</script>