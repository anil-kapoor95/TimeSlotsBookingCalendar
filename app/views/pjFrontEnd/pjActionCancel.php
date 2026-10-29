<div style="margin: 0 auto; width: 1024px">
	<?php
	if (isset($tpl['response'], $tpl['response']['status']) && $tpl['response']['status'] == "OK")
	{
	    $hidePrices = (int) $tpl['_option_arr']['o_hide_prices'] === 1;
	    ?>
	    <h3><?php __('front_selected_timeslots'); ?></h3>
	    <table class="table table-bordered">
	    	<tbody>
	    		<?php
	    		if (isset($tpl['arr'], $tpl['arr']['bs_arr']))
	    		{
	    		    if (!empty($tpl['arr']['bs_arr']))
	    		    {
	    		        ?>
	    		        <tr>
	    		        	<td><strong><?php __('front_cart_date'); ?></strong></td>
	    		        	<td><strong><?php __('front_cart_start_time'); ?></strong></td>
	    		        	<td><strong><?php __('front_cart_end_time'); ?></strong></td>
	    		        	<?php if (!$hidePrices) : ?>
	    		        	<td><strong><?php __('front_cart_price'); ?></strong></td>
	    		        	<?php endif;?>
	    		        </tr>
	    		        <?php
	    		        foreach ($tpl['arr']['bs_arr'] as $slot)
	    		        {
	    		            $sd = date("Y-m-d", $slot['start_ts']);
	    		            $_date = $slot['booking_date'] == $sd ? $slot['booking_date'] : $sd;
	    		            ?>
	    		            <tr>
	    		            	<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($_date)); ?></td>
	    		            	<td><?php echo date($tpl['option_arr']['o_time_format'], $slot['start_ts']); ?></td>
	    		            	<td><?php echo date($tpl['option_arr']['o_time_format'], $slot['end_ts']); ?></td>
	    		            	<?php if (!$hidePrices) : ?>
	    		            	<td><?php echo (int)$slot['cnt'];?> x <?php echo pjCurrency::formatPrice($slot['price']); ?></td>
	    		            	<?php endif;?>
	    		            </tr>
	    		            <?php
	    		        }
	    		    }else{
	    		        ?><tr><td colspan="<?php echo $hidePrices == 1 ? '3' : '4';?>"><strong><?php __('front_cart_empty'); ?></strong></td></tr><?php
	    		    }
	    		}
	    		?>
	    	</tbody>
	    </table>
	    <?php
	    if ((int) $tpl['_option_arr']['o_disable_payments'] === 0 && (int) $tpl['_option_arr']['o_hide_prices'] === 0)
	    {
    	    ?>
    	    <h3><?php __('front_summary'); ?></h3>
    	    <table class="table table-bordered">
    	    	<tbody>
    	    		<tr>
    	    			<td><?php __('front_summary_price'); ?></td>
    	    			<td><?php echo pjCurrency::formatPrice($tpl['arr']['booking_price']); ?></td>
    	    		</tr>
    	    		<tr>
    	    			<td><?php __('front_summary_tax'); ?></td>
    	    			<td><?php echo pjCurrency::formatPrice($tpl['arr']['booking_tax']); ?></td>
    	    		</tr>
    	    		<tr>
    	    			<td><?php __('front_summary_total'); ?></td>
    	    			<td><?php echo pjCurrency::formatPrice($tpl['arr']['booking_total']); ?></td>
    	    		</tr>
    	    		<tr>
    	    			<td><?php __('front_summary_deposit'); ?></td>
    	    			<td><?php echo pjCurrency::formatPrice($tpl['arr']['booking_deposit']); ?></td>
    	    		</tr>
    	    		<?php 
    	    		if ((int) $tpl['_option_arr']['o_disable_payments'] === 0 && isset($tpl['arr']['payment_method']))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('booking_payment_method'); ?></td>
    	    		    	<td><?php echo @$tpl['payment_titles'][$tpl['arr']['payment_method']]; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		?>
    	    	</tbody>
    	    </table>
    	    <?php
	    }
	    ?>
	    <h3><?php __('front_preview_form'); ?></h3>
	    <form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjFrontEnd&amp;action=pjActionCancel&amp;uuid=<?php echo $controller->_get->toString('uuid'); ?>&amp;hash=<?php echo $controller->_get->toString('hash'); ?>" method="post">
			<input type="hidden" name="ts_cancel_booking" value="1" />
			<input type="hidden" name="id" value="<?php echo $controller->_get->toInt('id'); ?>" />
			<input type="hidden" name="hash" value="<?php echo $controller->_get->toString('hash'); ?>" />
			<table class="table table-bordered">
    	    	<tbody>
    	    		<?php
    	    		if (in_array($tpl['_option_arr']['o_bf_name'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_name'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_name']) ? pjSanitize::html($tpl['arr']['customer_name']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_email'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_email'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_email']) ? pjSanitize::html($tpl['arr']['customer_email']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_phone'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_phone'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_phone']) ? pjSanitize::html($tpl['arr']['customer_phone']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_address_1'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_address_1'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_address_1']) ? pjSanitize::html($tpl['arr']['customer_address_1']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_address_2'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_address_2'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_address_2']) ? pjSanitize::html($tpl['arr']['customer_address_2']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_city'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_city'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_city']) ? pjSanitize::html($tpl['arr']['customer_city']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_state'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_state'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_state']) ? pjSanitize::html($tpl['arr']['customer_state']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_zip'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_zip'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_zip']) ? pjSanitize::html($tpl['arr']['customer_zip']) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_country'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_country'); ?></td>
    	    		    	<td><?php echo pjSanitize::html(@$tpl['arr']['country_name']); ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		if (in_array($tpl['_option_arr']['o_bf_notes'], array(2, 3)))
    	    		{
    	    		    ?>
    	    		    <tr>
    	    		    	<td><?php __('opt_o_bf_notes'); ?></td>
    	    		    	<td><?php echo isset($tpl['arr']['customer_notes']) ? nl2br(pjSanitize::html($tpl['arr']['customer_notes'])) : NULL; ?></td>
    	    		    </tr>
    	    		    <?php
    	    		}
    	    		?>
    	    		
    	    	</tbody>
    	    </table>
    	    <input type="submit" value="<?php __('front_button_cancel_booking', false, true); ?>" class="btn btn-primary" />
    	    <br/><br/>
		</form>
	    <?php
	}elseif (isset($tpl['response'], $tpl['response']['status']) && $tpl['response']['status'] == 'ERR') {
	    ?>
	    <div class="alert alert-danger">
	    	<i class="fa fa-exclamation-triangle m-r-xs"></i>
			<strong><?php __('front_system_msg'); ?></strong>
			<?php echo $tpl['response']['text']; ?>
	    </div>
		<?php
	}
	?>
</div>