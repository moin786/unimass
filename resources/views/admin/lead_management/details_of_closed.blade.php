@extends('admin.layouts.app')

@push('css_lib')

    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Details of Closed </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Details</a></li>
            <li class="active">Details of Closed</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-body table-responsive">
                        <table id="dataTable_6" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="" title="Customer Name">Customer Name</th>
                                    <th style="" title="Mobile">Mobile</th>
                                    <th style="" title="Project">Project</th>
                                    <th style="" title="Agent">Agent</th>
                                    <th style="" class="text-center" title="Closed Date">Closed Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr role="row">
                                    <td>Shikin Shahari</td>
                                    <td>+880 1523485621</td>
                                    <td>Project-1</td>
                                    <td>Agent-1</td>
                                    <td class="text-center"><?php echo date('d-m-Y'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
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
        //Initialize Select2 Elements
        $('.select2').select2();
        //Date picker
        $('.date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
    </script>
    <script>
        $(function () {
            $('#dataTable_6').DataTable()
        })
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

