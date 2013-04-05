<?php
/*
	TODO : finire template
*/
class TEMPLATE {
	
	private static function compile_template($template) {
		define('template_path',__http_host.__http_path.'/template/'.$template);
	
	}

	public static function elab($html) {
		if (!file_exists(__base_path.'cache/template.php'))
			self::compile_template(GLOBALS::val('template'));
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			ob_start('ob_gzhandler');
		include __base_path.'cache/template.php';
		if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
			ob_flush();
	}
}
?>