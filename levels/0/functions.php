<?php
/**
 *	Useful functions for ALExxia
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
class FUNCTIONS {

	//Return a array containg all directories in a directory
	public static function list_dir($directory) {
		//Declare a empty array
		$dirs= array();
		//Open the directory
		if ($handle = opendir($directory."/")) {
			//List directory
			while ($file = readdir($handle)) {
				//Check if is a directory
				if (is_dir($directory."/{$file}"))
					if ($file != "." & $file != "..") $dirs[] = $file;
			}
		}
		//Close directory
		closedir($handle);
		//Order directory array
		reset($dirs);
		sort($dirs);
		reset($dirs);
		return $dirs;
	}
	
	//Get the extension of a file
	public static function fext($filename) {
		//Estensione di un file
		$path_info = pathinfo($filename);
		if (isset($path_info['extension']))
			return strtolower($path_info['extension']);
		else
			return '';
	}
	
	//Return if is a valid email address
	public static function is_valid_email($email) {
		return preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email);
	}
}
?>