@extends('admin.layouts.app')

@push('css_lib')
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">

    <style>
        .close {
            font-size: 27px;
        }

        .modal-header {
            border-bottom: 1px solid #ccc !important;
        }
        .modal-header {
            padding: 7px 15px;
        }
        .modal-footer {
            padding: 7px 15px;
        }
    </style>

@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Pending Collection</h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Project Pending Collection</a></li>
            <li class="active">Pending Collection</li>
        </ol>
    </section>

    <section class="content">
        <div class="form-row">
            <div class="col-md-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs" id="tab_container">
                        <li class="active"><a href="#sold_lead" data-toggle="tab" data-type="1" data-action="{{ route('load_lead_pending') }}"   aria-expanded="true">Pending</a></li>
                        <li><a href="#monthly_lead" data-toggle="tab" data-type="2" data-action="{{ route('load_lead_pending') }}"   aria-expanded="true">Monthly Collection</a></li>
                        <li><a href="#monthly_lead" data-toggle="tab" data-type="3" data-action="{{ route('load_lead_pending') }}"   aria-expanded="true">Complete Collection</a></li>
                    </ul>
                    <div class="tab-content" id="list-body">
                        @include("admin.lead_management.schedule_collection.pending_collection.pending_lead_list")
                    </div>
                    <!-- /.tab-content -->
                </div>
            </div>
        </div>
    </section>


@endsection

@push('js_lib')

    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <script>
        $('.table').DataTable(
            {"ordering": false}
        );
    </script>
@endpush

@push('js_custom')

@endpush
