<?php
include PJ_VIEWS_PATH . 'pjFrontPublic/elements/menu.php';
$tsb_label = function ($key, $default)
{
	$v = __($key, true);
	return (is_string($v) && $v !== '' && $v !== $key) ? $v : $default;
};
$get_cid = $controller->_get->toInt('cid');
$hide_prices = (int) $tpl['option_arr']['o_hide_prices'] === 1;
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<?php echo pjSanitize::html($tsb_label('front_my_bookings_title', 'My Bookings')); ?>
	</div><!-- /.panel-heading -->
	<?php
	if (empty($tpl['logged']))
	{
		?>
		<div class="panel-body">
			<p class="tsb-my-empty-text"><?php echo pjSanitize::html($tsb_label('front_my_login_required', 'Please log in to see your bookings.')); ?></p>
		</div><!-- /.panel-body -->
		<div class="panel-footer text-center">
			<a href="#" class="btn btn-primary pjTsLogin"><span class="glyphicon glyphicon-log-in"></span> <?php __('front_btn_login'); ?></a>
		</div><!-- /.panel-footer -->
		<?php
	} else {
		$filters = array(
			'all' => $tsb_label('front_my_filter_all', 'All'),
			'upcoming' => $tsb_label('front_my_filter_upcoming', 'Upcoming'),
			'past' => $tsb_label('front_my_filter_past', 'Past'),
			'cancelled' => $tsb_label('front_my_filter_cancelled', 'Cancelled')
		);
		$states = array(
			'pending' => $tsb_label('front_my_status_pending', 'Pending'),
			'confirmed' => $tsb_label('front_my_status_confirmed', 'Confirmed'),
			'cancelled' => $tsb_label('front_my_status_cancelled', 'Cancelled'),
			'completed' => $tsb_label('front_my_status_completed', 'Completed')
		);
		?>
		<div class="panel-body">
			<ul class="tsb-my-filters" role="tablist">
				<?php
				foreach ($filters as $key => $title)
				{
					?>
					<li>
						<a href="#" role="tab" class="tsb-my-filter pjTsMyFilter<?php echo $tpl['filter'] == $key ? ' active' : NULL; ?>" data-filter="<?php echo $key; ?>" aria-selected="<?php echo $tpl['filter'] == $key ? 'true' : 'false'; ?>">
							<?php echo pjSanitize::html($title); ?> <span class="tsb-my-count"><?php echo (int) $tpl['counts'][$key]; ?></span>
						</a>
					</li>
					<?php
				}
				?>
			</ul>
			<?php
			if (empty($tpl['booking_arr']))
			{
				?>
				<div class="tsb-my-empty">
					<span class="glyphicon glyphicon-calendar tsb-my-empty-icon" aria-hidden="true"></span>
					<?php
					if ((int) $tpl['counts']['all'] === 0)
					{
						?>
						<h4 class="tsb-my-empty-title"><?php echo pjSanitize::html($tsb_label('front_my_empty_title', 'No bookings yet')); ?></h4>
						<p class="tsb-my-empty-text"><?php echo pjSanitize::html($tsb_label('front_my_empty_text', 'You have not made any bookings yet. Pick a date and time on the calendar to book your first slot.')); ?></p>
						<a href="#" class="btn btn-primary pjTsSelectorCalendar"><?php echo pjSanitize::html($tsb_label('front_my_book_now', 'Book now')); ?></a>
						<?php
					} else {
						?>
						<h4 class="tsb-my-empty-title"><?php echo pjSanitize::html($tsb_label('front_my_empty_filter_title', 'Nothing to show here')); ?></h4>
						<p class="tsb-my-empty-text"><?php echo pjSanitize::html($tsb_label('front_my_empty_filter_text', 'You have no bookings in this category.')); ?></p>
						<?php
					}
					?>
				</div>
				<?php
			} else {
				?>
				<div class="tsb-my-list">
					<?php
					foreach ($tpl['booking_arr'] as $item)
					{
						$first = !empty($item['slots']) ? $item['slots'][0] : NULL;
						$extra = count($item['slots']) - 1;
						$amount = (float) $item['booking_total'];
						?>
						<a href="#" class="tsb-my-item pjTsMyBookingRow tsb-st-<?php echo $item['state']; ?>" data-uuid="<?php echo pjSanitize::html($item['uuid']); ?>">
							<span class="tsb-my-main">
								<span class="tsb-my-name"><?php echo pjSanitize::html(!empty($item['calendar_name']) ? $item['calendar_name'] : $tsb_label('front_my_booking', 'Booking')); ?></span>
								<span class="tsb-badge tsb-badge-<?php echo $item['state']; ?>"><?php echo pjSanitize::html($states[$item['state']]); ?></span>
							</span>
							<span class="tsb-my-when">
								<?php
								if ($first)
								{
									?>
									<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> <?php echo pjDateTime::formatDate($first['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?>
									<span class="glyphicon glyphicon-time" aria-hidden="true"></span> <?php echo date($tpl['option_arr']['o_time_format'], $first['start_ts']); ?> - <?php echo date($tpl['option_arr']['o_time_format'], $first['end_ts']); ?>
									<?php
									if ($extra > 0)
									{
										?><span class="tsb-my-more">+<?php echo $extra; ?></span><?php
									}
								}
								?>
							</span>
							<span class="tsb-my-meta">
								<span><?php echo pjSanitize::html($tsb_label('front_my_col_id', 'Booking ID')); ?>: <strong><?php echo pjSanitize::html($item['uuid']); ?></strong></span>
								<?php
								if (!$hide_prices && $amount > 0)
								{
									?><span><?php echo pjSanitize::html($tsb_label('front_my_col_total', 'Total')); ?>: <strong><?php echo pjCurrency::formatPrice($amount); ?></strong></span><?php
								}
								?>
							</span>
						</a>
						<?php
					}
					?>
				</div>
				<?php
			}
			?>
		</div><!-- /.panel-body -->
		<?php
	}
	?>
</div>
