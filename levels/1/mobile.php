<?php
/**
 *	Mobile module for ALExxia
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

//This class return if a user is using a mobile device
class MOBILE {
	//Device found
	static protected $mobile=null;

	//Check if the user is using a mobile device
	public static function is_mobile() {
		//If is the first checking
		if (self::$mobile==null) {
			//If exists the variable nomob
			if (GET::exists('nomob')) {
				//Se the user is from a PC
				COOKIE::set('ale_mobile','false',30*24*60);
				self::$mobile=false;
			} else {
				//Check if was calculated the device of the user
				if (COOKIE::exists('ale_mobile'))
					self::$mobile=COOKIE::val('ale_mobile')=='true';	//Set the old value
				else {
					//Find what device is
					self::$mobile = false;
					//List of devices
					$devices = array(
							"Android" => "android.*mobile",
							"Androidtablet" => "android(?!.*mobile)",
							"Blackberry" => "blackberry",
							"Blackberrytablet" => "rim tablet os",
							"Iphone" => "(iphone|ipod)",
							"Ipad" => "(ipad)",
							"Palm" => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
							"Windows" => "windows ce; (iemobile|ppc|smartphone)",
							"Windowsphone" => "windows phone os",
							"Generic" => "(webos|android|kindle|mobile|mmp|midp|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini|opera mobi)"
						);
					//If is from a wap connection
					if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']))
						self::$mobile = true;
					elseif (strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') > 0 || strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') > 0) {
						//If accept a wap response is a mobile device
						self::$mobile = true;
					} else {
						//Control all the devices to find it
						foreach ($devices as $device => $regexp) 
							if(preg_match("/$regexp/i", $_SERVER['HTTP_USER_AGENT'])) 
								self::$mobile = true;	
					}
				}
				//Set a cookie to not calculate again
				COOKIE::set('ale_mobile',(self::$mobile)?'true':'false',30*24*60);
			}
		}
		return self::$mobile;
	}
}
?>