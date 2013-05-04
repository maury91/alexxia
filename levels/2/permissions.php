<?php
class PERMISSION {
	static protected $perms=null;

	static protected function load() {
		if (self::$perms==null) {
			include(__base_path.'config/permissions.php');
			self::$perms = $__permissions;
		}
	}
	
	static public function has($perm) {
		self::load();
		if (isset(self::$perms[$perm]))
			return USER::level()<=self::$perms[$perm];
		else
			return false;
	}
}
?>