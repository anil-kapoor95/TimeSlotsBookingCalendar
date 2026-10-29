<?php
if (!isset($tpl['dayoff']))
{
	?>
	<div class="service-table">
		<label class="control-label"><?php __('booking_slots'); ?></label>
		<div class="row">
    	<?php
    	$step = $tpl['t_arr']['slot_length'] * 60;
    	$numOfColumns = 2;
    	# Fix for 24h support
    	$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
    	
    	$numOfSlots = $tpl['t_arr']['slots'];
    	$numOfSlotsPerColumn = ceil($numOfSlots / $numOfColumns);
    	$firstHalfEndSlot = $tpl['t_arr']['start_ts'] + ($step * $numOfSlotsPerColumn);
    	$now = time();
    	
    	$cid = $controller->getCalendarId();
    	if($controller->_get->toInt('calendar_id') > 0)
    	{
    	    $cid = $controller->_get->toInt('calendar_id');
    	}
    	$iso_date = pjDateTime::formatDate($controller->_get->toString('date'), $tpl['option_arr']['o_date_format']);
    	#-------------------------------------
    	ob_start();
    	$total = 0;
    	$CART = array();
    	$i = $tpl['t_arr']['start_ts'];
    	$lunch_from_ts = $tpl['t_arr']['lunch_start_ts'];
    	$lunch_to_ts = $tpl['t_arr']['lunch_end_ts'];
    	if($lunch_from_ts < $i)
    	{
    		$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
    		$lunch_to_ts = $lunch_to_ts + (24 * 60 * 60);
    	}
    	$end_ts = $tpl['t_arr']['end_ts'];
    	for($n = 1; $n <= $numOfSlots; $n++)
    	{
    		if($i + $step == $lunch_from_ts)
    		{
    			$i = $lunch_to_ts;
    		}else{
    			$i = $i + $step;
    		}
    		$end_ts = $i;
    	}
    	$i = $tpl['t_arr']['start_ts'];
    	foreach (range(0, $numOfColumns - 1) as $column)
    	{
    		?>
    		<div class="col-md-6 col-sm-6 col-xs-12">
    		<table class="table table-bordered">
    			<thead>
    				<tr>
    					<th><?php __('front_cart_start_time'); ?></th>
    					<th><?php __('front_cart_end_time'); ?></th>
    					{PRICE}
    					<th>&nbsp;</th>
    				</tr>
    			</thead>
    			<tbody>
    			<?php
    			for($n = 1; $n <= $numOfSlots; $n++)
    			{
    				if ($column == 0 && $i >= $firstHalfEndSlot)
    				{
    					break;
    				}
    				if ($column == 1 && $i >= $end_ts)
    				{
    					break;
    				}
    				$booked = 0;
    				foreach ($tpl['bs_arr'] as $bs)
    				{
    					if ($bs['start_ts'] == $i && $bs['end_ts'] == $i + $step)
    					{
    					    $booked += $bs['cnt'];
    					}
    				}
    				if ($i < $now)
    				{
    					# Start Time is in past
    					$state = 4;
    					$class = "pj-table-row-even";
    				} elseif ($i < $now + $tpl['_option_arr']['o_hours_before'] * 3600) {
    					# Bookings are not allowed X hours before
    					$state = 6;
    					$class = "pj-table-row-even";
    				}  else {
    					if ($booked < $tpl['t_arr']['slot_limit'])
    					{
    						$checked = NULL;
    						if (isset($CART[$cid][$iso_date][$i . "|" . ($i + $step)]))
    						{
    							# In basket
    							$state = 1;
    							$class = "";
    						} else {
    							# Available
    							$state = 2;
    							$class = "";
    						}
    					} else {
    						# Fully booked
    						$state = 3;
    						$class = "pj-table-row-even";
    					}
    				}
    				$availableSlotsNumber = $tpl['t_arr']['slot_limit'] - $booked;
    				if($tpl['t_arr']['people'] < $availableSlotsNumber)
    				{
    				    $availableSlotsNumber = $tpl['t_arr']['people'];
    				}
    				?>
    				<tr class="<?php echo $class; ?>">
    					<td><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></td>
    					<td><?php echo date($tpl['option_arr']['o_time_format'], $i + $step); ?></td>
    					<?php
    					$price = (float) @$tpl['price_arr'][$i . "|" . ($i + $step)];
    					if ((int) $tpl['_option_arr']['o_hide_prices'] === 0 && $price > 0)
    					{
    					    ?><td><?php echo pjCurrency::formatPrice($price); ?></td><?php
    						$total += @$tpl['price_arr'][$i . "|" . ($i + $step)];
    					}
    					?>
    					<td class="text-center">
    						<input type="hidden" name="price[<?php echo $i; ?>]" value="<?php echo $price; ?>" />
    					<?php
    					switch ($state)
    					{
    						case 1:
    							# In basket
    							?><input type="checkbox" name="" checked="checked" class="i-checks"/><?php
    							break;
    						case 2:
    							# Available
    							
    						    ?>
    							<select class="form-control" name="timeslot[<?php echo $i; ?>|<?php echo $i + $step; ?>]">
    								<option value="">-- <?php __('lblChoose'); ?> --</option>
    								<?php 
    								foreach (range(1, $availableSlotsNumber) as $slot)
    								{
    								    $selected = '';
    								    if($controller->_post->check('timeslot'))
    								    {
    								        $timeslots = $controller->_post->toArray('timeslot');
    								        foreach($timeslots as $key => $value)
    								        {
    								            if($key == $i . '|' . ($i + $step) && $slot == $value)
    								            {
    								                $selected = ' selected="selected"';
    								            }
    								        }
    								    }
    								    ?>
    									<option value="<?php echo $slot; ?>"<?php echo $selected;?>><?php echo $slot; ?></option>
    									<?php 
    								}
    								?>
    							</select>
    							<?php
    							break;
    						case 3:
    							# Fully booked
    						    ?><input type="checkbox" checked="checked" disabled="disabled" /><?php
    							break;
    						case 4:
    							# Past
    							__('front_cart_passed');
    							break;
    						case 5:
    							# Lunch break
    							__('front_cart_lunch');
    							break;
    						case 6:
    							# Not allowed X hours before
    							__('front_cart_before');
    							break;
    					}
    					?>
    					</td>
    				</tr>
    				<?php
    				if($i + $step == $lunch_from_ts)
    				{
    					if($lunch_from_ts < $lunch_to_ts)
    					{
    						?>
    						<tr class="pj-table-row-even">
    							<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_from_ts); ?></td>
    							<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_to_ts); ?></td>
    							<?php
    							if ((int) $tpl['_option_arr']['o_hide_prices'] === 0 && $price > 0)
    							{
    								?><td class="text-center">&nbsp;</td><?php
    							} 
    							?>
    							<td class="align_center"><?php __('front_cart_lunch');?></td>
    						</tr>
    						<?php
    					}
    					$i = $lunch_to_ts;
    				}else{
    					$i = $i + $step;
    				}
    			}
    			?>
    			</tbody>
    		</table>
    		</div>
    		<?php
    	}
    	$content = ob_get_contents();
    	ob_end_clean();
    	$replacement = (int) $tpl['_option_arr']['o_hide_prices'] === 0 && $total > 0 ? '<th>' . __('front_cart_price', true) . '</th>' : NULL;
    	echo str_replace('{PRICE}', $replacement, $content);
    	?>
		</div>
	</div>
	<?php
} else {
    ?>
    <div class="service-table"><p class="alert alert-warning"><?php __('front_cart_dayoff');?></p></div>
    <?php
}
?>