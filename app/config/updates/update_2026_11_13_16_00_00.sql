-- Theme 11 'Customize design': Back Button Color (labels only; the colour itself is stored in the existing o_theme11_colors option).
-- Table names are unprefixed (the installer adds the prefix).

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'theme11_c_back', 'backend', 'theme11_c_back', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_back');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_back' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Back Button Color', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'theme11_chk_back', 'backend', 'theme11_chk_back', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_back');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_back' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Back button text on its color', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');
