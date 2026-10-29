
START TRANSACTION;

ALTER TABLE `plugin_vouchers` MODIFY COLUMN `discount` decimal(16,2);

COMMIT;