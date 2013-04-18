<?php
abstract class ALESQLDatabase extends ALEDatabase {
	
	abstract function SQLEscape($q);
	
	public function create($name,$dim=5) {
		return new ALEMySQLTable($name,$dim,true,$this);
	}
	
	public function create_query($argv) {
	    $argc = count($argv);
		$q = '';
		for ($i = 0; $i < $argc; $i++) { 
			if ($i&1) {
				if (is_array($argv[$i])) {
					$aux = '';
					foreach($argv[$i] as $x)
						$aux .= '"'.$this->SQLEscape($x).'",';
					$q .= trim($aux,",");
				} else {
					$q .= '"'.$this->SQLEscape($argv[$i]).'" ';
				}
			} else
				$q .= $argv[$i];
		}
		return $q; 
	}
	
	public function insert($t,$el) {
		$camps = '';
		$values = array();
		$i=0;
		foreach ($el as $k => $v) {
			$camps.='`'.$k.'`,';
			$j=0;
			if (is_array($v)) {
				$tot = count($v);
				for(;$j<$tot;$j++)
					$values[$j][$i] = '"'.$this->SQLEscape($v[$j]).'"'; 
			} else
				$values[0][$i] = '"'.$this->SQLEscape($v).'"';
			$i++;
		}
		$tot = count($values);
		for ($j=0;$j<$tot;$j++) {
			for ($x=0;$x<$i;$x++)
				if (!isset($values[$j][$x]))
					$values[$j][$x] = '""';
			$values[$j] = '('.implode(',',$values[$j]).')';
		}
		return $this->query('INSERT INTO `'.($this->pre).$t.'` ('.substr($camps,0,-1).') VALUES '.implode(',',$values));
	}
	
	public function update($t,$el,$arr) {
		$set = array();
		foreach ($el as $k=>$v)
			$set[] = '`'.$k.'` = "'.$this->SQLEscape($v).'"';
		return $this->query('UPDATE `'.($this->pre).$t.'` SET '.implode(' AND ',$set).' '.($this->create_query($arr)));
	}
	
	public function select($cols,$from,$arr) {
		$set = array();
		return print('SELECT '.$cols.' FROM `'.($this->pre).$from.'` '.($this->create_query($arr)));
	}

	public function read($name) {
		//Lettura di una tabella
		$table_info = $this->query('SHOW COLUMNS FROM `'.$this->pre.$name.'`');
		$table = new ALEMySQLTable($name,0,false,$this);
		while ($r = $this->assoc($table_info)) {
			$type_d = explode(' ',$r['Type']);
			$type_d2 = explode('(',$type_d[0]);
			$column = $table->property($r['Field'],true)->type($type_d2[0]);
			if (isset($type_d2[1]))
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
		while ($r = @$this->assoc($table_info2)) {
			if ($r['Key_name'] != 'PRIMARY')
				$uniques[$r['Key_name']][] = $r['Column_name'];
		}
		foreach($uniques as $v)
			call_user_func_array(array($table, "make_unique"), $v);
		return $table;
	}

}
?>