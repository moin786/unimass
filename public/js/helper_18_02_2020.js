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

	if(confirm("Are you sure?"))
	{
		$.ajax({
			data: $('#'+formID).serialize(),
			url: formAction,
			type: formMethod,
			beforeSend:function(){
				blockUI();
			},
			success: function (data) {
				$.unblockUI();
				if(data.type == 'error')
				{
					toastr.error(data.message, data.title);
				}
				else
				{
					toastr.success(data.message, data.title);
					$("#" + formID).find("input, textarea").not('.keep_me').val("");
					$("#" + formID).find("select").not('.keep_me').val("0");
					if(responseAction)
					{
						var tab = $("ul#tab_container li.active a").attr("href");
						$.ajax({
							url: responseAction,
							type: "post",
							data: { tab_type: tab_type },
							beforeSend:function(){

							},
							success: function (response_data) {
								$('#list-body').html(response_data);
								$('.table').DataTable({
									"order": [[ 0, "desc" ]]
								});
								$(tab).addClass("active");
							}
						});
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
	}
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
	var list_type = $(this).attr("data-type");
	var action = $(this).attr("data-action");
	var tab = $(this).attr("href");
	$.ajax({
		data: { tab_type : list_type },
		url: action,
		type: "post",
		beforeSend:function(){
		},
		success: function (data) {

			$(".tab-content").html(data);
			$(tab).addClass("active");
			$('.table').DataTable({
				"order": [[ 0, "desc" ]]
			});
		}

	});
	$.unblockUI();
});

$(document).on("keyup",".number-only", function (event) {

	if (!(event.keyCode == 8
		|| event.keyCode == 9
		|| event.keyCode == 17
		|| event.keyCode == 46
		|| (event.keyCode >= 35 && event.keyCode <= 40)
		|| (event.keyCode >= 48 && event.keyCode <= 57)
		|| (event.keyCode >= 96 && event.keyCode <= 105)
		))
	{
		$(this).val('');
	}
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
    const timeout = 600000;
    var idleTimer = null;
    $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change mouseenter scroll resize dblclick', function () {
    	clearTimeout(idleTimer);

    	idleTimer = setTimeout(function () {
    		document.getElementById('logout-form').submit();
    	}, timeout);
    });
    $("body").trigger("mousemove");
});