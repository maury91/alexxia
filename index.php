<?php
//Inizializzazione
include('version.php');
header('Content-Type: text/html; charset=utf-8');
header('CMS: Alexxia v'.__ALE_version.' http://alexxia.it');
define('__base_path',dirname(__FILE__).'/');
define('__http_path',dirname($_SERVER['SCRIPT_NAME']).'/');
require(__base_path.'levels/2/loader.php');
//Installazione
if (file_exists('install/make.php')) {
	$point = (isset($_GET['pax']))? $_GET['pax'] : 1;
	if ($point == 'end') {
		$handle = opendir('install/');
		while (false !== ($file = readdir($handle))) { 
			if(is_file('install/'.$file)){
				unlink('install/'.$file);
			}
		}
		$handle = closedir($handle);
		@rmdir('install/');
	} else
		include('install/make.php');
}
//Controllo modalità offline
if (GLOBALS::val('offline')) {
	if (USER::level() > 3) {
		include('pages/extra/offline.php');
		if (isset($_GET['zone'])&&($_GET['zone']=='lostp')) {
			echo '<center>';
			include('core/lostp.php');
		} else
			include('core/login.php');
		exit(0);
	}
}
HTML::add_script('js/jquery.js','js/jquery-ui.js');
HTML::add_style('css/test.css');
echo HTML::get_head();
?>