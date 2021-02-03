@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Dispatch Reports </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Report</a></li>
            <li class="active">Dispatch Reports</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class=" col-md-offset-3 col-md-6">
                            <div class="form-group">
                                <div class="input-daterange input-group mt-20" id="datepicker">
                                    <span class="input-group-addon date_addon">From</span>
                                    <input type="text" class="input-sm form-control from_date" name="start" placeholder="From Date" />
                                    <span class="input-group-addon date_addon">To</span>
                                    <input type="text" class="input-sm form-control to_date" name="end" placeholder="To Date" />
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="dispatch_cod_table" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="min-width: 20px;">SL#</th>
                                <th style="min-width: 50px;">Date</th>
                                <th style="min-width: 100px;">Dispatch By</th>
                                <th style="min-width: 100px;">Sales Agent</th>
                                <th style="min-width: 70px;">Order No.</th>
                                <th style="min-width: 110px;">Customer</th>
                                <th style="min-width: 145px;">Tracking No./Collect By</th>
                                <th style="min-width: 90px;">Carrier</th>
                                <th style="min-width: 90px;">Total Items</th>
                                <th style="min-width: 70px;" class="text-center">Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>01</td>
                                <td>25/09/19</td>
                                <td>Sunil</td>
                                <td>Sabbir</td>
                                <td>ODID-1568</td>
                                <td>Noor Azizah Ibrahim</td>
                                <td>060301647018541</td>
                                <td>City Link</td>
                                <td>03</td>
                                <td class="text-center type_income">
                                    <a href="#" title="View"><i class="fa fa-envelope-open-o"></i></a> &nbsp;
                                    <a href="#" title="Chat"><i class="fa fa-wechat"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>02</td>
                                <td>25/09/19</td>
                                <td>Sunil</td>
                                <td>Sabbir</td>
                                <td>ODID-1568</td>
                                <td>Noor Azizah Ibrahim</td>
                                <td>060301647018541</td>
                                <td>City Link</td>
                                <td>03</td>
                                <td class="text-center type_income">
                                    <a href="#" title="View"><i class="fa fa-envelope-open-o"></i></a> &nbsp;
                                    <a href="#" title="Chat"><i class="fa fa-wechat"></i></a>
                                </td>
                            </tr>

                            <tr>
                                <td>03</td>
                                <td>25/09/19</td>
                                <td>Sunil</td>
                                <td>Sabbir</td>
                                <td>ODID-1568</td>
                                <td>Noor Azizah Ibrahim</td>
                                <td>060301647018541</td>
                                <td>City Link</td>
                                <td>03</td>
                                <td class="text-center type_income">
                                    <a href="#" title="View"><i class="fa fa-envelope-open-o"></i></a> &nbsp;
                                    <a href="#" title="Chat"><i class="fa fa-wechat"></i></a>
                                </td>
                            </tr>





                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="min-width: 20px;">SL#</th>
                                <th style="min-width: 20px;">Date</th>
                                <th style="min-width: 20px;">Dispatch By</th>
                                <th style="min-width: 20px;">Sales Agent</th>
                                <th style="min-width: 20px;">Order No.</th>
                                <th style="min-width: 90px;">Customer</th>
                                <th style="min-width: 70px;">Tracking No./Collect By</th>
                                <th style="min-width: 90px;">Carrier</th>
                                <th style="min-width: 90px;">Total Items</th>
                                <th style="min-width: 70px;" class="text-center">Action</th>
                            </tr>
                            </tfoot>
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
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
@endpush

@push('js_custom')
    <script>
        $(function() {
            $('#example1').DataTable();
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': false
            });


            $('.input-phone').intlInputPhone();


            $("#alert_success").on("click", function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Do you want to continue',
                    type: 'success',
                    confirmButtonText: 'Cool'
                });

            });
        })

    </script>
@endpush

