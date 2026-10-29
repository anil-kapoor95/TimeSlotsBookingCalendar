<?php
/**
 * Shared helpers for the Theme 11 checkout steps.
 * Everything printed here carries the "tsb-only" class: it is visible in Theme 11 only
 * (pjActionLoadCss hides it for every other theme), so the older themes look exactly as before.
 *
 * Usage: set $tsb_step before including this file:
 * 0 = time slots / cart, 1 = your details, 2 = confirm, 3 = done (all steps complete).
 */
$tsb_label = function ($key, $default)
{
	$v = __($key, true);
	return (is_string($v) && $v !== '' && $v !== $key) ? $v : $default;
};
$tsb_printed = array();
$tsb_head = function ($group) use ($tsb_label, &$tsb_printed)
{
	if (isset($tsb_printed[$group]))
	{
		return;
	}
	$tsb_printed[$group] = true;
	$titles = array(
		'contact' => $tsb_label('front_sec_contact', 'Contact'),
		'address' => $tsb_label('front_sec_address', 'Address'),
		'extra' => $tsb_label('front_sec_extra', 'Additional information'),
		'payment' => $tsb_label('front_sec_payment', 'Payment')
	);
	if (isset($titles[$group]))
	{
		?><div class="tsb-only tsb-sec-title"><?php echo pjSanitize::html($titles[$group]); ?></div><?php
	}
};
if (isset($tsb_step))
{
	$tsb_labels = array(
		$tsb_label('front_step_slots', 'Time slots'),
		$tsb_label('front_step_details', 'Your details'),
		$tsb_label('front_step_confirm', 'Confirm'),
		$tsb_label('front_step_done', 'Done')
	);
	$tsb_cur = (int) $tsb_step >= 3 ? 99 : (int) $tsb_step; // 3 = booking completed: every step shows as done
	?>
	<ol class="tsb-only tsb-steps" aria-label="<?php echo pjSanitize::html($tsb_label('front_steps_label', 'Booking progress')); ?>">
		<?php foreach ($tsb_labels as $i => $l) { ?>
		<li class="<?php echo $i < $tsb_cur ? 'done' : ($i === $tsb_cur ? 'cur' : ''); ?>"><span class="n"><?php echo $i < $tsb_cur ? '&#10003;' : ($i + 1); ?></span><span class="l"><?php echo pjSanitize::html($l); ?></span></li>
		<?php } ?>
	</ol>
	<?php
}
