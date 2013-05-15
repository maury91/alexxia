<?php
/**
 *	Database module for ALExxia
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
//Abstract definition of the class
abstract class ALEDatabase {
	//Queryes executed (for debug)
	public $queryes=array();

	//Connection data
	protected $h,$u,$p,$db;
	protected $connection=null;
	//Tables prefix
	public $pre;
	
	//Construction and connection of the class
	public function __construct($h,$u='',$p='',$db='',$pre='') {
		$this->h = $h;
		$this->u = $u;
		$this->p = $p;
		$this->db = $db;
		$this->pre = $pre;
	}
	
	//Execute a query and get the number of the rows
	public function q_rows($q) {
		$a = func_get_args();
		return $this->rows($this->query($this->create_query($a)));
	}
	
	//Execute a query and return one array
	public function q_assoc($q) {
		$a = func_get_args();
		return $this->assoc($this->query($this->create_query($a)));
	}
	
	//Connection function
	abstract function connect();
	//Read a table
	abstract function read($name);
	//Create a query
	abstract function create_query($argv);
	//Execute a query
	abstract function query($q);
	//Get last error
	abstract function error();
	//Enum rows
	abstract function rows($r);
	//Get a array from a query result
	abstract function assoc($r);
	//Build and execute a select query
	abstract function select($cols,$from,$arr);
	//Build and execute a delete query
	abstract function delete($from,$arr);
	//Build and execute a insert query
	abstract function insert($t,$el);
	//Build and execute a update query
	abstract function update($t,$el,$arr);
	//Create a table
	abstract function create($name,$dim=5);
	//Return the id of the last inserted row
	abstract function last_insert();
	//Return a string between the apices of the language used in the database
	abstract function in_apices($q);
}
//Static version of the class (primary DB)
class DB {
	//Database in use
	protected static $db;
	//Prefix fot the database in use
	public static $pre;
	
	//Return all queryes executed
	public static function debug() {
		return self::$db->queryes;
	}
	
	//Set Database to use
	public static function set_DB($db) {
		self::$db=$db;
		self::$pre=$db->pre;
	}
	
	//Function implemented from the abstrated version
	public static function q_rows($q) {
		return self::$db->q_rows($q);
	}
	
	public static function q_assoc($q) {
		return self::$db->q_assoc($q);
	}
	
	public static function read($n) {
		return self::$db->read($n);
	}
	
	public static function create_query($argv) {
		return self::$db->create_query($argv);
	}
	
	public static function query() {
		$args = func_get_args();
		return self::$db->query(self::$db->create_query($args));
	}
	
	public static function error() {
		return self::$db->error();
	}
	
	public static function rows($r) {
		return self::$db->rows($r);
	}
	
	public static function assoc($r) {
		return self::$db->assoc($r);
	}
	
	public static function select($cols,$from) {
		$args = array_slice(func_get_args(),2);
		return self::$db->select($cols,$from,$args);
	}
	
	public static function delete($from) {
		$args = array_slice(func_get_args(),1);
		return self::$db->delete($from,$args);
	}

	public static function insert($t,$el) {
		return self::$db->insert($t,$el);
	}
	
	public static function update($t,$el) {
		$args = array_slice(func_get_args(),2);
		return self::$db->update($t,$el,$args);
	}

	public static function create($name,$dim=5) {
		return self::$db->create($name,$dim);
	}

	//Semplified version of the select query
	public static function simple_select($cols,$from) {
		$args = array_slice(func_get_args(),2);
		return self::$db->select($cols,$from,self::$db->ArrayToQuery($args));
	}
	
	//Semplified version of the delete query
	public static function simple_delete($from) {
		$args = array_slice(func_get_args(),1);
		return self::$db->delete($from,self::$db->ArrayToQuery($args));
	}

	//Semplified version of the update query
	public static function simple_update($t,$el) {
		$args = array_slice(func_get_args(),2);
		return self::$db->update($t,$el,self::$db->ArrayToQuery($args));
	}
}
//Include configuration file (this file make a DB and assocciate the DB with the static class)
include(__base_path.'config/dbconfig.php');
?>