@extends('admin.layouts.app')

@push('css_lib')

    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Settings </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setting</a></li>
            <li class="active">User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <a href="#" type="button" class="btn bg-purple pull-right" data-toggle="modal" data-target="#add_user"> <i class="fa fa-plus" style="font-size:12px;"></i> Add User</a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="add_user" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header" style="background-color:#6b66c6; color:#fff">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                                </div>
                                <div class="modal-body">
                                    <section class="content">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <label for="lookup_id">User ID : <small style="color:red">*</small></label>
                                                                <input type="text" class="form-control" id="lookup_id" name="lookup_id" value="" title="Lookup Name" placeholder="Lookup Name" tabindex="">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="lookup_name">User Name : <small style="color:red">*</small></label>
                                                                <input type="text" class="form-control" id="lookup_name" name="lookup_name" value="" title="Lookup Name" placeholder="Lookup Name" tabindex="">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Role : <small style="color:red">*</small></label>
                                                                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
                                                                  <option selected="selected"> Select User Role</option>
                                                                  <option>Role-1</option>
                                                                  <option>Role-2</option>
                                                                  <option>Role-3</option>
                                                                  <option>Role-4</option>
                                                                  <option>Role-5</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success">Save User</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="min-width:100px">SL#</th>
                                    <th style="min-width:100px">ID</th>
                                    <th style="min-width:100px" class="text-center">User Name</th>
                                    <th style="min-width:100px" class="text-center">User Full Name</th>
                                    <th style="min-width:100px" class="text-center">Employee ID</th>
                                    <th style="min-width:100px" class="text-center">Role</th>
                                    <th style="min-width:100px" class="text-center">Active</th>
                                    <th style="min-width:80px" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>01</td>
                                    <td>User/ID-52</td>
                                    <td class="text-center">User Name-52</td>
                                    <td class="text-center">User Full Name-52</td>
                                    <td class="text-center">Emp/ID-52</td>
                                    <td class="text-center">Role-23</td>
                                    <td class="text-center"><span class="btn btn-block bg-green">Active</span></td>
                                    <td class="text-center" data-toggle="modal" data-target="#add_user"><a class="btn bg-purple btn-xs" href="#"><i class="fa fa-pencil"></i></a></td>
                                </tr>

                                <tr>
                                    <td>02</td>
                                    <td>User/ID-55</td>
                                    <td class="text-center">User Name-55</td>
                                    <td class="text-center">User Full Name-55</td>
                                    <td class="text-center">Emp/ID-52</td>
                                    <td class="text-center">Role-23</td>
                                    <td class="text-center"><span class="btn btn-block bg-green">Active</span></td>
                                    <td class="text-center" data-toggle="modal" data-target="#add_user"><a class="btn bg-purple btn-xs" href="#"><i class="fa fa-pencil"></i></a></td>
                                </tr>

                                <tr>
                                    <td>03</td>
                                    <td>User/ID-57</td>
                                    <td class="text-center">User Name-57</td>
                                    <td class="text-center">User Full Name-57</td>
                                    <td class="text-center">Emp/ID-52</td>
                                    <td class="text-center">Role-23</td>
                                    <td class="text-center"><span class="btn btn-block bg-green">Active</span></td>
                                    <td class="text-center" data-toggle="modal" data-target="#add_user"><a class="btn bg-purple btn-xs" href="#"><i class="fa fa-pencil"></i></a></td>
                                </tr>

                            </tbody>
                        </table>
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
    <!-- Select2 -->
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        //Date picker
        $('.date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });

        $(function () {
            $('#example1').DataTable()
            $('#example2').DataTable({
                'paging'      : true,
                'lengthChange': false,
                'searching'   : false,
                'ordering'    : false,
                'info'        : true,
                'autoWidth'   : false
            });
        });

        //Initialize Select2 Elements
        $('.select2').select2();
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif
@endpush

