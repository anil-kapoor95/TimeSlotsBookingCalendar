<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCron extends pjAppController
{
	public function pjActionIndex()
	{
		$this->setLayout('pjActionEmpty');
		
		$pjOptionModel = pjOptionModel::factory();
		$pjBookingModel = pjBookingModel::factory();
		$pjBookingSlotModel = pjBookingSlotModel::factory();
		$pjNotificationModel = pjNotificationModel::factory();
		
		$pjEmail = new pjEmail();
		$pjEmail->setContentType('text/html');
		
		$priv_key = md5($this->option_arr['private_key'] . PJ_SALT);
		
		$calendar_arr = pjCalendarModel::factory()->findAll()->getData();
		foreach ($calendar_arr as $calendar)
		{
			$script_option_arr = $pjOptionModel->reset()->getPairs($calendar['id']);
			$option_arr = array_merge($this->option_arr, $script_option_arr);
			if ((int) $option_arr['o_reminder_enable'] === 0)
			{
				continue;
			}
			
			# Emails
			$pjEmail = self::getMailer($option_arr);
			
			$booking_arr = $pjBookingModel
				->reset()
				->select('t1.*, t1.id AS `booking_id`, t2.content AS `country_title`,
					t3.content AS `reminder_subject_client`, t4.content AS `reminder_tokens_client`, t5.content AS `calendar_name`, t7.name AS customer_name, t7.email AS customer_email, t7.phone AS customer_phone')
				->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
				->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='reminder_subject_client'", 'left outer')
				->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='reminder_tokens_client'", 'left outer')
				->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='title'", 'left outer')
				->join('pjClient', 't1.client_id=t6.id', 'left outer')
				->join('pjAuthUser', 't6.foreign_id=t7.id', 'left outer')
				->where('t1.booking_status', 'confirmed')
				->where('t1.calendar_id', $calendar['id'])
				->where('t1.reminder_email', 0)
				->where(sprintf("0 < (SELECT COUNT(*)
					FROM `%1\$s`
					WHERE `booking_id` = `t1`.`id`
					AND (UNIX_TIMESTAMP() BETWEEN (`start_ts` - %2\$u) AND `start_ts`)
					LIMIT 1)", $pjBookingSlotModel->getTable(), (int) $option_arr['o_reminder_email_before'] * 3600))
				->findAll()
				->getData();
			foreach ($booking_arr as $booking)
			{
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking['calendar_id'])->where('recipient', 'client')->where('transport', 'email')->where('variant', 'reminder')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    				$booking['bs_arr'] = $pjBookingSlotModel->reset()->where('t1.booking_id', $booking['id'])->findAll()->getData();
    				$tokens = pjAppController::getTokens($booking, $option_arr);
    				$subject_client = str_replace($tokens['search'], $tokens['replace'], $booking['reminder_subject_client']);
    				$message_client = str_replace($tokens['search'], $tokens['replace'], $booking['reminder_tokens_client']);
    				$message_client = stripslashes($message_client);
    				
    				$pjEmail->setTo($booking['customer_email']);
    				$pjEmail->setSubject($subject_client);
    				
    				if ($pjEmail->send($message_client))
    				{
    					$pjBookingModel->reset()->set('id', $booking['id'])->modify(array('reminder_email' => 1));
    				}
			    }
			}
			
			# SMS
			$booking_arr = $pjBookingModel
			->reset()
			->select('t1.*, t2.content AS `country_title`, t3.content AS `reminder_sms_client`, t5.content AS `calendar_name`, t7.name AS customer_name, t7.email AS customer_email, t7.phone AS customer_phone')
			->join('pjMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.customer_country AND t2.locale=t1.locale_id AND t2.field='name'", 'left outer')
			->join('pjMultiLang', "t3.model='pjCalendar' AND t3.foreign_id=t1.calendar_id AND t3.locale=t1.locale_id AND t3.field='reminder_sms_client'", 'left outer')
			->join('pjMultiLang', "t4.model='pjCalendar' AND t4.foreign_id=t1.calendar_id AND t4.locale=t1.locale_id AND t4.field='title'", 'left outer')
			->join('pjMultiLang', "t5.model='pjCalendar' AND t5.foreign_id=t1.calendar_id AND t5.locale=t1.locale_id AND t5.field='title'", 'left outer')
			->join('pjClient', 't1.client_id=t6.id', 'left outer')
			->join('pjAuthUser', 't6.foreign_id=t7.id', 'left outer')
			->where('t1.booking_status', 'confirmed')
			->where('t1.calendar_id', $calendar['id'])
			->where('t1.reminder_sms', 0)
			->where(sprintf("0 < (SELECT COUNT(*)
						FROM `%1\$s`
						WHERE `booking_id` = `t1`.`id`
						AND (UNIX_TIMESTAMP() BETWEEN (`start_ts` - %2\$u) AND `start_ts`)
						LIMIT 1)", $pjBookingSlotModel->getTable(), (int) $option_arr['o_reminder_sms_hours'] * 3600))
			->findAll()
			->getData();
			foreach ($booking_arr as $booking)
			{
			    if (empty($booking['customer_phone']))
			    {
			        continue;
			    }
			    $notification = $pjNotificationModel->reset()->where('foreign_id', $booking['calendar_id'])->where('recipient', 'client')->where('transport', 'sms')->where('variant', 'reminder')->findAll()->getDataIndex(0);
			    if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
			    {
    			    $booking['bs_arr'] = $pjBookingSlotModel->reset()->where('t1.booking_id', $booking['id'])->findAll()->getData();
    			    $tokens = pjAppController::getTokens($booking, $option_arr);
    			    $message_client = str_replace($tokens['search'], $tokens['replace'], $booking['reminder_sms_client']);
    			    
    			    $number = $booking['customer_phone'];
    			    $number = preg_replace('/\D/', '', $number);
    			    if(!empty($message_client))
    			    {
        			    $params = array(
        			        'number' => $booking['customer_phone'],
        			        'text' => $message_client,
        			        'type' => 'unicode',
        			        'key' => md5($option_arr['private_key'] . PJ_SALT)
        			    );
        			    $result = pjBaseSms::init($params)->pjActionSend();
        			    $pjBookingModel->reset()->set('id', $booking['id'])->modify(array('reminder_sms' => 1));
    			    }
			    }
			}
		}
		return "Cron job is executed.";
	}
}
?>