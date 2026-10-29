<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontCart extends pjFront
{
	public function pjActionAdd()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
		    if ($this->_get->toInt('cid') > 0 && $this->_post->check('date') && $this->_post->check('start_ts') && $this->_post->check('end_ts') &&
		        preg_match('/^\d{10}$/', $this->_post->toString('start_ts')) && preg_match('/^\d{10}$/', $this->_post->toString('end_ts')) &&
		        pjValidation::pjActionDate($this->_post->toString('date'))
			)
			{
			    $can_be_added = true;
			    if((int) $this->option_arr['o_max_slots_per_booking'] > 0)
			    {
			        $cart_all = $this->cart->getAll();
			        $count = 0;
			        foreach($cart_all as $cid => $items)
			        {
			            if($this->_get->toInt('cid') == $cid)
			            {
			                foreach ($items as $item)
			                {
			                    $count += count($item);
			                }
			            }
			        }
			        if($count >= (int) $this->option_arr['o_max_slots_per_booking'])
			        {
			            $can_be_added = false;
			        }
			    }
			    if($can_be_added == true)
			    {
    			    $key = sprintf('%s|%s', $this->_post->toString('start_ts'), $this->_post->toString('end_ts'));
    			    $this->cart->add($this->_get->toInt('cid'), $this->_post->toString('date'), $key, 1);
    			    if (!isset($_SESSION[$this->defaultSlots][$this->_get->toInt('cid')][$key])) 
    			    {
    			        $_SESSION[$this->defaultSlots][$this->_get->toInt('cid')][$key] = 1;
    			    }
    			    self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item was added to your cart.'));
			    }else{
			        self::jsonResponse(array('status' => 'ERR', 'code' => 101, 'text' => sprintf(__('front_max_slots_per_booking_msg', true), (int) $this->option_arr['o_max_slots_per_booking'])));
			    }
			}
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
		}
		exit;
	}

	protected function pjActionSort(&$arr)
	{
		$_arr = array();
		foreach ($arr as $cid => $date_arr)
		{
			foreach ($date_arr as $date => $time_arr)
			{
				$_time_arr = array();
				foreach ($time_arr as $time => $q)
				{
					list($start_ts, $end_ts) = explode("|", $time);
					$_time_arr[$start_ts] = $time;
				}
				ksort($_time_arr); //ksort, krsort
				$_arr[$cid][strtotime($date)] = array_flip($_time_arr);
			}
			ksort($_arr[$cid]); //ksort, krsort
		}
		$arr = array();
		foreach ($_arr as $cid => $date_arr)
		{
			foreach ($date_arr as $date => $time_arr)
			{
				$arr[$cid][date("Y-m-d", $date)] = $time_arr;
			}
		}
	}
	
	public function pjActionRemove()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
		    if ($this->_get->toInt('cid') > 0 && $this->_post->check('date') && $this->_post->check('start_ts') && $this->_post->check('end_ts') &&
		        preg_match('/^\d{10}$/', $this->_post->toString('start_ts')) && preg_match('/^\d{10}$/', $this->_post->toString('end_ts')) &&
		        pjValidation::pjActionDate($this->_post->toString('date'))
			)
			{
			    $key = sprintf('%s|%s', $this->_post->toString('start_ts'), $this->_post->toString('end_ts'));
			    $this->cart->remove($this->_get->toInt('cid'), $this->_post->toString('date'), $key);
			    if (isset($_SESSION[$this->defaultSlots][$this->_get->toInt('cid')][$key])) 
			    {
			        unset($_SESSION[$this->defaultSlots][$this->_get->toInt('cid')][$key]);
			    }
				self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Item was removed from your cart.'));
			}
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
		}
		exit;
	}
	
	public function pjActionReset()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
			$this->cart->reset();
			self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Cart has been reset.'));
		}
		exit;
	}
	
	public function pjActionUpdate()
	{
		$this->setAjax(true);
		
		if ($this->isXHR())
		{
		    if ($this->_get->toInt('cid') > 0 && $this->_post->check('timeslot') && !$this->_post->isEmpty('timeslot'))
			{
			    foreach ($this->_post->toArray('timeslot') as $date => $time_arr)
				{
					foreach ($time_arr as $start_ts => $end_ts)
					{
					    $this->cart->remove($this->_get->toInt('cid'), $date, $start_ts . "|" . $end_ts);
					}
				}
				self::jsonResponse(array('status' => 'OK', 'code' => 200, 'text' => 'Cart has been updated.'));
			}
			self::jsonResponse(array('status' => 'ERR', 'code' => 100, 'text' => 'Missing or empty parameters.'));
		}
		exit;
	}
}
?>