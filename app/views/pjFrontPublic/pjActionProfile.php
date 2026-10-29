<?php 
include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
$get_cid = $controller->_get->toInt('cid');
$FORM =  @$_SESSION[$controller->defaultClient];
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<?php __('front_profile_form'); ?>
	</div><!-- /.panel-heading -->
	
	<form id="pjTsProfileForm_<?php echo $get_cid;?>" action="" method="post" class="pjTsSelectorCheckoutForm">
		<input type="hidden" name="ts_profile" value="1" />
		<input type="hidden" name="foreign_id" value="<?php echo @$FORM['foreign_id'];?>" />
		<div class="panel-body">
			<div class="pj-calendar-form">
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_name'); ?><span class="tsAsterisk">*</span></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_name" class="form-control required" value="<?php echo isset($FORM['name']) ? pjSanitize::html($FORM['name']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_name', false)); ?>">
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
        		<div class="row">
        			<label class="col-sm-4 control-label"><?php __('opt_o_bf_email'); ?><span class="tsAsterisk">*</span></label><!-- /.col-sm-4 -->
        
        			<div class="col-sm-8">
        				<input type="text" name="customer_email" class="form-control email required" value="<?php echo isset($FORM['email']) ? pjSanitize::html($FORM['email']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_email', false)); ?>" data-msg-email="<?php echo pjSanitize::html(__('front_v_email_format', false)); ?>" data-msg-remote="<?php echo pjSanitize::html(__('front_v_email_exists', false)); ?>" />
        				<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
        			</div><!-- /.col-sm-8 -->
        		</div><!-- /.row -->
        		<div class="row">
        			<label class="col-sm-4 control-label"><?php __('front_password'); ?><span class="tsAsterisk">*</span></label><!-- /.col-sm-4 -->
        
        			<div class="col-sm-8">
        				<input type="password" name="customer_password" value="<?php echo isset($FORM['password']) ? pjSanitize::html($FORM['password']) : NULL; ?>" class="form-control required" data-msg-required="<?php echo pjSanitize::html(__('front_v_password', false)); ?>"/>
        				<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
        			</div><!-- /.col-sm-8 -->
        		</div><!-- /.row -->
        		<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_phone'); ?><?php if ((int) $tpl['option_arr']['o_bf_phone'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_phone" class="form-control<?php echo $tpl['option_arr']['o_bf_phone'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['phone']) ? pjSanitize::html($FORM['phone']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_phone', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				<div class="row">
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
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_state'); ?><?php if ((int) $tpl['option_arr']['o_bf_state'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_state" class="form-control<?php echo $tpl['option_arr']['o_bf_state'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_state']) ? pjSanitize::html($FORM['customer_state']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_state', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_city'); ?><?php if ((int) $tpl['option_arr']['o_bf_city'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->
					

					<div class="col-sm-8">
						<input type="text" name="customer_city" class="form-control<?php echo $tpl['option_arr']['o_bf_city'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_city']) ? pjSanitize::html($FORM['customer_city']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_city', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('booking_address_1'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_1'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_address_1" class="form-control<?php echo $tpl['option_arr']['o_bf_address_1'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_address_1']) ? pjSanitize::html($FORM['customer_address_1']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_address_1', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('booking_address_2'); ?><?php if ((int) $tpl['option_arr']['o_bf_address_2'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_address_2" class="form-control<?php echo $tpl['option_arr']['o_bf_address_2'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_address_2']) ? pjSanitize::html($FORM['customer_address_2']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_address_1', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_zip'); ?><?php if ((int) $tpl['option_arr']['o_bf_zip'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<input type="text" name="customer_zip" class="form-control<?php echo $tpl['option_arr']['o_bf_zip'] == 3 ? ' required' : NULL; ?>" value="<?php echo isset($FORM['customer_zip']) ? pjSanitize::html($FORM['customer_zip']) : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_zip', false)); ?>" />
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
				
				<div class="row">
					<label class="col-sm-4 control-label"><?php __('opt_o_bf_notes'); ?><?php if ((int) $tpl['option_arr']['o_bf_notes'] === 3) : ?><span class="tsAsterisk">*</span><?php endif; ?></label><!-- /.col-sm-4 -->

					<div class="col-sm-8">
						<textarea name="customer_notes" cols="30" rows="10" class="form-control<?php echo $tpl['option_arr']['o_bf_notes'] == 3 ? ' required' : NULL; ?>" data-msg-required="<?php echo pjSanitize::html(__('front_v_notes', false)); ?>"><?php echo isset($FORM['customer_notes']) ? pjSanitize::html($FORM['customer_notes']) : NULL; ?></textarea>
						<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
					</div><!-- /.col-sm-8 -->
				</div><!-- /.row -->
        		
        		<div class="row" style="display: none;">
            		<div class="col-lg-12 col-md-12 col-sm-12">
                		<div id="pjProfileMessage_<?php echo $get_cid;?>" class="alert alert-danger text-center"></div>
                	</div>
                </div>
        	</div><!-- /.pj-calendar-form -->
		</div><!-- /.panel-body -->
		
		<div class="panel-footer text-center">
			
			<button type="submit" class="btn btn-primary"><?php __('front_btn_save', false, true); ?></button>
		</div><!-- /.panel-footer -->
	</form>
</div>