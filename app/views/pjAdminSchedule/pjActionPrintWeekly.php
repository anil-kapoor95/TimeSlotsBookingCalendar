<?php
$months = __('months', true);

reset($tpl['dates_arr']);
$first_iso_date = key($tpl['dates_arr']);
end($tpl['dates_arr']);
$last_iso_date = key($tpl['dates_arr']); 

$first_date_ts = strtotime($first_iso_date);
$last_date_ts = strtotime($last_iso_date);
$range_text = $months[date('n', $first_date_ts)] . ' ' . date('j', $first_date_ts);
$range_text .= ' - ' . $months[date('n', $last_date_ts)] . ' ' . date('j', $last_date_ts);
?>
<h2 style="text-align: center;"><?php echo $range_text;?></h2>
<table>
	<thead>
        <tr>
        	<?php
        	if($tpl['option_arr']['o_show_week_numbers'] == 1)
        	{
            	?>
            	<th>#</th>
               	<?php
        	}
			$days = __('days', true);
			foreach($tpl['dates_arr'] as $iso_date => $item)
			{
			    ?><th><strong><?php echo $days[date('w', strtotime($iso_date))];?></strong></th><?php
        	}
            ?>
        </tr>
    </thead>
    <tbody>
        <tr>
        	<?php
        	if($tpl['option_arr']['o_show_week_numbers'] == 1)
        	{
            	?>
            	<td><?php echo date('W', $first_date_ts);?></td>
    	        <?php
        	}
        	foreach($tpl['dates_arr'] as $iso_date => $item)
        	{
        	    include dirname(__FILE__) . '/elements/print_slots.php';
        	}
           	?>
        </tr>
    </tbody>
</table>