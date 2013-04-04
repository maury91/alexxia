<?php
class GLOBALS {
	static protected $vars=null;
	
	static public function sec_load() {
		include(__base_path.'config/globals.php');
		self::$vars = get_defined_vars();
	}
	
	static public function load() {
		if (self::$vars==null) {
			$include = __base_path.'config/globals.php';
			if (ALECHECK::PHP($include))	{
				include($include);
				require __base_path.'struct/globals.php';
				$vars = get_defined_vars();
				foreach($__content as $k => $v)
					if (!isset($vars[$k])) {
						RESTORE::file('globals.php',$include);
						include($include);
						break;
					}
				self::sec_load();
			} else
				RESTORE::file('globals.php',$include);
		}
	}

	static public function val($v) {
		self::load();
		return self::$vars[$v];
	}
}