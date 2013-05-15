<?php
/**
 *	Globals vars for ALExxia
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

//This class manage global variables
class GLOBALS {
	//Loaded variables
	static protected $vars=null;
	
	//Obtain the variables from the configuration file
	static public function sec_load() {
		include(__base_path.'config/globals.php');
		self::$vars = get_defined_vars();
	}
	
	//Load the variabiles
	static public function load() {
		//If not loaded yet
		if (self::$vars==null) {
			//Check the configuration file is not corrupted
			$include = __base_path.'config/globals.php';
			if (ALECHECK::PHP($include))	{
				//Include the configuration file
				include($include);
				require __base_path.'struct/globals.php';
				$vars = get_defined_vars();
				//Check that exists all variables in
				foreach($__content as $k => $v)
					if (!isset($vars[$k])) {
						//If one of that don't exists restore the file
						RESTORE::file('globals.php',$include);
						include($include);
						break;
					}
			} else 
				RESTORE::file('globals.php',$include);	//If is corrupted restore the file
			//Load the file (after is checked and restored)
			self::sec_load();
		}
	}

	//Get the value of a variable
	static public function val($v) {
		self::load();
		return self::$vars[$v];
	}
}