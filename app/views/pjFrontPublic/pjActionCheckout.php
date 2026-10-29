<?php
if (isset($tpl['status']) && $tpl['status'] == 'OK')
{
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
	$tsb_step = 1;
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/steps.php';
	?>
	<div class="tsb-co">
	<?php
	if ((int) $tpl['option_arr']['o_disable_payments'] === 0 && (int) $tpl['option_arr']['o_hide_prices'] === 0 && isset($tpl['amount']) && $tpl['amount']['deposit'] > 0)
	{
		?><div class="tsb-voucher"><?php
		include PJ_VIEWS_PATH . 'pjFrontPublic/elements/voucher.php';
		?></div><?php
	}
	?><div class="tsb-aside"><?php
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/summary.php';
	?></div><?php
	
	$get_cid = $controller->_get->toInt('cid');
	
	$FORM = @$_SESSION[$controller->defaultForm . '_' . $get_cid];
	if($controller->isFrontLogged())
	{
	    $CLIENT = $_SESSION[$controller->defaultClient] ;
	    $CLIENT['customer_name'] = $CLIENT['name'];
	    $CLIENT['customer_email'] = $CLIENT['email'];
	    $CLIENT['customer_phone'] = $CLIENT['phone'];
	}else{
	    $CLIENT = array();
	}
	$FORM = array_merge($CLIENT, $FORM);
	?>
	<div class="tsb-main">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_booking_form'); ?>
		</div><!-- /.panel-heading -->

		<form action="" method="post" class="pjTsSelectorCheckoutForm">
			<input type="hidden" name="ts_checkout" value="1" />
			<div class="panel-body">
				<div class="pj-calendar-form">
					<?php
					if (in_array($tpl['option_arr']['o_bf_name'], array(2, 3)))
					{ 
						$tsb_head('contact');
						?>
						<div class="row tsb-f-name">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_name'); ?><?php if ((int) $tpl['option_arr']['o_bf_name'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_name" class="form-control<?php echo $tpl['option_arr']['o_bf_name'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_name']) ? pjSanitize::html($FORM['customer_name']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_name', false)); ?>">
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_email'], array(2, 3)))
					{ 
						$tsb_head('contact');
						?>
						<div class="row tsb-f-email">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_email'); ?><?php if ((int) $tpl['option_arr']['o_bf_email'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_email" class="form-control email<?php echo $tpl['option_arr']['o_bf_email'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_email']) ? pjSanitize::html($FORM['customer_email']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_email', false)); ?>" data-msg-email="<?php echo pjSanitize::html(__('front_v_email_format', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					} 
					if (in_array($tpl['option_arr']['o_bf_phone'], array(2, 3)))
					{
						$tsb_head('contact');
						?>
						<div class="row tsb-f-phone">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_phone" class="form-control<?php echo $tpl['option_arr']['o_bf_phone'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_phone']) ? pjSanitize::html($FORM['customer_phone']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_phone', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					} 
					if (in_array($tpl['option_arr']['o_bf_country'], array(2, 3)))
					{
						$tsb_head('address');
						?>
						<div class="row tsb-f-country">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_country'); ?><?php if ((int) $tpl['option_arr']['o_bf_country'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<select name="customer_country" class="form-control<?php echo $tpl['option_arr']['o_bf_country'] == 3 ? ' required' : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_country', false)); ?>">
									<option value="">-- <?php __('co_select_country'); ?> --</option>
									<?php
									if (isset($tpl['country_arr']) && is_array($tpl['country_arr']))
									{
										foreach ($tpl['country_arr'] as $v)
										{
											?><option value="<?php echo $v['id']; ?>"<?php echo isset($FORM['customer_country']) && $FORM['customer_country'] == $v['id'] ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($v['name']); ?></option><?php
										}
									}
									?>
								</select>
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_state'], array(2, 3)))
					{ 
						$tsb_head('address');
						?>
						<div class="row tsb-f-state">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_state" class="form-control<?php echo $tpl['option_arr']['o_bf_state'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_state']) ? pjSanitize::html($FORM['customer_state']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_state', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_city'], array(2, 3)))
					{ 
						$tsb_head('address');
						?>
						<div class="row tsb-f-city">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
							
		
							<div class="col-sm-8">
								<input type="text" name="customer_city" class="form-control<?php echo $tpl['option_arr']['o_bf_city'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_city']) ? pjSanitize::html($FORM['customer_city']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_city', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_address_1'], array(2, 3)))
					{ 
						$tsb_head('address');
						?>
						<div class="row tsb-f-addr1">
							<label class="col-sm-4 control-label"><?php __('booking_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_1'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_address_1" class="form-control<?php echo $tpl['option_arr']['o_bf_address_1'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_address_1']) ? pjSanitize::html($FORM['customer_address_1']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_address_1', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_address_2'], array(2, 3)))
					{
						$tsb_head('address');
						?>
						<div class="row tsb-f-addr2">
							<label class="col-sm-4 control-label"><?php __('booking_address_2'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_2'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_address_2" class="form-control<?php echo $tpl['option_arr']['o_bf_address_2'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_address_2']) ? pjSanitize::html($FORM['customer_address_2']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_address_2', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_zip'], array(2, 3)))
					{ 
						$tsb_head('address');
						?>
						<div class="row tsb-f-zip">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<input type="text" name="customer_zip" class="form-control<?php echo $tpl['option_arr']['o_bf_zip'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_zip']) ? pjSanitize::html($FORM['customer_zip']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_zip', false)); ?>" />
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_notes'], array(2, 3)))
					{ 
						$tsb_head('extra');
						?>
						<div class="row tsb-f-notes">
							<label class="col-sm-4 control-label"><?php __('opt_o_bf_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
		
							<div class="col-sm-8">
								<textarea name="customer_notes" cols="30" rows="10" class="form-control<?php echo $tpl['option_arr']['o_bf_notes'] == 3 ? ' required' : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_notes', false)); ?>"><?php echo isset($FORM['customer_notes']) ? pjSanitize::html($FORM['customer_notes']) : NULL; ?></textarea>
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div><!-- /.col-sm-8 -->
						</div><!-- /.row -->
						<?php
					}
					if ((int) $tpl['option_arr']['o_disable_payments'] === 0 &&
							(int) $tpl['option_arr']['o_hide_prices'] === 0 &&
							isset($tpl['amount']) && $tpl['amount']['deposit'] > 0)
					{
					    $tsb_head('payment');
					    $plugins_payment_methods = pjObject::getPlugin('pjPayments') !== NULL? pjPayments::getPaymentMethods(): array();
					    $haveOnline = $haveOffline = false;
					    foreach ($tpl['payment_titles'] as $k => $v)
					    {
					        if( $k != 'cash' && $k != 'bank' )
					        {
					            if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
					            {
					                $haveOnline = true;
					                break;
					            }
					        }
					    }
					    foreach ($tpl['payment_titles'] as $k => $v)
					    {
					        if( $k == 'cash' || $k == 'bank' )
					        {
					            if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
					            {
					                $haveOffline = true;
					                break;
					            }
					        }
					    }
						?>
						<div class="row tsb-f-pay">
							<label class="col-sm-4 control-label"><?php __('booking_payment_method'); ?><span class="tsAsterisk">*</span></label>
							<div class="col-sm-8">
								<select name="payment_method" class="form-control required" data-msg-required="<?php echo pjSanitize::html(__('front_v_payment_method', false)); ?>">
									<option value="">-- <?php __('front_select_payment'); ?> --</option>
									<?php
									if ($haveOnline && $haveOffline)
									{
									    ?><optgroup label="<?php __('script_online_payment_gateway', false, true); ?>"><?php
				                    }
				                    foreach ($tpl['payment_titles'] as $k => $v)
				                    {
				                        if($k == 'cash' || $k == 'bank' ){
				                            continue;
				                        }
				                        if (array_key_exists($k, $plugins_payment_methods))
				                        {
				                            if(!isset($tpl['payment_option_arr'][$k]['is_active']) || (isset($tpl['payment_option_arr']) && $tpl['payment_option_arr'][$k]['is_active'] == 0) )
				                            {
				                                continue;
				                            }
				                        }
				                        ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
				                    }
				                    if ($haveOnline && $haveOffline)
				                    {
				                        ?>
				                    	</optgroup>
				                    	<optgroup label="<?php __('script_offline_payment', false, true); ?>">
				                    	<?php 
				                    }
				                    foreach ($tpl['payment_titles'] as $k => $v)
				                    {
				                        if( $k == 'cash' || $k == 'bank' )
				                        {
				                            if( (int) $tpl['payment_option_arr'][$k]['is_active'] == 1)
				                            {
				                                ?><option value="<?php echo $k; ?>"<?php echo isset($FORM['payment_method']) && $FORM['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
				                            }
				                        }
				                    }
				                    if ($haveOnline && $haveOffline)
				                    {
				                        ?></optgroup><?php
				                    }
									?>
								</select>
								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							</div>
						</div>
						<div class="row pjTsSelectorBank" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
							<label class="col-sm-4 control-label"><?php __('booking_bank_account'); ?></label>
							<div class="col-sm-8"><span class="text-muted"><?php echo pjSanitize::html($tpl['bank_account']); ?></span></div>
						</div>
						<?php
					}
					if (in_array($tpl['option_arr']['o_bf_captcha'], array(2, 3)))
					{
						?>
						<div class="row tsb-f-captcha">
							<label for="" class="col-sm-4 control-label">
								<?php __('opt_o_bf_captcha'); ?><?php if ((int) $tpl['option_arr']['o_bf_captcha'] === 3) : ?><span class="asterisk">*</span><?php endif; ?>
							</label>
							<div class="col-sm-8">
								<?php
								if($tpl['option_arr']['o_captcha_type_front'] == 'system')
								{
    								?>
    								<div class="row">
    									<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">
    										<input type="text" name="captcha" class="form-control<?php echo (int) $tpl['option_arr']['o_bf_captcha'] === 3 ? ' required' : NULL; ?>" autocomplete="off" data-msg-required="<?php echo pjSanitize::html(__('front_v_captcha', false)); ?>" data-msg-remote="<?php echo pjSanitize::html(__('front_v_captcha_match', false)); ?>" />
    									</div>
    									<div class="col-lg-6 col-md-6 col-sm-6 col-sx-12">
    										<img id="pjTsCaptchaImage" class="pjTsCaptchaImage" alt="Captcha" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFrontEnd&action=pjActionCaptcha&rand=<?php echo rand(1000, 999999); ?><?php echo isset($_GET['session_id']) ? '&session_id=' . $_GET['session_id'] : NULL;?>" style="vertical-align: middle" />
    									</div>
    								</div><!-- /.row -->
    								<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
    								<?php
								}else{
								    ?>
								    <div id="g-recaptcha_<?php echo $get_cid; ?>" class="g-recaptcha" data-sitekey="<?php echo $tpl['option_arr']['o_captcha_site_key_front'] ?>"></div>
									<input type="hidden" id="recaptcha" name="recaptcha" class="recaptcha<?php echo ($tpl['option_arr']['o_bf_captcha'] == 3) ? ' required' : NULL; ?>" autocomplete="off" data-msg-required="<?php __('front_v_captcha');?>" data-msg-remote="<?php __('front_v_captcha_match');?>"/>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
									<?php
								}
    							?>
							</div><!-- /.col-lg-8 col-md-8 col-sm-8 col-sx-12 -->
						</div>
						<?php
					}
					if (in_array((int) $tpl['option_arr']['o_bf_terms'], array(3)))
					{
						$terms_str = __('front_bf_terms', true, false);
						$term_agree = true;
						if (isset($tpl['terms_arr']) && !empty($tpl['terms_arr']))
						{
							$t_url = $tpl['terms_arr']['terms_url'];
							$t_body = trim($tpl['terms_arr']['terms_body']);
							if(empty($t_url) && empty($t_body))
							{
								$term_agree = false;
							}else{
								if (!empty($t_url) && preg_match('/^http(s)?:\/\//i', $t_url))
								{
									$terms_str = str_replace("{STAG}", '<a href="'.pjSanitize::html($t_url).'" class="tsServiceLink" target="_blank">', $terms_str);
								}else{
									$terms_str = str_replace("{STAG}", '<a href="#" data-pj-target="#pjTsTermModal" data-pj-toggle="modal" class="tsServiceLink">', $terms_str);
								}
								$terms_str = str_replace("{ETAG}", '</a>', $terms_str);
							}
								
						}
						if($term_agree == true)
						{
							?>
							<div class="row tsb-f-terms">
								<label class="col-sm-4 control-label">&nbsp;</label>
								<div class="col-sm-8">
									<div style="display: block; overflow: hidden;">
										<span style="float: left; width: 15px">
											<input type="checkbox" name="terms" id="terms_<?php echo $get_cid; ?>" value="1" class="<?php echo (int) $tpl['option_arr']['o_bf_terms'] === 3 ? ' required' : NULL; ?>" style="margin: 0" data-msg-required="<?php echo pjSanitize::html(__('front_v_terms', false)); ?>" />
										</span>
										<span for="terms_<?php echo $get_cid; ?>" style="float: left; width: 85%"><?php echo $terms_str; ?></span>
									</div>
									<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
								</div>
							</div>
							<?php
						}
					} 
					?>
				</div><!-- /.form -->
			</div><!-- /.panel-body -->
	
			<div class="panel-footer">
				<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back', false, true); ?></a>
	
				<button type="submit" class="btn btn-primary pull-right"><?php __('front_button_continue', false, true); ?></button>
			</div><!-- /.panel-footer -->
		</form>
		
		<div class="modal fade pjTbModal" id="pjTsTermModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  	<div class="modal-dialog">
		    	<div class="modal-content">
				    <div class="modal-body">
				    	<?php
				    	echo stripslashes(@$tpl['terms_arr']['terms_body']);
						?>
				    </div>
			      	<div class="modal-footer">
			        	<button type="button" class="btn btn-default pjTbBtn pjTbBtnPrimary" data-dismiss="modal"><?php __('front_btn_close');?></button>
			      	</div>
		    	</div>
		  	</div>
		</div>
	</div><!-- /.panel -->
	</div><!-- /.tsb-main -->
	</div><!-- /.tsb-co -->
	
	<div class="modal fade" id="pjTsLoginModal_<?php echo $get_cid;?>" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-md" role="document">
		    <div class="modal-content">
			      <div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title"><?php __('front_login_form'); ?></h4>
			      </div>
			      <div class="modal-body">
			      	<form id="pjTsLoginForm_<?php echo $get_cid;?>" class="" action="#" method="post">
						<input type="hidden" name="ts_login" value="1" />
						<div class="row">
							<div class="col-xs-12"><div class="alert alert-info"><?php __('front_login_form_popup_desc');?></div></div>
						</div>
				      	<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  	<div class="form-group required">
							    	<label class="control-label"><?php __('opt_o_bf_email'); ?><span class="tsAsterisk">*</span></label>
							    	<input type="text" name="login_email" class="form-control email required" data-msg-required="<?php echo pjSanitize::html(__('front_v_email', false)); ?>" data-msg-email="<?php echo pjSanitize::html(__('front_v_email_format', false)); ?>" />
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  	<div class="form-group required">
							    	<label class="control-label"><?php __('front_password'); ?><span class="tsAsterisk">*</span></label>
							    	<input type="password" name="login_password" class="form-control required" data-msg-required="<?php echo pjSanitize::html(__('front_v_password', false)); ?>" />
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
						</div>
					
						<div class="row form-group">
							<div class="col-md-6 col-sm-6"><a href="javascript:void(0);" class="pjTsForgotPasswordModal"><?php __('front_forgot_password'); ?></a></div>
							<div class="col-md-6 col-sm-6 col-xs-12 text-right">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('front_btn_cancel');?></button>
					        	<button id="pjTsBtnLogin" type="button" class="btn btn-primary"><?php __('front_btn_login');?></button>
					        </div>
				        </div>
				        <div class="row" style="display: none;">
		            		<div class="col-lg-12 col-md-12 col-sm-12">
		                		<div id="pjLoginMessage_<?php echo $get_cid;?>" class="alert alert-danger text-center"></div>
		                	</div>
		                </div>
			       	</form>
			     </div>
		    </div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div class="modal fade" id="pjTsForgotPasswordModal_<?php echo $get_cid;?>" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel">
	  	<div class="modal-dialog modal-md" role="document">
		    <div class="modal-content">
			      <div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title"><?php __('front_forgot_form'); ?></h4>
			      </div>
			      <div class="modal-body">
			      	<form id="pjTsForgotForm_<?php echo $get_cid;?>" class="" action="#" method="post">
						<input type="hidden" name="ts_forgot" value="1" />
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
							  	<div class="form-group required">
							    	<label class="control-label"><?php __('opt_o_bf_email'); ?><span class="tsAsterisk">*</span></label>
							    	<input type="text" name="email" class="form-control email required" data-msg-required="<?php echo pjSanitize::html(__('front_v_email', false)); ?>" data-msg-email="<?php echo pjSanitize::html(__('front_v_email_format', false)); ?>" />
							    	<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
							  	</div>
							</div>
						</div>
					
						<div class="row form-group">
							<div class="col-md-6 col-sm-6"><a href="javascript:void(0);" class="pjTsLoginModal"><?php __('front_btn_login'); ?></a></div>
							<div class="col-md-6 col-sm-6 col-xs-12 text-right">
								<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('front_btn_cancel');?></button>
					        	<button id="pjTsBtnForgotPassword" type="button" class="btn btn-primary"><?php __('front_btn_send');?></button>
					        </div>
				        </div>
				        <div class="row" style="display: none;">
		            		<div class="col-lg-12 col-md-12 col-sm-12">
		                		<div id="pjForgotMessage_<?php echo $get_cid;?>" class="alert alert-danger text-center"></div>
		                	</div>
		                </div>
			       	</form>
			     </div>
		    </div><!-- /.modal-content -->
	  	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<?php
} else {
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_system_msg'); ?>
		</div><!-- /.panel-heading -->
		<div class="panel-body">
			<?php __('front_checkout_na'); ?>
		</div>
		<div class="panel-footer">
			<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><?php __('front_start_over', false, true); ?></a>
		</div><!-- /.panel-footer -->
	</div>
	<?php
}
?>