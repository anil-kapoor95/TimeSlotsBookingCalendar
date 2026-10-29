<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
class pjClientModel extends pjAppModel
{
	protected $primaryKey = 'id';
	
	protected $table = 'clients';
	
	protected $schema = array(
		array('name' => 'id', 'type' => 'int', 'default' => ':NULL'),
	    array('name' => 'foreign_id', 'type' => 'int', 'default' => ':NULL'),
	    array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'customer_title', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_company', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_address_1', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_address_2', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_country', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'customer_state', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_city', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_zip', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'customer_notes', 'type' => 'text', 'default' => ':NULL')
	);
	
	public static function factory($attr=array())
	{
		return new pjClientModel($attr);
	}
}
?>