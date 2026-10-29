var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var datepicker = ($.fn.datepicker !== undefined),
			validate = ($.fn.validate !== undefined),
			datagrid = ($.fn.datagrid !== undefined),
			$frmTimeCustom = $("#frmTimeCustom"),
			$frmPriceDefault = $('#frmPriceDefault');
		
		
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
        if ($('.clockpicker').length) {
        	$('.clockpicker').clockpicker({
                twelvehour: myLabel.showperiod,
                autoclose: true,
                afterDone: function() {
                	if($frmTimeCustom.length > 0)
    				{
    					getCustomLunchBreak.call(null);
    				}else{
    					getLunchBreak.call(null);
    				}    
                }
            });
        };
        if($(".field-int").length > 0)
        {
            $(".field-int").TouchSpin({
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white',
                max: 4294967295
            });
        }
		if ($frmPriceDefault.length > 0 && validate) {
			$frmPriceDefault.validate({
				submitHandler: function(form){
					var l = Ladda.create( $(form).find(":submit").get(0) );
					l.start();
					if($('#after_midnight').length > 0 && $('.tsIsDayOff').is(':checked') == false)
					{
						swal({
			    			title: "",
							text: $('#after_midnight').html(),
							type: "warning",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: myLabel.btnOK,
							closeOnConfirm: false,
							showLoaderOnConfirm: false
						}, function () {
							swal.close();
						});
						l.stop();
					}else{
						var wday = $frmPriceDefault.find('input[name="day_of_week"]').val();
						var limit_value = parseInt($('#' + wday + '_limit').val(), 10);
						var people_value = parseInt($('#' + wday + '_people').val(), 10);
						if(people_value > limit_value)
						{
							swal({
				    			title: "",
								text: myLabel.restriction_per_slot_msg,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
							l.stop();
						}else{
							form.submit();	
						}
					}
					return false;
				}
			});
		}
		
		if ($frmTimeCustom.length > 0 && validate) {
			if($('.i-checks').length > 0)
			{
				$('.i-checks').iCheck({
		            checkboxClass: 'icheckbox_square-green',
		            radioClass: 'iradio_square-green'
		        });
				$('input').on('ifChanged', function (event) { $(event.target).trigger('change'); });
			}
			if ($frmTimeCustom.find('.input-group.date').length > 0) {
				$frmTimeCustom.find('.input-group.date').datepicker({autoclose: true}).on('changeDate', function (selected) {
	        		
	            });
	        }
			if($frmTimeCustom.find('input[name="date_id"]').val() != ''){
				getCustomLunchBreak.call(null);
			}
			$frmTimeCustom.validate({
				submitHandler: function(form){
					var l = Ladda.create( $(form).find(":submit").get(0) );
					l.start();
					if($('#after_midnight').length > 0 && $('input[name="is_dayoff"]').is(':checked') == false)
					{
						swal({
			    			title: "",
							text: $('#after_midnight').html(),
							type: "warning",
							confirmButtonColor: "#DD6B55",
							confirmButtonText: myLabel.btnOK,
							closeOnConfirm: false,
							showLoaderOnConfirm: false
						}, function () {
							swal.close();
						});
						l.stop();
					}else{
						var limit_value = parseInt($('#slot_limit').val(), 10);
						var people_value = parseInt($('#people').val(), 10);
						if(people_value > limit_value)
						{
							swal({
				    			title: "",
								text: myLabel.restriction_per_slot_msg,
								type: "warning",
								confirmButtonColor: "#DD6B55",
								confirmButtonText: myLabel.btnOK,
								closeOnConfirm: false,
								showLoaderOnConfirm: false
							}, function () {
								swal.close();
							});
							l.stop();
						}else{
							form.submit();	
						}
					}
					return false;
				}
			});
		}
		
		$(document).on("click", ".tsIsDayOff", function () {
			var $this = $(this);
			if ($this.is(":checked")) {
				$('.pjTsWTimeWrapper').hide();
			} else {
				$('.pjTsWTimeWrapper').show();
			}
		}).on("click", ".pjTsLunchBreak", function () {
			var $this = $(this);
			if ($this.is(":checked")) {
				$('#tsLunchBreakContainer').show();
			} else {
				$('#tsLunchBreakContainer').hide();
				$('input[name="lunch_length"]').val('');
			}
			if($frmTimeCustom.length > 0)
			{
				generateCustomSlots.call(null);
			}else{
				generateSlots.call(null);
			}
		}).on("click", "#pjTsDeleteCustomPrices", function () {
			var $this = $(this);
			var calendar_id = $this.attr('data-fid');
			var wday = $this.attr('data-wday');
			$.post("index.php?controller=pjAdminTime&action=pjActionSetPrices", {
				"delete": 1,
				"calendar_id": calendar_id,
				"day" : wday
			}).done(function (data) {
				$('#setCustomPriceModal').modal('hide');
			});
		}).on("click", "#pjTsSaveCustomPrices", function () {
			var $this = $(this);
			var calendar_id = $this.attr('data-fid');
			var wday = $this.attr('data-wday');
			var $form = $('#setCustomPriceModal').find('form');
			$.post("index.php?controller=pjAdminTime&action=pjActionSetPrices", $form.serialize()).done(function (data) {
				$('#setCustomPriceModal').modal('hide');
			});
		}).on("change", ".pps", function () {
			if ($("#single_price").is(":checked")) {
				$("#boxPPS").html("");
				$('#pjTsCustomDefaultPrice').show();
			} else {
				$('#pjTsCustomDefaultPrice').hide();
				$.post("index.php?controller=pjAdminTime&action=pjActionGetSlots", $frmTimeCustom.serialize()).done(function (data) {
					$("#boxPPS").html(data);
				});
			}
		}).on("click", "a.day-price", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var $this = $(this);
			var day = $this.attr('data-day');
			var fid = $this.attr('data-fid');
			$.get("index.php?controller=pjAdminTime&action=pjActionGetPrices", {
				"foreign_id": fid,
				"day": day
			}).done(function (data) {
				$('#setCustomPriceModal').find('.modal-body').html(data);
				$('#setCustomPriceModal').modal('show');
			});
			return false;
		}).on("change", "input[name='is_dayoff']", function () {
			var $this = $(this),
				$form = $this.closest("form");
			if ($this.is(":checked")) {
				$form.find(".business").hide();
				$('.tsMidnightMessage').hide();
			} else {
				$form.find(".business").show();
				var lunch_break = $("#frmTimeCustom input[type='radio']:checked").val();
				if(lunch_break == 'F')
				{
					$('#tsLunchBreakContainer').hide();
				}
			}
		}).on("change", "#slot_length", function () {
			if($frmTimeCustom.length > 0)
			{
				getCustomLunchBreak.call(null);
			}else{
				getLunchBreak.call(null);
			}
		}).on("change", "#number_of_slots", function () {
			if($frmTimeCustom.length > 0)
			{
				getCustomLunchBreak.call(null);
			}else{
				getLunchBreak.call(null);
			}
		}).on("change", "#lunch_from", function () {
			if($frmTimeCustom.length > 0)
			{
				generateCustomSlots.call(null);
			}else{
				generateSlots.call(null);
			}
		}).on("change", "input[name='lunch_length']", function () {
			var length = $(this).val();
			if(length.match(/^\d+$/)) {
				if($frmTimeCustom.length > 0)
				{
					generateCustomSlots.call(null);
				}else{
					generateSlots.call(null);
				}
			}
		});
		/****Default Time*******/
		function getLunchBreak()
		{
			var $form = $('#frmPriceDefault');
			$.post("index.php?controller=pjAdminTime&action=pjActionGetLunchBreak", $form.serialize()).done(function (data) {
				$('#tsSlotBox').html(data);
				$('.day-price').hide();
				if($('#pjTsEndTimeValue').length > 0)
				{
					var wday = $('#pjTsEndTimeValue').attr('data-wday');
					var etime = $('#pjTsEndTimeValue').html();
					$form.find('input[name="'+wday+'_to"]').val(etime);
				}
				if($(".field-int").length > 0)
		        {
					$('#tsSlotBox').find(".field-int").TouchSpin({
		                verticalbuttons: true,
		                buttondown_class: 'btn btn-white',
		                buttonup_class: 'btn btn-white',
		                max: 4294967295
		            });
		        }
			});
		}
		function generateSlots()
		{
			var $form = $('#frmPriceDefault');
			$.post("index.php?controller=pjAdminTime&action=pjActionGenerateSlots", $form.serialize()).done(function (data) {
				$('#tsSlotsContainer').html(data);
				if($('#pjTsEndTimeValue').length > 0)
				{
					var wday = $('#pjTsEndTimeValue').attr('data-wday');
					var etime = $('#pjTsEndTimeValue').html();
					$form.find('input[name="'+wday+'_to"]').val(etime);
				}
				$('.day-price').hide();
			});
		}
		/****Custom Time*******/
		function getCustomLunchBreak()
		{
			if($('input[name="date"]').val() != '' && $('input[name="start"]').val())
			{
				$.post("index.php?controller=pjAdminTime&action=pjActionGetCustomLunchBreak", $frmTimeCustom.serialize()).done(function (data) {
					$('#tsCustomSlotBox').html(data);
					if($('#pjTsEndTimeValue').length > 0)
					{
						var etime = $('#pjTsEndTimeValue').html();
						$frmTimeCustom.find('input[name="end"]').val(etime);
					}
					if($(".field-int").length > 0)
			        {
						$('#tsCustomSlotBox').find(".field-int").TouchSpin({
			                verticalbuttons: true,
			                buttondown_class: 'btn btn-white',
			                buttonup_class: 'btn btn-white',
			                max: 4294967295
			            });
			        }
					if (!$("#single_price").is(":checked")) {
						$.post("index.php?controller=pjAdminTime&action=pjActionGetSlots", $frmTimeCustom.serialize()).done(function (data) {
							$("#boxPPS").html(data);
						});
					}
					if($frmTimeCustom.find('input[name="date_id"]').val() != ''){
						generateCustomSlots.call(null);
					}
				});
			}
		}
		function generateCustomSlots()
		{
			$.post("index.php?controller=pjAdminTime&action=pjActionGenerateCustomSlots", $frmTimeCustom.serialize()).done(function (data) {
				$('#tsCustomSlotsContainer').html(data);
				if($('#pjTsEndTimeValue').length > 0)
				{
					var etime = $('#pjTsEndTimeValue').html();
					$frmTimeCustom.find('input[name="end"]').val(etime);
				}
				if (!$("#single_price").is(":checked")) {
					$.post("index.php?controller=pjAdminTime&action=pjActionGetSlots", $frmTimeCustom.serialize()).done(function (data) {
						$("#boxPPS").html(data);
					});
				}
			});
		}
		
		if ($("#grid").length > 0 && datagrid) {
			function formatDateOff(val, obj) {
				if(val == 'T')
				{
					return '<div class="btn btn-primary btn-xs no-margin"><i class="fa fa-check"></i> ' + myLabel.time_yes + '</div>';
				}else if(val == 'F'){
					return '<div class="btn btn-default btn-xs no-margin"><i class="fa fa-times"></i> ' + myLabel.time_no + '</div>';
				}
			}
			var buttonsOpts = [];
			var actionsOpts = [];
			buttonsOpts.push({type: "edit", url: "index.php?controller=pjAdminTime&action=pjActionCustom&id={:foreign_id}&date_id={:id}"});
			if(pjGrid.has_delete)
			{
				buttonsOpts.push({type: "delete", url: "index.php?controller=pjAdminTime&action=pjActionDeleteDate&id={:id}"});
			}
			if(pjGrid.has_delete_bulk)
			{
				actionsOpts.push({text: myLabel.delete_selected, url: "index.php?controller=pjAdminTime&action=pjActionDeleteDateBulk", render: true, confirmation: myLabel.delete_confirmation});
			}
			var $grid = $("#grid").datagrid({
				buttons: buttonsOpts,
				columns: [{text: myLabel.time_date, type: "date", sortable: true, editable: false},
				          {text: myLabel.time_start, type: "text", sortable: true, editable: false},
				          {text: myLabel.time_end, type: "text", sortable: true, editable: false},
				          {text: myLabel.time_lunch_start, type: "text", sortable: true, editable: false},
				          {text: myLabel.time_lunch_end, type: "text", sortable: true, editable: false},
				          {text: myLabel.time_price, type: "text", sortable: true, editable: false},
				          {text: myLabel.time_dayoff, type: "text", sortable: true, editable: false, renderer: formatDateOff}],
				dataUrl: "index.php?controller=pjAdminTime&action=pjActionGetDate" + pjGrid.queryString,
				dataType: "json",
				fields: ['date', 'start_time', 'end_time', 'start_lunch', 'end_lunch', 'price', 'is_dayoff'],
				paginator: {
					actions: actionsOpts,
					gotoPage: true,
					paginate: true,
					total: true,
					rowCount: true
				},
				saveUrl: "index.php?controller=pjAdminTime&action=pjActionSaveDate&id={:id}",
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
			var $this = $(this);
			$this.addClass("btn-primary active").removeClass("btn-default")
			.siblings(".btn").removeClass("btn-primary active").addClass("btn-default");
			var content = $grid.datagrid("option", "content"),
				cache = $grid.datagrid("option", "cache");
			$.extend(cache, {
				"is_dayoff": ""
			});
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminTime&action=pjActionGetDate" + pjGrid.queryString, "date", "ASC", content.page, content.rowCount);
			return false;
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
			obj[$this.data("column")] = $this.data("value");
			$.extend(cache, obj);
			$grid.datagrid("option", "cache", cache);
			$grid.datagrid("load", "index.php?controller=pjAdminTime&action=pjActionGetDate" + pjGrid.queryString, "date", "ASC", content.page, content.rowCount);
			return false;
		}).on("keypress", ".field-int", function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
			{
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				return false;
		    }
		}).on("keypress", ".spin", function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
			{
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				return false;
		    }
		}).on("keydown", ".tsPriceField", function (e) {
			if (e.shiftKey == true) {
                e.preventDefault();
            }

            if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode == 8 || e.keyCode == 9 || e.keyCode == 37 || e.keyCode == 39 || e.keyCode == 46 || e.keyCode == 190) {

            } else {
                e.preventDefault();
            }
            
            if($(this).val().indexOf('.') !== -1 && e.keyCode == 190)
            {
            	e.preventDefault();
            }
		}).on("change", ".number", function (e) {
			var val = parseFloat(this.value);
		    if (!isNaN(val)) {
		    	this.value = val.toFixed(2);
		    	if (parseFloat(this.value) >= 99999999999999.99) {
			    	this.value = '99999999999999.99';
			    	swal({
		    			title: "",
						text: myLabel.price_limit_msg,
						type: "warning",
						confirmButtonColor: "#DD6B55",
						confirmButtonText: "OK",
						closeOnConfirm: false,
						showLoaderOnConfirm: false
					}, function () {
						swal.close();
					});
			    }
		    }
		});
	});
})(jQuery_1_8_2);