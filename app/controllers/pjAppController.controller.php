<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjAppController extends pjBaseAppController
{
	public $models = array();

	/**
	 * Generate (or return existing) CSRF token for the current session.
	 */
	public static function getCsrfToken()
	{
		if (empty($_SESSION['csrf_token']))
		{
			$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
		}
		return $_SESSION['csrf_token'];
	}

	/**
	 * Validate CSRF token. pjInput nulls raw $_POST/$_GET, so read request objects first.
	 */
	public static function validateCsrfToken()
	{
		$token = '';
		$reg = pjRegistry::getInstance();
		$post = $reg->get('_post');
		$get = $reg->get('_get');
		if (is_object($post) && $post->check('csrf_token')) {
			$token = (string) $post->toString('csrf_token');
		} elseif (is_object($get) && $get->check('csrf_token')) {
			$token = (string) $get->toString('csrf_token');
		} elseif (!empty($_POST['csrf_token'])) {
			$token = $_POST['csrf_token'];
		} elseif (!empty($_GET['csrf_token'])) {
			$token = $_GET['csrf_token'];
		} elseif (!empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
			$token = $_SERVER['HTTP_X_CSRF_TOKEN'];
		}
		if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string) $token))
		{
			header('HTTP/1.1 403 Forbidden');
			exit('Invalid CSRF token.');
		}
	}


	/**
	 * Theme 11: default values of the colour / font / shape settings (must match app/web/css/themes/theme11.css).
	 */
	public static function getTheme11Defaults()
	{
		return array(
			'primary' => '#c84e33',
			'accent' => '#2f6f6a',
			'bg' => '#fbf1ec',
			'surface' => '#ffffff',
			'heading' => '#3b1c14',
			'text' => '#4a3730',
			'muted' => '#7d625a',
			'border' => '#efd9cf',
			'soft' => '#f9ece9',
			'font_heading' => 'Poppins',
			'font_body' => 'Inter',
			'radius' => 18,
			'button' => 'pill'
		);
	}

	/**
	 * Theme 11: fonts the admin can choose from (name => Google Fonts family, NULL for system fonts).
	 */
	public static function getTheme11Fonts()
	{
		return array(
			'Poppins' => 'Poppins',
			'Inter' => 'Inter',
			'DM Sans' => 'DM+Sans',
			'Nunito' => 'Nunito',
			'Space Grotesk' => 'Space+Grotesk',
			'Playfair Display' => 'Playfair+Display',
			'System UI' => NULL
		);
	}

	/**
	 * Theme 11: validates the saved JSON and returns ONLY the valid overrides (empty array = use CSS defaults).
	 * Every value is checked against a strict whitelist, so the result is safe to print inside a stylesheet.
	 */
	public static function getTheme11Colors($json)
	{
		$out = array();
		if (!is_string($json) || $json === '')
		{
			return $out;
		}
		$arr = json_decode($json, true);
		if (!is_array($arr))
		{
			return $out;
		}
		$defaults = self::getTheme11Defaults();
		$fonts = self::getTheme11Fonts();
		foreach ($defaults as $key => $default)
		{
			if (!isset($arr[$key]))
			{
				continue;
			}
			$v = $arr[$key];
			if (in_array($key, array('primary', 'accent', 'bg', 'surface', 'heading', 'text', 'muted', 'border', 'soft'), true))
			{
				if (is_string($v) && preg_match('/^#[0-9a-fA-F]{6}$/', $v))
				{
					$out[$key] = strtolower($v);
				}
			} elseif ($key == 'font_heading' || $key == 'font_body') {
				if (is_string($v) && array_key_exists($v, $fonts))
				{
					$out[$key] = $v;
				}
			} elseif ($key == 'radius') {
				if (is_numeric($v) && (int) $v >= 0 && (int) $v <= 32)
				{
					$out[$key] = (int) $v;
				}
			} elseif ($key == 'button') {
				if (is_string($v) && in_array($v, array('pill', 'rounded', 'square'), true))
				{
					$out[$key] = $v;
				}
			}
		}
		return $out;
	}

	/**
	 * Theme 11: Google Fonts stylesheet URL for the effective heading + body fonts ('' when only system fonts are used).
	 */
	public static function getTheme11FontsUrl($overrides)
	{
		$cfg = array_merge(self::getTheme11Defaults(), is_array($overrides) ? $overrides : array());
		$fonts = self::getTheme11Fonts();
		$families = array();
		foreach (array($cfg['font_heading'], $cfg['font_body']) as $name)
		{
			if (isset($fonts[$name]) && $fonts[$name] !== NULL)
			{
				$families[$fonts[$name]] = 'family=' . $fonts[$name] . ':wght@400;500;600;700';
			}
		}
		if (empty($families))
		{
			return '';
		}
		return 'https://fonts.googleapis.com/css2?' . join('&', $families) . '&display=swap';
	}

	/**
	 * Theme 11: builds the "#pjWrapper{--tsb-*:...}" block from validated overrides.
	 */
	public static function getTheme11CssBlock($overrides)
	{
		if (empty($overrides))
		{
			return '';
		}
		$fonts = self::getTheme11Fonts();
		$stack = function ($name) use ($fonts) {
			$sys = "-apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
			if ($name == 'Playfair Display')
			{
				return "'Playfair Display', Georgia, 'Times New Roman', serif";
			}
			if (!isset($fonts[$name]) || $fonts[$name] === NULL)
			{
				return "system-ui, " . $sys;
			}
			return "'" . $name . "', " . $sys;
		};
		$lum = function ($hex) {
			$c = array();
			foreach (array(1, 3, 5) as $i)
			{
				$v = hexdec(substr($hex, $i, 2)) / 255;
				$c[] = $v <= 0.03928 ? $v / 12.92 : pow(($v + 0.055) / 1.055, 2.4);
			}
			return 0.2126 * $c[0] + 0.7152 * $c[1] + 0.0722 * $c[2];
		};
		$vars = array();
		$map = array('primary' => '--tsb-primary', 'accent' => '--tsb-accent', 'bg' => '--tsb-bg', 'surface' => '--tsb-surface', 'heading' => '--tsb-heading', 'text' => '--tsb-text', 'muted' => '--tsb-muted', 'border' => '--tsb-border');
		foreach ($map as $k => $css)
		{
			if (isset($overrides[$k]))
			{
				$vars[] = $css . ':' . $overrides[$k];
			}
		}
		if (isset($overrides['soft']))
		{
			$vars[] = '--tsb-primary-soft:' . $overrides['soft'];
		}
		if (isset($overrides['primary']))
		{
			// white or near-black text on the brand colour, whichever has the better contrast
			$l = $lum($overrides['primary']);
			$vars[] = '--tsb-primary-contrast:' . ((1.05 / ($l + 0.05)) >= (($l + 0.05) / 0.06) ? '#ffffff' : '#111111');
		}
		if (isset($overrides['font_heading']))
		{
			$vars[] = '--tsb-font-heading:' . $stack($overrides['font_heading']);
		}
		if (isset($overrides['font_body']))
		{
			$vars[] = '--tsb-font-body:' . $stack($overrides['font_body']);
		}
		if (isset($overrides['radius']))
		{
			$vars[] = '--tsb-radius:' . (int) $overrides['radius'] . 'px';
			$vars[] = '--tsb-radius-sm:' . (int) round($overrides['radius'] * 0.66) . 'px';
		}
		if (isset($overrides['button']))
		{
			$btn = array('pill' => '999px', 'rounded' => '10px', 'square' => '3px');
			$vars[] = '--tsb-radius-btn:' . $btn[$overrides['button']];
		}
		return empty($vars) ? '' : "\n/* Theme 11 customization */\n#pjWrapper{" . join(';', $vars) . ";}\n";
	}

	public $defaultCalendarId = 'admin_calendar_id';
	
	public $defaultSlots = 'TSBC_Slots';
	
	private $layoutRange = array(1, 2);
	
	public function pjActionCheckInstall()
	{
	    $this->setLayout('pjActionEmpty');
	    
	    $result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
	    $folders = array('app/web/upload');
	    foreach ($folders as $dir)
	    {
	        if (!is_writable($dir))
	        {
	            $result['status'] = 'ERR';
	            $result['code'] = 101;
	            $result['text'] = 'Permission requirement';
	            $result['info'][] = sprintf('Folder \'<span class="bold">%1$s</span>\' is not writable. You need to set write permissions (chmod 777) to directory located at \'<span class="bold">%1$s</span>\'', $dir);
	        }
	    }
	    
	    return $result;
	}
	
	public function getLayoutRange()
	{
		return $this->layoutRange;
	}
	
	public function beforeFilter()
	{
	    parent::beforeFilter();
	    $this->trimPostedValues();
	    
	    if(!in_array($this->_get->toString('controller'), array('pjFront', 'pjInstaller')))
	    {
	        $pjCalendarModel = pjCalendarModel::factory();
	        if(!$this->isMasterAdmin())
	        {
	            $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	        }
	        $calendar_id_arr = $pjCalendarModel->findAll()->getDataPair(NULL, 'id');
	        if(!$this->getCalendarId())
	        {
	            if (count($calendar_id_arr) >= 1)
	            {
	                $this->setCalendarId($calendar_id_arr[0]);
	            }
	        }else{
	            if(!in_array($this->getCalendarId(), $calendar_id_arr) && !$this->isMasterAdmin())
	            {
	                $this->setCalendarId($calendar_id_arr[0]);
	            }
	        }
	        
	        $pjCalendarModel = pjCalendarModel::factory();
	        if(!$this->isMasterAdmin())
	        {
	            $pjCalendarModel->where(sprintf("(t1.id IN(SELECT `TCU`.calendar_id FROM `%s` AS `TCU` WHERE `TCU`.user_id=%u))", pjCalendarUserModel::factory()->getTable(), $this->getUserId()));
	        }
	        $calendar_arr = $pjCalendarModel
	        ->select("t1.*, t2.content as title")
	        ->join('pjMultiLang', sprintf("t2.model='pjCalendar' AND t2.foreign_id=t1.id AND t2.field='title' AND t2.locale='%u'", $this->getLocaleId()), 'left outer')
	        ->orderBy("t2.content ASC")
	        ->findAll()->getData();
	        $this->set('calendar_arr', $calendar_arr);
	        
	        $base_option_arr = pjBaseOptionModel::factory()->getPairs(1);
	        $script_option_arr = pjOptionModel::factory()->getPairs($this->getCalendarId());
	        $this->option_arr = array_merge($base_option_arr, $script_option_arr);
	        $this->set('option_arr', $this->option_arr);
	        
	        pjRegistry::getInstance()->set('options', $this->option_arr);
	        
	        $this->appendJs('pjAdminCore.js');
	    }
	    
	    return true;
	}
	
	/**
	 * Removes leading/trailing white space from every submitted text value, so a value made only of
	 * spaces/tabs/new lines becomes empty and is caught by the normal "required" checks.
	 * Passwords and tokens are never touched.
	 */
	protected function trimPostedValues()
	{
	    // The framework keeps the real request data in the protected "_pj_post" array of $this->_post
	    // (the global $_POST is blanked by it), so that is the copy which has to be cleaned.
	    if (!is_object($this->_post) || !isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] !== 'POST')
	    {
	        return;
	    }
	    try {
	        $prop = new ReflectionProperty($this->_post, '_pj_post');
	        $prop->setAccessible(true);
	        $data = $prop->getValue($this->_post);
	        if (is_array($data) && !empty($data))
	        {
	            $prop->setValue($this->_post, self::trimDeep($data, ''));
	        }
	    } catch (Exception $e) {
	        // leave the request untouched if the framework layout is different
	    }
	}
	
	protected static function trimDeep($value, $key)
	{
	    if (is_array($value))
	    {
	        foreach ($value as $k => $v)
	        {
	            $value[$k] = self::trimDeep($v, (string) $k);
	        }
	        return $value;
	    }
	    if (is_string($value) && !preg_match('/pass|token|secret|captcha/i', (string) $key))
	    {
	        $out = @preg_replace('/^[\\s\\x{00A0}\\x{200B}\\x{FEFF}]+|[\\s\\x{00A0}\\x{200B}\\x{FEFF}]+$/u', '', $value);
	        return $out === null ? trim($value) : trim($out);
	    }
	    return $value;
	}
	
	/**
	 * True when at least one language holds a non-blank value for $field (server-side safety net:
	 * the form itself already requires the default language).
	 */
	protected static function i18nFilled($i18n_arr, $field)
	{
	    if (!is_array($i18n_arr))
	    {
	        return false;
	    }
	    foreach ($i18n_arr as $row)
	    {
	        if (is_array($row) && isset($row[$field]) && is_string($row[$field]) && trim($row[$field]) !== '')
	        {
	            return true;
	        }
	    }
	    return false;
	}
	
	/**
	 * Customer fields that Options > Booking form marks as "required" (value 3) but arrived empty/blank.
	 * Returns the list of field names.
	 */
	protected function missingRequiredCustomerFields($option_arr = null)
	{
	    $missing = array();
	    if (!is_array($option_arr))
	    {
	        $option_arr = $this->option_arr;
	    }
	    $map = array(
	        'name' => 'customer_name', 'email' => 'customer_email', 'phone' => 'customer_phone',
	        'address_1' => 'customer_address_1', 'address_2' => 'customer_address_2', 'city' => 'customer_city',
	        'state' => 'customer_state', 'zip' => 'customer_zip', 'country' => 'customer_country', 'notes' => 'customer_notes'
	    );
	    foreach ($map as $opt => $field)
	    {
	        if (isset($option_arr['o_bf_' . $opt]) && (int) $option_arr['o_bf_' . $opt] === 3)
	        {
	            $v = $this->_post->toString($field);
	            if ($v === false || trim((string) $v) === '')
	            {
	                $missing[] = $opt;
	            }
	        }
	    }
	    return $missing;
	}
	
	public function afterFilter()
	{
	    parent::afterFilter();
	    if(!in_array($this->_get->toString('controller'), array('pjFront', 'pjInstaller')))
	    {
	        $this->appendCss('admin.css');
	    }
	}
    
	public function pjActionAfterInstall()
	{
	    $this->setLayout('pjActionEmpty');
	    
	    $result = array('status' => 'OK', 'code' => 200, 'text' => 'Operation succeeded', 'info' => array());
	    $pjAuthRolePermissionModel = pjAuthRolePermissionModel::factory();
	    $pjAuthUserPermissionModel = pjAuthUserPermissionModel::factory();
	    
	    $permissions = pjAuthPermissionModel::factory()->findAll()->getDataPair('key', 'id');
	    
	    $roles = array(1 => 'admin', 2 => 'editor');
	    foreach ($roles as $role_id => $role)
	    {
	        if (isset($GLOBALS['CONFIG'], $GLOBALS['CONFIG']["role_permissions_{$role}"])
	        && is_array($GLOBALS['CONFIG']["role_permissions_{$role}"])
	        && !empty($GLOBALS['CONFIG']["role_permissions_{$role}"]))
	        {
	            $pjAuthRolePermissionModel->reset()->where('role_id', $role_id)->eraseAll();
	            
	            foreach ($GLOBALS['CONFIG']["role_permissions_{$role}"] as $role_permission)
	            {
	                if($role_permission == '*')
	                {
	                    // Grant full permissions for the role
	                    foreach($permissions as $key => $permission_id)
	                    {
	                        $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
	                    }
	                    break;
	                }
	                else
	                {
	                    $hasAsterix = strpos($role_permission, '*') !== false;
	                    if($hasAsterix)
	                    {
	                        $role_permission = str_replace('*', '', $role_permission);
	                    }
	                    
	                    foreach($permissions as $key => $permission_id)
	                    {
	                        if($role_permission == $key || ($hasAsterix && strpos($key, $role_permission) !== false))
	                        {
	                            $pjAuthRolePermissionModel->setAttributes(compact('role_id', 'permission_id'))->insert();
	                        }
	                    }
	                }
	            }
	        }
	    }
	    pjAuthRoleModel::factory()->setAttributes(array('id' => 3, 'role' => 'Client', 'is_backend' => 'F', 'T'))->insert();
	    
	    // Grant full permissions to Admin
	    $user_id = 1; // Admin ID
	    $pjAuthUserPermissionModel->reset()->where('user_id', $user_id)->eraseAll();
	    foreach($permissions as $key => $permission_id)
	    {
	        $pjAuthUserPermissionModel->setAttributes(compact('user_id', 'permission_id'))->insert();
	    }
	    
		$calendar_id = pjCalendarModel::factory()->init(1, 1);
		
		$pjBaseOptionModel = pjBaseOptionModel::factory();
		$base_option_arr = $pjBaseOptionModel->where('foreign_id', 1)->where("(tab_id <> 99 OR (tab_id = 99 AND `key` IN ('plugin_sms_api_key', 'plugin_sms_country_code', 'plugin_sms_phone_number_length', 'private_key', 'o_fields_index')))")->findAll()->getData();
		$pjBaseOptionModel->reset()->setBatchFields(array('foreign_id', 'key', 'tab_id', 'value', 'label', 'type', 'order', 'is_visible', 'style'));
		foreach ($base_option_arr as $record)
		{
		    $record['foreign_id'] = 0;
		    foreach ($record as $k => $v) {
		        if (empty($v)) {
		            $record[$k] = '';
		        }
		    }
		    $pjBaseOptionModel->addBatchRow($record);
		}
		$pjBaseOptionModel->insertBatch();
		
		
		return $result;
	}
	
	/* public function getForeignId()
	{
	    if (isset($_SESSION[$this->defaultCalendarId]))
	    {
	        return $_SESSION[$this->defaultCalendarId];
	    }
	    return 1;
	} */
	
	public function getCalendarId()
	{
	    if (isset($_SESSION[$this->defaultCalendarId]))
	    {
	        return $_SESSION[$this->defaultCalendarId];
	    }
	    return false;
	}
	
	public function setCalendarId($calendar_id)
	{
	    $_SESSION[$this->defaultCalendarId] = (int) $calendar_id;
	    return $this;
	}
	public function isMasterAdmin()
	{
	    return (int) $this->getUserId() === 1;
	}
	public function isEditor()
	{
	    return (int) $this->getRoleId() === 2;
	}
	public function isClient()
	{
	    return $this->getRoleId() == 3;
	}
	
	public function isCountryReady()
	{
	    return $this->isAdmin();
	}
	public function isOneAdminReady()
	{
	    return $this->isAdmin();
	}
	
	public function getLocaleId()
	{
	    return isset($_SESSION[$this->defaultLocale]) && (int) $_SESSION[$this->defaultLocale] > 0 ? (int) $_SESSION[$this->defaultLocale] : false;
	}
	
	public function setLocaleId($locale_id)
	{
	    $_SESSION[$this->defaultLocale] = (int) $locale_id;
	}
	
    public static function jsonDecode($str)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->decode($str);
	}
	
	public static function jsonEncode($arr)
	{
		$Services_JSON = new pjServices_JSON();
		return $Services_JSON->encode($arr);
	}
	
	public static function jsonResponse($arr)
	{
		header("Content-Type: application/json; charset=utf-8");
		echo pjAppController::jsonEncode($arr);
		exit;
	}
	
	public static function getFromEmail()
	{
	    $arr = pjAuthUserModel::factory()
	    ->findAll()
	    ->orderBy("t1.id ASC")
	    ->limit(1)
	    ->getData();
	    return !empty($arr) ? $arr[0]['email'] : null;
	}
	
	public static function getAdminEmail()
	{
	    $arr = pjAuthUserModel::factory()
	    ->where('t1.role_id', '1')
	    ->where('t1.status', 'T')
	    ->limit(1)
	    ->findAll()
	    ->getDataPair('id', 'email');
	    return $arr;
	}
	public static function getAdminPhone()
	{
	    $arr = pjAuthUserModel::factory()
	    ->where('t1.role_id', '1')
	    ->where('t1.status', 'T')
	    ->limit(1)
	    ->findAll()
	    ->getDataPair('id', 'phone');
	    return $arr;
	}
		
	public static function getTokens($booking_arr, $option_arr)
	{
		$timeslots = NULL;
		$tmp = array();
		$hidePrices = (int) $option_arr['o_hide_prices'] === 1;
		if (isset($booking_arr['bs_arr']))
		{
			foreach ($booking_arr['bs_arr'] as $item)
			{
				$tmp[] = sprintf("%s | %s - %s%s",
					pjDateTime::formatDate($item['booking_date'], 'Y-m-d', $option_arr['o_date_format']),
					date($option_arr['o_time_format'], $item['start_ts']),
					date($option_arr['o_time_format'], $item['end_ts']),
				    $hidePrices ? NULL : " | " . (int)$item['cnt'] . " x " .  pjCurrency::formatPrice($item['price'])
				);
			}
		}
		if (!empty($tmp))
		{
			$timeslots = join("\n", $tmp);
		}
		
		$payment_methods = pjObject::getPlugin('pjPayments') !== NULL? pjPayments::getPaymentTitles($booking_arr['calendar_id'], $booking_arr['locale_id']): __('payment_methods',true);
		
		$search = array(
			'{Name}', '{Phone}', '{Email}', '{Notes}', '{Country}', '{CalendarName}', 
			'{State}', '{City}', '{Zip}', '{Address1}', '{Address2}',
		 	'{CCType}', '{CCNum}', '{CCExpMonth}', '{CCExpYear}', '{CCSec}',
		 	'{PaymentMethod}', '{Deposit}', '{Tax}', '{Total}', '{Price}', '{VoucherCode}', '{Discount}',
			'{BookingID}', '{Timeslots}', '{CancelURL}'
		);
		$replace = array(
			@$booking_arr['customer_name'], @$booking_arr['customer_phone'], @$booking_arr['customer_email'], @$booking_arr['customer_notes'], @$booking_arr['country_name'], @$booking_arr['calendar_name'], 
			@$booking_arr['customer_state'], @$booking_arr['customer_city'], @$booking_arr['customer_zip'], @$booking_arr['customer_address_1'], @$booking_arr['customer_address_2'],
			@$booking_arr['cc_type'], @$booking_arr['cc_num'], @$booking_arr['cc_exp_month'], @$booking_arr['cc_exp_year'], @$booking_arr['cc_code'],
			@$payment_methods[$booking_arr['payment_method']],
		    $hidePrices ? NULL : pjCurrency::formatPrice(@$booking_arr['booking_deposit']),
		    $hidePrices ? NULL : pjCurrency::formatPrice(@$booking_arr['booking_tax']),
		    $hidePrices ? NULL : pjCurrency::formatPrice(@$booking_arr['booking_total']),
		    $hidePrices ? NULL : pjCurrency::formatPrice(@$booking_arr['booking_price']),
		    $hidePrices ? NULL : @$booking_arr['discount_code'],
		    $hidePrices ? NULL : pjCurrency::formatPrice(@$booking_arr['booking_discount']),
			@$booking_arr['uuid'], $timeslots, sprintf("%sindex.php?controller=pjFrontEnd&action=pjActionCancel&uuid=%s&hash=%s", PJ_INSTALL_URL, $booking_arr['uuid'], sha1($booking_arr['uuid'] . PJ_SALT))
		);
		
		return compact('search', 'replace');
	}
	
	public static function getClientTokens($option_arr, $client_id, $salt, $locale_id)
	{
	    $country = NULL;
	    $client = pjClientModel::factory()->find($client_id)->getData();
	    $auth_client = pjAuthUserModel::factory()->find($client['foreign_id'])->getData();
	    if(isset($client['customer_country']) && (int) $client['customer_country'] > 0)
	    {
	        $country_arr = pjBaseCountryModel::factory()
	        ->select('t1.id, t2.content AS country_title')
	        ->join('pjBaseMultiLang', "t2.model='pjBaseCountry' AND t2.foreign_id=t1.id AND t2.field='name' AND t2.locale='".$locale_id."'", 'left outer')
	        ->find($client['customer_country'])->getData();
	        if (!empty($country_arr))
	        {
	            $country = $country_arr['country_title'];
	        }
	    }
	    $search = array(
	        '{Title}', '{Name}', '{Phone}', '{Email}', '{Password}',
	        '{Notes}', '{Address1}', '{Address2}', '{City}', '{State}', '{Zip}', '{Country}');
	    $replace = array(
	        @$client['c_title'], pjSanitize::clean(@$auth_client['name']), pjSanitize::clean(@$auth_client['phone']), pjSanitize::clean(@$auth_client['email']), @$auth_client['password'],
	        @$client['customer_notes'], pjSanitize::clean(@$client['customer_address_1']), pjSanitize::clean(@$client['customer_address_2']), pjSanitize::clean(@$client['customer_city']), pjSanitize::clean(@$client['customer_state']), pjSanitize::clean(@$client['customer_zip']), $country);
	    return compact('search', 'replace');
	}
	
	public static function pjActionGetSubjectMessage($notification, $locale_id, $calendar_id)
	{
	    $field = $notification['variant'] . '_tokens_' . $notification['recipient'];
	    $field = str_replace('confirmation', 'confirm', $field);
	    $pjMultiLangModel = pjMultiLangModel::factory();
	    $lang_message = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id', $calendar_id)
	    ->where('t1.model','pjCalendar')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', $field)
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    $field = $notification['variant'] . '_subject_' . $notification['recipient'];
	    $field = str_replace('confirmation', 'confirm', $field);
	    $lang_subject = $pjMultiLangModel
	    ->reset()
	    ->select('t1.*')
	    ->where('t1.foreign_id',  $calendar_id)
	    ->where('t1.model','pjCalendar')
	    ->where('t1.locale', $locale_id)
	    ->where('t1.field', $field)
	    ->limit(0, 1)
	    ->findAll()
	    ->getData();
	    return compact('lang_message', 'lang_subject');
	}
	
	public static function getPrices($calendar_id, $wt_arr, $date, $start_ts, $end_ts, $qty)
	{
		$price = 0;
		
		$date_arr = pjDateModel::factory()->getDailyWorkingTime($calendar_id, $date);
		
		if ($date_arr !== false && $date_arr['is_dayoff'] == 'F')
		{
		    $price = (float) $date_arr['price'] * $qty;
		    $price_arr = pjPriceModel::factory()
		    ->where('t1.calendar_id', $calendar_id)
		    ->where('t1.date', $date)
		    ->where('t1.start_ts', $start_ts)
		    ->where('t1.end_ts', $end_ts)
		    ->orderBy('t1.start_time ASC')
		    ->limit(1)
		    ->findAll()
		    ->getData();
		    if (count($price_arr) === 1)
		    {
		        $price = $price_arr[0]['price'] * $qty;
		    }
		}else{
			$day = strtolower(date("l", $start_ts));
			if ($wt_arr !== false && $wt_arr['is_dayoff'] == 'F')
			{
				$price_day_arr = pjPriceDayModel::factory()
					->where('t1.calendar_id', $calendar_id)
					->where('t1.day', $day)
					->where('t1.start_time', date("H:i:s", $start_ts))
					->where('t1.end_time', date("H:i:s", $end_ts))
					->orderBy('t1.start_time ASC')
					->limit(1)
					->findAll()
					->getData();
				if (count($price_day_arr) === 1)
				{
					$price = $price_day_arr[0]['price'] * $qty;
				} else {
					$price = (float) $wt_arr['price'] * $qty;
				}
			}
		}
		return $price;
	}
	
	public static function getCartTotal($calendar_id, $cart, $option_arr)
	{
		$pjWorkingTimeModel = pjWorkingTimeModel::factory();
		
		$original_price = 0;
		$price = 0;
		$discount = 0;
		if (!$cart->isEmpty())
		{
			$cart_arr = $_SESSION['TSBC_Cart'];
			
			foreach ($cart_arr as $cid => $date_arr)
			{
				if ($cid != $calendar_id)
				{
					continue;
				}
				
				$wt_data = $pjWorkingTimeModel->getWorkingTime($cid);
				foreach ($date_arr as $date => $time_arr)
				{
					$wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
					foreach ($time_arr as $time => $qty)
					{
						list($start_ts, $end_ts) = explode("|", $time);
						$qty = $_SESSION['TSBC_Slots'][$cid][$time];
						$slot_price = pjAppController::getPrices($cid, $wt_arr, $date, $start_ts, $end_ts, $qty);
						$original_price += $slot_price;
						if(isset($cart_arr['voucher_data'][$start_ts]))
						{
						    $discount_record = $cart_arr['voucher_data'][$start_ts];
						    if($discount_record['status'] == 'OK' && isset($discount_record['arr']))
						    {
    						    $_discount = $discount_record['arr']['discount'];
    						    if($discount_record['arr']['type'] == 'percent')
    						    {
    						        $_discount = $slot_price * $discount_record['arr']['discount'] / 100; 
    						    }
    						    if($_discount > $slot_price)
    						    {
    						        $_discount = $slot_price;
    						    }
    						    $discount += $_discount;
    						    $slot_price -= $_discount;
						    }
						}
						if($slot_price < 0)
						{
						    $slot_price = 0;
						}
						$price += $slot_price;
					}
				}
			}
		}
		
		$tax = ($price * $option_arr['o_tax']) / 100;
		$total = $price + $tax;
		switch ($option_arr['o_deposit_type'])
		{
			case 'amount':
				$deposit = $option_arr['o_deposit'];
				break;
			case 'percent':
			default:
				$deposit = ($total * $option_arr['o_deposit']) / 100;
				break;
		}

		return array('original_price' => round($original_price, 2), 'price' => round($price, 2), 'total' => round($total, 2), 'deposit' => round($deposit, 2), 'tax' => round($tax, 2), 'discount' => round($discount, 2));
	}
	
	public static function getCartPrices($calendar_id, $cart)
	{
		$pjDateModel = pjDateModel::factory();
		$pjWorkingTimeModel = pjWorkingTimeModel::factory();
		$pjPriceModel = pjPriceModel::factory();
		$pjPriceDayModel = pjPriceDayModel::factory();
		
		$_arr = array();
		$cart_arr = $cart->getAll();
		foreach ($cart_arr as $cid => $date_arr)
		{
			if ($cid != $calendar_id)
			{
				continue;
			}
			$wt_data = $pjWorkingTimeModel->getWorkingTime($calendar_id);
			foreach ($date_arr as $date => $time_arr)
			{
				$wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
				foreach ($time_arr as $time => $q)
				{
					list($start_ts, $end_ts) = explode("|", $time);
					
					$index = $time;
					$_arr[$index] = 0;
					
					$_arr[$index] = pjAppController::getPrices($calendar_id, $wt_arr, $date, $start_ts, $end_ts, 1);
				}
			}
		}
		# NB: Higher RANK gets bigger precedence!
		return array($calendar_id => $_arr);
	}
	
	public static function getPricesDate($calendar_id, $date, $option_arr)
	{
		$t_arr = pjAppController::getDailySlots($calendar_id, $date, $option_arr);
		if ($t_arr === false)
		{
			# It's Day off
			return false;
		}
		
		$pjWorkingTimeModel = pjWorkingTimeModel::factory();
				
		$wt_data = $pjWorkingTimeModel->getWorkingTime($calendar_id);
		$wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
		
		$_arr = array();
		$step = $t_arr['slot_length'] * 60;
		# Fix for 24h support
		$offset = $t_arr['end_ts'] <= $t_arr['start_ts'] ? 86400 : 0;
		$day = strtolower(date("l", strtotime($date)));
	
		$slots = $t_arr['slots'];
		$i = $t_arr['start_ts'];
		$lunch_from_ts = $t_arr['lunch_start_ts'];
		$lunch_to_ts = $t_arr['lunch_end_ts'];
		if($lunch_from_ts < $i)
		{
			$lunch_from_ts = $lunch_from_ts + (24 * 60 * 60);
			$lunch_to_ts = $lunch_to_ts + (24 * 60 * 60);
		}
		for($n = 1; $n <= $slots; $n++)
		{
			$index = $i . "|" . ($i + $step);
			$_arr[$index] = 0;
			
			$_arr[$index] = pjAppController::getPrices($calendar_id, $wt_arr, $date, $i, ($i + $step), 1);
			if($i + $step == $lunch_from_ts)
			{
				$i = $lunch_to_ts;
			}else{
				$i = $i + $step;
			}
		}
		# NB: Higher RANK gets bigger precedence!
		return $_arr;
	}
	public static function getTimeByDates($calendar_ids, $date)
	{
	    $result = array();
	    $pjWorkingTimeModel = pjWorkingTimeModel::factory();
	    $pjDateModel = pjDateModel::factory();
	    foreach($calendar_ids as $cid)
	    {
	        $date_arr = $pjDateModel->reset()->getDailyWorkingTime($cid, $date);
	        if ($date_arr === false)
	        {
	            $wt_data = $pjWorkingTimeModel->reset()->getWorkingTime($cid);
	            $wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);
	            $t_arr = $wt_arr;
	        } else {
	            $t_arr = $date_arr;
	        }
	        
	        if($t_arr != false && $t_arr['is_dayoff'] == 'F')
	        {
	            if(isset($result['start_ts']))
	            {
	                if($t_arr['start_ts'] < $result['start_ts'])
	                {
	                    $result['start_ts'] = $t_arr['start_ts'];
	                }
	            }else{
	                $result['start_ts'] = $t_arr['start_ts'];
	            }
	            if(isset($result['end_ts']))
	            {
	                if($t_arr['end_ts'] > $result['end_ts'])
	                {
	                    $result['end_ts'] = $t_arr['end_ts'];
	                }
	            }else{
	                $result['end_ts'] = $t_arr['end_ts'];
	            }
	            if($t_arr['lunch_start_ts'] != $t_arr['lunch_end_ts'])
	            {
    	            $result['lunch_start_ts'] = $t_arr['lunch_start_ts'];
    	            $result['lunch_end_ts'] = $t_arr['lunch_end_ts'];
	            }
	        }
	    }
	    return $result;
	}
	public static function getDailySlots($calendar_id, $date, $option_arr)
	{
		$date_arr = pjDateModel::factory()->getDailyWorkingTime($calendar_id, $date);
		
		if ($date_arr === false)
		{
			# There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
			$pjWorkingTimeModel = pjWorkingTimeModel::factory();
			$wt_data = $pjWorkingTimeModel->getWorkingTime($calendar_id);
			$wt_arr = $pjWorkingTimeModel->filterDate($wt_data, $date);

			//$wt_arr['slot_length'] = $option_arr['slot_length'];
			$t_arr = $wt_arr;
		} else {
			# There is custom working time/prices for given date
			$t_arr = $date_arr;
		}
		
		return $t_arr;
	}
	
	public static function getWeeklySlots($calendar_id, $iso_date)
	{
		$date_arr = pjDateModel::factory()->getWeeklyWorkingTime($calendar_id, $iso_date);
		
		$pjWorkingTimeModel = pjWorkingTimeModel::factory();
		$wt_data = $pjWorkingTimeModel->getWorkingTime($calendar_id);

		$t_arr = array();
		foreach ($date_arr as $date => $item)
		{
			$t_arr[$date] = array();
			
			# There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
			if (empty($item))
			{
				$t_arr[$date] = $pjWorkingTimeModel->filterDate($wt_data, $date);
				continue;
			}
			
			$t_arr[$date] = $item;
		}
		
		return $t_arr;
	}
	
	public static function getMonthlySlots($calendar_id, $month, $year)
	{
		$date_arr = pjDateModel::factory()->getMonthlyWorkingTime($calendar_id, $month, $year);
		
		$pjWorkingTimeModel = pjWorkingTimeModel::factory();
		$wt_data = $pjWorkingTimeModel->getWorkingTime($calendar_id);

		$t_arr = array();
		foreach ($date_arr as $date => $item)
		{
			$t_arr[$date] = array();
			
			# There is not custom working time/prices for given date, so get for day of week (Monday, Tuesday...)
			if (empty($item))
			{
				$t_arr[$date] = $pjWorkingTimeModel->filterDate($wt_data, $date);
				continue;
			}
			
			$t_arr[$date] = $item;
		}
		
		return $t_arr;
	}
	
	public static function getMonthStatus($calendar_id, $month, $year, $option_arr)
	{
		$numOfDays = date("t", mktime(0, 0, 0, $month, 1, $year));
		$_arr = array();
		foreach (range(1, $numOfDays) as $i)
		{
			$_arr[date("Y-m-d", mktime(0, 0, 0, $month, $i, $year))] = array('code' => 1, 'text' => 'free');
		}
		
		$bookings = pjBookingSlotModel::factory()->getBookings($calendar_id, $month, $year);
		$monthlySlots = pjAppController::getMonthlySlots($calendar_id, $month, $year);
		$now = time();
		foreach ($monthlySlots as $date => $t_arr)
		{
			if (empty($t_arr) || (isset($t_arr['is_dayoff']) && $t_arr['is_dayoff'] == 'T'))
			{
				continue;
			}
			$step = $t_arr['slot_length'] * 60;
			# Fix for 24h support
			$offset = $t_arr['end_ts'] <= $t_arr['start_ts'] ? 86400 : 0;

			$dailyAv = 0;
			$dailyBo = 0;
			
			$slots = $t_arr['slots'];
			$i = $t_arr['start_ts'];
			$lunch_from_ts = $t_arr['lunch_start_ts'];
			$lunch_to_ts = $t_arr['lunch_end_ts'];
			if($lunch_from_ts < $i)
			{
			    $lunch_from_ts = $lunch_from_ts + 86400;
			    $lunch_to_ts = $lunch_to_ts + 86400;
			}
			for($n = 1; $n <= $slots; $n++)
			{
				$cnt_booked = 0;
			    foreach ($bookings[$date] as $bs)
			    {
			        if ($bs['start_ts'] == $i && $bs['end_ts'] == $i + $step)
			        {
			            $dailyBo += $bs['cnt'];
			            $cnt_booked += $bs['cnt'];
			        }
			    }
				if ($i < $now || ($i < $now + $option_arr['o_hours_before'] * 3600))
				{
					$dailyBo += $t_arr['slot_limit'] - $cnt_booked;
				}
			    $dailyAv += $t_arr['slot_limit'];
			    if($i + $step == $lunch_from_ts)
			    {
			        $i = $lunch_to_ts;
			    }else{
			        $i = $i + $step;
			    }
			}
			
			if ($dailyBo > 0 && $dailyAv > $dailyBo)
			{
			    $_arr[$date] = array('code' => 2, 'text' => 'partly');
			} elseif ($dailyAv <= $dailyBo) {
			    $_arr[$date] = array('code' => 3, 'text' => 'fully');
			}
		}
		return $_arr;
	}
}
?>