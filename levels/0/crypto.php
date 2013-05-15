<?php
/**
 *	CRYPT module for ALExxia
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
//This class implements this crypt algoritms : blowfish(hash)
class CRYPT {	
	const BF_STRENGH = 8;
	
	public static function BF($val,$strength=0) {
		if ($strength<4||$strength>31) $strength=self::BF_STRENGH;
		$salt = '$2a$'.str_pad($strength,2,'0',STR_PAD_LEFT).'$'.(RAND::word(22));
		return crypt($val,$salt);
	}
	
	public static function is_BF($val) {
		return substr($val, 0, 4)=='$2a$';
	}
	
	public static function BF_check($original,$crypted) {
		if (is_bf($crypted))
			return crypt($original, $crypted) == $crypted;
		else
			return false;
	}
}
?>