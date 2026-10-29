<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjFrontClient extends pjAuth
{
    public $defaultClient = 'pjTSBC_Client';
    
    public $defaultTmpUser = 'pjTSBC_Temp_User';
    
    public function getClientByEmail()
    {
        $params = $this->getParams();
        $client = pjAuthUserModel::factory()->where('email', $params['email'])->where('role_id', 3)->findAll()->getDataIndex(0);
        if(!empty($client))
        {
            return $client;
        }else{
            return FALSE;
        }
    }
    
    public function createClient()
    {
        $params = $this->getParams();
        $u_data = array();
        $u_data['is_active'] = 'T';
        $u_data['role_id'] = 3;
        $u_data['email'] = $params['customer_email'];
        $u_data['password'] = isset($params['customer_password']) ? $params['customer_password'] : pjAuth::generatePassword($this->option_arr);
        $u_data['name'] = isset($params['customer_name']) ? $params['customer_name'] : ":NULL";
        $u_data['phone'] = isset($params['customer_phone']) ? $params['customer_phone'] : ":NULL";
        $u_data['status'] = isset($params['status']) ? $params['status'] : ":NULL";
        $u_data['ip'] = pjUtil::getClientIp();
        $u_data['is_active'] = 'T';
        $id = pjAuthUserModel::factory($u_data)->insert()->getInsertId();
        if ($id !== false && (int) $id > 0)
        {
            $client = pjFrontClient::init($u_data)->getClientByEmail();
            if($client != FALSE)
            {
                $c_data = array();
                $c_data['foreign_id'] = $client['id'];
                $c_data['calendar_id'] = $params['calendar_id'];
                $c_data['customer_title'] = isset($params['customer_title']) ? $params['customer_title'] : ":NULL";
                $c_data['customer_company'] = isset($params['customer_company']) ? $params['customer_company'] : ":NULL";
                $c_data['customer_notes'] = isset($params['customer_notes']) ? $params['customer_notes'] : ":NULL";
                $c_data['customer_address_1'] = isset($params['customer_address_1']) ? $params['customer_address_1'] : ":NULL";
                $c_data['customer_address_2'] = isset($params['customer_address_2']) ? $params['customer_address_2'] : ":NULL";
                $c_data['customer_country'] = isset($params['customer_country']) ? $params['customer_country'] : ":NULL";
                $c_data['customer_state'] = isset($params['customer_state']) ? $params['customer_state'] : ":NULL";
                $c_data['customer_city'] = isset($params['customer_city']) ? $params['customer_city'] : ":NULL";
                $c_data['customer_zip'] = isset($params['customer_zip']) ? $params['customer_zip'] : ":NULL";
                $client_id = pjClientModel::factory()->setAttributes($c_data)->insert()->getInsertId();
                if ($client_id !== false && (int) $client_id > 0)
                {
                	$calendar_id = isset($params['calendar_id']) && (int) $params['calendar_id'] > 0 ? (int) $params['calendar_id'] : $client['calendar_id'];
                    pjFrontClient::pjActionConfirmSend($this->option_arr, $client_id, PJ_SALT, 'account', $params['locale_id'], $calendar_id);
                    return array('status' => "OK", 'code' => 200, 'client_id' => $client_id);
                }
                return array('status' => "OK", 'code' => 200);
            }
        }else{
            return array('status' => "ERR", 'code' => 100);
        }
    }
    
    public function setClientSession()
    {
        $params = $this->getParams();
        $client = pjClientModel::factory()->where('foreign_id', $params['id'])->findAll()->getDataIndex(0);
        if(!empty($client))
        {
            $user = pjAuth::init($params)->getUser();
            unset($client['id']);
            $client = array_merge($user, $client);
            $this->session->unsetData($this->defaultClient);
            $this->session->setData($this->defaultClient, $client);
        }
    }
    
    public function doClientLogin()
    {
        $params = $this->getParams();
        if($this->session->has($this->defaultUser))
        {
            $this->session->setData($this->defaultTmpUser, $this->session->getData($this->defaultUser));
        }
        $params['is_backend'] = 'F';
        $params['role_id'] = 3;
        $response = pjAuth::init($params)->doLogin();
        if($response['status'] == 'OK')
        {
            if($this->isClient())
            {
                pjFrontClient::init(array('id' => $this->getUserId()))->setClientSession();
            }else{
                $response = array('status' => 'ERR', 'code' => '6');
            }
        }
        if($this->session->has($this->defaultTmpUser))
        {
            $this->session->setData($this->defaultUser, $this->session->getData($this->defaultTmpUser));
            $this->session->unsetData($this->defaultTmpUser);
        }
        return $response;
    }
    
    public function doSendPassword()
    {
        $params = $this->getParams();
        $user = pjFrontClient::init($params)->getClientByEmail();
        $response = array();
        if ($user == FALSE)
        {
            $response = array('status' => 'ERR', 'code' => 100);
        }else{
            $client = pjClientModel::factory()->where('foreign_id', $user['id'])->findAll()->getDataIndex(0);
            if ($user['status'] != 'T')
            {
                $response = array('status' => 'ERR', 'code' => 101);
            }else{
                $locale_id = $this->getLocaleId();
                if(isset($params['locale_id']) && (int) $params['locale_id'] > 0)
                {
                    $locale_id = $params['locale_id'];
                }
                $calendar_id = isset($params['calendar_id']) && (int) $params['calendar_id'] > 0 ? (int) $params['calendar_id'] : $client['calendar_id'];
                pjFrontClient::pjActionConfirmSend($this->option_arr, $client['id'], PJ_SALT, 'forgot', $locale_id, $calendar_id);
                $response = array('status' => 'OK', 'code' => 200);
            }
        }
        return $response;
    }
    
    public static function pjActionConfirmSend($option_arr, $client_id, $salt, $opt, $locale_id, $calendar_id)
    {
        $Email = self::getMailer($option_arr);
        $pjNotificationModel = pjNotificationModel::factory();
        $notification = $pjNotificationModel->reset()->where('foreign_id', $calendar_id)->where('recipient', 'client')->where('transport', 'email')->where('variant', $opt)->findAll()->getDataIndex(0);
        if((int) $notification['id'] > 0 && $notification['is_active'] == 1)
        {
            $client = pjClientModel::factory()->find($client_id)->getData();
            $tokens = pjAppController::getClientTokens($option_arr, $client_id, $salt, $locale_id);
            $resp = pjAppController::pjActionGetSubjectMessage($notification, $locale_id, $calendar_id);
            $lang_message = $resp['lang_message'];
            $lang_subject = $resp['lang_subject'];
            $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
            if (count($lang_message) === 1 && count($lang_subject) === 1 && !empty($auth_client['email']) && !empty($lang_subject[0]['content']))
            {
                $subject = str_replace($tokens['search'], $tokens['replace'], $lang_subject[0]['content']);
                $message = str_replace($tokens['search'], $tokens['replace'], $lang_message[0]['content']);
                $message = stripcslashes($message);
                $Email
                ->setTo($auth_client['email'])
                ->setSubject(stripslashes($subject))
                ->send($message);
            }
        }
    }
}
?>