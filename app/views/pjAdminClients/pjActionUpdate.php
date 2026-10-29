<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('infoUpdateClientTitle', false, true);?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoUpdateClientDesc', false, true);?></p>
    </div><!-- /.col-md-12 -->
</div>

<div class="row wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
    	{
    	    $titles = __('error_titles', true);
    	    $bodies = __('error_bodies', true);
    	    switch (true)
    	    {
    	        case in_array($error_code, array('ACL01', 'ACL03')):
    	            ?>
    				<div class="alert alert-success">
    					<i class="fa fa-check m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
                case in_array($error_code, array('ACL04', 'ACL08')):
    				?>
    				<div class="alert alert-danger">
    					<i class="fa fa-exclamation-triangle m-r-xs"></i>
    					<strong><?php echo @$titles[$error_code]; ?></strong>
    					<?php echo @$bodies[$error_code]?>
    				</div>
    				<?php
    				break;
    		}
    	}
    	?>
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionUpdate" method="post" id="frmUpdateClient">
                	<input type="hidden" name="client_update" value="1" />
                	<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
                	<input type="hidden" name="id" value="<?php echo (int) $tpl['arr']['id']; ?>" />
                    <div class="row">
                    	<div class="col-lg-3 col-md-4 col-sm-6">
                    		<div class="form-group">
                                <label class="control-label"><?php __('lblClientTotalBookings'); ?></label>

                                <div class="clearfix">
                                	<?php
                                	if((int) $tpl['arr']['cnt_bookings'] > 0)
                                	{
                                    	?>
                                        <p class="form-control-static"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex&amp;client_id=<?php echo (int) $tpl['arr']['id'];?>"><?php echo $tpl['arr']['cnt_bookings'];?> / <?php echo pjCurrency::formatPrice($tpl['arr']['total_amount']);?></a></p>
                                        <?php
                                	}else{
                                	    ?>
                                        <p class="form-control-static"><?php echo $tpl['arr']['cnt_bookings'];?> / <?php echo pjCurrency::formatPrice($tpl['arr']['total_amount']);?></p>
                                        <?php
                                	}
                                    ?>
                                </div><!-- /.clearfix -->
                            </div><!-- /.form-group -->
                    	</div>
                    	<?php
                    	if($tpl['arr']['cnt_bookings'] > 0)
                    	{
                    	    $created = $tpl['arr']['last_booking'][0];
                    	    $id = $tpl['arr']['last_booking'][1];
                        	?>
                        	<div class="col-lg-3 col-md-4 col-sm-6">
                        		<div class="form-group">
                                    <label class="control-label"><?php __('lblClientLastBooking'); ?></label>
    
                                    <div class="clearfix">
                                        <p class="form-control-static"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $id;?>"><?php echo date($tpl['option_arr']['o_date_format'], strtotime($created));?>, <?php echo date($tpl['option_arr']['o_time_format'], strtotime($created));?></a></p>
                                    </div><!-- /.clearfix -->
                                </div><!-- /.form-group -->
                        	</div>
                        	<?php
                    	}
                        ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblStatus'); ?></label>

                                <div class="clearfix">
                                    <div class="switch onoffswitch-data pull-left">
                                        <div class="onoffswitch">
                                            <input type="checkbox" class="onoffswitch-checkbox" id="status" name="status"<?php echo $tpl['arr']['status']=='T' ? ' checked' : NULL;?>>
                                            <label class="onoffswitch-label" for="status">
                                                <span class="onoffswitch-inner" data-on="<?php __('filter_ARRAY_active');?>" data-off="<?php __('filter_ARRAY_inactive');?>"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- /.clearfix -->
                            </div><!-- /.form-group -->
                        </div><!-- /.col-md-3 -->
					</div>
					<div class="hr-line-dashed"></div>
					<div class="row">
                        
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('lblName'); ?></label>

                                <input type="text" id="customer_name" name="customer_name" value="<?php echo pjSanitize::html($tpl['arr']['customer_name']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->
                    
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('email'); ?></label>
								
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-at"></i></span>
                                	<input type="text" name="customer_email" id="customer_email" value="<?php echo pjSanitize::html($tpl['arr']['customer_email']); ?>" class="form-control email required" placeholder="info@domain.com" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>" data-msg-remote="<?php __('vr_email_taken', false, true);?>" maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->
						<div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('pass'); ?></label>

								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                	<input type="password" name="customer_password" id="customer_password" value="<?php echo pjSanitize::html($tpl['arr']['customer_password']); ?>" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>" maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_phone'); ?></label>

								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                	<input type="text" name="customer_phone" id="customer_phone" value="<?php echo pjSanitize::html($tpl['arr']['customer_phone']); ?>" class="form-control" placeholder="(123) 456-7890" maxlength="255">
                                </div>
                            </div>
                        </div><!-- /.col-md-3 -->

                    </div><!-- /.row -->

                    <div class="hr-line-dashed"></div>
                    
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_address_1'); ?></label>

                                <input type="text" name="customer_address_1" id="customer_address_1" value="<?php echo pjSanitize::html($tpl['arr']['customer_address_1']); ?>" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_address_2'); ?></label>

                                <input type="text" name="customer_address_2" id="customer_address_2" value="<?php echo pjSanitize::html($tpl['arr']['customer_address_2']); ?>" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_city'); ?></label>

                                <input type="text" name="customer_city" id="customer_city" value="<?php echo pjSanitize::html($tpl['arr']['customer_city']); ?>" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_state'); ?></label>

                                <input type="text" name="customer_state" id="customer_state" value="<?php echo pjSanitize::html($tpl['arr']['customer_state']); ?>" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_zip'); ?></label>

                                <input type="text" name="customer_zip" id="customer_zip" value="<?php echo pjSanitize::html($tpl['arr']['customer_zip']); ?>" class="form-control" maxlength="255">
                            </div>
                        </div><!-- /.col-md-3 -->

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_country'); ?></label>

                                <select name="customer_country" id="customer_country" class="form-control select-item">
                					<option value="">-- <?php __('lblChoose'); ?>--</option>
                					<?php
                					foreach ($tpl['country_arr'] as $v)
                					{
                					    ?><option value="<?php echo $v['id']; ?>"<?php echo $tpl['arr']['customer_country'] == $v['id'] ? ' selected="selected"' : NULL;?>><?php echo pjSanitize::html($v['country_title']); ?></option><?php
                					}
                					?>
                				</select>
                            </div>
                        </div><!-- /.col-md-3 -->
                        <div class="col-lg-6 col-md-8 col-sm-12">
                            <div class="form-group">
                                <label class="control-label"><?php __('booking_notes'); ?></label>

                                <textarea id="customer_notes" name="customer_notes" rows="4" class="form-control"><?php echo pjSanitize::clean($tpl['arr']['customer_notes']); ?></textarea>
                            </div>
                        </div><!-- /.col-md-3 -->
                    </div><!-- /.row -->

                    <div class="hr-line-dashed"></div>

                    <div class="clearfix">
                        <button type="submit" class="ladda-button btn btn-primary btn-lg btn-phpjabbers-loader pull-left" data-style="zoom-in" style="margin-right: 15px;">
                            <span class="ladda-label"><?php __('btnSave'); ?></span>
                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                        </button>
                        <a class="btn btn-white btn-lg pull-right" href="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminClients&action=pjActionIndex"><?php __('btnCancel'); ?></a>
                    </div><!-- /.clearfix -->
                </form>
            </div>
        </div>
    </div><!-- /.col-lg-12 -->
</div>
<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.email_exists = <?php x__encode('email_taken'); ?>;
</script>