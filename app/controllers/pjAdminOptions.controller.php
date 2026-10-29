<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminOptions extends pjAdmin
{
	public function pjActionIndex()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjOptionModel = pjOptionModel::factory();
	    
	    $calendar_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $calendar_id = $this->_get->toInt('id');
	    }
	    
	    $arr = $pjOptionModel
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 3)
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    
	    $this->set('arr', $arr);
	    
	    $o_arr = $pjOptionModel->reset()->getPairs($calendar_id);
	    $this->set('o_arr', $o_arr);
	    
	    $tmp = $pjOptionModel->reset()->where('foreign_id', $calendar_id)->findAll()->getData();
	    $o_arr = array();
	    foreach ($tmp as $item)
	    {
	        $o_arr[$item['key']] = $item;
	    }
	    $this->set('_option_arr', $o_arr);
	    
	    $this->setLocalesData();
	    
	    $this->appendJs('pjAdminOptions.js');
	}
	
	public function pjActionPayments()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjOptionModel = pjOptionModel::factory();
	    
	    $calendar_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $calendar_id = $this->_get->toInt('id');
	    }
	    
	    $arr = $pjOptionModel
	    ->where('t1.foreign_id', $calendar_id)
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $this->set('arr', $arr);
	    
	    $o_arr = $pjOptionModel->reset()->getPairs($calendar_id);
	    $this->set('o_arr', $o_arr);
	    
	    $this->setLocalesData();
	    $this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('pjAdminOptions.js');
	}
	public function pjActionPaymentOptions()
	{
	    $this->checkLogin();
	    
	    $this->setAjax(true);
	    
	    $foreign_id = $this->getCalendarId();
	    if($this->_get->check('calendar_id') && $this->_get->toInt('calendar_id') > 0)
	    {
	        $foreign_id = $this->_get->toInt('calendar_id');
	    }
	    if($this->_post->check('options_update'))
	    {
	        if (pjObject::getPlugin('pjPayments') !== NULL && $this->_post->check('plugin_payment_options'))
	        {
	            $this->requestAction(array(
	                'controller' => 'pjPayments',
	                'action' => 'pjActionSaveOptions',
	                'params' => array(
	                    'foreign_id' => $foreign_id,
	                    'data' => $this->_post->toArray('plugin_payment_options'),
	                )
	            ), array('return'));
	        }
	        if(in_array($this->_post->toString('payment_method'), array('cash', 'bank')))
	        {
	            $pjPaymentOptionModel = new pjPaymentOptionModel();
	            $pjPaymentOptionModel
	            ->reset()
	            ->where('foreign_id', $foreign_id)
	            ->where('`payment_method`', $this->_post->toString('payment_method'))
	            ->limit(1)
	            ->modifyAll(array('is_active' => $this->_post->toInt('is_active')));
	        }
	        if ($this->_post->check('i18n'))
	        {
	            pjMultiLangModel::factory()->updateMultiLang($this->_post->toI18n('i18n'), $foreign_id, 'pjPayment', 'data');
	        }
	        if ($this->_post->check('i18n_options'))
	        {
	            pjMultiLangModel::factory()->updateMultiLang($this->_post->toI18n('i18n_options'), $foreign_id, 'pjOption', 'data');
	        }
	    }else{
	        $this->set('i18n', pjMultiLangModel::factory()->getMultiLang($foreign_id, 'pjPayment'));
	        $this->set('i18n_options', pjMultiLangModel::factory()->getMultiLang($foreign_id, 'pjOption'));
	        
	        $this->setLocalesData();
	        
	        $o_arr = pjOptionModel::factory()->getPairs($foreign_id);
	        $this->set('o_arr', $o_arr);
	    }
	}
	
	public function pjActionTerms()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjOptionModel = pjOptionModel::factory();
	    
	    $calendar_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $calendar_id = $this->_get->toInt('id');
	    }
	    
	    $arr = $pjOptionModel
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 3)
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($calendar_id, 'pjCalendar');
	    $this->set('arr', $arr);
	    
	    $o_arr = $pjOptionModel->reset()->getPairs($calendar_id);
	    $this->set('o_arr', $o_arr);
	    
	    $this->setLocalesData();
	    $this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
	    $this->appendJs('pjAdminOptions.js');
	}
	
	public function pjActionBookingForm()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjOptionModel = pjOptionModel::factory();
	    
	    $calendar_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $calendar_id = $this->_get->toInt('id');
	    }
	    
	    $group_1_arr = $pjOptionModel
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 4)
	    ->whereIn('`key`', array('o_bf_name', 'o_bf_email', 'o_bf_phone'))
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $this->set('group_1_arr', $group_1_arr);
	    
	    $group_2_arr = $pjOptionModel
	    ->reset()
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 4)
	    ->whereIn('`key`', array('o_bf_address_1', 'o_bf_address_2', 'o_bf_country', 'o_bf_state', 'o_bf_city', 'o_bf_zip'))
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $this->set('group_2_arr', $group_2_arr);
	    
	    $group_3_arr = $pjOptionModel
	    ->reset()
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 4)
	    ->whereIn('`key`', array('o_bf_captcha', 'o_bf_notes', 'o_bf_terms'))
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $this->set('group_3_arr', $group_3_arr);
	    
	    $o_arr = $pjOptionModel->reset()->getPairs($calendar_id);
	    $this->set('o_arr', $o_arr);
	    
	    $this->appendJs('pjAdminOptions.js');
	}
	
	public function pjActionNotifications()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
	    $pjOptionModel = pjOptionModel::factory();
	    
	    $calendar_id = $this->getCalendarId();
	    if($this->_get->toInt('id') > 0)
	    {
	        $calendar_id = $this->_get->toInt('id');
	    }
	    
	    $arr = $pjOptionModel
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.tab_id', 3)
	    ->orderBy('t1.order ASC')
	    ->findAll()
	    ->getData();
	    $arr['i18n'] = pjMultiLangModel::factory()->getMultiLang($calendar_id, 'pjCalendar');
	    $this->set('arr', $arr);
	    
	    $o_arr = $pjOptionModel->reset()->getPairs($calendar_id);
	    $this->set('o_arr', $o_arr);
	    
	    
	    
	    $this->setLocalesData();
	    $this->appendCss('awesome-bootstrap-checkbox.css', PJ_THIRD_PARTY_PATH . 'awesome_bootstrap_checkbox/');
	    $this->appendJs('jquery.multilang.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
	    $this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
	    $this->appendJs('pjAdminOptions.js');
	}
	public function pjActionNotificationsGetMetaData()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid request.'));
	    }
	    
	    if (!(isset($this->query['recipient']) && pjValidation::pjActionNotEmpty($this->query['recipient'])))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    
	    $this->set('arr', pjNotificationModel::factory()
	        ->where('t1.recipient', $this->query['recipient'])
	        ->where('t1.foreign_id', $this->getCalendarId())
	        ->orderBy('t1.id ASC')
	        ->findAll()
	        ->getData());
	}
	public function pjActionNotificationsGetContent()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isGet())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid request.'));
	    }
	    
	    if (!($this->_get->check('recipient') && $this->_get->check('variant') && $this->_get->check('transport'))
	        && pjValidation::pjActionNotEmpty($this->_get->toString('recipient'))
	        && pjValidation::pjActionNotEmpty($this->_get->toString('variant'))
	        && pjValidation::pjActionNotEmpty($this->_get->toString('transport'))
	        && in_array($this->_get->toString('transport'), array('email', 'sms'))
	        )
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    $calendar_id = $this->getCalendarId();
	    $arr = pjNotificationModel::factory()
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.recipient', $this->_get->toString('recipient'))
	    ->where('t1.variant', $this->_get->toString('variant'))
	    ->where('t1.transport', $this->_get->toString('transport'))
	    ->limit(1)
	    ->findAll()
	    ->getDataIndex(0);
	    
	    if (!$arr)
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Message not found.'));
	    }
	    
	    $arr['i18n'] = pjBaseMultiLangModel::factory()->getMultiLang($calendar_id, 'pjCalendar');
	    $this->set('arr', $arr);
	    
	    # Check SMS
	    $this->set('is_sms_ready', (isset($this->option_arr['plugin_sms_api_key']) && !empty($this->option_arr['plugin_sms_api_key']) ? 1 : 0));
	    
	    # Get locales
	    $this->setLocalesData();
	}
	
	public function pjActionNotificationsSetContent()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if (!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Invalid request.'));
	    }
	    
	    if (!(isset($this->body['id']) && pjValidation::pjActionNumeric($this->body['id'])))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    
	    $isToggle = $this->_post->check('is_active') && in_array($this->_post->toInt('is_active'), array(1,0));
	    $isFormSubmit = $this->_post->check('i18n') && !$this->_post->isEmpty('i18n');
	    
	    if (!($isToggle xor $isFormSubmit))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Data mismatch.'));
	    }
	    
	    if ($isToggle)
	    {
	        pjNotificationModel::factory()
	        ->set('id', $this->_post->toInt('id'))
	        ->modify(array('is_active' => $this->_post->toInt('is_active')));
	    } elseif ($isFormSubmit) {
	        pjBaseMultiLangModel::factory()->updateMultiLang($this->_post->toArray('i18n'), $this->getCalendarId(), 'pjCalendar');
	    }
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Notification has been updated.'));
	}
	
	public function pjActionInstall()
	{
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    
		$slot_length = 0;
		$is_weekly_ok = 1;
		
		$wt_arr = pjWorkingTimeModel::factory()
			->where('t1.foreign_id', $this->getCalendarId())
			->limit(1)
			->findAll()
			->getDataIndex(0);
		if ($wt_arr !== FALSE)
		{
			foreach (array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') as $weekday)
			{
				if ($wt_arr[$weekday.'_dayoff'] == 'T')
				{
					continue;
				}
				if ($slot_length == 0)
				{
					$slot_length = $wt_arr[$weekday.'_length'];
				}
				if ($slot_length != $wt_arr[$weekday.'_length'])
				{
					$is_weekly_ok = 0;
					break;
				}
			}
		}
		
		$dt_arr = pjDateModel::factory()
			->select('t1.slot_length')
			->where('t1.foreign_id', $this->getCalendarId())
			->where('t1.is_dayoff', 'F')
			->groupBy('t1.slot_length')
			->findAll()
			->getData();
			
		foreach ($dt_arr as $data)
		{
			if ($slot_length == 0)
			{
				$slot_length = $data['slot_length'];
			}
			if ($slot_length != $data['slot_length'])
			{
				$is_weekly_ok = 0;
				break;
			}
		}
		$this->set('is_weekly_ok', $is_weekly_ok);
		
		$this->appendJs('pjAdminOptions.js');
	}

	public function pjActionPreview()
	{
	    $this->checkLogin();
	    
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    $this->appendJs('pjAdminOptions.js');
	}
	
	public function pjActionUpdate()
	{
	    $this->checkLogin();
	    
	    if (!pjAuth::factory()->hasAccess())
	    {
	        $this->sendForbidden();
	        return;
	    }
	    if (self::isPost() && $this->_post->toInt('options_update'))
	    {
	        $calendar_id = $this->getCalendarId();
	        
	        $pjOptionModel = new pjOptionModel();
	        $pjOptionModel
	        ->where('foreign_id', $calendar_id)
	        ->where('type', 'bool')
	        ->where('tab_id', $this->_post->toInt('tab'))
	        ->modifyAll(array('value' => '1|0::0'));
	        
	        foreach ($this->_post->raw() as $key => $value)
	        {
	            if (preg_match('/value-(string|text|int|float|enum|bool|color)-(.*)/', $key) === 1)
	            {
	                list(, $type, $k) = explode("-", $key);
	                if (!empty($k))
	                {
	                    $_value = ':NULL';
	                    if ($value)
	                    {
	                        switch ($type)
	                        {
	                            case 'string':
	                            case 'text':
	                            case 'enum':
	                            case 'color':
	                                $_value = $this->_post->toString($key);
	                                break;
	                            case 'int':
	                            case 'bool':
	                                $_value = $this->_post->toInt($key);
	                                break;
	                            case 'float':
	                                $_value = $this->_post->toFloat($key);
	                                break;
	                        }
	                    }
	                    $pjOptionModel
	                    ->reset()
	                    ->where('foreign_id', $calendar_id)
	                    ->where('`key`', $k)
	                    ->limit(1)
	                    ->modifyAll(array('value' => $_value));
	                }
	            }
	        }
	        $i18n_arr = $this->_post->toI18n('i18n');
	        
	        if (!empty($i18n_arr))
	        {
	            pjMultiLangModel::factory()->updateMultiLang($i18n_arr, $calendar_id, 'pjCalendar', 'data');
	        }
	        
	        $next_action = $this->_post->toString('next_action');
	        if($next_action == 'pjActionIndex')
	        {
	            $err = 'AO03';
	        }else if($next_action == 'pjActionTerms'){
	            $err = 'AO06';
	        }else if($next_action == 'pjActionBookingForm'){
	            $err = 'AO04';
	        }
	        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=" . $next_action . "&err=$err");
		}
	}
	
	public function pjActionUpdateTheme()
	{
	    $this->setAjax(true);
	    
	    if (!$this->isXHR())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing headers.'));
	    }
	    
	    if(!self::isPost())
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'HTTP method not allowed.'));
	    }
	    
	    if (!$this->_post->has('theme'))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    
	    // the theme belongs to the calendar that is being edited (it used to be saved for foreign_id 0, i.e. the defaults for NEW calendars only)
	    $theme = $this->_post->toString('theme');
	    if (!preg_match('/^theme([1-9]|1[01])$/', $theme))
	    {
	        self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
	    }
	    
	    pjOptionModel::factory()
	    ->where('foreign_id', $this->getCalendarId())
	    ->where('`key`', 'o_theme')
	    ->limit(1)
	    ->modifyAll(array('value' => 'theme1|theme2|theme3|theme4|theme5|theme6|theme7|theme8|theme9|theme10|theme11::' . $theme));
	    
	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Theme has been changed.'));
	}
	
	
	/**
	 * Theme 11: saves (or resets) the colour / font / shape settings used by the front-end theme.
	 * The posted JSON is validated against a strict whitelist before it is stored.
	 */
	public function pjActionSaveThemeColors()
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

		$post = $this->_post->raw();
		$value = '';
		if (!(isset($post['reset']) && (int) $post['reset'] === 1))
		{
			$valid = pjAppController::getTheme11Colors(isset($post['colors']) ? (string) $post['colors'] : '');
			if (empty($valid))
			{
				self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Missing, empty or invalid parameters.'));
			}
			$value = json_encode($valid);
		}

		$foreign_id = $this->getCalendarId();
		$exists = pjOptionModel::factory()
			->where('foreign_id', $foreign_id)
			->where('`key`', 'o_theme11_colors')
			->findCount()
			->getData();
		if ($exists > 0)
		{
			pjOptionModel::factory()
				->where('foreign_id', $foreign_id)
				->where('`key`', 'o_theme11_colors')
				->limit(1)
				->modifyAll(array('value' => $value));
		} else {
			pjOptionModel::factory()
				->setAttributes(array(
					'foreign_id' => $foreign_id,
					'key' => 'o_theme11_colors',
					'tab_id' => NULL,
					'value' => $value,
					'label' => NULL,
					'type' => 'text',
					'order' => NULL,
					'is_visible' => 0,
					'style' => NULL
				))
				->insert();
		}

		self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => $value === '' ? 'Theme colors have been reset.' : 'Theme colors have been saved.'));
	}
}
?>