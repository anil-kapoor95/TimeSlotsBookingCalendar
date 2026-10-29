DROP TABLE IF EXISTS `ts_booking_bookings`;
CREATE TABLE IF NOT EXISTS `ts_booking_bookings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(12) DEFAULT NULL,
  `calendar_id` int(10) UNSIGNED DEFAULT NULL,
  `locale_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(10) UNSIGNED DEFAULT NULL,
  `booking_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `booking_total` decimal(16,2) UNSIGNED DEFAULT NULL,
  `booking_deposit` decimal(16,2) UNSIGNED DEFAULT NULL,
  `booking_tax` decimal(16,2) UNSIGNED DEFAULT NULL,
  `booking_discount` decimal(16,2) UNSIGNED DEFAULT NULL,
  `discount_code` VARCHAR(255) DEFAULT NULL,
  `booking_status` enum('pending','confirmed','cancelled') DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_option` enum('deposit','total') DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `customer_country` int(10) UNSIGNED DEFAULT NULL,
  `customer_state` varchar(255) DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `customer_address_1` varchar(255) DEFAULT NULL,
  `customer_address_2` varchar(255) DEFAULT NULL,
  `customer_zip` varchar(255) DEFAULT NULL,
  `customer_notes` text,
  `cc_type` blob,
  `cc_num` blob,
  `cc_exp_month` blob,
  `cc_exp_year` blob,
  `cc_code` blob,
  `txn_id` varchar(255) DEFAULT NULL,
  `processed_on` datetime DEFAULT NULL,
  `reminder_email` tinyint(1) UNSIGNED DEFAULT '0',
  `reminder_sms` tinyint(1) UNSIGNED DEFAULT '0',
  `ip` varchar(15) DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `calendar_id` (`calendar_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_bookings_slots`;
CREATE TABLE IF NOT EXISTS `ts_booking_bookings_slots` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` int(10) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `start_ts` int(10) UNSIGNED DEFAULT NULL,
  `end_ts` int(10) UNSIGNED DEFAULT NULL,
  `price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `cnt` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_id` (`booking_id`,`booking_date`,`start_time`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_clients`;
CREATE TABLE IF NOT EXISTS `ts_booking_clients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) unsigned DEFAULT NULL,
  `calendar_id` int(10) unsigned DEFAULT NULL,
  `customer_title` varchar(255) DEFAULT NULL,
  `customer_company` varchar(255) DEFAULT NULL,
  `customer_address_1` varchar(255) DEFAULT NULL,
  `customer_address_2` varchar(255) DEFAULT NULL,
  `customer_country` varchar(255) DEFAULT NULL,
  `customer_state` varchar(255) DEFAULT NULL,
  `customer_city` varchar(255) DEFAULT NULL,
  `customer_zip` varchar(255) DEFAULT NULL,
  `customer_notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_calendars`;
CREATE TABLE IF NOT EXISTS `ts_booking_calendars` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_calendars_users`;
CREATE TABLE IF NOT EXISTS `ts_booking_calendars_users` (
  `calendar_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`calendar_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_dates`;
CREATE TABLE IF NOT EXISTS `ts_booking_dates` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `slot_length` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Values in minutes',
  `slot_limit` smallint(5) UNSIGNED DEFAULT '1' COMMENT 'Max bookings per slot',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `slots` smallint(5) UNSIGNED DEFAULT NULL,
  `people` smallint(5) UNSIGNED DEFAULT NULL,
  `start_lunch` time DEFAULT NULL,
  `end_lunch` time DEFAULT NULL,
  `price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `is_dayoff` enum('T','F') DEFAULT 'F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`,`date`),
  KEY `is_dayoff` (`is_dayoff`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_options`;
CREATE TABLE IF NOT EXISTS `ts_booking_options` (
  `foreign_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `key` varchar(255) NOT NULL DEFAULT '',
  `tab_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `value` text,
  `label` text,
  `type` enum('string','text','int','float','enum','bool') NOT NULL DEFAULT 'string',
  `order` int(10) UNSIGNED DEFAULT NULL,
  `is_visible` tinyint(1) UNSIGNED DEFAULT '1',
  `style` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`foreign_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_password`;
CREATE TABLE IF NOT EXISTS `ts_booking_password` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_prices`;
CREATE TABLE IF NOT EXISTS `ts_booking_prices` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time DEFAULT NULL,
  `start_ts` int(10) UNSIGNED DEFAULT NULL,
  `end_ts` int(10) UNSIGNED DEFAULT NULL,
  `price` decimal(16,2) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendar_id` (`calendar_id`,`date`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_prices_days`;
CREATE TABLE IF NOT EXISTS `ts_booking_prices_days` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `calendar_id` int(10) UNSIGNED NOT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL DEFAULT '00:00:00',
  `end_time` time DEFAULT NULL,
  `price` decimal(16,2) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendar_id` (`calendar_id`,`day`,`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_working_times`;
CREATE TABLE IF NOT EXISTS `ts_booking_working_times` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `monday_from` time DEFAULT NULL,
  `monday_to` time DEFAULT NULL,
  `monday_lunch_from` time DEFAULT NULL,
  `monday_lunch_to` time DEFAULT NULL,
  `monday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `monday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `monday_length` smallint(5) UNSIGNED DEFAULT '60',
  `monday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `monday_people` smallint(5) UNSIGNED DEFAULT '1',
  `monday_dayoff` enum('T','F') DEFAULT 'F',
  `tuesday_from` time DEFAULT NULL,
  `tuesday_to` time DEFAULT NULL,
  `tuesday_lunch_from` time DEFAULT NULL,
  `tuesday_lunch_to` time DEFAULT NULL,
  `tuesday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `tuesday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `tuesday_length` smallint(5) UNSIGNED DEFAULT '60',
  `tuesday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `tuesday_people` smallint(5) UNSIGNED DEFAULT '9',
  `tuesday_dayoff` enum('T','F') DEFAULT 'F',
  `wednesday_from` time DEFAULT NULL,
  `wednesday_to` time DEFAULT NULL,
  `wednesday_lunch_from` time DEFAULT NULL,
  `wednesday_lunch_to` time DEFAULT NULL,
  `wednesday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `wednesday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `wednesday_length` smallint(5) UNSIGNED DEFAULT '60',
  `wednesday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `wednesday_people` smallint(5) UNSIGNED DEFAULT '1',
  `wednesday_dayoff` enum('T','F') DEFAULT 'F',
  `thursday_from` time DEFAULT NULL,
  `thursday_to` time DEFAULT NULL,
  `thursday_lunch_from` time DEFAULT NULL,
  `thursday_lunch_to` time DEFAULT NULL,
  `thursday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `thursday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `thursday_length` smallint(5) UNSIGNED DEFAULT '60',
  `thursday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `thursday_people` smallint(5) UNSIGNED DEFAULT '1',
  `thursday_dayoff` enum('T','F') DEFAULT 'F',
  `friday_from` time DEFAULT NULL,
  `friday_to` time DEFAULT NULL,
  `friday_lunch_from` time DEFAULT NULL,
  `friday_lunch_to` time DEFAULT NULL,
  `friday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `friday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `friday_length` smallint(5) UNSIGNED DEFAULT '60',
  `friday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `friday_people` smallint(5) UNSIGNED DEFAULT '1',
  `friday_dayoff` enum('T','F') DEFAULT 'F',
  `saturday_from` time DEFAULT NULL,
  `saturday_to` time DEFAULT NULL,
  `saturday_lunch_to` time DEFAULT NULL,
  `saturday_lunch_from` time DEFAULT NULL,
  `saturday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `saturday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `saturday_length` smallint(5) UNSIGNED DEFAULT '60',
  `saturday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `saturday_people` smallint(5) UNSIGNED DEFAULT '1',
  `saturday_dayoff` enum('T','F') DEFAULT 'F',
  `sunday_from` time DEFAULT NULL,
  `sunday_to` time DEFAULT NULL,
  `sunday_lunch_from` time DEFAULT NULL,
  `sunday_lunch_to` time DEFAULT NULL,
  `sunday_price` decimal(16,2) UNSIGNED DEFAULT NULL,
  `sunday_limit` smallint(5) UNSIGNED DEFAULT '1',
  `sunday_length` smallint(5) UNSIGNED DEFAULT '60',
  `sunday_slots` smallint(5) UNSIGNED DEFAULT '9',
  `sunday_people` smallint(5) UNSIGNED DEFAULT '1',
  `sunday_dayoff` enum('T','F') DEFAULT 'F',
  PRIMARY KEY (`id`),
  UNIQUE KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ts_booking_notifications`;
CREATE TABLE IF NOT EXISTS `ts_booking_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foreign_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `recipient` enum('client','admin') DEFAULT NULL,
  `transport` enum('email','sms') DEFAULT NULL,
  `variant` varchar(30) DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `recipient` (`foreign_id`,`recipient`,`transport`,`variant`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT IGNORE INTO `ts_booking_notifications` (`id`, `foreign_id`, `recipient`, `transport`, `variant`, `is_active`) VALUES
(NULL, 0, 'client', 'email', 'confirmation', 1),
(NULL, 0, 'client', 'email', 'payment', 1),
(NULL, 0, 'client', 'email', 'cancel', 1),
(NULL, 0, 'client', 'email', 'reminder', 1),
(NULL, 0, 'client', 'email', 'account', 1),
(NULL, 0, 'client', 'email', 'forgot', 1),
(NULL, 0, 'admin', 'email', 'confirmation', 1),
(NULL, 0, 'admin', 'email', 'payment', 1),
(NULL, 0, 'admin', 'email', 'cancel', 1),
(NULL, 0, 'client', 'sms', 'reminder', 1),
(NULL, 0, 'admin', 'sms', 'confirmation', 1),
(NULL, 0, 'admin', 'sms', 'payment', 1);

INSERT INTO `ts_booking_working_times` (`id`, `foreign_id`, `monday_from`, `monday_to`, `monday_lunch_from`, `monday_lunch_to`, `monday_price`, `monday_limit`, `monday_length`, `monday_slots`, `monday_people`, `monday_dayoff`, `tuesday_from`, `tuesday_to`, `tuesday_lunch_from`, `tuesday_lunch_to`, `tuesday_price`, `tuesday_limit`, `tuesday_length`, `tuesday_slots`, `tuesday_people`, `tuesday_dayoff`, `wednesday_from`, `wednesday_to`, `wednesday_lunch_from`, `wednesday_lunch_to`, `wednesday_price`, `wednesday_limit`, `wednesday_length`, `wednesday_slots`, `wednesday_people`, `wednesday_dayoff`, `thursday_from`, `thursday_to`, `thursday_lunch_from`, `thursday_lunch_to`, `thursday_price`, `thursday_limit`, `thursday_length`, `thursday_slots`, `thursday_people`, `thursday_dayoff`, `friday_from`, `friday_to`, `friday_lunch_from`, `friday_lunch_to`, `friday_price`, `friday_limit`, `friday_length`, `friday_slots`, `friday_people`, `friday_dayoff`, `saturday_from`, `saturday_to`, `saturday_lunch_to`, `saturday_lunch_from`, `saturday_price`, `saturday_limit`, `saturday_length`, `saturday_slots`, `saturday_people`, `saturday_dayoff`, `sunday_from`, `sunday_to`, `sunday_lunch_from`, `sunday_lunch_to`, `sunday_price`, `sunday_limit`, `sunday_length`, `sunday_slots`, `sunday_people`, `sunday_dayoff`) VALUES
(NULL, 0, '08:00:00', '18:00:00', '00:00:00', '00:00:00', '10.00', 1, 60, 10, 1, 'F', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '10.00', 1, 60, 10, 1, 'F', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '10.00', 1, 60, 10, 1, 'F', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '10.00', 1, 60, 10, 1, 'F', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '10.00', 1, 60, 10, 1, 'F', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '0.00', 1, 60, 10, 1, 'T', '08:00:00', '18:00:00', '00:00:00', '00:00:00', '0.00', 1, 60, 10, 1, 'T');

INSERT INTO `ts_booking_options` (`foreign_id`, `key`, `tab_id`, `value`, `label`, `type`, `order`, `is_visible`, `style`) VALUES
(0, 'o_theme', 1, 'theme1|theme2|theme3|theme4|theme5|theme6|theme7|theme8|theme9|theme10::theme1', 'Theme 1|Theme 2|Theme 3|Theme 4|Theme 5|Theme 6|Theme 7|Theme 8|Theme 9|Theme 10', 'enum', 1, 0, NULL),
(0, 'o_layout', 1, '1|2::1', 'Default layout|Weekly layout', 'enum', 1, 1, NULL),

(0, 'o_hide_prices', 3, '1|0::0', NULL, 'bool', 2, 1, NULL),
(0, 'o_show_legend', 3, '1|0::1', NULL, 'bool', 3, 0, NULL),
(0, 'o_show_week_numbers', 3, '1|0::0', NULL, 'bool', 5, 1, NULL),
(0, 'o_status_if_paid', 3, 'confirmed|pending::confirmed', 'Confirmed|Pending', 'enum', 9, 1, NULL),
(0, 'o_status_if_not_paid', 3, 'confirmed|pending::pending', 'Confirmed|Pending', 'enum', 10, 1, NULL),
(0, 'o_hours_before', 3, '0', NULL, 'int', 11, 1, NULL),
(0, 'o_max_slots_per_booking', 3, '0', NULL, 'int', 12, 1, NULL),
(0, 'o_reminder_enable', 3, '1|0::1', NULL, 'bool', 13, 1, NULL),
(0, 'o_reminder_sms_hours', 3, '1', NULL, 'int', 14, 1, NULL),
(0, 'o_reminder_email_before', 3, '2', NULL, 'int', 15, 1, NULL),
(0, 'o_disable_payments', 3, '1|0::0', NULL, 'bool', 16, 1, NULL),
(0, 'o_deposit', 3, '20', NULL, 'float', 17, 1, NULL),
(0, 'o_deposit_type', 3, 'amount|percent::percent', 'Amount|Percent', 'enum', NULL, 0, NULL),
(0, 'o_tax', 3, '10', NULL, 'float', 18, 1, NULL),
(0, 'o_thankyou_page', 3, 'http://www.phpjabbers.com/', NULL, 'string', 19, 1, NULL),
(0, 'o_cancel_page', 3, 'http://www.phpjabbers.com/', NULL, 'string', 20, 1, NULL),

(0, 'o_bf_name', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 1, 1, NULL),
(0, 'o_bf_email', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 2, 1, NULL),
(0, 'o_bf_phone', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 3, 1, NULL),
(0, 'o_bf_notes', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 4, 1, NULL),
(0, 'o_bf_country', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 5, 1, NULL),
(0, 'o_bf_state', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 6, 1, NULL),
(0, 'o_bf_city', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 7, 1, NULL),
(0, 'o_bf_zip', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 8, 1, NULL),
(0, 'o_bf_address_1', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 9, 1, NULL),
(0, 'o_bf_address_2', 4, '1|2|3::3', 'No|Yes|Yes (Required)', 'enum', 10, 1, NULL),
(0, 'o_bf_captcha', 4, '1|3::3', 'No|Yes (Required)', 'enum', 11, 1, NULL),
(0, 'o_bf_terms', 4, '1|3::3', 'No|Yes (Required)', 'enum', 12, 1, NULL),

(0, 'o_allow_cash', 7, '1|0::1', NULL, 'bool', 24, 1, NULL),
(0, 'o_allow_bank', 7, '1|0::0', NULL, 'bool', 25, 1, NULL),
(0, 'o_bank_account', 7, 'Bank of America', NULL, 'text', 26, 1, NULL);

INSERT INTO `ts_booking_plugin_base_cron_jobs` (`name`, `controller`, `action`, `interval`, `period`, `is_active`) VALUES
('Send Email and SMS reminders', 'pjCron', 'pjActionIndex', 10, 'minute', 1);

INSERT INTO `ts_booking_plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`) VALUES
(NULL, 0, 'pjCalendar', 1, 'title', 'Initial Calendar', 'data'),
(NULL, 0, 'pjCalendar', 1, 'terms_url', 'http://www.google.com/', 'data'),
(NULL, 0, 'pjCalendar', 1, 'terms_body', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque consectetur sollicitudin mi. Cras scelerisque lorem non nunc iaculis lacinia id nec mauris. Nunc suscipit tincidunt velit, et gravida enim blandit nec. Cras pellentesque blandit interdum. Ut porttitor risus felis. Donec vestibulum risus neque, in auctor tortor tincidunt at. Suspendisse varius metus dui, at consectetur turpis lobortis sit amet. Donec eget accumsan sapien. Proin posuere nibh et bibendum consectetur. Integer imperdiet nibh est.', 'data'),
(NULL, 0, 'pjCalendar', 1, 'confirm_subject_client', 'Client confirmation email', 'data'),
(NULL, 0, 'pjCalendar', 1, 'confirm_tokens_client', 'Confirmation email<br/>---------------------------<br/>{Name} - Customer name;<br/>{Phone} - Customer phone number;<br/>{Email} - Customer e-mail address;<br/>{BookingID} - Booking ID;<br/><br/>{Price} - Price for selected slots;<br/>{Deposit} - Deposit amount;<br/>{Tax} - Tax amount;<br/>{Total} - Total amount;<br/><br/>{CancelURL} ', 'data'),
(NULL, 0, 'pjCalendar', 1, 'payment_subject_client', 'Client payment email', 'data'),
(NULL, 0, 'pjCalendar', 1, 'payment_tokens_client', 'Payment email<br/>---------------------------<br/>{Name} - Customer name;<br/>{Phone} - Customer phone number;<br/>{Email} - Customer e-mail address;<br/>{BookingID} - Booking ID;<br/><br/>{Price} - Price for selected slots;<br/>{Deposit} - Deposit amount;<br/>{Tax} - Tax amount;<br/>{Total} - Total amount;', 'data'),
(NULL, 0, 'pjCalendar', 1, 'confirm_subject_admin', 'Admin confirmation email', 'data'),
(NULL, 0, 'pjCalendar', 1, 'confirm_tokens_admin', 'Confirmation email<br/>---------------------------<br/>{Name} - Customer name;<br/>{Phone} - Customer phone number;<br/>{Email} - Customer e-mail address;<br/>{BookingID} - Booking ID;<br/><br/>{Price} - Price for selected slots;<br/>{Deposit} - Deposit amount;<br/>{Tax} - Tax amount;<br/>{Total} - Total amount;', 'data'),
(NULL, 0, 'pjCalendar', 1, 'payment_subject_admin', 'Admin payment email', 'data'),
(NULL, 0, 'pjCalendar', 1, 'payment_tokens_admin', 'Payment email<br/>---------------------------<br/>{Name} - Customer name;<br/>{Phone} - Customer phone number;<br/>{Email} - Customer e-mail address;<br/>{BookingID} - Booking ID;<br/><br/>{Price} - Price for selected slots;<br/>{Deposit} - Deposit amount;<br/>{Tax} - Tax amount;<br/>{Total} - Total amount;', 'data'),
(NULL, 0, 'pjCalendar', 1, 'reminder_subject_client', 'Booking Reminder', 'data'),
(NULL, 0, 'pjCalendar', 1, 'reminder_tokens_client', '{Name} - Customer name;<br/>{Phone} - Customer phone number;<br/>{Email} - Customer e-mail address;<br/>{BookingID} - Booking ID;<br/><br/>{Price} - Price for selected slots;<br/>{Deposit} - Deposit amount;<br/>{Tax} - Tax amount;<br/>{Total} - Total amount;<br/><br/>{CancelURL} ', 'data'),
(NULL, 0, 'pjCalendar', 1, 'confirm_sms_admin', 'Booking received', 'data'),
(NULL, 0, 'pjCalendar', 1, 'payment_sms_admin', 'Payment received', 'data'),
(NULL, 0, 'pjCalendar', 1, 'reminder_sms_client', '{Name}, your booking is coming.', 'data');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'user', 'backend', 'Username', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Username', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pass', 'backend', 'Password', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'email', 'backend', 'E-Mail', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'url', 'backend', 'URL', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'URL', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'created', 'backend', 'Created', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'DateTime', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSave', 'backend', 'Save', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Save', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnReset', 'backend', 'Reset', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Reset', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'addLocale', 'backend', 'Add language', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add language', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuLang', 'backend', 'Menu Multi lang', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Multi Lang', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuPlugins', 'backend', 'Menu Plugins', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Plugins', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuUsers', 'backend', 'Menu Users', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuOptions', 'backend', 'Menu Options', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuLogout', 'backend', 'Menu Logout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Logout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnUpdate', 'backend', 'Update', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblChoose', 'backend', 'Choose', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSearch', 'backend', 'Search', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Search', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'backend', 'backend', 'Backend titles', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Back-end titles', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'frontend', 'backend', 'Front-end titles', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Front-end titles', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'locales', 'backend', 'Languages', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'adminLogin', 'backend', 'Admin Login', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin Login', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnLogin', 'backend', 'Login', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Login', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuDashboard', 'backend', 'Menu Dashboard', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dashboard', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionList', 'backend', 'Option list', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Option list', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAdd', 'backend', 'Button Add', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add +', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblDelete', 'backend', 'Delete', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblType', 'backend', 'Type', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Type', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblName', 'backend', 'Name', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblRole', 'backend', 'Role', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Role', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblStatus', 'backend', 'Status', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Status', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblIsActive', 'backend', 'Is Active', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Is confirmed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblUpdateUser', 'backend', 'Update user', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAddUser', 'backend', 'Add user', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblValue', 'backend', 'Value', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Value', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOption', 'backend', 'Option', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Option', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblDays', 'backend', 'Days', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'days', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuLocales', 'backend', 'Menu Languages', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblYes', 'backend', 'Yes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNo', 'backend', 'No', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblError', 'backend', 'Error', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Error', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnBack', 'backend', 'Button Back', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '&laquo; Back', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnCancel', 'backend', 'Button Cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblForgot', 'backend', 'Forgot password', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Forgot password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'adminForgot', 'backend', 'Forgot password', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSend', 'backend', 'Button Send', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'emailForgotSubject', 'backend', 'Email / Forgot Subject', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'emailForgotBody', 'backend', 'Email / Forgot Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dear {Name},Your password: {Password}', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuProfile', 'backend', 'Menu Profile', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesTitle', 'backend', 'Infobox / Locales Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesBody', 'backend', 'Infobox / Locales Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesBackendTitle', 'backend', 'Infobox / Locales Backend Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Backend Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesBackendBody', 'backend', 'Infobox / Locales Backend Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Backend Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesFrontendTitle', 'backend', 'Infobox / Locales Frontend Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Frontend Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesFrontendBody', 'backend', 'Infobox / Locales Frontend Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Frontend Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingPricesTitle', 'backend', 'Infobox / Listing Prices Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Prices Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingPricesBody', 'backend', 'Infobox / Listing Prices Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Prices Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingBookingsTitle', 'backend', 'Infobox / Listing Bookings Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Bookings Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingBookingsBody', 'backend', 'Infobox / Listing Bookings Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Bookings Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingContactTitle', 'backend', 'Infobox / Listing Contact Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Contact Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingContactBody', 'backend', 'Infobox / Listing Contact Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Contact Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingAddressTitle', 'backend', 'Infobox / Listing Address Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Address Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingAddressBody', 'backend', 'Infobox / Listing Address Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Listing Address Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingExtendTitle', 'backend', 'Infobox / Extend exp.date Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Extend exp.date Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoListingExtendBody', 'backend', 'Infobox / Extend exp.date Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Extend exp.date Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuBackup', 'backend', 'Menu Backup', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnBackup', 'backend', 'Button Backup', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblBackupDatabase', 'backend', 'Backup / Database', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup database', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblBackupFiles', 'backend', 'Backup / Files', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup files', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridChooseAction', 'backend', 'Grid / Choose Action', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose Action', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridGotoPage', 'backend', 'Grid / Go to page', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Go to page:', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridTotalItems', 'backend', 'Grid / Total items', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total items:', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridItemsPerPage', 'backend', 'Grid / Items per page', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Items per page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridPrevPage', 'backend', 'Grid / Prev page', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Prev page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridPrev', 'backend', 'Grid / Prev', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '&laquo; Prev', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridNextPage', 'backend', 'Grid / Next page', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridNext', 'backend', 'Grid / Next', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next &raquo;', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridDeleteConfirmation', 'backend', 'Grid / Delete confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridConfirmationTitle', 'backend', 'Grid / Confirmation Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure you want to delete selected record?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridActionTitle', 'backend', 'Grid / Action Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Action confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridBtnOk', 'backend', 'Grid / Button OK', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'OK', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridBtnCancel', 'backend', 'Grid / Button Cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridBtnDelete', 'backend', 'Grid / Button Delete', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'gridEmptyResult', 'backend', 'Grid / Empty resultset', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No records found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'multilangTooltip', 'backend', 'MultiLang / Tooltip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblIp', 'backend', 'IP address', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'IP address', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblUserCreated', 'backend', 'User / Registration Date & Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Registration date/time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_currency', 'backend', 'Options / Currency', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Currency', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_date_format', 'backend', 'Options / Date format', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date format', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_timezone', 'backend', 'Options / Timezone', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Timezone', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_week_start', 'backend', 'Options / First day of the week', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'First day of the week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'u_statarr_ARRAY_T', 'arrays', 'u_statarr_ARRAY_T', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Active', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'u_statarr_ARRAY_F', 'arrays', 'u_statarr_ARRAY_F', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Inactive', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'filter_ARRAY_active', 'arrays', 'filter_ARRAY_active', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Active', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'filter_ARRAY_inactive', 'arrays', 'filter_ARRAY_inactive', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Inactive', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, '_yesno_ARRAY_T', 'arrays', '_yesno_ARRAY_T', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, '_yesno_ARRAY_F', 'arrays', '_yesno_ARRAY_F', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_mr', 'arrays', 'personal_titles_ARRAY_mr', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mr.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_mrs', 'arrays', 'personal_titles_ARRAY_mrs', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mrs.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_miss', 'arrays', 'personal_titles_ARRAY_miss', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Miss', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_ms', 'arrays', 'personal_titles_ARRAY_ms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Ms.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_dr', 'arrays', 'personal_titles_ARRAY_dr', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dr.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_prof', 'arrays', 'personal_titles_ARRAY_prof', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Prof.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_rev', 'arrays', 'personal_titles_ARRAY_rev', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Rev.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'personal_titles_ARRAY_other', 'arrays', 'personal_titles_ARRAY_other', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Other', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-43200', 'arrays', 'timezones_ARRAY_-43200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-12:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-39600', 'arrays', 'timezones_ARRAY_-39600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-11:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-36000', 'arrays', 'timezones_ARRAY_-36000', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-10:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-32400', 'arrays', 'timezones_ARRAY_-32400', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-09:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-28800', 'arrays', 'timezones_ARRAY_-28800', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-08:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-25200', 'arrays', 'timezones_ARRAY_-25200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-07:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-21600', 'arrays', 'timezones_ARRAY_-21600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-06:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-18000', 'arrays', 'timezones_ARRAY_-18000', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-05:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-14400', 'arrays', 'timezones_ARRAY_-14400', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-04:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-10800', 'arrays', 'timezones_ARRAY_-10800', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-03:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-7200', 'arrays', 'timezones_ARRAY_-7200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-02:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_-3600', 'arrays', 'timezones_ARRAY_-3600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT-01:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_0', 'arrays', 'timezones_ARRAY_0', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_3600', 'arrays', 'timezones_ARRAY_3600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+01:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_7200', 'arrays', 'timezones_ARRAY_7200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+02:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_10800', 'arrays', 'timezones_ARRAY_10800', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+03:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_14400', 'arrays', 'timezones_ARRAY_14400', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+04:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_18000', 'arrays', 'timezones_ARRAY_18000', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+05:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_21600', 'arrays', 'timezones_ARRAY_21600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+06:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_25200', 'arrays', 'timezones_ARRAY_25200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+07:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_28800', 'arrays', 'timezones_ARRAY_28800', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+08:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_32400', 'arrays', 'timezones_ARRAY_32400', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+09:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_36000', 'arrays', 'timezones_ARRAY_36000', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+10:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_39600', 'arrays', 'timezones_ARRAY_39600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+11:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_43200', 'arrays', 'timezones_ARRAY_43200', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+12:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'timezones_ARRAY_46800', 'arrays', 'timezones_ARRAY_46800', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'GMT+13:00', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU01', 'arrays', 'error_titles_ARRAY_AU01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU03', 'arrays', 'error_titles_ARRAY_AU03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User added!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU04', 'arrays', 'error_titles_ARRAY_AU04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User failed to add.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU08', 'arrays', 'error_titles_ARRAY_AU08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User not found.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO01', 'arrays', 'error_titles_ARRAY_AO01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Options updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB01', 'arrays', 'error_titles_ARRAY_AB01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB02', 'arrays', 'error_titles_ARRAY_AB02', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup complete!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB03', 'arrays', 'error_titles_ARRAY_AB03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup failed!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AB04', 'arrays', 'error_titles_ARRAY_AB04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup failed!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA10', 'arrays', 'error_titles_ARRAY_AA10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Account not found!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA11', 'arrays', 'error_titles_ARRAY_AA11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password send!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA12', 'arrays', 'error_titles_ARRAY_AA12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password not send!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA13', 'arrays', 'error_titles_ARRAY_AA13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU01', 'arrays', 'error_bodies_ARRAY_AU01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to this user have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU03', 'arrays', 'error_bodies_ARRAY_AU03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to this user have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU04', 'arrays', 'error_bodies_ARRAY_AU04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry, but the user has not been added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU08', 'arrays', 'error_bodies_ARRAY_AU08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'User your looking for is missing.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO01', 'arrays', 'error_bodies_ARRAY_AO01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to options have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ALC01', 'arrays', 'error_bodies_ARRAY_ALC01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to titles have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB01', 'arrays', 'error_bodies_ARRAY_AB01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc at ligula non arcu dignissim pretium. Praesent in magna nulla, in porta leo.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB02', 'arrays', 'error_bodies_ARRAY_AB02', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All backup files have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB03', 'arrays', 'error_bodies_ARRAY_AB03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No option was selected.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AB04', 'arrays', 'error_bodies_ARRAY_AB04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Backup not performed.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA10', 'arrays', 'error_bodies_ARRAY_AA10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Given email address is not associated with any account.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA11', 'arrays', 'error_bodies_ARRAY_AA11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'For further instructions please check your mailbox.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA12', 'arrays', 'error_bodies_ARRAY_AA12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We''re sorry, please try again later.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA13', 'arrays', 'error_bodies_ARRAY_AA13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to your profile have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_1', 'arrays', 'months_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'January', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_2', 'arrays', 'months_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'February', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_3', 'arrays', 'months_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'March', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_4', 'arrays', 'months_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'April', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_5', 'arrays', 'months_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'May', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_6', 'arrays', 'months_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'June', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_7', 'arrays', 'months_ARRAY_7', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'July', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_8', 'arrays', 'months_ARRAY_8', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'August', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_9', 'arrays', 'months_ARRAY_9', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'September', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_10', 'arrays', 'months_ARRAY_10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'October', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_11', 'arrays', 'months_ARRAY_11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'November', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'months_ARRAY_12', 'arrays', 'months_ARRAY_12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'December', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_0', 'arrays', 'days_ARRAY_0', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sunday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_1', 'arrays', 'days_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Monday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_2', 'arrays', 'days_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tuesday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_3', 'arrays', 'days_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Wednesday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_4', 'arrays', 'days_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Thursday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_5', 'arrays', 'days_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Friday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_ARRAY_6', 'arrays', 'days_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Saturday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_0', 'arrays', 'day_names_ARRAY_0', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'S', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_1', 'arrays', 'day_names_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'M', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_2', 'arrays', 'day_names_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'T', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_3', 'arrays', 'day_names_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'W', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_4', 'arrays', 'day_names_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'T', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_5', 'arrays', 'day_names_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'F', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_names_ARRAY_6', 'arrays', 'day_names_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'S', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_1', 'arrays', 'short_months_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Jan', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_2', 'arrays', 'short_months_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Feb', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_3', 'arrays', 'short_months_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_4', 'arrays', 'short_months_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Apr', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_5', 'arrays', 'short_months_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'May', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_6', 'arrays', 'short_months_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Jun', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_7', 'arrays', 'short_months_ARRAY_7', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Jul', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_8', 'arrays', 'short_months_ARRAY_8', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Aug', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_9', 'arrays', 'short_months_ARRAY_9', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sep', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_10', 'arrays', 'short_months_ARRAY_10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Oct', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_11', 'arrays', 'short_months_ARRAY_11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Nov', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_months_ARRAY_12', 'arrays', 'short_months_ARRAY_12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dec', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_1', 'arrays', 'status_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You are not loged in.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_2', 'arrays', 'status_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Access denied. You have not requisite rights to.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_3', 'arrays', 'status_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Empty resultset.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_7', 'arrays', 'status_ARRAY_7', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The operation is not allowed in demo mode.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_123', 'arrays', 'status_ARRAY_123', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your hosting account does not allow uploading such a large image.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_999', 'arrays', 'status_ARRAY_999', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No permisions to edit the property', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_998', 'arrays', 'status_ARRAY_998', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No permisions to edit the reservation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_997', 'arrays', 'status_ARRAY_997', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No reservation found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_996', 'arrays', 'status_ARRAY_996', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No property for the reservation found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9999', 'arrays', 'status_ARRAY_9999', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your registration was successfull.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9998', 'arrays', 'status_ARRAY_9998', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your registration was successfull. Your account needs to be approved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'status_ARRAY_9997', 'arrays', 'status_ARRAY_9997', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'E-Mail address already exist', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_1', 'arrays', 'login_err_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Wrong username or password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_2', 'arrays', 'login_err_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Access denied', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'login_err_ARRAY_3', 'arrays', 'login_err_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Account is disabled', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'localeArrays', 'backend', 'Locale / Arrays titles', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Arrays titles', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesArraysTitle', 'backend', 'Locale / Languages Array Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Arrays Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoLocalesArraysBody', 'backend', 'Locale / Languages Array Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Languages Array Body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lnkBack', 'backend', 'Link Back', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Back', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'locale_order', 'backend', 'Locale / Order', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Order', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'locale_is_default', 'backend', 'Locale / Is default', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Is default', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'locale_flag', 'backend', 'Locale / Flag', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Flag', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'locale_title', 'backend', 'Locale / Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnDelete', 'backend', 'Button Delete', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnContinue', 'backend', 'Button Continue', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Continue', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'vr_email_taken', 'backend', 'Users / Email already taken', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email address is already in use', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'revert_status', 'backend', 'Revert status', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Revert status', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblExport', 'backend', 'Export', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_send_email', 'backend', 'opt_o_send_email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_host', 'backend', 'opt_o_smtp_host', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMTP Host', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_port', 'backend', 'opt_o_smtp_port', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMTP Port', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_user', 'backend', 'opt_o_smtp_user', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMTP Username', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_smtp_pass', 'backend', 'opt_o_smtp_pass', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMTP Password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAll', 'backend', 'All', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'delete_selected', 'backend', 'Delete selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'delete_confirmation', 'backend', 'Delete confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure you want to delete selected records?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuInstall', 'backend', 'Menu Install', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuPreview', 'backend', 'Menu Preview', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuCalendars', 'backend', 'Menu Calendars', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuBookings', 'backend', 'Menu Bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuTime', 'backend', 'Menu Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time & Prices', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuSeo', 'backend', 'Menu SEO', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SEO', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallSeo_1', 'backend', 'Install / SEO Step 1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Step 1. Enter the URL for the webpage where your calendar is', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallSeo_2', 'backend', 'Install / SEO Step 2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Step 2. Put the meta tag below between &lt;head&gt; and &lt;/head&gt; tags on your web page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallSeo_3', 'backend', 'Install / SEO Step 3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Step 3. Create .htaccess file (or update existing one) in the folder where your web page is and put the data below in it', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallJs1_title', 'backend', 'Install / Title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install instructions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallJs1_body', 'backend', 'Install / Body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set different options for the front end calendar, then copy the code below and put it on the web page where you want the calendar to appear. ', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SEO Optimization', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO30', 'arrays', 'error_titles_ARRAY_AO30', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'To better optimize your time slots calendar please follow the steps below', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'calendar_create', 'backend', 'Calendar / Add calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'calendar_update', 'backend', 'Calendar / Update calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'calendar_user', 'backend', 'Calendar / Owner', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Owner', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'calendar_title', 'backend', 'Calendar / Calendar title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC01', 'arrays', 'error_titles_ARRAY_AC01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC03', 'arrays', 'error_titles_ARRAY_AC03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar added!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC04', 'arrays', 'error_titles_ARRAY_AC04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar failed to add.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC08', 'arrays', 'error_titles_ARRAY_AC08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar not found.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC01', 'arrays', 'error_bodies_ARRAY_AC01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to this calendar have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC03', 'arrays', 'error_bodies_ARRAY_AC03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to this calendar have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC04', 'arrays', 'error_bodies_ARRAY_AC04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry, but the calendar has not been added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC08', 'arrays', 'error_bodies_ARRAY_AC08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sorry but the calendar your''re looking for is missing.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC10', 'arrays', 'error_titles_ARRAY_AC10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar list', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC10', 'arrays', 'error_bodies_ARRAY_AC10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Below you can find a list with your calendars.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC11', 'arrays', 'error_bodies_ARRAY_AC11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to add calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC11', 'arrays', 'error_titles_ARRAY_AC11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC12', 'arrays', 'error_bodies_ARRAY_AC12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to update calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC12', 'arrays', 'error_titles_ARRAY_AC12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'calendar_view', 'backend', 'Calendar / View calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_confirmed', 'arrays', 'Bookings / Status: confirmed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirmed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_pending', 'arrays', 'Bookings / Status: pending', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Pending', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_statuses_ARRAY_cancelled', 'arrays', 'Bookings / Status: cancelled', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancelled', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_add', 'backend', 'Bookings / Add booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_update_custom', 'backend', 'Working Time / Update custom', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update custom', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_default', 'backend', 'Working Time / Default', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Default', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_custom', 'backend', 'Working Time / Custom', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_day', 'backend', 'Working Time / Day of week', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Day of week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_from', 'backend', 'Working Time / Start Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_to', 'backend', 'Working Time / End Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_is', 'backend', 'Working Time / Is Day off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Is Day off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_date', 'backend', 'Working Time / Date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_default_wt', 'backend', 'Working Time / Default Working Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Default Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_custom_wt', 'backend', 'Working Time / Custom Working Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_lunch_from', 'backend', 'Working Time / Lunch from', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch from', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_lunch_to', 'backend', 'Working Time / Lunch to', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch to', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AT04', 'arrays', 'error_bodies_ARRAY_AT04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Below you can set available booking times for each day of the week. You can also set "Lunch break" during the day, price for each slot, slot length and number of bookings that can be made for each slot. Under "Custom" tab you can configure these options for any particular date.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AT01', 'arrays', 'error_titles_ARRAY_AT01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working Time updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AT01', 'arrays', 'error_bodies_ARRAY_AT01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to working time have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AT02', 'arrays', 'error_titles_ARRAY_AT02', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Working Time saved!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AT02', 'arrays', 'error_bodies_ARRAY_AT02', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to custom working time have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AT03', 'arrays', 'error_titles_ARRAY_AT03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Working Time updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AT03', 'arrays', 'error_bodies_ARRAY_AT03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to custom working time have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AT04', 'arrays', 'error_titles_ARRAY_AT04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_10', 'arrays', 'time_slot_length_ARRAY_10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '10 minutes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_15', 'arrays', 'time_slot_length_ARRAY_15', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '15 minutes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_30', 'arrays', 'time_slot_length_ARRAY_30', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '30 minutes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_60', 'arrays', 'time_slot_length_ARRAY_60', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '1 hour', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_120', 'arrays', 'time_slot_length_ARRAY_120', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '2 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_180', 'arrays', 'time_slot_length_ARRAY_180', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '3 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_240', 'arrays', 'time_slot_length_ARRAY_240', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '4 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_300', 'arrays', 'time_slot_length_ARRAY_300', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '5 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_360', 'arrays', 'time_slot_length_ARRAY_360', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '6 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_420', 'arrays', 'time_slot_length_ARRAY_420', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '7 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_480', 'arrays', 'time_slot_length_ARRAY_480', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '8 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_540', 'arrays', 'time_slot_length_ARRAY_540', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '9 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_600', 'arrays', 'time_slot_length_ARRAY_600', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '10 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_660', 'arrays', 'time_slot_length_ARRAY_660', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '11 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_slot_length_ARRAY_720', 'arrays', 'time_slot_length_ARRAY_720', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '12 hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_limit', 'backend', 'Working Time / Bookings per slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings per slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_length', 'backend', 'Working Time / Slot length', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot length', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_price', 'backend', 'Working Time / Price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_single_price', 'backend', 'Working Time / Single price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use the same price for all slots during the day', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_dp_title', 'backend', 'Working Time / Set custom prices', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set custom prices', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_time_format', 'backend', 'Options / Time format', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time format', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuGeneral', 'backend', 'Menu General', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'General', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuPayments', 'backend', 'Menu Payments', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payments', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuReminder', 'backend', 'Menu Reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuBookingForm', 'backend', 'Menu Booking form', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuTerms', 'backend', 'Menu Terms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuNotifications', 'backend', 'Menu Notifications', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO21', 'arrays', 'error_bodies_ARRAY_AO21', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set-up general settings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO23', 'arrays', 'error_bodies_ARRAY_AO23', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set-up booking options.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO24', 'arrays', 'error_bodies_ARRAY_AO24', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose the fields that should be available on the booking form.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO25', 'arrays', 'error_bodies_ARRAY_AO25', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email notifications will be sent to people who make a reservation after reservation form is completed or/and payment is made. Suppored tokens to be used in the email are:<br /><br /><table style="width:100%"><tr><td style="width:50%">{Name} - The customer''s name;</td><td>{CCType} - The provided CC type;</td></tr><tr><td>{Email} - The customer''s e-mail;</td><td>{CCNum} - The provided CC number;</td></tr><tr><td>{Phone} - The customer''s phone number;</td><td>{CCExpMonth} - The provided CC exp.month;</td></tr><tr><td>{Notes} - Any additional notes;</td><td>{CCExpYear} - The provided CC exp.year;</td></tr><tr><td>{Country} - The provided country;</td><td>{CCSec} - The provided CC sec. code;</td></tr><tr><td>{State} - The provided state;</td><td>{PaymentMethod} - The payment method;</td></tr><tr><td>{City} - The provided city;</td><td>{Deposit} - Deposit;</td></tr><tr><td>{Zip} - The provided zip code;</td><td>{Tax} - Tax;</td></tr><tr><td>{Address1} - The provided address 1;</td><td>{Total} - Total amount;</td></tr><tr><td>{Address2} - The provided address 2;</td><td>{Price} - Price;</td></tr><tr><td>{CalendarName} - Calendar name</td><td>{BookingID} - Booking''s ID;</td></tr><tr><td></td><td>{Timeslots} - Booking''s timeslots</td></tr><tr><td></td><td>{CancelURL} - Cancel link for the booking</td></tr></table>', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO26', 'arrays', 'error_bodies_ARRAY_AO26', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Enter booking terms and conditions. You can also include a link to external web page where your terms and conditions page is.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO27', 'arrays', 'error_bodies_ARRAY_AO27', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Configure booking payment options.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO28', 'arrays', 'error_bodies_ARRAY_AO28', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set-up reminder options.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO21', 'arrays', 'error_titles_ARRAY_AO21', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'General options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO23', 'arrays', 'error_titles_ARRAY_AO23', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO24', 'arrays', 'error_titles_ARRAY_AO24', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO25', 'arrays', 'error_titles_ARRAY_AO25', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO26', 'arrays', 'error_titles_ARRAY_AO26', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO27', 'arrays', 'error_titles_ARRAY_AO27', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO28', 'arrays', 'error_titles_ARRAY_AO28', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Reminder options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_address_1', 'backend', 'Options / Address 1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address 1', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_address_2', 'backend', 'Options / Address 2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address 2', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_captcha', 'backend', 'Options / Captcha', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Captcha', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_city', 'backend', 'Options / City', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_country', 'backend', 'Options / Country', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Country', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_email', 'backend', 'Options / Email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_name', 'backend', 'Options / Name', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_notes', 'backend', 'Options / Notes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_phone', 'backend', 'Options / Phone ', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_state', 'backend', 'Options / State', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'State', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_terms', 'backend', 'Options / Terms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bf_zip', 'backend', 'Options / Zip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Zip', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionsTermsContent', 'backend', 'Options / Booking terms content', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking terms content', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionsTermsURL', 'backend', 'Options / Booking terms URL', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking terms URL', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_allow_authorize', 'backend', 'Options / Allow Authorize.net', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Allow payments with Authorize.net', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_allow_bank', 'backend', 'Options / Allow Bank', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Provide Bank account details for wire transfers', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_allow_creditcard', 'backend', 'Options / Allow Credit Card', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Collect Credit Card details for offline processing', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_allow_paypal', 'backend', 'Options / Allow Paypal', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Allow payments with PayPal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_hash', 'backend', 'Options / Authorize.net hash value', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authorize.net hash value', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_key', 'backend', 'Options / Authorize.net transaction key', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authorize.net transaction key', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_mid', 'backend', 'Options / Authorize.net merchant ID', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authorize.net merchant ID', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_authorize_tz', 'backend', 'Options / Authorize.net Time zone', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Authorize.net time zone', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_bank_account', 'backend', 'Options / Bank account', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bank account', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_paypal_address', 'backend', 'Options / Paypal address', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Paypal address', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_deposit', 'backend', 'Options / Deposit', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set deposit to be collected for each booking. You can set a fixed fee or a percent from booking total amount. You can set it to 100 Percent if you want to collect full payment for each booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_tax', 'backend', 'Options / Tax payment', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tax amount to be collected for each booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_thankyou_page', 'backend', 'Options / "Thank you" page location', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'URL for the web page where your clients will be redirected after PayPal or Authorize.net payment', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_disable_payments', 'backend', 'Options / Disable payments', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Disable payments and only accept bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_reminder_enable', 'backend', 'Options / Enable notifications', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Enable reminders', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_reminder_email_before', 'backend', 'Options / Send email reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send an email reminder X hour(s) before booking time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_reminder_sms_hours', 'backend', 'Options / Send SMS reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send SMS reminder X hour(s) before booking time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_admin_confirmation', 'backend', 'Confirmation / Admin confirmation title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin - booking confirmation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_admin_payment', 'backend', 'Confirmation / Admin payment title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin - payment confirmation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_client_confirmation', 'backend', 'Confirmation / Client confirmation title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client - booking confirmation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_client_payment', 'backend', 'Confirmation / Client payment title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client - payment confirmation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_subject', 'backend', 'Confirmation / Email subject', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Subject', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_body', 'backend', 'Confirmation / Email body', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email body', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabEmails', 'backend', 'Tab Emails', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Emails', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabSms', 'backend', 'Tab SMS', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirm_sms_admin', 'backend', 'Confirmation / Admin - order confirmation sms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin - order confirmation sms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_sms_admin', 'backend', 'Confirmation / Admin - payment confirmation sms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin - payment confirmation sms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'sms_body', 'backend', 'Confirmation / SMS content', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS content', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_client_reminder', 'backend', 'Confirmation / Client reminder title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client - booking reminder email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'reminder_sms_client', 'backend', 'Confirmation / Client - booking reminder sms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client - booking reminder sms', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO06', 'arrays', 'error_bodies_ARRAY_AO06', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to terms have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO06', 'arrays', 'error_titles_ARRAY_AO06', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO03', 'arrays', 'error_bodies_ARRAY_AO03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to booking options have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO03', 'arrays', 'error_titles_ARRAY_AO03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking options updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO04', 'arrays', 'error_titles_ARRAY_AO04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking form updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO04', 'arrays', 'error_bodies_ARRAY_AO04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to booking form have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO05', 'arrays', 'error_bodies_ARRAY_AO05', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to confirmation have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO05', 'arrays', 'error_titles_ARRAY_AO05', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirmation updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO08', 'arrays', 'error_bodies_ARRAY_AO08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to reminder have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO08', 'arrays', 'error_titles_ARRAY_AO08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Reminder updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO07', 'arrays', 'error_bodies_ARRAY_AO07', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All the changes made to payment options have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO07', 'arrays', 'error_titles_ARRAY_AO07', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment options updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_status_if_not_paid', 'backend', 'Options / Default status for booked dates if not paid', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All bookings which are only made but NOT paid will be set with the following status:', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_status_if_paid', 'backend', 'Options / Default status for booked dates if paid', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All bookings which are made and paid will be set with the following status', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_hide_prices', 'backend', 'Options / Hide prices', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Hide prices', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_show_legend', 'backend', 'Options / Show legend', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Show color legend below calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_booking_form_position', 'backend', 'Options / Booking form position', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Timeslot details and booking form position', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_uuid', 'backend', 'Bookings / Unique ID', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Unique ID', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_total', 'backend', 'Bookings / Total', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_status', 'backend', 'Bookings / Status', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Status', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_query', 'backend', 'Bookings / Query', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Keyword', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_tab_details', 'backend', 'Bookings / Booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_tab_client', 'backend', 'Bookings / Client', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK10', 'arrays', 'error_bodies_ARRAY_ABK10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fill in details about your booking. Once you select the time slots, price for the booking is automatically calculated. You can add information about your client under the Client tab.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK10', 'arrays', 'error_titles_ARRAY_ABK10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK11', 'arrays', 'error_titles_ARRAY_ABK11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK11', 'arrays', 'error_bodies_ARRAY_ABK11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to enter details about your client.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_payment_method', 'backend', 'Bookings / Payment method', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment method', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_tax', 'backend', 'Bookings / Tax', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tax', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_exp', 'backend', 'Bookings / CC Exp.date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Exp.date', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_code', 'backend', 'Bookings / CC Code', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Code', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_num', 'backend', 'Bookings / CC Number', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Number', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_type', 'backend', 'Bookings / CC Type', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Type', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_types_ARRAY_maestro', 'arrays', 'booking_cc_types_ARRAY_maestro', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Maestro', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_types_ARRAY_amex', 'arrays', 'booking_cc_types_ARRAY_amex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'AmericanExpress', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_types_ARRAY_mastercard', 'arrays', 'booking_cc_types_ARRAY_mastercard', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'MasterCard', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cc_types_ARRAY_visa', 'arrays', 'booking_cc_types_ARRAY_visa', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Visa', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_phone', 'backend', 'Bookings / Phone', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Phone', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_email', 'backend', 'Bookings / Email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_customer', 'backend', 'Bookings / Customer details', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Customer details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_notes', 'backend', 'Bookings / Notes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_address_2', 'backend', 'Bookings / Address Line 2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address Line 2', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_address_1', 'backend', 'Bookings / Address Line 1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address Line 1', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_name', 'backend', 'Bookings / Name', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_zip', 'backend', 'Bookings / Zip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Zip', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_city', 'backend', 'Bookings / City', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'City', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_state', 'backend', 'Bookings / State', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'State', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_country', 'backend', 'Bookings / Country', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Country', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_deposit', 'backend', 'Bookings / Deposit', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Deposit', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_price', 'backend', 'Bookings / Price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_choose', 'backend', 'Bookings / Choose', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '-- Choose --', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_bank', 'arrays', 'payment_methods_ARRAY_bank', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bank account', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_paypal', 'arrays', 'payment_methods_ARRAY_paypal', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'PayPal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK03', 'arrays', 'error_titles_ARRAY_ABK03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking added', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK03', 'arrays', 'error_bodies_ARRAY_ABK03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The booking has been successfully added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK08', 'arrays', 'error_titles_ARRAY_ABK08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking not found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK08', 'arrays', 'error_bodies_ARRAY_ABK08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sorry, but the booking you''re looking for is missing.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_update', 'backend', 'Bookings / Update booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK12', 'arrays', 'error_titles_ARRAY_ABK12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update Details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK12', 'arrays', 'error_bodies_ARRAY_ABK12', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'If you do changes to the time slots, price for the booking is automatically recalculated. You can change details about your client under the Client tab. You can also send email and/or sms reminder to your client using the icons next to each booked time slot.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK13', 'arrays', 'error_titles_ARRAY_ABK13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK13', 'arrays', 'error_bodies_ARRAY_ABK13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to enter details about your client.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_datetime_format', 'backend', 'Options / DateTime format', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'DateTime format', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_created', 'backend', 'Bookings / Created', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Created', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_create_invoice', 'backend', 'Bookings / Create Invoice', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Create Invoice', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_invoice_details', 'backend', 'Bookings / Invoice details', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invoice details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_recalc', 'backend', 'Bookings / Recalculate the price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Recalculate the price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slot_add', 'backend', 'Bookings / Add slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots', 'backend', 'Bookings / Slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time Slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_general', 'backend', 'Bookings / Details', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking details', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_from', 'backend', 'Bookings / Date From', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date from', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_to', 'backend', 'Bookings / Date To', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date to', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_show_week_numbers', 'backend', 'Options / Show week numbers', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Show week numbers', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_price', 'frontend', 'Cart / Price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_start_time', 'frontend', 'Cart / Start time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_end_time', 'frontend', 'Cart / End time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_basket', 'frontend', 'Cart / Selected slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Selected slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_chechout', 'frontend', 'Cart / Booking Form', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_summary', 'frontend', 'Cart / Booking Summary', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Summary', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_item_name', 'frontend', 'Cart / Timeslot Booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Timeslot Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_date', 'frontend', 'Cart / Date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_remove', 'frontend', 'Cart / Remove', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Remove', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_empty', 'frontend', 'Cart / No slots selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No slots selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_total', 'frontend', 'Cart / Total', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_passed', 'frontend', 'Cart / passed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'passed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_dayoff', 'frontend', 'Cart / Day off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Day off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_continue', 'frontend', 'Button / Continue', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Continue', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_cancel', 'frontend', 'Button / Cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_book', 'frontend', 'Button / Book', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Book', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_confirm', 'frontend', 'Button / Confirm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirm & Proceed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_proceed', 'frontend', 'Button / Proceed to booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Proceed to booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_layout', 'backend', 'Options / Layout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Layout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_name', 'frontend', 'Validate / Name', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Name is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_phone', 'frontend', 'Validate / Phone', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Phone is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_email', 'frontend', 'Validate / Email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_country', 'frontend', 'Validate / Country', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Country is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_state', 'frontend', 'Validate / State', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'State is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_city', 'frontend', 'Validate / City', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'City is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_zip', 'frontend', 'Validate / Zip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Zip is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_notes', 'frontend', 'Validate / Notes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notes is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_address_1', 'frontend', 'Validate / Address 1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address 1 is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_address_2', 'frontend', 'Validate / Address 2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Address 2 is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_payment_method', 'frontend', 'Validate / Payment method', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment method is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_captcha', 'frontend', 'Validate / Captcha', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Capcha is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_terms', 'frontend', 'Validate / Terms & Conditions', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_email_format', 'frontend', 'Validate / Email invalid', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email is invalid', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_captcha_match', 'frontend', 'Validate / Captcha is wrong', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Capcha is wrong', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_bf_terms', 'frontend', 'Booking form / Terms', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'I agree with {STAG}terms and conditions{ETAG}', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_cc_type', 'frontend', 'Validate / CC Type', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Type is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_cc_num', 'frontend', 'Validate / CC Number', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Number is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_cc_code', 'frontend', 'Validate / CC Code', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Code is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_cc_exp_year', 'frontend', 'Validate / CC Exp.Year', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Exp.Year is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_cc_exp_month', 'frontend', 'Validate / CC Exp.Month', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CC Exp.Month is required', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_price', 'frontend', 'Summary / Price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_tax', 'frontend', 'Summary / Tax', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tax', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_total', 'frontend', 'Summary / Total price', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total price', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_deposit', 'frontend', 'Summary / Deposit', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Deposit', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_close', 'frontend', 'Button / Close', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Close', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_date', 'backend', 'Bookings / Date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_delete_title', 'backend', 'Bookings / Delete booking slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete booking''s slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_add_title', 'backend', 'Bookings / Add booking slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add booking''s slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_email_title', 'backend', 'Bookings / Email reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_sms_title', 'backend', 'Bookings / SMS reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_delete_body', 'backend', 'Bookings / Delete booking slot confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure you want to delete selected slot?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_start_time', 'backend', 'Bookings / Start time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_end_time', 'backend', 'Bookings / End time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_export', 'backend', 'Bookings / Export bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_format_lbl', 'backend', 'Bookings / Format', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Format', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_delimiter_lbl', 'backend', 'Bookings / Delimiter', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delimiter', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_format_ARRAY_csv', 'arrays', 'booking_format_ARRAY_csv', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CSV', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_format_ARRAY_xml', 'arrays', 'booking_format_ARRAY_xml', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'XML', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_format_ARRAY_ical', 'arrays', 'booking_format_ARRAY_ical', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_delimiter_ARRAY_comma', 'arrays', 'booking_delimiter_ARRAY_comma', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Comma', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_delimiter_ARRAY_semicolon', 'arrays', 'booking_delimiter_ARRAY_semicolon', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Semicolon', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_delimiter_ARRAY_tab', 'arrays', 'booking_delimiter_ARRAY_tab', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tab', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_export_title', 'backend', 'Bookings / Export bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_add', 'arrays', 'Button / Add', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_cancel', 'arrays', 'Button / Cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_erase', 'arrays', 'Button / Delete', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_send', 'arrays', 'Button / Send', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_ship', 'arrays', 'Button / Export', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_erase_all', 'arrays', 'Button / Delete All', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete All', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_paypal_submit', 'frontend', 'Frontend / Paypal submit', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Go to PayPal Secure page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_authorize_submit', 'frontend', 'Frontend / Authorize.NET submit', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Go to Authorize.NET Secure page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_status_ARRAY_11', 'arrays', 'front_booking_status_ARRAY_11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please wait while redirect to secure payment processor webpage complete...', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_status_ARRAY_1', 'arrays', 'front_booking_status_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your request has been sent successfully. Thank you.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_status_ARRAY_4', 'arrays', 'front_booking_status_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking not found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_status_ARRAY_3', 'arrays', 'front_booking_status_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The invoice for this booking is already paid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_system_msg', 'frontend', 'Frontend / System message', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'System message', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_na', 'frontend', 'Frontend / Booking not available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking not available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_return_back', 'frontend', 'Frontend / Return back', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Return back', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booking_form', 'frontend', 'Frontend / Booking Form', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_selected_timeslots', 'frontend', 'Frontend / Selected timeslots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Selected timeslots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_select_date', 'frontend', 'Frontend / Select a Date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select a Date', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_select_timeslots', 'backend', 'Frontend / Select Timeslots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select Timeslots on', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_select_payment', 'frontend', 'Frontend / Select Payment method', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select Payment method', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'co_select_country', 'frontend', 'Checkout / Select Country', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select Country', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary', 'frontend', 'Frontend / Summary', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Summary', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_checkout_na', 'frontend', 'Frontend / Checkout form not available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Checkout form not available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_preview_na', 'frontend', 'Frontend / Preview not available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview not available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_preview_form', 'frontend', 'Frontend / Booking Preview', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Preview', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_bank_account', 'backend', 'Frontend / Bank account', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bank account', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_start_over', 'frontend', 'Frontend / Start over', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start over', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'button_ARRAY_save', 'arrays', 'Button / Save', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Save', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_past', 'arrays', 'Legend / Past', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Past', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_today', 'arrays', 'Legend / Today', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_fully', 'arrays', 'Legend / Fully Booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fully Booked', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_partly', 'arrays', 'Legend / Partly Booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Partly Booked', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_dayoff', 'arrays', 'Legend / Day Off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Day Off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_available', 'arrays', 'Legend / Available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'legend_ARRAY_selected', 'arrays', 'Legend / Selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_checkout', 'frontend', 'Button / Checkout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Checkout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_legend', 'frontend', 'Button / Legend', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Legend', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_basket_singular', 'frontend', 'Button / Basket (singular)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Basket (%u slot)', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_basket_plural', 'frontend', 'Button / Basket (plural)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Basket (%u slots)', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_back_calendar', 'frontend', 'Button / Back to calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Back to calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_last_login', 'backend', 'Dashboard / Last login', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last login', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_bookings_today_singular', 'backend', 'Dashboard / Bookings today (singular)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'booking today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_bookings_today_plural', 'backend', 'Dashboard / Bookings today (plural)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'bookings today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_bookings_week_plural', 'backend', 'Dashboard / Bookings this week (plural)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'bookings this week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_bookings_week_singular', 'backend', 'Dashboard / Bookings this week (singular)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'booking this week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_users_singular', 'backend', 'Dashboard / Users (singular)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_users_plural', 'backend', 'Dashboard / Users (plural)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_latest_bookings', 'backend', 'Dashboard / Latest bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Latest bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_upcoming_bookings', 'backend', 'Dashboard / Upcoming bookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Upcoming bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_last_logged_users', 'backend', 'Dashboard / Last logged users', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last logged users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_upcoming_bookings_empty', 'backend', 'Dashboard / Upcoming bookings not found', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Upcoming bookings not found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_last_logged_users_empty', 'backend', 'Dashboard / Last logged users not found', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last logged users not found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dashboard_latest_bookings_empty', 'backend', 'Dashboard / Latest bookings not found', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Latest bookings not found', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_schedule', 'backend', 'Bookings / Schedule', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_schedule_prev', 'backend', 'Bookings / Schedule Prev 5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Prev 5 days', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_schedule_next', 'backend', 'Bookings / Schedule Next 5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next 5 days', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_schedule_print', 'backend', 'Bookings / Schedule Print', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Print', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_terms', 'frontend', 'Frontend / Terms and Conditions', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms and Conditions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_terms_note', 'frontend', 'Frontend / Terms note', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'I have read and agree to the Booking Conditions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_terms_link', 'frontend', 'Frontend / Terms link', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Read terms and conditions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_lunch', 'frontend', 'Cart / Lunch break', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'break', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK14', 'arrays', 'error_titles_ARRAY_ABK14', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK14', 'arrays', 'error_bodies_ARRAY_ABK14', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Below you can see your bookings for the upcoming 5 days. You can print the schedule.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK15', 'arrays', 'error_titles_ARRAY_ABK15', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invalid data', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK15', 'arrays', 'error_bodies_ARRAY_ABK15', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry but the booking form data seems to be invalid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AT05', 'arrays', 'error_titles_ARRAY_AT05', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invalid data', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AT05', 'arrays', 'error_bodies_ARRAY_AT05', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry but the form data seems to be invalid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AC13', 'arrays', 'error_titles_ARRAY_AC13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invalid data', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AC13', 'arrays', 'error_bodies_ARRAY_AC13', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry but the calendar form data seems to be invalid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK01', 'arrays', 'error_titles_ARRAY_ABK01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking updated', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK01', 'arrays', 'error_bodies_ARRAY_ABK01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The booking has been successfully updated.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ABK04', 'arrays', 'error_titles_ARRAY_ABK04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking failed to add', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ABK04', 'arrays', 'error_bodies_ARRAY_ABK04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The booking has not been successfully added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU10', 'arrays', 'error_titles_ARRAY_AU10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add an user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU10', 'arrays', 'error_bodies_ARRAY_AU10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to add an user.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AU11', 'arrays', 'error_titles_ARRAY_AU11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update an user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AU11', 'arrays', 'error_bodies_ARRAY_AU11', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to update selected user.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA14', 'arrays', 'error_titles_ARRAY_AA14', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile update failed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA14', 'arrays', 'error_bodies_ARRAY_AA14', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sorry, but the submitted data are not valid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AA15', 'arrays', 'error_titles_ARRAY_AA15', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile settings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AA15', 'arrays', 'error_bodies_ARRAY_AA15', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use form below to update your profile settings.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_wtime', 'backend', 'Working Time / Working time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_break', 'backend', 'Working Time / Lunch break', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch break', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_from', 'backend', 'Working Time / From', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'From', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_to', 'backend', 'Working Time / To', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'To', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_price', 'backend', 'Working Time / Price per slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Price per slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_length', 'backend', 'Working Time / Slot length', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot length', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_limit', 'backend', 'Working Time / Bookings per slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings per slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_dayoff', 'backend', 'Working Time / Day off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Day off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_tbl_customize', 'backend', 'Working Time / Customize', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'customize...', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_email_reminder', 'backend', 'Bookings / Email reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_sms_reminder', 'backend', 'Bookings / SMS reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_hours_before', 'backend', 'Options / Allow bookings X hours earlier', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Allow new bookings to be made X hours earlier', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_before', 'frontend', 'Cart / Not allowed X hours before', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'not allowed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_cancel_booking', 'frontend', 'Button / Cancel booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cancel_100', 'frontend', 'Cancel / Missing parameters', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Missing or invalid parameters.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cancel_101', 'frontend', 'Cancel / Not found', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking not found.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cancel_102', 'frontend', 'Cancel / Already cancelled', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking is already cancelled.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cancel_103', 'frontend', 'Cancel / Booking cancelled', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking have been cancelled.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cancel_title', 'frontend', 'Cancel / Booking cancellation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking cancellation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_from_email', 'backend', 'Options / From email address', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'From email address', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_confirm', 'arrays', 'receive_emails_ARRAY_confirm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking confirmation email - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_payment', 'arrays', 'receive_emails_ARRAY_payment', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment confirmation email - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_reminder', 'arrays', 'receive_emails_ARRAY_reminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking reminder email - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblReceiveEmails', 'backend', 'Label / Receive emails', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Receive emails', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_select_time', 'frontend', 'Frontend / Select time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_6', 'arrays', 'Days short / Sat', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sat', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_0', 'arrays', 'Days short / Sun', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sun', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_1', 'arrays', 'Days short / Mon', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mon', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_2', 'arrays', 'Days short / Tue', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tue', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_3', 'arrays', 'Days short / Wed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Wed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_4', 'arrays', 'Days short / Thu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Thu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'days_short_ARRAY_5', 'arrays', 'Days short / Fri', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fri', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_1', 'arrays', 'Day suffix / 1st', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'st', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_2', 'arrays', 'Day suffix / 2nd', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'nd', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_3', 'arrays', 'Day suffix / 3rd', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'rd', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_4', 'arrays', 'Day suffix / 4th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_5', 'arrays', 'Day suffix / 5th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_6', 'arrays', 'Day suffix / 6th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_7', 'arrays', 'Day suffix / 7th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_8', 'arrays', 'Day suffix / 8th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_9', 'arrays', 'Day suffix / 9th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_10', 'arrays', 'Day suffix / 10th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_11', 'arrays', 'Day suffix / 11th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_12', 'arrays', 'Day suffix / 12th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_13', 'arrays', 'Day suffix / 13th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_14', 'arrays', 'Day suffix / 14th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_15', 'arrays', 'Day suffix / 15th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_16', 'arrays', 'Day suffix / 16th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_17', 'arrays', 'Day suffix / 17th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_18', 'arrays', 'Day suffix / 18th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_19', 'arrays', 'Day suffix / 19th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_20', 'arrays', 'Day suffix / 20th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_21', 'arrays', 'Day suffix / 21st', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'st', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_22', 'arrays', 'Day suffix / 22nd', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'nd', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_23', 'arrays', 'Day suffix / 23rd', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'rd', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_24', 'arrays', 'Day suffix / 24th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_25', 'arrays', 'Day suffix / 25th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_26', 'arrays', 'Day suffix / 26th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_27', 'arrays', 'Day suffix / 27th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_28', 'arrays', 'Day suffix / 28th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_29', 'arrays', 'Day suffix / 29th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_30', 'arrays', 'Day suffix / 30th', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'day_suffix_ARRAY_31', 'arrays', 'Day suffix / 31st', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'st', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_weekly_available', 'frontend', 'Frontend / Legend / Available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_weekly_booked', 'frontend', 'Frontend / Legend / Booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booked', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_weekly_selected', 'frontend', 'Frontend / Legend / Selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'install_layout', 'backend', 'Install / Layout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Layout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'install_layout_default', 'backend', 'Install / System default', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'As per default options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'install_legend', 'backend', 'Install / Install config', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install config', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'layouts_ARRAY_1', 'arrays', 'Layouts / Default layout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Default layout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'layouts_ARRAY_2', 'arrays', 'Layouts / Weekly layout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Weekly layout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AO10', 'arrays', 'error_bodies_ARRAY_AO10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Weekly view only works if slot length is the same for all days. Change slot length <a href="index.php?controller=pjAdminTime&action=pjActionIndex">here</a>.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AO10', 'arrays', 'error_titles_ARRAY_AO10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Warning', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'install_step_2', 'backend', 'Install / Step 2 (Optional)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '(Optional) If your web page hasn''t yet a valid doctype, copy and paste next line at the very top of your web page.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'install_step_3', 'backend', 'Install / Step 3 (Optional)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '(Optional) If your web page hasn''t yet a viewport meta tag, copy and paste next line in the HEAD part on your web page.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_weekly_length', 'frontend', 'Frontend / Weekly slot length', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We''re sorry, but bookings are not available for selected week.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_cron_title', 'backend', 'Options / Cron script', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cron script', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_cron_info', 'backend', 'Options / Cron script (text)', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You need to set up a cron job using your hosting account control panel which should execute every hour. Depending on your web server you should use either the URL or script path.\r\n<br><br>\r\nServer path:<br><span class="bold">%1$s</span>\r\n<br><br>\r\nURL:<br><span class="bold">%2$s</span>', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_button_back', 'frontend', 'Label / Back to calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Back to calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_allow_cash', 'backend', 'Options / Allow cash payments', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Allow cash payments', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'payment_methods_ARRAY_cash', 'arrays', 'payment_methods_ARRAY_cash', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cash', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_AR21', 'arrays', 'error_titles_ARRAY_AR21', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_AR21', 'arrays', 'error_bodies_ARRAY_AR21', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can export bookings in different formats. You can either download a file with bookings details or use a link for a feed which load all the bookings.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_formats_ARRAY_ical', 'arrays', 'export_formats_ARRAY_ical', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_formats_ARRAY_xml', 'arrays', 'export_formats_ARRAY_xml', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'XML', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_formats_ARRAY_csv', 'arrays', 'export_formats_ARRAY_csv', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'CSV', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_types_ARRAY_file', 'arrays', 'export_types_ARRAY_file', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'File', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_types_ARRAY_feed', 'arrays', 'export_types_ARRAY_feed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Feed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_periods_ARRAY_next', 'arrays', 'export_periods_ARRAY_next', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Coming', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'export_periods_ARRAY_last', 'arrays', 'export_periods_ARRAY_last', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Created or Modified', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblFormat', 'backend', 'Label / Format', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Format', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblEnterPassword', 'backend', 'Label / Enter password', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Enter password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_1', 'arrays', 'coming_arr_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_2', 'arrays', 'coming_arr_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tomorrow', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_3', 'arrays', 'coming_arr_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_4', 'arrays', 'coming_arr_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_5', 'arrays', 'coming_arr_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This month', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'coming_arr_ARRAY_6', 'arrays', 'coming_arr_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next month', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_1', 'arrays', 'made_arr_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_2', 'arrays', 'made_arr_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yesterday', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_3', 'arrays', 'made_arr_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_4', 'arrays', 'made_arr_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_5', 'arrays', 'made_arr_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This month', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'made_arr_ARRAY_6', 'arrays', 'made_arr_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last month', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnExport', 'backend', 'Button / Export', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnGetFeedURL', 'backend', 'Button / Get Feed URL', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Get Feed URL', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNoAccessToFeed', 'backend', 'Label / No access to feed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No access to feed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoBookingsFeedTitle', 'backend', 'Infobox / Bookings Feed URL', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings Feed URL', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoBookingsFeedDesc', 'backend', 'Infobox / Bookings Feed URL', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use the URL below to have access to all bookings. Please, note that if you change the password the URL will change too as password is used in the URL itself so no one else can open it.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'send_sms_title', 'backend', 'Label / Sms title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The following SMS message will be sent to %s.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'send_email_title', 'backend', 'Label / Sms title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The following email message will be sent to %s.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_subject', 'backend', 'Label / Subject', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Subject', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_message', 'backend', 'Label / Message', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Message', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnEmail', 'backend', 'Button / Email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSMS', 'backend', 'Button / SMS', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btniCal', 'backend', 'Button / iCal', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSelectSlotToBook', 'backend', 'Label / Select at lest one slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please select at least one slot to complete the booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblDayOfWeek', 'backend', 'Label / Day of the week', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Day of the week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetAsDayOff', 'backend', 'Label / Set as day off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set as day off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblStartTime', 'backend', 'Label / Start time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNumberOfSlots', 'backend', 'Label / Number of slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Number of slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblFrom', 'backend', 'Label / From', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'from', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblLength', 'backend', 'Label / length', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'length', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblMinutes', 'backend', 'Label / minutes', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'minutes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNone', 'backend', 'Label / none', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'none', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblEndTime', 'backend', 'Label / End time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSlotLimitation', 'backend', 'Label / Slots limitation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can not make slots after {AFTER}. Please select "Start time" or "Slot length" or "Number of slots" again.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'custom_filter_ARRAY_A', 'arrays', 'custom_filter_ARRAY_A', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'custom_filter_ARRAY_T', 'arrays', 'custom_filter_ARRAY_T', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working days', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'custom_filter_ARRAY_F', 'arrays', 'custom_filter_ARRAY_F', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Days off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_1', 'arrays', 'enum_arr_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_2', 'arrays', 'enum_arr_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_3', 'arrays', 'enum_arr_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes (Required)', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_confirmed', 'arrays', 'enum_arr_ARRAY_confirmed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirmed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_pending', 'arrays', 'enum_arr_ARRAY_pending', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Pending', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_amount', 'arrays', 'enum_arr_ARRAY_amount', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Amount', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'enum_arr_ARRAY_percent', 'arrays', 'enum_arr_ARRAY_percent', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Percent', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoThemeTitle', 'backend', 'Infobox / Preview front end', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview front end', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoThemeDesc', 'backend', 'Infobox / Preview front end', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'There are multiple color schemes available for the front end. Click on each of the thumbnails below to preview it. Click on "Use this theme" button for the theme you want to use.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallTheme', 'backend', 'Label / Choose theme', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose theme', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_1', 'arrays', 'option_themes_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 1', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_2', 'arrays', 'option_themes_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 2', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_3', 'arrays', 'option_themes_ARRAY_3', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 3', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_4', 'arrays', 'option_themes_ARRAY_4', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 4', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_5', 'arrays', 'option_themes_ARRAY_5', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 5', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_6', 'arrays', 'option_themes_ARRAY_6', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 6', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_7', 'arrays', 'option_themes_ARRAY_7', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 7', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_8', 'arrays', 'option_themes_ARRAY_8', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 8', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_9', 'arrays', 'option_themes_ARRAY_9', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 9', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'option_themes_ARRAY_10', 'arrays', 'option_themes_ARRAY_10', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Theme 10', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCurrentlyInUse', 'backend', 'Label / Currently in use', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Currently in use', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnUseThisTheme', 'backend', 'Label / Use this theme', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use this theme', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAddCalendar', 'backend', 'Button / Add Calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCalendarViewTitle', 'backend', 'Infobox / View calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCalendarViewDesc', 'backend', 'Infobox / View calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'On below you can view the details of the current calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAddUser', 'backend', 'Button / Add user', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add user', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUsersTitle', 'backend', 'Infobox / Users list', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Users list', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUsersDesc', 'backend', 'Infobox / Users list', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can see below the list of users. If you want to add new user, click on the button "+ Add user".', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAddBooking', 'backend', 'Button / Add booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoBookingsTitle', 'backend', 'Infobox / Bookings list', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings list', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoBookingsDesc', 'backend', 'Infobox / Bookings list', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can see below the list bookings. To add new booking, you can click on the button "+ Add booking".', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetWtimeAndPrice', 'backend', 'Lable / Set Working Time and Price for', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Working Time and Price for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_goto_cart', 'frontend', 'Label / Go to cart', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Go to cart', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_availability', 'frontend', 'Label / Availability', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Availability', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_available', 'frontend', 'Label / available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_booked', 'frontend', 'Label / booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'booked', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_selected', 'frontend', 'Label / selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_close', 'frontend', 'Button / Close', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Close', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCalendar', 'backend', 'Label / Calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAllCalendars', 'backend', 'Label / All calendars', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_slot_selected', 'frontend', 'Label / slot selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '%s slot selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_slots_selected', 'frontend', 'Label / slots selected', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', '%s slots selected', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionCopy', 'backend', 'Copy options from', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Copy options from', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionCopyTip', 'backend', 'Copy options tip', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can copy all the options below from any of your other calendars.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionCopyTitle', 'backend', 'Copy options confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Copy options confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionCopyDesc', 'backend', 'Copy options confirmation', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure you want to copy selected options?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnCopy', 'backend', 'Button / Copy', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Copy', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_views_ARRAY_1', 'arrays', 'front_views_ARRAY_1', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendar view', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_views_ARRAY_2', 'arrays', 'front_views_ARRAY_2', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Weekly view', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAllowSwitchLayout', 'backend', 'Label / Allow switching layout', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Allow switching layout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblMultiCalendar', 'backend', 'Label / Multi calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Multi calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_select_calendar', 'frontend', 'Label / Select calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_duplicated_slots', 'frontend', 'Label / Duplicated slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot(s) you selected is/are booked by another while you are placing the booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetOptionsFor', 'backend', 'Label / Set options for', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set options for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblPreviewCalendar', 'backend', 'Label / Preview calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblPreviewOptions', 'backend', 'Label / Preview options', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblDuplidatedSlot', 'backend', 'Label / Duplicated slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot(s) is/are already added for this reservation.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_slots_booked', 'backend', 'Label / Slot(s) of the calendar is booked.', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot(s) of the calendar is booked.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'cal_del_title', 'backend', 'Label / Delete booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'cal_del_body', 'backend', 'Label / Delete booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Do you want to delete the selected booking?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'cal_del_ts_title', 'backend', 'Label / Delete slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'cal_del_ts_body', 'backend', 'Label / Delete slot', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Do you want to delete the selected slot?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblReceiveSms', 'backend', 'Label / Receive SMS', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Receive SMS', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_sms_ARRAY_confirm', 'arrays', 'receive_sms_ARRAY_confirm', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking confirmation SMS - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_sms_ARRAY_payment', 'arrays', 'receive_sms_ARRAY_payment', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment confirmation SMS - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_custom_wtime', 'backend', 'Label / Custom working time is set', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom working time is set for this day.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_view_slots', 'backend', 'Label / View slots', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_client_cancellation', 'backend', 'Client - Cancellation email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client - Cancellation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'confirmation_admin_cancellation', 'backend', 'Admin - Cancellation email', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin - Cancellation email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_cancel', 'arrays', 'receive_emails_ARRAY_cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking cancellation email - all calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_myconf', 'arrays', 'receive_emails_ARRAY_myconf', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking confirmation email - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_mypay', 'arrays', 'receive_emails_ARRAY_mypay', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment confirmation email - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_myremind', 'arrays', 'receive_emails_ARRAY_myremind', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking reminder email - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_emails_ARRAY_mycan', 'arrays', 'receive_emails_ARRAY_mycan', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking cancellation email - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_sms_ARRAY_myconf', 'arrays', 'receive_sms_ARRAY_myconf', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking confirmation SMS - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'receive_sms_ARRAY_mypay', 'arrays', 'receive_sms_ARRAY_mypay', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment confirmation SMS - my calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_legend_past', 'frontend', 'Label / Dates in the past', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dates in the past', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_legend_today', 'frontend', 'Label / Today', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_legend_partly', 'frontend', 'Label / Partly booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Partly booked', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_legend_avail', 'frontend', 'Label / Available', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_legend_booked', 'frontend', 'Label / Fully booked or days off', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fully booked or days off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_fully_booked', 'frontend', 'Label / Fully booked', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fully booked', 'script');


INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdmin_pjActionIndex');

INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminCalendars');
SET @level_1_id := (SELECT LAST_INSERT_ID());

	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminCalendars_pjActionIndex');
	SET @level_2_id := (SELECT LAST_INSERT_ID());
	
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCalendars_pjActionCreate');
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCalendars_pjActionUpdate');
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCalendars_pjActionDeleteCalendar');
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminCalendars_pjActionDeleteCalendarBulk');
		
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminTime_pjActionIndex');
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminTime_pjActionCustom');
	SET @level_2_id := (SELECT LAST_INSERT_ID());
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminTime_pjActionDeleteDate');
		INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminTime_pjActionDeleteDateBulk');
	
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionIndex');
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionPayments');
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionTerms');
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionNotifications');
	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminOptions_pjActionBookingForm');

INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminClients');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminClients_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());

    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionCreate');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionUpdate');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionDeleteClient');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionDeleteClientBulk');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionStatusClient'); 
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminClients_pjActionExportClient');
    
INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminSchedule_pjActionIndex');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionWeekly');
  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionMonthly');
  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionPrintWeekly');
  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminSchedule_pjActionPrintMonthly');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdmin_pjActionIndex', 'backend', 'Label / Dashboard Menu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dashboard Menu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars', 'backend', 'Label / Calendars Menu', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendars Menu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars_pjActionIndex', 'backend', 'Label / Calendars List', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Calendars List', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars_pjActionCreate', 'backend', 'Label / Add Calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars_pjActionUpdate', 'backend', 'Label / Update Calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update Calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars_pjActionDeleteCalendar', 'backend', 'Label / Delete Single Calendar', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete Single Calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminCalendars_pjActionDeleteCalendarBulk', 'backend', 'Label / Delete Multiple Calendars', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete Multiple Calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminTime_pjActionIndex', 'backend', 'Label / Working Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminTime_pjActionCustom', 'backend', 'Label / Custom Working Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminTime_pjActionDeleteDate', 'backend', 'Label / Delete single Custom Working Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single Custom Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminTime_pjActionDeleteDateBulk', 'backend', 'Label / Delete multiple Custom Working Times', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple Custom Working Times', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionIndex', 'backend', 'Label / Booking Options', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionPayments', 'backend', 'Label / Payments', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payments', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionTerms', 'backend', 'Label / Terms & Conditions', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms & Conditions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionNotifications', 'backend', 'Label / Notifications', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionBookingForm', 'backend', 'Label / Booking Form', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients', 'backend', 'pjAdminClients', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Clients Menu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionCreate', 'backend', 'pjAdminClients_pjActionCreate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionDeleteClient', 'backend', 'pjAdminClients_pjActionDeleteClient', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionDeleteClientBulk', 'backend', 'pjAdminClients_pjActionDeleteClientBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionExportClient', 'backend', 'pjAdminClients_pjActionExportClient', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Export clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionIndex', 'backend', 'pjAdminClients_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Clients List', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionStatusClient', 'backend', 'pjAdminClients_pjActionStatusClient', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Revert clients status', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminClients_pjActionUpdate', 'backend', 'pjAdminClients_pjActionUpdate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Edit clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_Index', 'backend', 'pjAdminSchedule_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Schedule Menu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionWeekly', 'backend', 'pjAdminSchedule_pjActionWeekly', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Weekly Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionMonthly', 'backend', 'pjAdminSchedule_pjActionMonthly', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Monthly Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionPrintWeekly', 'backend', 'pjAdminSchedule_pjActionPrintWeekly', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Print Weekly Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionPrintMonthly', 'backend', 'pjAdminSchedule_pjActionPrintMonthly', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Print Monthly Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_name', 'backend', 'Label / Script Name', 'script', '2020-07-31 11:21:49');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time Slots Booking Calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabWorkingTime', 'backend', 'Tab / Working Time', 'script', '2020-08-03 12:15:37');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCopyOptions', 'backend', 'Label / Copy Options', 'script', '2020-08-03 12:50:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Copy Options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSelectOwnerNote', 'backend', 'Label / Select owner note', 'script', '2020-08-03 12:52:36');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select the user who can edit calendar and view its bookings.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabBookingOptions', 'backend', 'Tab / Booking Options', 'script', '2020-08-03 13:45:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Options', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabTerms', 'backend', 'Tab / Terms & Conditions', 'script', '2020-08-03 14:54:48');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Terms & Conditions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoNotificationsTitle', 'backend', 'Infobox / Notifications', 'script', '2020-08-03 18:34:37');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Notifications', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoNotificationsDesc', 'backend', 'Infobox / Notifications desc', 'script', '2020-08-03 18:35:37');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Automated messages are sent both to client and administrator(s) on specific events. Select message type to edit it - enable/disable or just change message text. For SMS notifications you need to enable SMS service.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_recipient', 'backend', 'Label / Recipient', 'script', '2020-08-03 18:40:17');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Recipient', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_tokens', 'backend', 'Label / Available Tokens', 'plugin', '2020-08-03 18:53:10');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Available Tokens', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_tokens_note', 'backend', 'Label / Available Tokens note', 'plugin', '2020-08-03 18:53:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Personalize the message by including any of the available tokens and it will be replaced with corresponding data.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'recipients_ARRAY_client', 'arrays', 'recipients_ARRAY_client', 'script', '2020-08-03 19:11:01');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'recipients_ARRAY_admin', 'arrays', 'recipients_ARRAY_admin', 'script', '2020-08-03 19:11:17');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Admin', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_message', 'backend', 'Label / Message', 'plugin', '2020-08-03 19:37:14');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Message', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_send', 'backend', 'Label / Send', 'plugin', '2020-08-03 19:39:25');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_do_not_send', 'backend', 'Label / Do not send', 'plugin', '2020-08-03 19:40:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Do not send', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_confirmation', 'arrays', 'notifications_ARRAY_client_email_confirmation', 'plugin', '2020-08-03 19:43:55');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send booking confirmation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_payment', 'arrays', 'notifications_ARRAY_client_email_payment', 'plugin', '2020-08-03 19:44:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send payment confirmation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_cancel', 'arrays', 'notifications_ARRAY_client_email_cancel', 'plugin', '2020-08-03 19:44:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send cancellation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_reminder', 'arrays', 'notifications_ARRAY_client_email_reminder', 'plugin', '2020-08-03 19:48:37');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send booking reminder email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_sms_reminder', 'arrays', 'notifications_ARRAY_client_sms_reminder', 'plugin', '2020-08-03 19:49:08');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send booking reminder SMS', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_confirmation', 'arrays', 'notifications_ARRAY_admin_email_confirmation', 'plugin', '2020-08-03 19:54:22');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send booking confirmation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_payment', 'arrays', 'notifications_ARRAY_admin_email_payment', 'plugin', '2020-08-03 19:54:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send payment confirmation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_email_cancel', 'arrays', 'notifications_ARRAY_admin_email_cancel', 'plugin', '2020-08-03 19:54:45');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send cancellation email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_sms_confirmation', 'arrays', 'notifications_ARRAY_admin_sms_confirmation', 'plugin', '2020-08-03 19:55:25');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send booking confirmation SMS', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_admin_sms_payment', 'arrays', 'notifications_ARRAY_admin_sms_payment', 'plugin', '2020-08-03 19:55:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send payment confirmation SMS', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subject', 'backend', 'Label / Subject', 'plugin', '2020-08-03 20:01:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Subject', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_messages', 'backend', 'Label / Messages', 'plugin', '2020-08-03 20:02:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Messages', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_is_active', 'backend', 'Label / Send this message', 'plugin', '2020-08-03 20:03:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send this message', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_confirmation', 'arrays', 'notifications_titles_ARRAY_client_email_confirmation', 'plugin', '2020-08-03 20:04:56');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Confirmation email sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_confirmation', 'arrays', 'notifications_subtitles_ARRAY_client_email_confirmation', 'plugin', '2020-08-03 20:05:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to client when a new booking is made.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_payment', 'arrays', 'notifications_titles_ARRAY_client_email_payment', 'plugin', '2020-08-03 20:05:52');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment Confirmation email sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_payment', 'arrays', 'notifications_subtitles_ARRAY_client_email_payment', 'plugin', '2020-08-03 20:06:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the client when a payment is made for a new booking.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_cancel', 'arrays', 'notifications_titles_ARRAY_client_email_cancel', 'plugin', '2020-08-03 20:06:54');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Cancellation email sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_cancel', 'arrays', 'notifications_subtitles_ARRAY_client_email_cancel', 'plugin', '2020-08-03 20:07:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the client when a client cancels a booking.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_reminder', 'arrays', 'notifications_titles_ARRAY_client_email_reminder', 'plugin', '2020-08-03 20:07:43');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Reminder email sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_reminder', 'arrays', 'notifications_subtitles_ARRAY_client_email_reminder', 'plugin', '2020-08-03 20:08:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the client for reminder.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_sms_reminder', 'arrays', 'notifications_titles_ARRAY_client_sms_reminder', 'plugin', '2020-08-03 20:08:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Confirmation SMS sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_sms_reminder', 'arrays', 'notifications_subtitles_ARRAY_client_sms_reminder', 'plugin', '2020-08-03 20:09:40');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This SMS is sent to client when a new booking is made.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_confirmation', 'arrays', 'notifications_titles_ARRAY_admin_email_confirmation', 'plugin', '2020-08-03 20:10:26');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Confirmation email sent to Admin', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_confirmation', 'arrays', 'notifications_subtitles_ARRAY_admin_email_confirmation', 'plugin', '2020-08-03 20:10:59');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to Admin when a new booking is made.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_payment', 'arrays', 'notifications_titles_ARRAY_admin_email_payment', 'plugin', '2020-08-03 20:11:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment Confirmation email sent to Admin', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_payment', 'arrays', 'notifications_subtitles_ARRAY_admin_email_payment', 'plugin', '2020-08-03 20:11:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the Admin when a payment is made for a new booking.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_email_cancel', 'arrays', 'notifications_titles_ARRAY_admin_email_cancel', 'plugin', '2020-08-03 20:12:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Cancellation email sent to Admin', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_email_cancel', 'arrays', 'notifications_subtitles_ARRAY_admin_email_cancel', 'plugin', '2020-08-03 20:12:51');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the Admin when a booking cancelled.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_sms_confirmation', 'arrays', 'notifications_titles_ARRAY_admin_sms_confirmation', 'plugin', '2020-08-03 20:13:25');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking Confirmation SMS sent to Admin', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_sms_confirmation', 'arrays', 'notifications_subtitles_ARRAY_admin_sms_confirmation', 'plugin', '2020-08-03 20:13:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This SMS is sent to the Admin when a booking made.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_admin_sms_payment', 'arrays', 'notifications_titles_ARRAY_admin_sms_payment', 'plugin', '2020-08-03 20:14:14');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Payment Confirmation SMS sent to Admin', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_admin_sms_payment', 'arrays', 'notifications_subtitles_ARRAY_admin_sms_payment', 'plugin', '2020-08-03 20:14:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This SMS is sent to the Admin when the payment made for new booking.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_sms_na', 'backend', 'Label / Subject', 'plugin', '2020-08-03 20:18:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'SMS notifications are currently not available for your website. See details', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_sms_na_here', 'backend', 'Label / Subject', 'plugin', '2020-08-03 20:18:43');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'here', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_offline_payment_methods', 'backend', 'Label / Offline Payment Methods', 'script', '2020-08-05 09:20:13');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Offline Payment Methods', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_max_slots_per_booking', 'backend', 'Options / Maximum slots per booking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Maximum slots per booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_max_slots_per_booking_text', 'backend', 'Options / Maximum slots per booking text', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'If you leave it empty or set it 0, it means that you allow unlimited slots per booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_working_hours', 'backend', 'Working Time / Working hours', 'script', '2020-08-05 11:24:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Working hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lunch_off', 'backend', 'Label / Lunch off', 'script', '2020-08-06 03:09:48');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch off', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_people_per_slot', 'backend', 'Working Time / People per slot', 'script', '2020-08-06 06:08:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'People per slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'tabCustomWorkingTime', 'backend', 'Tab / Custom Working Time', 'script', '2020-08-06 10:43:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Working Time', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'custom_dates_list', 'backend', 'Label / List of custom dates', 'script', '2020-08-06 10:52:41');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'List of custom dates', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAddCustom', 'backend', 'button / Add Custom', 'script', '2020-08-06 14:01:15');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Custom', 'script');

INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminBookings');
SET @level_1_id := (SELECT LAST_INSERT_ID());

  INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjAdminBookings_pjActionIndex');
  SET @level_2_id := (SELECT LAST_INSERT_ID());

    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionCreate');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionUpdate');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionDeleteBooking');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionDeleteBookingBulk');
    INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminBookings_pjActionExportICal');
    
INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings', 'backend', 'pjAdminBookings', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings Menu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionIndex', 'backend', 'pjAdminBookings_pjActionIndex', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings List', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionCreate', 'backend', 'pjAdminBookings_pjActionCreate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionUpdate', 'backend', 'pjAdminBookings_pjActionUpdate', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionDeleteBooking', 'backend', 'pjAdminBookings_pjActionDeleteBooking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete single booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionDeleteBookingBulk', 'backend', 'pjAdminBookings_pjActionDeleteBookingBulk', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Delete multiple bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminBookings_pjActionExportICal', 'backend', 'pjAdminBookings_pjActionExportICal', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Download iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuClients', 'backend', 'Menu / Clients', 'script', '2020-08-07 07:54:31');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoClientsTitle', 'backend', 'Infobox / Client list', 'script', '2020-08-07 08:55:21');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client list', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoClientsDesc', 'backend', 'Infobox / Client list', 'script', '2020-08-07 08:55:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Below is a list of the people who have used the system to make bookins. Click on the pencil icon of each row to see and edit client details.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAddClient', 'backend', 'Button / Add Client', 'script', '2020-08-07 08:56:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblBookings', 'backend', 'Label / Bookings', 'script', '2020-08-07 08:57:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCreateClientTitle', 'backend', 'Infobox / Add Client', 'script', '2020-08-07 09:00:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCreateClientDesc', 'backend', 'Infobox / Add Client', 'script', '2020-08-07 09:01:11');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fill in the form below and "Save" to create new client.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblTitle', 'backend', 'Label / Title', 'script', '2020-08-07 09:02:13');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Title', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCompany', 'backend', 'Label / Company', 'script', '2020-08-07 09:06:52');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Company', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUpdateClientTitle', 'backend', 'Infobox / Update Client', 'script', '2020-08-07 09:12:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUpdateClientDesc', 'backend', 'Infobox / Update Client', 'script', '2020-08-07 09:12:17');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use the form below to make changes to client''s details.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblClientTotalBookings', 'backend', 'Label / Total bookings', 'script', '2020-08-07 09:13:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Total bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACL01', 'backend', 'error_bodies_ARRAY_ACL01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All changes made to the client have been saved.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACL03', 'backend', 'error_bodies_ARRAY_ACL03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The client has been added into the system.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACL04', 'backend', 'error_bodies_ARRAY_ACL04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The client could not be added into the system.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_bodies_ARRAY_ACL08', 'backend', 'error_bodies_ARRAY_ACL08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We are sorry that client you are looking for is missing.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACL01', 'backend', 'error_titles_ARRAY_ACL01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client updated!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACL03', 'backend', 'error_titles_ARRAY_ACL03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client added!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACL04', 'backend', 'error_titles_ARRAY_ACL04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client not added!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'error_titles_ARRAY_ACL08', 'backend', 'error_titles_ARRAY_ACL08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client not found!', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_0', 'arrays', 'short_days_ARRAY_0', 'script', '2020-08-07 13:19:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Su', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_1', 'arrays', 'short_days_ARRAY_1', 'script', '2020-08-07 13:20:11');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Mo', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_2', 'arrays', 'short_days_ARRAY_2', 'script', '2020-08-07 13:20:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tu', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_3', 'arrays', 'short_days_ARRAY_3', 'script', '2020-08-07 13:20:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_4', 'arrays', 'short_days_ARRAY_4', 'script', '2020-08-07 13:20:40');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Th', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_5', 'arrays', 'short_days_ARRAY_5', 'script', '2020-08-07 13:20:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fr', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'short_days_ARRAY_6', 'arrays', 'short_days_ARRAY_6', 'script', '2020-08-07 13:21:12');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sa', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnAdvanceSearch', 'backend', 'Label / Advance Search', 'script', '2020-08-07 13:30:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Advance Search', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_dates', 'backend', 'Label / Dates', 'script', '2020-08-07 13:38:52');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Dates', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblTo', 'backend', 'to', 'script', '2020-08-07 13:39:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'to', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoAddBookingTitle', 'backend', 'Infobox / Add Booking', 'script', '2020-08-07 15:01:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoAddBookingDesc', 'backend', 'Infobox / Add Booking', 'script', '2020-08-07 15:01:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fill in the form below to add a new booking. Under Clients tab you can enter information about the client.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblLunchStart', 'backend', 'Label / Lunch start', 'script', '2020-08-07 17:28:06');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch start', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblLunchDuration', 'backend', 'Label / Lunch duration', 'script', '2020-08-07 17:28:33');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Lunch duration', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_set_custom_price', 'backend', 'Label / Set custom price for each slot', 'script', '2020-08-07 17:31:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set custom price for each slot', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_limit_note', 'backend', 'Working Time / Bookings per slot note', 'script', '2020-08-07 17:36:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Maximum number of bookings that can be made for the same slot.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'time_people_per_slot_note', 'backend', 'Working Time / People per slot note', 'script', '2020-08-07 17:37:46');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Maximum number of people that can be added for the same slot.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_online_payment_gateway', 'backend', 'Label / Online payment gateway', 'script', '2020-08-08 15:16:24');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Online payment gateway', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_offline_payment', 'backend', 'Label / Offline payment', 'script', '2020-08-08 15:16:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Offline payment', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNewClient', 'backend', 'Label / New Client', 'script', '2020-08-09 11:06:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'New Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblExistingClient', 'backend', 'Label / Existing Client', 'script', '2020-08-09 11:06:41');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Existing Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'add_item_msg_ARRAY_100', 'arrays', 'add_item_msg_ARRAY_100', 'script', '2020-08-09 13:59:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The time slots cannot be empty.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'add_item_msg_ARRAY_101', 'arrays', 'add_item_msg_ARRAY_101', 'script', '2020-08-09 13:59:54');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slot(s) is/are already added for this booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'add_item_msg_ARRAY_200', 'arrays', 'add_item_msg_ARRAY_200', 'script', '2020-08-09 14:00:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Booking slot(s) has been added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_i_empty', 'backend', 'booking_i_empty', 'script', '2020-08-09 16:12:08');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No booking slots added.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAddCustom', 'backend', 'Label / Add Custom', 'script', '2020-08-09 17:31:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Custom', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblAddCustomDesc', 'backend', 'Label / Add Custom desc', 'script', '2020-08-09 17:32:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Use the form below to add new custom working time for particular date.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCustomDatesTitle', 'backend', 'Label / Custom Dates', 'script', '2020-08-09 17:34:24');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Custom Dates', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoCustomDatesDesc', 'backend', 'Label / Custom Dates', 'script', '2020-08-09 17:36:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can see below the list of custom dates that have been defined for selected calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoMissingParamsTitle', 'backend', 'Infobox / Missing Params', 'script', '2020-08-10 03:26:06');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Missing Params', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoMissingParamsDesc', 'backend', 'Infobox / Missing Params', 'script', '2020-08-10 03:26:35');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Prices per slot are not shown up due to missing parameters.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblViewBookings', 'backend', 'Label / View Bookings', 'script', '2020-08-10 09:17:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblViewSchedule', 'backend', 'Label / View Schedule', 'script', '2020-08-10 09:18:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_new', 'backend', 'Dash / new', 'script', '2020-08-10 09:26:17');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'new', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_today', 'backend', 'Dash / today', 'script', '2020-08-10 09:27:05');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_confirmed', 'backend', 'Dash / confirmed', 'script', '2020-08-10 09:27:45');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'confirmed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_total', 'backend', 'Dash / total', 'script', '2020-08-10 09:28:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'total', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_cancelled', 'backend', 'Dash / cancelled', 'script', '2020-08-10 09:28:41');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'cancelled', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblUpcoming5Bookings', 'backend', 'Label / Upcoming 5 Bookings', 'script', '2020-08-10 09:32:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Upcoming 5 Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblFromAnotherCalendar', 'backend', 'Label / From another calendar', 'script', '2020-08-10 10:23:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'From another calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblToAnotherCalendar', 'backend', 'Label / To another calendar', 'script', '2020-08-10 10:23:43');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'To another calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCopyCalendarSelection', 'backend', 'Label / Select the options that you need copied from this calendar to', 'script', '2020-08-10 10:24:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select the options that you need copied from this calendar to', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCopyCompleted', 'backend', 'Label / The selected options have been copied.', 'script', '2020-08-10 14:42:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The selected options have been copied.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUpdateBookingTitle', 'backend', 'Infobox / Update Booking', 'script', '2020-08-11 05:56:19');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoUpdateBookingDesc', 'backend', 'Infobox / Update Booking', 'script', '2020-08-11 05:57:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'If you do changes to the time slots, price for the booking is automatically recalculated. You can change details about your client under the Client tab. You can also send email and/or sms reminder to your client using the icons next to each booked time slot.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblClient', 'backend', 'Label / Client', 'script', '2020-08-11 07:08:25');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCreated', 'backend', 'Label / Created', 'script', '2020-08-11 07:16:59');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Created', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSendICal', 'backend', 'Label / Send iCal', 'script', '2020-08-11 07:20:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSendEmailConfirmation', 'backend', 'Button Send Email Confirmation', 'script', '2020-08-11 07:43:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send Email Confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_send_email_confirmation', 'backend', 'Label / Send email confirmation', 'script', '2020-08-11 07:44:01');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send email confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblEmailNotificationNotSet', 'backend', 'Label / Email notification is not set.', 'script', '2020-08-11 08:12:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Email notification is not set.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnSendSMSConfirmation', 'backend', 'Button Send SMS Confirmation', 'script', '2020-08-12 04:56:08');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send SMS Confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_send_sms_confirmation', 'backend', 'Label / Send SMS confirmation', 'script', '2020-08-12 04:57:01');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send SMS confirmation', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuSchedule', 'backend', 'Menu / Schedule', 'script', '2020-08-12 07:48:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoScheduleTitle', 'backend', 'Infobox / Schedule', 'script', '2020-08-12 08:01:31');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Schedule', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoScheduleDesc', 'backend', 'Infobox / Schedule desc', 'script', '2020-08-12 08:02:39');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'By default below you can view the weekly or monthly schedule. Use "jump to" to switch to any week/month. You can also print the schedule by clicking on the Print button.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_jump_to', 'backend', 'Label / Jump to', 'script', '2020-08-12 08:04:40');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Jump to', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_choose_calendar', 'backend', 'Label / Choose calendar', 'script', '2020-08-12 08:05:26');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Choose calendar', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_legend_available', 'backend', 'Legend / Available', 'script', '2020-08-12 08:09:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Available', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_legend_pending', 'backend', 'Legend / Pending', 'script', '2020-08-12 08:10:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Pending', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_legend_confirmed', 'backend', 'Legend / Confirmed', 'script', '2020-08-12 08:11:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Confirmed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_legend_passed', 'backend', 'Legend / Passed', 'script', '2020-08-12 08:11:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Passed', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_monthly_view', 'backend', 'Label / MONTHLY VIEW', 'script', '2020-08-12 08:12:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'MONTHLY VIEW', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_weekly_view', 'backend', 'Label / WEEKLY VIEW', 'script', '2020-08-12 08:14:40');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'WEEKLY VIEW', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_monthly_view_lowercase', 'backend', 'Label / Monthly view', 'script', '2020-08-12 08:15:15');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Monthly view', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_weekly_view_lowercase', 'backend', 'Label / Monthly view', 'script', '2020-08-12 08:15:43');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Weekly view', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_week', 'backend', 'Label / Week', 'script', '2020-08-12 09:13:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Week', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_send_reminder', 'backend', 'Label / Send reminder', 'script', '2020-08-12 12:19:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send reminder', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_send_reminder_email', 'backend', 'Label / Send reminder email', 'script', '2020-08-13 04:55:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send reminder email', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_are_you_sure', 'backend', 'Label / Are you sure', 'script', '2020-08-13 05:07:48');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'schedule_cancel_booking_text', 'backend', 'Label / Are you sure', 'script', '2020-08-13 05:08:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Are you sure that you want to cancel the selected booking slots?', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btn_confirm_cancel', 'backend', 'Label / Yes, cancel it', 'script', '2020-08-13 05:08:56');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Yes, cancel it', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_change_labels', 'backend', 'Label / Change Labels', 'script', '2020-08-13 11:15:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Change Labels', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_preview_your_website', 'backend', 'Label / Preview your website', 'script', '2020-08-13 11:19:08');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Open in new window', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'script_install_your_website', 'backend', 'Label / Install your website', 'script', '2020-08-13 11:19:33');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install your website', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoInstallTitle', 'backend', 'Infobox / Install instructions', 'script', '2020-08-13 11:52:54');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install instructions', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'infoInstallDesc', 'backend', 'Infobox / Install instructions desc', 'script', '2020-08-13 11:53:12');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'In order to install the script on your website copy the code below and add it to your web page.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallCode', 'backend', 'Label / Install Code', 'script', '2020-08-13 13:07:06');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install Code', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallSeo_Step3', 'backend', 'Label / Install Code Seo 3', 'script', '2020-08-13 13:13:39');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Step 3. (SAME DOMAIN INSTALL ONLY) Create .htaccess file (or update existing one) in the folder where your web page is and put the data below in it', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallSeo_Step4', 'backend', 'Label / Install Code Seo 4', 'script', '2020-08-13 13:13:54');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Step 4. (CROSS-DOMAIN INSTALL ONLY) Create .htaccess file (or update existing one) in the folder where your web page is and put the data below in it', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_new_bookings', 'backend', 'Dash / New Bookings', 'script', '2020-08-13 14:37:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'New Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_next_7_days', 'backend', 'Dash / Next 7 days', 'script', '2020-08-13 14:38:35');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Next 7 days', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_pending', 'backend', 'Dash / pending', 'script', '2020-08-13 14:39:26');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'pending', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_this_month', 'backend', 'Dash / This Month', 'script', '2020-08-13 14:40:45');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This Month', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_all_bookings', 'backend', 'Dash / All Bookings', 'script', '2020-08-13 14:41:11');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_bookings', 'backend', 'Dash / bookings', 'script', '2020-08-13 14:50:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_total_amount', 'backend', 'Dash / total amount', 'script', '2020-08-13 14:51:51');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'total amount', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_users', 'backend', 'Dash / Users', 'script', '2020-08-13 14:52:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_clients', 'backend', 'Dash / Clients', 'script', '2020-08-13 14:54:23');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_new_clients', 'backend', 'Dash / new clients', 'script', '2020-08-13 14:57:50');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'new clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_new_users', 'backend', 'Dash / new users', 'script', '2020-08-13 14:58:04');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'new users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_total_users', 'backend', 'Dash / total users', 'script', '2020-08-13 14:58:15');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'total users', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_total_clients', 'backend', 'Dash / total clients', 'script', '2020-08-13 14:58:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'total clients', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_upcoming_bookings', 'backend', 'Dash / Upcoming bookings', 'script', '2020-08-13 15:31:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Upcoming bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_you_have_bookings_today', 'backend', 'Dash / You have xx bookings today', 'script', '2020-08-13 15:32:49');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You have %s bookings today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_you_have_booking_today', 'backend', 'Dash / You have xx booking today', 'script', '2020-08-13 15:33:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You have %s booking today', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_view_all_bookings', 'backend', 'Dash / View All Bookings', 'script', '2020-08-13 15:35:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'View All Bookings', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_bookings_made', 'backend', 'Dash / bookings made', 'script', '2020-08-13 15:53:43');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'bookings made', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_cnt', 'backend', 'Bookings / Slots', 'script', '2020-08-14 10:25:22');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'add_item_msg_ARRAY_102', 'arrays', 'add_item_msg_ARRAY_102', 'script', '2020-08-14 10:48:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can only add maximum of %u slot(s) for selected calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_cart_slots', 'frontend', 'Label / Slots', 'script', '2020-08-14 13:12:06');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Slots', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_account', 'arrays', 'notifications_titles_ARRAY_client_email_account', 'plugin', '2020-08-17 07:37:59');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'New client account email sent to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_account', 'arrays', 'notifications_subtitles_ARRAY_client_email_account', 'plugin', '2020-08-17 07:38:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to the client when new account is created.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_account', 'arrays', 'notifications_ARRAY_client_email_account', 'plugin', '2020-08-17 07:40:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'New client account email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_ARRAY_client_email_forgot', 'arrays', 'notifications_ARRAY_client_email_forgot', 'plugin', '2020-08-17 07:40:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send forgot password email', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_titles_ARRAY_client_email_forgot', 'arrays', 'notifications_titles_ARRAY_client_email_forgot', 'plugin', '2020-08-17 07:41:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send forgot password email to Client', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'notifications_subtitles_ARRAY_client_email_forgot', 'arrays', 'notifications_subtitles_ARRAY_client_email_forgot', 'plugin', '2020-08-17 07:41:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'This message is sent to client when he requests for password recovery.', 'plugin');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_login', 'frontend', 'Button / Login', 'script', '2020-08-17 10:31:25');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Login', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_logout', 'frontend', 'Button / Logout', 'script', '2020-08-17 10:38:22');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Logout', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_profile', 'frontend', 'Button / Profile', 'script', '2020-08-17 10:38:32');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_login_form', 'frontend', 'Label / Login Form', 'script', '2020-08-17 10:48:32');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Login Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_password', 'frontend', 'Label / Password', 'script', '2020-08-17 11:00:53');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_password', 'frontend', 'Validate / Password is required', 'script', '2020-08-17 11:01:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Password is required.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_forgot_password', 'frontend', 'Label / Forgot password', 'script', '2020-08-17 11:06:42');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Forgot password', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_forgot_form', 'frontend', 'Label / Forgot Password Form', 'script', '2020-08-19 14:07:12');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Forgot Password Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'forgot_messages_ARRAY_100', 'arrays', 'forgot_messages_ARRAY_100', 'script', '2020-08-19 14:11:29');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'There is no such account in the system.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'forgot_messages_ARRAY_101', 'arrays', 'forgot_messages_ARRAY_101', 'script', '2020-08-19 14:11:56');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your account is not active yet.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'forgot_messages_ARRAY_200', 'arrays', 'forgot_messages_ARRAY_200', 'script', '2020-08-19 14:12:26');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The password has been to your mail box.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_profile_form', 'frontend', 'Label / Profile Form', 'script', '2020-08-19 14:21:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Profile Form', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_update_profile_msg', 'frontend', 'Label / Profile Form', 'script', '2020-08-19 14:25:20');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your profile has been updated.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_send', 'frontend', 'Button / Send', 'script', '2020-08-19 14:34:41');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_save', 'frontend', 'Button / Save', 'script', '2020-08-19 14:34:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Save', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetBookingOptionsFor', 'backend', 'Lable / Set Booking Options for', 'script', '2020-08-20 07:51:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Booking Options for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetPaymentsFor', 'backend', 'Lable / Set Payments for', 'script', '2020-08-20 07:52:02');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Payments for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetTermsFor', 'backend', 'Lable / Set Terms for', 'script', '2020-08-20 07:53:36');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Terms & Conditions for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetNotificationsFor', 'backend', 'Lable / Set Notifications for', 'script', '2020-08-20 07:54:55');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Notifications for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetBookingFormFor', 'backend', 'Lable / Set Booking Form options for', 'script', '2020-08-20 07:55:36');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set Booking Form options for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_voucher', 'frontend', 'Frontend / Voucher', 'script', '2020-09-25 11:07:13');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_voucher_code', 'frontend', 'Frontend / Voucher code', 'script', '2020-09-25 11:09:57');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_apply', 'frontend', 'Button / Apply', 'script', '2020-09-25 11:14:34');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Apply', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'apply_voucher_msg_ARRAY_100', 'arrays', 'apply_voucher_msg_ARRAY_100', 'script', '2020-09-25 11:28:55');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code is missing.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_discount', 'frontend', 'Summary / Discount', 'script', '2020-09-28 03:43:38');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Discount', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_summary_subtotal', 'frontend', 'Summary / Subtotal', 'script', '2020-09-28 03:44:30');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Subtotal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_remove', 'frontend', 'Button / Remove', 'script', '2020-09-28 03:57:07');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Remove', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_discount_code_applied', 'frontend', 'Summary / Discount code is applied', 'script', '2020-09-28 04:03:47');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Discount code is applied', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'apply_voucher_msg_ARRAY_101', 'arrays', 'apply_voucher_msg_ARRAY_101', 'script', '2020-09-28 04:06:56');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code is not valid.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'apply_voucher_msg_ARRAY_200', 'arrays', 'apply_voucher_msg_ARRAY_200', 'script', '2020-09-28 04:07:12');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code is applied.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_discount', 'backend', 'Bookings / Discount', 'script', '2020-09-28 07:30:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Discount', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_voucher_code', 'backend', 'Bookings / Voucher code', 'script', '2020-09-28 07:31:19');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'menuBookingsList', 'backend', 'Menu / Bookings List', 'script', '2020-09-29 10:18:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Bookings List', 'script');

SET @id := (SELECT `id` FROM `ts_booking_plugin_base_fields` WHERE `key` = 'opt_o_status_if_paid');
UPDATE `ts_booking_plugin_base_multi_lang` SET `content` = 'Paid Booking status' WHERE `foreign_id` = @id AND `model` = 'pjBaseField' AND `field` = 'title';

SET @id := (SELECT `id` FROM `ts_booking_plugin_base_fields` WHERE `key` = 'opt_o_status_if_not_paid');
UPDATE `ts_booking_plugin_base_multi_lang` SET `content` = 'NOT Paid Booking status' WHERE `foreign_id` = @id AND `model` = 'pjBaseField' AND `field` = 'title';

SET @id := (SELECT `id` FROM `ts_booking_plugin_base_fields` WHERE `key` = 'opt_o_deposit');
UPDATE `ts_booking_plugin_base_multi_lang` SET `content` = 'Deposit' WHERE `foreign_id` = @id AND `model` = 'pjBaseField' AND `field` = 'title';

SET @id := (SELECT `id` FROM `ts_booking_plugin_base_fields` WHERE `key` = 'opt_o_thankyou_page');
UPDATE `ts_booking_plugin_base_multi_lang` SET `content` = 'Thank you page' WHERE `foreign_id` = @id AND `model` = 'pjBaseField' AND `field` = 'title';

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_no_upcoming_bookings', 'backend', 'Label / No upcoming bookings', 'script', '2020-10-05 11:35:09');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'No upcoming bookings.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblCopyOptionsNote', 'backend', 'Label / Copy options note', 'script', '2020-10-05 12:10:26');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Quickly copy options from and to other calendars', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSelectOptionsToCopy', 'backend', 'Label / Select the options you need copied', 'script', '2020-10-05 12:15:01');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Select the options you need copied', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSetWTimeFor', 'backend', 'Label / Set working time for', 'script', '2020-10-06 13:26:28');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set working time for', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblTimeSlotsNote', 'backend', 'Label / Time slots note', 'script', '2020-10-06 13:39:21');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'These are the time slots that clients can book.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_status_if_paid_text', 'backend', 'Options / Option note', 'script', '2020-10-07 04:30:33');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All bookings which are made and paid will be set with the following status.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_status_if_not_paid_text', 'backend', 'Options / Option note', 'script', '2020-10-07 04:30:49');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All bookings which are made and NOT paid will be set with the following status.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_deposit_text', 'backend', 'Options / Option note', 'script', '2020-10-07 04:38:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Set deposit to be collected for each booking. You can set a fixed fee or a percent from booking total amount. You can set it to 100 Percent if you want to collect full payment for each booking.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_thankyou_page_text', 'backend', 'Options / Option note', 'script', '2020-10-07 04:39:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'URL for the web page where your clients will be redirected after they complete online payments.\r\n', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOptionHours', 'backend', 'Label / hours', 'script', '2020-10-07 04:41:58');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'hours', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'btnDownloadICal', 'backend', 'Label / Download iCal', 'script', '2020-10-09 14:09:27');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Download iCal', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblSMSAPIKeyNotSet', 'backend', 'Label / The SMS API Key is not set.', 'script', '2020-10-09 14:14:11');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The SMS API Key is not set.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblClientLastBooking', 'backend', 'Label / Last booking', 'script', '2020-10-09 14:28:18');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Last booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'dash_add_booking', 'backend', 'Dash / Add Booking', 'script', '2020-10-13 07:12:04');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'booking_client', 'backend', 'Bookings / Client', 'script', '2020-10-13 07:27:35');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Client', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_max_slots_per_booking_msg', 'frontend', 'front_max_slots_per_booking_msg', 'script', '2020-10-21 02:59:36');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You can only add maximum of %u slot(s) for selected calendar.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'restriction_per_slot_msg', 'backend', 'restriction_per_slot_msg', 'script', '2020-10-21 05:57:52');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The people per slot value should not be greater than the bookings per slot value.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_cancel_page', 'backend', 'Options / Cancel page', 'script', '2020-10-21 10:29:15');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel page', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'opt_o_cancel_page_text', 'backend', 'Options / Option note', 'script', '2020-10-21 10:29:49');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'URL for the web page where your clients will be redirected if they cancel the online payments.\r\n', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_ip_address_blocked', 'frontend', 'front_ip_address_blocked', 'script', '2020-10-21 08:17:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Your IP address has been blocked.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblOverlappingBookingMsg', 'backend', 'Label / Overlapping booking message', 'script', '2020-10-29 14:35:44');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Some selected slot was already booked.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallConfigLocale', 'backend', 'Label / Language', 'script', '2020-10-29 17:47:00');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Language', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblInstallConfigHide', 'backend', 'Label / Hide language selector', 'script', '2020-10-29 17:47:48');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Hide language selector', 'script');


UPDATE `ts_booking_plugin_base_fields` SET `key` = 'pjAdminSchedule_pjActionIndex' WHERE `key` = 'pjAdminSchedule_Index';

SET @level_1_id := (SELECT `id` FROM `ts_booking_plugin_auth_permissions` WHERE `key` = 'pjAdminBookings');

	UPDATE `ts_booking_plugin_auth_permissions` SET `parent_id` = @level_1_id WHERE `key` = 'pjAdminSchedule_pjActionIndex';
	SET @level_2_id := (SELECT `id` FROM `ts_booking_plugin_auth_permissions` WHERE `key` = 'pjAdminSchedule_pjActionIndex');

	
    	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminSchedule_pjActionCancelBooking');
    	INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_2_id, 'pjAdminSchedule_pjActionSendReminder');

INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminOptions_pjActionPreview');
INSERT INTO `ts_booking_plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjAdminOptions_pjActionInstall');


INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionCancelBooking', 'backend', 'pjAdminSchedule_pjActionCancelBooking', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel booking', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminSchedule_pjActionSendReminder', 'backend', 'pjAdminSchedule_pjActionSendReminder', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Send reminder', 'script');


INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionPreview', 'backend', 'pjAdminOptions_pjActionPreview', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Preview', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'pjAdminOptions_pjActionInstall', 'backend', 'pjAdminOptions_pjActionInstall', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Install', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_btn_cancel', 'frontend', 'Button / Cancel', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Cancel', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_login_form_popup_desc', 'frontend', 'Info / Login form popup desc', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You already have an account. If you want, log-in here', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'front_v_email_exists', 'frontend', 'Error / Email exits', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Customer with this email address already exists', 'script');



INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblPriceLimitMsg', 'backend', 'lblPriceLimitMsg', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The price cannot be greater than 99999999999999.99.', 'script');

INSERT INTO `ts_booking_plugin_base_fields` VALUES (NULL, 'lblNoCalendarAssignedMsg', 'backend', 'Label / You are not assigned to any calendar, please contact the administrator', 'script', '2020-11-17 10:50:03');
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `ts_booking_plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'You are not assigned to any calendar, please contact the administrator.', 'script');



