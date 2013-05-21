<?php
/**
 *	External variables class for ALExxia
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
class External {	
	public static function restore(&$restored) {
		if((function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc())||ini_get('magic_quotes_sybase'))		
			foreach($restored as $k => $v) $restored[$k] = stripslashes($v);
	}
}

class GET extends External {
	protected static $notrestored=true;
	
	public static function restore() {
		if (self::$notrestored) {
			self::$notrestored=false;
			parent::restore($_GET);
		}
	}
	public static function exists() {
		$a = func_get_args();
		$tr = $_GET;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return true;
	}
	public static function val($var) {
		self::restore();
		$a = func_get_args();
		$tr = $_GET;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return $tr;
	}
	public static function int($var) {
		self::restore();
		$a = func_get_args();
		$tr = $_GET;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return intval($tr);
	}
}

class POST extends External {
	protected static $notrestored=true;
	
	public static function restore() {
		if (self::$notrestored) {
			self::$notrestored=false;
			parent::restore($_POST);
		}
	}
	public static function exists() {
		$a = func_get_args();
		$tr = $_POST;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return true;
	}
	public static function val($var) {
		self::restore();
		$a = func_get_args();
		$tr = $_POST;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return $tr;
	}
	public static function int($var) {
		self::restore();
		$a = func_get_args();
		$tr = $_POST;
		foreach ($a as $v) {
			if (!isset($tr[$v]))
				return false;
			$tr = $tr[$v];
		}
		return intval($tr);
	}
}

class COOKIE extends External {
	protected static $notrestored=true;
	
	public static function restore() {
		if (self::$notrestored) {
			self::$notrestored=false;
			parent::restore($_COOKIE);
		}
	}
	
	public static function val($var) {
		self::restore();
		return isset($_COOKIE[$var])?$_COOKIE[$var]:false;
	}
	
	public static function exists($var) {
		return isset($_COOKIE[$var]);
	}
	
	public static function set($name,$value='',$duration=0) {
		if ($duration)
			return setcookie($name,$value,time()+$duration*60,dirname(__http_path),($_SERVER['SERVER_NAME']=='localhost')?'':$_SERVER['SERVER_NAME'],false,false);
		else
			return setcookie($name,$value,0);
	}
	
	public static function extend($name) {
		self::set($name,self::val($name),24*60);
	}
}
?>