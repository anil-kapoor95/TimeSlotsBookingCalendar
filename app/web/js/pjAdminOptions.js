var jQuery_1_8_2 = jQuery_1_8_2 || $.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		var tabs = ($.fn.tabs !== undefined),
			tipsy = ($.fn.tipsy !== undefined),
			$tabs = $("#tabs"),
			dialog = ($.fn.dialog !== undefined),
			multilang = ($.fn.multilang !== undefined),
			$dialogCopyOptions = $("#dialogCopyOptions");;
		
		function myTinyMceDestroy() {
			
			if (window.tinymce === undefined) {
				return;
			}
			
			var iCnt = tinymce.editors.length;
			
			if (!iCnt) {
				return;
			}
			
			for (var i = 0; i < iCnt; i++) {
				tinymce.remove(tinymce.editors[i]);
			}
		}
		function myTinyMceInit(pSelector) {
			
			if (window.tinymce === undefined) {
				return;
			}
			
			tinymce.init({
				relative_urls : false,
				remove_script_host : false,
				convert_urls : true,
				browser_spellcheck : true,
			    contextmenu: false,
			    selector: pSelector,
			    theme: "modern",
			    height: 480,
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
		}
		
		if ($('.mceEditor').length > 0) {
			myTinyMceDestroy.call(null);
			myTinyMceInit.call(null, 'textarea.mceEditor');
        }
		if($(".field-int").length > 0)
        {
            $(".field-int").TouchSpin({
                verticalbuttons: true,
                buttondown_class: 'btn btn-white',
                buttonup_class: 'btn btn-white',
                max: 4294967295
            });
        }
		if (multilang && 'pjLocale' in window) {
			$(".multilang").multilang({
				langs: pjLocale.langs,
				flagPath: pjLocale.flagPath,
				tooltip: "",
				select: function (event, ui) {
					$("input[name='locale_id']").val(ui.index);
				}
			});
		}
		
		function reDrawCode() {
			var code = $("#hidden_code").text(),
				layout = $("select[name='layout']").find("option:selected").val(),
				locale = $("select[name='install_locale']").find("option:selected").val(),
				hide = $("input[name='install_hide']").is(":checked") ? "&hide=1" : "",
				switch_layout = $("input[name='switch']").is(":checked") ? "&switch=1" : "&switch=0",
				multi = $("input[name='multi']").is(":checked") ? "&multi=1" : "&multi=0",
				cid = $("select[name='cid']").find("option:selected").val();
			locale = typeof locale !== "undefined" && locale !== null && parseInt(locale.length, 10) > 0 ? "&locale=" + locale : "";
			layout = parseInt(layout.length, 10) > 0 ? "&layout=" + layout : "";
			cid = parseInt(cid, 10) > 0 ? "&cid=" + cid : "";
			$("#install_code").text(code.replace(/(&cid=\d+)/g, function(match, p1) {
	            return [cid, layout, locale, hide, switch_layout, multi].join("");
	        }));
		}
		
		if($('#pjInstallSettings').length > 0)
		{
			reDrawCode.call(null);
		}
		
		$(document).on("focus", ".textarea_install", function (e) {
			var $this = $(this);
			$this.select();
			$this.mouseup(function() {
				$this.unbind("mouseup");
				return false;
			});
		}).on("keyup", "#uri_page", function (e) {
			var tmpl = $("#hidden_htaccess").text(),
				tmpl_remote = $("#hidden_htaccess_remote").text(),
				index = this.value.indexOf("?");
			$("#install_htaccess").text(tmpl.replace('::URI_PAGE::', index >= 0 ? this.value.substring(0, index) : this.value));
			$("#install_htaccess_remote").text(tmpl_remote.replace('::URI_PAGE::', index >= 0 ? this.value.substring(0, index) : this.value));
		}).on("change", "select[name='layout']", function (e) {
			
			var $this = $(this),
				$selected = $this.find("option:selected"),
				val = $selected.val();

			if (val == 2 && $selected.data("ok") == 0) {
				$("#weekly_warn").parent().parent().show();
				$(".install_stuff, #btnPreview").hide();
			} else {
				$("#weekly_warn").parent().parent().hide();
				$(".install_stuff, #btnPreview").show();
			}
			
			reDrawCode.call(null);
			
		}).on("change", "select[name='install_locale']", function(e) {
            
            reDrawCode.call(null);
            
		}).on("change", "input[name='install_hide']", function (e) {
			
			reDrawCode.call(null);
			
		}).on("change", "input[name='switch']", function (e) {
			
			reDrawCode.call(null);
			
		}).on("change", "input[name='multi']", function (e) {
			
			reDrawCode.call(null);
			
		}).on("change", "select[name='cid']", function (e) {
			
			reDrawCode.call(null);
			
		}).on("click", ".pj-use-theme", function (e) {
			var theme = $(this).attr('data-theme');
			$('.pj-loader').css('display', 'block');
			$.ajax({
				type: "GET",
				async: false,
				url: 'index.php?controller=pjAdminOptions&action=pjActionUpdateTheme&theme=' + theme,
				success: function (data) {
					$('.theme-holder').html(data);
					$('.pj-loader').css('display', 'none');
				}
			});
		}).on("click", "#btnCopyOptions", function () {
			if ($dialogCopyOptions.length > 0 && dialog) {
				$dialogCopyOptions.dialog("open");
			}
		}).on("change", "#preview_calendar_id", function (e) {
			var cid = $(this).val();
			$('.pj_preview_install').each(function(){
				var href = $(this).attr('data-href');
				href = href.replace(/{CID}/g, cid);
				$(this).attr('href', href);
			});
		}).on( 'change', '.onoffswitch-checkbox', function (e) {
			var name = $(this).attr('name');
			if($(this).is(':checked'))
			{
				$('input[name="value-enum-'+name+'"]').val('1|0::1');
			}else{
				$('input[name="value-enum-'+name+'"]').val('1|0::0');
			}
		}).on("click", ".paymentLink", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var select_locale_id = null;
			var payment_method = $(this).attr('data-method');
			var calendar_id = $('#pjCalendarId').val();
			$('.pj-form-langbar-item').each(function(){
				if($(this).hasClass('btn-primary'))
				{
					select_locale_id = $(this).attr('data-index');
				}
			});
			$.get("index.php?controller=pjAdminOptions&action=pjActionPaymentOptions", {
				"calendar_id": calendar_id,
				"payment_method": payment_method
			}).done(function (data) {
				$('#modalContent').html(data);
				if (multilang && typeof pjLocale != "undefined")
				{
					var $multilangWrap = $('#modalContent').find('.pj-multilang-wrap');
					$multilangWrap.each(function(e){
						var locale_id = $(this).attr('data-index');
						if(locale_id == select_locale_id)
						{
							$(this).show();
						}else{
							$(this).hide();
						}
					})
				}
				$('#paymentModal').modal('show');
			});
			return false;
		}).on("click", "#btnSavePaymentOptions", function (e) {
			if (e && e.preventDefault) {
				e.preventDefault();
			}
			var calendar_id = $('#pjCalendarId').val();
			$.post("index.php?controller=pjAdminOptions&action=pjActionPaymentOptions&calendar_id=" + calendar_id, $('#frmPaymentOptions').serialize()).done(function (data) {
				$('#paymentModal').modal('hide');
				window.location.href = "index.php?controller=pjAdminOptions&action=pjActionPayments";
			});
			return false;
		}).on( 'change', '#enablePayment', function (e) {
			if ($(this).prop('checked')) {
                $('.hidden-area').show();
                $('#payment_is_active').val(1);
                $("#enableTestMode").trigger("change");
            }else {
                $('.hidden-area').hide();
                $('#payment_is_active').val(0);
            }
		}).on("change", "#enableTestMode", function (e) {
			if ($(this).is(":checked")) {
                $(".test-area").show();
                $(".live-area").hide();
                $("#payment_is_test_mode").val(1);
            } else {
                $(".test-area").hide();
                $(".live-area").show();
                $("#payment_is_test_mode").val(0);
            }
		}).on( 'change', '#o_reminder_enable', function (e) {
			if ($(this).prop('checked')) {
				$('.boxReminder').show();
            }else {
            	$('.boxReminder').hide();
            }
		});
		
		var $topMenu = $("#page-wrapper").children(".row.border-bottom"),
			$iframe = $("#iframeEditor"),
			$body = $("body"),
			$window = $(window);
	
		function resizeIframe() {
			if (!$iframe.length) {
				return;
			}
	
			$iframe.height($window.height() - $topMenu.outerHeight());
		}
	
		if ($iframe.length) {
	
			$iframe.on('load', function () {
			    var body = this.contentWindow.document.body;
			    if (body.getAttribute('data-editor'))
	            {
	                var script = document.createElement('script');
	                script.type = 'text/javascript';
	                script.async = true;
	                script.src = body.getAttribute('data-editor');
	                window.setTimeout(function () {
	                    body.appendChild(script);
	                }, 1200);
	            }
	
				var head = this.contentWindow.document.getElementsByTagName('head')[0],
					style = document.createElement('link');
				style.rel = 'stylesheet';
				style.href = 'third-party/font_awesome/4.7.0/css/font-awesome.min.css';
				head.appendChild(style);
			});
	
			$body.addClass("page-editor");
			resizeIframe.call(null);
	
			$window.on("resize", function () {
				resizeIframe.call(null);
			});
		}
	
		$(document).on('click', '.device-view', function (e) {
			e.preventDefault();
	
			var $this = $(this),
				device = $this.data('device'),
				orientation = $this.data('orientation'),
				$device = $('#iframeDevice'),
				$holder = $('#iframeHolder');
	
			$this.closest('.row').find('.device-view.active').removeClass('active').end().end().addClass('active');
	
			switch (device) {
			case 'desktop':
				$device.addClass('hidden');
				$iframe.insertBefore($device);
				$body.addClass('page-editor');
				$window.trigger('resize');
				break;
			case 'tablet':
			case 'phone':
				$iframe.appendTo($holder);
				$holder.removeClass().addClass(device + '-view-' + orientation);
				$device.removeClass('hidden');
				$body.removeClass('page-editor');
				$('#device_title').html($(['#', device, '_', orientation].join('')).html());
				$('#device_info').html($(['#', device, '_', orientation, '_info'].join('')).html());
				break;
			}
	
			return false;
		}).on('click', '[data-theme]', function (e) {
		    e.preventDefault();
		    
		    var $this = $(this);
		    
		    $.post("index.php?controller=pjAdminOptions&action=pjActionUpdateTheme", {
		    	theme: $this.data("index")
		    }).done(function (data) {
		    	if (data && data.status && data.status === "OK") {
		    		$this.closest(".dropdown-menu").find(".thumbnail").removeClass("active");
		    		$this.addClass("active");
		    	}
		    });
	
		    var $link = $(".open-new-window");
		    if ($link.length) {
		    	$link.attr("href", $link.attr("href").replace(/(&?theme=)theme\d+/, '$1' + $this.data("index")));
		    }
			$iframe.attr('src', $this.attr('href'));
			//$('.device-view.active').trigger('click');
		}).on("change", ".number", function (e) {
			var val = parseFloat(this.value);
		    if (!isNaN(val)) {
		    	this.value = val.toFixed(2);
		    	if (parseFloat(this.value) >= 99999999999999.99) {
			    	this.value = '99999999999999.99';
			    }
		    }
		});
		
		// ---------------------------------------------------------------------
		function notificationsGetMetaData() {
			var $box = $("#boxNotificationsMetaData");
			if (!$box.length) {
				return;
			}
			
			// show preloader
			$box.empty().addClass("ibox-content-notification");
			
			$('<div class="ibox-content-overlay"></div> \
				<div class="sk-spinner sk-spinner-double-bounce"> \
					<div class="sk-double-bounce1"></div> \
					<div class="sk-double-bounce2"></div> \
				</div>').appendTo($box);
			
			$box.find(".ibox-content-overlay, .sk-spinner").show();

			var search = window.location.search,
				variant = search.match(/&?variant=(\w+)/),
				transport = search.match(/&?transport=(\w+)/),
				params = {
					recipient: $('input[name="recipient"]:checked').val()
				};
			if (variant !== null && transport !== null) {
				
				params.variant = variant[1];
				params.transport = transport[1];
			}
			$.get("index.php?controller=pjAdminOptions&action=pjActionNotificationsGetMetaData", params).done(function (data) {
				
				$box.html(data);
				
				if (variant !== null && transport !== null) {
					$box.find(['#variant', transport[1], variant[1]].join("_")).trigger("change");
				} else {
					$box.find('input[name="variant"]:first').trigger("change");
				}
				
			});
		}
		
		function notificationsGetContent() {
			var $box = $("#boxNotificationsContent");
			if (!$box.length) {
				return;
			}
			
			// show preloader
			$box.empty().addClass("ibox-content-notification");
			
			$('<div class="ibox-content-overlay"></div> \
				<div class="sk-spinner sk-spinner-double-bounce"> \
					<div class="sk-double-bounce1"></div> \
					<div class="sk-double-bounce2"></div> \
				</div>').appendTo($box);
			
			$box.find(".ibox-content-overlay, .sk-spinner").show();
			
			var $checked = $('input[name="variant"]:checked');
			
			$.get("index.php?controller=pjAdminOptions&action=pjActionNotificationsGetContent", {
				recipient: $('input[name="recipient"]:checked').val(),
				variant: $checked.val(),
				transport: $checked.data("transport")
			}).done(function (data) {
				
				$box.html(data);
				
				myTinyMceDestroy.call(null);
				myTinyMceInit.call(null, 'textarea.mceEditor');
				
				var index = $(".pj-form-langbar-item.btn-primary").data("index");
				if (index !== undefined) {
					$box.find('.pj-multilang-wrap[data-index!="' + index + '"]').hide();
					$box.find('.pj-multilang-wrap[data-index="' + index + '"]').show();
				}
				$('.notifyTokens').hide();
				if($checked.val() == 'account' || $checked.val() == 'forgot')
				{
					$('.accountTokens').show();
				}else{
					$('.bookingTokens').show();
				}
			});
		}
		
		function notificationsSetContent(toggle) {
			
			var $box = $("#boxNotificationsContent");
			if (!$box.length) {
				return;
			}
			
			// show preloader
			$box.addClass("notification-box");
			
			$('<div class="ibox-content-overlay"></div> \
				<div class="sk-spinner sk-spinner-double-bounce"> \
					<div class="sk-double-bounce1"></div> \
					<div class="sk-double-bounce2"></div> \
				</div>').appendTo($box);
			
			$box.find(".ibox-content-overlay, .sk-spinner").show();
			
			var postData,
				$form = $box.find("form");
			
			if (toggle) {
				postData = $.param({
					is_active: ($form.find("#is_active").is(":checked") ? 1 : 0),
					id: $form.find('input[name="id"]').val()
				});
			} else {
				postData = $form.serialize();
				postData = postData.replace(/&?is_active=(\w+)?/, "");
				
				var l = Ladda.create($form.find(":submit").get(0));
				l.start();
			}
			
			$.post("index.php?controller=pjAdminOptions&action=pjActionNotificationsSetContent", postData).done(function (data) {
				
				if (data && data.status && data.status === "OK") {
					
					notificationsGetMetaData.call(null);
					
				}
				
			});
		}
		
$("#boxNotificationsWrapper").on("change", 'input[name="recipient"]', function () {
			
			var search = window.location.search,
				recipient = search.match(/&?recipient=(\w+)/),
				variant = search.match(/&?variant=(\w+)/),
				transport = search.match(/&?transport=(\w+)/);
			
			var arr = [];
			arr.push("index.php?controller=pjAdminOptions&action=pjActionNotifications&recipient=");
			arr.push(this.value);
			
			if (recipient !== null && recipient[1] === this.value) {
				if (variant !== null && transport !== null) {
					arr.push("&transport=");
					arr.push(transport[1]);
					arr.push("&variant=");
					arr.push(variant[1]);
				}
			}
			
			var url = arr.join("");
			history.pushState({
				url: url,
				title: null
			}, null, url);
			
			notificationsGetMetaData.call(null);
			
		}).on("change", 'input[name="variant"]', function () {
			
			var $this = $(this);
			
			var url = ["index.php?controller=pjAdminOptions&action=pjActionNotifications&recipient=", $('input[name="recipient"]:checked').val(), "&transport=", $this.data("transport"), "&variant=", $this.val()].join("");
			history.pushState({
				url: url,
				title: null
			}, null, url);
			
			notificationsGetContent.call(null);
			
		}).on("change", '#is_active', function () {
			
			notificationsSetContent.call(null, true);
			
			var $this = $(this),
				$hidden = $this.closest("form").find(".notification-area");
			
			if ($this.is(":checked")) {
				$hidden.removeClass("hidden");
			} else {
				$hidden.addClass("hidden");
			}
			
		}).on("submit", "form", function (e) {
			e.preventDefault();
			
			notificationsSetContent.call(null, false);
			
			return false;
		});
		
		$('input[name="recipient"]:checked').trigger("change");
		
		$(window).on("popstate", function (e) {
			var state = e.originalEvent.state;
			if (state !== null) {
				//load
			} else {
				//empty
			}
		});
		// ---------------------------------------------------------------------
	});
})(jQuery_1_8_2);
/* Theme 11: "Customize design" side panel on the Preview page (live preview in the iframe, save / reset via AJAX) */
(function ($, undefined) {
	$(function () {
		"use strict";

		var cfg = window.tsbTheme11,
			$panel = $('#tsbcColorsPanel'),
			$tab = $('#tsbcColorsBtn'),
			$iframe = $('#iframeEditor');
		if (!cfg || !$panel.length || !$iframe.length) {
			return;
		}

		var T = cfg.t,
			C = $.extend({}, cfg.defaults, cfg.saved),
			softCustom = !!(cfg.saved && cfg.saved.soft),
			SECTIONS = [
				['brand', [['primary', 'c_primary'], ['accent', 'c_accent']]],
				['surfaces', [['bg', 'c_bg'], ['surface', 'c_surface'], ['soft', 'c_soft'], ['border', 'c_border']]],
				['text', [['heading', 'c_heading'], ['text', 'c_text'], ['muted', 'c_muted']]]
			],
			BASE = ['primary', 'accent', 'bg', 'surface', 'heading', 'text', 'muted', 'border'],
			PRESETS = [
				{n: 'Sunset Coral', v: {}},
				{n: 'Sage', v: {primary: '#5f7f4a', accent: '#8aa270', bg: '#f3f5ee', surface: '#ffffff', heading: '#27341f', text: '#3a4733', muted: '#68775f', border: '#dde5d3'}},
				{n: 'Ocean', v: {primary: '#1c6fb8', accent: '#0e8a7d', bg: '#eef5fb', surface: '#ffffff', heading: '#0f2a43', text: '#2c4156', muted: '#5a7288', border: '#d3e2ef'}},
				{n: 'Lavender', v: {primary: '#6d4bc2', accent: '#9a7fe0', bg: '#f4f0fb', surface: '#ffffff', heading: '#2a1d4d', text: '#433a5c', muted: '#74698d', border: '#e2d9f2'}},
				{n: 'Forest', v: {primary: '#2f7d4f', accent: '#b8761a', bg: '#f0f5ef', surface: '#ffffff', heading: '#173324', text: '#34493c', muted: '#5f7767', border: '#d5e3d8'}},
				{n: 'Gold', v: {primary: '#a8741a', accent: '#d4a63a', bg: '#fbf6e8', surface: '#ffffff', heading: '#3b2c0d', text: '#54442a', muted: '#7f6f4c', border: '#eadfbd'}},
				{n: 'Rose', v: {primary: '#b8365f', accent: '#d9728f', bg: '#fbeff3', surface: '#ffffff', heading: '#44162a', text: '#5a3445', muted: '#85606e', border: '#efd3dc'}},
				{n: 'Mint', v: {primary: '#157a68', accent: '#62c3ad', bg: '#ecf8f4', surface: '#ffffff', heading: '#0f3d34', text: '#2f524b', muted: '#58796f', border: '#cfe9e1'}}
			],
			SYS = "-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif",
			timer = null;

		function lum(hex) {
			var n = parseInt(hex.slice(1), 16),
				c = [n >> 16 & 255, n >> 8 & 255, n & 255].map(function (v) {
					v /= 255;
					return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
				});
			return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2];
		}
		function ratio(a, b) {
			var x = lum(a), y = lum(b);
			return (Math.max(x, y) + 0.05) / (Math.min(x, y) + 0.05);
		}
		function contrastOn(hex) {
			return ratio(hex, '#ffffff') >= ratio(hex, '#111111') ? '#ffffff' : '#111111';
		}
		// mix(a, b, t): t share of a, the rest of b (same maths as color-mix in the theme CSS)
		function mix(a, b, t) {
			var x = parseInt(a.slice(1), 16), y = parseInt(b.slice(1), 16), out = '#';
			$.each([16, 8, 0], function (i, sh) {
				var v = Math.round((x >> sh & 255) * t + (y >> sh & 255) * (1 - t));
				out += (v < 16 ? '0' : '') + v.toString(16);
			});
			return out;
		}
		function derivedSoft() {
			return mix(C.primary, C.surface, 0.11);
		}
		function fontStack(name) {
			if (name === 'Playfair Display') {
				return "'Playfair Display', Georgia, 'Times New Roman', serif";
			}
			if (!cfg.fonts[name]) {
				return 'system-ui, ' + SYS;
			}
			return "'" + name + "', " + SYS;
		}
		function cssVars() {
			var btn = {pill: '999px', rounded: '10px', square: '3px'};
			return {
				'--tsb-primary': C.primary, '--tsb-primary-contrast': contrastOn(C.primary), '--tsb-accent': C.accent, '--tsb-primary-soft': C.soft,
				'--tsb-bg': C.bg, '--tsb-surface': C.surface, '--tsb-heading': C.heading, '--tsb-text': C.text, '--tsb-muted': C.muted, '--tsb-border': C.border,
				'--tsb-font-heading': fontStack(C.font_heading), '--tsb-font-body': fontStack(C.font_body),
				'--tsb-radius': C.radius + 'px', '--tsb-radius-sm': Math.round(C.radius * 0.66) + 'px', '--tsb-radius-btn': btn[C.button] || btn.pill
			};
		}
		function fontsUrl() {
			var fam = {};
			$.each([C.font_heading, C.font_body], function (i, n) {
				if (cfg.fonts[n]) {
					fam[cfg.fonts[n]] = 'family=' + cfg.fonts[n] + ':wght@400;500;600;700';
				}
			});
			var parts = $.map(fam, function (v) { return v; });
			return parts.length ? 'https://fonts.googleapis.com/css2?' + parts.join('&') + '&display=swap' : '';
		}
		function wrapper() {
			try {
				var doc = $iframe[0].contentWindow.document;
				return {doc: doc, el: doc.querySelector('[id^="pjWrapperTSBCalendar_"]')};
			} catch (e) {
				return {doc: null, el: null};
			}
		}
		function applyLive(clear) {
			var w = wrapper();
			if (!w.el) {
				return false;
			}
			$.each(cssVars(), function (k, v) {
				if (clear) {
					w.el.style.removeProperty(k);
				} else {
					w.el.style.setProperty(k, v);
				}
			});
			var url = clear ? '' : fontsUrl(), link = w.doc.getElementById('tsbcLiveFonts');
			if (url) {
				if (!link) {
					link = w.doc.createElement('link');
					link.id = 'tsbcLiveFonts';
					link.rel = 'stylesheet';
					w.doc.head.appendChild(link);
				}
				if (link.getAttribute('href') !== url) {
					link.setAttribute('href', url);
				}
			}
			return true;
		}
		// the widget is built by JavaScript after the iframe loads, so retry for a few seconds
		function applyWhenReady() {
			var tries = 0;
			window.clearInterval(timer);
			timer = window.setInterval(function () {
				tries++;
				if (applyLive(false) || tries > 40) {
					window.clearInterval(timer);
				}
			}, 250);
		}
		function check(label, fg, bg) {
			var r = ratio(fg, bg), ok = r >= 4.5;
			return '<div class="tsbc-chk"><span>' + label + '</span><b class="' + (ok ? 'ok' : 'bad') + '" title="' + (ok ? '' : T.low) + '">' + r.toFixed(1) + ':1 ' + (ok ? '&#10003;' : '&#9888;') + '</b></div>';
		}
		function checksHtml() {
			return check(T.chk_button, contrastOn(C.primary), C.primary) + check(T.chk_text, C.text, C.surface) + check(T.chk_muted, C.muted, C.surface) + check(T.chk_brand, C.primary, C.bg);
		}
		function colorRow(key, labelKey) {
			return '<div class="tsbc-fld"><label>' + T[labelKey] + '</label><span class="tsbc-clr"><input type="color" data-k="' + key + '" value="' + C[key] + '"><input type="text" class="tsbc-hex" data-hex="' + key + '" value="' + C[key] + '" maxlength="7" spellcheck="false" autocomplete="off"></span></div>';
		}
		function fontSelect(key, labelKey) {
			var opts = $.map(cfg.fonts, function (v, name) {
				return '<option value="' + name + '"' + (C[key] === name ? ' selected' : '') + '>' + name + '</option>';
			}).join('');
			return '<div class="tsbc-fld"><label>' + T[labelKey] + '</label><select class="form-control input-sm" data-k="' + key + '">' + opts + '</select></div>';
		}
		function presetColors(v) {
			var c = $.extend({}, cfg.defaults, v);
			return [c.bg, mix(c.primary, '#ffffff', 0.62), c.primary, c.heading];
		}
		function presetOn(p) {
			var v = $.extend({}, cfg.defaults, p.v), same = true;
			$.each(BASE, function (i, k) {
				if (String(C[k]).toLowerCase() !== String(v[k]).toLowerCase()) {
					same = false;
				}
			});
			return same;
		}
		function renderPanel() {
			var keep = $panel.find('.tsbc-body').scrollTop() || 0;
			var presets = $.map(PRESETS, function (p) {
				var sw = $.map(presetColors(p.v), function (c) { return '<s style="background:' + c + '"></s>'; }).join('');
				return '<button type="button" class="tsbc-preset' + (presetOn(p) ? ' on' : '') + '" data-preset="' + p.n + '" title="' + p.n + '"><i>' + sw + '</i><span>' + p.n + '</span></button>';
			}).join('');
			var btns = $.map(['pill', 'rounded', 'square'], function (b) {
				return '<button type="button" data-btn="' + b + '" class="' + (C.button === b ? 'on' : '') + '">' + T['btn_' + b] + '</button>';
			}).join('');
			var secs = $.map(SECTIONS, function (sec) {
				return '<h5>' + T[sec[0]] + '</h5>' + $.map(sec[1], function (c) { return colorRow(c[0], c[1]); }).join('');
			}).join('');
			var html = '<div class="tsbc-head"><i class="fa fa-paint-brush"></i><h3>' + T.title + '</h3><button type="button" class="tsbc-close" data-tsbc="close" aria-label="' + T.close + '"><i class="fa fa-times"></i></button></div>' +
				'<div class="tsbc-body"><p class="tsbc-info">' + T.info + '</p>' +
				'<h5 class="tsbc-first">' + T.presets + '</h5><div class="tsbc-presets">' + presets + '</div><hr>' +
				secs + '<hr>' +
				'<h5>' + T.typography + '</h5>' + fontSelect('font_heading', 'font_heading') + fontSelect('font_body', 'font_body') +
				'<h5>' + T.shape + '</h5>' +
				'<div class="tsbc-fld"><label>' + T.radius + ' <small class="text-muted" id="tsbcRadiusVal">' + C.radius + ' px</small></label><input type="range" min="0" max="32" value="' + C.radius + '" data-k="radius"></div>' +
				'<div class="tsbc-fld"><label>' + T.button + '</label><span class="tsbc-seg">' + btns + '</span></div>' +
				'<h5>' + T.access + '</h5><div id="tsbcChecks">' + checksHtml() + '</div></div>' +
				'<div class="tsbc-foot"><div class="tsbc-msg" id="tsbcMsg"></div><div class="tsbc-actions"><button type="button" class="tsbc-btn tsbc-btn-reset" data-tsbc="reset">' + T.reset + '</button><button type="button" class="tsbc-btn tsbc-btn-save" data-tsbc="save"><i class="fa fa-check"></i> ' + T.save + '</button></div></div>';
			$panel.html(html);
			$panel.find('.tsbc-body').scrollTop(keep);
		}
		function setSoftFromPrimary() {
			if (!softCustom) {
				C.soft = derivedSoft();
				$panel.find('[data-k="soft"]').val(C.soft);
				$panel.find('[data-hex="soft"]').val(C.soft);
			}
		}
		function msg(text, ok) {
			$('#tsbcMsg').text(text).css('color', ok ? '#15803d' : '#b91c1c');
		}
		function placeTab() {
			var $bar = $('.bar-top');
			if ($bar.length) {
				var r = $bar[0].getBoundingClientRect();
				$tab.css('top', Math.max(60, r.bottom + 30) + 'px');
			}
		}
		function openPanel(open) {
			$panel.toggleClass('open', open);
			$tab.toggleClass('tsbc-tab-off', open);
			if (open) {
				renderPanel();
				applyLive(false);
			}
		}

		if (!softCustom) {
			C.soft = derivedSoft();
		}
		placeTab();
		$(window).on('resize scroll', placeTab);

		$(document).on('click', '#tsbcColorsBtn', function (e) {
			e.preventDefault();
			openPanel(true);
		});
		$panel.on('click', '[data-tsbc="close"]', function () {
			openPanel(false);
		}).on('click', '[data-preset]', function () {
			var name = $(this).data('preset'),
				p = $.grep(PRESETS, function (x) { return x.n === name; })[0];
			C = $.extend({}, cfg.defaults, p.v);
			softCustom = false;
			C.soft = derivedSoft();
			renderPanel();
			applyLive(false);
		}).on('click', '[data-btn]', function () {
			C.button = $(this).data('btn');
			$panel.find('[data-btn]').removeClass('on').filter(this).addClass('on');
			applyLive(false);
		}).on('input change', 'input[data-k], select[data-k]', function () {
			var k = $(this).data('k'), v = $(this).val();
			C[k] = k === 'radius' ? parseInt(v, 10) : v;
			if (k === 'radius') {
				$('#tsbcRadiusVal').text(C.radius + ' px');
			}
			if (k === 'soft') {
				softCustom = true;
			}
			if (k === 'primary' || k === 'surface') {
				setSoftFromPrimary();
			}
			$panel.find('[data-hex="' + k + '"]').val(v);
			$panel.find('.tsbc-preset').each(function () {
				var name = $(this).data('preset'), p = $.grep(PRESETS, function (x) { return x.n === name; })[0];
				$(this).toggleClass('on', presetOn(p));
			});
			$('#tsbcChecks').html(checksHtml());
			applyLive(false);
		}).on('input', 'input[data-hex]', function () {
			var k = $(this).data('hex'), v = $.trim($(this).val());
			if (v.charAt(0) !== '#') {
				v = '#' + v;
			}
			var ok = /^#[0-9a-fA-F]{6}$/.test(v);
			$(this).toggleClass('tsbc-bad', !ok);
			if (ok) {
				v = v.toLowerCase();
				$panel.find('input[data-k="' + k + '"]').val(v).trigger('input');
			}
		}).on('click', '[data-tsbc="save"]', function () {
			var out = $.extend({}, C);
			if (!softCustom) {
				delete out.soft;
			}
			$.post('index.php?controller=pjAdminOptions&action=pjActionSaveThemeColors', {colors: JSON.stringify(out), csrf_token: cfg.csrf}).done(function (data) {
				msg(data && data.status === 'OK' ? T.saved : T.error, data && data.status === 'OK');
			}).fail(function () {
				msg(T.error, false);
			});
		}).on('click', '[data-tsbc="reset"]', function () {
			$.post('index.php?controller=pjAdminOptions&action=pjActionSaveThemeColors', {reset: 1, csrf_token: cfg.csrf}).done(function (data) {
				if (data && data.status === 'OK') {
					applyLive(true);
					C = $.extend({}, cfg.defaults);
					softCustom = false;
					C.soft = derivedSoft();
					renderPanel();
					msg(T.was_reset, true);
				} else {
					msg(T.error, false);
				}
			}).fail(function () {
				msg(T.error, false);
			});
		});

		$iframe.on('load', function () {
			if ($panel.hasClass('open')) {
				applyWhenReady();
			}
		});
		// theme picker: the panel only makes sense for Theme 11
		$(document).on('click', '[data-theme]', function () {
			var is11 = $(this).data('index') === 'theme11';
			$tab.toggleClass('hidden', !is11);
			if (!is11) {
				openPanel(false);
			}
		});
	});
})(jQuery_1_8_2);
