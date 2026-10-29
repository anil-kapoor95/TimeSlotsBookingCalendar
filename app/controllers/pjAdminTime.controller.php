<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminTime extends pjAdmin
{
	private $weekDays = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
	
	public function pjActionIndex()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    $foreign_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $foreign_id = $this->_get->toInt('id');
	    }
	    $wday = $this->_get->toString('day');
	    if (self::isPost() && $this->_post->check('working_time'))
	    {
	        if(!$this->_post->check('id'))
	        {
	            pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex", PJ_INSTALL_URL));
	        }
	        if($this->_post->check('day_of_week'))
	        {
	            if(!in_array($this->_post->toString('day_of_week'), array('monday','tuesday','wednesday','thursday','friday','saturday','sunday')))
	            {
	                pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex", PJ_INSTALL_URL));
	            }
	        }
	        $post = $this->_post->raw();
	        $data = array();
	        $day = $post['day_of_week'];
	        if (!isset($post[$day . '_dayoff']))
	        {
	            if (!(isset($post[$day . '_from'], $post[$day . '_length'], $post[$day . '_slots'], $post[$day . '_to'])
	                && !empty($post[$day . '_from'])
	                && !empty($post[$day . '_to'])
	                && !empty($post[$day . '_length'])
	                && !empty($post[$day . '_slots'])
	                && array_key_exists($day . '_people', $post)
	                && array_key_exists($day . '_price', $post)
	                && array_key_exists($day . '_limit', $post)
	                ))
	            {
	                pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex", PJ_INSTALL_URL));
	            }
	            
	            $data[$day . '_from'] = date('H:i', strtotime($post[$day . '_from']));
	            $data[$day . '_to'] = date('H:i', strtotime($post[$day . '_to']));
	            if(isset($post['lunch_break']))
	            {
	                $lunch_from = $post[$day . '_lunch_from'];
	                $lunch_length = $post['lunch_length'] != '' ? intval($post['lunch_length']) : 0;
	                $lunch_to = strtotime($lunch_from) + ($lunch_length * 60);
	                
	                $data[$day . '_lunch_from'] = date('H:i', strtotime($lunch_from));
	                $data[$day . '_lunch_to'] = date('H:i', $lunch_to);
	            }else{
	                $data[$day . '_lunch_from'] = '00:00:00';
	                $data[$day . '_lunch_to'] = '00:00:00';
	            }
	            $data[$day . '_slots'] = $post[$day . '_slots'];
	            $data[$day . '_people'] = $post[$day . '_people'];
	            $data[$day . '_price'] = !empty($post[$day . '_price']) ? (float) $post[$day . '_price'] : 0;
	            $data[$day . '_dayoff'] = "F";
	            
	        } else {
	            $data[$day . '_from'] = ":NULL";
	            $data[$day . '_to'] = ":NULL";
	            $data[$day . '_lunch_from'] = ":NULL";
	            $data[$day . '_lunch_to'] = ":NULL";
	            $data[$day . '_slots'] = ":NULL";
	            $data[$day . '_people'] = ":NULL";
	            $data[$day . '_price'] = ":NULL";
	            $data[$day . '_limit'] = ":NULL";
	            $data[$day . '_length'] = ":NULL";
	            $data[$day . '_dayoff'] = "T";
	        }
	        if ($data)
	        {
	            pjWorkingTimeModel::factory()->set('id', $post['id'])->modify(array_merge($post, $data));
	        }
	        
	        pjUtil::redirect(sprintf("%sindex.php?controller=pjAdminTime&action=pjActionIndex&day=%s&err=AT01", PJ_INSTALL_URL, $day));
	    }
	    if (self::isGet())
	    {
    	    $wt_arr = pjWorkingTimeModel::factory()
    	    ->where('t1.foreign_id', $foreign_id)
    	    ->limit(1)
    	    ->findAll()
    	    ->getDataIndex(0);
    	    
    	    $this->set('wt_arr', $wt_arr);
    	    
    	    $this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
    	    $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
    	    
    	    $this->appendJs('pjAdminTime.js');
	    }
	}
	
	public function pjActionCustom()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    $foreign_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $foreign_id = $this->_get->toInt('id');
	    }
	    if (self::isPost() && $this->_post->check('custom_time'))
	    {
	        $post = $this->_post->raw();
			$pjPriceModel = pjPriceModel::factory();
			$date = pjDateTime::formatDate($post['date'], $this->option_arr['o_date_format']);
			
			$pjPriceModel
			->where('calendar_id', $foreign_id)
			->where('`date`', $date)
			->eraseAll()
			->reset();

			$data = $_data = array();
			$data['foreign_id'] = $foreign_id;
			$data['date'] = $date;
			$data['is_dayoff'] = isset($post['is_dayoff']) ? 'T' : 'F';
			
			$lunch_from_ts = strtotime($date . ' ' . $post['lunch_from']);
			$lunch_length = !empty($post['lunch_length']) ? intval($post['lunch_length']) : 0;
			$lunch_to = $lunch_from_ts + ($lunch_length * 60);
			$data['start_time'] = !empty($post['start']) ? date('H:i', strtotime($post['start'])) : '00:00';
			$data['end_time'] = !empty($post['end']) ? date('H:i', strtotime($post['end'])) : '00:00';
			$data['start_lunch'] = date('H:i', $lunch_from_ts);
			$data['end_lunch'] = date('H:i', $lunch_to);
			$data['price'] = !empty($post['default_price']) ? (float) $post['default_price'] : 0.00;
			$data['people'] = (int) $post['people'] > 0 ? (int) $post['people'] : 0;
			if ($data['is_dayoff'] == 'F')
			{
			    if (!isset($post['single_price']))
			    {
			        $pjPriceModel->setBatchFields(array('calendar_id', 'date', 'start_time', 'end_time', 'start_ts', 'end_ts', 'price'));
			        foreach ($post['price'] as $k => $price)
			        {
			            if (empty($price) && strlen($price) === 0) continue;
			            list($start_ts, $end_ts) = explode("|", $k);
			            $pjPriceModel->addBatchRow(array(
			                $foreign_id,
			                $date,
			                date("H:i:s", $start_ts),
			                date("H:i:s", $end_ts),
			                $start_ts,
			                $end_ts,
			                $price
			            ));
			        }
			        $pjPriceModel->insertBatch();
			    }
			}			
			pjDateModel::factory()
			->where('foreign_id', $foreign_id)
			->where('`date`', $date)
			->limit(1)
			->eraseAll()
			->reset()
			->setAttributes(array_merge($post, $data, $_data))
			->insert();
			
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionCustom&err=AT02");
		}
		if (self::isGet())
		{
		    if($this->_get->toInt('date_id') > 0)
		    {
		        $arr = pjDateModel::factory()->find($this->_get->toInt('date_id'))->getData();
    		    if (empty($arr))
    		    {
    		        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminTime&action=pjActionCustom&err=AT08");
    		    }
    		    $this->set('arr', $arr);
    		    $this->set('price_arr', pjPriceModel::factory()
    	        ->where('t1.calendar_id', $arr['foreign_id'])
    	        ->where('t1.date', $arr['date'])
    	        ->orderBy('t1.start_time ASC')
    	        ->findAll()
    	        ->getData()
    	        );
		    }
		    
		    $this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));
		    
		    $this->appendCss('datepicker.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
		    $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
		    $this->appendCss('clockpicker.css', PJ_THIRD_PARTY_PATH . 'clockpicker/');
		    $this->appendJs('clockpicker.js', PJ_THIRD_PARTY_PATH . 'clockpicker/');
		    $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		    $this->appendJs('pjAdminTime.js');
		}
	}
	
	public function pjActionDeleteDate()
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
		    $pjDateModel = pjDateModel::factory();
		    $arr = $pjDateModel->find($id)->getData();
		    if (empty($arr))
		    {
		        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Record not found.'));
		    }
		    if (pjDateModel::factory()->set('id', $id)->erase()->getAffectedRows() == 1)
		    {
		        pjPriceModel::factory()
		        ->where('calendar_id', $arr['foreign_id'])
		        ->where('`date`', $arr['date'])
		        ->eraseAll();
		        
		        self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Date has been deleted.'));
		    }
		    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Date has not been deleted.'));
		}
		self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
		exit;
	}
	
	public function pjActionDeleteDateBulk()
	{
	    $this->setAjax(true);
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    if (!pjAuth::factory()->hasAccess())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
	    }
	    if (!$this->_post->has('record'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $record = $this->_post->toArray('record');
	    if (empty($record))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $pjDateModel = pjDateModel::factory();
	    $arr = $pjDateModel
	    ->select("CONCAT_WS('_', `foreign_id`, `date`) AS `fd`")
	    ->whereIn('id', $record)
	    ->findAll()
	    ->getDataPair(null, 'fd');
	    
	    if (empty($arr))
	    {
	        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Record(s) not found.'));
	    }
	    $pjDateModel->reset()->whereIn('id', $record)->limit(count($record))->eraseAll();
	    pjPriceModel::factory()->whereIn("CONCAT_WS('_', `calendar_id`, `date`)", $arr)->eraseAll();
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Date(s) has been deleted.'));
	    exit;
	
	}
	
	public function pjActionGetDate()
	{
		$this->setAjax(true);
		if (!$this->isXHR())
		{
		    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
		}
		$foreign_id = $this->getCalendarId();
		if($this->_get->toInt('calendar_id') > 0)
		{
		    $foreign_id = $this->_get->toInt('calendar_id');
		}
		
		$pjDateModel = pjDateModel::factory();
		
		$pjDateModel->where('t1.foreign_id', $foreign_id);
		
		if ($this->_get->check('is_dayoff') && in_array($this->_get->toString('is_dayoff'), array('T', 'F')))
		{
		    $pjDateModel->where('t1.is_dayoff', $this->_get->toString('is_dayoff'));
		}
		
		$column = '`date`';
		$direction = 'ASC';
		$allowed_columns = array('date', 'start_time', 'end_time', 'start_lunch', 'end_lunch', 'price', 'is_dayoff');
		if ($this->_get->check('column') && in_array($this->_get->toString('column'), $allowed_columns) && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
		{
		    $column = $this->_get->toString('column');
		    $direction = strtoupper($this->_get->toString('direction'));
		}
		
		$total = $pjDateModel->findCount()->getData();
		$rowCount = $this->_get->toInt('rowCount') ? $this->_get->toInt('rowCount') : 10;
		$pages = ceil($total / $rowCount);
		$page = $this->_get->toInt('page') ? $this->_get->toInt('page') : 1;
		$offset = ((int) $page - 1) * $rowCount;
		if ($page > $pages)
		{
		    $page = $pages;
		}
		
		$data = $pjDateModel
		->select("t1.*,
					TIME_FORMAT(`start_time`, '%H:%i') AS `start_time`, TIME_FORMAT(`end_time`, '%H:%i') AS `end_time`,
					TIME_FORMAT(`start_lunch`, '%H:%i') AS `start_lunch`, TIME_FORMAT(`end_lunch`, '%H:%i') AS `end_lunch`")
		->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
		
		foreach ($data as $k => $v)
		{
		    $data[$k]['date'] = date($this->option_arr['o_date_format'], strtotime($v['date']) );
		    $data[$k]['price'] = pjCurrency::formatPrice($v['price']);
		    $data[$k]['start_time'] = date($this->option_arr['o_time_format'], strtotime($v['start_time']) );
		    $data[$k]['end_time'] = date($this->option_arr['o_time_format'], strtotime($v['end_time']) );
		    $data[$k]['start_lunch'] = date($this->option_arr['o_time_format'], strtotime($v['start_lunch']) );
		    $data[$k]['end_lunch'] = date($this->option_arr['o_time_format'], strtotime($v['end_lunch']) );
		}
		
		pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		exit;
	}
	
	public function pjActionGetSlots()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
		    $price_arr = array();
		    if($this->_post->toInt('date_id') > 0)
		    {
		        $arr = pjDateModel::factory()->find($this->_post->toInt('date_id'))->getData();
		        $_price_arr = pjPriceModel::factory()
		        ->where('t1.calendar_id', $arr['foreign_id'])
		        ->where('t1.date', $arr['date'])
		        ->orderBy('t1.start_time ASC')
		        ->findAll()
		        ->getData();
		        foreach($_price_arr as $k => $v)
		        {
		            $price_arr[$v['start_ts'] . '|' . $v['end_ts']] = $v['price'];
		        }
		    }
		    $this->set('price_arr', $price_arr);
		}
	}
	
	public function pjActionGetPrices()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
		    $foreign_id = $this->getCalendarId();
		    if($this->_get->toInt('foreign_id') > 0)
		    {
		        $foreign_id = $this->_get->toInt('foreign_id');
		    }
		    
			$this->set('wt_arr', pjWorkingTimeModel::factory()
			    ->where('t1.foreign_id', $foreign_id)
				->findAll()
				->getDataIndex(0)
			);
				
			$price_day_arr = array();
			if ($this->_get->check('day') && !$this->_get->isEmpty('day') && in_array($this->_get->toString('day'), $this->weekDays))
			{
				$price_day_arr = pjPriceDayModel::factory()
				->where('t1.calendar_id', $foreign_id)
				->where('t1.day', $this->_get->toString('day'))
				->orderBy('t1.start_time ASC')
				->findAll()
				->getData();
			}
			$this->set('price_day_arr', $price_day_arr);
		}
	}
	
	public function pjActionSetPrices()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
		    if (!$this->_post->check('day'))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid params.'));
			}
			if ($this->_post->check('day') && !in_array($this->_post->toString('day'), $this->weekDays))
			{
			    pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid params.'));
			}
			$wday = $this->_post->toString('day');
			$calendar_id = $this->_post->toInt('calendar_id');
			
			$pjPriceDayModel = new pjPriceDayModel();
			$pjPriceDayModel
			->where('calendar_id', $calendar_id)
			->where('`day`', $wday)
			->eraseAll();
			$post = $this->_post->raw();
			if (!isset($post['delete']) && isset($post['price']) && !empty($post['price']))
			{
				$pjPriceDayModel->reset()->setBatchFields(array('calendar_id', 'day', 'start_time', 'end_time', 'price'));
				foreach ($post['price'] as $k => $price)
				{
					if (empty($price) && strlen($price) === 0) continue;
					list($start_ts, $end_ts) = explode("|", $k);
					$pjPriceDayModel->addBatchRow(array($calendar_id, $wday, date("H:i:s", $start_ts), date("H:i:s", $end_ts), $price));
				}
				$pjPriceDayModel->insertBatch();
				
				self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Price(s) has been saved.'));
			} else {
			    self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Price(s) has been deleted.'));
			}
		}
		exit;
	}

	public function pjActionGetLunchBreak()
	{
		$this->setAjax(true);
		
		if ($this->_post->check('working_time'))
		{
		    
		}
	}
	
	public function pjActionGenerateSlots()
	{
		$this->setAjax(true);
		
		if ($this->_post->check('working_time'))
		{
				
		}
	}
	
	public function pjActionGetCustomLunchBreak()
	{
		$this->setAjax(true);
	    if($this->_post->toInt('date_id') > 0)
	    {
	        $arr = pjDateModel::factory()->find($this->_post->toInt('date_id'))->getData();
	        $this->set('arr', $arr);
	    }
	}
	
	public function pjActionGenerateCustomSlots()
	{
		$this->setAjax(true);
	}
}
?>