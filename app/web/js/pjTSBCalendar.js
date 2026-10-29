/*!
 * TimeSlots Booking Calendar v3.0
 * http://www.phpjabbers.com/time-slots-booking-calendar/
 * 
 * Copyright 2014, PHPJabbers
 * 
 * Date: Wed Nov 27 17:17:20 2013 +0200
 */
(function (window, undefined) {
	"use strict";
	
	pjQ.$.ajaxSetup({
		xhrFields: {
			withCredentials: true
		}
	});
	
	var document = window.document,
		validate = (pjQ.$.fn.validate !== undefined),
		dialog = (pjQ.$.fn.dialog !== undefined),
		$dialogTerms,
		routes = [
		    {pattern: /^#!\/Calendar$/, eventName: "loadCalendar"},
		    {pattern: /^#!\/Calendar\/date:([\d\-\.\/]+)?$/, eventName: "loadCalendar"},
			{pattern: /^#!\/((?:19|20)\d\d)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])$/, eventName: "loadCalendar"},
		    {pattern: /^#!\/Cart$/, eventName: "loadCart"},
			{pattern: /^#!\/Checkout$/, eventName: "loadCheckout"},
			{pattern: /^#!\/Timeslots$/, eventName: "loadTimeslots"},
			{pattern: /^#!\/Timeslots\/date:([\d\-\.\/]+)?$/, eventName: "loadTimeslots"},
			{pattern: /^#!\/Timeslots\/((?:19|20)\d\d)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])$/, eventName: "loadTimeslots"},
			{pattern: /^#!\/Weekly$/, eventName: "loadWeekly"},
			{pattern: /^#!\/Weekly\/date:([\d\-\.\/]+)?$/, eventName: "loadWeekly"},
			{pattern: /^#!\/Weekly\/((?:19|20)\d\d)\/(0[1-9]|1[012])\/(0[1-9]|[12][0-9]|3[01])$/, eventName: "loadWeekly"},
			{pattern: /^#!\/Preview$/, eventName: "loadPreview"},
			{pattern: /^#!\/Booking\/([A-Z]{2}\d{10})$/, eventName: "loadBooking"},
			{pattern: /^#!\/Login$/, eventName: "loadLogin"},
			{pattern: /^#!\/Profile$/, eventName: "loadProfile"},
			{pattern: /^#!\/Forgot$/, eventName: "loadForgot"},
			{pattern: /^#!\/IpBlocked$/, eventName: "loadIpBlocked"}
		];
	
	function log() {
		if (window.console && window.console.log) {
			for (var x in arguments) {
				if (arguments.hasOwnProperty(x)) {
					window.console.log(arguments[x]);
				}
			}
		}
	}
	
	function assert() {
		if (window && window.console && window.console.assert) {
			window.console.assert.apply(window.console, arguments);
		}
	}
	
	function hashBang(value) {
		if (value !== undefined && value.match(/^#!\//) !== null) {
			if (window.location.hash == value) {
				return false;
			}
			window.location.hash = value;
			return true;
		}
		
		return false;
	}
	
	function onHashChange() {
		var i, iCnt, m;
		for (i = 0, iCnt = routes.length; i < iCnt; i++) {
			m = unescape(window.location.hash).match(routes[i].pattern);
			if (m !== null) {
				pjQ.$(window).trigger(routes[i].eventName, m.slice(1));
				break;
			}
		}
		if (m === null) {
			pjQ.$(window).trigger("loadRouter");
		}
	}
	
	pjQ.$(window).on("hashchange", function (e) {
    	onHashChange.call(null);
    });
	
	if (!Date.prototype.toISOString) {
		(function(){
			function pad(number) {
				var r = String(number);
				if (r.length === 1) {
					r = '0' + r;
				}
				return r;
			}

			Date.prototype.toISOString = function() {
				return this.getUTCFullYear()
				+ '-' + pad(this.getUTCMonth() + 1)
				+ '-' + pad(this.getUTCDate())
				+ 'T' + pad(this.getUTCHours())
				+ ':' + pad(this.getUTCMinutes())
				+ ':' + pad(this.getUTCSeconds())
				+ '.' + String((this.getUTCMilliseconds()/1000).toFixed(3)).slice(2, 5)
				+ 'Z';
			};
		}());
	}
	
	function TSBCalendar(options) {
		if (!(this instanceof TSBCalendar)) {
			return new TSBCalendar(options);
		}
		
		this.reset.call(this);
		this.init.call(this, options);
		
		return this;
	}
	
	// --- Public methods/members
	TSBCalendar.prototype = {
		reset: function () {
			this.$container = null;
			this.container = null;
			this.view = null;
			this.date = new Date().toISOString().slice(0, 10);
			this.booking_uuid = null;
			this.layout = 1;
			this.duplicated = null;
			this.cid = null;
			this.custom = null;
			this.options = {};
			
			return this;
		},
		init: function(opts) {
			var self = this;
			this.options = opts;

			self.layout = this.options.layout;
			self.cid = this.options.cid;
			
			this.container = document.getElementById("pjWrapperTSBCalendar_" + self.cid);
			if (this.container) {
				this.$container = pjQ.$(this.container);
			}
			
			this.$container.on("click.rb", ".pjTsSelectorLocale", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var locale = pjQ.$(this).data("id");
				self.options.locale = locale;
				pjQ.$(this).addClass("pjTsLocaleFocus").parent().parent().find("a.pjTsLocaleFocus").not(this).removeClass("pjTsLocaleFocus");
				
				pjQ.$.get([self.options.folder, "index.php?controller=pjFront&action=pjActionLocale", "&session_id=", self.options.session_id].join(""), {
					"locale_id": locale
				}).done(function (data) {
					onHashChange.call(null);
				}).fail(function () {
					log("Deferred is rejected");
				});
				return false;
			}).on("click.ts", ".pjTsCalendarLinkMonth", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this),
					month = String($this.data("month"));
				hashBang(["#!", $this.data("year"), (month.length === 1 ? '0' + month : month), '01'].join("/"));
				
				return false;
				
			}).on("click.ts", ".pjTsCalendarDate", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var iso = pjQ.$(this).data("iso").split("-");
				pjQ.$('.tooltipster-base').hide();
				if(pjQ.$(this).attr('data-custom'))
				{
					self.custom = 1;
				}else{
					self.custom = 0;
				}
				hashBang(["#!/Timeslots", iso[0], iso[1], iso[2]].join("/"));
				return false;
				
			}).on("click.ts", ".pjTsViewTab", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				pjQ.$('.pjTsViewTab').removeClass('active');
				pjQ.$(this).addClass('active');
				self.layout = parseInt(pjQ.$(this).attr('data-layout'), 10);
				switch (self.layout) {
				case 2:
					hashBang("#!/Weekly");
					break;
				case 1:
				default:
					hashBang("#!/Calendar");
					break;
				}
				return false;
			}).on("click.ts", ".tsSelectorToggleLegend", function (e) {	
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.$container.find(".tsSelectorLegend").slideToggle();
				
				return false;
				
			}).on("click.ts", ".pjTsSelectorCalendar", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				var iso = self.date.split("-");
				switch (self.layout) {
				case 2:
					hashBang(["#!/Weekly", iso[0], iso[1], iso[2]].join("/"));
					break;
				case 1:
				default:
					hashBang(["#!", iso[0], iso[1], iso[2]].join("/"));
					break;
				}
				return false;
				
			}).on("click.ts", ".pjTsSelectorCart", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				
				hashBang("#!/Cart");
				
				return false;
				
			}).on("click.ts", ".pjTsSelectorCheckout", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Checkout");
				
				return false;
				
			}).on("click.ts", ".pjTsSelectorPreview", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang("#!/Preview");
				
				return false;

			}).on("click.ts", ".pjTsSelectorAddToCart", function (e) {
				if (e && e.stopPropagation) {
					e.stopPropagation();
				}
				self.add.call(self, pjQ.$(this).data()).done(function (data) {
					if(data.status == 'OK')
					{
						switch (self.layout) {
						case 2:
							if(self.custom == 1)
							{
								pjQ.$(window).trigger("loadTimeslots", self.date);
							}else{
								pjQ.$(window).trigger("loadWeekly", self.date);
							}
							break;
						case 1:
						default:
							pjQ.$(window).trigger("loadTimeslots", self.date);
							break;
						}
					}else if(data.status == 'ERR' && data.code == '101'){
						pjQ.$('#pjTsMaxSlotsModal').find('.modal-body').html(data.text);
						pjQ.$('#pjTsMaxSlotsModal').modal('show');
					}
				});
				
				return false;
				
			}).on("click.ts", ".pjTsSelectorRemoveFromCart", function (e) {
				if (e && e.stopPropagation) {
					e.stopPropagation();
				}
				var $this = pjQ.$(this);
				self.remove.call(self, $this.data()).done(function (data) {
					switch (self.layout) {
					case 2:
						if ($this.hasClass("tsSelectorRemoveTimeslot")) {
							if(self.custom == 1)
							{
								pjQ.$(window).trigger("loadTimeslots", self.date);
							}else{
								pjQ.$(window).trigger("loadWeekly", self.date);
							}
						} else {
							pjQ.$(window).trigger("loadCart");
						}
						break;
					case 1:
					default:
						if ($this.hasClass("tsSelectorRemoveTimeslot")) {
							pjQ.$(window).trigger("loadTimeslots", self.date);
						} else {
							pjQ.$(window).trigger("loadCart");
						}
						break;
					}
				});
				
				return false;
				
			}).on("change.ts", "select[name='payment_method']", function (e) {
				self.$container.find(".pjTsSelectorCCard").hide();
				self.$container.find(".pjTsSelectorBank").hide();
				switch (pjQ.$(this).find("option:selected").val()) {
				case 'creditcard':
					self.$container.find(".pjTsSelectorCCard").show();
					break;
				case 'bank':
					self.$container.find(".pjTsSelectorBank").show();
					break;
				}
			}).on("click.ts", ".pjTsSelectorWeeklyNav", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				hashBang(["#!/Weekly", pjQ.$(this).data("date")].join("/"));
				return false;
			}).on("change.ts", ".pjTsSwitchCalendar", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				self.cid = pjQ.$(this).val();
				if (self.layout === 2) {
					pjQ.$(window).trigger("loadWeekly");
				} else {
					pjQ.$(window).trigger("loadCalendar");
				}
				return false;
			}).on('click.ts', '#pjTsCaptchaImage', function(e){
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $captchaImg = pjQ.$(this);
				if($captchaImg.length > 0){
					var rand = Math.floor((Math.random()*999999)+1); 
					$captchaImg.attr("src", self.options.folder + 'index.php?controller=pjFrontEnd&action=pjActionCaptcha&rand=' + rand + "&session_id=" + self.options.session_id);
					self.$container.find('input[name="captcha"]').val("");
				}
				return false;
			}).on('click.ts', '.slot-select', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}

				return false;
			}).on('change.ts', '.slot-select', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				var $row = pjQ.$(this).parent().parent();
				var date = $row.attr('data-date');
				var startTs = $row.attr('data-start_ts');
				var endTs = $row.attr('data-end_ts');
				var cnt = pjQ.$(this).val();

				pjQ.$.post([self.options.folder + 'index.php?controller=pjFrontEnd&action=pjActionUpdateSlotCnt'].join(''), {
					'cid': self.cid,
					'date': date,
					'startTs': startTs,
					'endTs': endTs,
					'cnt': cnt
				}).done(function (data) {
					if(data.status == 'ERR')
					{
						$this.val("");
					}
					if (!$row.hasClass('selected-item')) {
						$row.trigger('click');
					} else {
						pjQ.$(window).trigger("loadTimeslots", self.date);
					}
				});
			}).on('change.ts', '.weekly-slot-select', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $this = pjQ.$(this);
				var date = $this.attr('data-date');
				var startTs = $this.attr('data-start_ts');
				var endTs = $this.attr('data-end_ts');
				var cnt = pjQ.$(this).val();
				var $next_label = $this.next();

				pjQ.$.post([self.options.folder + 'index.php?controller=pjFrontEnd&action=pjActionUpdateSlotCnt'].join(''), {
					'cid': self.cid,
					'date': date,
					'startTs': startTs,
					'endTs': endTs,
					'cnt': cnt
				}).done(function (data) {
					if(data.status == 'ERR')
					{
						$this.val("");
					}
					if (data.in_cart == 'F') {
						$next_label.trigger('click');
					} else {
						pjQ.$(window).trigger("loadWeekly", self.date);
					}
				});
			}).on('click.ts', '.pjTsLogin', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!hashBang("#!/Login")) 
				{
					self.loadLogin.call(self);
				}
				return false;
			}).on('click.ts', '.pjTsLogout', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var ajax_url = [self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionLogout"].join("");
				if(self.options.session_id != '')
				{
					ajax_url = [self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionLogout", "&session_id=", self.options.session_id].join("");
				}
				self.disableButtons.call(self);
				pjQ.$.get(ajax_url,{
					"cid": self.cid,
					"locale": self.options.locale,
					"multi" : self.options.multi
				}).done(function (data) {
					switch (self.layout) {
					case 2:
						if(!hashBang("#!/Weekly"))
						{
							self.loadWeekly.call(self);
						}
						break;
					case 1:
					default:
						if(!hashBang("#!/Calendar"))
						{
							self.loadCalendar.call(self);
						}
						break;
					}
				}).fail(function () {
					
				});
			}).on('click.ts', '.pjTsForgot', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!hashBang("#!/Forgot")) 
				{
					self.loadForgot.call(self);
				}
			}).on('click.ts', '.pjTsProfile', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				if (!hashBang("#!/Profile")) 
				{
					self.loadProfile.call(self);
				}
			}).on('click.ts', '.pjTsApplyCode', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var voucher_code = pjQ.$('#pjTsVoucherCodeField').val();
				if(voucher_code != '')
				{
					pjQ.$.get([self.options.folder, "index.php?controller=pjFrontEnd&action=pjActionApplyCode"].join(""), {
						"session_id": self.options.session_id,
						"cid": self.cid,
						"locale": self.options.locale,
						"multi" : self.options.multi,
						"voucher_code" : voucher_code
					}).done(function (data) {
						pjQ.$('#pjTsVoucherMessage').removeClass('alert-danger alert-info');
						if(data.status == "OK")
						{
							pjQ.$('#pjTsVoucherMessage').html(data.text).addClass('alert-info').show();
							pjQ.$('.pjTsApplyCode').hide();
							pjQ.$('.pjTsRemoveCode').show();
						}else{
							pjQ.$('#pjTsVoucherMessage').html(data.text).addClass('alert-danger').show();
						}
						self.getSummary.call(self);
					});
				}
			}).on('click.ts', '.pjTsRemoveCode', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var voucher_code = pjQ.$('#pjTsVoucherCodeField').val();
				if(voucher_code != '')
				{
					pjQ.$.get([self.options.folder, "index.php?controller=pjFrontEnd&action=pjActionRemoveCode"].join(""), {
						"session_id": self.options.session_id,
						"cid": self.cid,
						"locale": self.options.locale,
						"multi" : self.options.multi,
						"voucher_code" : voucher_code
					}).done(function (data) {
						pjQ.$('#pjTsVoucherCodeField').val("");
						pjQ.$('#pjTsVoucherMessage').html("").hide();
						pjQ.$('.pjTsApplyCode').show();
						pjQ.$('.pjTsRemoveCode').hide();
						self.getSummary.call(self);
					});
				}
			}).on("change.ts", "input[name='customer_email']", function(){
				var $this = pjQ.$(this);				
				if($this.val() != '')
				{
					pjQ.$.get([self.options.folder, "index.php?controller=pjFrontEnd&action=pjActionCheckClientAccount", "&session_id=", self.options.session_id, "&cid=", self.cid, "&email=", $this.val()].join("")).done(function (data) {
						if (data == 'true') {
							pjQ.$('#pjTsLoginModal_' + self.cid).modal('show');
						}
					});					
				}
			}).on("click.ts", ".pjTsForgotPasswordModal", function (e) {
				pjQ.$('#pjTsForgotPasswordModal_' + self.cid).modal('show');
				pjQ.$('#pjTsLoginModal_' + self.cid).modal('hide');
			}).on("click.ts", ".pjTsLoginModal", function (e) {
				pjQ.$('#pjTsLoginModal_' + self.cid).modal('show');
				pjQ.$('#pjTsForgotPasswordModal_' + self.cid).modal('hide');
			}).on("click.ts", "#pjTsBtnLogin", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}				
				var $frmLogin = pjQ.$('#pjTsLoginForm_' + self.cid);				
				$frmLogin.validate({
					errorElement: 'li',
					errorPlacement: function (error, element) {
						element.parent().parent().addClass('has-error');
						error.appendTo(element.next().find('ul'));
					},
					highlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().parent().removeClass('has-success').addClass('has-error');
		            },
		            unhighlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().parent().removeClass('has-error').addClass('has-success');
		            },
					onkeyup: false,
					submitHandler: function(form){
						self.disableButtons.call(self);
						var $loginMessage = pjQ.$('#pjLoginMessage_' + self.cid);
						$loginMessage.removeClass("alert-danger");
						$loginMessage.html('');
						$loginMessage.parent().parent().hide();
						pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionLogin", "&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $frmLogin.serialize()).done(function (data) {
							if (data.status == 'ERR') {
								$loginMessage.addClass("alert-danger");
								$loginMessage.html(data.text);
								$loginMessage.parent().parent().show();
								self.enableButtons.call(self);
							} else {
								pjQ.$('#pjTsLoginModal_' + self.cid).modal('hide');
								pjQ.$('body').removeClass('modal-open');
								pjQ.$(window).trigger("loadCheckout");
							}
						});
						return false;
				    }
				});
				$frmLogin.submit();				
			}).on("click.ts", "#pjTsBtnForgotPassword", function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}				
				var $formForgot = pjQ.$('#pjTsForgotForm_' + self.cid);				
				$formForgot.validate({
					errorElement: 'li',
					errorPlacement: function (error, element) {
						element.parent().parent().addClass('has-error');
						error.appendTo(element.next().find('ul'));
					},
					highlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().parent().removeClass('has-success').addClass('has-error');
		            },
		            unhighlight: function(ele, errorClass, validClass) {
		            	var element = pjQ.$(ele);
		            	element.parent().parent().removeClass('has-error').addClass('has-success');
		            },
					onkeyup: false,
					submitHandler: function(form){
						self.disableButtons.call(self);
						var $forgotMessage = pjQ.$('#pjForgotMessage_' + self.cid);
						$forgotMessage.html('');
						$forgotMessage.parent().parent().hide();
						pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionForgot", "&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $formForgot.serialize()).done(function (data) {
							if (data.status == 'ERR') {
								$forgotMessage.removeClass('alert-success').addClass('alert-danger');
							} else {
								$forgotMessage.removeClass('alert-danger').addClass('alert-success');
							}
							$forgotMessage.html(data.text);
							$forgotMessage.parent().parent().show();
							self.enableButtons.call(self);
						});
						return false;
				    }
				});
				$formForgot.submit();				
			});
			
			//Custom events
			pjQ.$(window).on("loadRouter", this.container, function (e) {
				if (self.layout === 2) {
					pjQ.$(window).trigger("loadWeekly");
				} else {
					pjQ.$(window).trigger("loadCalendar");
				}
			}).on("loadTimeslots", this.container, function (e) {
				switch (arguments.length) {
				case 4:
					self.date = [arguments[1], arguments[2], arguments[3]].join("-");
					break;
				case 2:
					self.date = arguments[1];
					break;
				}
				self.loadTimeslots.call(self);
			}).on("loadCalendar", this.container, function (e) {
				self.layout = 1;
				switch (arguments.length) {
				case 4:
					self.date = [arguments[1], arguments[2], arguments[3]].join("-");
					break;
				case 2:
					self.date = arguments[1];
					break;
				}
				self.loadCalendar.call(self);
			}).on("loadWeekly", this.container, function (e) {
				self.layout = 2;
				switch (arguments.length) {
				case 4:
					self.date = [arguments[1], arguments[2], arguments[3]].join("-");
					break;
				case 2:
					self.date = arguments[1];
					break;
				}
				self.loadWeekly.call(self);
			}).on("loadCart", this.container, function (e) {
				self.loadCart.call(self);
			}).on("loadCheckout", this.container, function (e) {
				self.loadCheckout.call(self);
			}).on("loadPreview", this.container, function (e) {
				self.loadPreview.call(self);
			}).on("loadBooking", this.container, function (e, booking_uuid) {
				self.booking_uuid = booking_uuid;
				self.loadBooking.call(self);
			}).on("loadLogin", this.container, function (e) {
				self.loadLogin.call(self);
			}).on("loadProfile", this.container, function (e) {
				self.loadProfile.call(self);
			}).on("loadForgot", this.container, function (e) {
				self.loadForgot.call(self);
			}).on("loadIpBlocked", this.container, function (e) {
				self.loadIpBlocked.call(self);
			});
			
			if (window.location.hash.length === 0) {
				pjQ.$(window).trigger("loadRouter");
			} else {
				onHashChange.call(null);
			}
			
			pjQ.$(document).on("click.ts", 'button[data-dismiss="modal"]', function (e) {
				if (e && e.preventDefault) {
					e.preventDefault();
				}
				var $modal = pjQ.$(this).closest('.modal');
				if ($modal !== undefined && $modal.length > 0) {
					$modal.modal('hide');
					pjQ.$('body').removeClass('modal-open');
				}
				return false;
			});
			
			return this;
		},
		disableButtons: function () {
			this.$container.find(".btn").attr("disabled", "disabled");

			return this;
		},
		enableButtons: function () {
			this.$container.find(".btn").removeAttr("disabled");

			return this;
		},
		errorHandler: function (msg) {
			this.$container.find(".tsSelectorNotice:first").html(msg).show();
			
			return this;
		},
		add: function (obj) {
			var self = this;
			pjQ.$.extend(obj, {});
			
			var jqxhr = pjQ.$.post([this.options.folder + "index.php?controller=pjFrontCart&action=pjActionAdd", "&session_id=", self.options.session_id, "&cid=", self.cid, "&locale=", this.options.locale].join(''), obj);
			
			return jqxhr;
		},
		remove: function (obj) {
			var self = this;
			pjQ.$.extend(obj, {});
			
			var jqxhr = pjQ.$.post([this.options.folder + "index.php?controller=pjFrontCart&action=pjActionRemove", "&session_id=", self.options.session_id, "&cid=", self.cid, "&locale=", this.options.locale].join(''), obj);
			
			return jqxhr;
		},
		loadIpBlocked: function () {
			var self = this,
				index = this.options.index,
				params = 	{
					"session_id": self.options.session_id,
					"cid": self.cid,
					"locale": this.options.locale,
					"hide": this.options.hide,
					"switch": this.options.switch,
					"multi" : self.options.multi
				};
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionIpBlocked"].join(""), params).done(function (data) {
				self.$container.html(data);
				pjQ.$('html, body').animate({
			        scrollTop: self.$container.offset().top
			    }, 500);
			});
		},
		getSummary: function () {
			var self = this;
			if(pjQ.$('#pjTsBookingSummaryBox').length > 0)
			{
				pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionGetSummary"].join(""), {
					"session_id": self.options.session_id,
					"cid": self.cid,
					"locale": this.options.locale,
					"hide": this.options.hide,
					"multi" : self.options.multi
				}).done(function (data) {
					pjQ.$('#pjTsBookingSummaryBox').html(data);
				});
			}
			return this;
		},
		loadProfile: function () {
			var self = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionProfile"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionProfile';
					self.bindProfile.call(self);
				}
			});
			return this;
		},
		bindProfile: function () {
			var self = this;
			var $formProfile = pjQ.$('#pjTsProfileForm_' + self.cid);
			$formProfile.validate({
				rules: {
					"customer_email": {
						remote: self.options.folder + "index.php?controller=pjFrontPublic&action=pjActionCheckEmail&session_id=" + self.options.session_id + "&cid=" + self.cid
					},
				},
				onkeyup: false,
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					error.appendTo(element.next().find('ul'));
				},
				success: function(li, element) {
					if(li.parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
					if(li.parent().parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
	            },
	            unhighlight: function(ele, errorClass, validClass) {
	            	var element = pjQ.$(ele);
	            	element.parent().parent().removeClass('has-error').addClass('has-success');
	            },
				submitHandler: function (form) {
					self.disableButtons.call(self);
					var $form = pjQ.$(form);
					pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionProfile&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $form.serialize()).done(function (data) {
						var $profileMessage = pjQ.$('#pjProfileMessage_' + self.cid);
						if(data.code == '200')
						{
							$profileMessage.removeClass('alert-danger').addClass('alert-success');
						}else{
							$profileMessage.removeClass('alert-success').addClass('alert-danger');
						}
						$profileMessage.html(data.text);
						$profileMessage.parent().parent().show();
						self.enableButtons.call(self);
					}).fail(function () {
						self.enableButtons.call(self);
					});
					return false;
				}
			});
			return this;
		},
		loadForgot: function () {
			var self = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionForgot"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionForgot';
					self.bindForgot.call(self);
				}
			});
			return this;
		},
		bindForgot: function () {
			var self = this;
			var $formForgot = pjQ.$('#pjTsForgotForm_' + self.cid);
			$formForgot.validate({
				
				onkeyup: false,
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					error.appendTo(element.next().find('ul'));
				},
				success: function(li, element) {
					if(li.parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
					if(li.parent().parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
	            },
	            unhighlight: function(ele, errorClass, validClass) {
	            	var element = pjQ.$(ele);
	            	element.parent().parent().removeClass('has-error').addClass('has-success');
	            },
				submitHandler: function (form) {
					self.disableButtons.call(self);
					var $form = pjQ.$(form);
					pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionForgot&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $form.serialize()).done(function (data) {
						var $forgotMessage = pjQ.$('#pjForgotMessage_' + self.cid);
						if(data.code == '200')
						{
							$forgotMessage.removeClass('alert-danger').addClass('alert-success');
						}else{
							$forgotMessage.removeClass('alert-success').addClass('alert-danger');
						}
						$forgotMessage.html(data.text);
						$forgotMessage.parent().parent().show();
						self.enableButtons.call(self);
					}).fail(function () {
						self.enableButtons.call(self);
					});
					return false;
				}
			});
			return this;
		},
		loadLogin: function () {
			var self = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionLogin"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionLogin';
					self.bindLogin.call(self);
				}
			});
			
			return this;
		},
		bindLogin: function () {
			var self = this;
			var $formLogin = pjQ.$('#pjTsLoginForm_' + self.cid);
			$formLogin.validate({
				
				onkeyup: false,
				errorElement: 'li',
				errorPlacement: function (error, element) {
					element.parent().parent().addClass('has-error');
					error.appendTo(element.next().find('ul'));
				},
				success: function(li, element) {
					if(li.parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
					if(li.parent().parent().parent().parent().hasClass('has-error'))
					{
						li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
					}
	            },
	            unhighlight: function(ele, errorClass, validClass) {
	            	var element = pjQ.$(ele);
	            	element.parent().parent().removeClass('has-error').addClass('has-success');
	            },
				submitHandler: function (form) {
					self.disableButtons.call(self);
					var $form = pjQ.$(form);
					pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionLogin&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $form.serialize()).done(function (data) {
						if(data.code == '200')
						{
							if (!hashBang("#!/Profile")) 
							{
								self.loadProfile.call(self);
							}
						}else{
							var $loginMessage = pjQ.$('#pjLoginMessage_' + self.cid);
							$loginMessage.html(data.text);
							$loginMessage.parent().parent().show();
							self.enableButtons.call(self);
						}
					}).fail(function () {
						self.enableButtons.call(self);
					});
					return false;
				}
			});
			return this;
		},
		loadBooking: function () {
			var self = this;
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionBooking"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"booking_uuid": this.booking_uuid,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionBooking';
					
					pjQ.$('html, body').animate({
				        scrollTop: self.$container.offset().top
				    }, 500);
					var $payment_form = self.$container.find("form[name='pjOnlinePaymentForm']").first();
					if ($payment_form.length > 0) {
						$payment_form.trigger('submit');
					}
				}
			});
			
			return this;
		},
		loadCalendar: function () {
			var self = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCalendar"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"layout": self.layout,
				"date": this.date,
				"switch": this.options.switch,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionCalendar';
					pjQ.$('.pjTsTooltipster').tooltipster({
						interactive: true,
		        		contentAsHTML: true
		        	});
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
			
			return this;
		},
		loadCart: function () {
			var self = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCart"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"layout": self.layout,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionCart';
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
			
			return this;
		},
		loadCheckout: function () {
			var self = this;
			this.disableButtons.call(this);
			pjQ.$.get(this.options.folder + "index.php?controller=pjFrontPublic&action=pjActionCheckout", {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"layout": self.layout,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionCheckout';
					pjQ.$('.modal-dialog').css("z-index", "9999"); 
					
					var $reCaptcha = self.$container.find('#g-recaptcha_' + self.cid);
					if ($reCaptcha.length > 0)
		            {
		                grecaptcha.render($reCaptcha.attr('id'), {
		                    sitekey: $reCaptcha.data('sitekey'),
		                    callback: function(response) {
		                        var elem = pjQ.$("input[name='recaptcha']");
		                        elem.val(response);
		                        elem.valid();
		                    }
		                });
		            }
					
					if (validate) {					
						self.$container.find(".pjTsSelectorCheckoutForm").validate({
							rules: {
								"captcha" : {
									remote: self.options.folder + "index.php?controller=pjFrontEnd&action=pjActionCheckCaptcha&session_id=" + self.options.session_id,
								},
								"recaptcha": {
			                        remote: self.options.folder + "index.php?controller=pjFrontEnd&action=pjActionCheckReCaptcha&session_id=" + self.options.session_id,
			                    },
							},
							ignore: ".ignore",
							onkeyup: false,
							errorElement: 'li',
							errorPlacement: function (error, element) {
								if(element.attr('name') == 'captcha' || element.attr('name') == 'terms')
								{
									element.parent().parent().parent().parent().addClass('has-error');
									error.appendTo(element.parent().parent().next().find('ul'));
								}else{
									element.parent().parent().addClass('has-error');
									error.appendTo(element.next().find('ul'));
								}
							},
							success: function(li, element) {
								if(li.parent().parent().parent().hasClass('has-error'))
								{
									li.parent().parent().parent().removeClass('has-error').addClass('has-success');
								}
								if(li.parent().parent().parent().parent().hasClass('has-error'))
								{
									li.parent().parent().parent().parent().removeClass('has-error').addClass('has-success');
								}
				            },
				            unhighlight: function(ele, errorClass, validClass) {
				            	var element = pjQ.$(ele);
				            	if(element.attr('name') == 'captcha')
								{
									element.parent().parent().removeClass('has-error').addClass('has-success');
								}else{
									element.parent().parent().removeClass('has-error').addClass('has-success');
								}
				            },
							submitHandler: function (form) {
								self.disableButtons.call(self);
								var $form = pjQ.$(form);
								pjQ.$.post([self.options.folder, "index.php?controller=pjFrontPublic&action=pjActionCheckout", "&session_id=", self.options.session_id, "&cid=", self.cid].join(""), $form.serialize()).done(function (data) {
									if (data.status == "OK") {
										hashBang("#!/Preview");
									}else{
										self.enableButtons.call(self);
									} 
								}).fail(function () {
									self.enableButtons.call(self);
								});
								return false;
							}
						});
					}
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
			
			return this;
		},
		loadPreview: function () {
			var self = this;
			pjQ.$.get(this.options.folder + "index.php?controller=pjFrontPublic&action=pjActionPreview", {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"layout": self.layout,
				"duplicated": self.duplicated,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionPreview';
					
					if (validate) {
						self.$container.find(".pjTsSelectorPreviewForm").validate({
							rules: {},
							onkeyup: false,
							onclick: false,
							onfocusout: false,
							errorClass: "tsError",
							validClass: "tsValid",
							wrapper: "em",
							errorPlacement: function (error, element) {
								error.insertAfter(element.parent());
							},
							submitHandler: function (form) {
								self.disableButtons.call(self);
								self.duplicated = 0;
								var $form = pjQ.$(form);
								pjQ.$.post([self.options.folder, "index.php?controller=pjFrontEnd&action=pjActionOrder", "&session_id=", self.options.session_id, "&cid=", self.cid, "&locale=", self.options.locale].join(""), $form.serialize()).done(function (data) {
									if (data.status == "OK") {
										hashBang("#!/Booking/" + data.booking_uuid);
									} else if (data.status == "ERR") {
										if(data.code == '101')
										{
											self.duplicated = 1;
											self.loadPreview.call(self);
										}else{
											self.enableButtons.call(self);
										}
									}
								}).fail(function () {
									self.enableButtons.call(self);
								});
								return false;
							}
						});
					}
				}
			});
			
			return this;
		},
		loadTimeslots: function () {
			var self = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionTimeslots"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"layout": self.layout,
				"date": this.date,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionTimeslots';
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
			
			return this;
		},
		loadWeekly: function () {
			var self = this;
			this.disableButtons.call(this);
			pjQ.$.get([this.options.folder, "index.php?controller=pjFrontPublic&action=pjActionWeekly"].join(""), {
				"session_id": self.options.session_id,
				"cid": self.cid,
				"locale": this.options.locale,
				"hide": this.options.hide,
				"switch": this.options.switch,
				"layout": self.layout,
				"date": this.date,
				"multi" : self.options.multi
			}).done(function (data) {
				if (data.code != undefined && data.status == 'ERR') {
					hashBang("#!/IpBlocked");
				}else{
					self.$container.html(data);
					self.view = 'pjActionWeek';
					var $tb_body = self.$container.find('.pj-calendar-body').eq(0);
					$tb_body.find('.pj-calendar-rowspan').each(function(){
						pjQ.$(this).height($tb_body.height());
					});
					if($tb_body.find('.weekly-slot-select').length > 0)
					{
						$tb_body.find('.weekly-slot-select').each(function(){
							pjQ.$(this).parent().css("padding-top", 6);
							pjQ.$(this).parent().css("padding-bottom", 6);
						});
					}
				}
			}).fail(function () {
				self.enableButtons.call(self);
			});
			
			return this;
		}
	};
	
	// expose
	window.TSBCalendar = TSBCalendar;
})(window);