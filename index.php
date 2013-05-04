<?php
//Inizializzazione
include('version.php');
header('Content-Type: text/html; charset=utf-8');
header('CMS: Alexxia v'.__ALE_version.' http://alexxia.it');
define('__base_path',dirname(__FILE__).'/');
require(__base_path.'levels/3/loader.php');
//Criptazione
if (isset($_POST['init'])) {
	echo SECURE::init();
	exit(0);
} elseif (isset($_POST['cr'])) {
	//Decodifica
	$data = SECURE::decrypt($_POST['cr'],$_POST['data']);
	$params = json_decode($data,true);
	if ($params!=NULL) {
		$input = $params['params'];
		switch ($params['action']) {
			case 'new_aes' :
				echo SECURE::new_aes($input['rsa_key']);
				break;
		}
	} else echo 'decription error';
	exit(0);
}
//Installazione
if (file_exists('install/make.php')) {
	session_start();
	$point = (isset($_SESSION['step']))? $_SESSION['step'] : 1;
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
include(__base_path.'config/infoserver.php');
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
//Localizazione Script da chiamare
if(POST::exists('page'))	$page 	= POST::val('page'); 	else $page 	= (GET::exists('page'))?GET::val('page') : '';
if(POST::exists('com'))		$com 	= POST::val('com'); 	else $com 	= (GET::exists('com'))?GET::val('com') : '';
if(POST::exists('zone'))	$zone 	= POST::val('zone'); 	else $zone 	= (GET::exists('zone'))?GET::val('zone') : '';
if($page != '')
	$pag = 'pages/'.$page; 
else 
	$pag = ($com != '')?'com/'.$com : 'core/'.$zone;
if ($pag == 'core/') $pag = 'pages/home';
$pag .= '.php';
//Controllo se passare alla modalità Mobile
if (MOBILE::is_mobile()) {
	include('mobile/mobile.php');
	exit(0);
}
//Modalità Normale
HTML::add_script('js/jquery.js','js/jquery-ui.js');
HTML::add_style('css/jquery-ui.css');
foreach (PLUGINS::in('core','index','begin') as $p) include("plugin/$p.php");
$pg_html = '';
ob_start();
if(strpos($pag,'..') === false) {
	if (file_exists($pag)) {
		foreach (PLUGINS::in('core','index','before_page') as $p) include("plugin/$p.php");
		include($pag);
	}
	else
		ERRORS::display(404,$pag);
}
else
	ERRORS::display(405,$pag);
foreach (PLUGINS::in('core','index','after_page') as $p) include("plugin/$p.php");
//Elaborazione Template
if (!GET::exists('aj')||(GET::val('aj')=='no')){
	foreach (PLUGINS::in('core','index','template') as $p) include("plugin/$p.php");
	$pg_html .= ob_get_contents();
	ob_end_clean();
	TEMPLATE::elab($pg_html);
} elseif (HTML::get_title()!=GLOBALS::val('sitename'))
	echo'<script>document.title = "'.(HTML::get_title()).'";</script>';
//
foreach (PLUGINS::in('core','index','end') as $p) include("plugin/$p.php");
?>