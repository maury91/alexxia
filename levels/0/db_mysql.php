<?php
include_once('db_SQL.php');

class ALEmysql extends ALESQLDatabase {

	public function SQLEscape($q) {
		return mysql_real_escape_string($q);
	}
	
	public function connect() {
		if ($this->connection==null)
			$this->connection = mysql_connect($this->h,$this->u,$this->p);
			mysql_select_db($this->db,$this->connection);
	}
	
	public function query($q) {
		$this->connect();
		return mysql_query($q,$this->connection);
	}	
	
	public function error() {
		return mysql_error($this->connection);
	}
	
	public function rows($r) {
		return mysql_num_rows($r);
	}
	
	public function assoc($r) {
		return mysql_fetch_assoc($r);
	}

}
?>