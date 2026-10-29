<?php
$months = __('months', true);
ksort($months);
$short_days = __('short_days', true);
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);
?>
<div id="datePickerOptions" style="display:none;" data-wstart="<?php echo (int) $tpl['option_arr']['o_week_start']; ?>" data-format="<?php echo $tpl['date_format']; ?>" data-months="<?php echo implode("_", $months);?>" data-days="<?php echo implode("_", $short_days);?>"></div>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-lg-9 col-md-8 col-sm-6">
				<h2><?php __('infoUpdateBookingTitle');?></h2>
			</div>
		</div><!-- /.row -->

		<p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoUpdateBookingDesc');?></p>
	</div><!-- /.col-md-12 -->
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate" method="post" id="frmUpdateBooking" class="frmBooking">
            	<input type="hidden" name="booking_update" value="1" />
            	<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
        		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
        		
                <div class="tabs-container tabs-reservations m-b-lg">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#booking-details" class="tab-booking-details" aria-controls="booking-details" role="tab" data-toggle="tab"><?php __('booking_tab_details'); ?></a></li>
                        <li role="presentation"><a href="#client-details" class="tab-client-details" aria-controls="client-details" role="tab" data-toggle="tab"><?php __('booking_tab_client'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="booking-details">
                            <div class="panel-body">
                                <div class="row">
									<?php
									$calendar_name = '';
									if(!empty($tpl['calendar_arr']))
									{
    									?>
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('lblCalendar'); ?></label>
                                            
                                                <select class="form-control required" id="calendar_id" name="calendar_id" data-msg-remote="<?php __('booking_slots_booked');?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
        											<?php
        											foreach ($tpl['calendar_arr'] as $calendar)
        											{
        											    if($calendar['id'] == $tpl['arr']['calendar_id'])
        											    {
        											        $calendar_name = pjSanitize::html($calendar['title']);
        											        ?><option value="<?php echo $calendar['id']; ?>" selected="selected"><?php echo pjSanitize::html($calendar['title']); ?></option><?php
        											    }else{
        											        ?><option value="<?php echo $calendar['id']; ?>"><?php echo pjSanitize::html($calendar['title']); ?></option><?php
        											    }
        											}
        											?>
        										</select>
                                            </div>
                                        </div>
                                        <?php
									}
                                    ?>

                                    <div class="col-lg-3 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_status'); ?></label>
                                        
                                            <select name="booking_status" id="booking_status" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
        										<option value=""><?php __('booking_choose'); ?></option>
        										<?php
        										foreach (__('booking_statuses', true) as $k => $v)
        										{
        											?><option value="<?php echo $k; ?>"<?php echo $tpl['arr']['booking_status'] == $k ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
        										}
        										?>
        									</select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hr-line-dashed"></div>
                                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="m-b-md">
                                            <a href="#" class="btn btn-primary btn-outline m-t-xs item-add"><i class="fa fa-plus"></i> <?php __('booking_slot_add', false, true); ?></a>
                                        </div>
                                        <div id="boxBookingItems"></div>
                                    </div>

                                </div><!-- /.row -->
                                
                                <div class="hr-line-dashed"></div>
								<?php
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
                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div id="pjTsPaymentMethodBox" class="form-group">
                                            <label class="control-label"><?php __('booking_payment_method'); ?></label>

                                            <select name="payment_method" id="payment_method" class="form-control<?php echo (int) $tpl['_option_arr']['o_disable_payments'] == 0 ? ' required' : NULL;?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
        										<option value="">-- <?php __('lblChoose'); ?>--</option>
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
            				                        ?><option value="<?php echo $k; ?>"<?php echo isset($tpl['arr']['payment_method']) && $tpl['arr']['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
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
            				                                ?><option value="<?php echo $k; ?>"<?php echo isset($tpl['arr']['payment_method']) && $tpl['arr']['payment_method']==$k ? ' selected="selected"' : NULL;?>><?php echo $v; ?></option><?php
            				                            }
            				                        }
            				                    }
            				                    if ($haveOnline && $haveOffline)
            				                    {
            				                        ?></optgroup><?php
            				                    }
            									?>
        									</select>
                                        </div>
                                    </div><!-- /.col-md-3 -->
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_voucher_code'); ?></label>

                                            <input type="text" name="discount_code" id="discount_code" value="<?php echo pjSanitize::html($tpl['arr']['discount_code']); ?>" class="form-control"/>
                                        </div>
                                    </div><!-- /.col-lg-3 -->
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_price'); ?></label>

                                            <div class="input-group">
                                                <input type="text" name="booking_price" readonly="readonly" id="booking_price" value="<?php echo pjSanitize::html($tpl['arr']['booking_price']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>

                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span> 
                                            </div>
                                        </div>
                                    </div><!-- /.col-lg-3 -->
                                    
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_discount'); ?></label>

                                            <div class="input-group">
                                                <input type="text" name="booking_discount" readonly="readonly" id="booking_discount" value="<?php echo pjSanitize::html($tpl['arr']['booking_discount']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>

                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span> 
                                            </div>
                                        </div>
                                    </div><!-- /.col-lg-3 -->

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_tax'); ?></label>

                                            <div class="input-group">
                                                <input type="text" name="booking_tax" readonly="readonly" id="booking_tax" value="<?php echo pjSanitize::html($tpl['arr']['booking_tax']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>

                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span> 
                                            </div>
                                        </div>
                                    </div><!-- /.col-lg-3 -->

                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_total'); ?></label>

                                            <div class="input-group">
                                                <input type="text" name="booking_total" readonly="readonly" id="booking_total" value="<?php echo pjSanitize::html($tpl['arr']['booking_total']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>

                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span> 
                                            </div>
                                        </div>
                                    </div><!-- /.col-lg-3 -->
                                    
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label"><?php __('booking_deposit'); ?></label>

                                            <div class="input-group">
                                                <input type="text" name="booking_deposit" readonly="readonly" id="booking_deposit" value="<?php echo pjSanitize::html($tpl['arr']['booking_deposit']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>

                                                <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span> 
                                            </div>
                                        </div>
                                    </div><!-- /.col-lg-3 -->
                                    
                                </div>

                                <div class="hr-line-dashed"></div>

                                <div class="clearfix">
    								<button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
    									<span class="ladda-label"><?php __('btnSave'); ?></span>
    									<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>   
    								</button>
    	
    								<button class="btn btn-white btn-lg pull-right" type="button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';"><?php __('btnCancel'); ?></button>
    							</div>
                                
                            </div>
                        </div>

                        <div role="tabpanel" class="tab-pane" id="client-details">
                            <div class="panel-body">
                            	<div class="row">
        							<div class="col-lg-4 col-md-6 col-sm-12">
        								<div class="form-group">
                                            <label class="control-label"><?php __('lblClient'); ?></label>
                
                                            <div class="clearfix">
                                                <div class="switch onoffswitch-data pull-left">
                                                    <div class="onoffswitch onoffswitch-client">
                                                        <input type="checkbox" class="onoffswitch-checkbox" id="new_client" name="new_client">
                
                                                        <label class="onoffswitch-label" for="new_client">
                                                            <span class="onoffswitch-inner" data-on="<?php __('lblNewClient'); ?>" data-off="<?php __('lblExistingClient'); ?>"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div><!-- /.clearfix -->
                                        </div><!-- /.form-group -->
        							</div><!-- /.col-lg-4 col-md-6 col-sm-12 -->
        							<div class="col-lg-8 col-md-6 col-sm-12 current-client-area">
        								<div class="form-group">
                                            <label class="control-label"><?php __('lblExistingClient'); ?></label>
                                            <div class="row">
                                        		<div class="col-md-10">
                                                    <select name="client_id" id="client_id" class="select-item form-control required pjTsbRequired" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
                    									<option value="">-- <?php __('lblChoose'); ?>--</option>
                    									<?php
                    									$client_name = NULL;
                    									foreach ($tpl['client_arr'] as $v)
                    									{
                    										$email_phone = array();
                    										if(!empty($v['customer_email']))
                    										{
                    										    $email_phone[] = pjSanitize::clean($v['customer_email']);
                    										}
                    										if(!empty($v['customer_phone']))
                    										{
                    										    $email_phone[] = pjSanitize::clean($v['customer_phone']);
                    										}
                    										if($v['id'] == $tpl['arr']['client_id'])
                    										{
                    										    ?><option value="<?php echo $v['id']; ?>" selected="selected"><?php echo pjSanitize::clean($v['customer_name']); ?> (<?php echo join(" | ", $email_phone); ?>)</option><?php
                    										}else{
                    										    ?><option value="<?php echo $v['id']; ?>"><?php echo pjSanitize::clean($v['customer_name']); ?> (<?php echo join(" | ", $email_phone); ?>)</option><?php
                    										}
                    									}
                    									?>
                    								</select>
            									</div>
                    							<div class="col-md-2">
                    								<?php
                    								if(pjAuth::factory('pjAdminClients', 'pjActionUpdate')->hasAccess())
                    								{
                        								?>
                                            			<a id="pjTsbEditClient" class="btn btn-primary btn-outline btn-sm m-l-xs" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate&id=<?php echo $tpl['arr']['client_id'];?>" target="blank" data-href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate&id={ID}" style="display:inline-block;"><i class="fa fa-pencil"></i></a>
                                            			<?php
                    								}
                                            		?>
                                        		</div>
                                           </div>
                                    	</div>
        							</div><!-- /.col-lg-8 col-md-6 col-sm-12 -->
        						</div>
        						<div class="new-client-area" style="display:none;">
                                    <div class="row">
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_name'); ?></label>
                                    
                                                <input type="text" name="customer_name" id="customer_name" class="form-control<?php echo $tpl['_option_arr']['o_bf_name'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_email'); ?></label>
                                    
                                                <div class="input-group">
                                                	<span class="input-group-addon"><i class="fa fa-at"></i></span>
                                                    <input type="text" name="customer_email" id="customer_email" class="form-control email<?php echo $tpl['_option_arr']['o_bf_email'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>" data-msg-email="<?php __('plugin_base_email_invalid', false, true);?>"/>
                                                </div>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_phone'); ?></label>
                                    
                                                <div class="input-group">
                                                	<span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                                    <input type="text" name="customer_phone" id="customer_phone" class="form-control<?php echo $tpl['_option_arr']['o_bf_phone'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>" />
                                                </div>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                    </div><!-- /.row -->
                                    
                                    <div class="hr-line-dashed"></div>
                                    
                                    <div class="row">
                                    	<div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_address_1'); ?></label>
                                    
                                                <input type="text" name="customer_address_1" id="customer_address_1" class="form-control<?php echo $tpl['_option_arr']['o_bf_address_1'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_address_2'); ?></label>
                                    
                                                <input type="text" name="customer_address_2" id="customer_address_2" class="form-control<?php echo $tpl['_option_arr']['o_bf_address_2'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                        
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_city'); ?></label>
                                    
                                                <input type="text" name="customer_city" id="customer_city" class="form-control<?php echo $tpl['_option_arr']['o_bf_city'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                    	<div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_state'); ?></label>
                                    
                                                <input type="text" name="customer_state" id="customer_state" class="form-control<?php echo $tpl['_option_arr']['o_bf_state'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    	<div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_zip'); ?></label>
                                    
                                                <input type="text" name="customer_zip" id="customer_zip" class="form-control<?php echo $tpl['_option_arr']['o_bf_zip'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                        <div class="col-md-4 col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label"><?php __('booking_country'); ?></label>
                                    
                                                <select name="customer_country" id="customer_country" class="form-control select-item<?php echo $tpl['_option_arr']['o_bf_country'] == 3 ? ' pjTsbRequired' : NULL; ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>">
                    								<option value=""><?php __('booking_choose'); ?></option>
                    								<?php
                    								foreach ($tpl['country_arr'] as $country)
                    								{
                    									?><option value="<?php echo $country['id']; ?>"><?php echo pjSanitize::html($country['name']); ?></option><?php
                    								}
                    								?>
                    							</select>
                                            </div>
                                        </div><!-- /.col-md-3 -->
                                    
                                    </div><!-- /.row -->
                                    
                                    <div class="hr-line-dashed"></div>
                                    
                                    <div class="form-group">
                                        <label class="control-label"><?php __('booking_notes'); ?></label>
                                    
                                        <textarea name="customer_notes" id="customer_notes" class="form-control<?php echo $tpl['_option_arr']['o_bf_notes'] == 3 ? ' pjTsbRequired' : NULL; ?>" rows="6" cols="30"></textarea>
                                    </div>
								</div><!-- /.new-client-area -->
								
                                <div class="hr-line-dashed"></div>

                                <div class="clearfix">
    								<button class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in">
    									<span class="ladda-label"><?php __('btnSave'); ?></span>
    									<?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>   
    								</button>
    	
    								<button class="btn btn-white btn-lg pull-right" type="button" onclick="window.location.href='<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminBookings&action=pjActionIndex';"><?php __('btnCancel'); ?></button>
    							</div>
                            </div>
                        </div>
                    </div><!-- /.tab-content -->
                </div>
            </form>
        </div><!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="m-b-lg">
            	<?php
            	$bg_class = ' bg-' . $tpl['arr']['booking_status'];
            	$fa_class = '';
            	if($tpl['arr']['booking_status'] == 'confirmed')
            	{
            	    $fa_class = ' fa-check';
            	}else if($tpl['arr']['booking_status'] == 'pending'){
            	    $fa_class = ' fa-exclamation-triangle';
            	}else if($tpl['arr']['booking_status'] == 'cancelled'){
            	    $fa_class = ' fa-times';
            	}
            	$booking_statuses = __('booking_statuses', true);
            	?>
                <div id="pjFdPriceWrapper" class="panel no-borders">
                    <div class="panel-heading<?php echo $bg_class;?>">
                        <p class="lead m-n">
                            <i class="fa<?php echo $fa_class;?>"></i> <?php __('booking_status'); ?>: <span class="pull-right status-text"><?php echo $booking_statuses[$tpl['arr']['booking_status']];?></span>
                        </p>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <p class="lead m-b-xs">
                            <button type="button" id="btnEmail" data-id="<?php echo $tpl['arr']['id'];?>" class="btn btn-primary btn-md btn-block btn-outline"><i class="fa fa-envelope m-r-xs"></i><?php __('btnSendEmailConfirmation')?></button>
                        </p>
                        <p class="lead m-b-xs text-right">
                            <button type="button" id="btnSMS" data-id="<?php echo $tpl['arr']['id'];?>" class="btn btn-primary btn-block btn-md btn-outline" data-toggle="modal" data-target="#smsModal"><i class="fa fa-phone m-r-xs"></i><?php __('btnSendSMSConfirmation')?></button>
                        </p>
                        <?php
                        if(pjAuth::factory('pjAdminBookings', 'pjActionExportICal')->hasAccess())
                        {
                            ?>
                            <p class="lead m-b-xs text-right">
                                <button type="button" class="btn btn-primary btn-block btn-md btn-outline export-ical"><i class="fa fa-file m-r-xs"></i><?php __('btnDownloadICal')?></button>
                            </p>
                            <?php
                        }
                        ?>
                        <div class="hr-line-dashed">

                        </div>
                        <?php
                        if(pjAuth::factory('pjAdminClients', 'pjActionUpdate')->hasAccess())
                        {
                            ?>
                			<p class="lead m-b-md">
                                <?php __('lblClient')?>: <span class="pull-right"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate&id=<?php echo $tpl['arr']['client_id'];?>" id="clientDetailsLink"><?php echo $client_name;?></a></span>
                            </p>
                			<?php
                        }else{
                            ?>
                			<p class="lead m-b-md">
                                <?php __('lblClient')?>: <span class="pull-right"><?php echo $client_name;?></span>
                            </p>
                			<?php
                        }
                        ?>
                        
                        <p class="lead m-b-md">
                            <?php __('lblCreated')?>: <span class="pull-right"><?php echo date($tpl['option_arr']['o_date_format'] . ', ' . $tpl['option_arr']['o_time_format'], strtotime($tpl['arr']['created']));?></span>
                        </p>
                        <p class="lead m-b-md">
                            <?php __('booking_uuid'); ?>: <span class="pull-right"><?php echo pjSanitize::html($tpl['arr']['uuid']);?></span>
                        </p>
                        <div class="hr-line-dashed">
                        </div>
                        <p class="lead m-b-md">
                            <?php __('lblCalendar');?>: <span class="pull-right"><?php echo $calendar_name;?></span>
                        </p>
                        <div class="hr-line-dashed">
                        </div>
                        <h3 class="lead m-b-md"><?php __('booking_total');?>: <strong id="pjTsTotalLabel" class="pull-right text-right"><?php echo pjCurrency::formatPrice($tpl['arr']['booking_total']);?></strong></h3>
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div><!-- /.m-b-lg -->
        </div><!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
