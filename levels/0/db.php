<?php
$GLOBALS['query'] = '';
abstract class ALEDatabase {

	protected $h,$u,$p,$db;
	public $pre;
	
	protected $connection=null;
	
	public function __construct($h,$u='',$p='',$db='',$pre='') {
		$this->h = $h;
		$this->u = $u;
		$this->p = $p;
		$this->db = $db;
		$this->pre = $pre;
	}
	
	public function q_rows($q) {
		return $this->rows($this->query($this->create_query(func_get_args())));
	}
	
	public function q_assoc($q) {
		return $this->assoc($this->query($this->create_query(func_get_args())));
	}
	
	//Metodi astratti
	
	abstract function connect();
	abstract function read($name);
	abstract function create_query($argv);
	abstract function query($q);
	abstract function error();
	abstract function rows($r);
	abstract function assoc($r);
	abstract function select($cols,$from,$arr);
	abstract function delete($from,$arr);
	abstract function insert($t,$el);
	abstract function update($t,$el,$arr);
	abstract function create($name,$dim=5);
	abstract function last_insert();
	abstract function in_apices($q);
}
class DB {

	protected static $db;
	public static $pre;
	
	public static function set_DB($db) {
		self::$db=$db;
		self::$pre=$db->pre;
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
	
	public static function delete($from) {
		$args = array_slice(func_get_args(),1);
		return self::$db->delete($from,$args);
	}
	
	public static function insert($t,$el) {
		return self::$db->insert($t,$el);
	}
	
	public static function update($t,$el) {
		$args = array_slice(func_get_args(),2);
		return self::$db->update($t,$el,$args);
	}
	
	public static function create($name,$dim=5) {
		return self::$db->create($name,$dim);
	}
}
include(__base_path.'config/dbconfig.php');
?>