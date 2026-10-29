
START TRANSACTION;

ALTER TABLE `plugin_vouchers` MODIFY COLUMN `discount` decimal(16,2);

INSERT INTO `plugin_base_fields` VALUES (NULL, 'plugin_vouchers_discount_invalid', 'backend', 'plugin_vouchers_discount_invalid', 'plugin', NULL);
SET @id := (SELECT LAST_INSERT_ID());
INSERT INTO `plugin_base_multi_lang` VALUES (NULL, @id, 'pjBaseField', '::LOCALE::', 'title', 'The discount value cannot be greater than 99999999999999.99.', 'plugin');

COMMIT;