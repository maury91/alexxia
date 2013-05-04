<?php
function admin_level_0_autoload($class) {
	switch($class) {
		case 'DB' : 
		case 'DB2' : 
			include __base_path.'admin/levels/0/db.php'; break;
		case 'SCRIPT' : 
		case 'STYLE' : 
			include __base_path.'admin/levels/0/script.php'; break;
	}
}
spl_autoload_register('admin_level_0_autoload');
include_once(__base_path.'levels/3/loader.php');
?>