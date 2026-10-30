START TRANSACTION;

-- UPDATE `options`
-- SET `value` = REPLACE(`value`, 'theme10::', 'theme10|theme11::'),
--     `label` = CONCAT(`label`, '|Theme 11')
-- WHERE `key` = 'o_theme' AND `value` NOT LIKE '%theme11%';

-- UPDATE `options`
-- SET `value` = REPLACE(`value`, 'theme10|theme11::theme1', 'theme10|theme11::theme11')
-- WHERE `key` = 'o_theme' AND `value` LIKE '%theme10|theme11::theme1';


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'option_themes_ARRAY_11', 'arrays', 'option_themes_ARRAY_11', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'option_themes_ARRAY_11');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'option_themes_ARRAY_11' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Theme 11', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_design_title', 'backend', 'theme11_design_title', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_design_title');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_design_title' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Theme 11 design', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_design_info', 'backend', 'theme11_design_info', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_design_info');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_design_info' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Changes appear in the preview straight away. Press Save to publish them to your website.', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_palettes', 'backend', 'theme11_palettes', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_palettes');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_palettes' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Colour palettes', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_brand', 'backend', 'theme11_brand', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_brand');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_brand' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Brand', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_surfaces', 'backend', 'theme11_surfaces', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_surfaces');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_surfaces' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Surfaces', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_text', 'backend', 'theme11_text', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_text');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_text' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Text', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_soft', 'backend', 'theme11_c_soft', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_soft');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_soft' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Soft areas / chips', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_colors', 'backend', 'theme11_colors', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_colors');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_colors' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Colors', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_typography', 'backend', 'theme11_typography', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_typography');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_typography' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Typography', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_shape', 'backend', 'theme11_shape', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_shape');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_shape' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Shape', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_access', 'backend', 'theme11_access', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_access');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_access' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Contrast check', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_primary', 'backend', 'theme11_c_primary', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_primary');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_primary' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Brand color', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_accent', 'backend', 'theme11_c_accent', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_accent');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_accent' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Accent', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_bg', 'backend', 'theme11_c_bg', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_bg');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_bg' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Page background', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_cards', 'backend', 'theme11_c_cards', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_cards');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_cards' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Cards', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_heading', 'backend', 'theme11_c_heading', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_heading');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_heading' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Headings', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_text', 'backend', 'theme11_c_text', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_text');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_text' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Body text', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_muted', 'backend', 'theme11_c_muted', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_muted');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_muted' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Secondary text', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_c_border', 'backend', 'theme11_c_border', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_c_border');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_c_border' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Borders', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_font_heading', 'backend', 'theme11_font_heading', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_font_heading');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_font_heading' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Heading font', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_font_body', 'backend', 'theme11_font_body', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_font_body');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_font_body' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Body font', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_radius', 'backend', 'theme11_radius', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_radius');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_radius' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Corner radius', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_button', 'backend', 'theme11_button', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_button');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_button' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Buttons', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_btn_pill', 'backend', 'theme11_btn_pill', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_pill');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_pill' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Pill', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_btn_rounded', 'backend', 'theme11_btn_rounded', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_rounded');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_rounded' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Rounded', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_btn_square', 'backend', 'theme11_btn_square', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_square');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_btn_square' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Square', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_save', 'backend', 'theme11_save', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_save');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_save' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Save', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_reset', 'backend', 'theme11_reset', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_reset');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_reset' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Reset to default', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_close', 'backend', 'theme11_close', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_close');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_close' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Close', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_saved', 'backend', 'theme11_saved', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_saved');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_saved' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Colors saved.', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_was_reset', 'backend', 'theme11_was_reset', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_was_reset');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_was_reset' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Colors reset to the default.', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_error', 'backend', 'theme11_error', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_error');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_error' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Could not save. Please try again.', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_chk_button', 'backend', 'theme11_chk_button', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_button');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_button' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Button text on brand color', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_chk_text', 'backend', 'theme11_chk_text', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_text');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_text' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Body text on cards', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_chk_muted', 'backend', 'theme11_chk_muted', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_muted');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_muted' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Secondary text on cards', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_chk_brand', 'backend', 'theme11_chk_brand', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_brand');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_chk_brand' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Brand color on page background', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_low', 'backend', 'theme11_low', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_low');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_low' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Low contrast - hard to read', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'theme11_customize', 'backend', 'theme11_customize', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'theme11_customize');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'theme11_customize' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Customize design', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_sec_contact', 'frontend', 'front_sec_contact', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_sec_contact');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_sec_contact' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Contact', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_sec_address', 'frontend', 'front_sec_address', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_sec_address');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_sec_address' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Address', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_sec_extra', 'frontend', 'front_sec_extra', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_sec_extra');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_sec_extra' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Additional information', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_sec_payment', 'frontend', 'front_sec_payment', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_sec_payment');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_sec_payment' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Payment', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_step_slots', 'frontend', 'front_step_slots', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_step_slots');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_step_slots' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Time slots', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_step_details', 'frontend', 'front_step_details', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_step_details');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_step_details' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Your details', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_step_confirm', 'frontend', 'front_step_confirm', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_step_confirm');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_step_confirm' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Confirm', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_step_done', 'frontend', 'front_step_done', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_step_done');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_step_done' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Done', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_steps_label', 'frontend', 'front_steps_label', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_steps_label');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_steps_label' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Booking progress', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');


-- INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
-- SELECT NULL, 'front_confirm_lead', 'frontend', 'front_confirm_lead', 'script', NOW() FROM DUAL
-- WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'front_confirm_lead');
-- SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'front_confirm_lead' LIMIT 1);
-- INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
-- SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Please check your details below and press Confirm to complete your booking.', 'script'
-- FROM `plugin_base_locale` l
-- WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');



INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'error_titles_ARRAY_ACL09', 'backend', 'error_titles_ARRAY_ACL09', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'error_titles_ARRAY_ACL09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'error_titles_ARRAY_ACL09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Client not saved', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'error_bodies_ARRAY_ACL09', 'backend', 'error_bodies_ARRAY_ACL09', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'error_bodies_ARRAY_ACL09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'error_bodies_ARRAY_ACL09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'The client has not been saved. Please fill in the name, e-mail and password (spaces only are not accepted).', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'plugin_base_error_titles_ARRAY_PU09', 'arrays', 'plugin_base_error_titles_ARRAY_PU09', 'plugin', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'plugin_base_error_titles_ARRAY_PU09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'plugin_base_error_titles_ARRAY_PU09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'User not saved', 'plugin'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'plugin_base_error_bodies_ARRAY_PU09', 'arrays', 'plugin_base_error_bodies_ARRAY_PU09', 'plugin', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'plugin_base_error_bodies_ARRAY_PU09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'plugin_base_error_bodies_ARRAY_PU09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'The user has not been saved. Please fill in the name, e-mail and password (spaces only are not accepted).', 'plugin'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'plugin_vouchers_error_titles_ARRAY_AV09', 'arrays', 'plugin_vouchers_error_titles_ARRAY_AV09', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'plugin_vouchers_error_titles_ARRAY_AV09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'plugin_vouchers_error_titles_ARRAY_AV09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'Voucher not saved', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');

INSERT INTO `plugin_base_fields` (`id`, `key`, `type`, `label`, `source`, `modified`)
SELECT NULL, 'plugin_vouchers_error_bodies_ARRAY_AV09', 'arrays', 'plugin_vouchers_error_bodies_ARRAY_AV09', 'script', NOW() FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `plugin_base_fields` WHERE `key` = 'plugin_vouchers_error_bodies_ARRAY_AV09');
SET @id := (SELECT `id` FROM `plugin_base_fields` WHERE `key` = 'plugin_vouchers_error_bodies_ARRAY_AV09' LIMIT 1);
INSERT INTO `plugin_base_multi_lang` (`id`, `foreign_id`, `model`, `locale`, `field`, `content`, `source`)
SELECT NULL, @id, 'pjBaseField', l.id, 'title', 'The voucher has not been saved. Please fill in the voucher code and discount (spaces only are not accepted).', 'script'
FROM `plugin_base_locale` l
WHERE @id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM `plugin_base_multi_lang` m WHERE m.`foreign_id` = @id AND m.`model` = 'pjBaseField' AND m.`locale` = l.id AND m.`field` = 'title');



COMMIT;