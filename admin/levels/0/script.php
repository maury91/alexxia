<?php
class INCLUDER {
	protected static $bpath='',$rpath='';

	public static function base_path($path) {
		self::$bpath=$path;
	}
	
	public static function rel_path($path) {
		self::$rpath=$path;
	}

}
class SCRIPT extends INCLUDER{
	private static $scripts=array();
	
	public static function add($script,$rpath=null,$bpath=null) {
		self::$scripts[] = (($bpath!=null)?$bpath:self::$bpath).(($rpath!=null)?$rpath:self::$rpath).$script;
	}
	
	public static function get() {
		return self::$scripts;
	}
}
class STYLE extends INCLUDER{
	private static $styles=array();
	
	public static function add($style,$rpath=null,$bpath=null) {
		self::$styles[] = (($bpath!=null)?$bpath:self::$bpath).(($rpath!=null)?$rpath:self::$rpath).$style;
	}
	
	public static function get() {
		return self::$styles;
	}
}

?>