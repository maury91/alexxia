<?php
/**
 *	PHP Syntax checker for ALExxia
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
//If the original php function is not present
if (!function_exists('php_check_syntax')) {
	//Homemade version of the php_check_syntax function
	function php_check_syntax($file) {
		//Prevent output
		ob_start ();
		//Evalutatue the script
		$val = (eval('return true; if(0){?>'.file_get_contents($file).'<?php };'));
		//Clear output
		ob_end_clean();
		//Return true if the script was executed
		return (is_bool($val)&&$val);
	}
}
//Class that check the sintax of PHP,
class ALECHECK {
	static public function PHP ($content) {
		return php_check_syntax($content);
	}
}
?>