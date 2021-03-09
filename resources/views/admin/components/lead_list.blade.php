@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        .istallment_percent {
            position: relative;
            right: 70px;
            top: 25px;
            z-index: 999;
        }
    </style>
@endpush

@section('content')
    @php
    $ses_other_user_id = Session::get('user.ses_other_user_pk_no');
    $ses_other_user_name = Session::get('user.ses_other_full_name');
    $role_id = Session::get('user.ses_role_lookup_pk_no');

    $is_ses_hod = Session::get('user.is_ses_hod');
    $is_ses_hot = Session::get('user.is_ses_hot');
    $is_team_leader = Session::get('user.is_team_leader');
    $status = '';
    @endphp
    <!-- Content Header (Page header) -->
    <section class="content-header">
        @if ($ses_other_user_id == '')
            <h1>{{ $page_title }} List</h1>
        @else
            <h1>
                Lead List :: <span class="text-danger">{{ $ses_other_user_name }}</span>
                | <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard', $ses_other_user_id) }}">Back</a>
                | <a class="btn btn-xs btn-danger" href="{{ route('admin.dashboard') }}">Back To My Dashboard</a>
            </h1>
        @endif

        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Dashboard</a></li>
            <li class="active">{{ $page_title }} List</li>
        </ol>
    </section>

    <section id="product_details" class="content">
        <div class="row">
            <div class="col-sm-10">
                <div class="box box-info">
                    <div class="box-body  table-responsive" id="list-body">
                        <table id="datatable" class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    @include('admin.components.lead_list_table_header')
                                    @if ($type == 7)
                                        <td>Sold Date</td>
                                        <td width="100">Cost</td>
                                    @endif
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (!empty($lead_data))
                                    @php $i=1; @endphp
                                    @foreach ($lead_data as $row)
                                        @php
                                            $agent_name = '';
                                            if ($row->lead_sales_agent_pk_no == 0 && $row->lead_cluster_head_pk_no > 0) {
                                                $agent_name = $row->lead_cluster_head_name;
                                            }
                                            if ($row->lead_sales_agent_pk_no > 0) {
                                                $agent_name = $row->lead_sales_agent_name;
                                            }
                                        @endphp
                                        <tr>
                                            @include('admin.components.lead_list_table')
                                            @if ($type == 7)
                                                <td> {{ date('d/m/Y', strtotime($row->lead_sold_date_manual)) }}</td>
                                                <td>
                                                    <div>
                                                        <strong>F:</strong> {{ $row->lead_sold_flatcost }}
                                                    </div>
                                                    <div>
                                                        <strong>U:</strong>{{ $row->lead_sold_utilitycost }}
                                                    </div>
                                                    <div>
                                                        <strong>P:</strong>{{ $row->lead_sold_parkingcost }}
                                                    </div>
                                                    <div>
                                                        <strong>R:</strong>{{ $row->lead_reserve_money }}
                                                    </div>
                                                </td>
                                            @endif
                                            <td class="text-center" width="100px;">
                                                <span class="btn bg-info btn-xs lead-view" data-title="Lead Details"
                                                    title="Lead Details" data-id="{{ $row->lead_pk_no }}"
                                                    data-action="{{ route('lead_view', $row->lead_pk_no) }}"><i
                                                        class="fa fa-eye"></i></span>

                                                @if ($user_type == 1)
                                                    @if ($row->created_by == $ses_user_id && $row->lead_cluster_head_pk_no == 0)
                                                        @if ($type != 7)
                                                            <span class="btn bg-info btn-xs lead-edit"
                                                                data-title="Lead Edit" title="Lead Edit"
                                                                data-id="{{ $row->lead_pk_no }}"
                                                                data-action="{{ route('lead.edit', $row->lead_pk_no) }}"><i
                                                                    class="fa fa-edit"></i></span>
                                                        @endif
                                                    @endif
                                                @else

                                                    @if ($row->lead_sales_agent_pk_no == $ses_user_id || $row->lead_cluster_head_pk_no == $ses_user_id || $row->created_by == $ses_user_id)
                                                        @if ($is_ch == 1 && $row->lead_sales_agent_pk_no == 0)
                                                            @if ($type != 7)
                                                                <span class="btn bg-info btn-xs lead-edit"
                                                                    data-title="Lead Edit" title="Lead Edit"
                                                                    data-id="{{ $row->lead_pk_no }}"
                                                                    data-action="{{ route('lead.edit', $row->lead_pk_no) }}"><i
                                                                        class="fa fa-edit"></i></span>
                                                            @endif
                                                        @elseif($row->created_by == $ses_user_id ||
                                                            $row->lead_sales_agent_pk_no == $ses_user_id)
                                                            @if ($type != 7)
                                                                <span class="btn bg-info btn-xs lead-edit"
                                                                    data-title="Lead Edit" title="Lead Edit"
                                                                    data-id="{{ $row->lead_pk_no }}"
                                                                    data-action="{{ route('lead.edit', $row->lead_pk_no) }}"><i
                                                                        class="fa fa-edit"></i></span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($role_id == 77 && $is_ses_hod == 0 && $is_ses_hot == 0 && $is_team_leader == 0)
                                                    @if ($type != 7 && $type != 15 && $type != 6)
                                                        <span class="btn bg-info btn-xs next-followup"
                                                            data-title="Lead Followup" title="Lead Followup"
                                                            data-id="{{ $row->lead_pk_no }}"
                                                            data-action="{{ route('lead_follow_up_from_dashboard', [$row->lead_pk_no, $type]) }}">
                                                            <i class="fa fa-list"></i></span>
                                                        @if ($row->is_note_sheet_approved == 1)
                                                            <span class="btn bg-info btn-xs lead-sold"
                                                                data-title="Lead Sold" title="Lead Sold"
                                                                data-id="{{ $row->lead_pk_no }}"
                                                                data-action="{{ route('lead_sold', $row->lead_pk_no) }}"><i
                                                                    class="fa fa-handshake-o"></i></span>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($type != 6 && $type != 7 && $type != 15 && $user_type != 1)


                                                @endif

                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center text-danger">No Data Found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @if ($ses_other_user_id == '' && ($is_ses_hod != 0 || $is_ses_hot != 0 || $is_team_leader != 0 || $userRoleID == 551))
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="teamname">Team</label>
                        <select name="teamname" id="teamname" data-action="{{ route('get_team_users') }}"
                            class="form-control" style="width: 100%;" required="required" aria-hidden="true">
                            <option value="0">Select</option>
                            @foreach ($team_arr as $key => $team)
                                <option value="{{ $key }}">{{ $team }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="hod" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">HOD&nbsp;</span>
                        </label>
                        <select name="team_name" id="team_hod" class="form-control" style="width: 100%;" required="required"
                            aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    {{-- <div class="form-group">
    <label class="pull-left" style="cursor: pointer;">
        <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
        style="position: relative; margin-right:10px; margin-bottom:6px;">
        <input type="radio" id="user_type" value="hot" name="user_type" class="flat-red"
        style="position: absolute; opacity: 0;">
        <ins class="iCheck-helper"
        style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
    </div>
    <span style="font-size:13px; margin-top:-5px;">CRC&nbsp;</span>
</label>
<select name="team_hot" id="team_hot" class="form-control" style="width: 100%;"
required="required" aria-hidden="true" {{ $status }}>
<option value="0">Select</option>
</select>
</div> --}}
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="tl" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">TL&nbsp;</span>
                        </label>
                        <select name="team_tl" id="team_tl" class="form-control" style="width: 100%;" required="required"
                            aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="pull-left" style="cursor: pointer;">
                            <div class="iradio_flat-green" aria-checked="false" aria-disabled="false"
                                style="position: relative; margin-right:10px; margin-bottom:6px;">
                                <input type="radio" id="user_type" value="agent" name="user_type" class="flat-red"
                                    style="position: absolute; opacity: 0;">
                                <ins class="iCheck-helper"
                                    style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                            </div>
                            <span style="font-size:13px; margin-top:-5px;">Sales Person&nbsp;</span>
                        </label>
                        <select name="team_agent" id="team_agent" class="form-control" style="width: 100%;"
                            required="required" aria-hidden="true" {{ $status }}>
                            <option value="0">Select</option>
                        </select>
                    </div>
                    <div>
                        <span id="switch_dashboard" data-action="{{ route('admin.dashboard') }}"
                            class="btn bg-green btn-xs">View Dashboard</span>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('js_custom')
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>

    <script>
         var delay = (function(){
            var timer = 0;
            return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
            };
        })();
        $(function() {
            
            //Flat red color scheme for iCheck
            $('input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
            $('#datatable').DataTable({
                "order": false,
                bSort: false,
                "pageLength": 30
            });
            
            

            $(document).on("change", "#teamname", function(e) {
                blockUI();
                var team_id = $(this).val();
                var action = $(this).attr('data-action');
                $.ajax({
                    data: {
                        team_id: team_id
                    },
                    url: action,
                    type: "post",
                    beforeSend: function() {
                        $("#team_hod").html("");
                        $("#team_hot").html("");
                        $("#team_tl").html("");
                        $("#team_agent").html("");
                    },
                    success: function(data) {
                        data = $.parseJSON(data);

                        var hod_list = hot_list = tl_list = agent_list = "";
                        $.each(data.hod_arr, function(i, item) {
                            hod_list += "<option value='" + i + "'>" + item +
                                "</option>";
                        });
                        $("#team_hod").append(hod_list);

                        $.each(data.hot_arr, function(i, item) {
                            hot_list += "<option value='" + i + "'>" + item +
                                "</option>";
                        });
                        $("#team_hot").append(hot_list);

                        $.each(data.tl_arr, function(i, item) {
                            tl_list += "<option value='" + i + "'>" + item +
                                "</option>";
                        });
                        $("#team_tl").append(tl_list);

                        $.each(data.agent_arr, function(i, item) {
                            agent_list += "<option value='" + i + "'>" + item +
                                "</option>";
                        });
                        $("#team_agent").append(agent_list);
                    }

                });
                $.unblockUI();
            });

            $(document).on("click", "#switch_dashboard", function() {
                var action = $(this).attr("data-action");
                var selected_user = $('input[name="user_type"]:checked');
                if (!selected_user.val()) {
                    alert('You did not select any user.');
                } else {
                    var selected_user_id = selected_user.parents("label").siblings("select").val();
                    window.location.href = action + "/" + selected_user_id;
                }


            });

            $(document).on("click", ".next-followup", function(e) {
                var id = $(this).attr("data-id");
                var action = $(this).attr("data-action");
                var title = $(this).attr("data-title");

                $.ajax({
                    url: action,
                    type: "get",
                    beforeSend: function() {
                        blockUI();
                        $('.common-modal').modal('show');
                        $('.common-modal .modal-body').html("Loading...");
                        $('.common-modal .modal-title').html(title);
                    },
                    success: function(data) {
                        $.unblockUI();
                        $('.common-modal .modal-body').html(data);
                        var date = new Date();
                        date.setDate(date.getDate());
                        $('#txt_followup_date').datepicker({
                            startDate: date,
                            todayHighlight: true
                        });
                        $('#txt_followup_date_time').timepicker();

                        $('#meeting_followup_date').datepicker({
                            startDate: date,
                            todayHighlight: true
                        });
                        $('#txt_meeting_visit_done_dt').datepicker({
                            startDate: date,
                            todayHighlight: true
                        });
                        $('#meeting_followup_date_time').timepicker();

                    }

                });
            });

            $(document).on("click", ".btnSaveUpdateFollowup", function(e) {
                e.preventDefault();
                var formID = $(this).parents("form").attr("id");
                var formAction = $(this).parents("form").attr("action");
                var formMethod = $(this).parents("form").attr("method");
                var responseAction = $(this).attr("data-response-action");
                var tab_type = $("ul#tab_container li.active a").attr("data-type");
                var validation_check = 0;
                var validation_array = [];

                $('.required').each(function() {
                    if ($(this).val() == '' || $(this).val() == 0) {
                        validation_array.push(1);
                        $(this).attr('style', 'border:2px solid #D44F49 !important');
                    }
                });

                if (validation_array.length > 0) {
                    toastr.error('You must fill up required fields', 'Validation Error');
                    return;
                }

                $.ajax({
                    data: $('#' + formID).serialize(),
                    url: formAction,
                    type: formMethod,
                    beforeSend: function() {
                        blockUI();
                    },
                    success: function(data) {
                        $.unblockUI();
                        if (data.type == 'error') {
                            toastr.error(data.message, data.title);
                        } else {
                            toastr.success(data.message, data.title);
                            if (responseAction) {
                                window.location.href = responseAction;
                            }
                        }

                    },
                    error: function(data) {
                        var errors = jQuery.parseJSON(data.responseText).errors;
                        for (messages in errors) {
                            var field_name = $("#" + messages).siblings("label").html();
                            error_messages = field_name + ' ' + errors[messages];
                            toastr.error(data.message, error_messages);
                        }
                        $.unblockUI();
                    }
                });
            });
            $(document).on("click", ".lead-sold", function(e) {
                var id = $(this).attr("data-id");
                var action = $(this).attr("data-action");
                var title = $(this).attr("data-title");

                $.ajax({
                    url: action,
                    type: "get",
                    beforeSend: function() {
                        blockUI();
                        $('.common-modal').modal('show');
                        $('.common-modal .modal-body').html("Loading...");
                        $('.common-modal .modal-title').html(title);
                    },
                    success: function(data) {
                        $.unblockUI();
                        $('.common-modal .modal-body').html(data);
                        $('#date_of_sold').datepicker();
                    }

                });
            });

           let bk = 1;
           $('body').on('click','.booking_schegenerate', function(){
                    
                    $('.generate_booking').append(`
                        <div class="booking_root" data-rootid="${bk}">
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text">Date</span>
                                    <input type="text" name="book_schedule_date_save[]" class="form-control datepicker required" required placeholder="Date">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text">Description</span>
                                    <input type="text" name="booking_installment[]" id="booking_installment${bk}" class="form-control booking_installment required" placeholder="Description">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text">Amount</span>
                                    <input type="text" name="booking_amount[]" id="booking_amount${bk}" class="form-control booking_amount required" placeholder="Amount">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <div class="input-group-text text-center istallment_percent">%</div>
                                    <input type="text" name="booking_percent_of_first_installment[]" id="booking_percent_of_first_installment${bk}" class="form-control booking_percent_of_first_installment required" placeholder="%">
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button type="button" class="btn btn-block bg-green amount_caculate" id="amount_calculate${bk}" data-rootid="${bk}" style="margin-top:16px;">Calculate</button>
                                    
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="input-group">
                                    
                                    <button type="button" class="btn btn-block bg-green booking_cancel" data-rootid="${bk}" style="margin-top:16px;">x</button>
                                </div>
                            </div>
                        </div>
                    `);
                

                
                bk++;
                $('.datepicker').datepicker();
        });

           
           
           
           $('body').on('click','.booking_cancel', function(){
               let rootid = $(this).data('rootid');
               let rootdiv = $(this).parent().parent().parent();
               let divid = $(this).parent().parent().parent().data('rootid');
               console.log(divid);
               //alert(`${rootid} ${divid}`)
               if(rootid == divid) {
                rootdiv.remove();
                
                bk = bk-1;
               }

               delay(() =>{
                    let totalamount = 0;
                    let totalpercent = 0;
                    $('input[name="booking_amount[]"]').each(function(){
                        totalamount += parseFloat($(this).val());
                            $('#total_amount').val(totalamount);
                    });

                    
                    $('input[name="booking_percent_of_first_installment[]"]').each(function(){
                        totalpercent += parseFloat($(this).val());
                            $('#total_percent').val(totalpercent);
                    });
                }, 1000);
           });

           let totalamount = 0;
           let totalpercent = 0;
           let bookingamount  = 0;
           let totalbookingpercent = 0;
           $('body').on('click','.amount_caculate', function(){
                let rootid = $(this).data('rootid');
                
                let checkleadamount = 0;
                let leadamount = parseFloat($('#grand-total').val());
                let inputtotalamount = parseFloat($('#total_amount').val());

                

                if ($('#booking_percent_of_first_installment'+rootid).val() != '') {
                    let amount = parseFloat($('#grand-total').val())*parseInt($('#booking_percent_of_first_installment'+rootid).val())/100;
                    
                    $('#booking_amount'+rootid).val(amount);

                    totalamount += amount;

                    $('#total_amount').val(totalamount);

                    totalbookingpercent += parseFloat($('#booking_percent_of_first_installment'+rootid).val());
                    $('#total_percent').val(totalbookingpercent);

                    leadamount = parseFloat($('#grand-total').val());
                    inputtotalamount = parseFloat($('#total_amount').val());

                    if (inputtotalamount > leadamount) {
                        alert("Schedule amount can not grater than grand Total");
                        totalamount -= amount;
                        totalbookingpercent -= parseFloat($('#booking_percent_of_first_installment'+rootid).val());
                        $('#total_amount').val(totalamount-amount);
                        $('#total_percent').val(totalbookingpercent-parseFloat($('#booking_percent_of_first_installment'+rootid).val()));
                        $('#booking_amount'+rootid).val('');
                        $('#booking_percent_of_first_installment'+rootid).val('');
                        return false;
                    }
                }

                if($('#booking_amount'+rootid).val() != '') {
                    let thisval = $(this).val();
                    let instpercent = parseInt($('#booking_amount'+rootid).val())*100/parseFloat($('#grand-total').val());
                    $('#booking_percent_of_first_installment'+rootid).val(instpercent);

                    totalpercent += instpercent;

                    $('#total_percent').val(totalpercent);

                    

                    bookingamount += parseFloat($('#booking_amount'+rootid).val());
                    $('#total_amount').val(bookingamount);

                    leadamount = parseFloat($('#grand-total').val());
                    inputtotalamount = parseFloat($('#total_amount').val());

                    if (inputtotalamount > leadamount) {
                        alert("Schedule amount can not grater than grand Total");
                        totalpercent -= instpercent;
                        bookingamount -= parseFloat($('#booking_amount'+rootid).val());
                        $('#total_amount').val(bookingamount-parseFloat($('#booking_amount'+rootid).val()));
                        $('#total_percent').val(totalpercent-instpercent);
                        $('#booking_amount'+rootid).val('');
                        $('#booking_percent_of_first_installment'+rootid).val('');
                        return false;
                    }
                }
                
            });

           


            $('body').on('focusout','#percent_of_first_installment', function(){
                $('#amount').attr('disabled',true);
                if($('#installment').val() == '') {
                    alert('No.of Installment cant not left empty');
                    $('#installment').focus();
                    return false;
                }
                
                delay(() =>{
                    if ($(this).val() == '') {
                        return false;
                    }
                    let thisval = $(this).val();
                    let amount = parseFloat($('#grand-total').val())*parseInt($(this).val())/100;
                    $('#amount').val(amount);
                    let fitstistallment = 1;
                    let noofinstallment = parseInt($('#installment').val());
                    let remainingistallment = ((noofinstallment+1)-fitstistallment)
                    let istallamount = [];
                    let second_installment = 0;
                    let other_installment = 0;
                    var istallment_obj = [];
                    var validation_array = [];
                    
                    let gulo = '';

                    istallment_obj.push({
                        installment: '1st Istallment',
                        amount: parseFloat(amount.toFixed(3)),
                        percent_of_total_apt_price: parseInt($(this).val())

                    })

                    istallamount.push(remainingistallment);
                    for(i = 1; i<=istallamount; i++) {
                        if (i == 1) {
                            continue;
                        }
                        if (i == 2) {
                            second_installment = (100-$(this).val())/(noofinstallment-1);
                            amount = $('#grand-total').val()*second_installment/100;
                            istallment_obj.push({
                                installment: '2nd Istallment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }

                        if (i == 3) {
                            gulo = 'rd';
                        } 
                        
                        if (i == 2) {
                            gulo = 'nd';
                        }

                        if (i != 3 && i!= 2) {
                            gulo = 'th';
                        }

                        if (i != 2) {
                            istallment_obj.push({
                                installment: i+gulo+ ' installment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }
                    }

                    //$(this).attr('disabled',true);
                    //$('#amount').attr('disabled',true);
                    $('body').on('click','.schegenerate', function(){
                        $('.required').each(function() {
                            if($(this).val() == '' || $(this).val() == 0) {
                                validation_array.push(1);
                                $(this).attr('style', 'border:2px solid #D44F49 !important');
                            }
                        });

                        if(validation_array.length > 0) {
                            toastr.error('You must fill up required fields', 'Validation Error');
                            return;
                        }
                        $(this).attr('disabled',true);
                        istallment_obj.forEach(function(installment) {
                            let generated_schedule = $('.generated_schedule');
                            
                            generated_schedule.append(`
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Date</span>
                                        <input type="text" name="schedule_date_save[]" class="form-control datepicker required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Installments <span class="text-danger"> *</span></span>
                                        <input type="text" name="installment_save[]" value="${installment.installment}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Amount <span class="text-danger"> *</span></span>
                                        <input type="text" name="amount_save[]" value="${installment.amount}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-text text-center istallment_percent">% <span class="text-danger"> *</span></div>
                                        <input type="text" name="percent_of_first_installment_save[]" value="${installment.percent_of_total_apt_price}" class="form-control required" placeholder="Installment" required>
                                    </div>
                                </div>
                            `);

                            $('.datepicker').datepicker();
                        });
                    });
                },1000);
            });


            $('body').on('focusout','#amount', function(){
                $('#percent_of_first_installment').attr('disabled',true);
                if($('#installment').val() == '') {
                    alert('No.of Installment cant not left empty');
                    $('#installment').focus();
                    return false;
                }
                
                delay(() =>{
                    if ($(this).val() == '') {
                        return false;
                    }
                    let thisval = $(this).val();
                    let instpercent = parseInt($(this).val())*100/parseFloat($('#grand-total').val());
                    $('#percent_of_first_installment').val(instpercent);
                    let fitstistallment = 1;
                    let amount = parseInt($(this).val());
                    let noofinstallment = parseInt($('#installment').val());
                    let remainingistallment = ((noofinstallment+1)-fitstistallment)
                    let istallamount = [];
                    let second_installment = 0;
                    let other_installment = 0;
                    var istallment_obj = [];
                    var validation_array = [];
                    
                    let gulo = '';

                    istallment_obj.push({
                        installment: '1st Istallment',
                        amount: parseFloat(amount.toFixed(3)),
                        percent_of_total_apt_price: parseFloat($('#percent_of_first_installment').val())

                    })

                    istallamount.push(remainingistallment);
                    for(i = 1; i<=istallamount; i++) {
                        if (i == 1) {
                            continue;
                        }
                        if (i == 2) {
                            second_installment = (100-parseFloat($('#percent_of_first_installment').val()))/(noofinstallment-1);
                            amount = $('#grand-total').val()*second_installment/100;
                            istallment_obj.push({
                                installment: '2nd Istallment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }

                        if (i == 3) {
                            gulo = 'rd';
                        } 
                        
                        if (i == 2) {
                            gulo = 'nd';
                        }

                        if (i != 3 && i!= 2) {
                            gulo = 'th';
                        }

                        if (i != 2) {
                            istallment_obj.push({
                                installment: i+gulo+ ' installment',
                                amount: parseFloat(amount.toFixed(3)),
                                percent_of_total_apt_price: second_installment.toFixed(3)

                            })
                        }
                    }

                    $('body').on('click','.schegenerate', function(){
                        $('.required').each(function() {
                            if($(this).val() == '' || $(this).val() == 0) {
                                validation_array.push(1);
                                $(this).attr('style', 'border:2px solid #D44F49 !important');
                            }
                        });

                        if(validation_array.length > 0) {
                            toastr.error('You must fill up required fields', 'Validation Error');
                            return;
                        }
                        $(this).attr('disabled',true);
                        istallment_obj.forEach(function(installment) {
                            let generated_schedule = $('.generated_schedule');
                            
                            generated_schedule.append(`
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Date</span>
                                        <input type="text" name="schedule_date_save[]" class="form-control datepicker required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Installments <span class="text-danger"> *</span></span>
                                        <input type="text" name="installment_save[]" value="${installment.installment}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text">Amount <span class="text-danger"> *</span></span>
                                        <input type="text" name="amount_save[]" value="${installment.amount}" class="form-control required" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <div class="input-group-text text-center istallment_percent">% <span class="text-danger"> *</span></div>
                                        <input type="text" name="percent_of_first_installment_save[]" value="${installment.percent_of_total_apt_price}" class="form-control required" placeholder="Installment" required>
                                    </div>
                                </div>
                            `);

                            $('.datepicker').datepicker();
                        });
                    });
                },1000);
            });

            

        });

    </script>
@endpush
