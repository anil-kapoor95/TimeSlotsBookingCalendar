<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontEnd extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	
	public function pjActionCancel()
	{
		$this->setAjax(false);
		$this->setLayout('pjActionCancel');
		
		if((!$this->_get->check('uuid') && !$this->_get->check('hash')) || $this->_get->isEmpty('uuid') || $this->_get->isEmpty('hash'))
		{
		    $this->set('response', array('status' => 'ERR', 'code' => 100, 'text' => __('front_cancel_100', true)));
		    return;
		}
		if($this->_get->toString('hash') != sha1($this->_get->toString('uuid') . PJ_SALT))
		{
			$this->set('response', array('status' => 'ERR', 'code' => 100, 'text' => __('front_cancel_100', true)));
			return;
		}
		
		$pjBookingModel = pjBookingModel::factory();
		$arr = $pjBookingModel
		->select('t1.*, t4.content AS `country_name`, t3.email AS customer_email, t3.name AS customer_name, t3.phone AS customer_phone')
		->join('pjClient', "t1.client_id=t2.id", 'left')
		->join('pjAuthUser', "t2.foreign_id=t3.id", 'left')
		->join('pjMultiLang', sprintf("t4.model='pjBaseCountry' AND t4.foreign_id=t2.customer_country AND t4.locale=t1.locale_id AND t4.field='name'"), 'left outer')
		->where('t1.uuid', $this->_get->toString('uuid'))
		->limit(1)
		->findAll()
		->getDataIndex(0);
		
		if((int) $arr['locale_id'] > 0)
		{
		    $locale = pjLocaleModel::factory()->find($arr['locale_id'])->getData();
		    if(!empty($locale))
		    {
		        $this->setLocaleId($locale['id']);
		        $this->loadSetFields(true);
		    }
		}
		
		$_option_arr = pjOptionModel::factory()->getPairs($arr['calendar_id']);
		$this->set('_option_arr', $_option_arr);
			
		if ($arr === FALSE || empty($arr))
		{
			$this->set('response', array('status' => 'ERR', 'code' => 101, 'text' => __('front_cancel_101', true)));
			return;
		}
		
		if ($this->_get->check('status') && $arr['booking_status'] == 'cancelled')
		{
			$this->set('response', array('status' => 'ERR', 'code' => 103, 'text' => __('front_cancel_103', true)));
			return;
		}
		
		if ($arr['booking_status'] == 'cancelled')
		{
			$this->set('response', array('status' => 'ERR', 'code' => 102, 'text' => __('front_cancel_102', true)));
			return;
		}
		
		if (self::isPost() && $this->_post->check('ts_cancel_booking'))
		{
			$result = $pjBookingModel
				->reset()
				->set('id', $arr['id'])
				->modify(array('booking_status' => 'cancelled'))
				->getAffectedRows();
			if ($result == 1)
			{
				# Cancellation email(s)
				$booking_arr = $pjBookingModel
					->reset()
					->select("t1.*, t1.id AS `booking_id`, t2.content AS `country_name`, t3.content AS `cancel_subject_client`, t4.content AS `cancel_tokens_client`,
						t5.content AS `cancel_subject_admin`, t6.content AS `cancel_tokens_admin`, t8.email AS `customer_email`, t8.name AS `customer_name`, t8.phone AS `customer_phone`, 
                        t9.content AS `confirm_sms_admin`, t10.content AS `payment_sms_admin`, t11.content AS `calendar_name`")
					->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
					->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='cancel_subject_client'", 'left outer')
					->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='cancel_tokens_client'", 'left outer')
					->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='cancel_subject_admin'", 'left outer')
					->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='cancel_tokens_admin'", 'left outer')
					->join('pjClient', "t1.client_id=t7.id", 'left')
					->join('pjAuthUser', "t7.foreign_id=t8.id", 'left')
					->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.locale=t1.locale_id AND t9.field='confirm_sms_admin'", 'left outer')
					->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.locale=t1.locale_id AND t10.field='payment_sms_admin'", 'left outer')
					->join('pjMultiLang', "t11.model='pjCalendar' AND t11.foreign_id=t1.calendar_id AND t11.locale=t1.locale_id AND t11.field='title'", 'left outer')
					->find($arr['id'])
					->getData();

				$booking_arr['bs_arr'] = pjBookingSlotModel::factory()
					->join('pjBooking', 't2.id=t1.booking_id', 'inner')
					->where('t1.booking_id', $arr['id'])
					->findAll()
					->getData();
				
				$option_arr = array_merge($this->option_arr, $_option_arr);	
				pjFrontEnd::pjActionConfirmSend($option_arr, $booking_arr, 'cancel');
				
				pjUtil::redirect(sprintf("%sindex.php?controller=pjFrontEnd&action=pjActionCancel&uuid=%s&hash=%s&status=1", PJ_INSTALL_URL, $this->_get->toString('uuid'), $this->_get->toString('hash')));
				return;
			}
		}
		
		$arr['bs_arr'] = pjBookingSlotModel::factory()
			->where('t1.booking_id', $arr['id'])
			->orderBy('t1.booking_date ASC, t1.start_ts ASC')
			->findAll()
			->getData();
		
		$this->set('arr', $arr);
		$this->set('response', array('status' => 'OK', 'code' => 200, 'text' => ''));
		
		if(pjObject::getPlugin('pjPayments') !== NULL)
		{
		    $this->set('payment_titles', pjPayments::getPaymentTitles($arr['calendar_id'], $this->getLocaleId()));
		}else{
		    $this->set('payment_titles', __('payment_methods', true));
		}
		
		$this->appendCss('index.php?controller=pjFrontEnd&action=pjActionLoadCss&cid=' . $arr['calendar_id'], PJ_INSTALL_URL, true);
		
	}
	
	public function pjActionCaptcha()
	{
	    $this->setAjax(true);
	    
	    header("Cache-Control: max-age=3600, private");
	    
	    $rand = $this->_get->toInt('rand') ?: rand(1, 9999);
	    $patterns = 'app/web/img/button.png';
	    if(!empty($this->option_arr['o_captcha_background_front']) && $this->option_arr['o_captcha_background_front'] != 'plain')
	    {
	        $patterns = PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_IMG_PATH') . 'captcha_patterns/' . $this->option_arr['o_captcha_background_front'];
	    }
	    $Captcha = new pjCaptcha(PJ_INSTALL_PATH . $this->getConstant('pjBase', 'PLUGIN_WEB_PATH') . 'obj/arialbd.ttf', $this->defaultCaptcha, (int) $this->option_arr['o_captcha_length_front']);
	    $Captcha->setImage($patterns)->setMode($this->option_arr['o_captcha_mode_front'])->init($rand);
	    
	    exit;
	}
	
	public function pjActionCheckCaptcha()
	{
	    if ($this->isXHR())
	    {
	        echo isset($_SESSION[$this->defaultCaptcha]) && $this->_get->check('captcha') && $_SESSION[$this->defaultCaptcha] == strtoupper($this->_get->toString('captcha')) ? 'true' : 'false';
	    }
	    exit;
	}
	
	public function pjActionCheckReCaptcha()
	{
	    $this->setAjax(true);
	    $pjHttp = new pjHttp();
	    $pjHttp->curlRequest('https://www.google.com/recaptcha/api/siteverify?secret='.$this->option_arr['o_captcha_secret_key_front'].'&response='.$this->_get->toString('recaptcha'));
	    $verifyResponse = $pjHttp->getResponse();
	    $responseData = json_decode($verifyResponse);
	    echo $responseData->success ? 'true': 'false';
	    exit;
	}
	
	public function pjActionConfirmSend($option_arr, $booking_arr, $type)
	{
		if (!in_array($type, array('confirm', 'payment', 'cancel')))
		{
			return false;
		}
		
		$pjNotificationModel = pjNotificationModel::factory();
		
		$Email = self::getMailer($option_arr);
		$tokens = pjAppController::getTokens($booking_arr, $option_arr);
		
		$admin_email = array();
		$admin_phone = array();
		
		$and_where = "";
		if((int) $booking_arr['calendar_id'] > 0)
		{
			$and_where .= sprintf(" AND t1.id IN (SELECT `TCU`.user_id FROM `%s` AS `TCU` WHERE `TCU`.calendar_id=%u)", pjCalendarUserModel::factory()->getTable(), $booking_arr['calendar_id']);
		}
		switch ($type)
		{
			case 'confirm':
				// Client
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'client')->where('transport', 'email')->where('variant', 'confirmation')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_subject_client']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_tokens_client']);
    				if (!empty($subject) && !empty($message))
    				{
    					$message = stripcslashes($message);
    					$Email
    					->setTo($booking_arr['customer_email'])
    					->setSubject($subject)
    					->send($message);
    				}
			    }
				// Admin
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'admin')->where('transport', 'email')->where('variant', 'confirmation')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_subject_admin']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_tokens_admin']);
    				if (!empty($subject) && !empty($message))
    				{
    				    $message = stripcslashes($message);
    				    
    					$user_arr = pjAuthUserModel::factory()->where(sprintf("(t1.status = 'T'%s)", $and_where))->findAll()->getDataPair('id', 'email');
    					foreach ($user_arr as $recipient)
    					{
    					    if(!empty($recipient) && !in_array($recipient, $admin_email))
    					    {
    					        $admin_email[] = $recipient;
    					    }
    					}
    					$admin_email = array_filter($admin_email);
    					foreach($admin_email as $email)
    					{
    					    $Email
    					    ->setTo($email)
    					    ->setSubject($subject)
    					    ->send($message);
    					}
    				}
			    }
				
				/*Sms to admin*/
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'admin')->where('transport', 'sms')->where('variant', 'confirmation')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['confirm_sms_admin']);
    				if (!empty($message))
    				{
    					$priv_key = md5($option_arr['private_key'] . PJ_SALT);
    					
    					$user_arr = pjAuthUserModel::factory()->where(sprintf("(t1.status = 'T'%s)", $and_where))->findAll()->getDataPair('id', 'phone');
    					foreach ($user_arr as $recipient)
    					{
    					    if(!empty($recipient) && !in_array($recipient, $admin_phone))
    					    {
    					        $admin_phone[] = $recipient;
    					    }
    					}
    					$admin_phone = array_filter($admin_phone);
    					foreach ($admin_phone as $recipient)
    					{
    					    $number = preg_replace('/\D/', '', $recipient);
    					    $params = array(
    					        'number' => $number,
    					        'text' => $message,
    					        'type' => 'unicode',
    					        'key' => $priv_key
    					    );
    					    $result = pjBaseSms::init($params)->pjActionSend();
    					}
    				}
			    }
				break;
			case 'payment':
				// Client
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'client')->where('transport', 'email')->where('variant', 'payment')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_subject_client']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_tokens_client']);
    				if (!empty($subject) && !empty($message))
    				{
    				    $message = stripcslashes($message);
    				    $Email
    				    ->setTo($booking_arr['customer_email'])
    				    ->setSubject($subject)
    				    ->send($message);
    				}
			    }
				// Admin
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'admin')->where('transport', 'email')->where('variant', 'payment')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_subject_admin']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_tokens_admin']);
    				if (!empty($subject) && !empty($message))
    				{
    				    $message = stripcslashes($message);
    				    
    				    $user_arr = pjAuthUserModel::factory()->where(sprintf("(t1.status = 'T'%s)", $and_where))->findAll()->getDataPair('id', 'email');
    				    foreach ($user_arr as $recipient)
    				    {
    				        if(!empty($recipient) && !in_array($recipient, $admin_email))
    				        {
    				            $admin_email[] = $recipient;
    				        }
    				    }
    				    $admin_email = array_filter($admin_email);
    				    foreach($admin_email as $email)
    				    {
    				        $Email
    				        ->setTo($email)
    				        ->setSubject($subject)
    				        ->send($message);
    				    }
    				}
			    }
				/*Sms to admin*/
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'admin')->where('transport', 'sms')->where('variant', 'payment')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['payment_sms_admin']);
    				if (!empty($message))
    				{
    				    $priv_key = md5($option_arr['private_key'] . PJ_SALT);
    				    
    				    $user_arr = pjAuthUserModel::factory()->where(sprintf("(t1.status = 'T'%s)", $and_where))->findAll()->getDataPair('id', 'phone');
    				    foreach ($user_arr as $recipient)
    				    {
    				        if(!empty($recipient) && !in_array($recipient, $admin_phone))
    				        {
    				            $admin_phone[] = $recipient;
    				        }
    				    }
    				    $admin_phone = array_filter($admin_phone);
    				    foreach ($admin_phone as $recipient)
    				    {
    				        $number = preg_replace('/\D/', '', $recipient);
    				        $params = array(
    				            'number' => $number,
    				            'text' => $message,
    				            'type' => 'unicode',
    				            'key' => $priv_key
    				        );
    				        $result = pjBaseSms::init($params)->pjActionSend();
    				    }
    				}
			    }
				break;
			
			case 'cancel':
				// Client
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'client')->where('transport', 'email')->where('variant', 'cancel')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['cancel_subject_client']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['cancel_tokens_client']);
    				if (!empty($subject) && !empty($message))
    				{
    				    $message = stripcslashes($message);
    				    $Email
    				    ->setTo($booking_arr['customer_email'])
    				    ->setSubject($subject)
    				    ->send($message);
    				}
			    }
				// Admin
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking_arr['calendar_id'])->where('recipient', 'admin')->where('transport', 'email')->where('variant', 'cancel')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$subject = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['cancel_subject_admin']);
    				$message = str_replace($tokens['search'], $tokens['replace'], @$booking_arr['cancel_tokens_admin']);
    				if (!empty($subject) && !empty($message))
    				{
    				    $message = stripcslashes($message);
    				    
    				    $user_arr = pjAuthUserModel::factory()->where(sprintf("(t1.status = 'T'%s)", $and_where))->findAll()->getDataPair('id', 'email');
    				    foreach ($user_arr as $recipient)
    				    {
    				        if(!empty($recipient) && !in_array($recipient, $admin_email))
    				        {
    				            $admin_email[] = $recipient;
    				        }
    				    }
    				    $admin_email = array_filter($admin_email);
    				    foreach($admin_email as $email)
    				    {
    				        $Email
    				        ->setTo($email)
    				        ->setSubject($subject)
    				        ->send($message);
    				    }
    				}
			    }
				break;
		}
	}
	
	public function pjActionLoad()
	{
		$this->setAjax(false);
		$this->setLayout('pjActionFront');
		
		ob_start();
		header("Content-Type: text/javascript; chartset=utf-8");
		
		$cid = @$this->_get->toInt('cid');
				
		$days_off = $dates_off = $dates_on = array();
		$w_arr = pjWorkingTimeModel::factory()->where('t1.foreign_id', $cid)->findAll()->getData();
		if (!empty($w_arr))
		{
			$w_arr = $w_arr[0];
			
			if ($w_arr['monday_dayoff'] == 'T')
			{
				$days_off[] = 1;
			}
			if ($w_arr['tuesday_dayoff'] == 'T')
			{
				$days_off[] = 2;
			}
			if ($w_arr['wednesday_dayoff'] == 'T')
			{
				$days_off[] = 3;
			}
			if ($w_arr['thursday_dayoff'] == 'T')
			{
				$days_off[] = 4;
			}
			if ($w_arr['friday_dayoff'] == 'T')
			{
				$days_off[] = 5;
			}
			if ($w_arr['saturday_dayoff'] == 'T')
			{
				$days_off[] = 6;
			}
			if ($w_arr['sunday_dayoff'] == 'T')
			{
				$days_off[] = 0;
			}
		}

		$d_arr = pjDateModel::factory()
			->where('t1.foreign_id', $cid)
			->where('t1.date >= CURDATE()')
			->findAll()
			->getData();

		foreach ($d_arr as $date)
		{
			if ($date['is_dayoff'] == 'T')
			{
				$dates_off[] = $date['date'];
			} else {
				$dates_on[] = $date['date'];
			}
		}

		$this->set('days_off', $days_off);
		$this->set('dates_off', $dates_off);
		$this->set('dates_on', $dates_on);
	}
	
	public function pjActionLoadCss()
	{
		$theme = $this->_get->check('theme') ? $this->_get->toString('theme') : $this->option_arr['o_theme'];
		if((int) $theme > 0)
		{
			$theme = 'theme' . $theme;
		}
		if (!preg_match('/^theme\d+$/', (string) $theme))
		{
			// only theme1, theme2, ... are valid names (the value becomes part of a file name)
			$theme = 'theme11';
		}
		$dm = new pjDependencyManager(PJ_INSTALL_PATH, PJ_THIRD_PARTY_PATH);
		$dm->load(PJ_CONFIG_PATH . 'dependencies.php')->resolve();
		$arr = array(
			array('file' => 'tooltipster.css', 'path' => $dm->getPath('pj_tooltipster')),
			array('file' => 'pj-calendar.css', 'path' => PJ_FRAMEWORK_LIBS_PATH . 'pj/css/'),
			array('file' => "style.css", 'path' => PJ_CSS_PATH),
			array('file' => "$theme.css", 'path' => PJ_CSS_PATH . "themes/")
		);
		
		header("Content-Type: text/css; charset=utf-8");
		$cid = (int) @$this->_get->toInt('cid');
		$theme11_colors = array();
		if ($theme == 'theme11')
		{
			$theme11_colors = pjAppController::getTheme11Colors(isset($this->option_arr['o_theme11_colors']) ? $this->option_arr['o_theme11_colors'] : '');
			$fonts_url = pjAppController::getTheme11FontsUrl($theme11_colors);
			if ($fonts_url != '')
			{
				// @import must be the very first rule of the stylesheet
				echo '@import url("' . $fonts_url . '");' . "\n";
			}
		}
		foreach ($arr as $item)
		{
			$string = FALSE;
			if ($stream = fopen($item['path'] . $item['file'], 'rb'))
			{
				$string = stream_get_contents($stream);
				fclose($stream);
			}
			
			if ($string !== FALSE)
			{
				echo str_replace(
					array(
						'images/', "pjWrapper"
					),
					array(
						PJ_INSTALL_URL . PJ_IMG_PATH . 'frontend/',
						"pjWrapperTSBCalendar_" . $this->getCalendarId()
					),
					$string
				) . "\n";
			}
		}
		if ($theme == 'theme11')
		{
			if (!empty($theme11_colors))
			{
				// Colour / font / shape overrides saved from the admin Preview page (Theme 11 only)
				echo str_replace('pjWrapper', 'pjWrapperTSBCalendar_' . $this->getCalendarId(), pjAppController::getTheme11CssBlock($theme11_colors));
			}
		} else {
			// Theme 11 adds a few extra elements (progress steps, section titles); the older themes keep their original look
			$w = '#pjWrapperTSBCalendar_' . $this->getCalendarId();
			echo $w . ' .tsb-only{display:none !important;}' . "\n";
			// a hidden title between two form rows would break the ".row + .row" spacing rule of style.css: keep it
			echo $w . ' .pj-calendar-form .row ~ .tsb-only + .row{padding-top:10px;}' . "\n";
		}
		exit;
	}

	public function pjActionOrder()
	{
		if ($this->isXHR())
		{
			if ($this->_get->toInt('cid') <= 0)
			{
			    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
			}
			
			$response = $this->validateCheckout($this->_get->toInt('cid'));
			if ($response['status'] == 'ERR')
			{
			    self::jsonResponse($response);
			}
			
			$response = $this->validateCart($this->_get->toInt('cid'));
			if ($response['status'] == 'ERR')
			{
				self::jsonResponse($response);
			}
			$cid = $this->_get->toInt('cid');
			if (!$this->_post->check('ts_preview') || !isset($_SESSION[$this->defaultForm . '_' . $cid]) || empty($_SESSION[$this->defaultForm . '_' . $cid]) || !isset($_SESSION[$this->defaultCart]) || empty($_SESSION[$this->defaultCart]))
			{
			    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
			}
			
			$FORM = $_SESSION[$this->defaultForm . '_' . $cid];
			$CART = $_SESSION[$this->defaultCart];
			$cid = $this->_get->toInt('cid');
			$locale = $this->_get->toInt('locale');
			
			$pjBookingSlotModel = pjBookingSlotModel::factory();
			if (isset($CART[$cid]) && !empty($CART[$cid]))
			{
				foreach ($CART[$cid] as $date => $items)
				{
					$t_arr = pjAppController::getDailySlots($cid, $date, $this->option_arr);
					foreach ($items as $key => $qty)
					{
						list($start_ts, $end_ts) = explode("|", $key);
						$reservedSlots = isset($_SESSION[$this->defaultSlots][$cid][$key]) ? $_SESSION[$this->defaultSlots][$cid][$key] : 1;
						
						$bs_arr = $pjBookingSlotModel
							->reset()
							->join('pjBooking', "t2.id=t1.booking_id", 'inner')
							->where('t1.booking_date', $date)
							->where('t1.start_time', date("H:i:s", $start_ts))
							->where('t1.end_time', date("H:i:s", $end_ts))
							->where('t1.start_ts', $start_ts)
							->where('t1.end_ts', $end_ts)
							->where('t2.booking_status <>', 'cancelled')
							->where('t2.calendar_id', $cid)
							->orderBy('t1.booking_date ASC, t1.start_ts ASC')
							->findAll()
							->getData();
						$booked = 0;
	                    foreach ($bs_arr as $bs) {
	                    	$booked += $bs['cnt'];                    	
	                    }
	                    $availableSlotsNumber = (int)$t_arr['slot_limit'] - $booked;
	                    if($t_arr['people'] < $availableSlotsNumber)
	    				{
	    				    $availableSlotsNumber = $t_arr['people'];
	    				}
						if($reservedSlots > $availableSlotsNumber)
						{
							pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' =>'Duplicated slots'));
							exit;
						}
					}
				}
			}
				
			$data = array();
			$data['uuid'] = pjUtil::uuid();
			$data['calendar_id'] = $cid;
			$data['locale_id'] = $locale;
			$data['ip'] = pjUtil::getClientIp();
			$data['booking_status'] = $this->option_arr['o_status_if_not_paid'];
			if (isset($FORM['payment_method']) && $FORM['payment_method'] != 'creditcard')
			{
				unset($FORM['cc_type']);
				unset($FORM['cc_num']);
				unset($FORM['cc_code']);
				unset($FORM['cc_exp_month']);
				unset($FORM['cc_exp_year']);
			}
			$info = pjAppController::getCartTotal($cid, $this->cart, $this->option_arr);
			$data['booking_price'] = $info['price'];
			$data['booking_tax'] = $info['tax'];
			$data['booking_deposit'] = $info['deposit'];
			$data['booking_total'] = $info['total'];
			$data['booking_discount'] = $info['discount'];
			$data['discount_code'] = ":NULL";
			if(isset($FORM['voucher_code']) && !empty($FORM['voucher_code']))
			{
			    $data['discount_code'] = $FORM['voucher_code'];
			}
			
			$post = $this->_post->raw();
			
			/* Client section */
			$is_new_client = false;
			$update_client = false;
			$pjClientModel = pjClientModel::factory();
			$data['client_id'] = ':NULL';
			if($this->isFrontLogged())
			{
			    $cnt = $pjClientModel->where('t1.foreign_id', $this->getClientId())->findCount()->getData();
			    if($cnt == 0)
			    {
			        $is_new_client = true;
			    }else{
			        $update_client = true;
			    }
			}else{
			    $is_new_client = true;
			}
			if($is_new_client == true)
			{
			    $client_by_email = pjFrontClient::init(array('email' => $FORM['customer_email']))->getClientByEmail();
			    
			    if($client_by_email == false)
			    {
			        $FORM['status'] = 'T';
			        $FORM['locale_id'] = $this->getLocaleId();
			        $FORM['calendar_id'] = $cid;
			        $response = pjFrontClient::init($FORM)->createClient();
			        if(isset($response['client_id']) && (int) $response['client_id'] > 0)
			        {
			            $data['client_id'] = $response['client_id'];
			        }
			    }else{
			        $update_client = true;
			    }
			}else{
			    $client = $pjClientModel->reset()->where('foreign_id', $this->getClientId())->findAll()->getDataIndex(0);
			    $data['client_id'] = $client['id'];
			}
			if($update_client == true)
			{
			    $c_data = array();
			    $auth_data = array();
			    if (isset($FORM['customer_address_1']) ) {
			    	$c_data['customer_address_1'] = $FORM['customer_address_1'];	
			    }
			    if (isset($FORM['customer_address_2'])) {
			    	$c_data['customer_address_2'] = $FORM['customer_address_2'];	
			    }
			    if (isset($FORM['customer_country'])) {
			    	$c_data['customer_country'] = $FORM['customer_country'];	
			    }
			    if (isset($FORM['customer_city'])) {
			    	$c_data['customer_city'] = $FORM['customer_city'];	
			    }
			    if (isset($FORM['customer_state'])) {
			    	$c_data['customer_state'] = $FORM['customer_state'];	
			    }
			    if (isset($FORM['customer_zip'])) {
			    	$c_data['customer_zip'] = $FORM['customer_zip'];	
			    }
			    if (isset($FORM['customer_notes'])) {
			    	$c_data['customer_notes'] = $FORM['customer_notes'];	
			    }			    
			    if (isset($FORM['customer_name']) && !empty($FORM['customer_name'])) {
			    	$auth_data['name'] = $FORM['customer_name'];
			    }
			    if (isset($FORM['customer_email']) && !empty($FORM['customer_email'])) {
			    	$auth_data['email'] = $FORM['customer_email'];
			    }
			    if (isset($FORM['customer_phone'])) {
			    	$auth_data['phone'] = $FORM['customer_phone'];
			    }
			    $foreign_id = $this->getClientId();
			    if(isset($client_by_email))
			    {
			        $foreign_id = $client_by_email['id'];
			    }
			    if(!empty($auth_data))
			    {
			        pjAuthUserModel::factory()->set('id', $foreign_id)->modify($auth_data);
			    }
			    if (!empty($c_data)) {
			    	$pjClientModel->reset()->where('foreign_id', $foreign_id)->limit(1)->modifyAll($c_data);
			    }
			    $client = $pjClientModel->reset()->where('foreign_id', $foreign_id)->findAll()->getDataIndex(0);
			    pjFrontClient::init(array('id' => $foreign_id))->setClientSession();
			    $data['client_id'] = $client['id'];
			}
			/* End client section */
			
			$data = array_merge($post, $FORM, $data);
			
			$required = array('uuid', 'calendar_id', 'locale_id', 'booking_status', 'booking_price', 'booking_deposit', 'booking_tax', 'booking_total');
			$pjBookingModel = pjBookingModel::factory();
			if (!$pjBookingModel->validateRequest($required, $data))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Data not valid.'));
			}
			
			$booking_id = $pjBookingModel->reset()->setAttributes($data)->insert()->getInsertId();
			if ($booking_id === false || empty($booking_id))
			{
				pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Order has not been stored.'));
			}
			
			if (isset($CART[$cid]) && !empty($CART[$cid]))
			{
				$prices = pjAppController::getCartPrices($cid, $this->cart);
				$pjBookingSlotModel = pjBookingSlotModel::factory();
				$pjBookingSlotModel->setBatchFields(array('booking_id', 'booking_date', 'start_time', 'end_time', 'start_ts', 'end_ts', 'price', 'cnt'));
				foreach ($CART[$cid] as $date => $items)
				{
					foreach ($items as $key => $qty)
					{
						list($start_ts, $end_ts) = explode("|", $key);
						$reservedSlots = isset($_SESSION[$this->defaultSlots][$cid][$key]) ? $_SESSION[$this->defaultSlots][$cid][$key] : 1;
						$pjBookingSlotModel->addBatchRow(array(
							$booking_id,
							$date,
							date("H:i:s", $start_ts),
							date("H:i:s", $end_ts),
							$start_ts,
							$end_ts,
							@$prices[$cid][$key],
						    $reservedSlots
						));
					}
				}
				$pjBookingSlotModel->insertBatch();
			}
			
			# Confirmation email(s)
			$booking_arr = $pjBookingModel
				->reset()
				->select('t1.*, t1.id AS `booking_id`, t2.content AS `country_name`, t3.content AS `confirm_subject_client`, t4.content AS `confirm_tokens_client`,
					t5.content AS `confirm_subject_admin`, t6.content AS `confirm_tokens_admin`, t8.email AS `customer_email`, t8.name AS `customer_name`, t8.phone AS `customer_phone`,  
                    t9.content AS `confirm_sms_admin`, t10.content AS `payment_sms_admin`, t11.content AS `calendar_name`')
				->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='confirm_subject_client'", 'left outer')
				->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='confirm_tokens_client'", 'left outer')
				->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='confirm_subject_admin'", 'left outer')
				->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='confirm_tokens_admin'", 'left outer')
				->join('pjClient', "t1.client_id=t7.id", 'left')
				->join('pjAuthUser', "t7.foreign_id=t8.id", 'left')
				->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.locale=t1.locale_id AND t9.field='confirm_sms_admin'", 'left outer')
				->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.locale=t1.locale_id AND t10.field='payment_sms_admin'", 'left outer')
				->join('pjMultiLang', "t11.model='pjCalendar' AND t11.foreign_id=t1.calendar_id AND t11.locale=t1.locale_id AND t11.field='title'", 'left outer')
				->find($booking_id)
				->getData();
			
			$booking_arr['bs_arr'] = pjBookingSlotModel::factory()
				->join('pjBooking', 't2.id=t1.booking_id', 'inner')
				->where('t1.booking_id', $booking_id)
				->findAll()
				->getData();

			#$bs_ids = $pjBookingSlotModel->getDataPair('id', null);
				
			pjFrontEnd::pjActionConfirmSend($this->option_arr, $booking_arr, 'confirm');
			# Confirmation email(s)
			
			$_SESSION[$this->defaultCart][$cid] = NULL;
			unset($_SESSION[$this->defaultCart][$cid]);
			if(empty($_SESSION[$this->defaultCart]))
			{
			    $_SESSION[$this->defaultCart] = NULL;
			    unset($_SESSION[$this->defaultCart]);
			}
			
			$_SESSION[$this->defaultForm . '_' . $cid] = NULL;
			unset($_SESSION[$this->defaultForm . '_' . $cid]);
			
			$_SESSION[$this->defaultCaptcha] = NULL;
			unset($_SESSION[$this->defaultCaptcha]);
			
			$_SESSION[$this->defaultSlots][$cid] = NULL;
			unset($_SESSION[$this->defaultSlots][$cid]);
			if(empty($_SESSION[$this->defaultSlots]))
			{
			    $_SESSION[$this->defaultSlots] = NULL;
			    unset($_SESSION[$this->defaultSlots]);
			}
			
			pjAppController::jsonResponse(array(
				'status' => 'OK',
				'code' => 200,
				'text' => 'Order has been stored.',
				'booking_uuid' => $data['uuid'],
				'payment_method' => @$data['payment_method']
			));
		}
		exit;
	}
	
	public function pjActionConfirm()
	{
	    $this->setAjax(true);
	    
	    if (pjObject::getPlugin('pjPayments') === NULL)
	    {
	        $this->log('pjPayments plugin not installed');
	        exit;
	    }
	    
	    $pjPayments = new pjPayments();
	    if($pjPlugin = $pjPayments->getPaymentPlugin($_REQUEST))
	    {
	        if($uuid = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionGetCustom', 'params' => $_REQUEST), array('return')))
	        {
	            $pjBookingModel = pjBookingModel::factory();
	            
	            $booking_arr = $pjBookingModel
	            ->reset()
	            ->select('t1.*, t1.id AS `booking_id`, t2.content AS `country_name`, t3.content AS `payment_subject_client`, t4.content AS `payment_tokens_client`,
					t5.content AS `payment_subject_admin`, t6.content AS `payment_tokens_admin`, t8.email AS `customer_email`, t8.name AS `customer_name`, t8.phone AS `customer_phone`,
                    t9.content AS `confirm_sms_admin`, t10.content AS `payment_sms_admin`, t11.content AS `calendar_name`')
                ->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
                ->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='payment_subject_client'", 'left outer')
                ->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='payment_tokens_client'", 'left outer')
                ->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='payment_subject_admin'", 'left outer')
                ->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='payment_tokens_admin'", 'left outer')
                ->join('pjClient', "t1.client_id=t7.id", 'left')
                ->join('pjAuthUser', "t7.foreign_id=t8.id", 'left')
                ->join('pjMultiLang', "t9.model='pjCalendar' AND t9.foreign_id=t1.calendar_id AND t9.locale=t1.locale_id AND t9.field='confirm_sms_admin'", 'left outer')
                ->join('pjMultiLang', "t10.model='pjCalendar' AND t10.foreign_id=t1.calendar_id AND t10.locale=t1.locale_id AND t10.field='payment_sms_admin'", 'left outer')
                ->join('pjMultiLang', "t11.model='pjCalendar' AND t11.foreign_id=t1.calendar_id AND t11.locale=t1.locale_id AND t11.field='title'", 'left outer')
                ->where('t1.uuid', $uuid)
                ->limit(1)
                ->findAll()
                ->getDataIndex(0);
    				
				if (!empty($booking_arr))
				{
				    $booking_id = $booking_arr['id'];
				    $option_arr = pjOptionModel::factory()->getPairs($booking_arr['calendar_id']);
				    $option_arr = array_merge($this->option_arr, $option_arr);
				    $params = array(
				        'request'		=> $_REQUEST,
				        'payment_method' => $_REQUEST['payment_method'],
				        'foreign_id'	 => $booking_arr['calendar_id'],
				        'amount'		 => $booking_arr['booking_deposit'],
				        'txn_id'		 => $booking_arr['txn_id'],
				        'order_id'	   => $booking_arr['id'],
				        'cancel_hash'	=> sha1($booking_arr['uuid'].strtotime($booking_arr['created']).PJ_SALT),
				        'key'			=> md5($option_arr['private_key'] . PJ_SALT)
				    );
				    $response = $this->requestAction(array('controller' => $pjPlugin, 'action' => 'pjActionConfirm', 'params' => $params), array('return'));
				    
				    if($response['status'] == 'OK')
				    {
				        $this->log("Payments | {$pjPlugin} plugin<br>Reservation was confirmed. UUID: {$uuid}");
				        if($booking_arr['booking_status'] != $option_arr['o_status_if_paid'])
				        {
    				        $pjBookingModel
    				        ->reset()
    				        ->set('id', $booking_arr['id'])
    				        ->modify(array('booking_status' => $option_arr['o_status_if_paid']));
    				        
    				        $booking_arr['bs_arr'] = pjBookingSlotModel::factory()
    				        ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
    				        ->where('t1.booking_id', $booking_id)
    				        ->findAll()
    				        ->getData();
    				        
                			pjFrontEnd::pjActionConfirmSend($option_arr, $booking_arr, 'payment');
				        }
            			echo $option_arr['o_thankyou_page'];
            			exit;
				    }elseif($response['status'] == 'CANCEL'){
				        $this->log("Payments | {$pjPlugin} plugin<br>Payment was cancelled. UUID: {$uuid}");
				        $pjBookingModel
				        ->reset()
				        ->set('id', $booking_arr['id'])
				        ->modify(array('booking_status' => 'cancelled', 'processed_on' => ':NOW()'));
				        
				        echo $option_arr['o_cancel_page'];
				        exit;
				    }else{
				        $this->log("Payments | {$pjPlugin} plugin<br>Reservation confirmation was failed. UUID: {$uuid}");
				    }
				    
				    if(isset($response['redirect']) && $response['redirect'] == true)
				    {
				        echo $option_arr['o_thankyou_page'];
				        exit;
				    }
				}else{
				    $this->log("Payments | {$pjPlugin} plugin<br>Reservation with UUID {$uuid} not found.");
				}
				echo $this->option_arr['o_thankyou_page'];
				exit;
	        }
	    }
	    
	    echo $this->option_arr['o_thank_you_page'];
	    exit;
	}
	
	
	public function pjActionUpdateSlotCnt()
	{
	    $this->setAjax(true);
	    
	    $key = sprintf('%s|%s', $this->_post->toString('startTs'), $this->_post->toString('endTs'));
	    $can_be_added = true;
	    $in_cart = $this->cart->check($this->_post->toInt('cid'), $this->_post->toString('date'), $key) ? 'T' : 'F';
	    if((int) @$this->option_arr['o_max_slots_per_booking'] > 0 && !$this->cart->check($this->_post->toInt('cid'), $this->_post->toString('date'), $key))
	    {
	        if($this->cart->getCount() >= (int) @$this->option_arr['o_max_slots_per_booking'])
	        {
	            $can_be_added = false;
	        }
	    }
	    if($can_be_added == true)
	    {
    	    if ($this->_post->toInt('cnt') <= 0) 
    	    {
    	        $this->cart->remove($this->_post->toInt('cid'), $this->_post->toString('date'), $key);
    	        unset($_SESSION[$this->defaultSlots][$this->_post->toInt('cid')][$key]);
    	    } else {
    	        $_SESSION[$this->defaultSlots][$this->_post->toInt('cid')][$key] = $this->_post->toInt('cnt');
    	    }
    	    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => '', 'in_cart' => $in_cart));
	    }else{
	        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => '', 'in_cart' => $in_cart));
	    }
	    
	}
	
	public function pjActionApplyCode()
	{
	    $this->setAjax(true);
	    if($this->isXHR())
	    {
	        if($this->_get->check('voucher_code') && trim((string) $this->_get->toString('voucher_code')) !== '')
	        {
	            $pjVoucherModel = pjVoucherModel::factory();
	            $voucher_item = NULL;
	            $voucher_data = array();
	            $voucher_code = trim((string) $this->_get->toString('voucher_code'));
	            $cart_arr = $this->getCart($this->_get->toInt('cid'));
	            foreach ($cart_arr as $cid => $date_arr)
	            {
	                if ($cid != $this->_get->toInt('cid'))
	                {
	                    continue;
	                }
	                foreach ($date_arr as $date => $time_arr)
	                {
	                    foreach ($time_arr as $time => $q)
	                    {
	                        list($start_ts, $end_ts) = explode("|", $time);
	                        
	                        $result = $pjVoucherModel->reset()->getVoucher($voucher_code, date("Y-m-d", $start_ts), date("H:i:s", $start_ts));
	                        if($result['status'] == 'OK')
	                        {
	                            $voucher_valid = true;
	                            $voucher_data[$start_ts] = $result;
	                            if($voucher_item == NULL)
	                            {
	                                $voucher_item = $result;
	                            }
	                        }
	                    }
	                }
	            }
	            $cid = $this->_get->toInt('cid');
	            if($voucher_valid == true)
	            {
	                $_SESSION[$this->defaultForm . '_' . $cid]['voucher_code'] = $voucher_code;
	                $_SESSION[$this->defaultForm . '_' . $cid]['voucher_item'] = $voucher_item;
	                $_SESSION[$this->defaultCart]['voucher_data'] = $voucher_data;
	                self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('apply_voucher_msg_ARRAY_200', true)));
	            }else{
	                self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('apply_voucher_msg_ARRAY_101', true)));
	            }
	        }else{
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('apply_voucher_msg_ARRAY_100', true)));
	        }
	    }
	}
	
	public function pjActionRemoveCode()
	{
	    $this->setAjax(true);
	    if($this->isXHR())
	    {
	        if($this->_get->check('voucher_code') && !$this->_get->isEmpty('voucher_code'))
	        {
	            $cid = $this->_get->toInt('cid');
	            $pjVoucherModel = pjVoucherModel::factory();
	            $_SESSION[$this->defaultForm . '_' . $cid]['voucher_code'] = NULL;
	            $_SESSION[$this->defaultForm . '_' . $cid]['voucher_item'] = NULL;
	            $_SESSION[$this->defaultCart]['voucher_data'] = NULL;
	            unset($_SESSION[$this->defaultForm . '_' . $cid]['voucher_code']);
	            unset($_SESSION[$this->defaultForm . '_' . $cid]['voucher_item']);
	            unset($_SESSION[$this->defaultCart]['voucher_data']);
	            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ""));
	        }else{
	            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('apply_voucher_msg_ARRAY_100', true)));
	        }
	    }
	}
	
	public function pjActionCheckClientAccount()
	{
		$this->setAjax(true);
	
		if ($this->isXHR())
		{
			if ($this->_get->check('email') && $this->_get->toString('email') != '')
			{
				$client_by_email = pjFrontClient::init(array('email' => $this->_get->toString('email')))->getClientByEmail();
				echo $client_by_email && !$this->isFrontLogged() ? 'true' : 'false';
			} else {
				echo 'false';
			}
		}
		exit;
	}
}
?>