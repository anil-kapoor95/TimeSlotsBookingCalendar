<?php
$date_ts = strtotime(date('Y-m-d'));
if($controller->_get->check('date'))
{
    $date_ts = strtotime($controller->_get->toString('date'));
}
$months = __('months', true);
$days = __('days', true);
$week_start = $tpl['option_arr']['o_week_start'];
$row_arr = array_chunk($tpl['dates_arr'], 7, true);
?>
<h2 style="text-align: center;"><?php echo $months[date('n', $date_ts)];?>, <?php echo date('Y', $date_ts);?></h2>
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
            ?>
            <th><strong><?php echo $days[$week_start%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 1)%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 2)%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 3)%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 4)%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 5)%7];?></strong></th>
            <th><strong><?php echo $days[($week_start + 6)%7];?></strong></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($row_arr as $k => $row)
        {            
            reset($row);
            $first_iso_date = key($row); 
            ?>
            <tr>
            	<?php
            	if($tpl['option_arr']['o_show_week_numbers'] == 1)
            	{
            	    ?>
                	<td><?php echo date('W', strtotime($first_iso_date));?></td>
                	<?php
            	}
            	foreach($row as $iso_date => $item)
            	{
            	    include dirname(__FILE__) . '/elements/print_slots.php';
            	}
            	?>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>