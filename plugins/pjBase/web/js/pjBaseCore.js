var jQuery_1_8_2 = jQuery_1_8_2 || jQuery.noConflict();
(function ($, undefined) {
	$(function () {
		"use strict";
		
		var validate = ($.fn.validate !== undefined);
		
		if (validate) {
			$.validator.setDefaults({
				highlight: function(element) {
					$(element).closest('.form-group').addClass('has-error');
				},
				unhighlight: function(element) {
					$(element).closest('.form-group').removeClass('has-error');
				},
				errorElement: 'span',
				errorClass: 'help-block',
				errorPlacement: function(error, element) {
					if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2'));
                    } else if (element.parent('.input-group').length) {
						error.insertAfter(element.parent());
					} else if (element.parent().parent('.btn-group').length) {
                        error.insertAfter(element.parent().parent());
                    } else {
						error.insertAfter(element);
					}
			    },
                submitHandler: function (form) {
				    var ladda_buttons = $(form).find('.ladda-button');
				    if(ladda_buttons.length > 0)
                    {
                        var l = ladda_buttons.ladda();
                        l.ladda('start');
                    }
                    return true;
                }
			});
		}
		
		$.ajaxSetup({
			error: function (jqXHR, textStatus, errorThrown) {
				if (jqXHR.status === 401) {
					if (typeof swal === "function") {
						swal(errorThrown, "Authorization is required.", "error");
					}
				} else if (jqXHR.status === 403) {
					if (typeof swal === "function") {
						swal(errorThrown, "Requested operation is forbidden.", "error");
					}
				}
			}
		});
	});
})(jQuery_1_8_2);

/* Input hygiene: leading/trailing white space is removed from text fields, so a value made
   only of spaces counts as empty and is rejected by the "required" rule. Passwords are never changed. */
(function (window, document) {
	"use strict";
	var SKIP = /pass|token|secret|captcha|csrf/i,
		EDGE = /^[\s\u00A0\u200B\uFEFF]+|[\s\u00A0\u200B\uFEFF]+$/g,
		TYPES = {"text": 1, "email": 1, "tel": 1, "url": 1, "search": 1, "": 1};

	function clean(v) {
		return typeof v === "string" ? v.replace(EDGE, "") : v;
	}

	function trimmable(el) {
		if (!el || !el.tagName || el.disabled || el.readOnly) { return false; }
		var tag = el.tagName.toUpperCase();
		if (SKIP.test(el.name || "") || SKIP.test(el.id || "")) { return false; }
		if (tag === "TEXTAREA") { return true; }
		return tag === "INPUT" && TYPES[(el.getAttribute("type") || "").toLowerCase()] === 1;
	}

	function trimEl(el) {
		if (trimmable(el)) {
			var v = el.value, c = clean(v);
			if (c !== v) { el.value = c; }
		}
	}

	function trimForm(form) {
		if (!form || !form.querySelectorAll) { return; }
		var els = form.querySelectorAll("input, textarea"), i;
		for (i = 0; i < els.length; i++) { trimEl(els[i]); }
	}

	document.addEventListener("focusout", function (e) { trimEl(e.target); }, true);
	document.addEventListener("submit", function (e) { trimForm(e.target); }, true);
	document.addEventListener("click", function (e) {
		var b = e.target && e.target.closest ? e.target.closest("button, input[type=submit], input[type=button]") : null;
		if (b && b.form) { trimForm(b.form); }
	}, true);

	function patchValidator(jq) {
		if (jq) {
			jq(function () {
			var v = jq.validator;
			if (v && v.methods && v.methods.required && !v.methods.required.__pjTrim) {
				var orig = v.methods.required;
				v.methods.required = function (value, element, param) {
					var n = element && element.nodeName ? element.nodeName.toLowerCase() : "";
					if (typeof value === "string" && n !== "select" && !(element && (element.type === "checkbox" || element.type === "radio"))) {
						value = clean(value);
					}
					return orig.call(this, value, element, param);
				};
				v.methods.required.__pjTrim = true;
			}
			});
		}
	}
	patchValidator(window.jQuery);
	if (window.jQuery_1_8_2 && window.jQuery_1_8_2 !== window.jQuery) { patchValidator(window.jQuery_1_8_2); }
})(window, document);
