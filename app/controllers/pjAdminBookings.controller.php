<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAdminBookings extends pjAdmin
{
    public function pjActionIndex()
    {
        $this->checkLogin();
        if (!pjAuth::factory()->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
        
        $this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));
        
        $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
        $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
        $this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
        $this->appendJs('pjAdminBookings.js');
    }
    public function pjActionGetBooking()
    {
        $this->checkLogin();
        
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            $pjBookingModel = pjBookingModel::factory();
            $pjBookingSlotModel = pjBookingSlotModel::factory();
            
            $pjBookingModel->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.calendar_id AND t2.field='title' AND t2.locale=%u", $this->getLocaleId()), 'left');
            $pjBookingModel->join('pjClient', "t1.client_id=t3.id", 'left');
            $pjBookingModel->join('pjAuthUser', "t3.foreign_id=t4.id", 'left');
            
            if ($this->_get->toInt('calendar_id') > 0)
            {
                $pjBookingModel->where('t1.calendar_id', $this->_get->toInt('calendar_id'));
            }
            if ($this->_get->toInt('client_id') > 0)
            {
                $pjBookingModel->where('t1.client_id', $this->_get->toInt('client_id'));
            }
            if(!$this->isMasterAdmin())
            {
                $pjBookingModel->where(sprintf("(t1.calendar_id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
            }
            if ($q = $this->_get->toString('q'))
            {
                $q = str_replace(array('_', '%'), array('\_', '\%'), trim($q));
                $pjBookingModel->where(sprintf("(t1.client_id IN(SELECT `TC`.id FROM `%2\$s` AS `TC` WHERE `TC`.customer_company LIKE '%1\$s' OR `TC`.customer_address_1 LIKE '%1\$s' OR `TC`.customer_address_2 LIKE '%1\$s' OR `TC`.customer_state LIKE '%1\$s' OR `TC`.customer_city LIKE '%1\$s' OR `TC`.customer_zip LIKE '%1\$s' OR `TC`.foreign_id IN (SELECT `TU`.id FROM `%3\$s` AS `TU` WHERE `TU`.role_id=3 AND (`TU`.email LIKE '%1\$s' OR `TU`.name LIKE '%1\$s' OR `TU`.phone LIKE '%1\$s'))))", "%$q%", pjClientModel::factory()->getTable(), pjAuthUserModel::factory()->getTable()));
            }
            if ($this->_get->toString('booking_status'))
            {
                $status = $this->_get->toString('booking_status');
                if(in_array($status, array('confirmed', 'cancelled', 'pending')))
                {
                    $pjBookingModel->where('t1.booking_status', $status);
                }
            }
            if ($this->_get->toString('date_from') && $this->_get->toString('date_to'))
            {
                $date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
                $date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
                $pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` BETWEEN '%s' AND '%s')", $pjBookingSlotModel->getTable(), $date_from, $date_to));
            }else{
                if ($this->_get->toString('date_from'))
                {
                    $date_from = pjDateTime::formatDate($this->_get->toString('date_from'), $this->option_arr['o_date_format']);
                    $pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` >= '%s')", $pjBookingSlotModel->getTable(), $date_from));
                }
                if ($this->_get->toString('date_to'))
                {
                    $date_to = pjDateTime::formatDate($this->_get->toString('date_to'), $this->option_arr['o_date_format']);
                    $pjBookingModel->where(sprintf("t1.id IN (SELECT `booking_id` FROM `%s` WHERE `booking_date` <= '%s')", $pjBookingSlotModel->getTable(), $date_to));
                }
            }
            $column = 'created';
            $direction = 'DESC';
            $sort_str = "created DESC";
            $allowed_columns = array('uuid', 'id', 'customer_name', 'booking_total', 'booking_status');
            if ($this->_get->toString('column') && in_array($this->_get->toString('column'), $allowed_columns) && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
            {
                $column = $this->_get->toString('column');
                $direction = strtoupper($this->_get->toString('direction'));
                $sort_str = "$column $direction";
                if($this->_get->toString('column') == 'stay')
                {
                    $sort_str = "date_from $direction";
                }
                if($this->_get->toString('column') == 'customer_name')
                {
                    $sort_str = "t4.name $direction";
                }
            }
            
            $total = $pjBookingModel->findCount()->getData();
            $rowCount = $this->_get->toInt('rowCount') ?: 10;
            $pages = ceil($total / $rowCount);
            $page = $this->_get->toInt('page') ?: 1;
            $offset = ((int) $page - 1) * $rowCount;
            if ($page > $pages)
            {
                $page = $pages;
            }
            $data = $pjBookingModel
            ->select(sprintf("t1.*, t2.content as calendar_title, t4.name AS customer_name, t4.email AS customer_email, t4.phone AS customer_phone, AES_DECRYPT(t1.cc_type, '".PJ_SALT."') AS `cc_type`,
								AES_DECRYPT(t1.cc_num, '".PJ_SALT."') AS `cc_num`,
								AES_DECRYPT(t1.cc_exp_month, '".PJ_SALT."') AS `cc_exp_month`,
								AES_DECRYPT(t1.cc_exp_year, '".PJ_SALT."') AS `cc_exp_year`,
								AES_DECRYPT(t1.cc_code, '".PJ_SALT."') AS `cc_code`", $pjBookingSlotModel->getTable(), pjMultiLangModel::factory()->getTable(), $this->getLocaleId()))
					->orderBy($sort_str)->limit($rowCount, $offset)
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
			
			foreach ($data as $k => $v)
			{
			    if(isset($items[$v['id']]))
			    {
			        foreach ($items[$v['id']] as $key => $val)
			        {
			            $data[$k]['items'][$key] = join("~.~", $val);
			        }
			    }
			    $data[$k]['customer_name'] = pjSanitize::html($v['customer_name']);
			    $data[$k]['customer_email'] = pjSanitize::html($v['customer_email']);
			    $data[$k]['customer_phone'] = pjSanitize::html($v['customer_phone']);
			    $data[$k]['total_formated'] = pjCurrency::formatPrice($v['booking_total']);
			}
			
			self::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
        }
    }
    
    public function pjActionCreate()
    {
        $this->checkLogin();
        if (!pjAuth::factory()->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
        if (self::isPost() && $this->_post->check('booking_create'))
        {
            $pjBookingSlotModel = pjBookingSlotModel::factory();
            $post = $this->_post->raw();

            $option_arr = pjOptionModel::factory()->getPairs($post['calendar_id']);
            if (isset($_SESSION[$this->adminBooking]) && isset($_SESSION[$this->adminBooking][$post['hash']]) && !empty($_SESSION[$this->adminBooking][$post['hash']]))
            {
                foreach ($_SESSION[$this->adminBooking][$post['hash']] as $key => $slot)
                {
                    $t_arr = pjAppController::getDailySlots($post['calendar_id'], $slot['booking_date'], $option_arr);
                    $bs_arr = $pjBookingSlotModel
                    ->reset()
                    ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                    ->where('t2.calendar_id', $post['calendar_id'])
                    ->where('t2.booking_status !=', 'cancelled')
                    ->where('t1.booking_date', $slot['booking_date'])
                    ->where('t1.start_time', $slot['start_time'])
                    ->where('t1.end_time', $slot['end_time'])
                    ->where('t1.start_ts', $slot['start_ts'])
                    ->where('t1.end_ts', $slot['end_ts'])
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
                    if($slot['cnt'] > $availableSlotsNumber)
                    {
                        pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK04");
                    }
                }
            }
            
            $pjBookingModel = pjBookingModel::factory();
            $required = array('uuid', 'booking_status', 'booking_price', 'booking_tax', 'booking_total', 'booking_deposit');
            $isPaymentDisabled = (int) $option_arr['o_disable_payments'] === 1;
            if (!$isPaymentDisabled)
            {
                $required[] = 'payment_method';
            }
            if (!$pjBookingModel->validateRequest($required, $post))
            {
                pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK15");
            }
            
            $data = array();
            $data['ip'] = pjUtil::getClientIp();
            if ($isPaymentDisabled)
            {
                $data['payment_method'] = ':NULL';
            }
            if($this->_post->check('new_client'))
            {
                $c_data = array();
            	if (!$this->_post->isEmpty('customer_name')) {
                	$c_data['customer_name'] = $this->_post->toString('customer_name');	
                }
                if (!$this->_post->isEmpty('customer_email')) {
                	$c_data['customer_email'] = $this->_post->toString('customer_email');
                }
                if (!$this->_post->isEmpty('customer_phone')) {
                	$c_data['customer_phone'] = $this->_post->toString('customer_phone');
                }
                if (!$this->_post->isEmpty('customer_address_1')) {
                	$c_data['customer_address_1'] = $this->_post->toString('customer_address_1');
                }
                if (!$this->_post->isEmpty('customer_address_2')) {
                	$c_data['customer_address_2'] = $this->_post->toString('customer_address_2');
                }
                if (!$this->_post->isEmpty('customer_city')) {
                	$c_data['customer_city'] = $this->_post->toString('customer_city');
                }
                if (!$this->_post->isEmpty('customer_state')) {
                	$c_data['customer_state'] = $this->_post->toString('customer_state');
                }
                if (!$this->_post->isEmpty('customer_zip')) {
                	$c_data['customer_zip'] = $this->_post->toString('customer_zip');
                }
                if ($this->_post->toInt('customer_country') > 0) {
                	$c_data['customer_country'] = $this->_post->toInt('customer_country');
                }
                if (!$this->_post->isEmpty('customer_notes')) {
                	$c_data['customer_notes'] = $this->_post->toString('customer_notes');
                }
                $client_by_email = pjFrontClient::init(array('email' => $c_data['customer_email']))->getClientByEmail();
                if($client_by_email == false)
                {
                    $c_data['status'] = 'T';
                    $response = pjFrontClient::init($c_data)->createClient();
                    if(isset($response['client_id']) && (int) $response['client_id'] > 0)
                    {
                        $data['client_id'] = $response['client_id'];
                    }
                }else{
                    $foreign_id = $client_by_email['id'];
                    $auth_data = array();
                    if (!$this->_post->isEmpty('customer_name')) {
                    	$auth_data['name'] = $this->_post->toString('customer_name');
                    }
                    if (!$this->_post->isEmpty('customer_email')) {
                    	$auth_data['email'] = $this->_post->toString('customer_email');
                    }
                    if (!$this->_post->isEmpty('customer_phone')) {
                    	$auth_data['phone'] = $this->_post->toString('customer_phone');
                    }
                    if ($auth_data) {
                    	pjAuthUserModel::factory()->set('id', $foreign_id)->modify($auth_data);
                    }
                    $client = pjClientModel::factory()->where('foreign_id', $foreign_id)->findAll()->getDataIndex(0);
                    if ($c_data) {
                    	pjClientModel::factory()->set('id', $client['id'])->modify($c_data);
                    }
                    $data['client_id'] = $client['id'];
                }
            }else{
                $client = pjClientModel::factory()->find($post['client_id'])->getData();
                unset($client['id']);unset($client['foreign_id']);unset($client['calendar_id']);
                $data = array_merge($post, $client);
            }
            $data['locale_id'] = $this->getLocaleId();
            $id = $pjBookingModel->reset()->setAttributes(array_merge($post, $data))->insert()->getInsertId();
            if ($id !== false && (int) $id > 0)
            {
                if (isset($_SESSION[$this->adminBooking]) && isset($_SESSION[$this->adminBooking][$post['hash']]) && !empty($_SESSION[$this->adminBooking][$post['hash']]))
                {
                    $pjBookingSlotModel = pjBookingSlotModel::factory();
                    $pjBookingSlotModel->setBatchFields(array('booking_id', 'booking_date', 'start_time', 'end_time', 'start_ts', 'end_ts', 'price', 'cnt'));
                    foreach ($_SESSION[$this->adminBooking][$post['hash']] as $key => $slot)
                    {
                        $pjBookingSlotModel->addBatchRow(array(
                            $id, $slot['booking_date'], $slot['start_time'], $slot['end_time'], $slot['start_ts'], $slot['end_ts'], $slot['price'], $slot['cnt']
                        ));
                    }
                    $pjBookingSlotModel->insertBatch();
                    $_SESSION[$this->adminBooking] = NULL;
                    unset($_SESSION[$this->adminBooking]);
                }
                $err = 'ABK03';
            } else {
                $err = 'ABK04';
            }
            pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=$err");
        }
        if(self::isGet())
        {
            $hash = md5(uniqid(rand(), true));
            $add_slot = false;
            if($this->_get->check('date') && $this->_get->check('start_ts') && $this->_get->check('end_ts'))
            {
                $date = pjUtil::formatDate($this->_get->toString('date'), $this->option_arr['o_date_format']);
                $start_ts = $this->_get->toString('start_ts');
                $end_ts =  $this->_get->toString('end_ts');
                $pjWorkingTimeModel = pjWorkingTimeModel::factory();
                $wt_data = $pjWorkingTimeModel->getWorkingTime($this->getCalendarId());
                $wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
                $price = pjAppController::getPrices($this->getCalendarId(), $wt_arr, $date, $start_ts, $end_ts, 1);
                if (!isset($_SESSION[$this->adminBooking]))
                {
                    $_SESSION[$this->adminBooking] = array();
                }
                if (!isset($_SESSION[$this->adminBooking][$hash]))
                {
                    $_SESSION[$this->adminBooking][$hash] = array();
                }
                
                $start_time = date("H:i:s", $this->_get->toString('start_ts'));
                $key = $date . '~' . $start_time;
                $_SESSION[$this->adminBooking][$hash][$key] = array(
                    'booking_date' => $date,
                    'start_time' => $start_time,
                    'end_time' => date("H:i:s", $end_ts),
                    'start_ts' => $start_ts,
                    'end_ts' => $end_ts,
                    'price' => $price,
                    'cnt' => 1
                );
                $add_slot = true;
            }
            $this->set('hash', $hash);
            $this->set('add_slot', $add_slot);
            
            $this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));
            
            $client_arr = pjClientModel::factory()
            ->select("t1.*, t2.email as customer_email, t2.name as customer_name, t2.phone as customer_phone")
            ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
            ->where('t2.status', 'T')
            ->orderBy('t2.name ASC')
            ->findAll()
            ->getData();
            $this->set('client_arr', $client_arr);
            
            $country_arr = pjBaseCountryModel::factory()
            ->select('t1.id, t2.content AS name')
            ->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
            ->where('t1.status', 'T')
            ->orderBy('`name` ASC')->findAll()->getData();
            $this->set('country_arr', $country_arr);
            
            if(pjObject::getPlugin('pjPayments') !== NULL)
            {
                $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->getCalendarId()));
                $this->set('payment_titles', pjPayments::getPaymentTitles($this->getCalendarId(), $this->getLocaleId()));
            }else{
                $this->set('payment_titles', __('payment_methods', true));
            }
            
            $this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
            $this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
            $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
            $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
            $this->appendJs('pjAdminBookings.js');
        }
    }
    
    public function pjActionDeleteBooking()
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
            $b_arr = pjBookingModel::factory()->find($id)->getData();
            if (pjBookingModel::factory()->set('id', $id)->erase()->getAffectedRows() == 1)
            {
                pjBookingSlotModel::factory()->where('booking_id', $id)->eraseAll();
                self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Booking has been deleted.'));
            }
            self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Booking has not been deleted.'));
        }
        
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
    }
    
    public function pjActionDeleteBookingBulk()
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
        if(!empty($record))
        {
            pjBookingModel::factory()->whereIn("id", $record)->eraseAll();
            pjBookingSlotModel::factory()->whereIn('booking_id', $record)->eraseAll();
            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Bookings have been deleted.'));
        }
        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing, empty or invalid parameters.'));
    }
    
    public function pjActionItemAdd()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if($this->_post->toInt('item_add') == 1)
            {
                $post = $this->_post->raw();
                $timeslotPost = array_filter($post['timeslot']);
                if (isset($post['timeslot']) && !empty($post['timeslot']))
                {
                    $pjBookingSlotModel = pjBookingSlotModel::factory();
                    $pjBsModel = pjBookingSlotModel::factory();
                    $date = pjDateTime::formatDate($post['date'], $this->option_arr['o_date_format']);
                    
                    $option_arr = pjOptionModel::factory()->getPairs($post['calendar_id']);
                    
                    if (isset($post['booking_id']) && (int) $post['booking_id'] > 0)
                    {
                        $pjBookingSlotModel->setBatchFields(array(
                            'booking_id', 'booking_date', 'start_time',
                            'end_time', 'start_ts', 'end_ts', 'price', 'cnt'
                        ));
                        $selected_slots = 0;
                        $duplicated = false;
                        foreach ($timeslotPost as $key => $cnt)
                        {
                            list($start_ts, $end_ts) = explode('|', $key);
                            $cntSlots = $pjBsModel
                            ->reset()
                            ->where('t1.booking_id', $post['booking_id'])
                            ->where('t1.booking_date', $date)
                            ->where('t1.start_time', date("H:i:s", $start_ts))
                            ->where('t1.end_time', date("H:i:s", $end_ts))
                            ->where('t1.start_ts', $start_ts)
                            ->where('t1.end_ts', $end_ts)
                            ->findCount()
                            ->getData();
                            if($cntSlots > 0)
                            {
                                $duplicated = true;
                                break;
                            }
                            $pjBookingSlotModel->addBatchRow(array(
                                $post['booking_id'],
                                $date,
                                date("H:i:s", $start_ts),
                                date("H:i:s", $end_ts),
                                $start_ts,
                                $end_ts,
                                $post['price'][$start_ts],
                                $cnt
                            ));
                            $selected_slots += $cnt;
                        }
                        if($duplicated == false)
                        {
                            if((int) $option_arr['o_max_slots_per_booking'] > 0)
                            {
                                $bs_arr = $pjBsModel->reset()->where('t1.booking_id', $post['booking_id'])->findAll()->getData();
                                foreach($bs_arr as $item)
                                {
                                    $selected_slots += $item['cnt'];
                                }
                                if($selected_slots > (int) $option_arr['o_max_slots_per_booking'])
                                {
                                    $text = sprintf( __('add_item_msg_ARRAY_102', true, false), $option_arr['o_max_slots_per_booking']);
                                    self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
                                }else{
                                    $pjBookingSlotModel->insertBatch();
                                }
                            }else{
                                $pjBookingSlotModel->insertBatch();
                            }
                        }else{
                            self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => __('add_item_msg_ARRAY_101', true, false)));
                        }
                    } elseif (isset($post['hash']) && !empty($post['hash'])) {
                        
                        if (!isset($_SESSION[$this->adminBooking]))
                        {
                            $_SESSION[$this->adminBooking] = array();
                        }
                        if (!isset($_SESSION[$this->adminBooking][$post['hash']]))
                        {
                            $_SESSION[$this->adminBooking][$post['hash']] = array();
                        }
                        $selected_slots = 0;
                        foreach ($timeslotPost as $key => $cnt)
                        {
                            $selected_slots += $cnt;
                        }
                        foreach ($_SESSION[$this->adminBooking][$post['hash']] as $key => $slot)
                        {
                            $selected_slots += $slot['cnt'];
                        }
                        if((int) $option_arr['o_max_slots_per_booking'] > 0)
                        {
                            if($selected_slots > (int) $option_arr['o_max_slots_per_booking'])
                            {
                                $text = sprintf( __('add_item_msg_ARRAY_102', true, false), $option_arr['o_max_slots_per_booking']);
                                self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => $text));
                            }
                        }
                        foreach ($timeslotPost as $key => $cnt)
                        {
                            list($start_ts, $end_ts) = explode('|', $key);
                            $start_time = date("H:i:s", $start_ts);
                            $key = $date . '~' . $start_time;
                            $_SESSION[$this->adminBooking][$post['hash']][$key] = array(
                                'booking_date' => $date,
                                'start_time' => $start_time,
                                'end_time' => date("H:i:s", $end_ts),
                                'start_ts' => $start_ts,
                                'end_ts' => $end_ts,
                                'price' => $post['price'][$start_ts],
                                'cnt' => $cnt
                            );
                        }
                    }
                    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => __('add_item_msg_ARRAY_200', true, false)));
                }
                self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('add_item_msg_ARRAY_100', true, false)));
            }
        }
    }
    
    public function pjActionItemDelete()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if ($this->_post->toInt('id') > 0)
            {
                $pjBookingSlotModel = pjBookingSlotModel::factory();
                $arr = $pjBookingSlotModel->find($this->_post->toInt('id'))->getData();
                if (empty($arr))
                {
                    pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Slot not found.'));
                }
                if (1 == $pjBookingSlotModel->set('id', $this->_post->toInt('id'))->erase()->getAffectedRows())
                {
                    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Slot has been deleted.'));
                }
                pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Slot has not been deleted.'));
            } elseif ($this->_post->check('hash') && !$this->_post->isEmpty('hash') && $this->_post->check('key') && !$this->_post->isEmpty('key')) {
                if (isset($_SESSION[$this->adminBooking]) &&
                    isset($_SESSION[$this->adminBooking][$this->_post->toString('hash')]) &&
                    isset($_SESSION[$this->adminBooking][$this->_post->toString('hash')][$this->_post->toString('key')])
                    )
                {
                    $_SESSION[$this->adminBooking][$this->_post->toString('hash')][$this->_post->toString('key')] = NULL;
                    unset($_SESSION[$this->adminBooking][$this->_post->toString('hash')][$this->_post->toString('key')]);
                    pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Slot has been deleted.'));
                } else {
                    pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Slot not found.'));
                }
            }
            pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => 'Missing parameters.'));
        }
        pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
        exit;
    }
    
    public function pjActionGetSlots()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if ($this->_get->check('date') && !$this->_get->isEmpty('date'))
            {
                $iso_date = pjDateTime::formatDate($this->_get->toString('date'), $this->option_arr['o_date_format']);
                $this->getSlots($this->_get->toInt('calendar_id'), $iso_date, $this->_get->check('hash') ? $this->_get->toString('hash') : NULL);
                
                $_option_arr = pjOptionModel::factory()->getPairs($this->_post->toInt('calendar_id'));
                $this->set('_option_arr', $_option_arr);
            }
        }
    }
    
    public function pjActionItemGet()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if ($this->_get->toInt('id') > 0)
            {
                $bi_arr = pjBookingSlotModel::factory()
                ->select("t1.*")
                ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                ->where('t1.booking_id', $this->_get->toInt('id'))->findAll()->getData();
            } elseif ($this->_get->check('hash') && !$this->_get->isEmpty('hash')) {
                $bi_arr = $_SESSION[$this->adminBooking][$this->_get->toString('hash')];
            }
            $this->set('bi_arr', $bi_arr);
        }
    }
    
    public function pjActionGetPrice()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            
            $price = $discount = $deposit = $tax = $total = 0;
            $has_slot = 0;
            $slotsArray = array();
            if ($this->_post->toInt('id') > 0)
            {
                $slotsArray = pjBookingSlotModel::factory()->where('t1.booking_id', $this->_post->toInt('id'))->findAll()->getData();
            } elseif ($this->_post->check('hash') && !$this->_post->isEmpty('hash') && isset($_SESSION[$this->adminBooking]) && isset($_SESSION[$this->adminBooking][$this->_post->toString('hash')])) {
                $slotsArray = array_filter($_SESSION[$this->adminBooking][$this->_post->toString('hash')]);
            }
            
            $pjVoucherModel = pjVoucherModel::factory();
            foreach ($slotsArray as $dt_iso => $slot) 
            {
                $slot_price = $slot['price'] * $slot['cnt'];
                $price += $slot_price;
                $has_slot = 1;
                if($this->_post->check('discount_code') && !$this->_post->isEmpty('discount_code'))
                {
                    $start_ts = strtotime($dt_iso);
                    $voucher_code = $this->_post->toString('discount_code');
                    $result = $pjVoucherModel->reset()->getVoucher($voucher_code, $slot['booking_date'], $slot['start_time']);
                    if($result['status'] == 'OK')
                    {
                        $_discount = $result['arr']['discount'];
                        if($result['arr']['type'] == 'percent')
                        {
                            $_discount = $slot_price * $result['arr']['discount'] / 100;
                        }
                        if($_discount > $slot_price)
                        {
                            $_discount = $slot_price;
                        }
                        $discount += $_discount;
                    }
                }
            }
            
            $_option_arr = pjOptionModel::factory()->getPairs($this->_post->toInt('calendar_id'));
            
            if ((float) $_option_arr['o_tax'] > 0)
            {
                $tax = (($price - $discount) * (float) $_option_arr['o_tax']) / 100;
            }
            $total = $price - $discount + $tax;
            switch ($_option_arr['o_deposit_type'])
            {
                case 'percent':
                    $deposit = ($total * (float) $_option_arr['o_deposit']) / 100;
                    break;
                case 'amount':
                    $deposit = (float) $_option_arr['o_deposit'];
                    break;
            }
            $data = compact('price', 'discount', 'deposit', 'tax', 'total', 'has_slot');
            $data = array_map('floatval', $data);
            $data['total_format'] = pjCurrency::formatPrice($total);
            pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Price has been updated', 'data' => $data));
        }
        exit;
    }
    
    public function pjActionUpdate()
    {
        $this->checkLogin();
        if (!pjAuth::factory()->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
        if (self::isPost() && $this->_post->check('booking_update'))
        {
            $pjBookingSlotModel = pjBookingSlotModel::factory();
            $post = $this->_post->raw();
            
            $option_arr = pjOptionModel::factory()->getPairs($post['calendar_id']);
            
            $pjBookingModel = pjBookingModel::factory();
            $required = array('booking_status', 'booking_price', 'booking_tax', 'booking_total', 'booking_deposit');
            $isPaymentDisabled = (int) $option_arr['o_disable_payments'] === 1;
            if (!$isPaymentDisabled)
            {
                $required[] = 'payment_method';
            }
            if (!$pjBookingModel->validateRequest($required, $post))
            {
                pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK15");
            }
            
            $data = array();
            $data['locale_id'] = $this->getLocaleId();
            if ($isPaymentDisabled)
            {
                $data['payment_method'] = ':NULL';
            }
            
            if($this->_post->check('new_client'))
            {
                $c_data = array();
            	if (!$this->_post->isEmpty('customer_name')) {
                	$c_data['customer_name'] = $this->_post->toString('customer_name');	
                }
                if (!$this->_post->isEmpty('customer_email')) {
                	$c_data['customer_email'] = $this->_post->toString('customer_email');
                }
                if (!$this->_post->isEmpty('customer_phone')) {
                	$c_data['customer_phone'] = $this->_post->toString('customer_phone');
                }
            	if (!$this->_post->isEmpty('customer_address_1')) {
                	$c_data['customer_address_1'] = $this->_post->toString('customer_address_1');
                }
                if (!$this->_post->isEmpty('customer_address_2')) {
                	$c_data['customer_address_2'] = $this->_post->toString('customer_address_2');
                }
                if (!$this->_post->isEmpty('customer_city')) {
                	$c_data['customer_city'] = $this->_post->toString('customer_city');
                }
                if (!$this->_post->isEmpty('customer_state')) {
                	$c_data['customer_state'] = $this->_post->toString('customer_state');
                }
                if (!$this->_post->isEmpty('customer_zip')) {
                	$c_data['customer_zip'] = $this->_post->toString('customer_zip');
                }
                if ($this->_post->toInt('customer_country') > 0) {
                	$c_data['customer_country'] = $this->_post->toInt('customer_country');
                }
                if (!$this->_post->isEmpty('customer_notes')) {
                	$c_data['customer_notes'] = $this->_post->toString('customer_notes');
                }
                $client_by_email = pjFrontClient::init(array('email' => $c_data['customer_email']))->getClientByEmail();
                if($client_by_email == false)
                {
                    $c_data['status'] = 'T';
                    $response = pjFrontClient::init($c_data)->createClient();
                    if(isset($response['client_id']) && (int) $response['client_id'] > 0)
                    {
                        $data['client_id'] = $response['client_id'];
                    }
                }else{
                    $foreign_id = $client_by_email['id'];
                    $auth_data = array();
                	if (!$this->_post->isEmpty('customer_name')) {
                    	$auth_data['name'] = $this->_post->toString('customer_name');
                    }
                    if (!$this->_post->isEmpty('customer_email')) {
                    	$auth_data['email'] = $this->_post->toString('customer_email');
                    }
                    if (!$this->_post->isEmpty('customer_phone')) {
                    	$auth_data['phone'] = $this->_post->toString('customer_phone');
                    }
                    if ($auth_data) {
                    	pjAuthUserModel::factory()->set('id', $foreign_id)->modify($auth_data);
                    }
                    $client = pjClientModel::factory()->where('foreign_id', $foreign_id)->findAll()->getDataIndex(0);
                    if ($c_data) {
                    	pjClientModel::factory()->set('id', $client['id'])->modify($c_data);
                    }
                    $data['client_id'] = $client['id'];
                }
            }else{
                $client = pjClientModel::factory()->find($post['client_id'])->getData();
                unset($client['id']);unset($client['foreign_id']);unset($client['calendar_id']);
                $data = array_merge($post, $client);
            }
            
            $pjBookingModel->reset()->set('id', $post['id'])->modify(array_merge($post, $data));
            pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjAdminBookings&action=pjActionIndex&err=ABK01");
        }
        if(self::isGet() && $this->_get->toInt('id') > 0)
        {
            $pjBookingModel = pjBookingModel::factory();
            $arr = $pjBookingModel->find($this->_get->toInt('id'))->getData();
            if (empty($arr))
            {
                pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=pjActionIndex&err=ABK08");
            }
            
            $this->set('date_format', pjUtil::toBootstrapDate($this->option_arr['o_date_format']));
            
            $client_arr = pjClientModel::factory()
            ->select("t1.*, t2.email as customer_email, t2.name as customer_name, t2.phone as customer_phone")
            ->join("pjAuthUser", "t2.id=t1.foreign_id", 'left outer')
            ->orderBy('t2.name ASC')
            ->findAll()
            ->getData();
            $this->set('client_arr', $client_arr);
            
            $country_arr = pjBaseCountryModel::factory()
            ->select('t1.id, t2.content AS name')
            ->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$this->getLocaleId()."'", 'left outer')
            ->where('t1.status', 'T')
            ->orderBy('`name` ASC')->findAll()->getData();
            $this->set('country_arr', $country_arr);
            
            $bi_arr = pjBookingSlotModel::factory()
            ->where('t1.booking_id', $arr['id'])
            ->findAll()
            ->getData();
            $this->set('bi_arr', $bi_arr);
            $this->set('arr', $arr);
            
            if(pjObject::getPlugin('pjPayments') !== NULL)
            {
                $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($arr['calendar_id']));
                $this->set('payment_titles', pjPayments::getPaymentTitles($arr['calendar_id'], $this->getLocaleId()));
            }else{
                $this->set('payment_titles', __('payment_methods', true));
            }
            
            $this->set('_option_arr', pjOptionModel::factory()->getPairs($arr['calendar_id']));
            
            $this->appendJs('tinymce.min.js', PJ_THIRD_PARTY_PATH . 'tinymce/');
            $this->appendCss('css/select2.min.css', PJ_THIRD_PARTY_PATH . 'select2/');
            $this->appendJs('js/select2.full.min.js', PJ_THIRD_PARTY_PATH . 'select2/');
            $this->appendCss('datepicker3.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
            $this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
            $this->appendJs('pjAdminBookings.js');
        }
    }
    
    public function pjActionCheckOverlapping()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if($this->_post->check('booking_update'))
            {
                $id = $this->_post->toInt('id');
                $booking = pjBookingModel::factory()->find($id)->getData();
                $pjBookingSlotModel = pjBookingSlotModel::factory();
                $bi_arr = $pjBookingSlotModel
                ->where('t1.booking_id', $id)
                ->findAll()
                ->getData();
                $calendar_id = $this->_post->toInt('calendar_id') > 0 ? $this->_post->toInt('calendar_id') : $this->getCalendarId();
                $option_arr = pjOptionModel::factory()->getPairs($calendar_id);
                foreach($bi_arr as $bi)
                {
                    $t_arr = pjAppController::getDailySlots($calendar_id, $bi['booking_date'], $option_arr);
                    $bs_arr = $pjBookingSlotModel->reset()
                    	->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                        ->where('t2.calendar_id', $calendar_id)
                        ->where('t2.booking_status !=', 'cancelled')
                        ->where("t1.booking_id <>", $id)
                        ->where('t1.booking_date', $bi['booking_date'])
                        ->where('t1.start_time', $bi['start_time'])
                        ->where('t1.end_time', $bi['end_time'])
                        ->where('t1.start_ts', $bi['start_ts'])
                        ->where('t1.end_ts', $bi['end_ts'])
                    	->findAll()->getData();
                    $booked = 0;
                    foreach ($bs_arr as $bs) {
                    	$booked += $bs['cnt'];                    	
                    }
                    $availableSlotsNumber = (int)$t_arr['slot_limit'] - $booked;
                	if($t_arr['people'] < $availableSlotsNumber)
    				{
    				    $availableSlotsNumber = $t_arr['people'];
    				}
                    if($bi['cnt'] > $availableSlotsNumber && $this->_post->toString('booking_status') != 'cancelled')
                    {
                        self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('lblOverlappingBookingMsg', true)));
                    }
                }
            }
            if($this->_post->check('booking_create'))
            {
                $post = $this->_post->raw();
                if (isset($_SESSION[$this->adminBooking]) && isset($_SESSION[$this->adminBooking][$post['hash']]) && !empty($_SESSION[$this->adminBooking][$post['hash']]))
                {
                    $option_arr = pjOptionModel::factory()->getPairs($post['calendar_id']);
                    $pjBookingSlotModel = pjBookingSlotModel::factory();
                    foreach ($_SESSION[$this->adminBooking][$post['hash']] as $key => $slot)
                    {
                        $t_arr = pjAppController::getDailySlots($post['calendar_id'], $slot['booking_date'], $option_arr);
                        $bs_arr = $pjBookingSlotModel
                        ->reset()
                        ->join('pjBooking', 't2.id=t1.booking_id', 'inner')
                        ->where('t2.calendar_id', $post['calendar_id'])
                        ->where('t2.booking_status !=', 'cancelled')
                        ->where('t1.booking_date', $slot['booking_date'])
                        ->where('t1.start_time', $slot['start_time'])
                        ->where('t1.end_time', $slot['end_time'])
                        ->where('t1.start_ts', $slot['start_ts'])
                        ->where('t1.end_ts', $slot['end_ts'])
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
                        if($slot['cnt'] > $availableSlotsNumber)
                        {
                            self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('lblOverlappingBookingMsg', true)));
                        }
                    }
                }
            }
            self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
        }
        exit;
    }
    
    public function pjActionReminderEmail()
    {
        $this->setAjax(true);
        
        if ($this->isXHR())
        {
            if (self::isPost())
            {
                if($this->_post->toInt('send_email') && $this->_post->toString('to') && $this->_post->toString('subject') && $this->_post->toString('message') && $this->_post->toInt('id'))
                {
                    $Email = self::getMailer($this->option_arr);
                    $r = $Email
                    ->setTo($this->_post->toString('to'))
                    ->setSubject($this->_post->toString('subject'))
                    ->send($this->_post->toString('message'));
                    if (isset($r) && $r)
                    {
                        pjAppController::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => ''));
                    }
                    pjAppController::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => ''));
                }
            }
            if (self::isGet() && $this->_get->toInt('id') > 0)
            {
                $id = $this->_get->toInt('id');
                $booking_arr = pjBookingModel::factory()
                ->select('t1.*, t1.id AS `booking_id`, t2.content AS `country_name`,
    						t3.content AS `reminder_subject_client`, t4.content AS `reminder_tokens_client`,
    						t6.email AS `admin_email`, t7.content AS `calendar_name`, t9.name AS customer_name, t9.email AS customer_email, t9.phone AS customer_phone')
    			->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
    			->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='reminder_subject_client'", 'left outer')
    			->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='reminder_tokens_client'", 'left outer')
    			->join('pjCalendar', 't5.id=t1.calendar_id', 'left outer')
    			->join('pjAuthUser', 't6.id=t5.user_id', 'left outer')
    			->join('pjMultiLang', "t7.model='pjCalendar' AND t7.foreign_id=t1.calendar_id AND t7.locale=t1.locale_id AND t7.field='title'", 'left outer')
    			->join('pjClient', "t1.client_id=t8.id", 'left')
    			->join('pjAuthUser', "t8.foreign_id=t9.id", 'left')
    			->find($id)
    			->getData();
                
    			if (!empty($booking_arr))
    			{
    			    $option_arr = pjOptionModel::factory()->getPairs($booking_arr['calendar_id']);
    			    $booking_arr['bs_arr'] = pjBookingSlotModel::factory()->where('t1.booking_id', $id)->findAll()->getData();
    			    
    			    $option_arr = array_merge($this->option_arr, $option_arr);
    			    $tokens = pjAppController::getTokens($booking_arr, $option_arr);
    			    
    			    $subject_client = str_replace($tokens['search'], $tokens['replace'], $booking_arr['reminder_subject_client']);
    			    $message_client = str_replace($tokens['search'], $tokens['replace'], $booking_arr['reminder_tokens_client']);
    			    $from = !empty($booking_arr['admin_email']) ? $booking_arr['admin_email'] : $booking_arr['customer_email'];
    			    $this->set('arr', array(
    			        'id' => $id,
    			        'to' => $booking_arr['customer_email'],
    			        'client_name' => $booking_arr['customer_name'],
    			        'from' => $from,
    			        'message' => $message_client,
    			        'subject' => $subject_client
    			    ));
    			}
            }
        }
    }
    
    public function pjActionReminderSms()
    {
        $this->setAjax(true);
        
        if ($this->isXHR() && $this->isLoged())
        {
            if(self::isPost() && $this->_post->toInt('send_sms') > 0 && $this->_post->toString('to') && $this->_post->toString('message') && $this->_post->toInt('id') > 0)
            {
                $params = array(
                    'text' => $_POST['message'],
                    'key' => md5($this->option_arr['private_key'] . PJ_SALT),
                    'type' => 'unicode'
                );
                
                $params = array(
                    'text' => $this->_post->toString('message'),
                    'type' => 'unicode',
                    'key' => md5($this->option_arr['private_key'] . PJ_SALT),
                );
                $params['number'] = $this->_post->toString('to');
                pjBaseSms::init($params)->pjActionSend();
                
                self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'SMS has been sent.'));
            }
            
            if (self::isGet() && $this->_get->toInt('id') > 0)
            {
                if(!empty($this->option_arr['plugin_sms_api_key']))
                {
                    $id = $this->_get->toInt('id');
                    $booking_arr = pjBookingModel::factory()
                    ->select('t1.*, t3.email AS `admin_email`, t4.content AS `country_name`, t5.content AS `reminder_sms_client`, t6.content AS `calendar_name`, t8.name AS customer_name, t8.email AS customer_email, t8.phone AS customer_phone')
                    ->join('pjCalendar', 't2.id=t1.calendar_id', 'inner')
                    ->join('pjAuthUser', 't3.id=t2.user_id', 'left outer')
                    ->join('pjMultiLang', "t4.model='pjBaseCountry' AND t4.foreign_id=t1.customer_country AND t4.locale=t1.locale_id AND t4.field='name'", 'left outer')
                    ->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t2.id AND t5.locale=t1.locale_id AND t5.field='reminder_sms_client'", 'left outer')
                    ->join('pjMultiLang', "t6.model='pjCalendar' AND t6.foreign_id=t1.calendar_id AND t6.locale=t1.locale_id AND t6.field='title'", 'left outer')
                    ->join('pjClient', "t1.client_id=t7.id", 'left')
                    ->join('pjAuthUser', "t7.foreign_id=t8.id", 'left')
                    ->find($id)
                    ->getData();
                    
                    if (!empty($booking_arr))
                    {
                        $booking_arr['bs_arr'] = pjBookingSlotModel::factory()->where('t1.booking_id', $id)->findAll()->getData();
                        
                        $option_arr = pjOptionModel::factory()->getPairs($booking_arr['calendar_id']);
                        $option_arr = array_merge($this->option_arr, $option_arr);
                        $tokens = pjAppController::getTokens($booking_arr, $option_arr);
                        
                        $tokens = pjAppController::getTokens($booking_arr, $option_arr);
                        
                        $message_client = str_replace($tokens['search'], $tokens['replace'], $booking_arr['reminder_sms_client']);
                        
                        $this->set('arr', array(
                            'id' => $id,
                            'client_phone' => pjUtil::formatPhone($booking_arr['customer_phone']),
                            'client_name' => pjSanitize::html($booking_arr['customer_name']),
                            'message' => $message_client
                        ));
                    }
                }else{
                    self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => __('lblSMSAPIKeyNotSet', true)));
                }
            }
        }
    }
    
    public function pjActionExportICal()
    {
        $this->checkLogin();
        if (!pjAuth::factory()->hasAccess())
        {
            $this->sendForbidden();
            return;
        }
        if(self::isPost() && $this->_post->check('booking_export') && $this->_post->toInt('id') > 0)
        {
            $arr = pjBookingModel::factory()
            ->select('t1.*, t2.*, t4.name AS customer_name, t4.email AS customer_email, t4.phone AS customer_phone, t2.id as bs_id')
            ->join('pjBookingSlot', 't2.booking_id=t1.id', 'left outer')
            ->join('pjClient', "t1.client_id=t3.id", 'left')
            ->join('pjAuthUser', "t3.foreign_id=t4.id", 'left')
            ->where('t1.id', $this->_post->toInt('id'))
            ->findAll()
            ->getData();
            foreach($arr as $k => $v)
            {
                $v['date_from'] = $v['booking_date'] . ' ' . date('H:i:s', $v['start_ts']);
                $v['date_to'] = $v['booking_date'] . ' ' . date('H:i:s', $v['end_ts']);
                $_arr = array();
                if (!empty($v['customer_name'])) {
                	$_arr[] = 'Name: ' . pjSanitize::html($v['customer_name']);
                }
                if(!empty($v['customer_email']))
                {
                    $_arr[] = 'Email: ' . pjSanitize::html($v['customer_email']);
                }
                if(!empty($v['customer_phone']))
                {
                    $_arr[] = 'Phone: ' . pjSanitize::html($v['customer_phone']);
                }
                if(!empty($v['booking_total']))
                {
                    $_arr[] = 'Price: ' . pjSanitize::html($v['booking_total']);
                }
                if(!empty($v['customer_notes']))
                {
                    $_arr[] = 'Notes: ' . pjSanitize::html(preg_replace('/\n|\r|\r\n/', ' ', $v['customer_notes']));
                }
                $_arr[] = 'Status: ' . pjSanitize::html($v['booking_status']);
                
                $v['desc'] = join("\r\n\t; ", $_arr);
                $v['location'] = '';
                $v['summary'] = 'Booking';
                $v['uuid'] = $v['uuid'] . '-' . $v['bs_id'];
                $arr[$k] = $v;
            }
            
            $ical = new pjICal();
            $ical
            ->setName("Export-".time().".ics")
            ->setProdID('Time Slots Booking Calendar')
            ->setSummary('summary')
            ->setCName('desc')
            ->setLocation('location')
            ->setVersion("VERSION:2.0")
            ->setTimezone("UTC/GMT")
            ->process($arr)
            ->download();
        }
        exit;
    }
    public function pjActionGetPMs()
    {
        $this->setAjax(true);
        
        if ($this->isXHR() && $this->isLoged())
        {
            $this->set('_option_arr', pjOptionModel::factory()->getPairs($this->_get->toInt('calendar_id')));
            if(pjObject::getPlugin('pjPayments') !== NULL)
            {
                $this->set('payment_option_arr', pjPaymentOptionModel::factory()->getOptions($this->_get->toInt('calendar_id')));
                $this->set('payment_titles', pjPayments::getPaymentTitles($this->_get->toInt('calendar_id'), $this->getLocaleId()));
            }else{
                $this->set('payment_titles', __('payment_methods', true));
            }
        }
    }
    
    public function pjActionGetBookingFields()
    {
        $this->setAjax(true);
        
        if ($this->isXHR() && $this->isLoged())
        {
        	if (isset($_SESSION[$this->adminBooking])) {
        		unset($_SESSION[$this->adminBooking]);
        	}
        	if ($this->_get->check('booking_id')) {
        		pjBookingSlotModel::factory()->where('booking_id', $this->_get->toInt('booking_id'))->eraseAll();
        	}
            $option_arr = pjOptionModel::factory()->getPairs($this->_get->toInt('calendar_id'));
            self::jsonResponse($option_arr);
        }
    }
}
?>