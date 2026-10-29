<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjVouchers extends pjVouchersAppController
{
	public function pjActionCheckCode()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			if (!$this->_get->toString('code'))
			{
				echo 'false';
				exit;
			}
			$pjVoucherModel = pjVoucherModel::factory()->where('t1.code', $this->_get->toString('code'));
			if ($this->_get->toInt('id'))
			{
				$pjVoucherModel->where('t1.id !=', $this->_get->toInt('id'));
			}
			echo $pjVoucherModel->findCount()->getData() == 0 ? 'true' : 'false';
		}
		exit;
	}

	public function pjActionCheckDate()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
			{
				$dt_from = strtotime(pjDateTime::formatDate($this->_post->toString('p_date_from'), $this->option_arr['o_date_format']) . ' ' .$this->_post->toString('p_hour_from') . ':' . $this->_post->toString('p_minute_from') . ' ' . strtoupper($this->_post->toString('p_ampm_from')));
				$dt_to = strtotime(pjDateTime::formatDate($this->_post->toString('p_date_to'), $this->option_arr['o_date_format']) . ' ' .$this->_post->toString('p_hour_to') . ':' . $this->_post->toString('p_minute_to') . ' ' . strtoupper($this->_post->toString('p_ampm_to')));
			}else{
				$dt_from = sprintf("%s %s:%s:00", pjDateTime::formatDate($this->_post->toString('p_date_from'), $this->option_arr['o_date_format']), $this->_post->toString('p_hour_from'), $this->_post->toString('p_minute_from'));
				$dt_to = sprintf("%s %s:%s:00", pjDateTime::formatDate($this->_post->toString('p_date_to'), $this->option_arr['o_date_format']), $this->_post->toString('p_hour_to'), $this->_post->toString('p_minute_to'));
				$dt_from = strtotime($dt_from);
				$dt_to = strtotime($dt_to);
			}
			echo $dt_to > $dt_from ? 'true' : 'false';
		}
		exit;
	}

	public function pjActionCreate()
	{
		if (!pjAuth::factory()->hasAccess())
		{
			$this->sendForbidden();
			return;
		}

		if ($this->_post->check('voucher_create'))
		{
			$data = array();

			$data['code'] = $this->_post->toString('code');
			$data['discount'] = $this->_post->toString('discount');
			$data['type'] = $this->_post->toString('type');
			$data['valid'] = $this->_post->toString('valid');
			switch ($data['valid'])
			{
				case 'fixed':
					$data['date_from'] = pjDateTime::formatDate($this->_post->toString('f_date'), $this->option_arr['o_date_format']);
					$data['date_to'] = $data['date_from'];
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime($data['date_from'] . ' ' .$this->_post->toString('f_hour_from') . ':' . $this->_post->toString('f_minute_from') . ' ' . strtoupper($this->_post->toString('f_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime($data['date_to'] . ' ' .$this->_post->toString('f_hour_to') . ':' . $this->_post->toString('f_minute_to') . ' ' . strtoupper($this->_post->toString('f_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('f_hour_from') . ":" . $this->_post->toString('f_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('f_hour_to') . ":" . $this->_post->toString('f_minute_to') . ":00";
					}
					break;
				case 'period':
					$data['date_from'] = pjDateTime::formatDate($this->_post->toString('p_date_from'), $this->option_arr['o_date_format']);
					$data['date_to'] = pjDateTime::formatDate($this->_post->toString('p_date_to'), $this->option_arr['o_date_format']);
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime($data['date_from'] . ' ' .$this->_post->toString('p_hour_from') . ':' . $this->_post->toString('p_minute_from') . ' ' . strtoupper($this->_post->toString('p_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime($data['date_to'] . ' ' .$this->_post->toString('p_hour_to') . ':' . $this->_post->toString('p_minute_to') . ' ' . strtoupper($this->_post->toString('p_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('p_hour_from') . ":" . $this->_post->toString('p_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('p_hour_to') . ":" . $this->_post->toString('p_minute_to') . ":00";
					}
					break;
				case 'recurring':
					$data['every'] = $this->_post->toString('r_every');
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime(date('Y-m-d') . ' ' .$this->_post->toString('r_hour_from') . ':' . $this->_post->toString('r_minute_from') . ' ' . strtoupper($this->_post->toString('r_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime(date('Y-m-d') . ' ' .$this->_post->toString('r_hour_to') . ':' . $this->_post->toString('r_minute_to') . ' ' . strtoupper($this->_post->toString('r_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('r_hour_from') . ":" . $this->_post->toString('r_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('r_hour_to') . ":" . $this->_post->toString('r_minute_to') . ":00";
					}
					break;
			}
			$id = pjVoucherModel::factory($data)->insert()->getInsertId();
			if ($id !== false && (int) $id > 0)
			{
				$err = 'AV03';
			} else {
				$err = 'AV04';
			}
			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjVouchers&action=pjActionIndex&err=$err");
		} else {

		    $this->appendJs('moment-with-locales.min.js', PJ_THIRD_PARTY_PATH . 'moment/');
			$this->appendCss('datepicker.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjVouchers.js', $this->getConst('PLUGIN_JS_PATH'));
		}
	}

	public function pjActionDeleteVoucher()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			if (!pjAuth::factory()->hasAccess())
			{
				self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
			}

			if (pjVoucherModel::factory()->setAttributes(array('id' => $this->_get->toInt('id')))->erase()->getAffectedRows() == 1)
			{
				self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Voucher has been deleted.'));
			} else {
				self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Voucher has not been deleted.'));
			}
		}
		exit;
	}

	public function pjActionDeleteVoucherBulk()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			if (!pjAuth::factory()->hasAccess())
			{
				self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Access denied.'));
			}

			$record = $this->_post->toArray('record');
			if (count($record))
			{
				pjVoucherModel::factory()->whereIn('id', $record)->eraseAll();

				self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Voucher(s) has been deleted.'));
			}

			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Reservation(s) has not been deleted.'));
		}
		exit;
	}

	public function pjActionExportVoucher()
	{
		$this->checkLogin();

		if (!pjAuth::factory()->hasAccess())
		{
			$this->sendForbidden();
			return;
		}

		$record = $this->_post->toArray('record');
		if (count($record))
		{
			$arr = pjVoucherModel::factory()->whereIn('id', $record)->findAll()->getData();
			foreach ($arr as &$item)
			{
				if (!empty($item['date_from']))
				{
					$item['date_from'] = pjDateTime::formatDate($item['date_from'], 'Y-m-d', $this->option_arr['o_date_format']);
				}
				if (!empty($item['date_to']))
				{
					$item['date_to'] = pjDateTime::formatDate($item['date_to'], 'Y-m-d', $this->option_arr['o_date_format']);
				}
				if (!empty($item['time_from']))
				{
					$item['time_from'] = pjDateTime::formatTime($item['time_from'], 'H:i:s', $this->option_arr['o_time_format']);
				}
				if (!empty($item['time_to']))
				{
					$item['time_to'] = pjDateTime::formatTime($item['time_to'], 'H:i:s', $this->option_arr['o_time_format']);
				}
			}
			$csv = new pjCSV();
			$csv
				->setHeader(true)
				->setName("Vouchers-".time().".csv")
				->process($arr)
				->download();
		}
		exit;
	}

	public function pjActionGetVoucher()
	{
		$this->setAjax(true);

		if ($this->isXHR())
		{
			$pjVoucherModel = pjVoucherModel::factory();

			if ($q = $this->_get->toString('q'))
			{
				$q = str_replace(array('%', '_'), array('\%', '\_'), trim($q));
				$pjVoucherModel->where('t1.code LIKE', "%$q%");
			}

			$column = 'code';
			$direction = 'ASC';
			if ($this->_get->check('column') && in_array(strtoupper($this->_get->toString('direction')), array('ASC', 'DESC')))
			{
				$column = $this->_get->toString('column');
				$direction = strtoupper($this->_get->toString('direction'));
			}

			$total = $pjVoucherModel->findCount()->getData();
			$rowCount = $this->_get->toInt('rowCount') ?: 10;
			$pages = ceil($total / $rowCount);
			$page = $this->_get->toInt('page') ?: 1;
			$offset = ((int) $page - 1) * $rowCount;
			if ($page > $pages)
			{
				$page = $pages;
			}

			$data = $pjVoucherModel
				->select('t1.id, t1.code, t1.discount, t1.type, t1.valid')
				->orderBy("$column $direction")->limit($rowCount, $offset)->findAll()->getData();
			
			$voucher_types = __('plugin_vouchers_types', true);
			$vouchers_valids = __('plugin_vouchers_valids', true, false);
			foreach($data as $k => $v)
			{
			    $data[$k]['type'] = $voucher_types[$v['type']];
			    $data[$k]['valid'] = $vouchers_valids[$v['valid']];
			}
			$data = pjSanitize::clean($data);

			pjAppController::jsonResponse(compact('data', 'total', 'pages', 'page', 'rowCount', 'column', 'direction'));
		}
		exit;
	}

	public function pjActionIndex()
	{
		if (!pjAuth::factory($this->_get->toString('controller'))->hasAccess())
		{
			$this->sendForbidden();
			return;
		}
		$this->set('has_export', pjAuth::factory('pjVouchers', 'pjActionExportVoucher')->hasAccess());
		
		$this->appendJs('jquery.datagrid.js', PJ_FRAMEWORK_LIBS_PATH . 'pj/js/');
		$this->appendJs('pjVouchers.js', $this->getConst('PLUGIN_JS_PATH'));
		$this->appendJs('index.php?controller=pjBase&action=pjActionMessages', PJ_INSTALL_URL, true);
	}

	public function pjActionSaveVoucher()
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
		
		if (!pjAuth::factory($this->_get->toString('controller'), 'pjActionUpdate')->hasAccess())
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 102, 'text' => 'Access denied.'));
		}
		
		$arr = pjVoucherModel::factory()->find($this->_get->toInt('id'))->getData();
		if (!$arr)
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 103, 'text' => 'Voucher not found.'));
		}
		
		if ($this->_post->toString('column') == 'discount' && $arr['type'] == 'percent' && $this->_post->toFloat('value') > 100)
		{
			self::jsonResponse(array('status' => 'ERR', 'code' => 104, 'text' => 'Discount can not exceed 100%.'));
		}

		pjVoucherModel::factory()->set('id', $this->_get->toInt('id'))->modify(array($this->_post->toString('column') => $this->_post->toString('value')));

		self::jsonResponse(array('status' => 'OK', 'code' => 201, 'text' => 'Voucher has been updated.'));
	}

	public function pjActionUpdate()
	{
		if (!pjAuth::factory()->hasAccess())
		{
			$this->sendForbidden();
			return;
		}

		if ($this->_post->check('voucher_update'))
		{
			$data = array();
			$data['code'] = $this->_post->toString('code');
			$data['discount'] = $this->_post->toString('discount');
			$data['type'] = $this->_post->toString('type');
			$data['valid'] = $this->_post->toString('valid');
			switch ($data['valid'])
			{
				case 'fixed':
					$data['date_from'] = pjDateTime::formatDate($this->_post->toString('f_date'), $this->option_arr['o_date_format']);
					$data['date_to'] = $data['date_from'];
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime($data['date_from'] . ' ' .$this->_post->toString('f_hour_from') . ':' . $this->_post->toString('f_minute_from') . ' ' . strtoupper($this->_post->toString('f_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime($data['date_to'] . ' ' .$this->_post->toString('f_hour_to') . ':' . $this->_post->toString('f_minute_to') . ' ' . strtoupper($this->_post->toString('f_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('f_hour_from') . ":" . $this->_post->toString('f_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('f_hour_to') . ":" . $this->_post->toString('f_minute_to') . ":00";
					}
					$data['every'] = array('NULL');
					break;
				case 'period':
					$data['date_from'] = pjDateTime::formatDate($this->_post->toString('p_date_from'), $this->option_arr['o_date_format']);
					$data['date_to'] = pjDateTime::formatDate($this->_post->toString('p_date_to'), $this->option_arr['o_date_format']);
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime($data['date_from'] . ' ' .$this->_post->toString('p_hour_from') . ':' . $this->_post->toString('p_minute_from') . ' ' . strtoupper($this->_post->toString('p_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime($data['date_to'] . ' ' .$this->_post->toString('p_hour_to') . ':' . $this->_post->toString('p_minute_to') . ' ' . strtoupper($this->_post->toString('p_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('p_hour_from') . ":" . $this->_post->toString('p_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('p_hour_to') . ":" . $this->_post->toString('p_minute_to') . ":00";
					}
					$data['every'] = array('NULL');
					break;
				case 'recurring':
					$data['date_from'] = array('NULL');
					$data['date_to'] = array('NULL');
					$data['every'] = $this->_post->toString('r_every');
					if(strpos($this->option_arr['o_time_format'], 'a') > -1 || strpos($this->option_arr['o_time_format'], 'A') > -1)
					{
						$data['time_from'] = date('H:i:s', strtotime(date('Y-m-d') . ' ' .$this->_post->toString('r_hour_from') . ':' . $this->_post->toString('r_minute_from') . ' ' . strtoupper($this->_post->toString('r_ampm_from'))));
						$data['time_to'] =  date('H:i:s', strtotime(date('Y-m-d') . ' ' .$this->_post->toString('r_hour_to') . ':' . $this->_post->toString('r_minute_to') . ' ' . strtoupper($this->_post->toString('r_ampm_to'))));
					}else{
						$data['time_from'] = $this->_post->toString('r_hour_from') . ":" . $this->_post->toString('r_minute_from') . ":00";
						$data['time_to'] = $this->_post->toString('r_hour_to') . ":" . $this->_post->toString('r_minute_to') . ":00";
					}
					break;
			}

			pjVoucherModel::factory()->where('id', $this->_post->toInt('id'))->limit(1)->modifyAll($data);

			pjUtil::redirect(PJ_INSTALL_URL . "index.php?controller=pjVouchers&action=pjActionIndex&err=AV01");

		} else {
			$arr = pjVoucherModel::factory()->find($this->_get->toInt('id'))->getData();
			if (count($arr) === 0)
			{
				pjUtil::redirect(PJ_INSTALL_URL. "index.php?controller=pjVouchers&action=pjActionIndex&err=AV08");
			}
			$this->set('arr', $arr);

			$this->appendJs('moment-with-locales.min.js', PJ_THIRD_PARTY_PATH . 'moment/');
			$this->appendCss('datepicker.css', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('bootstrap-datepicker.js', PJ_THIRD_PARTY_PATH . 'bootstrap_datepicker/');
			$this->appendJs('pjVouchers.js', $this->getConst('PLUGIN_JS_PATH'));
		}
	}
}
?>