<?php
$cid = $controller->_get->toInt('cid');
$FORM = @$_SESSION[$controller->defaultForm . '_' . $cid];
$voucher_valid = false;
$voucher_msg = '';
if(isset($FORM['voucher_item']))
{
    if($FORM['voucher_item'] != NULL)
    {
        $voucher_valid = true;
        $voucher_msg = __('apply_voucher_msg_ARRAY_200', true);
    }
}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<?php __('front_voucher'); ?>
	</div><!-- /.panel-heading -->

	<div class="panel-body">
		<div class="pj-calendar-form">
			<div class="row">
				<label class="col-sm-4 control-label"><?php __('front_voucher_code');?></label><!-- /.col-sm-4 -->

				<div class="col-sm-8">
					<div class="row form-group">
						<div class="col-sm-8">
							<input type="text" id="pjTsVoucherCodeField" name="voucher_code" class="form-control" value="<?php echo isset($FORM['voucher_code']) ? pjSanitize::html($FORM['voucher_code']) : NULL; ?>">
						</div>	
						<div class="col-sm-4">
							<a href="#" class="btn btn-primary pull-left pjTsApplyCode" style="display:<?php echo $voucher_valid == false ? '' : 'none';?>;"><?php echo __('front_btn_apply', false, true); ?></a>
							<a href="#" class="btn btn-primary pull-left pjTsRemoveCode" style="display:<?php echo $voucher_valid == false ? 'none' : '';?>;"><?php echo __('front_btn_remove', false, true); ?></a>
						</div>	
					</div>
					<div id="pjTsVoucherMessage" class="alert<?php echo $voucher_valid == false ? '' : ' alert-info';?>" style="display:<?php echo $voucher_valid == false ? ' none' : '';?>;"><?php echo $voucher_msg;?></div>
				</div><!-- /.col-sm-8 -->
			</div><!-- /.row -->
		</div><!-- /.pj-calendar-form -->
	</div><!-- /.panel-body -->
</div><!-- /.panel -->