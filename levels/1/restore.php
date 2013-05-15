<?php
/**
 *	Restore module for ALExxia
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

//This class restore a broken file
class RESTORE {
	//Translation string
	const $translate = array('string' => T_CONSTANT_ENCAPSED_STRING,'int' => T_LNUMBER,'bool' => T_STRING);

	//Process a file
	public static function file($name,$fullname) {
		//Open the structure file
		require __base_path.'struct/'.$name;
		//List the broken file
		$tokens = token_get_all(file_get_contents($fullname));
		$my_values = array();
		//Find the non broken values
		foreach ($__content as $k => $v) {
			$foundvar = false;
			$found_value = '';
			foreach($tokens as $a) {
				if ($foundvar) {
					if (($a[0] == $translate[$v])&&(($v!='bool')||($a[1]=='true')||($a[1]=='false'))) {
						$found_value=eval('return '.$a[1].';');
						break;
					}				
				} elseif (($a[0] == T_VARIABLE)&&($a[1] == '$'.$k))
					$foundvar = true;
			}
			if ($found_value==''&&$v=='int')
				$found_value=0;
			$my_values[$k] = $found_value;
		}
		//Write the repaired file
		PHP_WRITER::save($fullname,$my_values,$__content);
	}
}
?>