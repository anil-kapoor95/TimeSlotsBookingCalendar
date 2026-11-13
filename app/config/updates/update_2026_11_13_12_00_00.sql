START TRANSACTION;

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_btn_my_bookings', 'frontend', 'front_btn_my_bookings', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_btn_my_bookings');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_btn_my_bookings' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'My Bookings', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_bookings_title', 'frontend', 'front_my_bookings_title', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_bookings_title');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_bookings_title' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'My Bookings', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_filter_all', 'frontend', 'front_my_filter_all', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_all');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_all' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'All', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_filter_upcoming', 'frontend', 'front_my_filter_upcoming', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_upcoming');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_upcoming' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Upcoming', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_filter_past', 'frontend', 'front_my_filter_past', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_past');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_past' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Past', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_filter_cancelled', 'frontend', 'front_my_filter_cancelled', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_cancelled');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_filter_cancelled' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Cancelled', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_status_pending', 'frontend', 'front_my_status_pending', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_status_pending');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_status_pending' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Pending', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_status_confirmed', 'frontend', 'front_my_status_confirmed', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_status_confirmed');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_status_confirmed' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Confirmed', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_status_cancelled', 'frontend', 'front_my_status_cancelled', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_status_cancelled');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_status_cancelled' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Cancelled', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_status_completed', 'frontend', 'front_my_status_completed', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_status_completed');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_status_completed' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Completed', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_empty_title', 'frontend', 'front_my_empty_title', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_title');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_title' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'No bookings yet', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_empty_text', 'frontend', 'front_my_empty_text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_text');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_text' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'You have not made any bookings yet. Pick a date and time on the calendar to book your first slot.', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_empty_filter_title', 'frontend', 'front_my_empty_filter_title', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_filter_title');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_filter_title' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Nothing to show here', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_empty_filter_text', 'frontend', 'front_my_empty_filter_text', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_filter_text');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_empty_filter_text' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'You have no bookings in this category.', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_book_now', 'frontend', 'front_my_book_now', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_book_now');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_book_now' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Book now', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_login_required', 'frontend', 'front_my_login_required', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_login_required');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_login_required' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Please log in to see your bookings.', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_not_found', 'frontend', 'front_my_not_found', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_not_found');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_not_found' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'This booking could not be found.', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_back', 'frontend', 'front_my_back', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_back');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_back' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Back to My Bookings', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_booking', 'frontend', 'front_my_booking', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_booking');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_booking' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Booking', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_detail_title', 'frontend', 'front_my_detail_title', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_detail_title');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_detail_title' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Booking details', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_id', 'frontend', 'front_my_col_id', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_id');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_id' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Booking ID', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_service', 'frontend', 'front_my_col_service', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_service');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_service' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Service', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_status', 'frontend', 'front_my_col_status', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_status');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_status' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Status', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_created', 'frontend', 'front_my_col_created', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_created');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_created' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Booked on', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_total', 'frontend', 'front_my_col_total', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_total');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_total' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Total', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_section_slots', 'frontend', 'front_my_section_slots', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_section_slots');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_section_slots' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Date & time', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_section_payment', 'frontend', 'front_my_section_payment', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_section_payment');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_section_payment' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Payment', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_section_contact', 'frontend', 'front_my_section_contact', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_section_contact');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_section_contact' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Your details', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_method', 'frontend', 'front_my_col_method', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_method');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_method' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Payment method', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_pay_status', 'frontend', 'front_my_col_pay_status', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_pay_status');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_pay_status' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Payment status', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_price', 'frontend', 'front_my_col_price', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_price');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_price' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Price', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_discount', 'frontend', 'front_my_col_discount', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_discount');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_discount' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Discount', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_tax', 'frontend', 'front_my_col_tax', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_tax');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_tax' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Tax', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_deposit', 'frontend', 'front_my_col_deposit', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_deposit');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_deposit' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Deposit', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_pay_paid', 'frontend', 'front_my_pay_paid', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_paid');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_paid' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Paid', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_pay_cash', 'frontend', 'front_my_pay_cash', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_cash');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_cash' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Pay on arrival', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_pay_bank', 'frontend', 'front_my_pay_bank', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_bank');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_bank' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Awaiting bank transfer', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_pay_unpaid', 'frontend', 'front_my_pay_unpaid', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_unpaid');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_pay_unpaid' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Awaiting payment', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_name', 'frontend', 'front_my_col_name', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_name');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_name' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Name', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_email', 'frontend', 'front_my_col_email', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_email');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_email' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Email', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_phone', 'frontend', 'front_my_col_phone', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_phone');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_phone' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Phone', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_address', 'frontend', 'front_my_col_address', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_address');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_address' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Address', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'front_my_col_notes', 'frontend', 'front_my_col_notes', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_my_col_notes');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_my_col_notes' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Notes', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


COMMIT;
