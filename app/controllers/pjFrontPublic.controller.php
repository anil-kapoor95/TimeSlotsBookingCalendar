<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontPublic extends pjFront
{
	public function __construct()
	{
		parent::__construct();
		
		$this->setAjax(true);
		
		$this->setLayout('pjActionEmpty');
	}
	public function pjActionIpBlocked()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        
	    }
	}
	public function pjActionCalendar()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
		{
		    if ($this->_get->check('_escaped_fragment_'))
			{
			    preg_match('/\/date:([\d\-\.\/]+)?/', $this->_get->toString('_escaped_fragment_'), $matches);
				if (!empty($matches))
				{
					$date = $matches[1];
				}
			} else {
				$date = @$this->_get->toString('date');
			}
			
			$year = $month = $day = NULL;
			if (!empty($date))
			{
				list($year, $month, $day) = explode("-", $date);
			}
			
			$this->set('calendar', $this->getCalendar($this->_get->toInt('cid'), $year, $month, $day));
		}
	}
	
	public function pjActionCart()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('cart_arr', $this->getCart($this->_get->toInt('cid')));
			if ((int) $this->option_arr['o_hide_prices'] === 0)
			{
			    $this->set('cart_price_arr', pjAppController::getCartPrices($this->_get->toInt('cid'), $this->cart));
			}
		}
	}
	public function pjActionGetSummary()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        $this->set('amount', pjAppController::getCartTotal($this->_get->toInt('cid'), $this->cart, $this->option_arr));
	    }
	}
	public function pjActionCheckout()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
			if ($this->_post->check('ts_checkout'))
			{
				if ($this->_get->toInt('cid') <= 0)
				{
					pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
				}
				
				$cid = $this->_get->toInt('cid');
				$_SESSION[$this->defaultForm . '_' . $cid] = array_merge($_SESSION[$this->defaultForm . '_' . $cid], $this->_post->raw());
				
				$response = $this->validateCheckout($this->_get->toInt('cid'));
				pjAppController::jsonResponse($response);
				
			} else {
			    if (!empty($_SESSION[$this->defaultCart][$this->_get->toInt('cid')]))
				{
					if (in_array($this->option_arr['o_bf_country'], array(2,3)))
					{
						$this->set('country_arr', pjBaseCountryModel::factory()
							->select('t1.*, t2.content AS name')
							->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->pjActionGetLocale()."'", 'left outer')
							->where('t1.status', 'T')
							->orderBy('`name` ASC')
							->findAll()
							->getData()
						);
					}
					
					if ($this->option_arr['o_allow_bank'] == '1')
					{
					    $bank_account = pjMultiLangModel::factory()
					    ->select('t1.content')
					    ->where('t1.model','pjOption')
					    ->where('t1.locale', $this->getLocaleId())
					    ->where('t1.foreign_id', $this->_get->toInt('cid'))
					    ->where('t1.field', 'o_bank_account')
					    ->limit(1)
					    ->findAll()
					    ->getDataIndex(0);
					    $this->set('bank_account', $bank_account['content']);
					}
					
					if(pjObject::getPlugin('pjPayments') !== NULL)
					{
					    $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->_get->toInt('cid')));
					    $this->set('payment_titles', pjPayments::getPaymentTitles($this->_get->toInt('cid'), $this->getLocaleId()));
					}else{
					    $this->set('payment_titles', __('payment_methods', true));
					}
					
					$this->set('terms_arr', $this->getTerms($this->_get->toInt('cid')));
					$this->set('amount', pjAppController::getCartTotal($this->_get->toInt('cid'), $this->cart, $this->option_arr));
					
					$this->set('status', 'OK');
					$this->set('cart_arr', $this->getCart($this->_get->toInt('cid')));
					if ((int) $this->option_arr['o_hide_prices'] === 0)
					{
					    $this->set('cart_price_arr', pjAppController::getCartPrices($this->_get->toInt('cid'), $this->cart));
					}
					$this->set('terms_arr', $this->getTerms($this->_get->toInt('cid')));
				} else {
					$this->set('status', 'ERR');
					$this->set('code', '101');
				}
			}
		}
	}
		
	public function pjActionPreview()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
			if (!$this->cart->isEmpty())
			{
				if($this->_get->toInt('duplicated') == 1)
				{
				    $this->set('cart_arr', $this->getCart($this->_get->toInt('cid')));
					if ((int) $this->option_arr['o_hide_prices'] === 0)
					{
					    $this->set('cart_price_arr', pjAppController::getCartPrices($this->_get->toInt('cid'), $this->cart));
					}
					$_SESSION[$this->defaultCart] = NULL;
					unset($_SESSION[$this->defaultCart]);
					
					$this->set('status', 'OK');
					$this->set('duplicated', true);
				}else{
				    $cid = $this->_get->toInt('cid');
				    if (!isset($_SESSION[$this->defaultForm . '_' . $cid]) || empty($_SESSION[$this->defaultForm . '_' . $cid]) || !isset($_SESSION[$this->defaultCart][$cid]) || empty($_SESSION[$this->defaultCart][$cid]))
					{
						$this->set('status', 'ERR');
						$this->set('code', '102'); 
					} else {
					    if (in_array($this->option_arr['o_bf_country'], array(2,3)) && (int) @$_SESSION[$this->defaultForm . '_' . $cid]['customer_country'] > 0)
						{
							$this->set('country_arr', pjBaseCountryModel::factory()
								->select('t1.*, t2.content AS name')
								->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
							    ->find($_SESSION[$this->defaultForm . '_' . $cid]['customer_country'])
								->getData()
							);
						}
						$this->set('amount', pjAppController::getCartTotal($this->_get->toInt('cid'), $this->cart, $this->option_arr));
						$this->set('status', 'OK');
						
						$this->set('cart_arr', $this->getCart($this->_get->toInt('cid')));
						if ((int) $this->option_arr['o_hide_prices'] === 0)
						{
						    $this->set('cart_price_arr', pjAppController::getCartPrices($this->_get->toInt('cid'), $this->cart));
						}
						
						if(pjObject::getPlugin('pjPayments') !== NULL)
						{
						    $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->_get->toInt('cid')));
						    $this->set('payment_titles', pjPayments::getPaymentTitles($this->_get->toInt('cid'), $this->getLocaleId()));
						}else{
						    $this->set('payment_titles', __('payment_methods', true));
						}
						
						if ($this->option_arr['o_allow_bank'] == '1')
						{
						    $bank_account = pjMultiLangModel::factory()
						    ->select('t1.content')
						    ->where('t1.model','pjOption')
						    ->where('t1.locale', $this->getLocaleId())
						    ->where('t1.foreign_id', $this->_get->toInt('cid'))
						    ->where('t1.field', 'o_bank_account')
						    ->limit(1)
						    ->findAll()
						    ->getDataIndex(0);
						    $this->set('bank_account', $bank_account['content']);
						}
					}
				}
			} else {
				$this->set('status', 'ERR');
				$this->set('code', '101'); 
			}
		}
	}
	
	public function pjActionTimeslots()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if ($this->_get->check('_escaped_fragment_'))
			{
			    preg_match('/\/date:([\d\-\.\/]+)?/', $this->_get->toString('_escaped_fragment_'), $matches);
				if (!empty($matches))
				{
					$date = $matches[1];
				}
			} else {
			    $date = @$this->_get->toString('date');
			}
			
			$year = $month = $day = NULL;
			if (!empty($date))
			{
				list($year, $month, $day) = explode("-", $date);
			}
			
			$result = $this->getTimeslots($this->_get->toInt('cid'), $date);
			
			foreach ($result as $key => $value)
			{
				$this->set($key, $value);
			}
		}
	}

	public function pjActionBooking()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
			$this->set('status', 'OK');
			
			if (!$this->_get->isEmpty('booking_uuid'))
			{
			    $booking_uuid = $this->_get->toString('booking_uuid');
			} elseif ($this->_get->check('_escaped_fragment_')) {
			    preg_match('/\/Booking\/([A-Z]{2}\d{10})/', $this->_get->toString('_escaped_fragment_'), $matches);
				if (isset($matches[1]))
				{
					$booking_uuid = $matches[1];
				}
			}
			
			$booking_arr = pjBookingModel::factory()
			->select("t1.*, t3.name AS customer_name, t3.email AS customer_email, t3.phone AS customer_phone")
			->join('pjClient', "t1.client_id=t2.id", 'left')
			->join('pjAuthUser', "t2.foreign_id=t3.id", 'left')
			->where('t1.uuid', $booking_uuid)
			->limit(1)
			->findAll()->getData();
			
			if (!empty($booking_arr))
			{
			    $booking_arr = $booking_arr[0];
			    
			    if(pjObject::getPlugin('pjPayments') !== NULL)
			    {
			        $pjPlugin = pjPayments::getPluginName($booking_arr['payment_method']);
			        if(pjObject::getPlugin($pjPlugin) !== NULL)
			        {
			            $this->set('params', $pjPlugin::getFormParams(array('payment_method' => $booking_arr['payment_method']), array(
			                'locale_id'	 => $this->getLocaleId(),
			                'return_url'	=> $this->option_arr['o_thankyou_page'],
			                'id'			=> $booking_arr['id'],
			                'foreign_id'	=> $booking_arr['calendar_id'],
			                'uuid'		  => $booking_arr['uuid'],
			                'name'		  => $booking_arr['customer_name'],
			                'email'		 => $booking_arr['customer_email'],
			                'phone'		 => $booking_arr['customer_phone'],
			                'amount'		=> $booking_arr['booking_deposit'],
			                'cancel_hash'   => sha1($booking_arr['uuid'].strtotime($booking_arr['created']).PJ_SALT),
			                'currency_code' => $this->option_arr['o_currency'],
			            )));
			        }
			        if ($booking_arr['payment_method'] == 'bank')
			        {
			            $bank_account = pjMultiLangModel::factory()
			            ->select('t1.content')
			            ->where('t1.model','pjOption')
			            ->where('t1.locale', $this->getLocaleId())
			            ->where('t1.foreign_id', $booking_arr['calendar_id'])
			            ->where('t1.field', 'o_bank_account')
			            ->limit(1)
			            ->findAll()
			            ->getDataIndex(0);
			            $this->set('bank_account', $bank_account['content']);
			        }
			    }
			    
			    $this->set('booking_arr', $booking_arr);
			}
		}
	}
	
	public function pjActionWeekly()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if ($this->_get->check('_escaped_fragment_'))
			{
			    preg_match('/\/date:([\d\-\.\/]+)?/', $this->_get->toString('_escaped_fragment_'), $matches);
				if (!empty($matches))
				{
					$date = $matches[1];
				}
			} else {
			    $date = @$this->_get->toString('date');
			}
			$week_arr = pjUtil::getWeekRange($date, $this->option_arr['o_week_start']);
			$date = $week_arr[0];
			
			$result = $this->getWeeklyTimeslots($this->_get->toInt('cid'), $date);
			
			foreach ($result as $key => $value)
			{
				$this->set($key, $value);
			}
		}
	}
	
	public function pjActionRouter()
	{
		$this->setAjax(false);

		if (isset($_GET['_escaped_fragment_']))
		{
			$templates = array('Checkout', 'Preview', 'Timeslots', 'Booking', 'Calendar', 'Cart', 'Weekly');
			preg_match('/^\/(\w+).*/', $_GET['_escaped_fragment_'], $m);
			if (isset($m[1]) && in_array($m[1], $templates))
			{
				$template = 'pjAction'.$m[1];
			
				if (method_exists($this, $template))
				{
					$this->$template();
				}
				$this->setTemplate('pjFrontPublic', $template);
			}
		}
	}
	
	public function pjActionLogin()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if($this->_post->check('ts_login'))
	        {
	            $data = array();
	            $data['login_email'] = $this->_post->toString('login_email');
	            $data['login_password'] = $this->_post->toString('login_password');
	            $response = pjFrontClient::init($data)->doClientLogin();
	            $code = (int) $response['code'];
	            $login_err = __('plugin_base_login_err', true);
	            $text = '';
	            if(isset($login_err[$code]))
	            {
	                if($code == 5)
	                {
	                    $text = sprintf($login_err[$code], (int)$this->option_arr['o_failed_login_lock_after']);
	                }else{
	                    $text = $login_err[$code];
	                }
	            }
	            pjAppController::jsonResponse(array('status' => $response['status'], 'code' => $code, 'text' => $text));
	        }
	    }
	}
	public function pjActionLogout()
	{
	    if ($this->isXHR())
	    {
	        if($this->isFrontLogged())
	        {
	            $this->session->unsetData($this->defaultClient);
	        }
	        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
	    }
	    exit;
	}
	public function pjActionForgot()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if(self::isPost() && $this->_post->check('ts_forgot'))
	        {
	            $params = array();
	            $params['locale_id'] = $this->getLocaleId();
	            $params['calendar_id'] = $this->_get->toInt('cid');
	            $params['email'] = $this->_post->toString('email');
	            $resp = pjFrontClient::init($params)->doSendPassword();
	            $forgot_messages = __('forgot_messages', true, false);
	            $resp['text'] = $forgot_messages[$resp['code']];
	            pjAppController::jsonResponse($resp);
	        }
	    }
	}
	public function pjActionCheckEmail()
	{
	    $this->setAjax(true);
	    if ($this->isXHR())
	    {
	        if (!$this->_get->check('customer_email'))
	        {
	            echo 'false';
	            exit;
	        }
	        $c_email = $this->_get->toString('customer_email');
	        if(empty($c_email))
	        {
	            echo 'false';
	            exit;
	        }
	        $pjAuthUserModel = pjAuthUserModel::factory()
	        ->join('pjClient', 't1.id=t2.foreign_id', 'left outer')
	        ->where('t1.role_id', 3)
	        ->where('t1.email', $c_email);
	        if ($this->isFrontLogged())
	        {
	            $pjAuthUserModel->where('t1.id !=', $this->getClientId());
	        }
	        echo $pjAuthUserModel->findCount()->getData() == 0 ? 'true' : 'false';
	    }
	    exit;
	}
	public function pjActionProfile()
	{
	    if ($this->isXHR() || $this->_get->check('_escaped_fragment_'))
	    {
	        if(self::isPost() && $this->_post->check('ts_profile'))
	        {
	            $pjClientModel = pjClientModel::factory();
	            $foreign_id = $this->_post->toInt('foreign_id');
	            $client = $pjClientModel->where('foreign_id', $foreign_id)->findAll()->getDataIndex(0);
	            $post = $this->_post->raw();
	            $pjClientModel->reset()->where('id', $client['id'])->limit(1)->modifyAll($post);
	            $data = array();
	            $data['email'] = isset($post['customer_email']) ? $post['customer_email'] : ":NULL";
	            $data['password'] = isset($post['customer_password']) ? $post['customer_password'] : ":NULL";
	            $data['name'] = isset($post['customer_name']) ? $post['customer_name'] : ":NULL";
	            $data['phone'] = isset($post['customer_phone']) ? $post['customer_phone'] : ":NULL";
	            pjAuthUserModel::factory()->set('id', $foreign_id)->modify($data);
	            pjFrontClient::init(array('id' => $foreign_id))->setClientSession();
	            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('front_update_profile_msg', true)));
	        }else{
	            $this->set('country_arr', pjBaseCountryModel::factory()
                ->select('t1.*, t2.content AS name')
                ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
                ->where('t1.status', 'T')
                ->orderBy('`name` ASC')
                ->findAll()
                ->getData()
                );
	        }
	    }
	}
}
?>