$(document).ready(function () {
	var ambit = $(document);

    // Disable Cut + Copy + Paste (input)
    // ambit.on('copy cut', function (e) {
    //     e.preventDefault(); //disable cut,copy,paste
    //     return false;
    // });

    // var ambit = $(document);

    // // Disable Contextual Menu
    // ambit.on('contextmenu', function (e) {
    //     e.preventDefault();
    //     return false;
    // });

    // // Disable Tap and Hold (jQuery Mobile)
    // ambit.on('taphold', function (e) {
    //     e.preventDefault();
    //     return false;
    // });
});

function blockUI()
{
	$.blockUI({
		message: '<i class="fa fa-gear"></i>',
		overlayCSS: {
			backgroundColor: '#1b2024',
			opacity: 0.8,
			zIndex: 999999,
			cursor: 'wait'
		},
		css: {
			border: 0,
			color: '#fff',
			padding: 0,
			zIndex: 9999999,
			backgroundColor: 'transparent'
		}
	});
}

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});

$(document).on("click",".create_modal",function (e) {
	var action = $(this).attr("data-action");
	var title = $(this).attr("data-title");
	
	var modal  = ($(this).attr("data-modal"))?$(this).attr("data-modal"):"common-modal";

	$.ajax({
		url: action,
		type: "get",
		beforeSend:function(){
			blockUI();
			/*$('.common-modal'+modal).modal('show');
			$('.common-modal .modal-body').html("Loading...");
			$('.common-modal .modal-title').html(title);*/

			$('.'+modal).modal('show');
			$('.'+modal+' .modal-body').html("<i class='fas fa-stroopwafel fa-spin'></i>");
			$('.'+modal+' .modal-title').html(title);
		},
		success: function (data) {
			$.unblockUI();
			//$('.common-modal .modal-body').html(data);
			$('.'+modal+' .modal-body').html(data);
			//$(".select2").select2();
		}

	});
});

$(document).on("keyup",".required",function (e) {
	$(this).attr('style', 'border:1px solid #aaaaaa !important');
});

$(document).on("click",".btnSaveUpdate",function (e) {
	e.preventDefault();
	var formID = $(this).parents("form").attr("id");
	var formAction = $(this).parents("form").attr("action");
	var formMethod = $(this).parents("form").attr("method");
	var responseAction = $(this).attr("data-response-action");
	var tab_type = $("ul#tab_container li.active a").attr("data-type");
	var validation_check = 0;
	var validation_array = [];

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

	$.ajax({
		data: $('#'+formID).serialize(),
		url: formAction,
		type: formMethod,
		beforeSend:function(){
			blockUI();
			//
		},
		success: function (data) {
			$.unblockUI();
			$('.generated_schedule').empty();
			$('.schegenerate').attr('disabled',false);
			if(data.type == 'error')
			{
				toastr.error(data.message, data.title);
			}
			else
			{
				toastr.success(data.message, data.title);
				$("#" + formID).find("input, textarea").not('.keep_me').val("");
				$("#" + formID).find("select").not('.keep_me').val("0");
				var responseURL = typeof data.redirectPage !== "undefined" ? data.redirectPage :"" ;
				if(responseURL != ""){
					$(".common-modal").modal('hide');
					window.location = data.redirectPage;
				}
				else{
					if(responseAction)
					{
						var tab = $("ul#tab_container li.active a").attr("href");
						$.ajax({
							url: responseAction,
							type: "post",
							data: { tab_type: tab_type },
							beforeSend:function(){
								$('.table').DataTable().destroy();
							},
							success: function (response_data) {
								$('#list-body').html(response_data);							
								$(tab).addClass("active");
								$('.table').DataTable();
							}
						});
					}
				}
			}

		},
		error: function (data) {
			var errors = jQuery.parseJSON(data.responseText).errors;
			for (messages in errors) {
				var field_name = $("#"+messages).siblings("label").html();
				error_messages =  field_name + ' ' + errors[messages];
				toastr.error(data.message, error_messages);
			}
			$.unblockUI();
		}
	});
});

