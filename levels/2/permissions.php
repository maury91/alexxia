<?php
/*
	TODO : Permessi aggiuntivi
*/
/**
 *	Permissions for ALExxia
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

//This class check if the user have the permixxions for a determinate area
class PERMISSION {
	//Loaded perms
	static protected $perms=null;

	//Load the permissions
	static protected function load() {
		if (self::$perms==null) {
			include(__base_path.'config/permissions.php');
			self::$perms = $__permissions;
		}
	}
	
	//Check if the user have the permission to access the area
	static public function has($perm) {
		self::load();
		if (isset(self::$perms[$perm]))
			return USER::level()<=self::$perms[$perm];
		else
			return false;
	}
}
?>