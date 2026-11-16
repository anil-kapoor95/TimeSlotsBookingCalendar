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
						$soon = '';
						if ($first && in_array($item['state'], array('pending', 'confirmed'), true))
						{
							$days = (int) round((strtotime(date('Y-m-d', $first['start_ts'])) - strtotime(date('Y-m-d'))) / 86400);
							if ($days === 0)
							{
								$soon = $tsb_label('front_my_today', 'Today');
							} elseif ($days === 1) {
								$soon = $tsb_label('front_my_tomorrow', 'Tomorrow');
							} elseif ($days > 1) {
								$soon = sprintf($tsb_label('front_my_in_days', 'In %d days'), $days);
							}
						}
						?>
						<a href="#" class="tsb-my-item pjTsMyBookingRow tsb-st-<?php echo $item['state']; ?>" data-uuid="<?php echo pjSanitize::html($item['uuid']); ?>">
							<span class="tsb-tk-main">
								<?php
								if ($first)
								{
									?>
									<span class="tsb-tk-day"><?php echo pjDateTime::formatDate($first['booking_date'], 'Y-m-d', $tpl['option_arr']['o_date_format']); ?></span>
									<span class="tsb-tk-time"><?php echo date($tpl['option_arr']['o_time_format'], $first['start_ts']); ?><small><?php echo pjSanitize::html($tsb_label('front_my_to', 'to')); ?> <?php echo date($tpl['option_arr']['o_time_format'], $first['end_ts']); ?><?php echo $extra > 0 ? ' &middot; +' . $extra . ' ' . pjSanitize::html($tsb_label('front_my_more', 'more')) : NULL; ?></small></span>
									<?php
								}
								?>
								<span class="tsb-tk-svc"><?php echo pjSanitize::html(!empty($item['calendar_name']) ? $item['calendar_name'] : $tsb_label('front_my_booking', 'Booking')); ?></span>
							</span>
							<span class="tsb-tk-stub">
								<span class="tsb-badge tsb-badge-<?php echo $item['state']; ?>"><?php echo pjSanitize::html($states[$item['state']]); ?></span>
								<span class="tsb-tk-info">
									<?php
									if (!$hide_prices && $amount > 0)
									{
										?><span class="tsb-tk-price"><?php echo pjCurrency::formatPrice($amount); ?></span><?php
									}
									?>
									<span class="tsb-tk-id"><?php echo pjSanitize::html($item['uuid']); ?></span>
									<?php
									if ($soon !== '')
									{
										?><span class="tsb-tk-soon"><?php echo pjSanitize::html($soon); ?></span><?php
									}
									?>
								</span>
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
