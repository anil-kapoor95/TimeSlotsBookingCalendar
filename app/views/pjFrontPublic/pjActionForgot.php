<?php 
include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
$get_cid = $controller->_get->toInt('cid');
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<?php __('front_forgot_form'); ?>
	</div><!-- /.panel-heading -->
	
	<form id="pjTsForgotForm_<?php echo $get_cid;?>" action="" method="post" class="pjTsSelectorCheckoutForm">
		<input type="hidden" name="ts_forgot" value="1" />
		<div class="panel-body">
			<div class="pj-calendar-form">
        		<div class="row">
        			<label class="col-sm-4 control-label"><?php __('opt_o_bf_email'); ?><span class="tsAsterisk">*</span></label><!-- /.col-sm-4 -->
        
        			<div class="col-sm-8">
        				<input type="text" name="email" class="form-control email required" data-msg-required="<?php echo pjSanitize::html(__('front_v_email', false)); ?>" data-msg-email="<?php echo pjSanitize::html(__('front_v_email_format', false)); ?>" />
        				<div class="help-block with-errors"><ul class="list-unstyled"></ul></div>
        			</div><!-- /.col-sm-8 -->
        		</div><!-- /.row -->
        		
        		<div class="row" style="display: none;">
            		<div class="col-lg-12 col-md-12 col-sm-12">
                		<div id="pjForgotMessage_<?php echo $get_cid;?>" class="alert alert-danger text-center"></div>
                	</div>
                </div>
        	</div><!-- /.pj-calendar-form -->
		</div><!-- /.panel-body -->
		
		<div class="panel-footer text-center">
			
			<button type="submit" class="btn btn-primary"><?php __('front_btn_send', false, true); ?></button>
		</div><!-- /.panel-footer -->
	</form>
</div>