<?php
function __autoload($class) {
	switch($class) {
		case 'GLOBALS' : 
			include __base_path.'levels/2/globals.php'; break;
		default :
			include_once(__base_path.'levels/1/loader.php');
			level_1_autoload($class);
	}
}
?>