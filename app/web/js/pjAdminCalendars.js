var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateCalendar = $("#frmCreateCalendar"),
			$frmUpdateCalendar = $("#frmUpdateCalendar"),
			multilang = ($.fn.multilang !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			validate = ($.fn.validate !== undefined);
		
		if (multilang && myLabel.isFlagReady == 1) {
			$(".multilang").multilang({
				langs: pjLocale.langs,
				flagPath: pjLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					$("input[name='locale_id']").val(ui.index);					
				}
			});
		}
		if ($(".select-item").length) {
            $(".select-item").select2({
                placeholder: myLabel.choose ,
                allowClear: true
            });
        };
		if ($frmCreateCalendar.length > 0 && validate) {
			$frmCreateCalendar.validate({
				
				ignore: ''
			});
		}
		if ($frmUpdateCalendar.length > 0 && validate) {
			if($('.i-checks').length > 0)
			{
				$('.i-checks').iCheck({
		            checkboxClass: 'icheckbox_square-green',
		            radioClass: 'iradio_square-green'
		        });
			}
			$frmUpdateCalendar.validate({
				
				ignore: ''
			});
			var $formCopy = $('#myModalCopyOptions').find('form');
			if($formCopy.length > 0)
			{
				$formCopy.validate({
					submitHandler: function(form){
						$.post("index.php?controller=pjAdminCalendars&action=pjActionCopyOptions", $formCopy.serialize()).done(function (data) {
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
								$('#myModalCopyOptions').modal('hide');
							});
						});
						return false;
					}
				});
			}
		}
		
		function onBeforeShow (obj) {
			if (parseInt(obj.id, 10) === pjGrid.currentCalendarId) {
				return false;
			}
			return true;
		}
		
		function formatTitle(val, obj) {
			if (myLabel.has_update)
			{
				return ['<a href="index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminCalendars&nextAction=pjActionUpdate&calendar_id=', obj.id, '&nextParams=', encodeURIComponent("id=" + obj.id), '">', val, '</a>'].join("");	
			}else{
				return val;
			}
		}
		
		if ($("#grid").length > 0 && datagrid) {
			
			var buttonsOpts = [];
			var actionsOpts = [];
			
			if (myLabel.has_update)
			{
				buttonsOpts.push({type: "edit", url: "index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminCalendars&nextAction=pjActionUpdate&calendar_id={:id}&nextParams=id={:id}"});
			}
			if (myLabel.has_booking_options)
			{
				buttonsOpts.push({type: "cog", url: "index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminOptions&nextAction=pjActionIndex&calendar_id={:id}"});
			}
			if (myLabel.has_working_time)
			{
				buttonsOpts.push({type: "clock-o", url: "index.php?controller=pjAdmin&action=pjActionRedirect&nextController=pjAdminTime&nextAction=pjActionIndex&calendar_id={:id}"});
			}
			if (myLabel.has_delete)
			{
				buttonsOpts.push({type: "delete", url: "index.php?controller=pjAdminCalendars&action=pjActionDeleteCalendar&id={:id}", beforeShow: onBeforeShow});
			}
			if (myLabel.has_delete_bulk) 
			{
				actionsOpts.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminCalendars&action=pjActionDeleteCalendarBulk", render: true, confirmation: myLabel.delete_confirmation});
			}
			
			var $grid = $("#grid").datagrid({
				buttons: buttonsOpts,
				columns: [{text: myLabel.title, type: "text", sortable: true, editable: false, renderer: formatTitle},
				          {text: myLabel.email, type: "text", sortable: true, editable: false},
				          {text: myLabel.name, type: "date", sortable: true, editable: false}
				          ],
				dataUrl: "index.php?controller=pjAdminCalendars&action=pjActionGetCalendar" + pjGrid.queryString,
				dataType: "json",
				fields: ['title', 'email', 'name'],
				paginator: {
					actions: actionsOpts,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminCalendars&action=pjActionSaveCalendar&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		
		$(document).on("submit", ".frm-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				q: $this.find("input[name='q']").val()
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminCalendars&action=pjActionGetCalendar", "id", "ASC", content.page, content.rowCount);
			return false;
		}).on("change", ".select-item", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).valid();
		}).on("click", "tr.clickable", function (e) {
			window.location.href = $(this).attr('data-href');
		});
		
	});
})(jQuery_1_8_2);