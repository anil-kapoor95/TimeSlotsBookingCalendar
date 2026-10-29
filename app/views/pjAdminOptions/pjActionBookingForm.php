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
            if(in_array($error_code, array('AO04')))
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
                                <h3><?php echo @$titles['AO24'];?></h3>

                                <small><?php echo @$bodies['AO24'];?></small>
                            </div>
                            <div class="ibox-content">
                            	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionUpdate" method="post" id="frmUpdateCalendar" autocomplete="off" enctype="multipart/form-data">
    								<input type="hidden" name="options_update" value="1" />
    								<input type="hidden" name="csrf_token" value="<?php echo pjAppController::getCsrfToken(); ?>" />
    								<input type="hidden" name="tab_id" value="4" />
    								<input type="hidden" name="calendar_id" value="<?php echo $controller->_get->toInt('id');?>" />
    								<input type="hidden" name="next_action" value="pjActionBookingForm" />
    								<div class="row">
        								<?php
        								foreach ($tpl['group_1_arr'] as $option)
        								{
        								    ?>
        								    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            								    <div class="form-group">
                                                    <label class="control-label"><?php __('opt_' . $option['key']); ?></label>
                                                    <?php
                                                    include dirname(__FILE__) . '/elements/enum.php';
                                                    ?>
                                                </div><!-- /.form-group -->
                                            </div>
        								    <?php
        								}
        								?>
    								</div>
    								<div class="hr-line-dashed"></div>
    								<div class="row">
        								<?php
        								foreach ($tpl['group_2_arr'] as $option)
        								{
        								    ?>
        								    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            								    <div class="form-group">
                                                    <label class="control-label"><?php __('opt_' . $option['key']); ?></label>
                                                    <?php
                                                    include dirname(__FILE__) . '/elements/enum.php';
                                                    ?>
                                                </div><!-- /.form-group -->
                                            </div>
        								    <?php
        								}
        								?>
    								</div>
    								<div class="hr-line-dashed"></div>
    								<div class="row">
        								<?php
        								foreach ($tpl['group_3_arr'] as $option)
        								{
        								    ?>
        								    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
            								    <div class="form-group">
                                                    <label class="control-label"><?php __('opt_' . $option['key']); ?></label>
                                                    <?php
                                                    include dirname(__FILE__) . '/elements/enum.php';
                                                    ?>
                                                </div><!-- /.form-group -->
                                            </div>
        								    <?php
        								}
        								?>
    								</div>
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