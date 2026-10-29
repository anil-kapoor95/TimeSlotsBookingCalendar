<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminCalendars extends pjAdmin
{
	public function pjActionCreate()
	{
	    $this->checkLogin();
	    
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isPost() && $this->_post->check('calendar_create'))
	    {
	        $data = array();
	        $post = $this->_post->raw();
	        $data = array_merge($post, $data);
	        $pjCalendarModel = pjCalendarModel::factory();
	        $id = $pjCalendarModel->setAttributes($data)->insert()->getInsertId();
	        if ($id !== false && (int) $id > 0)
	        {
	            $err = 'AC03';
	            $i18n_arr = $this->_post->toI18n('i18n');
	            if (!empty($i18n_arr))
	            {
	                pjMultiLangModel::factory()->saveMultiLang($i18n_arr, $id, 'pjCalendar');
	            }
	            $user_id_arr = $this->_post->toArray('user_id');
	            if(count($user_id_arr) > 0)
	            {
	                $pjCalendarUserModel = pjCalendarUserModel::factory();
	                foreach ($user_id_arr as $user_id)
    	            {
    	                $pjCalendarUserModel->addBatchRow(array($id, $user_id));
    	            }
    	            $pjCalendarUserModel->setBatchFields(array('calendar_id', 'user_id'))->insertBatch();
	            }
	            
	            $pjMultiLangModel = pjMultiLangModel::factory();
	            $i18n_arr = $pjMultiLangModel->getMultiLang(0, 'pjCalendar');
	            unset($i18n_arr[1]['title']);
	            if (!empty($i18n_arr))
	            {
	                foreach ($i18n_arr as $locale => $locale_arr)
	                {
	                    $i18n_arr[$locale] = $i18n_arr[1];
	                }
	                $pjMultiLangModel->reset()->saveMultiLang($i18n_arr, $id, 'pjCalendar');
	            }else{
	                $pjMultiLangModel->reset()->saveMultiLang($i18n_arr, $id, 'pjCalendar');
	            }
	            $pjCalendarModel->reset()->initCalendar($id);
	            
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminCalendars&action=pjActionIndex&err=AC03");
	        } else {
	            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminCalendars&action=pjActionIndex&err=AC04");
	        }
	    }
	    if (self::isGet())
	    {
	        $this->setLocalesData();
	        
	        $user_arr = pjAuthUserModel::factory()->where('role_id <=', 2)->where('status', 'T')->orderBy("name ASC")->findAll()->getData();
	        $this->set('user_arr', $user_arr);
	        
	        $this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
	        $this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
	        $this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	        $this->appendJs('pjAdminCalendars.js');
	    }
	}
	public function pjActionDeleteCalendar()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	    }
	    
	    if ($this->_get->check('id') && $this->_get->toInt('id') > 0)
	    {
	        $id = $this->_get->toInt('id');
	        if ($id == $this->getCalendarId())
	        {
	            pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Current calendar can not be deleted.'));
	        }
	        if (pjCalendarModel::factory()->set('id', $id)->erase()->getAffectedRows() == 1)
	        {
	            pjMultiLangModel::factory()->where('model', 'pjCalendar')->where('foreign_id', $id)->eraseAll();
	            pjDateModel::factory()->where('foreign_id', $id)->eraseAll();
	            pjPriceModel::factory()->where('calendar_id', $id)->eraseAll();
	            pjPriceDayModel::factory()->where('calendar_id', $id)->eraseAll();
	            pjWorkingTimeModel::factory()->where('foreign_id', $id)->limit(1)->eraseAll();
	            pjOptionModel::factory()->where('foreign_id', $id)->eraseAll();
	            pjNotificationModel::factory()->where('foreign_id', $id)->eraseAll();
	            pjPaymentOptionModel::factory()->where('foreign_id', $id)->eraseAll();
	            pjCalendarUserModel::factory()->where('calendar_id', $id)->eraseAll();
	            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Calendar has been deleted.'));
	        }
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Calendar has not been deleted.'));
	    }
	    
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
	}
	
	public function pjActionDeleteCalendarBulk()
	{
		$this->setAjax(true);
	
		if (!$this->isXHR())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		if (!self::isPost())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
		}
		$record = $this->_post->toArray('record');
		if (count($record))
		{
		    $cnt = count($record);
		    pjCalendarModel::factory()
		    ->where('id !=', $this->getCalendarId())
		    ->whereIn('id', $record)
		    ->limit($cnt)
		    ->eraseAll();
		    pjMultiLangModel::factory()->where('model', 'pjCalendar')->where('foreign_id !=', $this->getCalendarId())->whereIn('foreign_id', $record)->eraseAll();
		    pjDateModel::factory()->whereIn('foreign_id', $record)->where('foreign_id !=', $this->getCalendarId())->eraseAll();
		    pjPriceModel::factory()->whereIn('calendar_id', $record)->where('calendar_id !=', $this->getCalendarId())->eraseAll();
		    pjPriceDayModel::factory()->whereIn('calendar_id', $record)->where('calendar_id !=', $this->getCalendarId())->eraseAll();
		    pjCalendarUserModel::factory()->whereIn('calendar_id', $record)->where('calendar_id !=', $this->getCalendarId())->eraseAll();
		    pjWorkingTimeModel::factory()->whereIn('foreign_id', $record)->where('foreign_id !=', $this->getCalendarId())->limit($cnt)->eraseAll();
		    pjOptionModel::factory()->whereIn('foreign_id', $record)->where('foreign_id !=', $this->getCalendarId())->eraseAll();
		    pjNotificationModel::factory()->whereIn('foreign_id', $record)->where('foreign_id !=', $this->getCalendarId())->eraseAll();
		    pjPaymentOptionModel::factory()->whereIn('foreign_id', $record)->where('foreign_id !=', $this->getCalendarId())->eraseAll();
		    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Calendar(s) has been deleted.'));
		}
		pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty params.'));
		exit;
	}
	
	public function pjActionGetCalendar()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    $pjCalendarModel = pjCalendarModel::factory()
	    ->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='%u'", $this->getLocaleId()), 'left outer');
	    
	    if(!$this->isMasterAdmin())
	    {
	        $pjCalendarModel->where(sprintf("(t1.id IN (SELECT TCU.calendar_id FROM `%s` AS TCU WHERE TCU.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	    }
	    if ($this->isAdmin())
	    {
	        if ($this->_get->toInt('user_id') > 0)
	        {
	            $pjCalendarModel->where(sprintf("(t1.id IN (SELECT TCU.calendar_id FROM `%s` AS TCU WHERE TCU.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->_get->toInt('user_id')));
	        }
	    }
	    
	    if ($q = $this->_get->toString('q'))
	    {
	        $q = $pjCalendarModel->escapeStr(trim($q));
	        $q = str_replace(array('%', '_'), array('\%', '\_'), $q);
	        $pjCalendarModel->where("((t2.content LIKE '%$q%') OR (t1.id IN (SELECT TCU.calendar_id FROM `".pjCalendarUserModel::factory()->getTable()."` AS TCU WHERE TCU.user_id IN (SELECT TU.id FROM `".pjAuthUserModel::factory()->getTable()."` AS TU WHERE TU.name LIKE '%$q%%' OR TU.email LIKE '%$q%%') )) )");
	        
	    }
	    
	    $column = '`title`';
	    $direction = 'ASC';
	    $sort_str = "$column $direction";
	    $allowed_columns = array('title', 'email', 'name');
	    if ($this->_get->check('column') && in_array($this->_get->toString('column'), $allowed_columns) && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
	    {
	        $column = $this->_get->toString('column');
	        $direction = strtoupper($this->_get->toString('direction'));
	        $sort_str = "$column $direction";
	        if($column == 'name')
	        {
	            $sort_str = "owner_name $direction";
	        }
	        if($column == 'email')
	        {
	            $sort_str = "owner_email $direction";
	        }
	    }
	    
	    $total = $pjCalendarModel->findCount()->getData();
	    $rowCount = $this->_get->toInt('rowCount') ? $this->_get->toInt('rowCount') : 10;
	    $pages = ceil($total / $rowCount);
	    $page = $this->_get->toInt('page') ? $this->_get->toInt('page') : 1;
	    $offset = ((int) $page - 1) * $rowCount;
	    if ($page > $pages)
	    {
	        $page = $pages;
	    }
	    
	    $data = $pjCalendarModel
	    ->select(sprintf("t1.id, t2.content AS `title`, 
                        (SELECT `TU`.name FROM `%1\$s` AS `TU` WHERE `TU`.id IN(SELECT `TCU`.user_id FROM `%2\$s` AS `TCU` WHERE `TCU`.calendar_id=t1.id) ORDER BY `TU`.id ASC LIMIT 1) AS `owner_name`,
                        (SELECT `TU`.email FROM `%1\$s` AS `TU` WHERE `TU`.id IN(SELECT `TCU`.user_id FROM `%2\$s` AS `TCU` WHERE `TCU`.calendar_id=t1.id) ORDER BY `TU`.id ASC LIMIT 1) AS `owner_email`",
	               pjAuthUserModel::factory()->getTable(), pjCalendarUserModel::factory()->getTable()))
	    ->orderBy($sort_str)
	    ->limit($rowCount, $offset)
	    ->findAll()
	    ->getData();
	    $email_arr = $name_arr = array();
	    $calendar_id_arr = $pjCalendarModel->findAll()->getDataPair(null, 'id');
	    if(!empty($calendar_id_arr))
	    {
	        $user_arr = pjAuthUserModel::factory()
	        ->select('t1.*, t2.calendar_id')
	        ->join('pjCalendarUser', 't1.id=t2.user_id', 'inner')
	        ->whereIn('calendar_id', $calendar_id_arr)
	        ->orderBy('`id` ASC')
	        ->findAll()->getData();
	        foreach($user_arr as $k => $v)
	        {
	            if(isset($email_arr[$v['calendar_id']]))
	            {
	                if(!in_array($v['email'], $email_arr[$v['calendar_id']]))
	                {
	                    $email_arr[$v['calendar_id']][] = pjSanitize::html($v['email']);
	                }
	            }else{
	                $email_arr[$v['calendar_id']][] = pjSanitize::html($v['email']);
	            }
	            if(isset($name_arr[$v['calendar_id']]))
	            {
	                if(!in_array($v['name'], $name_arr[$v['calendar_id']]))
	                {
	                    $name_arr[$v['calendar_id']][] = pjSanitize::html($v['name']);
	                }
	            }else{
	                $name_arr[$v['calendar_id']][] = pjSanitize::html($v['name']);
	            }
	        }
	    }
	    foreach($data as $k => $v)
	    {
	        $v['email'] = isset($email_arr[$v['id']]) ? implode("<br/>", $email_arr[$v['id']]) : NULL;
	        $v['name'] = isset($name_arr[$v['id']]) ? implode("<br/>", $name_arr[$v['id']]) : NULL;
	        $data[$k] = $v;
	    }
	    self::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
	}
	
	public function pjActionIndex()
	{
	    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminCalendars.js');
	    
	    $this->set('has_update', pjAuth::factory('pjAdminCalendars', 'pjActionUpdate')->hasAccess());
	    $this->set('has_create', pjAuth::factory('pjAdminCalendars', 'pjActionCreate')->hasAccess());
	    $this->set('has_delete', pjAuth::factory('pjAdminCalendars', 'pjActionDeleteCalendar')->hasAccess());
	    $this->set('has_delete_bulk', pjAuth::factory('pjAdminCalendars', 'pjActionDeleteCalendarBulk')->hasAccess());
	    $this->set('has_working_time', pjAuth::factory('pjAdminTime', 'pjActionIndex')->hasAccess());
	    $this->set('has_booking_options', pjAuth::factory('pjAdminOptions', 'pjActionIndex')->hasAccess());
	}
	
	public function pjActionUpdate()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
		
	    if (self::isPost() && $this->_post->toInt('calendar_update'))
	    {
	        $id = $this->_post->toInt('id');
	        $data = array();
	        $post = $this->_post->raw();
	        $data = array_merge($post, $data);
	        $pjCalendarModel = pjCalendarModel::factory();
	        $pjCalendarModel->set('id', $id)->modify($data);
			$i18n_arr = $this->_post->toI18n('i18n');
			if (!empty($i18n_arr))
			{
			    pjMultiLangModel::factory()->updateMultiLang($i18n_arr, $id, 'pjCalendar');
			}
			
			pjCalendarUserModel::factory()->where('calendar_id', $id)->eraseAll();;
			$user_id_arr = $this->_post->toArray('user_id');
			if(count($user_id_arr) > 0)
			{
			    $pjCalendarUserModel = pjCalendarUserModel::factory();
			    foreach ($user_id_arr as $user_id)
			    {
			        $pjCalendarUserModel->addBatchRow(array($id, $user_id));
			    }
			    $pjCalendarUserModel->setBatchFields(array('calendar_id', 'user_id'))->insertBatch();
			}
			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminCalendars&action=pjActionIndex&err=AC01");
			
		} 
		if(self::isGet())
		{
		    $id = $this->getCalendarId();
		    if($this->_get->toInt('id') > 0)
		    {
		        $id = $this->_get->toInt('id');
		    }
			
		    $arr = pjCalendarModel::factory()->find($id)->getData();
			if (empty($arr))
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjAdminCalendars&action=pjActionIndex&err=AC08");
			}
			$arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($arr['id'], 'pjCalendar');
			$arr['user_id_arr'] = pjCalendarUserModel::factory()->where('calendar_id', $arr['id'])->findAll()->getDataPair(null, 'user_id');
			$this->set('arr', $arr);
			
			$this->setLocalesData();
			
			$user_arr = pjAuthUserModel::factory()->where('role_id <=', 2)->where('status', 'T')->orderBy("name ASC")->findAll()->getData();
			$this->set('user_arr', $user_arr);
			
			$pjBookingModel = pjBookingModel::factory();
			$new_cnt_today = $pjBookingModel->where('t1.calendar_id', $id)->where("(DATE(created) = CURDATE())")->findCount()->getData();
			$confirmed_cnt_today = $pjBookingModel->reset()->where('t1.calendar_id', $id)->where('booking_status', 'confirmed')->where("(DATE(created) = CURDATE())")->findCount()->getData();
			$confirmed_cnt = $pjBookingModel->reset()->where('t1.calendar_id', $id)->where('booking_status', 'confirmed')->findCount()->getData();
			$cancelled_cnt = $pjBookingModel->reset()->where('t1.calendar_id', $id)->where('booking_status', 'cancelled')->findCount()->getData();
			
			$this->set('new_cnt_today', $new_cnt_today);
			$this->set('confirmed_cnt_today', $confirmed_cnt_today);
			$this->set('confirmed_cnt', $confirmed_cnt);
			$this->set('cancelled_cnt', $cancelled_cnt);
			
			$pjBookingModel->reset()->where('t1.calendar_id', $id);
			$pjBookingSlotModel = pjBookingSlotModel::factory();
			
			$pjBookingModel->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.calendar_id AND t2.field='title' AND t2.locale=%u", $this->getLocaleId()), 'left');
			$pjBookingModel->join('pjClient', "t1.client_id=t3.id", 'left');
			$pjBookingModel->join('pjAuthUser', "t3.foreign_id=t4.id", 'left');
			$pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` >= CURDATE())", $pjBookingSlotModel->getTable()));
			$upcoming_bookings = $pjBookingModel
			->select(sprintf("t1.*, t2.content as calendar_title, t4.name AS customer_name, t4.email AS customer_email, t4.phone AS customer_phone,
                    (SELECT `TBS`.start_ts FROM `%1\$s` AS `TBS` WHERE `TBS`.booking_id=t1.id ORDER BY `TBS`.start_ts ASC LIMIT 1 ) AS min_start_ts
					", $pjBookingSlotModel->getTable()))
			->orderBy("min_start_ts ASC")
			->limit(5)
			->findAll()
			->toArray('items', '~:~')
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
			
			$calendar_arr = pjCalendarModel::factory()
			->select("t1.*, t2.content as title")
			->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
			->where('t1.id <>', $id)
			->orderBy("t2.content ASC")
			->findAll()->getData();
			$this->set('copy_calendar_arr', $calendar_arr);
			
			$this->appendCss('awesome-bootstrap-checkbox.css', PJ_THIRD_PARTY_PATH . 'awesome_bootstrap_checkbox/');
			$this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
			$this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
			$this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
			$this->appendJs('pjAdminCalendars.js');
		}
	}
	
	public function pjActionCopyOptions()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'HTTP method not allowed.'));
	    }
	    $tabs_valid = false;
	    if($this->_post->check('tabs'))
	    {
	        $tabs = $this->_post->toArray('tabs');
	        if(!empty($tabs))
	        {
	            $tabs_valid = true;
	        }
	    }
	    if ($this->_post->check('current_calendar_id') && $this->_post->toInt('current_calendar_id') > 0 && $this->_post->check('calendar_id') && $this->_post->toInt('calendar_id') > 0 && $this->_post->check('radio-cal') && in_array($this->_post->toString('radio-cal'), array('from', 'to')) && $tabs_valid)
	    {
	        $tabs = $this->_post->toArray('tabs');
	        if($this->_post->toString('radio-cal') == 'from')
	        {
	            $src_calendar_id = $this->_post->toInt('calendar_id');
	            $dst_calendar_id = $this->_post->toInt('current_calendar_id');
	        }else{
	            $src_calendar_id = $this->_post->toInt('current_calendar_id');
	            $dst_calendar_id = $this->_post->toInt('calendar_id');
	        }
	        
	        $pjOptionModel = pjOptionModel::factory();
	        $pjMultiLangModel = pjMultiLangModel::factory();
	        
	        if(in_array('working_time', $tabs))
	        {
	            $pjWorkingTimeModel = pjWorkingTimeModel::factory();
	            $pjPriceDayModel = pjPriceDayModel::factory();
	            
	            $src_wt_arr = $pjWorkingTimeModel->where('foreign_id', $src_calendar_id)->limit(1)->findAll()->getData();
	            $wt_data = $src_wt_arr[0];
	            unset($wt_data['id']);
	            $arr = $pjWorkingTimeModel
	            ->reset()
	            ->where('t1.foreign_id', $dst_calendar_id)
	            ->limit(1)
	            ->findAll()
	            ->getDataIndex(0);
	            $wt_data['foreign_id'] = $dst_calendar_id;
	            $pjWorkingTimeModel->reset()->set('id', $arr['id'])->modify($wt_data);
	            
	            $price_day_arr = $pjPriceDayModel->where('calendar_id', $src_calendar_id)->findAll()->getData();
	            foreach($price_day_arr as $record)
	            {
	                unset($record['id']);
	                $record['calendar_id'] = $dst_calendar_id;
	                $pjPriceDayModel->reset()->setAttributes($record)->insert();
	            }
	        }
	        if(in_array('custom_working_time', $tabs))
	        {
	            $pjDateModel = pjDateModel::factory();
	            $pjPriceModel = pjPriceModel::factory();
	            
	            $src_date_arr = $pjDateModel->where('foreign_id', $src_calendar_id)->findAll()->getData();
	            foreach($src_date_arr as $record)
	            {
	                unset($record['id']);
	                $record['foreign_id'] = $dst_calendar_id;
	                $pjDateModel->reset()->setAttributes($record)->insert();
	            }
	            
	            $src_price_arr = $pjPriceModel->where('calendar_id', $src_calendar_id)->findAll()->getData();
	            foreach($src_price_arr as $record)
	            {
	                unset($record['id']);
	                $record['calendar_id'] = $dst_calendar_id;
	                $pjPriceModel->reset()->setAttributes($record)->insert();
	            }
	        }
	        if(in_array('booking_options', $tabs))
	        {
	            $src_option_arr = $pjOptionModel->reset()->where('foreign_id', $src_calendar_id)->where('tab_id', 3)->findAll()->getData();
	            $pjOptionModel->begin();
	            foreach ($src_option_arr as $option)
	            {
	                $pjOptionModel
	                ->reset()
	                ->where('foreign_id', $dst_calendar_id)
	                ->where('`key`', $option['key'])
	                ->limit(1)
	                ->modifyAll(array('value' => $option['value'], 'label' => $option['label']));
	            }
	            $pjOptionModel->commit();
	        }
	        if(in_array('payments', $tabs))
	        {
	            $src_option_arr = $pjOptionModel->reset()->where('foreign_id', $src_calendar_id)->where('tab_id', 7)->findAll()->getData();
	            $pjOptionModel->begin();
	            foreach ($src_option_arr as $option)
	            {
	                $pjOptionModel
	                ->reset()
	                ->where('foreign_id', $dst_calendar_id)
	                ->where('`key`', $option['key'])
	                ->limit(1)
	                ->modifyAll(array('value' => $option['value'], 'label' => $option['label']));
	            }
	            $pjOptionModel->commit();
	            
	            $multilang_arr = $pjMultiLangModel->reset()->where('foreign_id', $src_calendar_id)->where('model', 'pjOption')->where('field', 'o_bank_account')->findAll()->getData();
	            foreach ($multilang_arr as $record)
	            {
	                $i18n_arr[$record['locale']][$record['field']] = $record['content'];
	                $pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $dst_calendar_id, 'pjOption');
	            }
	            $multilang_arr = $pjMultiLangModel->reset()->where('foreign_id', $src_calendar_id)->where('model', 'pjPayment')->whereIn('field', array('cash','bank'))->findAll()->getData();
	            foreach ($multilang_arr as $record)
	            {
	                $i18n_arr[$record['locale']][$record['field']] = $record['content'];
	                $pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $dst_calendar_id, 'pjPayment');
	            }
	            
	            $pjPaymentOptionModel = pjPaymentOptionModel::factory();
	            $src_payment_options_arr = $pjPaymentOptionModel->where('foreign_id', $src_calendar_id)->findAll()->getData();
	            $payment_methods = $pjPaymentOptionModel->findAll()->getDataPair(null, 'payment_method');
	            foreach ($src_payment_options_arr as $option)
	            {
	                unset($option['id']);
	                unset($option['foreign_id']);
	                $pjPaymentOptionModel
	                ->reset()
	                ->where('foreign_id', $dst_calendar_id)
	                ->where('payment_method', $option['payment_method'])
	                ->limit(1)
	                ->modifyAll($option);
	            }
	            if(!empty($payment_methods))
	            {
	                $multilang_arr = $pjMultiLangModel->reset()->where('foreign_id', $src_calendar_id)->where('model', 'pjPayment')->whereIn('field', $payment_methods)->findAll()->getData();
    	            foreach ($multilang_arr as $record)
    	            {
    	                $i18n_arr[$record['locale']][$record['field']] = $record['content'];
    	                $pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $dst_calendar_id, 'pjPayment');
    	            }
	            }
	            
	            $pjMollieOptionModel = pjMollieOptionModel::factory();
	            $pjMollieOptionModel->where('foreign_id', $dst_calendar_id)->eraseAll();
	            $src_mollie_opts_arr = $pjMollieOptionModel->reset()->where('foreign_id', $src_calendar_id)->findAll()->getData();
	            foreach ($src_mollie_opts_arr as $option)
	            {
	                unset($option['id']);
	                $option['foreign_id'] = $dst_calendar_id;
	                $pjMollieOptionModel->reset()->setAttributes($option)->insert();
	            }
	        }
	        if(in_array('terms', $tabs))
	        {
	            $multilang_arr = $pjMultiLangModel->reset()->where('foreign_id', $src_calendar_id)->where('model', 'pjCalendar')->whereIn('field', array('terms_url','terms_body'))->findAll()->getData();
	            foreach ($multilang_arr as $record)
	            {
	                $i18n_arr[$record['locale']][$record['field']] = $record['content'];
	                $pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $dst_calendar_id, 'pjCalendar');
	            }
	        }
	        if(in_array('notifications', $tabs))
	        {
	            $pjNotificationModel = pjNotificationModel::factory();
	            $notification_arr = $pjNotificationModel->where('foreign_id', $src_calendar_id)->findAll()->getData();
	            $fields = array();
	            foreach($notification_arr as $record)
	            {
	                unset($record['id']);
	                unset($record['foreign_id']);
	                $pjNotificationModel
	                ->reset()
	                ->where('foreign_id', $dst_calendar_id)
	                ->where('recipient', $record['recipient'])
	                ->where('transport', $record['transport'])
	                ->where('variant', $record['variant'])
	                ->limit(1)
	                ->modifyAll(array('is_active' => $record['is_active']));
	                
	                if($record['transport'] == 'email')
	                {
	                    $subject = $record['variant'] . '_subject_' . $record['recipient'];
	                    $fields[] = str_replace('confirmation', 'confirm', $subject);
	                    $message = $record['variant'] . '_tokens_' . $record['recipient'];
	                    $fields[] = str_replace('confirmation', 'confirm', $message);
	                }else{
	                    $message = $record['variant'] . '_sms_' . $record['recipient'];
	                    $fields[] = str_replace('confirmation', 'confirm', $message);
	                }
	            }
	            if(!empty($fields))
	            {
	                $multilang_arr = $pjMultiLangModel->reset()->where('foreign_id', $src_calendar_id)->where('model', 'pjCalendar')->whereIn('field', $fields)->findAll()->getData();
	                foreach ($multilang_arr as $record)
	                {
	                    $i18n_arr[$record['locale']][$record['field']] = $record['content'];
	                    $pjMultiLangModel->reset()->updateMultiLang($i18n_arr, $dst_calendar_id, 'pjCalendar');
	                }
	            }
	        }
	        if(in_array('booking_form', $tabs))
	        {
	            $src_option_arr = $pjOptionModel->reset()->where('foreign_id', $src_calendar_id)->where('tab_id', 4)->findAll()->getData();
	            $pjOptionModel->begin();
	            foreach ($src_option_arr as $option)
	            {
	                $pjOptionModel
	                ->reset()
	                ->where('foreign_id', $dst_calendar_id)
	                ->where('`key`', $option['key'])
	                ->limit(1)
	                ->modifyAll(array('value' => $option['value'], 'label' => $option['label']));
	            }
	            $pjOptionModel->commit();
	        }
	        
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('lblCopyCompleted', true), 'calendar_id' => $this->_post->check('current_calendar_id')));
	    }
	    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
	}
	
	public function pjActionCheckAssignCalendar()
	{
	    $this->setAjax(true);
	    if($this->getCalendarId() == false)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('lblNoCalendarAssignedMsg', true), 'btnOK' => __('gridBtnOk', true)));
	    }else{
	        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	    exit;
	}
}
?>