<?php
function __autoload($class) {
	switch($class) {
		case 'TEMPLATE' : 
			include __base_path.'levels/3/template.php'; break;
		default :
			include_once(__base_path.'levels/2/loader.php');
			level_2_autoload($class);
	}
}
?>