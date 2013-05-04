<?php
include_once('db_SQL.php');

class ALESQLite3 extends ALESQLDatabase {
	
	public $prop_types = array('BIT'=>array(1,0,0,0,0,0),'TINYINT'=>array(1,0,0,0,0,0),'SMALLINT'=>array(1,0,0,0,0,0),'MEDIUMINT'=>array(1,0,0,0,0,0),'INT'=>array(1,0,0,0,0,0),'INTEGER'=>array(1,0,0,0,0,0),'BIGINT'=>array(1,0,0,0,0,0),'REAL'=>array(1,0,1,0,0,1),'DOUBLE'=>array(1,0,1,0,0,1),'FLOAT'=>array(1,0,1,0,0,1),'DECIMAL'=>array(1,0,1,0,0,1),'NUMERIC'=>array(1,0,1,0,0,1),'DATE'=>array(0,0,0,0,0,2),'TIME'=>array(0,0,0,0,0,3),'TIMESTAMP'=>array(0,0,0,0,0,4),'DATETIME'=>array(0,0,0,0,0,5),'YEAR'=>array(0,0,0,0,0,0),'CHAR'=>array(1,0,0,1,0,6),'VARCHAR'=>array(1,0,0,1,0,6),'BINARY'=>array(1,0,0,0,0,6),'VARBINARY'=>array(1,0,0,0,0,6),'TINYBLOB'=>array(0,0,0,0,0,-1),'BLOB'=>array(0,0,0,0,0,-1),'MEDIUMBLOB'=>array(0,0,0,0,0,-1),'LONGBLOB'=>array(0,0,0,0,0,-1),'TINYTEXT'=>array(0,0,0,1,1,-1),'TEXT'=>array(0,0,0,1,1,-1),'MEDIUMTEXT'=>array(0,0,0,1,1,-1),'LONGTEXT'=>array(0,0,0,1,1,-1),'BOOL'=>array(0,0,0,0,0,0),'BOOLEAN'=>array(0,0,0,0,0,0));

	public function __construct($db='',$pre='') {
		$this->db = $db;
		$this->pre = $pre;
	}
	
	public function in_apices($q) {
		return '\''.$q.'\'';
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
	
	public function last_insert() {
		return SQLite3::lastInsertRowID();
	}
	
	public function read($name) {
		//Lettura di una tabella
		$table_info = $this->query('SHOW COLUMNS FROM `'.$name.'`');
		$table = new ALESQLTable($name,0,$this,false);
		while ($r = $this->assoc($table_info)) {
			$type_d = explode(' ',$r['Type']);
			$type_d2 = explode('(',$type_d[0]);
			$column = $table->property($r['Field'])->type($type_d2[0]);
			if ($type_d2[1]!='')
				$column->dimension(substr($type_d2[1],0,-1));
			$c = count($type_d);
			for ($i=1;$i<$c;$i++) 
				switch($type_d[$i]) {
					case 'unsigned' : $column->unsigned(); break;
					case 'zerofill' : $column->zerofill(); break;
				}
			if ($r['Null'] == 'NO')
				$column->not_null();
			$extra = explode(' ',$r['Extra']);
			foreach($extra as $e)
				switch($e) {
					case 'auto_increment' : $column->auto_increment(); break;
				}
		}
		$table_info2 = $this->query('SHOW INDEX FROM `'.$name.'`');
		$uniques = array();
		while ($r = $this->assoc($table_info2)) {
			if ($r['Key_name'] != 'PRIMARY')
				$uniques[$r['Key_name']][] = $r['Column_name'];
		}
		foreach($uniques as $v)
			call_user_func_array(array($table, "make_unique"), $v);
		return $table;
	}

}
?>