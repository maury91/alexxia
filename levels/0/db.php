<?php

abstract class ALEDatabase {

	protected static $h,$u,$p,$db,$pre;
	
	protected static $connection=null;
	
	public static function params($h,$u,$p,$db,$pre='') {
		self::$h = $h;
		self::$u = $u;
		self::$p = $p;
		self::$db = $db;
		self::$pre = $pre;
	}
	
	public static function create($name,$dim=5) {
		return new ALETable($name,$dim,true);
	}
	
	public static function q_rows($q) {
		return static::rows(static::query(self::create_query(func_get_args())));
	}
	
	public static function q_assoc($q) {
		return static::assoc(static::query(self::create_query(func_get_args())));
	}
	
	//Metodi astratti
	
	abstract static function connect();
	abstract static function read($name);
	abstract static function create_query($argv);
	abstract static function query();
	abstract static function error();
	abstract static function rows($r);
	abstract static function assoc($r);
	abstract static function select($cols,$from);
	
}
include(__base_path.'config/dbconfig.php');
include(__base_path.'levels/0/db_'.$dbtype.'.php');
?>