<?php
/**
 *	Database SQL module for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
//Define a constant
if(!defined('CURRENT'))
	define('CURRENT','ALECurTime');

//Abstract definition of a SQLDatabase
abstract class ALESQLDatabase extends ALEDatabase {
	
	//Escape of a string in current implementation of a SQLDatabse
	abstract function SQLEscape($q);
	
	//Convert a PHP variable in a format usable from SQL
	public function ConvertType($x) {
		switch (gettype($x)) {
			case 'boolean' 	: return ($x)? '1' : '0'; break;	//Boolean is converted in integer (string)
			case 'double' 	:
			case 'integer' 	: return strval($x); break;			//Integer and floats are converted in string
			case 'string' 	: 
				if ($x==CURRENT)
					return '"'.@date('Y-m-d H:i:s').'"';		//CURRENT costant are converted in current time string
			break;
			case 'NULL'		: return 'NULL'; break;				//NULL are converted in NULL string
			case 'array'	:
				if (isset($x['date']))
					return '"'.@date( 'Y-m-d', $x['date']).'"';	//a array containg a date is converted in usable format
				if (isset($x['dateTime']))
					return '"'.@date( 'Y-m-d H:i:s', $x['dateTime']).'"';	//a array containg a datetime is converted in usable format
				if (isset($x['time']))
					return '"'.@date( 'H:i:s', $x['time']).'"';	//a array containg a time is converted in usable format
			break;
			case 'object'	:
				switch(get_class($x)) {
					case 'DateTime' : return '"'.$x->format('Y-m-d H:i:s').'"';	break;	//a datetime object is converted in usable format
				}
			break;
		}
		return '"'.$this->SQLEscape(strval($x)).'"'; break;		//If no conversion is executed is converted to string and escaped
	}
	
	//Trasform a array to a query
	public function ArrayToQuery($arr) {
		foreach ($arr as $k=>$v)
			if (is_array($v)) {
				$nq='';
				//WHERE
				if (isset($v['WHERE'])) {
					$nq.=' WHERE ';
					foreach ($v['WHERE'] as $b)	{
						//odd elements of the array are the congiuntion between a clause and another clause
						if (is_string($b))
							$nq .= ' '.$b.' ';
						else {
							//Transform the query content (add prefix to table name and ect...)
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
				//LIMIT
				if (isset($v['LIMIT'])) {
					$nq .= ' LIMIT ';
					if (is_array($v['LIMIT']))
						$nq .= $v['LIMIT'][0].','.$v['LIMIT'][1];
					else
						$nq .= $v['LIMIT'];
				}
				//GROUP
				if (isset($v['GROUP'])) {
					$nq .= ' GROUP BY ';
					if (is_array($v['GROUP']))
						$nq .= implode(',',array_map(array($this,'in_apices'),$v['GROUP']));
					else
						$nq .=  $this->in_apices($v['GROUP']);
				}
				//ORDER
				if (isset($v['ORDER'])) {
					$nq .= ' ORDER ';
					if (is_array($v['ORDER'])) {
						$nq .= 'BY ';
						$last = array_pop($v['ORDER']);
						$nq .= implode(',',array_map(array($this,'in_apices'),$v['ORDER']));
						$nq .= ' '.$last;
					} else 
						$nq .= (substr($v['ORDER'], 0,3) == 'BY ')?$v['ORDER']:'BY '.$this->in_apices($v['ORDER']);
				}
				//if is transformed, the array become a string
				if ($nq!='')
					$arr[$k] = $nq;
			}
		return $arr;
	}
	
	//Create a new table
	public function create($name,$dim=5) {
		return new ALEMySQLTable($name,$dim,true,$this);
	}
	
	//Create a query
	public function create_query($argv) {
	    $argc = count($argv);
		$q = '';
		for ($i = 0; $i < $argc; $i++) { 
			if ($i&1) {
				//odd elements are converted
				if (is_array($argv[$i])) {
					//Process every single element of the array and add to the query separated by a comma
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
	
	//Create a insert query and execute
	public function insert($t,$el) {
		$camps = '';
		$values = array();
		$i=0;
		//Process colums and create a array of the values
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
		//Process values
		$tot = count($values);
		for ($j=0;$j<$tot;$j++) {
			for ($x=0;$x<$i;$x++)
				if (!isset($values[$j][$x]))
					$values[$j][$x] = '""';
			$values[$j] = '('.implode(',',$values[$j]).')';
		}
		//Add prefix to table name and unite with the elements processed
		if ($this->query('INSERT INTO '.$this->in_apices(($this->pre).$t).' ('.substr($camps,0,-1).') VALUES '.implode(',',$values)))
			return $this->last_insert();
		else
			return false;
	}
	
	//Create a update query and execute
	public function update($t,$el,$arr) {
		$set = array();
		foreach ($el as $k=>$v)
			$set[] = $this->in_apices($k).' = '.$this->ConvertType($v);
		//Add prefix to table name and unite with the elements processed
		return $this->query('UPDATE '.$this->in_apices(($this->pre).$t).' SET '.implode(' , ',$set).' '.($this->create_query($arr)));
	}
	
	//Create a delete query and execute
	public function delete($from,$arr) {
		$set = array();
		if (is_array($from)) {
			$fr='';
			foreach($from as $f)
				$fr.=$this->in_apices(($this->pre).$f).',';
			$from=substr($fr,0,-1);
		} else
			$from=$this->in_apices(($this->pre).$from);
		//Add prefix to table name and unite with the elements processed
		return $this->query('DELETE FROM '.$from.' '.($this->create_query($arr)));
	}
	
	//Create a select query and execute
	public function select($cols,$from,$arr) {
		$set = array();
		//Columns to use in the query
		if (is_array($cols)) {
			foreach ($cols as $k=>$v) {
				//if the second camp is an array
				if (is_array($v[1])) {
					$vr = array();
					//add the table name with prefix (in the first camp) before every element
					foreach ($v[1] as $a)
						$vr[] = $this->in_apices(($this->pre).$v[0]).'.'.$a;
					//implode with a comma
					$cols[$k] = implode(',',$vr);
				} else
					$cols[$k] = $this->in_apices(($this->pre).$v[0]).'.'.$v[1];
				//if exists a four camp, all data processed as inserted in the 4° camp (example : MAX(pre__products.price))
				if (isset($v[3]))
					$cols[$k] = $v[3].'('.$cols[$k].')';
				//if exists a third camp, add AS in the end
				if (isset($v[2]))
					$cols[$k] .= ' AS '.$this->in_apices($v[2]);
			}
			//Implode all processed data with a comma
			$cols = implode(',',$cols);
		}
		//Process tables used in the query
		if (is_array($from)) {
			$fr='';
			//Add prefix to every table
			foreach($from as $f)
				$fr.=$this->in_apices(($this->pre).$f).',';
			$from=substr($fr,0,-1);
		} else
			$from=$this->in_apices(($this->pre).$from);
		//Add prefix to table name and unite with the elements processed
		return $this->query('SELECT '.$cols.' FROM '.$from.' '.($this->create_query($arr)));
	}

	//Read a table and return an ALETable
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