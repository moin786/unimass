
{{-- <form id="frmUser" action=""
method=""> --}}
<section class="content">
  <form id="frmUser" action="{{route('district-save')}}"
  method="post">
  @csrf
  <input type="hidden" id="hdnUserId" name="hdnUserId" value=" "/>
  <div class="form-row">
    <div class="col-md-9">
     <label>District Name <span class="text-danger">*</span></label>
     <input type="text" class="form-control" id="district_name" name="district_name" title="Attribute Name" placeholder="District Name" value="" required>
   </div>
   <div class="col-md-3">    
     <label for="">  </label> 		<br>			
     <button type="submit"
     class="btn bg-purple btn-md btnSaveUpdate" data-response-action="{{route('district_thana_setup')}}" >Save</button>
     <span class="msg"></span>
   </div> 
 </div>
</form>

<br>
<br>

{{-- Thana  --}}

<form id="newForm" action="{{route('thana_store')}}"
method="POST">
@csrf
<div class="row">
  <div class="col-md-12">
   <div class="form-group">
    <label>District Name <span class="text-danger">*</span></label>
    <select name="district_id" onchange="district.getThanaByDistrict(this)" id="district_id " class="form-control" aria-hidden="true">
     <option value="0">Select</option>
     @if(!empty($district))
     @foreach ($district as $name)

     <option value="{{$name->id}}">{{$name->district_name}}</option>

     @endforeach
     @endif
   </select>
 </div>
</div> 
</div>
<div class="row">
  <div class="col-md-12" id="thana_name">
   @include('admin.settings.district_thana.thana_name')
</div> 
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger btn-sm pull-left" data-dismiss="modal">Close</button>
  <button type="submit"
  class="btn bg-purple btn-sm btnSaveUpdate" data-response-action="{{route('district_thana_setup')}}" >Save</button>
  <span class="msg"></span>
</div>          

</form>
</section>


{{-- <script>
  var district = {

    getThanaByDistrict: function (thisElement) {
      var district_value = $(thisElement).val();

      $.ajax({
        url: "{{route('district.getThanaByDistrict')}}",
        type: 'POST',
        data: {district_value: district_value},
        beforeSend: function () {
          blockUI();

        },
        success: function (data) {
          $("#Thana").html(data);
        },
        complete: function () {
          $.unblockUI();
        }
      });
    },

  }

  // var district = {
  //   getThanaByDistrict:function(thisElement){
  //     var district_id = $(thisElement).val();
  //     alert(district_id);

  //     $.ajax({
  //       url:'',
  //       type:'post',
  //       data:{district_id:district_id},
  //       beforeSend:function(){
  //         blockUI();
  //       },
  //       success: function(data){
  //         $('#Thana').html(data);
  //       },
  //       complete::function(){
  //         $.unblockUI();
  //       }
  //     })
  //   }
  // }

</script> --}}