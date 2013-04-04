<?php
class PLUGINS {
	protected static $plugins=null;

	public static function load() {
		if (self::$plugins==null) {
			include __base_path.'config/plugins.php';
			self::$plugins = $__plugins;
		}
	}
	
	public static function in($l1,$l2,$l3) {
		self::load();
		if (isset(self::$plugins[$l1][$l2][$l3]))
			return self::$plugins[$l1][$l2][$l3];
		else
			return array();
	}
}
?>