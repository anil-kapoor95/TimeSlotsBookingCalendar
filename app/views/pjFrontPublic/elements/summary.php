<div class="panel panel-primary">
	<div class="panel-heading">
		<?php __('front_selected_timeslots'); ?>
	</div><!-- /.panel-heading -->
	<?php
	if (isset($tpl['cart_arr']))
	{ 
	    
		if (!empty($tpl['cart_arr']) && array_key_exists($controller->_get->toInt('cid'), $tpl['cart_arr']) && !empty($tpl['cart_arr'][$controller->_get->toInt('cid')]))
		{
			$hidePrices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
			
			$total = 0;
			foreach ($tpl['cart_arr'] as $cid => $date_arr)
			{
				if ($cid != $controller->_get->toInt('cid'))
				{
					continue;
				}
				foreach ($date_arr as $date => $time_arr)
				{
					foreach ($time_arr as $time => $q)
					{
						$total += @$tpl['cart_price_arr'][$cid][$time];
					}
				}
			}
			?>
			<div class="panel-body">
				<ul class="tooltip-view-table tsb-cart-list">
					<li>
						<div class="col-size-3"><strong><?php __('front_cart_date'); ?></strong></div><!-- /.col-size-1 -->
						<div class="col-size-3"><strong><?php __('front_cart_start_time'); ?></strong></div><!-- /.col-size-1 -->
						<div class="col-size-3"><strong><?php __('front_cart_end_time'); ?></strong></div><!-- /.col-size-1 -->
						<?php
						if (!$hidePrices)
						{
							?>
							<div class="col-size-3"><strong><?php __('front_cart_price'); ?></strong></div><!-- /.col-size-3 -->
							<?php
						}else{
							?>
							<div class="col-size-3"><strong>&nbsp;</strong></div><!-- /.col-size-3 -->
							<?php
						} 
						?>
						<div class="col-size-3"><strong><?php __('front_cart_slots'); ?></strong></div>
					</li>
					<?php
					foreach ($tpl['cart_arr'] as $cid => $date_arr)
					{ 
						if ($cid != $controller->_get->toInt('cid'))
						{
							continue;
						}
						foreach ($date_arr as $date => $time_arr)
						{
							foreach ($time_arr as $time => $q)
							{
								list($start_ts, $end_ts) = explode("|", $time);
								$sd = date("Y-m-d", $start_ts);
								$_date = $date == $sd ? $date : $sd;
								?>
								<li>
									<div class="col-size-3"><span><?php echo date($tpl['option_arr']['o_date_format'], strtotime($_date)); ?></span></div><!-- /.col-size-1 -->
									<div class="col-size-3"><span><?php echo date($tpl['option_arr']['o_time_format'], $start_ts); ?></span></div><!-- /.col-size-1 -->
									<div class="col-size-3"><span><?php echo date($tpl['option_arr']['o_time_format'], $end_ts); ?></span></div><!-- /.col-size-1 -->
									<div class="col-size-3">
										<?php
										if (!$hidePrices)
										{
											?>
											<strong><?php echo pjCurrency::formatPrice(@$tpl['cart_price_arr'][$cid][$time]); ?></strong>
											<?php
										} 
										?> 
									</div><!-- /.col-size-1 -->
									<div class="col-size-3"><?php echo $_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][$time]; ?></div>
								</li>
								<?php
							}
						}
					} 
					?>
				</ul><!-- /.tooltip-view-table -->
			</div><!-- /.panel-body -->
			<?php
		}
	}
	?>
</div><!-- /.panel -->
<?php
if ((int) $tpl['option_arr']['o_disable_payments'] === 0 && (int) $tpl['option_arr']['o_hide_prices'] === 0)
{
	?>	
	<div id="pjTsBookingSummaryBox">
    	<div class="panel panel-primary">
    		<div class="panel-heading">
    			<?php __('front_summary'); ?>
    		</div><!-- /.panel-heading -->
    	
    		<div class="panel-body">
    			<div class="pj-calendar-booking-summary">
    				<ul>
    					<?php
    					if($tpl['amount']['discount'] > 0)
    					{
        					?>
        					<li>
        						<span><?php __('front_summary_price'); ?></span>
        	
        						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['original_price']); ?></strong>
        					</li>
        					<li>
        						<span><?php __('front_summary_discount'); ?></span>
        	
        						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['discount']); ?></strong>
        					</li>
        					<?php
    					}
        				?>
    					<li>
    						<span><?php __('front_summary_subtotal'); ?></span>
    	
    						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['price']); ?></strong>
    					</li>
    					<li>
    						<span><?php __('front_summary_tax'); ?> (<?php echo $tpl['option_arr']['o_tax']?>%)</span>
    	
    						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['tax']); ?></strong>
    					</li>
    	
    					<li>
    						<span><?php __('front_summary_total'); ?></span>
    	
    						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['total']); ?></strong>
    					</li>
    	
    					<li>
    						<span><?php __('front_summary_deposit'); ?><?php echo $tpl['option_arr']['o_deposit_type'] == 'percent' ? ' ('.$tpl['option_arr']['o_deposit'].'%)' : NULL;?></span>
    	
    						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['deposit']); ?></strong>
    					</li>
    				</ul>
    			</div><!-- /.pj-calendar-booking-summary -->
    		</div><!-- /.panel-body -->
    	</div><!-- /.panel -->
	</div>
	<?php
} 
?>