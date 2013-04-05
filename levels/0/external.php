<?php
/*
	Lettura di variabili esterne
*/
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
	public static function exists($var) {
		return isset($_GET[$var]);
	}
	public static function val($var) {
		self::restore();
		return isset($_GET[$var])?$_GET[$var]:false;
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
	public static function exists($var) {
		return isset($_POST[$var]);
	}
	public static function val($var) {
		self::restore();
		return isset($_POST[$var])?$_POST[$var]:false;
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
	
	public static function set($name,$value,$duration=0) {
		if ($duration)
			return setcookie($name,$value,time()+$duration*60,__http_path,'',false,true);
		else
			return setcookie($name,$value,0);
	}
	
	public static function extend($name) {
		self::set($name,self::val($name),24*60);
	}
}
?>