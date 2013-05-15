<?php
/**
 *	Plug-in module for ALExxia
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
class PLUGINS {
	//Avaible plugins
	protected static $plugins=null;

	//Load the list of plug-ins
	public static function load() {
		if (self::$plugins==null) {
			include __base_path.'config/plugins.php';
			self::$plugins = $__plugins;
		}
	}
	
	//Return the plug-ins for a specified zone
	public static function in($l1,$l2,$l3) {
		self::load();
		if (isset(self::$plugins[$l1][$l2][$l3]))
			return self::$plugins[$l1][$l2][$l3];
		else
			return array();
	}
}
?>