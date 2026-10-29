var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var validator,
			validate = ($.fn.validate !== undefined);
		
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
		
		$(document).on("change", ".onoffswitch-data .onoffswitch-checkbox", function (e) {
			var href = $(this).attr('data-href'); 
			window.location.href = href;
		}).on('change', '#jump_to', function(e){
			var date = $(this).val();
			var action = $(this).attr('data-type');
			var calendar_id = $('#calendar_id').val() != '' ? '&calendar_id=' + $('#calendar_id').val() : '';
			window.location.href='index.php?controller=pjAdminSchedule&action=pjAction'+action+'&date=' + date + calendar_id;
		}).on('change', '#calendar_id', function(e){
			var date = $('#jump_to').val();
			var action = $(this).attr('data-type');
			var calendar_id = $(this).val() != '' ? '&calendar_id=' + $(this).val() : '';
			window.location.href='index.php?controller=pjAdminSchedule&action=pjAction'+action+'&date=' + date + calendar_id;
		}).on("click", ".btn-reminder", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var booking_id = $(this).attr('data-booking_id');
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
		}).on('click', '.btn-delete', function(e){
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var id = $(this).attr('data-id');
			var booking_id = $(this).attr('data-booking_id');
			swal({
                title: myLabel.are_you_sure,
                text: myLabel.cancel_text,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: myLabel.btn_confirm_cancel,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
            	$.post('index.php?controller=pjAdminSchedule&action=pjActionCancelBooking', {id: id, booking_id: booking_id}).done(function (data) {
            		swal.close();
            		location.reload(true);
				});
            });
		});
	});
})(jQuery_1_8_2);