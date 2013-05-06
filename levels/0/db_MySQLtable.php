<?php
class ALEMySQLEnum {
	private $values=array(),$column;
	
	public function __construct($column) {
		$this->column = $column;
	}
	
	public function __toString() {
		return implode(',',array_map(array($this,"in_apices"),$this->values));
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
	
	private function in_apices($str) {
		return '`'.$str.'`';
	}
	
	public function end() {
		return $this->column;
	}
}

class ALEMySQLColumn {
	//Variabile di classe
	//array(dimension,unsigned,digits,charset,binary,def[-1:not have,0:int,1:real,2:date,3:time,4:timestamp,5:datetime,6:char])
	
	static private $prop_types=array('BIT'=>array(1,0,0,0,0,0),'TINYINT'=>array(1,1,0,0,0,0),'SMALLINT'=>array(1,1,0,0,0,0),'MEDIUMINT'=>array(1,1,0,0,0,0),'INT'=>array(1,1,0,0,0,0),'INTEGER'=>array(1,1,0,0,0,0),'BIGINT'=>array(1,1,0,0,0,0),'REAL'=>array(1,1,1,0,0,1),'DOUBLE'=>array(1,1,1,0,0,1),'FLOAT'=>array(1,1,1,0,0,1),'DECIMAL'=>array(1,1,1,0,0,1),'NUMERIC'=>array(1,1,1,0,0,1),'DATE'=>array(0,0,0,0,0,2),'TIME'=>array(0,0,0,0,0,3),'TIMESTAMP'=>array(0,0,0,0,0,4),'DATETIME'=>array(0,0,0,0,0,5),'YEAR'=>array(0,0,0,0,0,0),'CHAR'=>array(1,0,0,1,0,6),'VARCHAR'=>array(1,0,0,1,0,6),'BINARY'=>array(1,0,0,0,0,6),'VARBINARY'=>array(1,0,0,0,0,6),'TINYBLOB'=>array(0,0,0,0,0,-1),'BLOB'=>array(0,0,0,0,0,-1),'MEDIUMBLOB'=>array(0,0,0,0,0,-1),'LONGBLOB'=>array(0,0,0,0,0,-1),'TINYTEXT'=>array(0,0,0,1,1,-1),'TEXT'=>array(0,0,0,1,1,-1),'MEDIUMTEXT'=>array(0,0,0,1,1,-1),'LONGTEXT'=>array(0,0,0,1,1,-1),'BOOL'=>array(0,0,0,0,0,0),'BOOLEAN'=>array(0,0,0,0,0,0));
	
	private $enum_data=NULL;
	private $table;
	
	public  $name,$type='VARCHAR',$dimension=1,$digits=0,$unsigned=false,$not_null=false,$auto_increment=false,$zerofill=false,$default='',$update='';
	
	public function __construct($nome,$table) {
		$this->table = $table;
		$this->name = $nome;
	}	
	
	public function __toString() {
		if (($this->type=='ENUM')||($this->type=='SET'))
			$dim = (string)$this->enum_data;
		else
			$dim = ($this->dimension==1)?'':'('.( $this->dimension).(($this->digits)?','.($this->digits):'').')';
		return '`'.($this->name).'` '.($this->type).$dim.(($this->zerofill)?' ZEROFILL':'').(($this->unsigned)?' UNSIGNED':'').(($this->not_null)?' NOT NULL':' NULL').(($this->default=='')?'':' DEFAULT '.(((!is_string($this->default))||((self::$prop_types[$this->type][5]==4)&&($this->default=='CURRENT_TIMESTAMP')))?$this->default:'"'.($this->default).'"')).(($this->auto_increment)?' AUTO_INCREMENT':'');
	}
	
	public function end() {
		return $this->table;
	}
	
