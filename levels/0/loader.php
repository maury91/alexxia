<?php
/**
 *	Class Auto-loader for ALExxia
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

function level_0_autoload($class) {
	switch($class) {
		case 'DB' : 
			include __base_path.'levels/0/db.php'; break;
		case 'GET' :
		case 'POST' :
		case 'COOKIE' :
			include __base_path.'levels/0/external.php'; break;
		case 'RAND' :
			include __base_path.'levels/0/rand.php'; break;
		case 'CRYPT' :
			include __base_path.'levels/0/crypto.php'; break;
		case 'PHP_WRITER' :
			include __base_path.'levels/0/php_writer.php'; break;
		case 'ALECHECK' :
			include __base_path.'levels/0/alecheck.php'; break;
		case 'HTML' :
			include __base_path.'levels/0/html.php'; break;
		case 'ERRORS' :
			include __base_path.'levels/0/errors.php'; break;
		case 'FUNCTIONS' :
			include __base_path.'levels/0/functions.php'; break;
		case 'ALESQLTable' :
			include __base_path.'levels/0/db_SQLtable.php'; break;
		case 'ALEMySQLTable' :
			include __base_path.'levels/0/db_MySQLtable.php'; break;
	}
}
?>