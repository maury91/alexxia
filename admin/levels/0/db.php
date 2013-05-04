<?php
include(__base_path.'levels/0/db.php');
class DB2 {

	protected static $db;
	
	public static function set_DB($db) {
		self::$db=$db;
	}
	
	public static function q_rows($q) {
		return self::$db->q_rows($q);
	}
	
	public static function q_assoc($q) {
		return self::$db->q_assoc($q);
	}
	
	public static function read($n) {
		return self::$db->read($n);
	}
	
	public static function create_query($argv) {
		return self::$db->create_query($argv);
	}
	
	public static function query() {
		$args = func_get_args();
		return self::$db->query(self::$db->create_query($args));
	}
	
	public static function error() {
		return self::$db->error();
	}
	
	public static function rows($r) {
		return self::$db->rows($r);
	}
	
	public static function assoc($r) {
		return self::$db->assoc($r);
	}
	
	public static function select($cols,$from) {
		$args = array_slice(func_get_args(),2);
		return self::$db->select($cols,$from,$args);
	}
	
	public static function insert($t,$el) {
		return self::$db->insert($t,$el);
	}
	
	public static function update($t,$el) {
		$args = array_slice(func_get_args(),2);
		return self::$db->select($t,$el,$args);
	}
	
	public static function create($name,$dim=5) {
		return self::$db->create($name,$dim);
	}
}
include(__base_path.'admin/config/dbconfig.php');
?>