<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdmin extends pjAppController
{
	public $defaultUser = 'admin_user';
	
	public $requireLogin = true;
	
	protected $adminBooking = 'Admin_Booking';
	
	public function __construct($requireLogin=null)
	{
		$this->setLayout('pjActionAdmin');
		
		if (!is_null($requireLogin) && is_bool($requireLogin))
		{
		    $this->requireLogin = $requireLogin;
		}
		
		if ($this->requireLogin)
		{
			$_get = pjRegistry::getInstance()->get('_get');
		    if (!$this->isLoged() && !in_array(@$_get->toString('action'), array('pjActionLogin', 'pjActionForgot', 'pjActionValidate', 'pjActionExportFeed')))
		    {
		        if (!$this->isXHR())
		        {
		            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjBase&action=pjActionLogin");
		        } else {
		            header('HTTP/1.1 401 Unauthorized');
		            exit;
		        }
		    }
		}
		$ref_inherits_arr = array();
		if ($this->isXHR() && isset($_SERVER['HTTP_REFERER'])) {
		    $http_refer_arr = parse_url($_SERVER['HTTP_REFERER']);
		    parse_str($http_refer_arr['query'], $arr);
		    if (isset($arr['controller']) && isset($arr['action'])) {
		        parse_str($_SERVER['QUERY_STRING'], $query_string_arr);
		        $key = $query_string_arr['controller'].'_'.$query_string_arr['action'];
		        $cnt = pjAuthPermissionModel::factory()->where('`key`', $key)->findCount()->getData();
		        if ($cnt <= 0) {
		            $ref_inherits_arr[$query_string_arr['controller'].'::'.$query_string_arr['action']] = $arr['controller'].'::'.$arr['action'];
		        }
		    }
		}
		$inherits_arr = array(
		    'pjBasePermissions::pjActionResetPermission' => 'pjBasePermissions::pjActionUserPermission',
		    
		    'pjAdminOptions::pjActionPaymentOptions' => 'pjAdminOptions::pjActionPayments',
		    'pjAdminOptions::pjActionNotificationsGetMetaData' => 'pjAdminOptions::pjActionNotifications',
		    'pjAdminOptions::pjActionNotificationsGetContent' => 'pjAdminOptions::pjActionNotifications',
		    'pjAdminOptions::pjActionNotificationsSetContent' => 'pjAdminOptions::pjActionNotifications',
		    
		    'pjAdminBookings::pjActionGetBooking' => 'pjAdminBookings::pjActionIndex',
		    'pjAdminBookings::pjActionItemAdd' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionItemDelete' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionGetSlots' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionItemGet' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionGetPrice' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionItemAdd' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionItemDelete' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionGetSlots' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionItemGet' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionGetPrice' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionReminderEmail' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionReminderSms' => 'pjAdminBookings::pjActionUpdate',
		    
		    'pjAdminBookings::pjActionGetPMs' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionGetPMs' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionGetBookingFields' => 'pjAdminBookings::pjActionCreate',
		    'pjAdminBookings::pjActionGetBookingFields' => 'pjAdminBookings::pjActionUpdate',
		    'pjAdminBookings::pjActionCheckOverlapping' => 'pjAdminBookings::pjActionUpdate',
		    
		    'pjAdminClients::pjActionGetClient' => 'pjAdminClients::pjActionIndex',
		    'pjAdminClients::pjActionCheckEmail' => 'pjAdminClients::pjActionCreate',
		    'pjAdminClients::pjActionCheckEmail' => 'pjAdminClients::pjActionUpdate',
		    'pjAdminClients::pjActionSaveClient' => 'pjAdminClients::pjActionUpdate',
		    'pjAdminClients::pjActionStatusClient' => 'pjAdminClients::pjActionUpdate',
		    
		    'pjAdminTime::pjActionGetPrices' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionSetPrices' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionGetLunchBreak' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionGenerateSlots' => 'pjAdminTime::pjActionIndex',
		    'pjAdminTime::pjActionGetDate' => 'pjAdminTime::pjActionCustom',
		    'pjAdminTime::pjActionGetSlots' => 'pjAdminTime::pjActionCustom',
		    'pjAdminTime::pjActionGetCustomLunchBreak' => 'pjAdminTime::pjActionCustom',
		    'pjAdminTime::pjActionGenerateCustomSlots' => 'pjAdminTime::pjActionCustom',
		    
		    'pjAdmin::pjActionRedirect' => 'pjAdminCalendars::pjActionUpdate',
		    'pjAdmin::pjActionRedirect' => 'pjAdminOptions::pjActionIndex',
		    'pjAdmin::pjActionRedirect' => 'pjAdminTime::pjActionIndex',
		    
		    'pjAdminCalendars::pjActionCheckAssignCalendar' => 'pjAdminCalendars::pjActionUpdate',
		    'pjAdminCalendars::pjActionCheckAssignCalendar' => 'pjAdminOptions::pjActionIndex',
		    'pjAdminCalendars::pjActionCheckAssignCalendar' => 'pjAdminTime::pjActionIndex',
			'pjAdminCalendars::pjActionCheckAssignCalendar' => 'pjAdminBookings::pjActionCreate',
			'pjAdminCalendars::pjActionCheckAssignCalendar' => 'pjAdminBookings::pjActionIndex'
		    
		);
		if ($_REQUEST['controller'] == 'pjAdminOptions' && isset($_REQUEST['next_action'])) {
		    $inherits_arr['pjAdminOptions::pjActionUpdate'] = 'pjAdminOptions::'.$_REQUEST['next_action'];
		}
		$inherits_arr = array_merge($ref_inherits_arr, $inherits_arr);
		pjRegistry::getInstance()->set('inherits', $inherits_arr);
	}
	
	public function beforeFilter()
	{
	    parent::beforeFilter();
	    
	    if (!pjAuth::factory()->hasAccess())
	    {
	        if (!$this->isXHR())
	        {
	            $this->sendForbidden();
	            return false;
	        } else {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
	        }
	    }
	    
	    if (self::isPost())
	    {
	    	pjAppController::validateCsrfToken();
	    }
	
	    return true;
	}
	
	public function afterFilter()
	{
	    parent::afterFilter();
	    $this->appendJs('index.php?controller=pjBase&action=pjActionMessages', PJ_INSTALL_URL, true);
	}
	
	public function beforeRender()
	{
		
	}
	
	public function setLocalesData()
	{
	    $locale_arr = pjLocaleModel::factory()
	    ->select('t1.*, t2.file')
	    ->join('pjBaseLocaleLanguage', 't2.iso=t1.language_iso', 'left')
	    ->where('t2.file IS NOT NULL')
	    ->orderBy('t1.sort ASC')->findAll()->getData();
	    
	    $lp_arr = array();
	    foreach ($locale_arr as $item)
	    {
	        $lp_arr[$item['id']."_"] = $item['file'];
	    }
	    $this->set('lp_arr', $locale_arr);
	    $this->set('locale_str', pjAppController::jsonEncode($lp_arr));
	    $this->set('is_flag_ready', $this->requestAction(array('controller' => 'pjBaseLocale', 'action' => 'pjActionIsFlagReady'), array('return')));
	}
	
	public function pjActionVerifyAPIKey()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method is not allowed.'));
	        }
	        
	        $option_key = $this->_post->toString('key');
	        if (!array_key_exists($option_key, $this->option_arr))
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Option cannot be found.'));
	        }
	        
	        $option_value = $this->_post->toString('value');
	        if(empty($option_value))
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'API key is empty.'));
	        }
	        
	        $html = '';
	        $isValid = false;
	        switch ($option_key)
	        {
	            case 'o_google_maps_api_key':
	                $address = preg_replace('/\s+/', '+', $this->option_arr['o_timezone']);
	                $api_key_str = $option_value;
	                $gfile = "https://maps.googleapis.com/maps/api/geocode/json?key=".$api_key_str."&address=".$address;
	                $Http = new pjHttp();
	                $response = $Http->request($gfile)->getResponse();
	                $geoObj = pjAppController::jsonDecode($response);
	                $geoArr = (array) $geoObj;
	                if ($geoArr['status'] == 'OK')
	                {
	                    $html = '<img src="' . $url . '" class="img-responsive" />';
	                    $isValid = true;
	                }
	                break;
	            default:
	                // API key for an unknown service. We can't verify it so we assume it's correct.
	                $isValid = true;
	        }
	        
	        if ($isValid)
	        {
	            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Key is correct!', 'html' => $html));
	        }
	        else
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Key is not correct!', 'html' => $html));
	        }
	    }
	    exit;
	}
	
	public function pjActionIndex()
	{
	    $this->checkLogin();
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjAuthUserModel = pjAuthUserModel::factory();
	    $pjBookingModel = pjBookingModel::factory();
	    $pjBookingSlotModel = pjBookingSlotModel::factory();
	    
	    $query = '';
	    if(!$this->isMasterAdmin())
	    {
	        $query = sprintf(" AND (t1.calendar_id IN (SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id = %u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId());
	    }
	    
	    $next_7_days_confirmed = $pjBookingModel	    
	    ->where('t1.booking_status', 'confirmed')
	    ->where(sprintf("(t1.id IN(SELECT t2.booking_id FROM `%s` AS `t2` WHERE `t2`.booking_date BETWEEN CURDATE() AND DATE(NOW()) + INTERVAL +7 DAY)%s)", $pjBookingSlotModel->getTable(), $query))
	    ->orderBy("t1.created DESC")
	    ->findCount()->getData();
	    $this->set('next_7_days_confirmed', $next_7_days_confirmed);
	    
	    $next_7_days_pending = $pjBookingModel
	    ->reset()
	    ->where('t1.booking_status', 'pending')
	    ->where(sprintf("(t1.id IN(SELECT t2.booking_id FROM `%s` AS `t2` WHERE `t2`.booking_date BETWEEN CURDATE() AND DATE(NOW()) + INTERVAL +7 DAY)%s)", $pjBookingSlotModel->getTable(), $query))
	    ->orderBy("t1.created DESC")
	    ->findCount()->getData();
	    $this->set('next_7_days_pending', $next_7_days_pending);
	    
	    $first_date_of_month = date('Y-m-01');
	    $last_date_of_month = date('Y-m-t');
	    
	    $tmp_arr = $pjBookingModel
	    ->reset()
	    ->whereIn('t1.booking_status', array('confirmed', 'pending'))
	    ->where(sprintf("(t1.id IN(SELECT `t2`.booking_id FROM `%s` AS `t2` WHERE `t2`.booking_date BETWEEN '%s' AND '%s')%s)", $pjBookingSlotModel->getTable(), $first_date_of_month, $last_date_of_month, $query))
	    ->findAll()->getData();
	    $total_amount_this_month = 0;
	    $bookings_this_month = count($tmp_arr);
	    foreach($tmp_arr as $k => $v)
	    {
	        $total_amount_this_month += $v['booking_total'];
	    }
	    $this->set('bookings_this_month', $bookings_this_month);
	    $this->set('total_amount_this_month', $total_amount_this_month);
	    
	    $new_clients_this_month = $pjAuthUserModel->where('t1.role_id', 3)->where('t1.email IS NOT NULL')->where(sprintf("(DATE(t1.created) BETWEEN '%s' AND '%s')", $first_date_of_month, $last_date_of_month))->findCount()->getData();
	    $total_clients = $pjAuthUserModel->reset()->where('t1.role_id', 3)->where('t1.email IS NOT NULL')->findCount()->getData();
	    $this->set('new_clients_this_month', $new_clients_this_month);
	    $this->set('total_clients', $total_clients);
	    
	    $pjBookingModel->reset();
	    if(!$this->isMasterAdmin())
	    {
	        $pjBookingModel->where(sprintf("(t1.calendar_id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id = %u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    $upcoming_bookings = $pjBookingModel
	    ->select(sprintf("t1.*, t2.content as calendar_title, t4.name AS customer_name, t4.email AS customer_email, t4.phone AS customer_phone,
                    (SELECT `TBS`.start_ts FROM `%1\$s` AS `TBS` WHERE `TBS`.booking_id=t1.id ORDER BY `TBS`.start_ts ASC LIMIT 1 ) AS min_start_ts", $pjBookingSlotModel->getTable()))
		->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.calendar_id AND t2.field='title' AND t2.locale=%u", $this->getLocaleId()), 'left')
		->join('pjClient', "t1.client_id=t3.id", 'left')
		->join('pjAuthUser', "t3.foreign_id=t4.id", 'left')
		->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` >= CURDATE())", $pjBookingSlotModel->getTable()))
		->whereIn('t1.booking_status', array('confirmed', 'pending'))
		->orderBy("min_start_ts ASC")
		->limit(5)
		->findAll()
		->getData();
		$booking_id_arr = $pjBookingModel->findAll()->getDataPair(null, 'id');
		$items = array();
		if(!empty($booking_id_arr))
		{
		    $bs_arr = $pjBookingSlotModel->reset()->whereIn('booking_id', $booking_id_arr)->findAll()->getData();
		    foreach($bs_arr as $item)
		    {
		        $items[$item['booking_id']][] = array(date($this->option_arr['o_date_format'], strtotime($item['booking_date'])), date($this->option_arr['o_time_format'], $item['start_ts']), date($this->option_arr['o_time_format'], $item['end_ts']), $item['cnt']);
		    }
		}
		$this->set('upcoming_bookings', $upcoming_bookings);
		$this->set('upcoming_items', $items);
		
		$pjBookingModel->reset();
		if(!$this->isMasterAdmin())
		{
		    $pjBookingModel->where(sprintf("(t1.calendar_id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id = %u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
		}
		$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` = CURDATE())", $pjBookingSlotModel->getTable()));
		$bookings_today = $pjBookingModel->whereIn('t1.booking_status', array('confirmed', 'pending'))->findCount()->getData();
		$this->set('bookings_today', $bookings_today);
		
		$pjBookingModel->reset();
		if(!$this->isMasterAdmin())
		{
		    $pjBookingModel->where(sprintf("(t1.calendar_id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id = %u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
		}
		$bookings_made = $pjBookingModel->findCount()->getData();
		$this->set('bookings_made', $bookings_made);
		
		$pjBookingModel->reset();
		if(!$this->isMasterAdmin())
		{
		    $pjBookingModel->where(sprintf("(t1.calendar_id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id = %u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
		}
		$latest_bookings = $pjBookingModel
		->select(sprintf("t1.*, t2.content as calendar_title, t4.name AS customer_name, t4.email AS customer_email, t4.phone AS customer_phone,
                    (SELECT `TBS`.start_ts FROM `%1\$s` AS `TBS` WHERE `TBS`.booking_id=t1.id ORDER BY `TBS`.start_ts ASC LIMIT 1 ) AS min_start_ts", $pjBookingSlotModel->getTable()))
		->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.calendar_id AND t2.field='title' AND t2.locale=%u", $this->getLocaleId()), 'left')
		->join('pjClient', "t1.client_id=t3.id", 'left')
		->join('pjAuthUser', "t3.foreign_id=t4.id", 'left')
		->orderBy("t1.created DESC")
		->limit(5)
		->findAll()
		->getData();
		$this->set('latest_bookings', $latest_bookings);
		
		$booking_id_arr = $pjBookingModel->findAll()->getDataPair(null, 'id');
		$items = array();
		if(!empty($booking_id_arr))
		{
		    $bs_arr = $pjBookingSlotModel->reset()->whereIn('booking_id', $booking_id_arr)->findAll()->getData();
		    foreach($bs_arr as $item)
		    {
		        $items[$item['booking_id']][] = array(date($this->option_arr['o_date_format'], strtotime($item['booking_date'])), date($this->option_arr['o_time_format'], $item['start_ts']), date($this->option_arr['o_time_format'], $item['end_ts']), $item['cnt']);
		    }
		}
		$this->set('latest_items', $items);
		
		$this->appendJs('pjAdmin.js');
	}
	
	public function pjActionRedirect()
	{
	    if ($this->_get->toInt('calendar_id') > 0)
	    {
	        if ((int) pjCalendarModel::factory()->where('t1.id', $this->_get->toInt('calendar_id'))->findCount()->getData() == 1)
	        {
	            $this->setCalendarId($this->_get->toInt('calendar_id'));
	        }
	    }
	    $qs = NULL;
	    if ($this->_get->check('nextParams'))
	    {
	        $nextParams = $this->_get->toString('nextParams');
	        parse_str($nextParams, $params);
	        if (!empty($params))
	        {
	            $qs = http_build_query($params);
	            $qs = "&" . $qs;
	        }
	    }
	    pjUtil::redirect(sprintf("%sindex.php?controller=%s&action=%s%s", PJ_INSTALL_URL, $this->_get->toString('nextController'), $this->_get->toString('nextAction'), $qs));
	    exit;
	}
	public function rebuildPermissions()
	{
	    $this->setLayout('pjActionEmpty');
	    
	    $pjAuthRolePermissionModel = pjAuthRolePermissionModel::factory();
	    $pjAuthUserPermissionModel = pjAuthUserPermissionModel::factory();
	    
	    $permissions = pjAuthPermissionModel::factory()->findAll()->getDataPair('key', 'id');
	    
	    $roles = array(1 => 'admin', 2 => 'editor');
	    foreach ($roles as $role_id => $role)
	    {
	        if (isset($GLOBALS['CONFIG'], $GLOBALS['CONFIG']["role_permissions_{$role}"])
	        && is_array($GLOBALS['CONFIG']["role_permissions_{$role}"])
	        && !empty($GLOBALS['CONFIG']["role_permissions_{$role}"]))
	        {
	            $pjAuthRolePermissionModel->reset()->where('role_id', $role_id)->eraseAll();
	            
	            foreach ($GLOBALS['CONFIG']["role_permissions_{$role}"] as $role_permission)
	            {
	                if($role_permission == '*')
	                {
	                    // Grant full permissions for the role
	                    foreach($permissions as $key => $permission_id)
	                    {
	                        $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
	                    }
	                    break;
	                }
	                else
	                {
	                    $hasAsterix = strpos($role_permission, '*') !== false;
	                    if($hasAsterix)
	                    {
	                        $role_permission = str_replace('*', '', $role_permission);
	                    }
	                    
	                    foreach($permissions as $key => $permission_id)
	                    {
	                        if($role_permission == $key || ($hasAsterix && strpos($key, $role_permission) !== false))
	                        {
	                            $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
	                        }
	                    }
	                }
	            }
	        }
	    }
	    echo 'DONE!';
	    exit;
	}
	
	protected function getSlots($calendar_id, $iso_date, $hash=NULL)
	{
		$t_arr = pjAppController::getDailySlots($calendar_id, $iso_date, $this->option_arr);
		if ($t_arr['is_dayoff'] == 'T')
		{
			# It's Day off
			$this->set('dayoff', true);
			return;
		}
		
		$this->set('price_arr', pjAppController::getPricesDate($calendar_id, $iso_date, $this->option_arr));
		
		$pjBookingSlotModel = pjBookingSlotModel::factory();
		
		# Get booked slots for given date.
		# If 24h, include next date
		$d_arr = array($pjBookingSlotModel->escapeStr($iso_date));
		if ($t_arr['end_ts'] < $t_arr['start_ts'])
		{
			$d_arr[] = date("Y-m-d", strtotime($iso_date) + 86400);
		}
		
		$bs_arr = $pjBookingSlotModel
			->select('t1.*, t2.calendar_id')
			->join('pjBooking', sprintf("t2.id=t1.booking_id AND t2.calendar_id='%u' AND t2.booking_status IN ('pending', 'confirmed')", $calendar_id), 'inner')
			->whereIn('t1.booking_date', $d_arr)
			->where('t2.booking_status !=', 'cancelled')
			->findAll()
			->getData();
		$_arr = array();
		if (isset($hash) && !empty($hash) &&
			isset($_SESSION[$this->adminBooking]) && isset($_SESSION[$this->adminBooking][$hash])
		)
		{
			foreach ($_SESSION[$this->adminBooking][$hash] as $key => $slot)
			{
				if ($slot['booking_date'] == $iso_date)
				{
					$_arr[] = $slot;
				}
			}
		}
		
		$bs_arr = array_merge($bs_arr, $_arr);
		
		$this->set('bs_arr', $bs_arr);
		$this->set('t_arr', $t_arr);
	}
}
?>