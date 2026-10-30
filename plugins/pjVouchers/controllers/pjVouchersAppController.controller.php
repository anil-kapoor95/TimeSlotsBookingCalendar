<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjVouchersAppController extends pjPlugin
{
	public function __construct()
	{
		$this->setLayout('pjActionAdmin');
	}
	
	/**
	 * Removes leading/trailing white space from every submitted text value, so a value made only of
	 * spaces becomes empty and is caught by the "required" checks. Passwords/tokens are never touched.
	 */
	protected function trimPostedValues()
	{
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
	
	public static function getConst($const)
	{
		$registry = pjRegistry::getInstance();
		$store = $registry->get('pjVouchers');
		return isset($store[$const]) ? $store[$const] : NULL;
	}
}
?>