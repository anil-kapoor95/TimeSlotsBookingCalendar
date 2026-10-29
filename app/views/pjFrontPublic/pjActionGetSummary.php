<?php
if ((int) $tpl['option_arr']['o_disable_payments'] === 0 && (int) $tpl['option_arr']['o_hide_prices'] === 0)
{
	?>	
	<div class="panel panel-primary">
		<div class="panel-heading">
			<?php __('front_summary'); ?>
		</div><!-- /.panel-heading -->
	
		<div class="panel-body">
			<div class="pj-calendar-booking-summary">
				<ul>
					<li>
						<span><?php __('front_summary_price'); ?></span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['original_price']); ?></strong>
					</li>
					<li>
						<span><?php __('front_summary_discount'); ?></span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['discount']); ?></strong>
					</li>
					<li>
						<span><?php __('front_summary_subtotal'); ?></span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['price']); ?></strong>
					</li>
					<li>
						<span><?php __('front_summary_tax'); ?> (<?php echo $tpl['option_arr']['o_tax']?>%)</span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['tax']); ?></strong>
					</li>
	
					<li>
						<span><?php __('front_summary_total'); ?></span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['total']); ?></strong>
					</li>
	
					<li>
						<span><?php __('front_summary_deposit'); ?><?php echo $tpl['option_arr']['o_deposit_type'] == 'percent' ? ' ('.$tpl['option_arr']['o_deposit'].'%)' : NULL;?></span>
	
						<strong><?php echo pjCurrency::formatPrice($tpl['amount']['deposit']); ?></strong>
					</li>
				</ul>
			</div><!-- /.pj-calendar-booking-summary -->
		</div><!-- /.panel-body -->
	</div><!-- /.panel -->
	<?php
} 
?>