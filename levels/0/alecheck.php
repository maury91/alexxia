<?php
if (!function_exists('php_check_syntax')) {
	function php_check_syntax($file) {
		ob_start ();
		$val = (eval('return true; if(0){?>'.file_get_contents($file).'<?php };'));
		ob_end_clean();
		return (is_bool($val)&&$val);
	}
}

class ALECHECK {
	static public function PHP ($content) {
		return php_check_syntax($content);
	}
}
?>