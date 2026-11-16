<?php
$stripe_label = function ($key, $default)
{
	$v = __($key, true);
	return (is_string($v) && $v !== '' && $v !== $key) ? $v : $default;
};
$stripe_has_session = !empty($tpl['session_id']) && empty($tpl['stripe_error']);
$stripe_debug = isset($_SESSION['error_reporting']) && $_SESSION['error_reporting'] == '1';
$stripe_checkout_url = '';
if (!empty($tpl['checkout_url']) && preg_match('#^https://checkout\.stripe\.com/#', $tpl['checkout_url']))
{
	$stripe_checkout_url = $tpl['checkout_url'];
}
$stripe_js = function ($value)
{
	return json_encode((string) $value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
};
?>
<div class="tsb-stripe-box" data-has-session="<?php echo $stripe_has_session ? '1' : '0'; ?>">
	<p class="tsb-processing tsb-stripe-connecting text-center" role="status" aria-live="polite"<?php echo $stripe_has_session ? '' : ' style="display:none"'; ?>>
		<span class="tsb-spinner" aria-hidden="true"></span>
		<span class="tsb-processing-text"><?php echo pjSanitize::html($stripe_label('front_processing_stripe', 'Connecting to Stripe, please wait...')); ?></span>
	</p>
	<div class="tsb-stripe-failed text-center" role="alert"<?php echo $stripe_has_session ? ' style="display:none"' : ''; ?>>
		<p class="alert alert-danger tsb-stripe-failed-msg"><?php echo pjSanitize::html($stripe_label('front_stripe_failed', 'We could not connect to Stripe. Your booking has been saved - please try again.')); ?><?php if ($stripe_debug && !empty($tpl['stripe_error'])) { echo '<br><small>' . pjSanitize::html($tpl['stripe_error']) . '</small>'; } ?></p>
		<a href="#" class="btn btn-primary pjTsSelectorRetryPayment"><?php echo pjSanitize::html($stripe_label('front_payment_retry', 'Try again')); ?></a>
	</div>
</div>
<?php if (!$stripe_has_session) : ?>
<script>(function () { var intro = document.querySelector('.tsb-pay-intro'); if (intro) { intro.style.display = 'none'; } })();</script>
<?php endif; ?>
<?php if ($stripe_has_session) : ?>
<script>
(function () {
	var box = document.querySelector('.tsb-stripe-box');
	var done = false;

	function fail() {
		if (done) { return; }
		done = true;
		if (!box) { return; }
		var intro = document.querySelector('.tsb-pay-intro');
		if (intro) { intro.style.display = 'none'; }
		var c = box.querySelector('.tsb-stripe-connecting'), f = box.querySelector('.tsb-stripe-failed');
		if (c) { c.style.display = 'none'; }
		if (f) { f.style.display = ''; }
	}

	function go(url) {
		try {
			window.top.location.href = url;
		} catch (e) {
			window.location.href = url;
		}
	}

	function loadScript(url, callback, onError) {
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.async = true;
		script.onload = function () { callback(); };
		script.onerror = function () { onError(); };
		script.src = url;
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(script);
	}

	function stripeCallback() {
		try {
			var stripe = Stripe(<?php echo $stripe_js(@$tpl['arr']['public_key']); ?>);
			var p = stripe.redirectToCheckout({
				sessionId: <?php echo $stripe_js(@$tpl['session_id']); ?>
			});
			if (p && typeof p.then === 'function') {
				p.then(function (result) {
					// redirectToCheckout only resolves when it failed (browser / network error)
					if (result && result.error) { fail(); }
				}, fail);
			}
		} catch (e) {
			fail();
		}
	}

	// safety net: if we are still here after 20 seconds something went wrong - show the retry button
	setTimeout(fail, 20000);

<?php if ($stripe_checkout_url !== '') : ?>
	// Stripe returned the hosted Checkout address, so no extra JavaScript library is needed
	go(<?php echo $stripe_js($stripe_checkout_url); ?>);
<?php else : ?>
	if (!window.Stripe) {
		loadScript('https://js.stripe.com/v3/', stripeCallback, fail);
	} else {
		stripeCallback();
	}
<?php endif; ?>
})();
</script>
<?php endif; ?>
