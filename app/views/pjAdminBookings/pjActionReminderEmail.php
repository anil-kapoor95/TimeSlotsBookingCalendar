<?php
if (isset($tpl['arr']) && !empty($tpl['arr']))
{
    ?>
	<form action="" method="post" class="">
		<input type="hidden" name="send_email" value="1" />
		<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<input type="hidden" name="from" value="<?php echo $tpl['arr']['from']; ?>" />
		<?php if (!empty($tpl['arr']['to'])) : ?>
		<p class="alert alert-info"><?php printf(__('send_email_title', true), pjSanitize::clean($tpl['arr']['client_name']));?></p>
		<div class="form-group">
			<label class="control-label"><?php __('booking_email'); ?></label>
	
			<input type="text" name="to" class="form-control required email" value="<?php echo pjSanitize::html($tpl['arr']['to']); ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
		</div>
		<?php endif; ?>
		<div class="form-group">
			<label class="control-label"><?php __('booking_subject');?></label>
			<input type="text" name="subject" id="confirm_subject" class="form-control required" value="<?php echo pjSanitize::html(stripslashes($tpl['arr']['subject'])); ?>" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"/>
		</div>
		<div class="form-group">
			<label class="control-label"><?php __('booking_message');?></label>
			<div id="crMessageEditorWrapper">
				<textarea name="message" id="mceEditor" class="form-control required" data-msg-required="<?php __('plugin_base_this_field_is_required', false, true);?>"><?php echo stripslashes(str_replace(array('\r\n', '\n'), '&#10;', $tpl['arr']['message'])); ?></textarea>
			</div>			
		</div>
	</form>
	<?php
}else{
    ?>
    <div id="pjResendAlert" class="alert alert-warning">
   		<?php __('lblEmailNotificationNotSet')?>
    </div>
    <?php    
}
?>