<?php include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
$tsb_step = 0;
include PJ_VIEWS_PATH . 'pjFrontPublic/elements/steps.php';
?>
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
						$total += @$tpl['cart_price_arr'][$cid][$time] * $_SESSION[$controller->defaultSlots][$cid][$time];
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
							<div class="col-size-3"><strong><?php __('front_cart_price');?></strong></div><!-- /.col-size-3 -->
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
									<div class="col-size-3">
										<span><?php echo $_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][$time]; ?></span>
										<button type="button" class="delete-btn pull-right pjTsSelectorRemoveFromCart" data-start_ts="<?php echo $start_ts; ?>" data-end_ts="<?php echo $end_ts; ?>" data-date="<?php echo $date; ?>"></button>
									</div>
								</li>
								<?php
							}
						}
					} 
					?>
				</ul><!-- /.tooltip-view-table -->
				<?php
				if (!$hidePrices)
				{
					?>
					<div class="pj-calendar-booking-summary">
						<ul>
							<li>
								<span><?php __('front_cart_total'); ?></span>
			
								<strong><?php echo pjCurrency::formatPrice($total); ?></strong>
							</li>
						</ul>
					</div><!-- /.pj-calendar-booking-summary -->
					<?php
				} 
				?>
			</div><!-- /.panel-body -->
		
			<div class="panel-footer">
				<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back_calendar', false, true); ?></a>
		
				<a href="#" class="btn btn-primary pull-right pjTsSelectorCheckout"><?php __('front_button_proceed', false, true); ?> <span class="glyphicon glyphicon-chevron-right"></span></a>
			</div><!-- /.panel-footer -->
			<?php
		}else{
			?>
			<div class="panel-body"><?php __('front_cart_empty');?></div>
			<div class="panel-footer">
				<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back_calendar', false, true); ?></a>
			</div><!-- /.panel-footer -->
			<?php
		}
	}else{
		?>
		<div class="panel-body"><?php __('front_cart_empty');?></div>
		<div class="panel-footer">
			<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back_calendar', false, true); ?></a>
		</div><!-- /.panel-footer -->
		<?php
	} 
	?>
</div><!-- /.panel -->