<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjCalendarModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'calendars';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'user_id', 'type' => 'int', 'default' => ':NULL')
	);
	
	protected $i18n = array(
		'title', 'terms_url', 'terms_body',
		'confirm_subject_client', 'confirm_tokens_client', 'payment_subject_client', 'payment_tokens_client',
		'confirm_subject_admin', 'confirm_tokens_admin', 'payment_subject_admin', 'payment_tokens_admin',
		'reminder_subject_client', 'reminder_tokens_client',
		'confirm_sms_admin', 'payment_sms_admin', 'reminder_sms_client'
	);
	
	public static function factory($attr=array())
	{
		return new pjCalendarModel($attr);
	}
	
	public function init($user_id, $locale)
	{
		$calendar_id = $this->setAttributes(array(
			'user_id' => $user_id
		))->insert()->getInsertId();
		
		if ($calendar_id !== FALSE && (int) $calendar_id > 0)
		{
			pjCalendarUserModel::factory()->setAttributes(array('calendar_id' => $calendar_id, 'user_id' => $user_id))->insert();
			
			$pjMultiLangModel = pjMultiLangModel::factory();
			$i18n_arr = $pjMultiLangModel->getMultiLang(0, 'pjCalendar');
			$i18n_arr[$locale]['title'] = 'Calendar 1';
			$pjMultiLangModel->reset()->saveMultiLang($i18n_arr, $calendar_id, 'pjCalendar');
			
			$this->initCalendar($calendar_id);
		}
		return $calendar_id;
	}
	
	public function initCalendar($id)
	{
	    $pjWorkingTimeModel = pjWorkingTimeModel::factory();
	    $init_wt_arr = $pjWorkingTimeModel->where('foreign_id', 0)->limit(1)->findAll()->getData();
	    $wt_data = $init_wt_arr[0];
	    unset($wt_data['id']);
	    $wt_data['foreign_id'] = $id;
	    $pjWorkingTimeModel->reset()->setAttributes($wt_data)->insert();
	    
	    $pjOptionModel = pjOptionModel::factory();
	    $init_option_arr = $pjOptionModel->where('foreign_id', 0)->findAll()->getData();
	    $pjOptionModel->reset()->setBatchFields(array('foreign_id', 'key', 'tab_id', 'value', 'label', 'type', 'order', 'is_visible', 'style'));
	    foreach ($init_option_arr as $record)
	    {
	        $record['foreign_id'] = $id;
	        foreach ($record as $k => $v) {
	            if (empty($v)) {
	                $record[$k] = '';
	            }
	        }
	        $pjOptionModel->addBatchRow($record);
	    }
	    $pjOptionModel->insertBatch();
	    
	    $pjNotificationModel = pjNotificationModel::factory();
	    $init_notify_arr = $pjNotificationModel->where('foreign_id', 0)->orderBy("id ASC")->findAll()->getData();
	    foreach ($init_notify_arr as $record)
	    {
	        $record['foreign_id'] = $id;
	        unset($record['id']);
	        $pjNotificationModel->reset()->setAttributes($record)->insert();
	    }
	    
	    $pjPaymentOptionModel = pjPaymentOptionModel::factory();
	    $payment_arr = $pjPaymentOptionModel->where("foreign_id IS NULL")->findAll()->getData();
	    foreach($payment_arr as $payment_data)
	    {
	        unset($payment_data['id']);
	        $payment_data['foreign_id'] = $id;
	        $pjPaymentOptionModel->reset()->setAttributes($payment_data)->insert();
	    }
	    $pjPaymentOptionModel->reset()->setAttributes(array('foreign_id' => $id, 'payment_method' => 'bank', 'is_active' => 0))->insert();
	    $po_id = $pjPaymentOptionModel->reset()->setAttributes(array('foreign_id' => $id, 'payment_method' => 'cash', 'is_active' => 1))->insert()->getInsertId();
	    if ($po_id !== false && (int) $po_id > 0)
	    {
	        $locale_arr = pjLocaleModel::factory()->findAll()->getData();
	        $i18n_arr = array();
	        foreach($locale_arr as $locale)
	        {
	            $i18n_arr[$locale['id']]['cash'] = 'Cash';
	        }
	        pjMultiLangModel::factory()->saveMultiLang($i18n_arr, $id, 'pjPayment');
	    }
	}
}
?>