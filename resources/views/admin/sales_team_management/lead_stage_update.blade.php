@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Lead Stage Update</h1>

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Sales Team Management</a></li>
            <li class="active">Lead Stage Update</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_details" class="content">
        <div class="row">
            <div class="col-xs-12">
                
                {{-- Lead Stage Update Table --}}

                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Lead Stage Update</h3>
                    </div>
                    <div class="box-body">
                        <form action="">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Lead ID :</label>
                                                <input type="text" class="form-control" id="stg_lead_id" name="stg_lead_id" value="" title="" readonly="readonly" readonly="readonly" placeholder="Lead-1546"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Customer :</label>
                                                <input type="text" class="form-control" id="stg_sold_customer" name="stg_sold_customer" value="" title=""  readonly="readonly" placeholder="Customer"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Category :</label>
                                                <input type="text" class="form-control" id="stg_sold_category" name="stg_sold_category" value="" title="" readonly="readonly" placeholder="Category"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Area :</label>
                                                <input type="text" class="form-control" id="stg_sold_area" name="stg_sold_area" value="" title="" readonly="readonly" placeholder="Area"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Flat :</label>
                                                <input type="text" class="form-control" id="stg_sold_flat" name="stg_sold_flat" value="" title="" readonly="readonly" placeholder="Flat"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Sales Agent :</label>
                                                <input type="text" class="form-control" id="stg_sales_agent" name="stg_sales_agent" value="" title="" readonly="readonly" placeholder="Sales Agent"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Current Stage :</label>
                                                <input type="text" class="form-control" id="current_stage" name="current_stage" value="" title="" readonly="readonly" placeholder="Current Stage"/>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>New Stage :</label>
                                                <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" aria-hidden="true">
                                                  <option selected="selected">Please Select New Stage</option>
                                                  <option>Alaska</option>
                                                  <option>Delaware</option>
                                                  <option>Tennessee</option>
                                                  <option>Texas</option>
                                                  <option>Washington</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8"></div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-7 pull-right">
                                                    <button type="button" class="btn btn-block bg-green">Update Stage</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
{{-- MODAL --}}
{{-- <div class="modal fade" id="view_orderlist_details" data-backdrop="static" data-keyboard="false" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-purple">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Order Details</h4>
            </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table id="datatable1" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="min-width:400px;">Item Name</th>
                        <th style="min-width:100px;" class="text-center">Qty</th>
                        <th style="min-width:130px;" class="text-right">Rate</th>
                        <th style="min-width:130px;" class="text-right">Value</th>
                    </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>Light Blue Sky</td>
                            <td class="text-center">200 [pcs]</td>
                            <td class="text-right">344.00</td>
                            <td class="text-right">68,800.00</td>
                        </tr>

                        <tr>
                            <td>Light Gree Black</td>
                            <td class="text-center">250 [pcs]</td>
                            <td class="text-right">125.00</td>
                            <td class="text-right">31,250.00</td>
                        </tr>

                        <tr>
                            <td rowspan="2">N/A</td>
                            <td colspan="2" class="text-right"> <strong>Total :</strong></td>
                            <td class="text-right">99,250.00</td>
                        </tr>

                        <tr>
                            <td colspan="2" class="text-right"><strong>Local Currency :</strong> </td>
                            <td class="text-right">99,250.00</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn bg-red pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn bg-green pull-right">Save changes</button>
        </div>
        </div>
    <!-- /.modal-content -->
    </div>
  <!-- /.modal-dialog -->
</div> --}}

@endsection

@push('js_custom')
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function () {
            $('#datatable1').DataTable();
            $('#datatable2').DataTable();
            $('#datatable3').DataTable();

            // $('#datatable2').DataTable({
            //     'paging'      : true,
            //     'lengthChange': false,
            //     'searching'   : false,
            //     'ordering'    : false,
            //     'info'        : true,
            //     'autoWidth'   : false
            // });

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
                todayBtn: true,
                todayHighlight: true
            });

             {{--var thisValue = 2;--}}
             {{--var url = "{{route('category.edit', ':id')}}".replace(':id', thisValue);--}}
             {{--alert(url);--}}

        $(".order_list_delete").click(function(){
            alert("Want to delete this row?");
        });

        });

        //iCheck for checkbox and radio inputs
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue'
        });




    </script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif
@endpush
