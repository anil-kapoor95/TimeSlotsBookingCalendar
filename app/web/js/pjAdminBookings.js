var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var validator,
			$bookingForm,
			$frmCreateBooking = $("#frmCreateBooking"),
			$frmUpdateBooking = $("#frmUpdateBooking"),
			$frmExportICal = $('#frmExportICal'),
			$modalAddItem = $('#modalAddItem'),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined);
		
		if ($(".select-item").length) {
            $(".select-item").select2({
                placeholder: myLabel.choose ,
                allowClear: true
            });
        };
		if ($('#datePickerOptions').length) {
        	$.fn.datepicker.dates['en'] = {
        		days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    		    daysMin: $('#datePickerOptions').data('days').split("_"),
    		    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    		    months: $('#datePickerOptions').data('months').split("_"),
    		    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    		    format: $('#datePickerOptions').data('format'),
            	weekStart: parseInt($('#datePickerOptions').data('wstart'), 10),
    		};
        };
        if ($('.datepick').length > 0) {
        	$('.datepick').datepicker({autoclose: true}).on('changeDate', function (selected) {
        		if($(this).attr('name') == 'date_from')
        		{
        			if($('input[name="date_to"]').length > 0)
        			{
        				var $to = $('input[name="date_to"]');
        				var date_to_value = $to.datepicker("getUTCDate");
        				if(date_to_value < selected.date)
    					{
        					$to.val($('input[name="date_from"]').val());
    					}
        			}
        		}
        		
        		if($(this).attr('name') == 'date_to')
        		{
        			if($('input[name="date_from"]').length > 0)
        			{
        				var $from = $('input[name="date_from"]');
        				var date_from_value = $from.datepicker("getUTCDate");
        				if(date_from_value > selected.date)
    					{
        					$from.val($('input[name="date_to"]').val());
    					}
        			}
        		}
            });
        }
        if ($frmCreateBooking.length > 0 && validate) {
        	$bookingForm = $frmCreateBooking;
        	if($('#add_slot').val() == '1')
			{
				getBookingItems.call(null, $frmCreateBooking);
			}
        	$frmCreateBooking.validate({
        		onkeyup: false,
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var $_id = $(validator.errorList[0].element, this).closest("div.tab-pane").attr("id");
				    	$('.tab-'+$_id).trigger("click");
				    };
				},
				submitHandler: function(form){
					$.post("index.php?controller=pjAdminBookings&action=pjActionCheckOverlapping", $(form).serialize()).done(function (data) {
						if(data.status == 'OK')
						{
							form.submit();
						}else{
							swal({
				    			title: "",
								text: data.text,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
						}
					});
					return false;
				}
        	});
        }
        if ($frmUpdateBooking.length > 0 && validate) {
        	$bookingForm = $frmUpdateBooking;
        	getBookingItems.call(null, $frmUpdateBooking);
        	$frmUpdateBooking.validate({
        		onkeyup: false,
				ignore: "",
				invalidHandler: function (event, validator) {
				    if (validator.numberOfInvalids()) {
				    	var $_id = $(validator.errorList[0].element, this).closest("div.tab-pane").attr("id");
				    	$('.tab-'+$_id).trigger("click");
				    };
				},
				submitHandler: function(form){
					$.post("index.php?controller=pjAdminBookings&action=pjActionCheckOverlapping", $(form).serialize()).done(function (data) {
						if(data.status == 'OK')
						{
							form.submit();
						}else{
							swal({
				    			title: "",
								text: data.text,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
						}
					});
					return false;
				}
        	});
        }
		if ($("#grid").length > 0 && datagrid) {
			
			function formatStatus(val, obj) {
				if(val == 'confirmed')
				{
					return '<div class="btn bg-confirmed btn-xs no-margin"><i class="fa fa-check"></i> ' + myLabel.confirmed + '</div>';
				}else if(val == 'cancelled'){
					return '<div class="btn bg-cancelled btn-xs no-margin"><i class="fa fa-times"></i> ' + myLabel.cancelled + '</div>';
				}else if(val == 'pending'){
					return '<div class="btn bg-pending btn-xs no-margin"><i class="fa fa-exclamation-triangle"></i> ' + myLabel.pending + '</div>';
				}
			}
			function formatId (str, obj) {
				if(pjGrid.hasAccessUpdate)
				{
					return ['<a href="index.php?controller=pjAdminBookings&action=pjActionUpdate&id=', obj.id, '">', str, '</a>'].join("");	
				}else{
					return str;
				}
			}
			function formatSlots(str, obj) {
				var tmp,
					arr = [];
				arr.push('<b>'+obj.calendar_title+'</b>');
				if (obj.items !== undefined) {
					for (var i = 0, iCnt = obj.items.length; i < iCnt; i++) {
						tmp = obj.items[i].split("~.~");
						if(parseInt(tmp[3], 10) > 1)
						{
							arr.push([tmp[0], ', ', tmp[1], '-', tmp[2], ' x ', tmp[3]].join(""));	
						}else{
							arr.push([tmp[0], ', ', tmp[1], '-', tmp[2]].join(""));
						}
					}
				}
				return arr.join("<br />");
			}
			function formatClient (str, obj) {
				return ['<b>',obj.customer_name,'</b>', 
				        (obj.customer_email && obj.customer_email.length > 0 ? ['<br><a href="mailto:', obj.customer_email, '">', obj.customer_email, '</a>'].join('') : ''), 
				        (obj.customer_phone && obj.customer_phone.length > 0 ? ['<br>', obj.customer_phone].join('') : '')
				        ].join("");
			}
			
			var buttonsOpts = [];
			var actionsOpts = [];
			if(pjGrid.hasAccessUpdate)
			{
				buttonsOpts.push({type: "edit", url: "index.php?controller=pjAdminBookings&action=pjActionUpdate&id={:id}"});
			}
			if(pjGrid.hasAccessDeleteSingle)
			{
				buttonsOpts.push({type: "delete", url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBooking&id={:id}"});
			}
			if(pjGrid.hasAccessDeleteMulti)
			{
				actionsOpts.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminBookings&action=pjActionDeleteBookingBulk", render: true, confirmation: myLabel.delete_confirmation});
			}
			var options = {
				buttons: buttonsOpts,
				columns: [{text: myLabel.uuid, type: "text", sortable: true, editable: false, renderer: formatId},
				          {text: myLabel.slots, type: "text", sortable: false, editable: false, renderer: formatSlots},
				          {text: myLabel.customer, type: "text", sortable: true, editable: false, renderer: formatClient},
				          {text: myLabel.total, type: "text", sortable: true, editable: false},
				          {text: myLabel.status, type: "text", sortable: true, editable: false, renderer: formatStatus}],
				dataUrl: "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString,
				dataType: "json",
				fields: ['uuid', 'id', 'customer_name', 'booking_total', 'booking_status'],
				paginator: {
					actions: actionsOpts,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminBookings&action=pjActionSaveBooking&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				},
			};
			
			var $grid = $("#grid").datagrid(options);
		}
		
		$(document).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val(),
				booking_status: $this.find("select[name='booking_status']").val(),
				date_from: "",
				date_to: "",
				calendar_id: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, content.column, content.direction, content.page, content.rowCount);
			return false;
		}).on("change", ".pj-filter-status", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(".frm-filter").trigger("submit");
			return false;
		}).on("reset", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(".frm-filter-advanced").find('select[name="calendar_id"]').val("");
			$(".frm-filter-advanced").find('select[name="booking_status"]').val("");
			$(".frm-filter-advanced").find('input[name="q"]').val("");
			$(".frm-filter-advanced").find('input[name="date_from"]').val("");
			$(".frm-filter-advanced").find('input[name="date_to"]').val("");
			$(".btn-advance-search").trigger("click");
			return false;
		}).on("submit", ".frm-filter-advanced", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var obj = {},
				$this = $(this),
				arr = $this.serializeArray(),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			for (var i = 0, iCnt = arr.length; i < iCnt; i++) {
				obj[arr[i].name] = arr[i].value;
			}
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminBookings&action=pjActionGetBooking" + pjGrid.queryString, content.column, content.direction, content.page, content.rowCount);
			return false;
		}).on("change", ".onoffswitch-client .onoffswitch-checkbox", function (e) {
			if ($(this).prop('checked')) {
                $('.current-client-area').hide();
                $('.current-client-area').find('.pjTsbRequired').removeClass('required');
                $('.new-client-area').show();
                $('.new-client-area').find('.pjTsbRequired').addClass('required');
            }else {
                $('.current-client-area').show();
                $('.current-client-area').find('.pjTsbRequired').addClass('required');
                $('.new-client-area').hide();
                $('.new-client-area').find('.pjTsbRequired').removeClass('required');
            }
		}).on("change", "#client_id", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $pjTsbEditClient = $('#pjTsbEditClient');
			var $form = $(this).closest('form');
			if($(this).val() != '')
			{
				if ($pjTsbEditClient.length > 0) {
					var href = $pjTsbEditClient.attr('data-href');
					href = href.replace("{ID}", $(this).val());
					$pjTsbEditClient.attr('href', href);
					$pjTsbEditClient.css('display', 'inline-block');
				}
				if($('#customer_email').length > 0)
				{
					$('#customer_email').removeClass('required');
				}
				if($('#customer_name').length > 0)
				{
					$('#customer_name').removeClass('required');
				}
			}else{
				if ($pjTsbEditClient.length > 0) {
					$pjTsbEditClient.css('display', 'none');
				}
				$form.find('.pjTsbRequired').addClass('required');
			}
			$(this).valid();
		}).on("click", ".item-add", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			if ($modalAddItem.length > 0) {
				$modalAddItem.find('.modal-inbody').html("");
				var fields = "input[name='id'], input[name='hash'], input[name='booking_date'], select[name='calendar_id']";
				if($bookingForm.find("input[name='calendar_id']").length > 0)
				{
					fields = "input[name='id'], input[name='hash'], input[name='booking_date'], input[name='calendar_id']";
				}
				$.get("index.php?controller=pjAdminBookings&action=pjActionItemAdd", $bookingForm.find(fields).serialize()).done(function (data) {
					$modalAddItem.find('.modal-inbody').html(data);
					$modalAddItem.find('.input-group.date').datepicker().on('changeDate', function (selected) {
						$(this).datepicker('hide');
						getSlots.call($modalAddItem.find("input[name='date']").get(0));
		            });
					$modalAddItem.modal('show');
					getSlots.call($modalAddItem.find("input[name='date']").get(0));
				});
			}
			return false;
		}).on("click", ".item-delete", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			swal({
				title: myLabel.alert_delete_booking_slot_title,
				text: myLabel.alert_delete_booking_slot_text,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: myLabel.alert_btn_delete,
				cancelButtonText: myLabel.alert_btn_cancel,
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			}, function () {
				$.post('index.php?controller=pjAdminBookings&action=pjActionItemDelete', {
					"id": $this.data("id"),
					"hash": $this.data("hash"),
					"key": $this.data("key")
				}).done(function (data) {
					if (data.status == "OK") {
						getBookingItems.call(null, $bookingForm);
						swal.close();
					} else {
						swal({
			    			title: "",
							text: data.text,
							type: "warning",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: myLabel.btnOK,
							closeOnConfirm: false,
							showLoaderOnConfirm: false
						}, function () {
							swal.close();
						});
					}
				});
			});
			return false;
		}).on("click", ".export-ical", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$frmExportICal.submit();
			return false;
		}).on("click", "#btnEmail", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-id');
			var $emailContentWrapper = $('#emailContentWrapper');
			
			$('#btnSendEmailConfirm').attr('data-booking_id', booking_id);
			
			$emailContentWrapper.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionReminderEmail", {
				"id": booking_id
			}).done(function (data) {
				$emailContentWrapper.html(data);
				if(data.indexOf("pjResendAlert") == -1)
				{
					myTinyMceInit.call(null, 'textarea#mceEditor', 'mceEditor');
					validator = $emailContentWrapper.find("form").validate({});
					$('#btnSendEmailConfirm').show();
				}else{
					$('#btnSendEmailConfirm').hide();
				}	
				$('#reminderEmailModal').modal('show');
			});
			return false;
		}).on("click", "#btnSendEmailConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var $emailContentWrapper = $('#emailContentWrapper');
			if (validator.form()) {
				$('#mceEditor').html( tinymce.get('mceEditor').getContent() );
				$(this).attr("disabled", true);
				var l = Ladda.create(this);
			 	l.start();
				$.post("index.php?controller=pjAdminBookings&action=pjActionReminderEmail", $emailContentWrapper.find("form").serialize()).done(function (data) {
					if (data.status == "OK") {
						$('#reminderEmailModal').modal('hide');
					} else {
						$('#reminderEmailModal').modal('hide');
					}
					$this.attr("disabled", false);
					l.stop();
				});
			}
			return false;
		}).on("click", "#btnSMS", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-id');
			var $smsContentWrapper = $('#smsContentWrapper');
			
			$('#btnSendSMSConfirm').attr('data-booking_id', booking_id);
			
			$smsContentWrapper.html("");
			$.get("index.php?controller=pjAdminBookings&action=pjActionReminderSms", {
				"id": booking_id
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					swal({
		    			title: "",
						text: data.text,
						type: "warning",
						confirmButtonColor: "#DD6B55",
						confirmButtonText: myLabel.btnOK,
						closeOnConfirm: false,
						showLoaderOnConfirm: false
					}, function () {
						swal.close();
					});
				}else{
					$smsContentWrapper.html(data);
					validator = $smsContentWrapper.find("form").validate({});
					$('#reminderSMSModal').modal('show');
				}
			});
			return false;
		}).on("click", "#btnSendSMSConfirm", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var $smsContentWrapper = $('#smsContentWrapper');
			if (validator.form()) {
				$(this).attr("disabled", true);
				var l = Ladda.create(this);
			 	l.start();
				$.post("index.php?controller=pjAdminBookings&action=pjActionReminderSms", $smsContentWrapper.find("form").serialize()).done(function (data) {
					$('#reminderSMSModal').modal('hide');
					$this.attr("disabled", false);
					l.stop();
				});
			}
			return false;
		}).on("change", "#booking_status", function (e) {
			var $pjFdPriceWrapper = $('#pjFdPriceWrapper');
			var value = $("#booking_status option:selected").val();
			var text = $("#booking_status option:selected").text();
			var bg_class = 'bg-' + value;
			var fa_class = '';
			if(value == 'confirmed')
			{
				fa_class = 'fa-check';
			}else if(value == 'pending'){
				fa_class = 'fa-exclamation-triangle';
			}else if(value == 'cancelled'){
				fa_class = 'fa-times';
			}
			$pjFdPriceWrapper.find('.panel-heading').removeClass("bg-pending").removeClass("bg-cancelled").removeClass("bg-confirmed").addClass(bg_class);
			$pjFdPriceWrapper.find('.status-text').html(text);
			$pjFdPriceWrapper.find('i.fa').removeClass('fa-check').removeClass('fa-exclamation-triangle').removeClass('fa-times').addClass(fa_class);
		}).on("change", "#discount_code", function (e) {
			var $form = $(this).closest("form");
			getPrices.call(null, $form);
		}).on("change", "#calendar_id", function (e) {
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetPMs&calendar_id=" + $(this).val()).done(function (data) {
				$('#pjTsPaymentMethodBox').html(data);
			});
			var $form = $(this).closest('form');
			$.get("index.php?controller=pjAdminBookings&action=pjActionGetBookingFields&calendar_id=" + $(this).val() + "&booking_id=" + $form.find('input[name="id"]').val()).done(function (data) {
				$('#boxBookingItems').html('');
				var required_classs = "";
				if($('#new_client').is(':checked'))
				{
					required_classs = "pjTsbRequired required";
				}else{
					required_classs = "pjTsbRequired";
				}
				$.each(data, function(key, val){
			        if(key.indexOf('o_bf_') >= 0)
			        {
			        	var field_id = "#" + key.replace("o_bf_", "customer_");
			        	if($(field_id).length > 0)
			        	{
			        		if(val == '3')
			        		{
			        			$(field_id).addClass(required_classs);
			        		}else{
			        			$(field_id).removeClass(required_classs);
			        			$(field_id).valid();
			        		}
			        	}
			        } else if(key == 'o_disable_payments') {
			        	var field_id = "#payment_method";
			        	if($(field_id).length > 0)
			        	{
			        		if(val == '0')
			        		{
			        			$(field_id).addClass('required');
			        		}else{
			        			$(field_id).removeClass('required');
			        			$(field_id).valid();
			        		}
			        	}
			        }
			    });
				
			});
		});
		
		function getSlots() {
			var $el = $(this),
				$form = $el.closest("form"),
				$details = $form.find(".item_details");
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetSlots&" + $form.find("input[name='booking_id'], input[name='hash'], input[name='date'], input[name='calendar_id']").serialize(), $bookingForm.serialize()).done(function (data) {
				$details.html(data).show();
				bindSlots.call();
			});
		}
		function bindSlots()
		{
			if($('.i-checks').length > 0)
			{
				$('.i-checks').iCheck({
		            checkboxClass: 'icheckbox_square-green',
		            radioClass: 'iradio_square-green'
		        });
			}
			var $form = $modalAddItem.find('form');
			$form.validate({
				submitHandler: function(form){
					$form.find('.btn').attr("disabled", "disabled");
					$.post("index.php?controller=pjAdminBookings&action=pjActionItemAdd", $form.serialize()).done(function (data) {
						$form.find('.btn').removeAttr("disabled");
						if (data.status == "OK") {
							getBookingItems.call(null, $bookingForm);
							swal({
				    			title: "",
								text: data.text,
								type: "success",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
							$modalAddItem.modal('hide');
						} else {
							swal({
				    			title: "",
								text: data.text,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
							tsApp.enableButtons.call(null, $dialogItemAdd);
						}
					});
					return false;
				}
			});
		}
		function getPrices($form)
		{
			$.post("index.php?controller=pjAdminBookings&action=pjActionGetPrice", $form.serialize()).done(function (data) {
				if (data.status == 'OK') {
					if(data.data.has_slot == '1')
					{
						$form.find("#booking_price").val(data.data.price.toFixed(2));
						$form.find("#booking_discount").val(data.data.discount.toFixed(2));
						$form.find("#booking_deposit").val(data.data.deposit.toFixed(2));
						$form.find("#booking_tax").val(data.data.tax.toFixed(2));
						$form.find("#booking_total").val(data.data.total.toFixed(2));
						$('#pjTsTotalLabel').html(data.data.total_format);
					}else{
						$form.find("#booking_price").val('');
						$form.find("#booking_discount").val('');
						$form.find("#booking_deposit").val('');
						$form.find("#booking_tax").val('');
						$form.find("#booking_total").val('');
						$('#pjTsTotalLabel').html("--");
					}
				}
			});
		}
		function getBookingItems($form) {
			$.get("index.php?controller=pjAdminBookings&action=pjActionItemGet", $form.find("select[name='calendar_id'], input[name='id'], input[name='hash']").serialize()).done(function (data) {
				$("#boxBookingItems").html(data);
				
				getPrices.call(null, $form);
			});
		}
		
		function myTinyMceInit(pSelector, pValue) {
			tinymce.init({
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				browser_spellcheck : true,
			    contextmenu: false,
			    selector: pSelector,
			    theme: "modern",
			    height: 300,
			    plugins: [
			         "advlist autolink link image lists charmap print preview hr anchor pagebreak",
			         "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
			         "save table contextmenu directionality emoticons template paste textcolor"
			    ],
			    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
			    image_advtab: true,
			    menubar: "file edit insert view table tools",
			    setup: function (editor) {
			    	editor.on('change', function (e) {
			    		editor.editorManager.triggerSave();
			    	});
			    }
			});
			if (tinymce.editors.length) {							
				tinymce.execCommand('mceAddEditor', true, pValue);
			}
		}
		$("#reminderEmailModal").on("hidden.bs.modal", function () {
        	if (tinymce.editors.length > 0) 
			{
		        tinymce.execCommand('mceRemoveEditor',true, "mceEditor");
		    }
        });
	});
})(jQuery_1_8_2);