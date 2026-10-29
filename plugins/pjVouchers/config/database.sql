DROP TABLE IF EXISTS `plugin_vouchers`;
CREATE TABLE IF NOT EXISTS `plugin_vouchers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `type` enum('amount','percent') DEFAULT NULL,
  `discount` decimal(9,2) unsigned DEFAULT NULL,
  `valid` enum('fixed','period','recurring') DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `time_from` time DEFAULT NULL,
  `time_to` time DEFAULT NULL,
  `every` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, NULL, 'pjVouchers');
SET @level_1_id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_1_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Vouchers Menu', 'data');

  INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjVouchers_pjActionCreate');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_2_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Add vouchers', 'data');

  INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjVouchers_pjActionUpdate');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_2_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Edit vouchers', 'data');

  INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjVouchers_pjActionDeleteVoucher');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_2_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Delete single voucher', 'data');

  INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjVouchers_pjActionDeleteVoucherBulk');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_2_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Delete multiple vouchers', 'data');

  INSERT INTO `plugin_auth_permissions` (`id`, `parent_id`, `key`) VALUES (NULL, @level_1_id, 'pjVouchers_pjActionExportVoucher');
  SET @level_2_id := (SELECT LAST_INSERT_ID());
  INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @level_2_id, 'pjAuthPermission', '::LOCALE::', 'title', 'Export vouchers', 'data');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_menu_vouchers', 'backend', 'Plugin Vouchers / Menu / Vouchers', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Vouchers', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_voucher', 'frontend', 'Plugin Vouchers / Label / Voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_add_voucher', 'frontend', 'Plugin Vouchers / Label / Add Voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_added_voucher', 'frontend', 'Plugin Vouchers / Label / Added voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Added voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_remove_voucher', 'frontend', 'Plugin Vouchers / Label / Remove voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Remove voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_invalid_voucher', 'frontend', 'Plugin Vouchers / Label / Invalid voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Invalid voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_validate_datetime', 'backend', 'Plugin Vouchers / Label / Validate date time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'To date/time must be greater than From date/time.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_validate_time', 'backend', 'Plugin Vouchers / Label / Validate time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End time must be greater than start time.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_front_label_out_range_voucher', 'frontend', 'Plugin Vouchers / Label / Out of range voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher is out of range date or time.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_voucher_code', 'backend', 'Plugin Vouchers / Label / Voucher code', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher code', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_discount', 'backend', 'Plugin Vouchers / Label / Discount', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Discount', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_valid', 'backend', 'Plugin Vouchers / Label / Valid', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Valid', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_voucher_code_exist', 'backend', 'Plugin Vouchers / Label / Existing voucher code', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The voucher code is already used.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_list_title', 'backend', 'Plugin Vouchers / Infobox / Voucher list title', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher List', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_list_desc', 'backend', 'Plugin Vouchers / Infobox / Voucher list description', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Below is the list of vouchers. If you wan to add new voucher, click on the "Add voucher" Tab. To edit or delete a certain voucher, click on corresponding icons on the row. You can also export the list of vouchers or delete multiple selected vouchers by using the popup menu at the bottom of the table.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_add_voucher_title', 'backend', 'Plugin Vouchers / Infobox / Add Voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add Voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_add_voucher_desc', 'backend', 'Plugin Vouchers / Infobox / Add Voucher description', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please fill out the form below to add voucher code and discount. You can add a voucher for specific date, day of the week or date range.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_update_voucher_title', 'backend', 'Plugin Vouchers / Infobox / Update voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Update voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_infobox_update_voucher_desc', 'backend', 'Plugin Vouchers / Infobox / Update voucher desc', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please make any change you want on the form below to update voucher information and click SAVE button.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_types_ARRAY_amount', 'arrays', 'plugin_vouchers_types_ARRAY_amount', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Amount', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_types_ARRAY_percent', 'arrays', 'plugin_vouchers_types_ARRAY_percent', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Percent', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_valids_ARRAY_fixed', 'arrays', 'plugin_vouchers_valids_ARRAY_fixed', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Fixed', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_valids_ARRAY_period', 'arrays', 'plugin_vouchers_valids_ARRAY_period', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Period', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_valids_ARRAY_recurring', 'arrays', 'plugin_vouchers_valids_ARRAY_recurring', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Recurring', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_date', 'backend', 'Plugin Vouchers / Label / Date', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_time_from', 'backend', 'Plugin Vouchers / Label / Time from', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time from', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_time_to', 'backend', 'Plugin Vouchers / Label / Time to', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Time to', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_monday', 'arrays', 'plugin_vouchers_days_ARRAY_monday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Monday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_tuesday', 'arrays', 'plugin_vouchers_days_ARRAY_tuesday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Tuesday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_wednesday', 'arrays', 'plugin_vouchers_days_ARRAY_wednesday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Wednesday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_thursday', 'arrays', 'plugin_vouchers_days_ARRAY_thursday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Thursday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_friday', 'arrays', 'plugin_vouchers_days_ARRAY_friday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Friday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_saturday', 'arrays', 'plugin_vouchers_days_ARRAY_saturday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Saturday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_days_ARRAY_sunday', 'arrays', 'plugin_vouchers_days_ARRAY_sunday', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Sunday', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_every', 'backend', 'Plugin Vouchers / Label / Every', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Every', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_titles_ARRAY_AV01', 'arrays', 'plugin_vouchers_error_titles_ARRAY_AV01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher updated', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_bodies_ARRAY_AV01', 'arrays', 'plugin_vouchers_error_bodies_ARRAY_AV01', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'All changes made to the voucher have been saved.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_titles_ARRAY_AV03', 'arrays', 'plugin_vouchers_error_titles_ARRAY_AV03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher added', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_bodies_ARRAY_AV03', 'arrays', 'plugin_vouchers_error_bodies_ARRAY_AV03', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'A new voucher has been added to the list. You can now add another voucher.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_titles_ARRAY_AV04', 'arrays', 'plugin_vouchers_error_titles_ARRAY_AV04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher failed to add', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_bodies_ARRAY_AV04', 'arrays', 'plugin_vouchers_error_bodies_ARRAY_AV04', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'We sorry that the voucher could not bee added successfully.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_titles_ARRAY_AV08', 'arrays', 'plugin_vouchers_error_titles_ARRAY_AV08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher not found', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_error_bodies_ARRAY_AV08', 'arrays', 'plugin_vouchers_error_bodies_ARRAY_AV08', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Voucher you are looking for is missing.', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_btn_add_voucher', 'backend', 'Plugin Vouchers / Button / Add voucher', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Add voucher', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_discount_type', 'backend', 'Plugin Vouchers / Label / Discount type', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Type', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_date_from', 'backend', 'Plugin Vouchers / Label / Date from', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date from', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_date_to', 'backend', 'Plugin Vouchers / Label / Date to', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Date to', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_start_time', 'backend', 'Plugin Vouchers / Label / Start Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Start Time', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_end_time', 'backend', 'Plugin Vouchers / Label / End Time', 'script', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'End Time', 'script');

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_enter_valid_number', 'backend', 'Plugin Vouchers / Please enter a valid number.', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'Please enter a valid number.', 'plugin');
