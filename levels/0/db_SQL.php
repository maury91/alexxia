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
			$camps.= $this->in_apices($k).',';
			$j=0;
			if (is_array($v)) {
				$tot = count($v);
				for(;$j<$tot;$j++) {
					if ($v[$j]==NULL)
						$values[$j][$i] = 'NULL';
					else
						$values[$j][$i] = '"'.$this->SQLEscape($v[$j]).'"'; 
				}
			} else {
				if ($v==NULL)
					$values[0][$i] = 'NULL';
				else
					$values[0][$i] = '"'.$this->SQLEscape($v).'"';
			}
			$i++;
		}
		$tot = count($values);
		for ($j=0;$j<$tot;$j++) {
			for ($x=0;$x<$i;$x++)
				if (!isset($values[$j][$x]))
					$values[$j][$x] = '""';
			$values[$j] = '('.implode(',',$values[$j]).')';
		}
		$GLOBALS['query'] .= 'INSERT INTO '.$this->in_apices(($this->pre).$t).' ('.substr($camps,0,-1).') VALUES '.implode(',',$values)."\n";
		if ($this->query('INSERT INTO '.$this->in_apices(($this->pre).$t).' ('.substr($camps,0,-1).') VALUES '.implode(',',$values)))
			return $this->last_insert();
		else
			return false;
	}
	
	public function update($t,$el,$arr) {
		$set = array();
		foreach ($el as $k=>$v)
			$set[] = $this->in_apices($k).' = "'.$this->SQLEscape($v).'"';
		$GLOBALS['query'] .= 'UPDATE '.$this->in_apices(($this->pre).$t).' SET '.implode(' , ',$set).' '.($this->create_query($arr))."\n";
		return $this->query('UPDATE '.$this->in_apices(($this->pre).$t).' SET '.implode(' , ',$set).' '.($this->create_query($arr)));
	}
	
	public function delete($from,$arr) {
		$set = array();
		if (is_array($from)) {
			$fr='';
			foreach($from as $f)
				$fr.=$this->in_apices(($this->pre).$f).',';
			$from=substr($fr,0,-1);
		} else
			$from=$this->in_apices(($this->pre).$from);
		$GLOBALS['query'] .= 'DELETE FROM '.$from.' '.($this->create_query($arr))."\n";
		return $this->query('DELETE FROM '.$from.' '.($this->create_query($arr)));
	}
	
	public function select($cols,$from,$arr) {
		$set = array();
		if (is_array($from)) {
			$fr='';
			foreach($from as $f)
				$fr.=$this->in_apices(($this->pre).$f).',';
			$from=substr($fr,0,-1);
		} else
			$from=$this->in_apices(($this->pre).$from);
		$GLOBALS['query'] .= 'SELECT '.$cols.' FROM '.$from.' '.($this->create_query($arr))."\n";
		return $this->query('SELECT '.$cols.' FROM '.$from.' '.($this->create_query($arr)));
	}

	public function read($name) {
		//Lettura di una tabella
		$table_info = $this->query('SHOW COLUMNS FROM '.$this->in_apices($this->pre.$name));
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
		$table_info2 = $this->query('SHOW INDEX FROM '.$this->in_apices($name));
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