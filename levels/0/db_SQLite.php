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
	
	public function in_apices($q) {
		return '\''.$q.'\'';
	}
	
	public function connect() {
		if ($this->connection==null)
			$this->connection = sqlite_open(__base_path.'config/'.($this->db).'.db');
	}
	
	public function query($q) {
		$this->connect();
		$this->queryes[] = $q;
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
	
	public function last_insert() {
		return sqlite_last_insert_rowid();
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