</div>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionExportICal" method="post" id="frmExportICal">
	<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
	<input type="hidden" name="booking_export" value="1" />
</form>

<div class="modal inmodal fade" id="modalAddItem" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        	<form action="" method="post">
            	<div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            
                    <h3 class="modal-title" id="myModalLabel"><?php __('booking_slots_add_title'); ?></h3>
                </div>
                <div class="modal-inbody">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php __('button_ARRAY_add')?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php __('button_ARRAY_cancel')?></button>
                </div>
            </form>	
        </div>
    </div>
</div>

<div class="modal fade" id="reminderEmailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('booking_send_email_confirmation'); ?></h4>
		      </div>
		      <div id="emailContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendEmailConfirm" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="reminderSMSModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  	<div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
		      <div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        	<h4 class="modal-title"><?php __('booking_send_sms_confirmation'); ?></h4>
		      </div>
		      <div id="smsContentWrapper" class="modal-body"></div>
		      <div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?php __('btnCancel');?></button>
		        	<button id="btnSendSMSConfirm" type="button" class="btn btn-primary"><?php __('btnSend');?></button>
		      </div>
	    </div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.choose = "-- <?php __('lblChoose', false, true); ?> --";
myLabel.btnOK = <?php x__encode('gridBtnOk'); ?>;
myLabel.alert_btn_delete = <?php x__encode('btnDelete', false, true); ?>;
myLabel.alert_btn_cancel = <?php x__encode('btnCancel', false, true); ?>;
myLabel.alert_delete_booking_slot_title = <?php x__encode('booking_slots_delete_title', false, true); ?>;
myLabel.alert_delete_booking_slot_text = <?php x__encode('booking_slots_delete_body', false, true); ?>;
</script>