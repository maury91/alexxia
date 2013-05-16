<?php
/**
 *	Index file for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
//Version include
include('version.php');
//Header data
header('Content-Type: text/html; charset=utf-8');
header('CMS: Alexxia v'.__ALE_version.' http://alexxia.it');
//Define the constants
define('__base_path',dirname(__FILE__).'/');
define('__http_path',rtrim(dirname($_SERVER['SCRIPT_NAME']), '/').'/');
define('__http_host',rtrim('http://'.$_SERVER['SERVER_NAME'], '/'));
define('__http',rtrim(__http_host.__http_path, '/').'/');
//Class Auto-loader
require(__base_path.'levels/3/loader.php');
//Enable secure connection
SECURE::init();
//Installation (if exists)
if (file_exists('install/make.php')) {
	//Point of the installation
	$point = (isset($_SESSION['step']))? $_SESSION['step'] : 1;
	//End of the installation
	if ($point == 'end') {
		//Remove all installation files
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
//Offline mode
if (GLOBALS::val('offline')) {
	//The site is visible only for who have the permission
	if (!PERMISSION::has('view_offile')) {
		//If not have the permission (or is not logged)
		//Show a user defined page and the login
		include('pages/extra/offline.php');
		if (GET::exists('zone')&&(GET::val('zone')=='lostp')) {
			echo '<center>';
			include('core/lostp.php');
		} else
			include('core/login.php');
		exit(0);
	}
}
//Localize script to call (secure mode)
if (SECURE::active()) {
	$params = SECURE::get('params');
	$pages = $params['page'];
	$_CRIPTED = $params['params'];
	$page = (isset($pages['page']))?$pages['page']:''; 
	$com  = (isset($pages['com'])) ?$pages['com']:''; 
	$zone = (isset($pages['zone']))?$pages['zone']:''; 
}
//If not in secure mode localize script to call in normal mode
if (!isset($pages)) {
	if(POST::exists('page'))	$page 	= POST::val('page'); 	else $page 	= (GET::exists('page'))?GET::val('page') : '';
	if(POST::exists('com'))		$com 	= POST::val('com'); 	else $com 	= (GET::exists('com'))?GET::val('com') : '';
	if(POST::exists('zone'))	$zone 	= POST::val('zone'); 	else $zone 	= (GET::exists('zone'))?GET::val('zone') : '';
}
//Prepare a variable with the path of the script
if($page != '')
	$pag = 'pages/'.$page; 
else 
	$pag = ($com != '')?'com/'.$com : 'core/'.$zone;
if ($pag == 'core/') $pag = 'pages/home';
$pag .= '.php';
//If the user are using a mobile phone show the mobile mode
/*
if (MOBILE::is_mobile()) {
	include('mobile/mobile.php');
	exit(0);
}*/
//Else show the normal mode
//Add script and styles to the head of the page
HTML::add_script('js/jquery.js','js/jquery-ui.js');
HTML::add_style('css/jquery-ui.css');
//A variable to insert the content of the page
/*
	Use
		echo 'Hello world!';
	and
		$pg_html .= 'Hello World!';
	is the same thing
*/
$pg_html = '';
//Plug-ins inclusion
foreach (PLUGINS::in('core','index','begin') as $p) include($p);
//Prevent output
ob_start();
//Script inclusion
if(strpos($pag,'..') === false) {
	//Check if the script exists
	if (file_exists($pag)) {
		//Plug-ins inclusion
		foreach (PLUGINS::in('core','index','before_page') as $p) include($p);
		include($pag);
		//Plug-ins inclusion
		foreach (PLUGINS::in('core','index','after_page') as $p) include($p);
	}
	else
		ERRORS::display(404,$pag);	//Display 404 error
}
else
	ERRORS::display(405,$pag);	//Display 405 error
//Template elaboration (if the mode is not ajax)
if (!GET::exists('aj')||(GET::val('aj')=='no')){
	//Plug-ins inclusion
	foreach (PLUGINS::in('core','index','template') as $p) include($p);
	//Get the data send in output
	$pg_html .= ob_get_contents();
	//Clear output
	ob_end_clean();
	//Elab tamplate
	TEMPLATE::elab($pg_html);
} elseif (HTML::get_title()!=GLOBALS::val('sitename')) {
	//If is in ajax mode print a script to change the window name
	echo'<script>document.title = "'.(HTML::get_title()).'";</script>';
}
//Plug-ins inclusion
foreach (PLUGINS::in('core','index','end') as $p) include($p);
?>