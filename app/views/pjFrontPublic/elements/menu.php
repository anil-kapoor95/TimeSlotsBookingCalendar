<?php
$action_name = $controller->_get->toString('action');
?>
<div class="pj-calendar">
    <div class="pj-calendar-views">
    	<?php
    	if(in_array($action_name, array('pjActionLogin', 'pjActionProfile', 'pjActionForgot')))
    	{
        	?>
        	<div class="btn-group pull-left">
        		<a href="#" class="btn btn-primary pull-left pjTsSelectorCalendar"><span class="glyphicon glyphicon-chevron-left"></span> <?php __('front_button_back_calendar', false, true); ?></a>
        	</div>
        	<?php
    	}
        ?>
    	<div class="btn-group pull-right" role="group" aria-label="...">
    		<?php
    		if($controller->isFrontLogged())
    		{
    		    ?>
    			<a href="#" class="btn btn-primary pjTsProfile"><span class="glyphicon glyphicon-user"></span> <?php __('front_btn_profile')?></a>
    			<a href="#" class="btn btn-primary pjTsLogout"><span class="glyphicon glyphicon-log-out"></span> <?php __('front_btn_logout')?></a>
    			<?php
    		}else{
    			?>
    			<a href="#" class="btn btn-primary pjTsLogin"><span class="glyphicon glyphicon-log-in"></span> <?php __('front_btn_login')?></a>
    			<?php
    		}
    		?>
    		<?php
    		if ((!$controller->_get->check('hide') || ($controller->_get->check('hide') && $controller->_get->toInt('hide') !== 1)) && isset($tpl['locale_arr']) && is_array($tpl['locale_arr']) && count($tpl['locale_arr']) > 1)
    		{
    		    ?>
    		    <div class="btn-group pjTsLanguage">
                	<button type="button" class="btn btn-primary dropdown-toggle pjTsBtnNav" data-toggle="dropdown" aria-expanded="false">
                		<?php
                		$selected_title = null;
                		$selected_src = NULL;
                		foreach ($tpl['locale_arr'] as $locale)
                		{
                			if ($controller->getLocaleId() == $locale['id'])
                			{
                				$selected_title = $locale['language_iso'];
                				$lang_iso = explode("-", $selected_title);
                				if(isset($lang_iso[1]))
                				{
                					$selected_title = $lang_iso[1];
                				}
                				if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
                				{
                					$selected_src = PJ_INSTALL_URL . $locale['flag'];
                				} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
                					$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
                				}
                				break;
                			}
                		}
                		?> 
                		<img src="<?php echo $selected_src; ?>" alt="">
                		<span class="title"><?php echo mb_strtoupper($selected_title, 'UTF-8'); ?></span>
                		<span class="caret"></span>
                	</button>
                	<ul class="dropdown-menu" role="menu">
                	<?php
                	foreach ($tpl['locale_arr'] as $locale)
                	{
                		$selected_src = NULL;
                		if (!empty($locale['flag']) && is_file(PJ_INSTALL_PATH . $locale['flag']))
                		{
                			$selected_src = PJ_INSTALL_URL . $locale['flag'];
                		} elseif (!empty($locale['file']) && is_file(PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'])) {
                			$selected_src = PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $locale['file'];
                		}
                		?>
                		<li>
                			<a href="#" class="pjTsSelectorLocale<?php echo $controller->getLocaleId() == $locale['id'] ? ' pjTsBtnActive' : NULL; ?>" data-id="<?php echo $locale['id']; ?>" data-dir="<?php echo $locale['dir']; ?>">
                				<img src="<?php echo $selected_src; ?>" alt="">
                				<?php echo pjSanitize::html($locale['name']); ?>
                			</a>
                		</li>
                		<?php
                	}
                	?>
                	</ul>
                </div>
    		    <?php
    		}
    		?>
    	</div>
    </div>
</div>
<br/>