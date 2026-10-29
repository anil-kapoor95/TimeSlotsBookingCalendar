<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);

include PJ_VIEWS_PATH . 'pjLayouts/elements/update-calendar-title.php';
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
        {
            if(in_array($error_code, array('AO03')))
            {
                ?>
    			<div class="alert alert-success">
    				<i class="fa fa-check m-r-xs"></i>
    				<strong><?php echo @$titles[$error_code]; ?></strong>
    				<?php echo @$bodies[$error_code]; ?>
    			</div>
    			<?php
            }
        }
        ?>
    	<div class="tabs-left">
    		<?php include PJ_VIEWS_PATH . 'pjLayouts/elements/tab-options.php'; ?>
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					<div class="panel-body-inner">
    						
							<div class="ibox-content ibox-heading">
                                <h3><?php echo @$titles['AO23'];?></h3>

                                <small><?php echo @$bodies['AO23'];?></small>
                            </div>
                            <div class="ibox-content">
                            	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" class="form-horizontal" method="post" id="frmUpdateCalendar" autocomplete="off" enctype="multipart/form-data">
    								<input type="hidden" name="options_update" value="1" />
    								<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
    								<input type="hidden" name="tab_id" value="3" />
    								<input type="hidden" name="calendar_id" value="<?php echo $controller->_get->toInt('id');?>" />
    								<input type="hidden" name="next_action" value="pjActionIndex" />
    								<?php
    								foreach ($tpl['arr'] as $option)
    								{
    								    if ((int) $option['is_visible'] === 0) continue;
    								    
    								    $rowStyle = NULL;
    								    $rowClass = NULL;
    								    if(in_array($option['key'], array('o_reminder_sms_hours', 'o_reminder_email_before')))
    								    {
    								        $rowClass = " boxReminder";
    								        $rowStyle = "display: none";
    								        switch ($tpl['option_arr']['o_reminder_enable'])
    								        {
    								            case 1:
    								                $rowStyle = NULL;
    								                break;
    								        }
    								    }
    								    ?>
    								    <div class="form-group<?php echo $rowClass;?>" style="<?php echo $rowStyle; ?>">
                                            <label class="col-sm-3 control-label"><?php __('opt_' . $option['key']); ?></label>
                                            <div class="col-lg-5 col-sm-7">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <?php
                                                        switch ($option['type'])
                                                        {
                                                            case 'string':
                                                                ?><input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control" value="<?php echo pjSanitize::html($option['value']); ?>"><?php
                                                                break;
                                                            case 'text':
                                                                ?><textarea name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control"><?php echo pjSanitize::html($option['value']); ?></textarea><?php
                                                                break;
                                                            case 'int':
                                                                if(in_array($option['key'], array('o_hours_before', 'o_reminder_sms_hours', 'o_reminder_email_before')))
                                                                {
                                                                    ?>
                                                                    <div class="row">
                                                                		<div class="col-md-9">
                                                                			<input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-int" value="<?php echo pjSanitize::html($option['value']); ?>">
                                                                		</div>
                                                                		<div class="col-md-3">
                                                                			<p class="form-control-static"><?php __('lblOptionHours');?></p>
                                                                		</div>
                                                                	</div>
                                                                    <?php
                                                                }else{
                                                                    ?>
                                                                    <input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-int" value="<?php echo pjSanitize::html($option['value']); ?>">
                                                                    <?php
                                                                }
                                                                break;
                                                            case 'float':
                                                                if(in_array($option['key'], array('o_booking_price'))) {
                                                                    ?>
                                                                    <div class="input-group">
                                                                        <input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control decimal number text-right" value="<?php echo number_format($option['value'], 2) ?>" data-msg-number="<?php __('pj_please_enter_valid_number', false, true);?>">
            
                                                                        <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency'], false) ?></span>
                                                                    </div>
                                                                    <?php
                                                                } else if(in_array($option['key'], array('o_deposit'))) {
                                                                	?>
                                                                	<div class="row">
                                                                		<div class="col-md-6">
                                                                			<input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-float number" value="<?php echo (int) $option['value']; ?>" />
                                                                		</div>
                                                                		<div class="col-md-6">
            																<select name="value-enum-o_deposit_type" class="form-control">
            																	<?php
            																	$default = explode("::", $tpl['_option_arr']['o_deposit_type']['value']);
            																	$enum = explode("|", $default[0]);
            																	
            																	$enumLabels = array();
            																	if (!empty($tpl['_option_arr']['o_deposit_type']['label']) && strpos($tpl['_option_arr']['o_deposit_type']['label'], "|") !== false)
            																	{
            																	    $enumLabels = explode("|", $tpl['_option_arr']['o_deposit_type']['label']);
            																	}
            																	$enum_arr = __('enum_arr', true);
            																	foreach ($enum as $k => $el)
            																	{
            																		?><option value="<?php echo $default[0].'::'.$el; ?>"<?php echo $default[1] == $el ? ' selected="selected"' : NULL; ?>><?php echo isset($enum_arr[$el]) ? $enum_arr[$el] : (array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el)); ?></option><?php
            																	}
            																	?>
            																</select>
            															</div>
            														</div>
            														<?php
            													} else if(in_array($option['key'], array('o_tax'))) {
            														?>
            														<div class="input-group">
            															<input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control number text-right" value="<?php echo number_format($option['value'], 2) ?>" />
            				
            															<span class="input-group-addon">%</span>
            														</div>
            														<?php 
            													} else {
                                                                    ?><input type="text" name="value-<?php echo $option['type']; ?>-<?php echo $option['key']; ?>" class="form-control field-float number" value="<?php echo number_format($option['value'], 2) ?>"><?php
                                                                }
                                                                break;
                                                            case 'enum':
            													include dirname(__FILE__) . '/elements/enum.php';
                                                                break;
            												case 'bool':
            													include dirname(__FILE__) . '/elements/switch.php';
            													break;
                                                        }
                                                        if(in_array($option['key'], array('o_max_slots_per_booking', 'o_status_if_paid', 'o_status_if_not_paid', 'o_deposit', 'o_thankyou_page', 'o_cancel_page')))
                                                        {
                                                            ?>
                                                            <small><?php __( 'opt_' . $option['key'] . '_text');?></small>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div><!-- /.col-sm-6 -->
            
                                                </div><!-- /.row -->
                                            </div><!-- /.col-lg-5 col-sm-7 -->
                                        </div><!-- /.form-group -->
    								    <?php
    								}
    								?>
    								<div class="hr-line-dashed"></div>
    								<div class="clearfix">
                                        <button class="ladda-button btn btn-primary btn-lg pull-left btn-phpjabbers-loader" data-style="zoom-in">
                                            <span class="ladda-label"><?php __('plugin_base_btn_save'); ?></span>
                                            <?php include $controller->getConstant('pjBase', 'PLUGIN_VIEWS_PATH') . 'pjLayouts/elements/button-animation.php'; ?>
                                        </button>
                                    </div>
    							</form>
                            </div><!-- /.ibox-content -->
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.isFlagReady = "<?php echo $tpl['is_flag_ready'] ? 1 : 0;?>";
myLabel.choose = "-- <?php __('lblChoose', false, true); ?> --";
<?php if ($tpl['is_flag_ready']) : ?>
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
</script>