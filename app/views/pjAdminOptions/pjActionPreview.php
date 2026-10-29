<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-10">
                <h2><?php __('infoThemeTitle') ?></h2>
            </div>
        </div><!-- /.row -->

        <p class="m-b-none"><i class="fa fa-info-circle"></i> <?php __('infoThemeDesc') ?></p>
    </div><!-- /.col-md-12 -->
</div>
<div class="row border-bottom bar-top">
    <div class="col-1of3">
        <a target="_blank" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjBaseLocale&amp;action=pjActionLabels" class="btn btn-secondary"><i class="fa fa-refresh m-r-xs"></i> <?php __('script_change_labels') ?></a>

        <div class="btn-group color-theme-group" role="group" aria-label="...">
            <button type="button" class="btn btn-secondary"data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-hand-o-up m-r-xs"></i> <?php __('lblInstallTheme') ?> <i class="fa fa-angle-down m-l-xs"></i></button>
            <ul class="dropdown-menu">
                <li>
                    <?php
                    $theme_arr = __('option_themes', true);
                    if (!isset($theme_arr[11]))
                    {
                        $theme_arr[11] = 'Theme 11';
                    }
                    ksort($theme_arr);
                    $selected_theme = $tpl['option_arr']['o_theme'];
                    if($controller->_get->check('theme'))
                    {
                        $selected_theme = $controller->_get->toString('theme');
                    }
                    $i = 1;
                    foreach($theme_arr as $k => $v)
                    {
                        $is_used = false;
                        if('theme' . $k == $selected_theme)
                        {
                            $is_used = true;
                        }
                        $img = PJ_IMG_PATH . 'backend/themes/theme' . $k . '.jpg';
                        if(!is_file($img))
                        {
                            $img = PJ_IMG_PATH . 'backend/themes/theme.png';
                        }
                        ?>
                        <a href="preview.php?cid=<?php echo $controller->getCalendarId(); ?>&switch=1&multi=1&theme=<?php echo 'theme' . $k; ?>" class="thumbnail<?php echo $is_used ? ' active' : null;?>" data-theme="<?php echo pjSanitize::html($v);?>" data-index="theme<?php echo $k; ?>">
                            <img src="<?php echo $img;?>" alt="">
                        </a>
                        <?php
                        if ($i % 2 == 0 && $i < count($theme_arr))
                        {
                            ?>
                            </li><li>
                            <?php
                        }
                        $i++;
                    }
                    ?>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-1of3 text-center">
        <a class="device-view active" href="#" data-device="desktop"><i class="fa fa-desktop"></i></a>

        <div class="device-view-holder">
            <a class="device-view" href="#" data-device="tablet" data-orientation="portrait"><i class="fa fa-tablet"></i></a>
            <a class="device-view device-view-rotate" href="#" data-device="tablet" data-orientation="landscape"><i class="fa fa-tablet"></i></a>
        </div>

        <div class="device-view-holder">
            <a class="device-view" href="#" data-device="phone" data-orientation="portrait"><i class="fa fa-mobile phone"></i></a>
            <a class="device-view device-view-rotate" href="#" data-device="phone" data-orientation="landscape"><i class="fa fa-mobile phone"></i></a>
        </div>
    </div>

    <div class="col-1of3 text-right">
        <a href="preview.php?cid=<?php echo $controller->getCalendarId(); ?>&switch=1&multi=1&theme=<?php echo 'theme' . str_replace('theme', '', $selected_theme); ?>" class="btn btn-secondary open-new-window" target="_blank"><i class="fa fa-eye m-r-xs"></i> <?php __('script_preview_your_website') ?></a>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionInstall" class="btn btn-secondary"><i class="fa fa-wrench m-r-xs"></i> <?php __('script_install_your_website') ?></a>
    </div>
</div>

<iframe id="iframeEditor" class="iframe-editor" src="preview.php?cid=<?php echo $controller->getCalendarId(); ?>&switch=1&multi=1&theme=<?php echo 'theme' . str_replace('theme', '', $selected_theme); ?>"></iframe>

<div id="iframeDevice" class="hidden">
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-sm-10">
					<h2 id="device_title"></h2>
				</div>
			</div>
			<p class="m-b-none"><i class="fa fa-info-circle"></i> <span id="device_info"></span></p>
		</div>
	</div>

	<div class="row wrapper wrapper-content">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-content">
					<br>
					<div id="iframeHolder"></div>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="hidden" id="phone_portrait"><?php __('plugin_base_editor_phone_portrait'); ?></div>
<div class="hidden" id="phone_landscape"><?php __('plugin_base_editor_phone_landscape'); ?></div>
<div class="hidden" id="tablet_portrait"><?php __('plugin_base_editor_tablet_portrait'); ?></div>
<div class="hidden" id="tablet_landscape"><?php __('plugin_base_editor_tablet_landscape'); ?></div>
<div class="hidden" id="phone_portrait_info"><?php __('plugin_base_editor_phone_portrait_info'); ?></div>
<div class="hidden" id="phone_landscape_info"><?php __('plugin_base_editor_phone_landscape_info'); ?></div>
<div class="hidden" id="tablet_portrait_info"><?php __('plugin_base_editor_tablet_portrait_info'); ?></div>
<div class="hidden" id="tablet_landscape_info"><?php __('plugin_base_editor_tablet_landscape_info'); ?></div>

