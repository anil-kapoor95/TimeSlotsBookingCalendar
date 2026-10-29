var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var $frmCreateClient = $("#frmCreateClient"),
			$frmUpdateClient = $("#frmUpdateClient"),
			datagrid = ($.fn.datagrid !== undefined);
		
		if ($(".select-item").length) {
            $(".select-item").select2({
                placeholder: myLabel.choose ,
                allowClear: true
            });
        };
		if($frmCreateClient.length > 0)
		{
			$frmCreateClient.validate({
				rules: {
					"customer_email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckEmail"
					}
				},
				messages: {
					"customer_email": {
						remote: myLabel.email_exists
					}
				}
			});
		}
		if ($frmUpdateClient.length > 0) {
			$frmUpdateClient.validate({
				rules: {
					"customer_email": {
						required: true,
						email: true,
						remote: "index.php?controller=pjAdminClients&action=pjActionCheckEmail&id=" + $frmUpdateClient.find("input[name='id']").val()
					}
				},
				messages: {
					"customer_email": {
						remote: myLabel.email_exists
					}
				}
			});
		}
		function formatBookings(str, obj) 
		{
			if(parseInt(str, 10) > 0)
			{
				return '<a href="index.php?controller=pjAdminBookings&action=pjActionIndex&client_id='+obj.id+'">'+str+'</a>';
			}else{
				return str;
			}
		}
		if ($("#grid").length > 0 && datagrid) 
		{
			var buttonsOpts = [];
			var actionsOpts = [];
			if(pjGrid.has_update)
			{
				buttonsOpts.push({type: "edit", url: "index.php?controller=pjAdminClients&action=pjActionUpdate&id={:id}"});
			}
			if(pjGrid.has_delete)
			{
				buttonsOpts.push({type: "delete", url: "index.php?controller=pjAdminClients&action=pjActionDeleteClient&id={:id}"});
			}
			if(pjGrid.has_delete_bulk)
			{
				actionsOpts.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminClients&action=pjActionDeleteClientBulk", render: true, confirmation: myLabel.delete_confirmation});
			}
			if(pjGrid.has_revert_status)
			{
				actionsOpts.push({text: myLabel.revert_status, url: "index.php?controller=pjAdminClients&action=pjActionStatusClient", render: true});
			}
			if(pjGrid.has_export)
			{
				actionsOpts.push({text: myLabel.exported, url: "index.php?controller=pjAdminClients&action=pjActionExportClient", ajax: false});
			}
			var $grid = $("#grid").datagrid({
				buttons: buttonsOpts,
				columns: [{text: myLabel.name, type: "text", sortable: true, editable: pjGrid.has_update, editableWidth: 190},
				          {text: myLabel.email, type: "text", sortable: true, editable: pjGrid.has_update , editableWidth: 190},
				          {text: myLabel.phone, type: "text", sortable: true, editable: pjGrid.has_update , editableWidth: 190},
				          {text: myLabel.bookings, type: "text", sortable: true, editable: false , renderer: formatBookings},
				          {text: myLabel.status, type: "toggle", sortable: true, editable: pjGrid.has_revert_status, positiveLabel: myLabel.active, positiveValue: "T", negativeLabel: myLabel.inactive, negativeValue: "F"},
				          ],
				dataUrl: "index.php?controller=pjAdminClients&action=pjActionGetClient",
				dataType: "json",
				fields: ['customer_name', 'customer_email', 'customer_phone', 'cnt_bookings', 'status'],
				paginator: {
					actions: actionsOpts,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminClients&action=pjActionSaveClient&id={:id}",
				select: {
					field: "id",
					name: "record[]",
					cellClass: 'cell-width-2'
				}
			});
		}
		
		$(document).on("click", ".btn-all", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			$(this).addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				status: "",
				q: ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "customer_name", "ASC", content.page, content.rowCount);
			
		}).on("click", ".btn-filter", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this),
				content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache"),
				obj = {};
			$this.addClass("btn-primary active").removeClass("btn-default")
				.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			obj.status = "";
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "customer_name", "ASC", content.page, content.rowCount);
			
		}).on("submit", ".frm-filter", function (e) {
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
			$grid.datagrid("load", "index.php?controller=pjAdminClients&action=pjActionGetClient", "customer_name", "ASC", content.page, content.rowCount);
			return false;
		});
	});
})(jQuery_1_8_2);