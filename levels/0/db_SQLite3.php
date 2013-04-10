<?php
include_once('db_SQL.php');

class ALESQLite3 extends ALESQLDatabase {
	
	public $prop_types = array('BIT'=>array(1,0,0,0,0,0),'TINYINT'=>array(1,0,0,0,0,0),'SMALLINT'=>array(1,0,0,0,0,0),'MEDIUMINT'=>array(1,0,0,0,0,0),'INT'=>array(1,0,0,0,0,0),'INTEGER'=>array(1,0,0,0,0,0),'BIGINT'=>array(1,0,0,0,0,0),'REAL'=>array(1,0,1,0,0,1),'DOUBLE'=>array(1,0,1,0,0,1),'FLOAT'=>array(1,0,1,0,0,1),'DECIMAL'=>array(1,0,1,0,0,1),'NUMERIC'=>array(1,0,1,0,0,1),'DATE'=>array(0,0,0,0,0,2),'TIME'=>array(0,0,0,0,0,3),'TIMESTAMP'=>array(0,0,0,0,0,4),'DATETIME'=>array(0,0,0,0,0,5),'YEAR'=>array(0,0,0,0,0,0),'CHAR'=>array(1,0,0,1,0,6),'VARCHAR'=>array(1,0,0,1,0,6),'BINARY'=>array(1,0,0,0,0,6),'VARBINARY'=>array(1,0,0,0,0,6),'TINYBLOB'=>array(0,0,0,0,0,-1),'BLOB'=>array(0,0,0,0,0,-1),'MEDIUMBLOB'=>array(0,0,0,0,0,-1),'LONGBLOB'=>array(0,0,0,0,0,-1),'TINYTEXT'=>array(0,0,0,1,1,-1),'TEXT'=>array(0,0,0,1,1,-1),'MEDIUMTEXT'=>array(0,0,0,1,1,-1),'LONGTEXT'=>array(0,0,0,1,1,-1),'BOOL'=>array(0,0,0,0,0,0),'BOOLEAN'=>array(0,0,0,0,0,0));

	public function __construct($db='',$pre='') {
		$this->db = $db;
		$this->pre = $pre;
	}
	
	public function create($name,$dim=5) {
		return new ALESQLTable($name,$dim,true,$this);
	}
	
	public function SQLEscape($q) {
		return SQLite3::escapeString($q);
	}
	
	public function connect() {
		if ($this->connection==null)
			$this->connection = new SQLite3(__base_path.'config/'.($this->db).'.db3');
	}
	
	public function query($q) {
		$this->connect();
		return $this->connection->query($q);
	}	
	
	public function error() {
		return $this->connection->lastErrorMsg();
	}
	
	public function rows($r) {
		$count = 0;
		while (list() = $r->fetchArray(SQLITE3_ASSOC)) 
			$count++;
		$r->reset();
		return $count;
	}
	
	public function assoc($r) {
		return $r->fetchArray(SQLITE3_ASSOC);
	}

}
?>