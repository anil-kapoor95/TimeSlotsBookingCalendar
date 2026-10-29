<?php
$controller_name = $controller->_get->toString('controller');
$action_name = $controller->_get->toString('action');
$id = $controller->_get->toString('id');
?>
<ul class="nav nav-tabs">
	<li<?php echo $controller_name == 'pjAdminCalendars' && $action_name == 'pjActionUpdate' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminCalendars&amp;action=pjActionUpdate"><?php __('lblCalendar');?></a></li>
	<?php
	if(pjAuth::factory('pjAdminTime', 'pjActionIndex')->hasAccess())
	{
		?>
		<li<?php echo $controller_name == 'pjAdminTime' && $action_name == 'pjActionIndex' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionIndex"><?php __('tabWorkingTime');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminTime', 'pjActionCustom')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminTime' && $action_name == 'pjActionCustom' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTime&amp;action=pjActionCustom"><?php __('tabCustomWorkingTime');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminOptions', 'pjActionIndex')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminOptions' && $action_name == 'pjActionIndex' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionIndex"><?php __('tabBookingOptions');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminOptions', 'pjActionPayments')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminOptions' && $action_name == 'pjActionPayments' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionPayments"><?php __('menuPayments');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminOptions', 'pjActionTerms')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminOptions' && $action_name == 'pjActionTerms' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionTerms"><?php __('tabTerms');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminOptions', 'pjActionNotifications')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminOptions' && $action_name == 'pjActionNotifications' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionNotifications"><?php __('menuNotifications');?></a></li>
		<?php
	}
	if(pjAuth::factory('pjAdminOptions', 'pjActionBookingForm')->hasAccess())
	{
	    ?>
		<li<?php echo $controller_name == 'pjAdminOptions' && $action_name == 'pjActionBookingForm' ? ' class="active"' : NULL;?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminOptions&amp;action=pjActionBookingForm"><?php __('menuBookingForm');?></a></li>
		<?php
	}
	?>
</ul><!-- /.nav nav-tabs -->