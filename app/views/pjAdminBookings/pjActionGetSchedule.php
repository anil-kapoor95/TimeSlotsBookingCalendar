<?php
if (!isset($tpl['t_arr'], $tpl['arr']))
{
	exit;
}
?>
<div class="schedule_nav" style="display: <?php echo $_GET['action'] == 'pjActionSchedulePrint' ? 'none' : NULL; ?>">
	<span class="pj-form-field-custom pj-form-field-custom-after float_left">
		<input type="text" name="schedule_date" class="pj-form-field w80 datepick pointer required" readonly="readonly" rel="<?php echo $tpl['week_start']; ?>" rev="<?php echo $tpl['jqDateFormat']; ?>" value="<?php echo pjUtil::formatDate($_GET['date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>" />
		<span class="pj-form-field-after"><abbr class="pj-form-field-icon-date"></abbr></span>
	</span>
	&nbsp;
	<select class="pj-form-field w200" id="filter_calendar_id" name="calendar_id" data-iso="<?php echo $_GET['date']; ?>">
		<option value="">-- <?php __('lblAllCalendars')?> --</option>
		<?php
		foreach ($tpl['calendars'] as $calendar)
		{
			?><option value="<?php echo $calendar['id']; ?>"<?php echo $calendar['id'] == $_GET['calendar_id'] ? ' selected="selected"' : NULL;?>><?php echo pjSanitize::html($calendar['title']); ?></option><?php
		}
		?>
	</select>
	<span class="float_right t10">
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionSchedulePrint&amp;date=<?php echo $_GET['date']; ?>&amp;calendar_id=<?php echo $_GET['calendar_id']; ?>" target="_blank"><?php __('booking_schedule_print'); ?></a> |
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionSchedule" data-iso="<?php echo date("Y-m-d", strtotime("-5 days", strtotime($_GET['date']))); ?>" class="schedule_get"><?php __('booking_schedule_prev'); ?></a> |
		<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionSchedule" data-iso="<?php echo date("Y-m-d", strtotime("+5 days", strtotime($_GET['date']))); ?>" class="schedule_get"><?php __('booking_schedule_next'); ?></a>
	</span>
	<br class="clear_both" />
</div>

<table cellpadding="0" cellspacing="0" class="pj-table" style="width: 100%">
	<thead>
		<tr>
			<th class="schedule_time">&nbsp;</th>
			<?php
			$days = __('days', true);
			foreach ($tpl['arr'] as $date => $bookings)
			{
				$time = strtotime($date);
				$w = date("w", $time);
				$weekday = @$days[$w];
				if ($tpl['t_arr'][$date]['is_dayoff'] == 'T')
				{
					$weekday = sprintf("%s (%s)", $weekday, __('front_cart_dayoff', true));
				}
				?><th class="schedule_date"><?php echo date($tpl['option_arr']['o_date_format'], $time); ?><br /><?php echo $weekday; ?></th><?php
			}
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	for ($i = $tpl['start_time']; $i <= $tpl['end_time']; $i += 3600)
	{
		list($y, $m, $d, $hh, $mm) = explode("-", date("Y-n-j-H-i", $i));
		?>
		<tr>
			<td><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></td>
			<?php
			foreach ($tpl['arr'] as $date => $bookings)
			{
				$class = "available";
				$add_slot = true;
				$isDayOff = false;
				$break = false;
				if ($tpl['t_arr'][$date]['is_dayoff'] == 'T')
				{
					$isDayOff = true;
					$class = "schedule_dayoff";
				}
				list($y, $m, $d) = explode("-", $date);
				
				$time_from = mktime($hh, $mm, 0, $m, $d, $y);
				$time_to = mktime($hh + 1, $mm, 0, $m, $d, $y);
				
				if (!$isDayOff)	
				{
					$slot_length_in_seconds = $tpl['t_arr'][$date]['slot_length'] * 60;
					$start_of_last_slot = $tpl['t_arr'][$date]['end_ts'] - $slot_length_in_seconds;
					
					if ($start_of_last_slot >= $time_to && $i == $tpl['end_time'])
					{
						$class = "schedule_na";
					}
				}
				
				if(!$isDayOff && $i > $tpl['start_time'])
				{
					if( ($time_from >= $tpl['t_arr'][$date]['lunch_start_ts'] && $time_from < $tpl['t_arr'][$date]['lunch_end_ts']) || 
						($time_to > $tpl['t_arr'][$date]['lunch_start_ts'] && $time_to <= $tpl['t_arr'][$date]['lunch_end_ts']))
					{
						$class = "schedule_na";
						$break = true;
					}
				}
				?><td class="<?php echo $class; ?>"><?php 
				foreach ($bookings as $booking)
				{
					if ($time_from < $booking['end_ts'] && $time_to > $booking['start_ts'])
					{
						?><div class="b5"><?php
						if ($_GET['action'] != 'pjActionSchedulePrint')
						{
							echo pjSanitize::html($booking['customer_name']); ?><br /><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionUpdate&amp;id=<?php echo $booking['booking_id']; ?>"><?php printf("%s - %s", date($tpl['option_arr']['o_time_format'], $booking['start_ts']), date($tpl['option_arr']['o_time_format'], $booking['end_ts'])); ?></a><br/><?php echo $booking['calendar_title']?><?php
						} else {
							printf("%s<br>%s - %s<br>%s", $booking['customer_name'], date($tpl['option_arr']['o_time_format'], $booking['start_ts']), date($tpl['option_arr']['o_time_format'], $booking['end_ts']), $booking['calendar_title']);
						}
						?></div><?php
						$add_slot = false;
					}
				}
				if($break == true)
				{
					__('time_tbl_break');
				}
				if($class == "available" && $add_slot == true && $_GET['action'] != 'pjActionSchedulePrint')
				{
					?><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionCreate&date=<?php echo date($tpl['option_arr']['o_date_format'], strtotime($date)); ?>&amp;start_ts=<?php echo $time_from?>&amp;end_ts=<?php echo $time_to;?>" class="pj-table-icon-add"></a><?php
				}
				?></td><?php
			}
			?>
		</tr>
		<?php
	}
	?>
	</tbody>
</table>