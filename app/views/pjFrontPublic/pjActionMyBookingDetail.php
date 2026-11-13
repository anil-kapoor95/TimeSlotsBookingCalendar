<?php
include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
$tsb_label = function ($key, $default)
{
	$v = __($key, true);
	return (is_string($v) && $v !== '' && $v !== $key) ? $v : $default;
};
$hide_prices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
$row = function ($label, $value) 
{
	if ($value === NULL || $value === '')
	{
		return;
	}
	?>
	<div class="row">
		<label class="col-sm-4 control-label"><?php echo pjSanitize::html($label); ?></label>
		<div class="col-sm-8"><p class="form-control-static"><?php echo $value; ?></p></div>
	</div>
	<?php
};
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<?php echo pjSanitize::html($tsb_label('front_my_detail_title', 'Booking details')); ?>
	</div><!-- /.panel-heading -->
	<?php
	if (empty($tpl['logged']) || $tpl['status'] != 'OK')
	{
		?>
		<div class="panel-body">
			<p class="tsb-my-empty-text"><?php echo pjSanitize::html(empty($tpl['logged']) ? $tsb_label('front_my_login_required', 'Please log in to see your bookings.') : $tsb_label('front_my_not_found', 'This booking could not be found.')); ?></p>
		</div><!-- /.panel-body -->
		<div class="panel-footer">
			<?php
			if (empty($tpl['logged']))
			{
				?><a href="#" class="btn btn-primary pjTsLogin"><span class="glyphicon glyphicon-log-in"></span> <?php __('front_btn_login'); ?></a><?php
			} else {
				?><a href="#" class="btn btn-primary pjTsMyBookings tsb-back-btn" data-keep="1"><span class="glyphicon glyphicon-chevron-left"></span> <?php echo pjSanitize::html($tsb_label('front_my_back', 'Back to My Bookings')); ?></a><?php
			}
			?>
		</div><!-- /.panel-footer -->
		<?php
	} else {
		$b = $tpl['booking_arr'];
		$states = array(
			'pending' => $tsb_label('front_my_status_pending', 'Pending'),
			'confirmed' => $tsb_label('front_my_status_confirmed', 'Confirmed'),
			'cancelled' => $tsb_label('front_my_status_cancelled', 'Cancelled'),
			'completed' => $tsb_label('front_my_status_completed', 'Completed')
		);
		$payment_titles = is_array($tpl['payment_titles']) ? $tpl['payment_titles'] : array();
		$method = isset($payment_titles[$b['payment_method']]) ? $payment_titles[$b['payment_method']] : $b['payment_method'];
		# there is no payment-status column: a transaction id means the online payment went through
		if ($b['txn_id'] != '')
		{
			$pay_state = array('paid', $tsb_label('front_my_pay_paid', 'Paid'));
		} elseif ($b['state'] == 'cancelled') {
			$pay_state = array('cancelled', $tsb_label('front_my_status_cancelled', 'Cancelled'));
		} elseif ($b['payment_method'] == 'cash') {
			$pay_state = array('pending', $tsb_label('front_my_pay_cash', 'Pay on arrival'));
		} elseif ($b['payment_method'] == 'bank') {
			$pay_state = array('pending', $tsb_label('front_my_pay_bank', 'Awaiting bank transfer'));
		} else {
			$pay_state = array('pending', $tsb_label('front_my_pay_unpaid', 'Awaiting payment'));
		}
		$address = array();
		foreach (array('customer_address_1', 'customer_address_2', 'customer_city', 'customer_state', 'customer_zip') as $f)
		{
			if (!empty($b[$f]))
			{
				$address[] = pjSanitize::html($b[$f]);
			}
		}
		?>
		<div class="panel-body">
			<div class="pj-calendar-form tsb-my-detail">
				<?php
				$row($tsb_label('front_my_col_id', 'Booking ID'), '<strong>' . pjSanitize::html($b['uuid']) . '</strong>');
				$row($tsb_label('front_my_col_service', 'Service'), pjSanitize::html($b['calendar_name']));
				$row($tsb_label('front_my_col_status', 'Status'), '<span class="tsb-badge tsb-badge-' . $b['state'] . '">' . pjSanitize::html($states[$b['state']]) . '</span>');
				$row($tsb_label('front_my_col_created', 'Booked on'), pjSanitize::html(date($tpl['option_arr']['o_date_format'] . ' ' . $tpl['option_arr']['o_time_format'], strtotime($b['created']))));
				?>
				<h5 class="tsb-my-section"><?php echo pjSanitize::html($tsb_label('front_my_section_slots', 'Date & time')); ?></h5>
				<div class="tsb-my-slots">
					<?php
					foreach ($b['slots'] as $s)
					{
						?>
						<div class="tsb-my-slot">
							<span><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php echo pjDateTime::formatDate($s['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></span>
							<span><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php echo date($tpl['option_arr']['o_time_format'], $s['start_ts']); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $s['end_ts']); ?></span>
							<?php
							if (!$hide_prices && (float) $s['price'] > 0)
							{
								?><span class="tsb-my-slot-price"><?php echo (int) $s['cnt']; ?> &times; <?php echo pjCurrency::formatPrice($s['price']); ?></span><?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
				<?php
				if (!$hide_prices)
				{
					?>
					<h5 class="tsb-my-section"><?php echo pjSanitize::html($tsb_label('front_my_section_payment', 'Payment')); ?></h5>
					<?php
					$row($tsb_label('front_my_col_method', 'Payment method'), pjSanitize::html($method));
					$row($tsb_label('front_my_col_pay_status', 'Payment status'), '<span class="tsb-badge tsb-badge-' . $pay_state[0] . '">' . pjSanitize::html($pay_state[1]) . '</span>');
					$row($tsb_label('front_my_col_price', 'Price'), (float) $b['booking_price'] > 0 ? pjCurrency::formatPrice($b['booking_price']) : NULL);
					$row($tsb_label('front_my_col_discount', 'Discount'), (float) $b['booking_discount'] > 0 ? '- ' . pjCurrency::formatPrice($b['booking_discount']) . ($b['discount_code'] != '' ? ' (' . pjSanitize::html($b['discount_code']) . ')' : '') : NULL);
					$row($tsb_label('front_my_col_tax', 'Tax'), (float) $b['booking_tax'] > 0 ? pjCurrency::formatPrice($b['booking_tax']) : NULL);
					$row($tsb_label('front_my_col_total', 'Total'), '<strong>' . pjCurrency::formatPrice($b['booking_total']) . '</strong>');
					$row($tsb_label('front_my_col_deposit', 'Deposit'), (float) $b['booking_deposit'] > 0 ? pjCurrency::formatPrice($b['booking_deposit']) : NULL);
				}
				?>
				<h5 class="tsb-my-section"><?php echo pjSanitize::html($tsb_label('front_my_section_contact', 'Your details')); ?></h5>
				<?php
				$row($tsb_label('front_my_col_name', 'Name'), pjSanitize::html($b['customer_name']));
				$row($tsb_label('front_my_col_email', 'Email'), pjSanitize::html($b['customer_email']));
				$row($tsb_label('front_my_col_phone', 'Phone'), pjSanitize::html($b['customer_phone']));
				$row($tsb_label('front_my_col_address', 'Address'), !empty($address) ? join(', ', $address) : NULL);
				$row($tsb_label('front_my_col_notes', 'Notes'), $b['customer_notes'] != '' ? nl2br(pjSanitize::html($b['customer_notes'])) : NULL);
				?>
			</div><!-- /.pj-calendar-form -->
		</div><!-- /.panel-body -->
		<div class="panel-footer">
			<a href="#" class="btn btn-primary pjTsMyBookings tsb-back-btn" data-keep="1"><span class="glyphicon glyphicon-chevron-left"></span> <?php echo pjSanitize::html($tsb_label('front_my_back', 'Back to My Bookings')); ?></a>
		</div><!-- /.panel-footer -->
		<?php
	}
	?>
</div>
