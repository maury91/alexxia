<?php
include_once('db_SQL.php');

class ALEmysql extends ALESQLDatabase {

	public function SQLEscape($q) {
		$this->connect();
		return mysql_real_escape_string($q,$this->connection);
	}
	
	public function ConvertType($x) {
		switch (gettype($x)) {
			case 'boolean' 	: return ($x)? '1' : '0'; break;
			case 'double' 	:
			case 'integer' 	: return strval($x); break;
			case 'string' 	: 
				if ($x==CURRENT)
					return '"'.date('Y-m-d H:i:s').'"';
			break;
			case 'NULL'		: return 'NULL'; break;
			case 'array'	:
				if (isset($x['date']))
					return '"'.date( 'Y-m-d', $x['date']).'"';
				if (isset($x['dateTime']))
					return '"'.date( 'Y-m-d H:i:s', $x['dateTime']).'"';
				if (isset($x['time']))
					return '"'.date( 'H:i:s', $x['time']).'"';
			break;
			case 'object'	:
				switch(get_class($x)) {
					case 'DateTime' : return '"'.$x->format('Y-m-d H:i:s').'"';	break;
				}
			break;
		}
		return '"'.$this->SQLEscape(strval($x)).'"'; break;
	}
	
	public function ArrayToQuery($arr) {
		foreach ($arr as $k=>$v)
			if (is_array($v)) {
				$nq='';
				if (isset($v['WHERE'])) {
					$nq.=' WHERE ';
					$i = 0;
					foreach ($v['WHERE'] as $b)	{
						if ($i++&1)
							$nq .= ' '.$b.' ';
						else {
							if (isset($b[3])) {
								if (isset($b[4]))
									$nq .= $this->in_apices($this->pre.$b[3]).'.'.$this->in_apices($b[0]).' '.$b[1].' '.$this->in_apices($this->pre.$b[4]).'.'.$this->in_apices($b[2]);
								else
									$nq .= $this->in_apices($this->pre.$b[3]).'.'.$this->in_apices($b[0]).' '.$b[1].' '.$this->ConvertType($b[2]);
							} else 								
								$nq .= $this->in_apices($b[0]).' '.$b[1].' '.$this->ConvertType($b[2]);
						}
					}
				}
				if (isset($v['LIMIT'])) {
					$nq .= ' LIMIT ';
					if (is_array($v['LIMIT']))
						$nq .= $v['LIMIT'][0].','.$v['LIMIT'][1];
					else
						$nq .= $v['LIMIT'];
				}
				if (isset($v['GROUP'])) {
					$nq .= ' GROUP BY ';
					if (is_array($v['GROUP']))
						$nq .= implode(',',array_map(array($this,'in_apices'),$v['GROUP']));
					else
						$nq .=  $this->in_apices($v['GROUP']);
				}
				if (isset($v['ORDER'])) {
					$nq .= ' ORDER BY ';
					if (is_array($v['ORDER']))
						$nq .= implode(',',array_map(array($this,'in_apices'),$v['ORDER']));
					else
						$nq .=  $this->in_apices($v['ORDER']);
				}
				if ($nq!='')
					$arr[$k] = $nq;
			}
		return $arr;
	}
	
	public function in_apices($q) {
		return '`'.$q.'`';
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
	
	public function last_insert() {
		return mysql_insert_id();
	}

}
?>