<?php
if(!defined('CURRENT'))
	define('CURRENT','ALECurTime');
	
abstract class ALESQLDatabase extends ALEDatabase {
	
	abstract function SQLEscape($q);
	abstract function ConvertType($el);
	abstract function ArrayToQuery($arr);
	
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
						$aux .= $this->ConvertType($x).',';
					$q .= trim($aux,",");
				} else
					$q .= $this->ConvertType($argv[$i]).' ';
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
			if (is_array($v)&&isset($v[0])) {
				$tot = count($v);
				for(;$j<$tot;$j++) 
					$values[$j][$i] = $this->ConvertType($v[$j]);
			} else
				$values[0][$i] = $this->ConvertType($v);
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
			$set[] = $this->in_apices($k).' = '.$this->ConvertType($v);
		$arr = $this->ArrayToQuery($arr);
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
		$arr = $this->ArrayToQuery($arr);
		$GLOBALS['query'] .= 'DELETE FROM '.$from.' '.($this->create_query($arr))."\n";
		return $this->query('DELETE FROM '.$from.' '.($this->create_query($arr)));
	}
	
	public function select($cols,$from,$arr) {
		$set = array();
		if (is_array($cols)) {
			foreach ($cols as $k=>$v) {
				if (is_array($v[1])) {
					$vr = array();
					foreach ($v[1] as $a)
						$vr[] = $this->in_apices(($this->pre).$v[0]).'.'.$a;
					$cols[$k] = implode(',',$vr);
				} else
					$cols[$k] = $this->in_apices(($this->pre).$v[0]).'.'.$v[1];
				if (isset($v[3]))
					$cols[$k] = $v[3].'('.$cols[$k].')';
				if (isset($v[2]))
					$cols[$k] .= ' AS '.$this->in_apices($v[2]);
			}
			$cols = implode(',',$cols);
		}
		if (is_array($from)) {
			$fr='';
			foreach($from as $f)
				$fr.=$this->in_apices(($this->pre).$f).',';
			$from=substr($fr,0,-1);
		} else
			$from=$this->in_apices(($this->pre).$from);
		$arr = $this->ArrayToQuery($arr);
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