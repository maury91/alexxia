<?php
/**
 *	Database MySQL module for ALExxia
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
//MySQL derived from SQL
include_once('db_SQL.php');

//Class that use a MySQL DB
class ALEmysql extends ALESQLDatabase {

	//Return a escaped string in mysql format
	public function SQLEscape($q) {
		$this->connect();
		return mysql_real_escape_string($q,$this->connection);
	}
	
	//Return a string between `apices`
	public function in_apices($q) {
		return '`'.$q.'`';
	}
	
	//Database connection
	public function connect() {
		if ($this->connection==null)
			$this->connection = mysql_connect($this->h,$this->u,$this->p);
			mysql_select_db($this->db,$this->connection);
	}
	
	//Execute a query
	public function query($q) {
		$this->connect();
		$this->queryes[] = $q;
		return mysql_query($q,$this->connection);
	}	
	
	//Return last error
	public function error() {
		return mysql_error($this->connection);
	}
	
	//Enum rows
	public function rows($r) {
		return mysql_num_rows($r);
	}
	
	//Return a array from a query result
	public function assoc($r) {
		return mysql_fetch_assoc($r);
	}
	
	//Return the id of the last row inserted
	public function last_insert() {
		return mysql_insert_id();
	}
}
?>