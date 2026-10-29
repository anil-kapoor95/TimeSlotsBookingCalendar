<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminSchedule extends pjAdmin
{
	public function pjActionIndex()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (pjAuth::factory('pjAdminSchedule', 'pjActionWeekly')->hasAccess())
	    {
	        if ($this->_get->toInt('calendar_id') > 0)
	        {
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSchedule&action=pjActionWeekly" . "&calendar_id=" . $this->_get->toInt('calendar_id'));
	        }else{
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSchedule&action=pjActionWeekly");
	        }
	    }
	    if (pjAuth::factory('pjAdminSchedule', 'pjActionMonthly')->hasAccess())
	    {
	        if ($this->_get->toInt('calendar_id') > 0)
	        {
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSchedule&action=pjActionMonthly" . "&calendar_id=" . $this->_get->toInt('calendar_id'));
	        }else{
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminSchedule&action=pjActionMonthly");
	        }
	    }
	}
	
	public function pjActionWeekly()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjCalendarModel = pjCalendarModel::factory();
	    if(!$this->isMasterAdmin())
	    {
	        $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    if($this->_get->toInt('calendar_id') > 0)
	    {
	        $pjCalendarModel->where('t1.id', $this->_get->toInt('calendar_id'));
	    }
	    $calendar_ids = $pjCalendarModel->findAll()->getDataPair(null, 'id');
	    
	    $date = date('Y-m-d');
	    if(!$this->_get->isEmpty('date'))
	    {
	        $date = $this->_get->toString('date');
	    }
	    
	    $dates_arr = array();
	    $date_range = pjUtil::getWeekRange($date, $this->option_arr['o_week_start']);
	    foreach (range(0,6) as $i)
	    {
	        $iso_date = date("Y-m-d", strtotime($date_range[0]) + 86400 * $i);
	        $dates_arr[$iso_date] = pjAppController::getTimeByDates($calendar_ids, $iso_date);
	    }
	   
	    $dates_arr = $this->getBookingsData($dates_arr, $calendar_ids);
	   
	    $this->set('dates_arr', $dates_arr);
	    
	    $this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
	    $this->appendJs('pjAdminSchedule.js');
	}
	
	public function pjActionMonthly()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjCalendarModel = pjCalendarModel::factory();
	    if(!$this->isMasterAdmin())
	    {
	        $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    if($this->_get->toInt('calendar_id') > 0)
	    {
	        $pjCalendarModel->where('t1.id', $this->_get->toInt('calendar_id'));
	    }
	    $calendar_ids = $pjCalendarModel->findAll()->getDataPair(null, 'id');
	    
	    $date = date('Y-m-d');
	    if(!$this->_get->isEmpty('date'))
	    {
	        $date = $this->_get->toString('date');
	    }
	    $date_ts = strtotime($date);
	    $month = date('n', $date_ts);
	    $year = date('Y', $date_ts);
	    $date_range = $this->getDaysInMonth($month, $year, $this->option_arr);
	    $dates_arr = array();
	    foreach ($date_range as $iso_date)
	    {
	        $dates_arr[$iso_date] = pjAppController::getTimeByDates($calendar_ids, $iso_date);
	    }
	    $dates_arr = $this->getBookingsData($dates_arr, $calendar_ids);
	    $this->set('dates_arr', $dates_arr);
	    
	    $this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
	    $this->appendJs('pjAdminSchedule.js');
	}
	
	public function pjActionPrintWeekly()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $this->setLayout('pjActionPrint');
	    
	    $pjCalendarModel = pjCalendarModel::factory();
	    if(!$this->isMasterAdmin())
	    {
	        $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    if($this->_get->toInt('calendar_id') > 0)
	    {
	        $pjCalendarModel->where('t1.id', $this->_get->toInt('calendar_id'));
	    }
	    $calendar_ids = $pjCalendarModel->findAll()->getDataPair(null, 'id');
	    
	    $date = date('Y-m-d');
	    if(self::isGet() && $this->_get->check('date'))
	    {
	        $date = $this->_get->toString('date');
	    }
	    
	    $dates_arr = array();
	    $date_range = pjUtil::getWeekRange($date, $this->option_arr['o_week_start']);
	    foreach (range(0,6) as $i)
	    {
	        $iso_date = date("Y-m-d", strtotime($date_range[0]) + 86400 * $i);
	        $dates_arr[$iso_date] = pjAppController::getTimeByDates($calendar_ids, $iso_date);
	    }
	    $dates_arr = $this->getBookingsData($dates_arr, $calendar_ids);
	    $this->set('dates_arr', $dates_arr);
	}
	
	public function pjActionPrintMonthly()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $this->setLayout('pjActionPrint');
	    
	    $pjCalendarModel = pjCalendarModel::factory();
	    if(!$this->isMasterAdmin())
	    {
	        $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    if($this->_get->toInt('calendar_id') > 0)
	    {
	        $pjCalendarModel->where('t1.id', $this->_get->toInt('calendar_id'));
	    }
	    $calendar_ids = $pjCalendarModel->findAll()->getDataPair(null, 'id');
	    
	    $date = date('Y-m-d');
	    if(self::isGet() && $this->_get->check('date'))
	    {
	        $date = $this->_get->toString('date');
	    }
	    $date_ts = strtotime($date);
	    $month = date('n', $date_ts);
	    $year = date('Y', $date_ts);
	    $date_range = $this->getDaysInMonth($month, $year, $this->option_arr);
	    $dates_arr = array();
	    foreach ($date_range as $iso_date)
	    {
	        $dates_arr[$iso_date] = pjAppController::getTimeByDates($calendar_ids, $iso_date);
	    }
	    
	    $dates_arr = $this->getBookingsData($dates_arr, $calendar_ids);
	    $this->set('dates_arr', $dates_arr);
	}
	
	public function pjActionCancelBooking()
	{
	    $this->setAjax(true);
	    
	    if ($this->isXHR())
	    {
	        if (!self::isPost())
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	        }
	        if (!($this->_post->check('id') && pjValidation::pjActionNumeric($this->_post->toInt('id')) && $this->_post->check('booking_id') && pjValidation::pjActionNumeric($this->_post->toInt('id'))))
	        {
	            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing, empty or invalid parameters.'));
	        }
	        
	        pjBookingModel::factory()->where('id', $this->_post->toInt('booking_id'))->limit(1)->modifyAll(array('booking_status' => 'cancelled'));
	        
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	    exit;
	}
	
	public function getDaysInMonth($month, $year, $option_arr)
	{
	    $d_arr = array();
	    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
	    $_date = getdate(mktime(12, 0, 0, $month, 1, $year));
	    $first = $_date["wday"];
	    $day = (int)$option_arr['o_week_start'] + 1 - $first;
	    while ($day > 1)
	    {
	        $day -= 7;
	    }
	    while ($day <= $daysInMonth)
	    {
	        $timestamp = mktime(0, 0, 0, $month, $day, $year);
	        $d_arr[] = date('Y-m-d', $timestamp);
	        $day++;
	    }
	    $row_arr = array_chunk($d_arr, 7);
	    $last_row = end($row_arr);
	    while(count($last_row) < 7)
	    {
	        $last_date = end($last_row);
	        $extra_date = date('Y-m-d', strtotime($last_date) + 86400);
	        $last_row[] = $extra_date;
	        $d_arr[] = $extra_date;
	    }
	    return $d_arr;
	}
	
	public function getBookingsData($dates_arr, $calendar_ids)
	{
	    $bs_arr = array();
	    if(!empty($dates_arr) && !empty($calendar_ids))
	    {
	        reset($dates_arr);
	        $first_iso_date = key($dates_arr); 
	        end($dates_arr);    
	        $last_iso_date = key($dates_arr); 
	        
	        $pjBookingModel = pjBookingModel::factory();
	        $pjBookingSlotModel = pjBookingSlotModel::factory();
	        $tmp_arr = $pjBookingSlotModel
	        ->select("t1.*, t2.booking_total, t2.booking_status, t4.email AS customer_email, t4.name AS customer_name, t4.phone AS customer_phone, t5.content AS calendar_title")
	        ->join("pjBooking", "t1.booking_id=t2.id", "left")
	        ->join('pjClient', "t2.client_id=t3.id", 'left')
	        ->join('pjAuthUser', "t3.foreign_id=t4.id", 'left')
	        ->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t2.calendar_id AND t5.field='title' AND t5.locale=t2.locale_id", 'left')
	        ->where(sprintf("(t1.booking_date BETWEEN '%s' AND '%s')", $first_iso_date, $last_iso_date))
	        ->whereIn("t2.calendar_id", $calendar_ids)
	        ->whereIn('t2.booking_status', array('confirmed', 'pending'))
	        ->orderBy("t1.start_ts ASC")
	        ->findAll()->getData();
	        
	        foreach($tmp_arr as $k => $v)
	        {
	            if(isset($dates_arr[$v['booking_date']]['lunch_start_ts']) && isset($dates_arr[$v['booking_date']]['lunch_end_ts']))
	            {
	                if($dates_arr[$v['booking_date']]['lunch_end_ts'] <= $v['start_ts'])
	                {
	                    $bs_arr[$v['booking_date']][] = array('booking_status' => 'break', 'start_ts' => $dates_arr[$v['booking_date']]['lunch_start_ts'], 'end_ts' => $dates_arr[$v['booking_date']]['lunch_end_ts']);
	                } 
	            }
	            $bs_arr[$v['booking_date']][] = $v;
	            if(isset($dates_arr[$v['booking_date']]['lunch_start_ts']) && isset($dates_arr[$v['booking_date']]['lunch_end_ts']))
	            {
    	            if(!isset($tmp_arr[$k + 1]) && $dates_arr[$v['booking_date']]['lunch_start_ts'] >= $v['end_ts'])
    	            {
    	                $bs_arr[$v['booking_date']][] = array('booking_status' => 'break', 'start_ts' => $dates_arr[$v['booking_date']]['lunch_start_ts'], 'end_ts' => $dates_arr[$v['booking_date']]['lunch_end_ts']);
    	            }
	            }
	        }
	    }
	    $blocks_arr = array();
	    foreach($dates_arr as $iso_date => $t_arr)
	    {
	        if(!empty($t_arr))
	        {
	            $block_arr = array();
	            if(isset($bs_arr[$iso_date]))
	            {
	                $start_ts = $t_arr['start_ts'];
	                $end_ts = $t_arr['end_ts'];
	                foreach($bs_arr[$iso_date] as $item)
	                {
	                    if($start_ts < $item['start_ts'])
	                    {
	                        $block_arr[] = array('start_time' => date('H:i:s', $start_ts), 'end_time' => date('H:i:s', $item['start_ts']), 'status' => 'avail');
	                        $start_ts = $item['start_ts'];
	                    }
	                    if($start_ts >= $item['start_ts'] && $start_ts <= $item['end_ts'])
	                    {
	                        $block_arr[] = array('start_time' => date('H:i:s', $item['start_ts']), 'end_time' => date('H:i:s', $item['end_ts']), 'status' => $item['booking_status'], 'data' => $item);
	                        $start_ts = $item['end_ts'];
	                    }
	                }
	                if($start_ts < $end_ts)
	                {
	                    $block_arr[] = array('start_time' => date('H:i:s', $start_ts), 'end_time' => date('H:i:s', $end_ts), 'status' => 'avail');
	                }
	            }else{
	                $start_ts = $t_arr['start_ts'];
	                $end_ts = $t_arr['end_ts'];
	                if(isset($dates_arr[$iso_date]['lunch_start_ts']) && isset($dates_arr[$iso_date]['lunch_end_ts']))
	                {
	                    $block_arr[] = array('start_time' => date('H:i:s', $start_ts), 'end_time' => date('H:i:s', $dates_arr[$iso_date]['lunch_start_ts']), 'status' => 'avail');
	                    $block_arr[] = array('start_time' => date('H:i:s', $dates_arr[$iso_date]['lunch_start_ts']), 'end_time' => date('H:i:s', $dates_arr[$iso_date]['lunch_end_ts']), 'status' => 'break', 'data' => array());
	                    $block_arr[] = array('start_time' => date('H:i:s', $dates_arr[$iso_date]['lunch_end_ts']), 'end_time' => date('H:i:s', $end_ts), 'status' => 'avail');
	                }
	            }
	            $dates_arr[$iso_date]['blocks'] = $block_arr;
	        }
	    }
	    return $dates_arr;
	}
}
?>