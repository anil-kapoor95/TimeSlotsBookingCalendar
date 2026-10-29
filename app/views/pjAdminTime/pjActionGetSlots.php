<?php
$post = $controller->_post->raw();
$required = array('slot_length', 'date', 'start', 'end');
foreach ($required as $index)
{
	if (!array_key_exists($index, $post))
	{
		?>
		<div class="hr-line-dashed"></div>
		<div class="alert alert-warning">
			<i class="fa fa-exclamation-triangle m-r-xs"></i>
			<strong><?php __('infoMissingParamsTitle'); ?></strong>
			<?php __('infoMissingParamsDesc'); ?>
		</div>
		<?php
		exit;
	}
}

$date = pjDateTime::formatDate($post['date'], $tpl['option_arr']['o_date_format']);
$step = $post['slot_length'] * 60;

$slots = $post['slots'];
$i = strtotime($date . ' ' . $post['start']);
$lunch_from_ts = strtotime($date . ' ' . $post['lunch_from']);
$lunch_length = $post['lunch_length'] != '' ? intval($post['lunch_length']) : 0;
if($lunch_from_ts < $i)
{
	$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
}
$lunch_to = $lunch_from_ts + ($lunch_length * 60);
?>
<div class="hr-line-dashed"></div>
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
        <table class="table table-bordered">
        	<thead>
        		<tr>
        			<th><?php __('time_from'); ?></th>
        			<th><?php __('time_to'); ?></th>
        			<th><?php __('time_price'); ?></th>
        		</tr>
        	</thead>
        	<tbody>
        	<?php
        	for($n = 1; $n <= $slots; $n++)
        	{
        		?>
        		<tr>
        			<td><?php echo date($tpl['option_arr']['o_time_format'], $i); ?></td>
        			<td><?php echo date($tpl['option_arr']['o_time_format'], $i + $step); ?></td>
        			<td>
        				<div class="input-group">
                            <input type="text" name="price[<?php echo $i; ?>|<?php echo $i + $step; ?>]" id="price_<?php echo $i; ?>" value="<?php echo isset($tpl['price_arr'][$i . '|' . ($i + $step)]) ? $tpl['price_arr'][$i . '|' . ($i + $step)] : NULL;?>" class="form-control tsPriceField"/>
        
                            <span class="input-group-addon"><?php echo pjCurrency::getCurrencySign($tpl['option_arr']['o_currency']);?></span>
                        </div>
        			</td>
        		</tr>
        		<?php
        		if($i + $step == $lunch_from_ts)
        		{
        		    if($lunch_length > 0)
        		    {
            			?>
            			<tr>
            				<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_from_ts); ?></td>
            				<td><?php echo date($tpl['option_arr']['o_time_format'], $lunch_to); ?></td>
            				<td>
            					<?php __('time_tbl_break');?>
            				</td>
            			</tr>
            			<?php
        		    }
        			$i = $lunch_to;
        		}else{
        			$i = $i + $step;
        		}
        	}
        	?>
        	</tbody>
        </table>
	</div>
</div>