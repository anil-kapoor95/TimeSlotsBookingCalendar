<?php
$days = __('days', true);
$w_days = array(
	'monday' => $days[1],
	'tuesday' => $days[2],
	'wednesday' => $days[3],
	'thursday' => $days[4],
	'friday' => $days[5],
	'saturday' => $days[6],
	'sunday' => $days[0]
);
$wday = $controller->_get->toString('day');
$foreign_id = $controller->getCalendarId();
if($controller->_get->toInt('foreign_id') > 0)
{
    $foreign_id = $controller->_get->toInt('foreign_id');
}
?>
<form action="" method="post" class="form pj-form">
	<input type="hidden" name="day" value="<?php echo $wday; ?>" />
	<input type="hidden" name="calendar_id" value="<?php echo $foreign_id; ?>" />
	<div class="table-responsive table-responsive-secondary">
    	<table class="table table-striped table-hover">
    		<thead>
    			<tr>
    				<th colspan="3"><?php echo @$w_days[$wday];?></th>
    			</tr>
    			<tr>
    				<th><?php echo __('time_from'); ?></th>
    				<th><?php echo __('time_to'); ?></th>
    				<th><?php echo __('time_price'); ?></th>
    			</tr>
    		</thead>
    		<tbody>
    		<?php
    		#$step = $tpl['option_arr']['slot_length'] * 60;
    		$step = $tpl['wt_arr'][$wday . '_length'] * 60;
    		$start_ts = strtotime($tpl['wt_arr'][$wday . '_from']);
    		$end_ts = strtotime($tpl['wt_arr'][$wday . '_to']);
    		
    		$slots = $tpl['wt_arr'][$wday.'_slots'];
    		
    		$i = strtotime($tpl['wt_arr'][$wday . '_from']);
    		$lunch_from_ts = strtotime($tpl['wt_arr'][$wday.'_lunch_from']);
    		$lunch_to_ts = strtotime($tpl['wt_arr'][$wday.'_lunch_to']);
    		if($lunch_from_ts < $i)
    		{
    			$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
    			$lunch_to_ts = $lunch_to_ts + (24 * 60 * 60);
    		}
    		
    		for($n = 1; $n <= $slots; $n++)
    		{
    			$price = NULL;
    			foreach ($tpl['price_day_arr'] as $k => $v)
    			{
    				if ($v['start_time'] == date("H:i:s", $i) && $v['end_time'] == date("H:i:s", $i + $step))
    				{
    					$price = $v['price'];
    					break;
    				}
    			}
    			?>
    			<tr>
    				<td><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></td>
    				<td><?php echo date($tpl['option_arr']['o_time_format'], $i + $step); ?></td>
    				<td>
    					<div class="input-group">
                            <input type="text" name="price[<?php echo $i; ?>|<?php echo $i + $step; ?>]" id="price_<?php echo $i; ?>" class="form-control number tsPriceField" value="<?php echo $price; ?>" />

                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
                        </div>
    				 </td>
    			</tr>
    			<?php
    			if($i + $step == $lunch_from_ts)
    			{
    				if($lunch_from_ts < $lunch_to_ts)
    				{
    					?>
    					<tr>
    						<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_from_ts); ?></td>
    						<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_to_ts); ?></td>
    						<td>
    							<?php __('time_tbl_break');?>
    						 </td>
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
</form>