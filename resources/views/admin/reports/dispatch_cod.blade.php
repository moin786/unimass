@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Dispatch COD </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Report</a></li>
            <li class="active">Dispatch COD</li>
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
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th style="min-width: 20px;">SL#</th>
                                <th style="min-width: 20px;">Date</th>
                                <th style="min-width: 90px;" class="text-center">User Name</th>
                                <th style="min-width: 550px;">Description</th>
                                <th style="min-width: 90px;" class="text-right">Amount</th>
                                <th style="min-width: 90px;" class="text-right">Balance</th>
                                <th style="min-width: 70px;" class="text-center">Type</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>01</td>
                                <td>25/09/19</td>
                                <td class="text-center user_name">Sunil</td>
                                <td>COD payment recieve from Aniza Albar(cust-1604), Order- ODID-1673,payment- PAYID-5624</td>
                                <td class="amount">RM 554.00</td>
                                <td class="balance">RM 1554.00</td>
                                <td class="text-center type_income">Income</td>
                            </tr>

                            <tr>
                                <td>01</td>
                                <td>25/09/19</td>
                                <td class="text-center user_name">Sharif</td>
                                <td>COD payment recieve from Aniza Albar(cust-1604), Order- ODID-1673,payment- PAYID-5624</td>
                                <td class="amount">RM 554.00</td>
                                <td class="balance">RM 1554.00</td>
                                <td class="text-center type_income">Income</td>
                            </tr>

                            <tr>
                                <td>01</td>
                                <td>25/09/19</td>
                                <td class="text-center user_name">Sunil</td>
                                <td>COD payment recieve from Aniza Albar(cust-1604), Order- ODID-1673,payment- PAYID-5624</td>
                                <td class="amount">RM 554.00</td>
                                <td class="balance">RM 1554.00</td>
                                <td class="text-center type_income">Income</td>
                            </tr>

                            <tr>
                                <td>01</td>
                                <td>25/09/19</td>
                                <td class="text-center user_name">Mahin</td>
                                <td>COD payment recieve from Aniza Albar(cust-1604), Order- ODID-1673,payment- PAYID-5624</td>
                                <td class="amount">RM 554.00</td>
                                <td class="balance">RM 1554.00</td>
                                <td class="text-center type_income">Income</td>
                            </tr>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="min-width: 20px;">SL#</th>
                                <th style="min-width: 20px;">Date</th>
                                <th style="min-width: 90px;" class="text-center">User Name</th>
                                <th style="min-width: 550px;">Description</th>
                                <th style="min-width: 90px;" class="text-right">Amount</th>
                                <th style="min-width: 90px;" class="text-right">Balance</th>
                                <th style="min-width: 70px;" class="text-center">Type</th>
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
