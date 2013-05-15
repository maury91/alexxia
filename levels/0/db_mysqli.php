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
//MySQLi derived from SQL
include_once('db_SQL.php');

class ALEmysqli extends ALESQLDatabase {

	//Return a escaped string in mysql format
	public function SQLEscape($q) {
		return mysqli_real_escape_string($q);
	}
	
	//Return a string between `apices`
	public function in_apices($q) {
		return '`'.$q.'`';
	}
	
	//Database connection
	public function connect() {
		if ($this->connection==null)
			$this->connection = new mysqli($this->h,$this->u,$this->p,$this->db);
	}
	
	//Execute a query
	public function query($q) {
		$this->connect();
		$this->queryes[] = $q;
		return $this->connection->query($q);
	}
	
	//Return last error
	public function error() {
		return $this->connection->error;
	}
	
	//Enum rows
	public function rows($r) {
		return $r->num_rows;
	}
	
	//Return a array from a query result
	public function assoc($r) {
		return $r->fetch_array(MYSQLI_ASSOC);
	}
	
	//Return the id of the last row inserted
	public function last_insert() {
		return mysqli_insert_id();
	}
}
?>