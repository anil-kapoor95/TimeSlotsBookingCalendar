<?php
$titles = __('error_titles', true);
$bodies = __('error_bodies', true);

include PJ_VIEWS_PATH . 'pjLayouts/elements/update-calendar-title.php';
?>

<div class="fakeLanguageWrapper" style="display: none;">
    <?php
    foreach ($tpl['lp_arr'] as $v)
    {
        ?>
        <div class="<?php echo $tpl['is_flag_ready'] ? 'input-group ' : NULL;?>pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 1 ? 'block' : 'none'; ?>">
            <input type="text" class="form-control" name="i18n[<?php echo $v['id']; ?>][fake_field]" data-msg-required="<?php __('script_required_field');?>">
            <?php if ($tpl['is_flag_ready']) : ?>
            <span class="input-group-addon pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="<?php echo pjSanitize::html($v['name']); ?>"></span>
            <?php endif; ?>
        </div>
        <?php
    }
    ?>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="tabs-container">
    	<?php
    	$error_code = $controller->_get->toString('err');
    	if (!empty($error_code))
        {
            if(in_array($error_code, array('AO06')))
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
        $calendar_id = $controller->getCalendarId();
        if($controller->_get->check('id') && $controller->_get->toInt('id') > 0)
        {
            $calendar_id = $controller->_get->toInt('id');
        }
        ?>
        <input type="hidden" id="pjCalendarId" name="calendar_id" value="<?php echo $calendar_id;?>"/>
    	<div class="tabs-left">
    		<?php include PJ_VIEWS_PATH . 'pjLayouts/elements/tab-options.php'; ?>
    		<div class="tab-content">
    			<div class="tab-pane active">
    				<div class="panel-body">
    					<div class="panel-body-inner">
    						
                            <?php
                            $payment_methods = __('payment_methods', true);
                            $sort_arr = array('paypal','authorize','paypal_express','2checkout','stripe','mollie','skrill','world_pay','braintree');
                            $active_arr = array();
                            $other_methods = array();
                            if(pjObject::getPlugin('pjPayments') !== NULL)
                            {
                                $active_arr = pjPayments::getActivePaymentMethods($controller->getCalendarId());
                                $other_methods = pjPayments::getPaymentMethods();
                            }
                            if(!empty($active_arr))
                            {
                                ?>
                                <div class="ibox-content ibox-heading">
                                    <h3><?php __('plugin_payments_active_payment_gateways');?></h3>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <?php
                                        if(pjObject::getPlugin('pjPayments') !== NULL)
                                        {
                                            $active_arr = pjUtil::sortArrayByArray($active_arr, $sort_arr);
                                            foreach($active_arr as $payment_method => $name)
                                            {
                                                if(!in_array($payment_method, array('cash', 'bank')))
                                                {
                                                    $pjPlugin = pjPayments::getPluginName($payment_method);
                                                    if(pjObject::getPlugin($pjPlugin) !== NULL)
                                                    {
                                                        ?>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                            <a href="#" class="payment paymentLink active" data-method="<?php echo $payment_method;?>" title="<?php echo $name;?>"><img src="<?php echo PJ_IMG_PATH?>backend/payments/<?php echo $payment_method?>.png" alt="<?php echo $name;?>"></a>
                                                        </div>
                                                        <?php
                                                    }
                                                }else{
                                                    ?>
                                                    <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                        <a href="#" class="payment paymentLink active" data-method="<?php echo $payment_method;?>" title="<?php echo $name;?>"><img src="<?php echo PJ_IMG_PATH?>backend/payments/<?php echo $payment_method?>.png" alt="<?php echo $name;?>"></a>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </div><!-- /.row -->
                                </div><!-- /.ibox-content -->
                                <?php
                            }
                            if(!empty($other_methods))
                            {
                                ?>
                                <div class="ibox-content ibox-heading">
                                    <h3><?php __('plugin_payments_add_payment_gateways');?></h3>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <?php
                                        if(pjObject::getPlugin('pjPayments') !== NULL)
                                        {
                                            $other_methods = pjUtil::sortArrayByArray($other_methods, $sort_arr);
                                            foreach($other_methods as $payment_method => $name)
                                            {
                                                if(!array_key_exists($payment_method, $active_arr))
                                                {
                                                    $pjPlugin = pjPayments::getPluginName($payment_method);
                                                    if(pjObject::getPlugin($pjPlugin) !== NULL)
                                                    {
                                                        ?>
                                                        <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                            <a href="#" class="payment paymentLink" data-method="<?php echo $payment_method;?>" title="<?php echo $name;?>"><img src="<?php echo PJ_IMG_PATH?>backend/payments/<?php echo $payment_method?>.png" alt="<?php echo $name;?>"></a>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div><!-- /.ibox-content -->
                                <?php
                            }
                            if((array_key_exists('cash', $other_methods) && !array_key_exists('cash', $active_arr)) || (array_key_exists('bank', $other_methods) && !array_key_exists('bank', $active_arr)))
                            {
                                ?>
                                <div class="ibox-content ibox-heading">
                                    <h3><?php __('script_offline_payment_methods');?></h3>
                                </div>
                                <div class="ibox-content">
                                    <div class="row">
                                        <?php
                                    	if(array_key_exists('bank', $other_methods) && !array_key_exists('bank', $active_arr))
                                    	{
                                    	    ?>
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                <a href="#" class="payment paymentLink" data-method="bank" title="<?php echo $payment_methods['bank'];?>"><img src="<?php echo PJ_IMG_PATH?>backend/payments/bank.png" alt="<?php echo $payment_methods['bank'];?>"></a>
                                            </div>
                                            <?php
                                    	}
                                    	if(array_key_exists('cash', $other_methods) && !array_key_exists('cash', $active_arr))
                                    	{
                                    	    ?>
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                                <a href="#" class="payment paymentLink" data-method="cash" title="<?php echo $payment_methods['cash'];?>"><img src="<?php echo PJ_IMG_PATH?>backend/payments/cash.png" alt="<?php echo $payment_methods['cash'];?>"></a>
                                            </div>
                                            <?php
                                    	}
                                    	?>
                                    </div>
                                </div><!-- /.ibox-content -->
                                <?php
                            }
                            ?>
    					</div><!-- /.panel-body-inner -->
    				</div><!-- /.panel-body -->
    			</div><!-- /.tab-pane active -->
    		</div><!-- /.tab-content -->
    	</div><!-- /.tabs-left -->
    </div><!-- /.tabs-container -->
</div><!-- /.wrapper wrapper-content animated fadeInRight -->

<div class="modal inmodal fade" id="paymentModal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div id="modalContent" class="modal-content">

        </div>
    </div>
</div>

<script type="text/javascript">
var myLabel = myLabel || {};
myLabel.isFlagReady = "<?php echo $tpl['is_flag_ready'] ? 1 : 0;?>";
<?php if ($tpl['is_flag_ready']) : ?>
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
<?php endif; ?>
</script>