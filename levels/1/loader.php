<?php
function level_1_autoload($class) {
	switch($class) {
		case 'USER' : 
			include __base_path.'levels/1/user.php'; break;
		case 'RESTORE' :
			include __base_path.'levels/1/restore.php'; break;
		case 'MOBILE' :
			include __base_path.'levels/1/mobile.php'; break;
		case 'PLUGINS' :
			include __base_path.'levels/1/plugin.php'; break;
		case 'LANG' :
			include __base_path.'levels/1/lang.php'; break;
		case 'MEDIA_MAN' :
			include __base_path.'levels/1/media_man.php'; break;
		default :
			include_once(__base_path.'levels/0/loader.php');
			level_0_autoload($class);
	}
}
?>