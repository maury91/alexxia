<?php
include_once('db_SQL.php');

class ALEmysqli extends ALESQLDatabase {

	public function SQLEscape($q) {
		return mysqli_real_escape_string($q);
	}
	
	public function connect() {
		if ($this->connection==null)
			$this->connection = new mysqli($this->h,$this->u,$this->p,$this->db);
	}
	
	public function query($q) {
		$this->connect();
		return $this->connection->query($q);
	}
	
	public function error() {
		return $this->connection->error;
	}
	
	public function rows($r) {
		return $r->num_rows;
	}
	
	public function assoc($r) {
		return $r->fetch_array(MYSQLI_ASSOC);
	}
}
?>