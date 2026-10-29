<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjVoucherModel extends pjVouchersAppModel
{
	protected $primaryKey = 'id';

	protected $table = 'plugin_vouchers';

	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'code', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'discount', 'type' => 'decimal', 'default' => ':NULL'),
		array('name' => 'valid', 'type' => 'enum', 'default' => ':NULL'),
		array('name' => 'date_from', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'date_to', 'type' => 'date', 'default' => ':NULL'),
		array('name' => 'time_from', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'time_to', 'type' => 'time', 'default' => ':NULL'),
		array('name' => 'every', 'type' => 'enum', 'default' => ':NULL')
	);

    public static function factory($attr=array())
    {
        return new self($attr);
    }

    public function getVoucher($code, $date, $time)
	{
		$result = array();

		$arr = $this->select("t1.*, TIME_TO_SEC(`time_from`) AS `sec_from`, TIME_TO_SEC(`time_to`) AS `sec_to`")
			->where("code", $code)
			->findAll()->getData();

		$result['status'] = 'ERR';
		$result['code'] = '100';

		if (count($arr) == 1)
		{
			$arr = $arr[0];
			$sec = self::hoursToSeconds($time);

			switch ($arr['valid'])
			{
				case 'period':
					$timestamp_from = strtotime($arr['date_from'] . ' ' . $arr['time_from']);
					$timestamp_to = strtotime($arr['date_to'] . ' ' . $arr['time_to']);
					$timestamp_current = strtotime($date . ' ' . $time);
					if ($timestamp_current >= $timestamp_from && $timestamp_current <= $timestamp_to)
					{
						$result['status'] = 'OK';
						$result['code'] = '200';
						$result['arr'] = $arr;

					} else {
						$result['status'] = 'ERR';
						$result['code'] = '101';
					}
					break;
				case 'fixed':
					if ($arr['date_from'] == $date && $sec >= $arr['sec_from'] && $sec <= $arr['sec_to'])
					{
						$result['status'] = 'OK';
						$result['code'] = '200';
						$result['arr'] = $arr;
					} else {
						$result['status'] = 'ERR';
						$result['code'] = '101';
					}
					break;
				case 'recurring':
					if (date("l", strtotime($date)) == ucfirst($arr['every']) && $sec >= $arr['sec_from'] && $sec <= $arr['sec_to'])
					{
						$result['status'] = 'OK';
						$result['code'] = '200';
						$result['arr'] = $arr;
					} else {
						$result['status'] = 'ERR';
						$result['code'] = '101';
					}
					break;
			}
		}

		return $result;
	}

	protected static function hoursToSeconds($hour)
	{
    	$parse = array();
		if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2}):(?<secs>[\d]{2})$#',$hour,$parse))
		{
			throw new RuntimeException ("Hour Format not valid");
		}
		return (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60 + (int) $parse['secs'];
	}
}
?>