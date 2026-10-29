<?php
if (isset($tpl['status']) && $tpl['status'] == 'OK')
{
	if(isset($tpl['duplicated']))
	{
		?>
		<div class="panel panel-primary">
			<div class="panel-body">
				<p class="text-danger"><?php __('front_duplicated_slots');?></p>
			</div>
			<div class="panel-footer">
				<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><?php __('front_start_over', false, true); ?></a>
			</div><!-- /.panel-footer -->
		</div>
		<?php
	}else{
	    $get_cid = $controller->_get->toInt('cid');
	    $FORM = @$_SESSION[$controller->defaultForm . '_' . $get_cid];
		include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
		$tsb_step = 2;
		include PJ_VIEWS_PATH . 'pjFrontPublic/elements/steps.php';
		?>
		<div class="tsb-co">
		<div class="tsb-aside"><?php include PJ_VIEWS_PATH . 'pjFrontPublic/elements/summary.php'; ?></div>
		<div class="tsb-main">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<?php __('front_booking_form'); ?>
			</div><!-- /.panel-heading -->
	
			<form action="" method="post" class="pjTsSelectorPreviewForm">
				<input type="hidden" name="ts_preview" value="1" />
				<div class="panel-body">
					<p class="tsb-only tsb-lead"><?php echo pjSanitize::html($tsb_label('front_confirm_lead', 'Please check your details below and press Confirm to complete your booking.')); ?></p>
					<div class="pj-calendar-form">
						<?php
						if (in_array($tpl['option_arr']['o_bf_name'], array(2, 3)))
						{ 
							$tsb_head('contact');
							?>
							<div class="row tsb-f-name">
								<label class="col-sm-4 control-label"><?php __('opt_o_bf_name'); ?><?php if ((int) $tpl['option_arr']['o_bf_name'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
			
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo isset($FORM['customer_name']) ? pjSanitize::html($FORM['customer_name']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_email']) ? pjSanitize::html($FORM['customer_email']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_phone']) ? pjSanitize::html($FORM['customer_phone']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo pjSanitize::html(@$tpl['country_arr']['name']); ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_state']) ? pjSanitize::html($FORM['customer_state']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_city']) ? pjSanitize::html($FORM['customer_city']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_address_1']) ? pjSanitize::html($FORM['customer_address_1']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_address_2']) ? pjSanitize::html($FORM['customer_address_2']) : NULL; ?></p>
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
									<p class="form-control-static"><?php echo isset($FORM['customer_zip']) ? pjSanitize::html($FORM['customer_zip']) : NULL; ?></p>
								</div><!-- /.col-sm-8 -->
							</div><!-- /.row -->
							<?php
						}
						if (in_array($tpl['option_arr']['o_bf_notes'], array(2, 3)))
						{ 
							$tsb_head('extra');
							?>
							<div class="row tsb-f-notes tsb-wide">
								<label class="col-sm-4 control-label"><?php __('opt_o_bf_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
			
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo isset($FORM['customer_notes']) ? nl2br(pjSanitize::html($FORM['customer_notes'])) : NULL; ?></p>
								</div><!-- /.col-sm-8 -->
							</div><!-- /.row -->
							<?php
						}
						if ((int) $tpl['option_arr']['o_disable_payments'] === 0 &&
								(int) $tpl['option_arr']['o_hide_prices'] === 0 &&
								isset($tpl['amount']) && $tpl['amount']['deposit'] > 0)
						{
							$tsb_head('payment');
							?>
							<div class="row tsb-f-pay">
								<label class="col-sm-4 control-label"><?php __('booking_payment_method'); ?><span class="tsAsterisk">*</span></label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo $tpl['payment_titles'][$FORM['payment_method']];; ?></p>
								</div>
							</div>
							<div class="row tsb-wide" style="display: <?php echo @$FORM['payment_method'] != 'bank' ? 'none' : NULL; ?>">
								<label class="col-sm-4 control-label"><?php __('booking_bank_account'); ?></label>
								<div class="col-sm-8">
									<p class="form-control-static"><?php echo nl2br(pjSanitize::html($tpl['bank_account'])); ?></p>
								</div>
							</div>
							<?php
						}
						?>
					</div><!-- /.form -->
				</div><!-- /.panel-body -->
		
				<div class="panel-footer">
					<a href="#" class="btn btn-primary pull-left pjTsSelectorCheckout"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_cancel', false, true); ?></a>
		
					<button type="submit" class="btn btn-primary pull-right"><?php __('front_button_confirm', false, true); ?></button>
				</div><!-- /.panel-footer -->
			</form>
		</div><!-- /.panel -->
		</div><!-- /.tsb-main -->
		</div><!-- /.tsb-co -->
		<?php
	}
} else {
	?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_system_msg'); ?>
		</div><!-- /.panel-heading -->
		<div class="panel-body">
			<?php __('front_preview_na'); ?>
		</div>
		<div class="panel-footer">
			<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><?php __('front_start_over', false, true); ?></a>
		</div><!-- /.panel-footer -->
	</div>
	<?php
}
?>