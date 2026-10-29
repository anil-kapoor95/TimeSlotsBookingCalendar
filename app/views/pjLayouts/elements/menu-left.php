<?php
$controller_name = $controller->_get->toString('controller');
$action_name = $controller->_get->toString('action');

// Dashboard
$isScriptDashboard = in_array($controller_name, array('pjAdmin')) && in_array($action_name, array('pjActionIndex'));

// Calendars
$isScriptCalendars = in_array($controller_name, array('pjAdminCalendars'));

// Bookings
$isScriptBookingsController = in_array($controller_name, array('pjAdminBookings'));

// Clients
$isScriptClientsController = in_array($controller_name, array('pjAdminClients'));
$isScriptClientsIndex = $isScriptClientsController && in_array($action_name, array('pjActionIndex', 'pjActionCreate', 'pjActionUpdate'));

// Schedule
$isScriptScheduleController = in_array($controller_name, array('pjAdminSchedule'));

// Vouchers
$isScriptVouchersController = in_array($controller_name, array('pjVouchers'));

// Options
$isScriptOptionsController = in_array($controller_name, array('pjAdminOptions'));

// Time
$isScriptTimeController = in_array($controller_name, array('pjAdminTime'));

// Permissions - Vouchers
$hasAccessScriptVouchers = pjAuth::factory('pjVouchers')->hasAccess();

// Permissions - Dashboard
$hasAccessScriptDashboard = pjAuth::factory('pjAdmin', 'pjActionIndex')->hasAccess();

// Permissions - Calendars
$hasAccessCalendarsList = pjAuth::factory('pjAdminCalendars', 'pjActionIndex')->hasAccess();
$hasAccessWorkingTime = pjAuth::factory('pjAdminTime', 'pjActionIndex')->hasAccess();
$hasAccessCustomWorkingTime = pjAuth::factory('pjAdminTime', 'pjActionCustom')->hasAccess();
$hasAccessBookingOptions = pjAuth::factory('pjAdminOptions', 'pjActionIndex')->hasAccess();
$hasAccessPayments = pjAuth::factory('pjAdminOptions', 'pjActionPayments')->hasAccess();
$hasAccessTerms = pjAuth::factory('pjAdminOptions', 'pjActionTerms')->hasAccess();
$hasAccessNotifications = pjAuth::factory('pjAdminOptions', 'pjActionNotifications')->hasAccess();
$hasAccessBookingForm = pjAuth::factory('pjAdminOptions', 'pjActionBookingForm')->hasAccess();
$hasAccessScriptCalendars = pjAuth::factory('pjAdminCalendars')->hasAccess() && ($hasAccessCalendarsList || $hasAccessWorkingTime || $hasAccessCustomWorkingTime || $hasAccessBookingOptions || $hasAccessPayments || $hasAccessTerms || $hasAccessNotifications || $hasAccessBookingForm);

$controller_menu = ''; $action_menu = '';
if($hasAccessCalendarsList && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminCalendars'; $action_menu = 'pjActionIndex';
}
if($hasAccessWorkingTime && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminTime'; $action_menu = 'pjActionIndex';
}
if($hasAccessCustomWorkingTime && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminTime'; $action_menu = 'pjActionCustom';
}
if($hasAccessBookingOptions && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminOptions'; $action_menu = 'pjActionIndex';
}
if($hasAccessPayments && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminOptions'; $action_menu = 'pjActionPayments';
}
if($hasAccessTerms && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminOptions'; $action_menu = 'pjActionTerms';
}
if($hasAccessNotifications && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminOptions'; $action_menu = 'pjActionNotifications';
}
if($hasAccessBookingForm && empty($controller_menu) && empty($action_menu))
{
    $controller_menu = 'pjAdminOptions'; $action_menu = 'pjActionBookingForm';
}

// Permissions - Bookings
$hasAccessScriptBookings          = pjAuth::factory('pjAdminBookings')->hasAccess();

// Permissions - Clients
$hasAccessScriptClients            = pjAuth::factory('pjAdminClients')->hasAccess();
$hasAccessScriptClientsIndex       = pjAuth::factory('pjAdminClients', 'pjActionIndex')->hasAccess();

// Permissions - Schedule
$hasAccessScriptSchedule          = pjAuth::factory('pjAdminSchedule', 'pjActionIndex')->hasAccess();
$hasAccessScriptScheduleWeekly          = pjAuth::factory('pjAdminSchedule', 'pjActionWeekly')->hasAccess();
$hasAccessScriptScheduleMonthly         = pjAuth::factory('pjAdminSchedule', 'pjActionMonthly')->hasAccess();
?>

<?php if ($hasAccessScriptDashboard): ?>
    <li<?php echo $isScriptDashboard ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdmin&amp;action=pjActionIndex"><i class="fa fa-th-large"></i> <span class="nav-label"><?php __('plugin_base_menu_dashboard');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptCalendars): ?>
    <li<?php echo $isScriptCalendars || $isScriptOptionsController || $isScriptTimeController ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $controller_menu;?>&amp;action=<?php echo $action_menu;?>" class="pjTsCheckAssignCalendar"><i class="fa fa-calendar"></i> <span class="nav-label"><?php __('menuCalendars');?></span></a>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptBookings || $hasAccessScriptSchedule): ?>
    <li<?php echo $isScriptBookingsController || $isScriptScheduleController ? ' class="active"' : NULL; ?>>
        <a href="#"><i class="fa fa-check"></i> <span class="nav-label"><?php __('menuBookings');?></span><span class="fa arrow"></span></a>
        <ul class="nav nav-second-level collapse">
            <?php if ($hasAccessScriptSchedule): ?>
                <li<?php echo $isScriptScheduleController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSchedule&amp;action=pjActionIndex"><?php __('menuSchedule');?></a></li>
            <?php endif; ?>

            <?php if ($hasAccessScriptBookings): ?>
                <li<?php echo $isScriptBookingsController ? ' class="active"' : NULL; ?>><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=pjActionIndex"><?php __('menuBookingsList');?></a></li>
            <?php endif; ?>
        </ul>
    </li>
<?php endif; ?>

<?php if ($hasAccessScriptClients): ?>
    <li<?php echo $isScriptClientsIndex ? ' class="active"' : NULL; ?>>
        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminClients&amp;action=pjActionIndex"><i class="fa fa-user"></i> <span class="nav-label"><?php __('menuClients');?></span></a>
    </li>
<?php endif; ?>

<?php if (pjObject::getPlugin('pjVouchers') !== NULL): ?>
    <?php if ($hasAccessScriptVouchers): ?>
        <li<?php echo $isScriptVouchersController ? ' class="active"' : NULL; ?>>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjVouchers&amp;action=pjActionIndex"><i class="fa fa-gift"></i> <span class="nav-label"><?php __('plugin_vouchers_menu_vouchers');?></span></a>
        </li>
    <?php endif; ?>
<?php endif; ?>