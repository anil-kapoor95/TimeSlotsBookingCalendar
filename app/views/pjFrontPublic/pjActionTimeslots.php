<?php include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';?>
<div class="pj-calendar">
	<?php 
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/header.php';
	
	$cid = $controller->_get->toInt('cid');
	?>
	
	<div class="pj-calendar-tooltip-view">
		<div class="pj-calendar-tooltip-view-head">
			<div class="pj-calendar-d"><?php echo pjDateTime::formatDate($controller->_get->toString('date'), 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></div><!-- /.pj-calendar-ym -->
		</div><!-- /.pj-calendar-tooltip-view-head -->
		
		<?php
		$hasAvailableSlots = false;
		if (!isset($tpl['dayoff']))
		{ 
			$hidePrices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
			$step = $tpl['t_arr']['slot_length'] * 60;
			# Fix for 24h support
			$offset = $tpl['t_arr']['end_ts'] <= $tpl['t_arr']['start_ts'] ? 86400 : 0;
			$now = time();
			$total = 0;
			if (!$hidePrices)
			{
				for ($i = $tpl['t_arr']['start_ts']; $i < $tpl['t_arr']['end_ts'] + $offset; $i += $step)
				{
					if ((float) @$tpl['price_arr'][$i . "|" . ($i + $step)] > 0)
					{
						$total += @$tpl['price_arr'][$i . "|" . ($i + $step)];
					}
				}
			}
				
			$CART = $controller->cart->getAll();
			
			$show_slot_column = true;
			$slot_limit = $tpl['t_arr']['slot_limit'];
			if($tpl['t_arr']['people'] < $slot_limit)
			{
			    $slot_limit = $tpl['t_arr']['people'];
			}
			if($slot_limit == 1)
			{
			    $show_slot_column = false;
			}
			?>
			<div class="pj-calendar-view-body">
				<ul class="tooltip-view-table">
					
					<li>
						<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><strong><?php __('front_cart_start_time'); ?></strong></div><!-- /.col-size-1 -->
						<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><strong><?php __('front_cart_end_time'); ?></strong></div><!-- /.col-size-1 -->
						<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-2';?>"><strong><?php __('front_availability'); ?></strong></div><!-- /.col-size-1 -->
						<?php 
						if (!$hidePrices)
						{ 
							?><div class="col-size-3"><strong><?php echo __('front_cart_price', true); ?></strong></div><?php
						}else{
							?><div class="col-size-3">&nbsp;</div><?php
						} 
						?>
						<div class="col-size-3" style="display:<?php echo $show_slot_column == true ? '': 'none';?>;"><strong><?php __('front_cart_slots'); ?></strong></div>
					</li>
					<?php
					$slots = $tpl['t_arr']['slots'];
					$i = $tpl['t_arr']['start_ts'];
					$lunch_from_ts = $tpl['t_arr']['lunch_start_ts'];
					$lunch_to_ts = $tpl['t_arr']['lunch_end_ts'];
					if($lunch_from_ts < $i)
					{
						$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
						$lunch_to_ts = $lunch_to_ts + (24 * 60 * 60);
					}

					for($n = 1; $n <= $slots; $n++)
					{
						$booked = 0;
						foreach ($tpl['bs_arr'] as $bs)
						{
							if ($bs['start_ts'] == $i && $bs['end_ts'] == $i + $step)
							{
							    $booked += $bs['cnt'];
							}
						}
						$attr = NULL;
						$tooltip_class = 'tooltip-past';
						if ($i < $now)
						{
							# Start Time is in past
							$state = 4;
							$class = "past-item";
							$tooltip_class = 'tooltip-past';
						} elseif ($i < $now + $tpl['option_arr']['o_hours_before'] * 3600) {
							# Bookings are not allowed X hours before
							$state = 6;
							$class = "past-item";
							$tooltip_class = 'tooltip-past';
						} else {
							if ($booked < $tpl['t_arr']['slot_limit'])
							{
								$checked = NULL;
								if (isset($CART[$cid][$controller->_get->toString('date')][$i . "|" . ($i + $step)]))
								{
									# In basket
									$state = 1;
									$class = "selected-item pjTsSelectorRemoveFromCart tsSelectorRemoveTimeslot";
									$tooltip_class = 'tooltip-available';
								} else {
									# Available
									$state = 2;
									$class = "pjTsSelectorAddToCart";
									$tooltip_class = 'tooltip-available';
								}
								$attr = ' data-date="'.$controller->_get->toString('date').'" data-start_ts="'.$i.'" data-end_ts="'.($i + $step).'"';
							} else {
								# Fully booked
								$state = 3;
								$class = "booked-item";
								$tooltip_class = 'tooltip-booked';
							}
						}
						$availableSlotsNumber = $tpl['t_arr']['slot_limit'] - $booked;
						if($tpl['t_arr']['people'] < $availableSlotsNumber)
						{
						    $availableSlotsNumber = $tpl['t_arr']['people'];
						}
						?>
						<li class="<?php echo $class?>"<?php echo $attr;?>>
							<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><span><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></span></div><!-- /.col-size-1 -->
							<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><span><?php echo date($tpl['option_arr']['o_time_format'], $i + $step); ?></span></div><!-- /.col-size-1 -->
							<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-2';?>">
								<span class="<?php echo $tooltip_class;?>">
									<?php
									switch ($state)
									{
										case 1:
											# In basket
											__('front_selected');
											break;
										case 2:
											# Available
											__('front_available');
											break;
										case 3:
											# Fully booked
											__('front_booked');
											$availableSlotsNumber = 0;
											break;
										case 4:
											# Past
											__('front_cart_passed');
											$availableSlotsNumber = 0;
											break;
										case 5:
											# Lunch break
											__('front_cart_lunch');
											$availableSlotsNumber = 0;
											break;
										case 6:
											# Bookings are not allowed X hours before
											__('front_cart_before');
											$availableSlotsNumber = 0;
											break;
									} 
									?>
								</span>
							</div><!-- /.col-size-2 -->
							<div class="col-size-3">
							<?php
							if (!$hidePrices)
							{
								if (!in_array($state, array(3,4,5,6)))
								{
									if (isset($tpl['price_arr'][$i . "|" . ($i + $step)]))
									{
									    echo pjCurrency::formatPrice(@$tpl['price_arr'][$i . "|" . ($i + $step)]);
									} else {
										echo pjCurrency::formatPrice(0.00);
									}
								} 
							}
							switch ($state)
							{
								case 1:
									# In basket
									?><label class="pjTsIconRemove"><span class="custom-checkbox"></span></label><?php
									break;
								case 2:
									# Available
									?><label class="pjTsIconAdd"><span class="custom-checkbox"></span></label><?php
									break;
								case 3:
									# Fully booked
									?>&nbsp;<?php
									break;
							} 
							?>
							</div><!-- /.col-size-3 -->
							<div class="col-size-3" style="display:<?php echo $show_slot_column == true ? '': 'none';?>;">
								<?php 
								if ($availableSlotsNumber >= 1)
								{
								    $hasAvailableSlots = true;
								    ?>
									<select name="cnt" class="form-control slot-select">
										<option value="">-- <?php __('lblChoose'); ?> --</option>
										<?php foreach (range(1, $availableSlotsNumber) as $num): ?>
											<option value="<?php echo $num; ?>" <?php echo (isset($_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][sprintf('%s|%s', $i, $i + $step)]) && $_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][sprintf('%s|%s', $i, $i + $step)] == $num) ? 'selected="selected"' : ''; ?>><?php echo $num; ?></option>
										<?php endforeach; ?>
									</select>
									<?php
								}
								?>
							</div>
						</li>
						<?php
						if($i + $step == $lunch_from_ts)
						{
							if($lunch_from_ts < $lunch_to_ts)
							{
								?>
								<li class="past-item">
									<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><span><?php echo date($tpl['option_arr']['o_time_format'], $lunch_from_ts); ?></span></div>
									<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-1';?>"><span><?php echo date($tpl['option_arr']['o_time_format'], $lunch_to_ts); ?></span></div>
									<div class="<?php echo $show_slot_column == true ? 'col-size-3' : 'col-size-2';?>"><span class="tooltip-past"><?php __('front_cart_lunch');?></span></div>
									<div class="col-size-3"><span>&nbsp;</span></div>
								</li>
								<?php
							}
							$i = $lunch_to_ts;
						}else{
							$i = $i + $step;
						}
					}
					?>
					
				</ul><!-- /.tooltip-view-table -->
			</div><!-- /.pj-calendar-view-body -->
			<?php
		}else{
			?>
			<div class="pj-calendar-view-body">
				<ul class="tooltip-view-table">
					<li>
						<div class="col-size-4"><?php __('front_cart_dayoff'); ?></div>
					</li>
				</ul>
			</ul>
			<?php
		}
		?>
		<div class="pj-calendar-footer active">
			<?php
			$slots = 0;			
			if(isset($tpl['calendar_cart'][$cid]))
			{
				foreach($tpl['calendar_cart'][$cid] as $date => $items)
				{
				    foreach ($items as $key => $cnt) 
				    {
				        if (isset($_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][$key])) 
				        {
				            $slots += $_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][$key];
				        } else {
				            $slots += 1;
				        }
				    }
				}
			}
			if ($slots > 0)
			{
				?><p><?php $slots != 1 ? printf(__('front_slots_selected', true, true), $slots) : printf(__('front_slot_selected', true, true), $slots); ?></p><!-- /.pull-left --><?php
			} 
			?>
			
			<a href="#" class="pull-left btn btn-primary btn-back pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back', false, true); ?></a>
			<?php
			if (!isset($tpl['dayoff']) && $hasAvailableSlots)
			{ 
				?>
				<a href="#" class="pull-right btn btn-primary pjTsSelectorCart"><?php __('front_goto_cart');?> <span class="glyphicon glyphicon-chevron-right"></span></a>
				<?php
			} 
			?>
			</div>
			
		</div><!-- /.pj-calendar-footer -->
	</div><!-- /.pj-calendar-tooltip -->
</div><!-- /.pj-calendar -->

<div class="modal fade pjTbModal" id="pjTsMaxSlotsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
		    <div class="modal-body text-danger">
		    	
		    </div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default pjTbBtn pjTbBtnPrimary" data-dismiss="modal"><?php __('front_btn_close');?></button>
	      	</div>
    	</div>
  	</div>
</div>
