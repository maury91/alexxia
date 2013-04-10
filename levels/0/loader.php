<?php
function level_0_autoload($class) {
	switch($class) {
		case 'DB' : 
			include __base_path.'levels/0/db.php'; break;
		case 'GET' :
		case 'POST' :
		case 'COOKIE' :
			include __base_path.'levels/0/external.php'; break;
		case 'RAND' :
			include __base_path.'levels/0/rand.php'; break;
		case 'CRYPT' :
			include __base_path.'levels/0/crypto.php'; break;
		case 'PHP_WRITER' :
			include __base_path.'levels/0/php_writer.php'; break;
		case 'ALECHECK' :
			include __base_path.'levels/0/alecheck.php'; break;
		case 'HTML' :
			include __base_path.'levels/0/html.php'; break;
		case 'ERRORS' :
			include __base_path.'levels/0/errors.php'; break;
		case 'FUNCTIONS' :
			include __base_path.'levels/0/functions.php'; break;
		case 'ALESQLTable' :
			include __base_path.'levels/0/db_SQLtable.php'; break;
		case 'ALEMySQLTable' :
			include __base_path.'levels/0/db_MySQLtable.php'; break;
	}
}
?>