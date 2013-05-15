<?php
/**
 *	Random for ALExxia
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

//This class return random values
class RAND {
	//Return a random binary string
	static public function bytes($len=3) {
		if (function_exists('openssl_random_pseudo_bytes'))
			return openssl_random_pseudo_bytes($len);
		$ret = '';
		for($i=0; $i < $len; $i++) 
			$ret .= chr(rand(1,255));
		return $ret;
	}
	
	//Return a random ascii string
	static public function word($len=5) {
		if (function_exists('openssl_random_pseudo_bytes'))
			return substr(strtr(base64_encode(openssl_random_pseudo_bytes(ceil($len/1.4))), '+', '.'),0,$len);
		$ret = '';
		$chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
		for($i=0; $i < $len; $i++) 
			$ret .= $chars[array_rand($chars)];
		return $ret;
	}

}
?>