	public function type($tipo=NULL) {
		if ($tipo==NULL)
			return $this->type;
		if (($tipo=='ENUM')||($tipo=='SET')) {
			return $this->enum_data = new ALEMySQLEnum($this);
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
		if ($is) {
			if (self::$prop_types[$this->type][1])
				$this->unsigned=$is;
			else
				trigger_error('Can\'t be unsigned',E_USER_WARNING);
		}
		return $this;
	}
	
	public function not_null($not=true) {
		$this->not_null = $not;
		return $this;
	}
	
	public function primary() {
		$this->table->make_primary($this->name);
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
		if ($zero) {
			if (self::$prop_types[$this->type][1])
				$this->zerofill=$zero;
			else
				trigger_error('Can\'t be zerofill',E_USER_WARNING);
		}
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
	
	public function auto_increment($auto=true) {
		if (self::$prop_types[$this->type][1]) {
			$this->auto_increment = $auto;
			if ($auto)
				$this->primary();
		} else
			trigger_error('Can\'t set auto_increment',E_USER_WARNING);
		return $this;
	}
	
	public function from($table) {
		//Creazione collegamento tra le due tabelle
		$this->table->from_col($this,$table);
		return $this;
	}
}

class ALEMySQLTable {
	private $db,$isnew;
	private $primary=array(),$uniques=array(),$foreign=array();
	public $properties=array(),$from=array(),$has_many=array(),$sub_tables=array(),$added=array(),$changed=array();
	public $name;
	
	private function in_apices($str) {
		return '`'.$str.'`';
	}
	
	public function __construct($nome,$size,$new,$db) {
		$this->name = $nome;
		$this->isnew = $new;
		$this->db = $db;
		if ($new) {
			$column = new ALEMySQLColumn('id',$this);
			$this->properties = array('id' => $column);
			$column->type('INT')->dimension($size)->unsigned()->not_null()->auto_increment();
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
				$i = array_search($v,$this->primary);
				unset($this->primary[$i]);
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
			$col = $this->properties[$nome] = new ALEMySQLColumn($nome,$this);
			$col->type($id->type)->dimension($id->dimension)->unsigned($id->unsigned)->zerofill($id->zerofill);
			$this->foreign[] = array($nome,$table->name);
			$this->foreign = array_unique($this->foreign);
			if ($table->name!=$this->name)
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
		if (!in_array($col,$this->primary)) 
			$this->primary[] = $col;
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
		return $this->db->query('DROP TABLE `'.($this->db->pre.$this->name).'`');
	}
	
	public function save($overwrite=false) {
		if ($this->isnew) {
			$contents = '';
			foreach ($this->properties as $v)
				$contents .= $v.',';
			foreach ($this->uniques as $v)
				$contents .= ' UNIQUE('.implode(',',array_map(array($this,'in_apices'),$v)).'),';
			foreach ($this->foreign as $v)
				$contents .= 'CONSTRAINT `fk_'.$v[0].$this->db->pre.$this->name.'` FOREIGN KEY (`'.$v[0].'`) REFERENCES '.$this->db->pre.$v[1].'(`id`),';
			if (count($this->primary))
				$contents .= ' PRIMARY KEY('.implode(',',array_map(array($this,'in_apices'),$this->primary)).')';
			else
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
							$new_table = new ALEMySQLTable('NxN__'.($this->name).'x'.($table->name).'_'.(is_array($s_name)?$s_name[1]:'s').'x'.(is_array($v)?$v[1]:'s'),$this->dimension()+1,true,$this->db);
							$new_table
								->property($t_name)->type('INT')->dimension($table->dimension())->unsigned()->end()
								->property($this->name)->type('INT')->dimension($this->dimension())->unsigned()->end()
							->save(true);
							if ($this->name != $table->name) {							
								$new_table->property($s_name)->type('INT')->dimension($this->dimension())->unsigned();
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
						$new_table = new ALEMySQLTable('NxN__'.($v[0]->name).'_'.($this->name).'x'.($v[1]->name),$this->dimension()+1,true,$this->db);
						$new_table
							->property($this->name)->type('INT')->dimension($this->dimension())->unsigned()->end()
							->property($v[0])->end()
							->property($v[1]->name)->type('INT')->dimension($v[1]->dimension())->unsigned()->end()
						->save(true);
					}
				
				}
				$res = $this->db->q_rows('SHOW TABLES LIKE "'.($this->db->pre.$this->name).'"')>0;
				
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
					echo 'CREATE TABLE `'.($this->db->pre.$this->name).'` ('.$contents.')';
					if (!$this->db->query('CREATE TABLE `'.($this->db->pre.$this->name).'` ('.$contents.')')) {
						$ret = false;
						trigger_error('Query Error : "'.($this->db->error()).'"!',E_USER_WARNING);
					} else
						$ret = true;
				}
			} else
				trigger_error('Invalid Table!',E_USER_WARNING);
			return $ret;
		} else {
			$add = '';
			foreach ($this->added as $v)
				$add .= $this->properties[$v].',';
			if ($add!='')
				$add = ' ADD('.substr($add,0,-1).') ';
			$chn = '';
			foreach ($this->changed as $v)
				$chn .= $this->properties[$v].',';
			if ($chn!='')
				$chn = ' ADD('.substr($chn,0,-1).') ';
			if ($add.$chn=='')
				return true;
			if (!$this->db->query('ALTER TABLE `'.($this->db->pre.$this->name).'` '.$add.$chn)) {
				$ret = false;
				trigger_error('Query Error : "'.($this->db->error()).'"!',E_USER_WARNING);
			} else
				$ret = true;
		}
	}
	
	
	public function property($nome,$from_read=false) {
		if (is_object($nome)&&get_class($nome)=='ALEMySQLColumn') {
			$aext = isset($this->properties[$nome->name]);
			$this->properties[$nome->name] = new ALEMySQLColumn($nome->name,$this);
			if (!$this->isnew&&!$from_read) {
				if ($aext) {
					if (!isset($this->added[$nome->name]))
						$this->changed[$nome->name] = $nome->name;
				} else
					$this->added[$nome->name] = $nome->name;
			}
			return $this->properties[$nome->name]->type($nome->type)->dimension($nome->dimension)->unsigned($nome->unsigned)->zerofill($nome->zerofill)->not_null($nome->not_null);
		} elseif (isset($this->properties[$nome])) {
			return $this->properties[$nome];
		} else {
			$this->properties[$nome] = new ALEMySQLColumn($nome,$this);
			if (!$this->isnew&&!$from_read) {
				var_dump($from_read);
				var_dump($nome);
				$this->added[$nome] = $nome;
			}
			return $this->properties[$nome];
		}
	}
}
?>