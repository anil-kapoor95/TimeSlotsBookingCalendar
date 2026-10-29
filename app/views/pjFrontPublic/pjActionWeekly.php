<?php include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';?>
<div class="pj-calendar">
	<?php 
	include PJ_VIEWS_PATH . 'pjFrontPublic/elements/header.php';
	
	$cid = $controller->_get->toInt('cid');
	
	$show_period = false;
	if((strpos($tpl['option_arr']['o_time_format'], 'a') > -1 || strpos($tpl['option_arr']['o_time_format'], 'A') > -1))
	{
	    $show_period = true;
	}
	
	$start_ts = 0;
	$end_ts = 0;
	$slot_length = 0;
	$first_iso_date = null;
	
	$slots = 0;
	$lunch_from_ts = 0;
	$lunch_to_ts = 0;
	
	$week_arr = pjUtil::getWeekRange($controller->_get->toString('date'), $tpl['option_arr']['o_week_start']);
	
	$months = __('months', true);
	$suffix = __('day_suffix', true);
	list($from_year, $from_month, $from_day) = explode("-", $week_arr[0]);
	list($to_year, $to_month, $to_day) = explode("-", date("Y-n-j", mktime(0,0,0, $from_month, $from_day+6, $from_year)));
	
	$custom_arr = array();
	$check_slot_arr = array();
	
	$all_days_off = true;
	
	$lunch_from_iso = null;
	$lunch_to_iso = null;
	
	# Check slot length
	$isSameLunchBreak = true;
	$isSlotLengthEqual = true;
	$tmp_length = 0;
	$i = 0;
	
	$is_same_lunch_break_arr = array();
	foreach ($tpl['t_arr'] as $iso_date => $data)
	{
	    $is_same_lunch_break_arr[$iso_date] = true;
		if ($data['is_dayoff'] == 'T')
		{
			continue;
		}else{
			$all_days_off = false;
		}
		if($data['lunch_start_ts'] == $data['lunch_end_ts'])
		{
			$tpl['t_arr'][$iso_date]['lunch_start_ts'] = strtotime($iso_date . ' 00:00:00');
			$tpl['t_arr'][$iso_date]['lunch_end_ts'] = strtotime($iso_date . ' 00:00:00');
			$tpl['t_arr'][$iso_date]['hasLunchBreak'] = false;
			
			$lunch_start_ts = '00:00';
			$lunch_end_ts = '00:00';
		}else{
			$tpl['t_arr'][$iso_date]['hasLunchBreak'] = true;
			$lunch_start_ts = date('H:i', $data['lunch_start_ts']);
			$lunch_end_ts = date('H:i', $data['lunch_end_ts']);
			
			
		}
		
		$is_same_lunch_break_arr[$iso_date] = date('Y-m-d') . '|' . date("H:i:s", $tpl['t_arr'][$iso_date]['lunch_end_ts']) ."=".date('Y-m-d') . '|' . date("H:i:s", $tpl['t_arr'][$iso_date]['lunch_start_ts']);
		
		if ($i >= 6 && $data['end_ts'] >= $data['start_ts'])
		{
			break;
		}
		
		$check_slot_arr[$iso_date] = $data['slot_length'];
		
		$i += 1;
	}
	
	$count_arr = array_count_values($check_slot_arr);
	$popular_val = array_search(max($count_arr), $count_arr);
	
	$count_break_arr = array_count_values($is_same_lunch_break_arr);
	$popular_break_val = array_search(max($count_break_arr), $count_break_arr);
	
	foreach($check_slot_arr as $iso_date => $val)
	{
		if($val != $popular_val)
		{
			$custom_arr[] = $iso_date;
		}else{
			if($first_iso_date == null)
			{
				$first_iso_date = $iso_date;
			}
		}
	}
	$custom_break_arr = array();
	$first_iso_date_break = null;
	foreach($is_same_lunch_break_arr as $iso_date => $val)
	{
	    if($val != $popular_break_val)
	    {
	        $custom_break_arr[] = $iso_date;
	    }else{
	        if($first_iso_date_break == null)
	        {
	            $first_iso_date_break = $iso_date;
	        }
	    }
	}
	list($year, $month, $day) = explode("-", $controller->_get->toString('date'));
	?>
	<div class="pj-calendar-actions">
		<a href="#" class="btn btn-primary btn-sm pull-left pjTsSelectorWeeklyNav" data-date="<?php echo date("Y/m/d", mktime(0, 0, 0, $month, $day-7, $year)); ?>"><span class="glyphicon glyphicon-chevron-left"></span></a>

		<div class="pj-calendar-ym"><?php printf("%u%s %s - %u%s %s", $from_day, @$suffix[(int) $from_day], @$months[(int) $from_month], $to_day, @$suffix[$to_day], @$months[$to_month]);?></div><!-- /.pj-calendar-ym -->

		<a href="#" class="btn btn-primary btn-sm pull-right pjTsSelectorWeeklyNav" data-date="<?php echo date("Y/m/d", mktime(0, 0, 0, $month, $day+7, $year)); ?>"><span class="glyphicon glyphicon-chevron-right"></span></a>
	</div><!-- /.pj-calendar-actions -->
	
	<?php
	if ($isSameLunchBreak)
	{
		?>
		<div class="pjTsWeeklyViewBody">
			<div class="pjTsWeeklyViewBodyInner">
        		<div class="pj-calendar-head pj-calendar-head2">
        			<div class="pj-calendar-day-header"><p>&nbsp;</p></div><!-- /.pj-calendar-day-header -->
        			<?php
        			$days_short = __('days_short', true);
        			
        			$i = 0;
        			
        			foreach ($tpl['t_arr'] as $iso_date => $data)
        			{
        				if ($i > 6)
        				{
        					break;
        				}
        				$w = date("w", strtotime($iso_date));
        				?><div class="pj-calendar-day-header"><p><?php echo $days_short[$w]; ?></p></div></th><?php
        				$i += 1;
        				if ($data['is_dayoff'] == 'F')
        				{
        					if ($start_ts === 0 )
        					{
        						if(!in_array($iso_date, $custom_arr))
        						{
        							$start_ts = $data['start_ts'];
        							if($data['hasLunchBreak'] == true)
        							{
        								$lunch_from_ts = $data['lunch_start_ts'];
        								$lunch_to_ts = $data['lunch_end_ts'];
        							}
        						}
        					}else{
        						$_start_ts = strtotime($first_iso_date . ' ' . date('H:i:s', $data['start_ts']));
        						if($_start_ts < $start_ts && !in_array($iso_date, $custom_arr))
        						{
        							$start_ts = $_start_ts;
        							if($data['hasLunchBreak'] == true)
        							{
        								$lunch_from_ts = $data['lunch_start_ts'];
        								$lunch_to_ts = $data['lunch_end_ts'];
        							}
        						}
        					}
        					if ($end_ts === 0)
        					{
        						if(!in_array($iso_date, $custom_arr))
        						{
        							if($first_iso_date == date('Y-m-d', $data['end_ts']) && date('H:i:s', $data['end_ts']) == '00:00:00')
        							{
        								$end_ts = $data['end_ts'] + 86400;
        							}else{
        								$end_ts = $data['end_ts'];
        							}
        						}
        					}else{
        						if(date('H:i:s', $data['end_ts']) == '00:00:00')
        						{
        							$_end_ts = strtotime($first_iso_date . ' ' . date('H:i:s', $data['end_ts'])) + 86400;
        						}else{
        							$_end_ts = strtotime($first_iso_date . ' ' . date('H:i:s', $data['end_ts']));
        						}
        						
        						if($_end_ts > $end_ts && !in_array($iso_date, $custom_arr))
        						{
        							$end_ts = $_end_ts;
        						}
        					}
        					$slot_length = $data['slot_length'];
        					$slots = $data['slots'];
        				}
        			}
        			?>
        		</div><!-- /.pj-calendar-head -->
        		<?php
        		if($all_days_off == true)
        		{
        			?>
        			<div class="pj-calendar-body">
        				<div class="pj-calendar-column">
        					<div class="pj-calendar-cell<?php echo $show_period == true ? ' pj-cell-ampm' : NULL;?>">&nbsp;</div>
        				</div>
        				<?php
        				$i = 0;
        				foreach ($tpl['t_arr'] as $iso_date => $data)
        				{
        					if ($i > 6)
        					{
        						break;
        					}
        					?>
        					<div class="pj-calendar-column">
        						<div class="pj-calendar-cell<?php echo $show_period == true ? ' pj-cell-ampm' : NULL;?>"><p>--</p></div>
        					</div>
        					<?php
        					$i++;
        				} 
        				?>
        			</div>
        			<?php
        		}else{ 
        			?>
        			<div class="pj-calendar-body">
        				<form>
        					<?php
        					$start_iso = date('Y-m-d', $start_ts);
        					$end_iso = date('Y-m-d', $end_ts);
        					$CART = $controller->cart->getAll();
        					$step = $slot_length * 60;
        					$now = time();
        					if ($start_ts == $end_ts || ($end_iso == $start_iso && date('H:i:s', $end_ts) == '00:00:00'))
        					{
        						$end_ts += 86400;
        					}
        					?>
        					<div class="pj-calendar-column">
        						<?php
        						$i = $start_ts;
        						while($i < $end_ts)
        						{
        							?>
        							<div class="pj-calendar-cell<?php echo $show_period == true ? ' pj-cell-ampm' : NULL;?>"><p class="pjTsWeeklyTime"><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></p></div>
        							<?php
        							if($i == $lunch_from_ts)
        							{
        								$i = $lunch_to_ts;
        							}else{
        								$i = $i + $step;
        							}
        						} 
        						?>
        					</div>
        					<?php
        					foreach ($tpl['t_arr'] as $iso_date => $data)
        					{
        					    if(in_array($iso_date, $custom_arr) || in_array($iso_date, $custom_break_arr))
        						{
        							?>
        							<div class="pj-calendar-column">
        								<div class="pj-calendar-rowspan"><?php __('front_custom_wtime');?><br/><br/><a href="#" class="pjTsCalendarDate" data-iso="<?php echo $iso_date;?>" data-custom="1"><?php __('front_view_slots');?></a></div>
        							</div>
        							<?php
        						}else{
        							?>
        							<div class="pj-calendar-column">
        								<?php
        								$i = $start_ts;
        								while($i < $end_ts)
        								{
        									?>
        									<div class="pj-calendar-cell<?php echo $show_period == true ? ' pj-cell-ampm' : NULL;?>">
        										<?php
        										$time = strtotime($iso_date . ' ' . date('H:i:s', $i));
        										if ($data['is_dayoff'] == 'T')
        										{
        											echo '--';
        										}elseif ($data['start_ts'] > $time) {
        											# Too early
        											echo '<p>--</p>';
        										}elseif ($data['end_ts'] <= $time && date('H:i:s', $data['end_ts']) != '00:00:00') {									
        											# Too late
        											echo '<p>--</p>';
        										}elseif ($time < $now) {
        											# Start Time is in past
        											echo '<p>--</p>';
        											$state = 4;
        										}elseif ($time < $now + $tpl['option_arr']['o_hours_before'] * 3600) {
        											# Bookings are not allowed X hours before
        											echo '<p>--</p>';
        											$state = 6;
        										}elseif (isset($data['lunch_start_ts'], $data['lunch_end_ts']) && $time >= $data['lunch_start_ts'] && $time < $data['lunch_end_ts']) {
        											# Lunch break
        											echo '<p>--</p>';
        											$state = 5;
        										}else{
        											$booked = 0;
        											foreach ($tpl['bs_arr'] as $bs)
        											{
        												if ($bs['start_ts'] == $time && $bs['end_ts'] == $time + $step)
        												{
        												    $booked += $bs['cnt'];
        												}
        											}
        											$attr = NULL;
        											if ($booked < $data['slot_limit'])
        											{
        												$checked = NULL;
        												if (isset($CART[$cid][$iso_date][$time . "|" . ($time + $step)]))
        												{
        													# In basket
        													$state = 1;
        													$class = "pjTsWeeklyIconSelected pjTsSelectorRemoveFromCart tsSelectorRemoveTimeslot";
        												} else {
        													# Available
        													$state = 2;
        													$class = "pjTsWeeklyIconAvailable pjTsSelectorAddToCart";
        												}
        												$attr = ' data-date="'.$iso_date.'" data-start_ts="'.$time.'" data-end_ts="'.($time + $step).'"';
        												
        												$availableSlotsNumber = $data['slot_limit'] - $booked;
        												if($data['people'] < $availableSlotsNumber)
        												{
        												    $availableSlotsNumber = $data['people'];
        												}
        												if ($availableSlotsNumber > 1)
        												{
        												    ?>
                        									<select name="cnt" class="form-control weekly-slot-select"<?php echo $attr; ?>>
                        										<option value="">----</option>
                        										<?php foreach (range(1, $availableSlotsNumber) as $num): ?>
                        											<option value="<?php echo $num; ?>" <?php echo (isset($_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][sprintf('%s|%s', $time, $time + $step)]) && $_SESSION[$controller->defaultSlots][$controller->_get->toInt('cid')][sprintf('%s|%s', $time, $time + $step)] == $num) ? 'selected="selected"' : ''; ?>><?php echo $num; ?></option>
                        										<?php endforeach; ?>
                        									</select>
                        									<label class="<?php echo $class; ?>"<?php echo $attr; ?> style="display:none;"><span class="custom-checkbox"></span></label>
                        									<?php
        												}else{
            												?>
            												<label class="<?php echo $class; ?>"<?php echo $attr; ?>><span class="custom-checkbox"></span></label>
            												<?php
        												}
        											} else {
        												# Fully booked
        												$state = 3;
        												?><div class="booked-btn"></div><?php
        											}
        										}
        										
        										?>
        									</div>
        									<?php
        									if($i == $lunch_from_ts)
        									{
        										$i = $lunch_to_ts;
        									}else{
        										$i = $i + $step;
        									}
        								}
        								?>
        							</div>
        							<?php
        						}
        					}
        					?>
        				</form>
        			</div><!-- /.pj-calendar-body -->
        			<?php
        		} 
        		?>
        	</div>
		</div>
		<div class="pj-calendar-footer">
			<?php
			$slots = 0;
			if(isset($tpl['calendar_cart'][$cid]))
			{
			    foreach($tpl['calendar_cart'][$cid] as $date => $items)
				{
					$slots += count($items);
				}
			}
			if ($slots > 0)
			{
				?><p class="pull-left"><?php $slots != 1 ? printf(__('front_slots_selected', true, true), $slots) : printf(__('front_slot_selected', true, true), $slots); ?></p><!-- /.pull-left --><?php
			} 
			?>
			
			<a href="#" class="pull-right btn btn-primary pjTsSelectorCart"><?php __('front_goto_cart');?> <span class="glyphicon glyphicon-chevron-right"></span></a>
		</div><!-- /.pj-calendar-footer -->
		<?php
	}else{
		?>
		<div class="pj-calendar-actions">
			<label class="text-muted"><?php __('front_weekly_length'); ?></label>
		</div>
		<?php
	}
	?>
</div>

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