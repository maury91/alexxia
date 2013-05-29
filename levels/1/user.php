<?php
/**
 *	User for ALExxia
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

//This class retrieve information of the user
class USER {
	//Current logged-in user
	protected static $user=null;

	//Load the user
	protected static function load($renew=true) {
		//If is not loaded yet
		if (self::$user===null) {
			//Search for a cookie of the user
			if (COOKIE::exists('ale_user')) {
				$nick = COOKIE::val('ale_user');
				//Retrieve data of the user from the database
				$user_data = DB::assoc(DB::select('*','users','WHERE nick = ',$nick));
				//Check the nick is valid
				if (strcmp($user_data['nick'],$nick)) {
					//If is not valid delete the currents cookies
					COOKIE::set('ale_user');
					COOKIE::set('ale_auth');
					self::$user=false;
				} else {
					//Hash and check the authcode
					if (CRYPT::BF_check(COOKIE::val('ale_auth'),$user_data['cookieCode'])) {
						//If is valid update last time the user visit the page
						DB::update('users',array('last'=>time()),'WHERE id = ',$user_data['id']);
						//Set the data of the user
						self::$user=$user_data;
						if ($renew) {
							//Extend the life of the cookies
							COOKIE::extend('ale_user');
							COOKIE::extend('ale_auth');
						}
					} else {
						//If is not valid delete the currents cookies
						COOKIE::set('ale_user');
						COOKIE::set('ale_auth');
						self::$user=false;
					}
				}
			} else
				self::$user=false;	//No cookies, no user logged-in
		}
	}
	
	//Set the user from a different log-in method
	public static function admin($usr) {
		self::$user = $usr;
	}
	
	//Check if the user is logged
	public static function logged($renew=true) {
		self::load($renew);
		return (self::$user===false)?false:true;
	}
	
	//Retrieve the level of the user
	public static function level($renew=true) {
		self::load($renew);
		return (self::$user===false)?10:self::$user['level'];
	}
	
	//Get a info about the user
	public static function data($k) {
		self::load();
		if (self::$user===false)
			return false;
		else
			return self::$user[$k];
	}

	//Logout user
	public static function logout() {
		self::$user=false;
	}
}
?>