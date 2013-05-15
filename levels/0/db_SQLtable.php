<?php
/**
 *	Database Table abstraction module (SQL) for ALExxia
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
if(!defined('CURRENT'))
	define('CURRENT','ALECurTime');

function in_apices($str) {
	return '\''.$str.'\'';
}

class ALESQLEnum {
	private $values=array(),$column;
	
	public function __construct($column) {
		$this->column = $column;
	}
	
	public function __toString() {
		return implode(',',array_map('in_apices',$this->values));
	}
	
	public function add() {
		$this->values = array_merge($this->values,func_get_args());
		return $this;
	}
	
	public function remove() {
		foreach (func_get_args() as $v) {
			$i = array_search($v,$this->values);
			if ($i === false)
				trigger_error($v.' don\'t exists !',E_USER_WARNING);
			else
				unset($this->values[$i]);
		}
	}
	
	public function end() {
		return $this->column;
	}
}

class ALESQLColumn {
	//Variabile di classe
	//array(dimension,nada,digits,charset,binary,def[-1:not have,0:int,1:real,2:date,3:time,4:timestamp,5:datetime,6:char])
	
	static private $prop_types=array('BIT'=>array(1,0,0,0,0,0),'TINYINT'=>array(1,1,0,0,0,0),'SMALLINT'=>array(1,1,0,0,0,0),'MEDIUMINT'=>array(1,1,0,0,0,0),'INT'=>array(1,1,0,0,0,0),'INTEGER'=>array(1,1,0,0,0,0),'BIGINT'=>array(1,1,0,0,0,0),'REAL'=>array(1,1,1,0,0,1),'DOUBLE'=>array(1,1,1,0,0,1),'FLOAT'=>array(1,1,1,0,0,1),'DECIMAL'=>array(1,1,1,0,0,1),'NUMERIC'=>array(1,1,1,0,0,1),'DATE'=>array(0,0,0,0,0,2),'TIME'=>array(0,0,0,0,0,3),'TIMESTAMP'=>array(0,0,0,0,0,4),'DATETIME'=>array(0,0,0,0,0,5),'YEAR'=>array(0,0,0,0,0,0),'CHAR'=>array(1,0,0,1,0,6),'VARCHAR'=>array(1,0,0,1,0,6),'BINARY'=>array(1,0,0,0,0,6),'VARBINARY'=>array(1,0,0,0,0,6),'TINYBLOB'=>array(0,0,0,0,0,-1),'BLOB'=>array(0,0,0,0,0,-1),'MEDIUMBLOB'=>array(0,0,0,0,0,-1),'LONGBLOB'=>array(0,0,0,0,0,-1),'TINYTEXT'=>array(0,0,0,1,1,-1),'TEXT'=>array(0,0,0,1,1,-1),'MEDIUMTEXT'=>array(0,0,0,1,1,-1),'LONGTEXT'=>array(0,0,0,1,1,-1),'BOOL'=>array(0,0,0,0,0,0),'BOOLEAN'=>array(0,0,0,0,0,0));
	
	private $enum_data=NULL;
	private $table;
	
	public  $name,$type='VARCHAR',$dimension=1,$digits=0,$not_null=false,$auto_increment=false,$default='',$update='',$primary=false;
	
	public function __construct($nome,$table) {
		$this->table = $table;
		$this->name = $nome;
	}	
	
	public function __toString() {
		if (($this->type=='ENUM')||($this->type=='SET'))
			$dim = (string)$this->enum_data;
		else
			$dim = ($this->dimension==1)?'':'('.( $this->dimension).(($this->digits)?','.($this->digits):'').')';
		return in_apices($this->name).' '.($this->type).$dim.(($this->not_null)?' NOT NULL':' NULL').(($this->default=='')?'':' DEFAULT '.(((!is_string($this->default))||((self::$prop_types[$this->type][5]==4)&&($this->default=='CURRENT_TIMESTAMP')))?$this->default:'"'.($this->default).'"')).(($this->auto_increment||$this->primary)?' PRIMARY KEY':'');
	}
	
	public function end() {
		return $this->table;
	}
	
	public function type($tipo=NULL) {
		if ($tipo==NULL)
			return $this->type;
		if (($tipo=='ENUM')||($tipo=='SET')) {
			return $this->enum_data = new ALESQLEnum($this);
		}
		if (isset(self::$prop_types[strtoupper($tipo)]))
			$this->type = strtoupper($tipo);
		else
			trigger_error('Type not exists!',E_USER_WARNING);
		return $this;
	}
	
	public function dimension($dim=NULL) {
		if ($dim==NULL) 
			return $this->dimension;
		if (is_numeric($dim)) {
			if (self::$prop_types[$this->type][0])
				$this->dimension = $dim;
		} else
			trigger_error('Not Valid Dimension',E_USER_WARNING);
		return $this;
	}
	
	public function unsigned($is=true) {
		return $this;
	}
	
	public function not_null($not=true) {
		$this->not_null = $not;
		return $this;
	}
	
	public function primary() {
		$this->primary=true;
		return $this;
	}
	
	public function unique($uniq=true) {
		if ($uniq)
			$this->table->make_unique($this->name);
		else
			$this->table->rem_unique($this->name);
		return $this;
	}
	
	public function zerofill($zero=true) {
		return $this;
	}
	
	public function digits($dig) {
		if ($dig!=1) {
			if (is_numeric($dig)) {
				if (self::$prop_types[$this->type][3])
					$this->digits = $dig;
			} else
				trigger_error('Not Valid Dimension',E_USER_WARNING);
		}
		return $this;
	}
	
	public function set_default($val) {
		$t_id = self::$prop_types[$this->type][5];
		if ($t_id > -1) {
			if (($val==CURRENT)&&($t_id==4))
				$this->default = 'CURRENT_TIMESTAMP';
			elseif (is_numeric($val)) {
				if ($t_id==0) $this->default = round($val);
				elseif ($t_id==1) $this->default = (float)$val;
				else trigger_error('Not Valid Number',E_USER_WARNING);
			} elseif ($t_id>1) {
				$this->default = (string)$val;
				//TODO:Distinzione tra i valori Char,Date,DateTime,TimeStamp
			} else
				trigger_error('Assertion not valid',E_USER_WARNING);
		}
		return $this;
	}
	
	public function set_update($val) {
		$t_id = self::$prop_types[$this->type][5];
		if ($t_id > -1) {
			if (($val==CURRENT)&&($t_id==4)) 
				$this->update = 'CURRENT_TIMESTAMP';
			elseif (is_numeric($val)) {
				if ($t_id==0) $this->update = round($val);
				elseif ($t_id==1) $this->update = (float)$val;
				else trigger_error('Not Valid Number',E_USER_WARNING);
			} elseif ($t_id>1) {
				$this->update = (string)$val;
				//TODO:Distinzione tra i valori Char,Date,DateTime,TimeStamp
			} else
				trigger_error('Assertion not valid',E_USER_WARNING);
		}
		return $this;
	}
	
	public function delete() {
		return $this->table->delete_col($this->name);
	}
	
	public function auto_increment() {
		$this->primary();
		return $this;
	}
	
	public function from($table) {
		//Creazione collegamento tra le due tabelle
		$this->table->from_col($this,$table);
	}
}

class ALESQLTable {
	private $db,$isnew;
	private $primary=array(),$uniques=array(),$foreign=array();
	public $properties=array(),$from=array(),$has_many=array(),$sub_tables=array();
	public $name;
	
	public function __construct($nome,$size,$new,$db) {
		$this->name = $nome;
		$this->isnew = $new;
		$this->db = $db;
		if ($new) {
			$column = new ALESQLColumn('id',$this);
			$this->properties = array('id' => $column);
			$column->type('INT')->dimension($size)->not_null()->primary();
		}
	}
	
	public function dimension() {
		return $this->properties['id']->dimension;
	}
	
	public function from_col($col,$table){
		$this->from[] = array($col,$table);
		unset($this->properties[$col->name]);
	}
	
	public function delete_col() {	
		foreach (func_get_args() as $v) {
			$i = array_search($v,$this->properties);
			if ($i === false)
				trigger_error($v.' don\'t exists !',E_USER_WARNING);
			else {
				unset($this->properties[$i]);
				//Search in primary and uniques
				foreach ($this->uniques as $k => $h) {
					$i = array_search($v,$h);
					unset($this->uniques[$k][$h]);
				}
			}
		}
		return $this;
	}
	
	public function belongs_to() {
		//Creazione
		$tables = func_get_args();
		foreach ($tables as $table) {
			$id = $table->get('id');
			$nome = ($table->name).'_ref';
			$col = $this->properties[$nome] = new ALESQLColumn($nome,$this);
			$col->type($id->type)->dimension($id->dimension)->zerofill($id->zerofill)->not_null($id->not_null);
			$this->foreign[] = array($nome,$table->name);
			$this->foreign = array_unique($this->foreign);
			$table->has_many[$this->name] = $this;
		}
		return $this;
	}
	
	public function get($n) {
		return $this->properties[$n];
	}
	
	public function has_many() {
		$tables = func_get_args();
		foreach ($tables as $table) {
			if (is_array($table)) {
				foreach($table as $k=>$v)
					$this->has_many[$v->name] = array($v,$k);
			} else
				$this->has_many[$table->name] = $table;
		}
		return $this;
	}
	
	public function make_primary($col) {
		$this->properties[$col]->primary();
		return $this;
	}
	
	public function make_unique() {
		$this->uniques[] = func_get_args();
		$this->uniques = array_unique($this->uniques);
		return $this;
	}
	
	public function rem_unique() {
		$i = array_search(func_get_args());
		if ($i === FALSE)
			trigger_error('Uniques not founded',E_USER_WARNING);
		else
			unset($this->uniques[$i]);
		return $this;
	}
	
	public function drop() {
		foreach ($this->has_many as $v) {
			if (is_array($v)) {
				if ($v[0]->name!=$this->name)
					$v[0]->drop();
			} else {
				if ($v->name!=$this->name)
					$v->drop();
			}
		}
		return $this->db->query('DROP TABLE '.in_apices($this->name));
	}
	
	public function save($overwrite=false) {
		$contents = '';
		foreach ($this->properties as $v)
			$contents .= $v.',';
		foreach ($this->uniques as $v)
			$contents .= ' UNIQUE('.implode(',',array_map('in_apices',$v)).'),';
		foreach ($this->foreign as $v)
			$contents .= 'CONSTRAINT '.in_apices('foreign_key_'.$v[0]).' FOREIGN KEY ('.in_apices($v[0]).') REFERENCES '.$v[1].'('.in_apices('id').'),';
		$contents = substr($contents,0,-1);
		if ($contents!='') {
			//if ($this->sub_tables[] )
			foreach ($this->has_many as $v) {
				$table = is_array($v)?$v[0]:$v;
				$t_name = is_array($v)?$v[1]:$v->name;
				//Controllo esistenza di has_many verso questa tabella
				if (!isset($this->sub_tables[$t_name])) {
					if (isset($table->has_many[$this->name])) {
						if ($this->name != $table->name) {
							$s_name = $table->has_many[$this->name];
							$s_name = is_array($s_name)?$s_name[1]:$s_name->name;
						} else
							$s_name = $this->name;
						$new_table = new ALESQLTable('NxN__'.($this->name).'x'.($table->name).'_'.$s_name.'x'.$t_name,$this->dimension()+1,$this->db);
						$new_table
							->property($t_name)->type('INT')->dimension($table->dimension())->not_null()->end()
							->property($this->name)->type('INT')->dimension($this->dimension())->not_null()->end()
						->save();
						if ($this->name != $table->name) {							
							$new_table->property($s_name)->type('INT')->dimension($this->dimension())->not_null();
							$table->sub_tables[$s_name] = $new_table;
						}
						$this->sub_tables[$t_name] = $new_table;
					} else {
						$table->belongs_to($this);
						$this->sub_tables[$t_name] = $table;
					}
				}
			}
			foreach ($this->from as $v) {
				if (!isset($this->sub_table['sup_'.($v[0]->name).'x'.($v[1]->name)])) {
					//Campi
					$new_table = new ALESQLTable('NxN__'.($v[0]->name).'_'.($this->name).'x'.($v[1]->name),$this->dimension()+1,$this->db);
					$new_table
						->property($this->name)->type('INT')->dimension($this->dimension())->not_null()->end()
						->property($v[0])->end()
						->property($v[1]->name)->type('INT')->dimension($v[1]->dimension())->not_null()->end()
					->save();
				}
			
			}
			$res = $this->db->q_rows('SELECT name FROM sqlite_master WHERE type='.in_apices('table').' AND name LIKE "'.($this->db->pre.$this->name).'"')>0;
			
			if ($res&&$overwrite) {
				//Eliminazione tabelle con dipendenze da questa
				if ($this->drop())
					$res = false;
				else
					trigger_error('Query Error : "'.($this->db->error()).'"!',E_USER_WARNING);
			}
			if ($res) {
				//Alterazione
				$ret = true;
			} else{
				if (!$this->db->query('CREATE TABLE '.in_apices($this->db->pre.$this->name).' ('.$contents.')')) {
					$ret = false;
					trigger_error('Query Error : "'.($this->db->error()).'"!',E_USER_WARNING);
				} else
					$ret = true;
			}
		} else
			trigger_error('Invalid Table!',E_USER_WARNING);
		return $ret;
	}
	
	public function property($nome) {
		if (is_object($nome)&&get_class($nome)=='ALESQLColumn') {
			$this->properties[$nome->name] = new ALESQLColumn($nome->name,$this);
			return $this->properties[$nome->name]->type($nome->type)->dimension($nome->dimension)->not_null($nome->not_null);
		} elseif (isset($this->properties[$nome])) {
			return $this->properties[$nome];
		} else {
			$this->properties[$nome] = new ALESQLColumn($nome,$this);
			return $this->properties[$nome];
		}
	}
}
?>