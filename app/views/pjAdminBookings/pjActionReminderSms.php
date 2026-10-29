<?php
if (isset($tpl['arr']) && !empty($tpl['arr']))
{
	?>
	<form action="" method="post" class="form pj-form">
		<input type="hidden" name="send_sms" value="1" />
		<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<p><label><?php printf(__('send_sms_title', true), pjSanitize::clean($tpl['arr']['client_name']))?></label></p>
		<?php if (!empty($tpl['arr']['client_phone'])) : ?>
		<div class="form-group">
			<label class="control-label"><?php __('booking_phone'); ?></label>
	
			<input type="text" name="to" class="form-control required" value="<?php echo pjSanitize::html($tpl['arr']['client_phone']); ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
		</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label"><?php __('booking_message');?></label>
			<div id="crMessageEditorWrapper">
				<textarea name="message" class="form-control required" rows="5" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"><?php echo stripslashes(str_replace(array('\r\n', '\n'), '&#10;', $tpl['arr']['message'])); ?></textarea>
			</div>			
		</div>
	</form>
	<?php
}
?>