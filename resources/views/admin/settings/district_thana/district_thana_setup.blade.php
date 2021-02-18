@extends('admin.layouts.app')

@push('css_lib')

<link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>District/Thana Setup</h1>

  <ol class="breadcrumb">
    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Admin</a></li>
    <li><a href="#">Settings</a></li>
    <li class="active">District / Thana</li>
  </ol>
</section>

<!-- Main content -->
<section id="product_category" class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header text-right">
          <span class="btn bg-purple btn-sm pull-right create_modal" data-modal="common-modal-sm" data-action="{{ route('add_district_thana_popup') }}" data-title="Create District And Thana" title="Create District And Thana"><i class="fa fa-plus" style="font-size:12px;"></i> Create New District/Thana</span>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive" id="list-body">
          <div class="col-md-6">
            <div class="box">
              <div class="box-header">District List</div>
              <div class="box-body">                
               @include('admin.settings.district_thana.district_list') 
             </div>
           </div>
         </div>
         <div class="col-md-6">
          <div class="box">
            <div class="box-header">Thana List</div>
            <div class="box-body">                
             @include('admin.settings.district_thana.thana_list') 
           </div>
         </div>
       </div>

     </div>
     <!-- /.box-body -->
   </div>
   <!-- /.box -->
 </div>
</div>
</section>
<!-- /.content -->
@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush
@push('js_custom')
<script>
  $(function () {
        //$('.data-table').DataTable();
        $('.data-table').dataTable( {
          "columnDefs": [
          { "width": "10px", "targets": 0 }
          ],
          "pagingType": "first_last_numbers"
        } );
      });
    </script>
    @endpush
