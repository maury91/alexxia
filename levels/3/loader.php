<?php
function __autoload($class) {
	switch($class) {
		case 'TEMPLATE' : 
			include __base_path.'levels/3/template.php'; break;
		case 'SECURE' : 
			include __base_path.'admin/levels/0/secure_lib.php'; break;
		default :
			include_once(__base_path.'levels/2/loader.php');
			level_2_autoload($class);
	}
}
?>