$(document).on("click",".update_modal",function (e) {
	var id = $(this).attr("data-id");
	var action = $(this).attr("data-action");
	var title = $(this).attr("data-title");

	$.ajax({
		url: action,
		type: "get",
		beforeSend:function(){
			blockUI();
			$('.common-modal').modal('show');
			$('.common-modal .modal-body').html("Loading...");
			$('.common-modal .modal-title').html(title);
		},
		success: function (data) {
			$.unblockUI();
			$('.common-modal .modal-body').html(data);
		}

	});
});


$(document).on("click",".lead-view",function (e) {
	var id = $(this).attr("data-id");
	var action = $(this).attr("data-action");
	var title = $(this).attr("data-title");

	$.ajax({
		url: action,
		type: "get",
		beforeSend:function(){
			blockUI();
			$('.common-modal').modal('show');
			$('.common-modal .modal-body').html("Loading...");
			$('.common-modal .modal-title').html(title);
			//$(".select2").select2();
		},
		success: function (data) {
			$.unblockUI();
			
			$('.common-modal .modal-body').html(data);
		}

	});
});
  
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	blockUI();
	$.fn.dataTable.ext.errMode = 'none'; 
	$('.table').DataTable().destroy();
	var list_type = $(this).attr("data-type");
	var action = $(this).attr("data-action");
	var tab = $(this).attr("href");
	$.ajax({
		data: { tab_type : list_type },
		url: action,
		type: "post",
		beforeSend:function(){
			blockUI();
			$(".tab-content").html('');
			
		},
		success: function (data) {
			$(".tab-content").html(data);
			$(tab).addClass("active");
			$('.table').DataTable({
				"ordering": false
			});
		},
		complete: function(data){

		}

	}).done(function() {
		$.unblockUI();
	});
	//$.unblockUI();
});



$(document).on("keyup paste",".number-only",function(){
	this.value = this.value.replace(/[^0-9.]/g, '');
});

$(document).on("keypress",".email-only", function (e) {
	var email = $(this).val();
	var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

	if(!regex.test(email)) {
		$(this).addClass("error-validation");
	}else{
		$(this).removeClass("error-validation");
	}
});

$(document).ready(function () {
    // 900000 ms = 15 minutes
    const timeout = 900000;
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
    	clearTimeout(idleTimer);

    	idleTimer = setTimeout(function () {
    		document.getElementById('logout-form').submit();
    	}, timeout);
    });
    $("body").trigger("mousemove");

    $(document).on("click",".lead-edit",function (e) {
    	var id = $(this).attr("data-id");
    	var action = $(this).attr("data-action");
    	var title = $(this).attr("data-title");

    	$.ajax({
    		url: action,
    		type: "get",
    		beforeSend:function(){
    			blockUI();
    			$('.common-modal').modal('show');
    			$('.common-modal .modal-body').html("Loading...");
    			$('.common-modal .modal-title').html(title);


    		},
    		success: function (data) {
    			$.unblockUI();
    			$('.common-modal .modal-body').html(data);
    			$('.datepicker').datepicker();
    		}

    	});
    });

    $(document).on("click",".next-followup",function (e) {
    	var id = $(this).attr("data-id");
    	var action = $(this).attr("data-action");
    	var title = $(this).attr("data-title");

    	$.ajax({
    		url: action,
    		type: "get",
    		beforeSend:function(){
    			blockUI();
    			$('.common-modal').modal('show');
    			$('.common-modal .modal-body').html("Loading...");
    			$('.common-modal .modal-title').html(title);
    		},
    		success: function (data) {
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
    			$('#meeting_followup_date_time').timepicker();
    			$('#txt_meeting_visit_done_dt').datepicker({
    				startDate: date,
    				todayHighlight: true
    			});

    		}

    	});
    });

    $(document).bind("keyup",".calculate-total-sold", function (e) {
    	var total_cost = 0;
    	$(".calculate-total-sold").each(function(){
    		total_cost += parseFloat(this.value*1);
    	});
    	$("#grand-total").val(total_cost);
    });
});