<?php
$tsbcT = function ($key, $default) {
    $v = __($key, true);
    return (is_string($v) && $v !== '' && $v !== $key) ? $v : $default;
};
$tsbc_active_theme = 'theme' . str_replace('theme', '', $controller->_get->check('theme') ? $controller->_get->toString('theme') : $tpl['option_arr']['o_theme']);
$tsbc_theme11_defaults = pjAppController::getTheme11Defaults();
$tsbc_theme11_saved = pjAppController::getTheme11Colors(isset($tpl['option_arr']['o_theme11_colors']) ? $tpl['option_arr']['o_theme11_colors'] : '');
$tsbc_labels = array(
    'title' => array('theme11_design_title', 'Theme 11 design'),
    'info' => array('theme11_design_info', 'Changes appear in the preview straight away. Press Save to publish them to your website.'),
    'presets' => array('theme11_palettes', 'Colour palettes'),
    'brand' => array('theme11_brand', 'Brand'),
    'surfaces' => array('theme11_surfaces', 'Surfaces'),
    'text' => array('theme11_text', 'Text'),
    'c_soft' => array('theme11_c_soft', 'Soft areas / chips'),
    'colors' => array('theme11_colors', 'Colors'),
    'typography' => array('theme11_typography', 'Typography'),
    'shape' => array('theme11_shape', 'Shape'),
    'access' => array('theme11_access', 'Contrast check'),
    'c_primary' => array('theme11_c_primary', 'Brand color'),
    'c_accent' => array('theme11_c_accent', 'Accent'),
    'c_bg' => array('theme11_c_bg', 'Page background'),
    'c_surface' => array('theme11_c_cards', 'Cards'),
    'c_heading' => array('theme11_c_heading', 'Headings'),
    'c_text' => array('theme11_c_text', 'Body text'),
    'c_muted' => array('theme11_c_muted', 'Secondary text'),
    'c_border' => array('theme11_c_border', 'Borders'),
    'font_heading' => array('theme11_font_heading', 'Heading font'),
    'font_body' => array('theme11_font_body', 'Body font'),
    'radius' => array('theme11_radius', 'Corner radius'),
    'button' => array('theme11_button', 'Buttons'),
    'btn_pill' => array('theme11_btn_pill', 'Pill'),
    'btn_rounded' => array('theme11_btn_rounded', 'Rounded'),
    'btn_square' => array('theme11_btn_square', 'Square'),
    'save' => array('theme11_save', 'Save'),
    'reset' => array('theme11_reset', 'Reset to default'),
    'close' => array('theme11_close', 'Close'),
    'saved' => array('theme11_saved', 'Colors saved.'),
    'was_reset' => array('theme11_was_reset', 'Colors reset to the default.'),
    'error' => array('theme11_error', 'Could not save. Please try again.'),
    'chk_button' => array('theme11_chk_button', 'Button text on brand color'),
    'chk_text' => array('theme11_chk_text', 'Body text on cards'),
    'chk_muted' => array('theme11_chk_muted', 'Secondary text on cards'),
    'chk_brand' => array('theme11_chk_brand', 'Brand color on page background'),
    'low' => array('theme11_low', 'Low contrast - hard to read'),
);
$tsbc_t = array();
foreach ($tsbc_labels as $k => $pair)
{
    $tsbc_t[$k] = $tsbcT($pair[0], $pair[1]);
}
?>
<style>
#tsbcColorsBtn{position:fixed;right:0;top:150px;z-index:1400;display:flex;align-items:center;gap:8px;padding:11px 20px 11px 16px;border:0;border-radius:10px 0 0 10px;background:#0f5a1c;color:#fff;font-size:15px;font-weight:600;line-height:1;box-shadow:-3px 4px 14px rgba(0,0,0,.2);cursor:pointer}
#tsbcColorsBtn:hover{background:#0b4515}
#tsbcColorsBtn.hidden,#tsbcColorsBtn.tsbc-tab-off{display:none}
#tsbcColorsPanel{position:fixed;top:0;right:0;bottom:0;width:320px;max-width:100%;z-index:1500;background:#fff;border-left:1px solid #e5e7eb;box-shadow:-10px 0 30px rgba(0,0,0,.14);display:none;flex-direction:column}
#tsbcColorsPanel.open{display:flex}
#tsbcColorsPanel .tsbc-head{display:flex;align-items:center;gap:10px;height:49px;flex:none;padding:0 16px 0 18px;border-bottom:1px solid #e5e7eb;color:#4b5563}
#tsbcColorsPanel .tsbc-head h3{margin:0;flex:1;font-size:15px;font-weight:700;color:#374151}
#tsbcColorsPanel .tsbc-close{border:0;background:none;font-size:17px;line-height:1;color:#4b5563;cursor:pointer;padding:4px}
#tsbcColorsPanel .tsbc-body{flex:1;overflow-y:auto;padding:16px 18px 20px}
#tsbcColorsPanel .tsbc-info{margin:0 0 14px;font-size:12.5px;line-height:1.55;color:#6b7280}
#tsbcColorsPanel h5{margin:20px 0 8px;font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#6b7280}
#tsbcColorsPanel h5.tsbc-first{margin-top:4px}
#tsbcColorsPanel hr{margin:18px 0 0;border:0;border-top:1px solid #eceef1}
#tsbcColorsPanel .tsbc-presets{display:grid;grid-template-columns:1fr 1fr;gap:8px}
#tsbcColorsPanel .tsbc-preset{display:flex;align-items:center;gap:8px;border:1.5px solid #e5e7eb;border-radius:9px;background:#fff;padding:6px 8px;font-size:12.5px;font-weight:600;color:#1f2937;cursor:pointer;text-align:left;min-width:0}
#tsbcColorsPanel .tsbc-preset:hover{border-color:#cbd0d6}
#tsbcColorsPanel .tsbc-preset.on{border-color:#2bb5a0;box-shadow:0 0 0 3px rgba(43,181,160,.16)}
#tsbcColorsPanel .tsbc-preset i{display:flex;flex:none;width:46px;height:24px;border-radius:6px;overflow:hidden}
#tsbcColorsPanel .tsbc-preset s{flex:1;text-decoration:none}
#tsbcColorsPanel .tsbc-preset span{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
#tsbcColorsPanel .tsbc-fld{display:flex;align-items:center;justify-content:space-between;gap:10px;margin:9px 0;font-size:13px;color:#374151}
#tsbcColorsPanel .tsbc-fld label{margin:0;font-weight:400}
#tsbcColorsPanel .tsbc-clr{display:flex;align-items:center;gap:8px}
#tsbcColorsPanel .tsbc-fld input[type=color]{width:44px;height:34px;padding:2px;border:1px solid #d7dbe0;border-radius:6px;background:#fff;cursor:pointer}
#tsbcColorsPanel .tsbc-hex{width:88px;height:34px;padding:0 9px;border:1px solid #d7dbe0;border-radius:6px;font-family:Menlo,Consolas,monospace;font-size:12.5px;color:#374151;background:#fff}
#tsbcColorsPanel .tsbc-hex.tsbc-bad{border-color:#dc2626;background:#fef2f2}
#tsbcColorsPanel .tsbc-fld select{max-width:150px}
#tsbcColorsPanel .tsbc-seg button{border:1px solid #d1d5db;background:#fff;padding:4px 9px;font-size:12px;cursor:pointer;margin-left:-1px}
#tsbcColorsPanel .tsbc-seg button.on{background:#111827;color:#fff;border-color:#111827}
#tsbcColorsPanel .tsbc-chk{display:flex;justify-content:space-between;gap:8px;font-size:12px;margin:5px 0}
#tsbcColorsPanel .tsbc-chk b.ok{color:#15803d}
#tsbcColorsPanel .tsbc-chk b.bad{color:#b91c1c}
#tsbcColorsPanel .tsbc-foot{flex:none;padding:10px 16px 14px;border-top:1px solid #e5e7eb;background:#fff}
#tsbcColorsPanel .tsbc-msg{min-height:16px;margin:0 0 8px;font-size:12px;text-align:right}
#tsbcColorsPanel .tsbc-actions{display:flex;justify-content:flex-end;gap:10px}
#tsbcColorsPanel .tsbc-btn{height:40px;padding:0 18px;border-radius:5px;font-size:15px;font-weight:600;cursor:pointer;line-height:1}
#tsbcColorsPanel .tsbc-btn-reset{background:#fff;border:1.5px solid #ef8a2b;color:#ef8a2b}
#tsbcColorsPanel .tsbc-btn-reset:hover{background:#fff6ec}
#tsbcColorsPanel .tsbc-btn-save{background:#0f5a1c;border:1.5px solid #0f5a1c;color:#fff}
#tsbcColorsPanel .tsbc-btn-save:hover{background:#0b4515}
</style>
<button type="button" id="tsbcColorsBtn" class="<?php echo $tsbc_active_theme == 'theme11' ? '' : 'hidden'; ?>"><i class="fa fa-paint-brush"></i> <?php echo htmlspecialchars($tsbcT('theme11_customize', 'Customize design')); ?></button>
<div id="tsbcColorsPanel" role="dialog" aria-label="<?php echo htmlspecialchars($tsbc_t['title']); ?>"></div>
<script type="text/javascript">
window.tsbTheme11 = <?php echo json_encode(array(
    'defaults' => $tsbc_theme11_defaults,
    'saved' => $tsbc_theme11_saved,
    'fonts' => pjAppController::getTheme11Fonts(),
    't' => $tsbc_t,
    'csrf' => pjAppController::getCsrfToken()
), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
</script>
