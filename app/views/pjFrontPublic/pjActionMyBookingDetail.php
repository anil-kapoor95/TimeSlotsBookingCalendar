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
<?php
$tsb_ok = !empty($tpl['logged']) && $tpl['status'] == 'OK';
?>
<div class="<?php echo $tsb_ok ? 'tsb-my-wrap' : 'panel panel-primary'; ?>">
	<?php
	if (!$tsb_ok)
	{
		?>
		<div class="panel-heading">
			<?php echo pjSanitize::html($tsb_label('front_my_detail_title', 'Booking details')); ?>
		</div><!-- /.panel-heading -->
		<?php
	}
	?>
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
		# there is no payment-status column. An online payment counts as paid when a transaction id was stored
		# OR when the payment gateway moved the booking to the "status if paid" (this script's payment confirmation
		# only changed the booking status, so older Stripe bookings have no transaction id)
		$tsb_paid_status = isset($tpl['option_arr']['o_status_if_paid']) ? $tpl['option_arr']['o_status_if_paid'] : '';
		$tsb_unpaid_status = isset($tpl['option_arr']['o_status_if_not_paid']) ? $tpl['option_arr']['o_status_if_not_paid'] : '';
		$tsb_is_online = !in_array($b['payment_method'], array('', 'cash', 'bank'), true);
		$tsb_paid_by_status = $tsb_is_online && $tsb_paid_status !== '' && $tsb_paid_status != $tsb_unpaid_status && $b['booking_status'] == $tsb_paid_status;
		if ($b['txn_id'] != '' || $tsb_paid_by_status)
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
		<div class="tsb-my-detail">
			<?php
			$first = !empty($b['slots']) ? $b['slots'][0] : NULL;
			$extra = count($b['slots']) - 1;
			$kv = function ($label, $value, $class = '')
			{
				if ($value === NULL || $value === '')
				{
					return;
				}
				?><div class="tsb-kv<?php echo $class != '' ? ' ' . $class : NULL; ?>"><span><?php echo pjSanitize::html($label); ?></span><span><?php echo $value; ?></span></div><?php
			};
			?>
			<div class="tsb-hero">
				<div class="tsb-hero-main">
					<span class="tsb-hero-svc"><?php echo pjSanitize::html($b['calendar_name']); ?></span>
					<span class="tsb-hero-id"><?php echo pjSanitize::html($b['uuid']); ?></span>
					<?php
					if ($first)
					{
						?>
						<div class="tsb-hero-when">
							<div><span><?php echo pjSanitize::html($tsb_label('front_my_col_date', 'Date')); ?></span><b><?php echo pjDateTime::formatDate($first['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></b></div>
							<div><span><?php echo pjSanitize::html($tsb_label('front_my_col_time', 'Time')); ?></span><b><?php echo date($tpl['option_arr']['o_time_format'], $first['start_ts']); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $first['end_ts']); ?><?php echo $extra > 0 ? ' &middot; +' . $extra . ' ' . pjSanitize::html($tsb_label('front_my_more', 'more')) : NULL; ?></b></div>
						</div>
						<?php
					}
					?>
				</div>
				<div class="tsb-hero-side">
					<span class="tsb-badge tsb-badge-<?php echo $b['state']; ?>"><?php echo pjSanitize::html($states[$b['state']]); ?></span>
					<?php
					if (!$hide_prices)
					{
						?><span class="tsb-hero-total"><?php echo pjCurrency::formatPrice($b['booking_total']); ?></span><?php
					}
					?>
				</div>
			</div>
			<div class="tsb-dgrid">
				<div class="tsb-dcard tsb-full">
					<h5><?php echo pjSanitize::html($tsb_label('front_my_section_slots', 'Date & time')); ?></h5>
					<?php
					foreach ($b['slots'] as $s)
					{
						?>
						<div class="tsb-slot">
							<span><?php echo pjDateTime::formatDate($s['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?> &middot; <b><?php echo date($tpl['option_arr']['o_time_format'], $s['start_ts']); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $s['end_ts']); ?></b></span>
							<?php
							if (!$hide_prices && (float) $s['price'] > 0)
							{
								?><b><?php echo (int) $s['cnt']; ?> &times; <?php echo pjCurrency::formatPrice($s['price']); ?></b><?php
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
					<div class="tsb-dcard">
						<h5><?php echo pjSanitize::html($tsb_label('front_my_section_payment', 'Payment')); ?></h5>
						<?php
						# same fields and order as the admin booking form: method, voucher code, price, discount, tax, total, deposit
						# price, discount, tax, total and deposit are always shown (0.00 when empty); the voucher code only when one was used
						$kv($tsb_label('front_my_col_method', 'Payment method'), pjSanitize::html($method));
						if ($b['discount_code'] != '')
						{
							$kv($tsb_label('front_my_col_voucher', 'Voucher code'), pjSanitize::html($b['discount_code']));
						}
						$kv($tsb_label('front_my_col_price', 'Price'), pjCurrency::formatPrice($b['booking_price']));
						$kv($tsb_label('front_my_col_discount', 'Discount'), ((float) $b['booking_discount'] > 0 ? '- ' : '') . pjCurrency::formatPrice($b['booking_discount']));
						$kv($tsb_label('front_my_col_tax', 'Tax'), pjCurrency::formatPrice($b['booking_tax']));
						$kv($tsb_label('front_my_col_total', 'Total'), '<strong>' . pjCurrency::formatPrice($b['booking_total']) . '</strong>', 'tsb-total');
						$kv($tsb_label('front_my_col_deposit', 'Deposit'), pjCurrency::formatPrice($b['booking_deposit']));
						?>
					</div>
					<?php
				}
				?>
				<div class="tsb-dcard">
					<h5><?php echo pjSanitize::html($tsb_label('front_my_section_contact', 'Your details')); ?></h5>
					<?php
					$kv($tsb_label('front_my_col_created', 'Booked on'), pjSanitize::html(date($tpl['option_arr']['o_date_format'] . ' ' . $tpl['option_arr']['o_time_format'], strtotime($b['created']))));
					$kv($tsb_label('front_my_col_name', 'Name'), pjSanitize::html($b['customer_name']));
					$kv($tsb_label('front_my_col_email', 'Email'), pjSanitize::html($b['customer_email']));
					$kv($tsb_label('front_my_col_phone', 'Phone'), pjSanitize::html($b['customer_phone']));
					$kv($tsb_label('front_my_col_address', 'Address'), !empty($address) ? join(', ', $address) : NULL);
					$kv($tsb_label('front_my_col_notes', 'Notes'), $b['customer_notes'] != '' ? nl2br(pjSanitize::html($b['customer_notes'])) : NULL);
					?>
				</div>
			</div><!-- /.tsb-dgrid -->
			<div class="tsb-my-foot">
				<a href="#" class="btn btn-primary pjTsMyBookings tsb-back-btn" data-keep="1"><span class="glyphicon glyphicon-chevron-left"></span> <?php echo pjSanitize::html($tsb_label('front_my_back', 'Back to My Bookings')); ?></a>
			</div>
		</div><!-- /.tsb-my-detail -->
		<?php
	}
	?>
</div>
