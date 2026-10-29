<?php
if (isset($tpl['bi_arr']) && !empty($tpl['bi_arr']))
{
	?>
	<div class="table-responsive table-responsive-secondary">
    	<table class="table table-striped table-hover">
    		<thead>
    			<tr>
    				<th><?php __('booking_date'); ?></th>
    				<th><?php __('booking_start_time'); ?></th>
    				<th><?php __('booking_end_time'); ?></th>
    				<th><?php __('booking_price'); ?></th>
    				<th><?php __('booking_cnt'); ?></th>
    				<th>&nbsp;</th>
    			</tr>
    		</thead>
    		<tbody>
    		<?php
    		foreach ($tpl['bi_arr'] as $item)
    		{
    			?>
    			<tr>
    				<td><?php echo date($tpl['option_arr']['o_date_format'], strtotime($item['booking_date'])); ?></td>
    				<td><?php echo date($tpl['option_arr']['o_time_format'], strtotime($item['start_time'])); ?></td>
    				<td><?php echo date($tpl['option_arr']['o_time_format'], strtotime($item['end_time'])); ?></td>
    				<td><?php echo pjCurrency::formatPrice($item['price']); ?></td>
    				<td><?php echo $item['cnt']; ?></td>
    				<td>
    					<a href="#" class="btn btn-danger btn-outline btn-sm btn-delete item-delete" data-id="<?php echo @$item['id']; ?>" data-hash="<?php echo $controller->_get->toString('hash'); ?>" data-key="<?php echo $item['booking_date'] . '~' . $item['start_time']; ?>"><i class="fa fa-trash"></i></a>
    					<input type="hidden" name="timeslot[<?php echo $item['start_ts']; ?>|<?php echo $item['end_ts']; ?>]" value="<?php echo $item['cnt'];?>"/>
    				</td>
    			</tr>
    			<?php
    		}
    		?>
    		</tbody>
    	</table>
    </div>
	<?php
} else {
	?><p class="alert alert-danger"><?php __('booking_i_empty'); ?></p><?php
}
?>