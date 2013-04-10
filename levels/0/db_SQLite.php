<?php
include_once('db_SQL.php');

class ALESQLite extends ALESQLDatabase {

	public function __construct($db='',$pre='') {
		$this->db = $db;
		$this->pre = $pre;
	}
	
	public function create($name,$dim=5) {
		return new ALESQLTable($name,$dim,true,$this);
	}

	public function SQLEscape($q) {
		return sqlite_escape_string($q);
	}
	
	public function connect() {
		if ($this->connection==null)
			$this->connection = sqlite_open(__base_path.'config/'.($this->db).'.db');
	}
	
	public function query($q) {
		$this->connect();
		return sqlite_query($this->connection,$q);
	}	
	
	public function error() {
		return sqlite_last_error($this->connection);
	}
	
	public function rows($r) {
		return sqlite_num_rows($r);
	}
	
	public function assoc($r) {
		return sqlite_fetch_array($r, SQLITE_ASSOC);
	}

}
?>