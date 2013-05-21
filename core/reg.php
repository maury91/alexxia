<?php
/**
 *	Users Registration for ALExxia
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
if (POST::exists('check')) {		//Ajax control for existent users and emails
	if (POST::exists('check','nick')) {
		if (DB::rows(DB::select('*','users','WHERE nick = ',POST::val('check','nick'))))
			echo json_encode(array('r'=>'n','err'=>1));
		else
			echo json_encode(array('r'=>'y'));
	} elseif (POST::exists('check','email')) {
		if (DB::rows(DB::select('*','users','WHERE email LIKE ',POST::val('check','email'))))
			echo json_encode(array('r'=>'n','err'=>1));
		else
			echo json_encode(array('r'=>'y'));
	}
	exit(0);
} elseif (isset($_CRIPTED)) {		//Registration End
	//Captcha control
	$captcha=new CAPTCHA($_CRIPTED['captcha']['id']);
	if (!$captcha->click($_CRIPTED['captcha']['x'],$_CRIPTED['captcha']['y']))
		SECURE::returns(array('ok'=>false,'err'=>'captcha','captcha'=>$_CRIPTED['captcha']['id']));
	//Nick control (alphanumerical,length and unique)
	if ((!(ctype_alnum($_CRIPTED['nick'])&&(strlen($_CRIPTED['nick'])>3)))||DB::rows(DB::select('*','users','WHERE nick = ',$_CRIPTED['nick'])))
		SECURE::returns(array('ok'=>false,'err'=>'nick'));
	//Email control (valid email and unique)
	if ((!FUNCTIONS::is_valid_email($_CRIPTED['email']))||DB::rows(DB::select('*','users','WHERE email LIKE ',$_CRIPTED['email'])))
		SECURE::returns(array('ok'=>false,'err'=>'email'));
	//Activation code
	$act_email = RAND::word(10);
	//Data to insert in the database
	$insert_data = array('nick'=>$_CRIPTED['nick'],'password'=>$_CRIPTED['pass'],'email'=>$_CRIPTED['email'],'name' => $_CRIPTED['name'],'lastname' => $_CRIPTED['lname'],'verifyCode' => $act_email);
	//Inclusion of the file of the language
	include(LANG::path().'reg.php');
	//Buil the email
	$content=str_replace(array('%sitename','%nick','%fname','%lname','%url'), array(GLOBALS::val('sitename'),$_CRIPTED['nick'],$_CRIPTED['name'],$_CRIPTED['lname'],__http.'zone_act.html?nick='.$_CRIPTED['nick'].'&code='.$act_email), $__regemail);
	//Plug-ins inclusion (if a developer want to make a plug-in that add or alter the data inserted in the database)
	foreach (PLUGINS::in('core','reg','insert_data') as $p) include($p);
	//Data is inserted in the database
	if (DB::insert('users',$insert_data)) {
		//Sending the email
		if (MAIL::send($_CRIPTED['email'],sprintf($__sbjct,GLOBALS::val('sitename')),$content))
			SECURE::returns(array('ok'=>true,'html'=>$__regsuccess));		//Email sendend
		else
			SECURE::returns(array('ok'=>false,'html'=>'Mail send error!','err'=>'mail'));	//Email error
	} else
		SECURE::returns(array('ok'=>false,'html'=>DB::error(),'err'=>'db'));	//DB error (data not inserted)
	//Return json data, the template is usefull
	exit(0);
} else {
	//Use the secure modal
	SECURE::libs();
	//Add style e script to the head of the page
	HTML::add_style('css/reg.css');
	HTML::add_script('js/reg.js');
	//Make a new capthca
	$captcha=new CAPTCHA();
	//Include the language file
	include(LANG::path().'reg.php');
	//Print the page content
	echo '<script type="text/javascript">
		__nick_short = "'.$__nick_short.'";
		__nick_invalid = "'.$__nick_invalid.'";
		__nick_used = "'.$__nick_used.'";
		__pass_short = "'.$__pass_short.'";
		__pass_equal = "'.$__pass_equal.'";
		__email_invalid = "'.$__email_invalid.'";
		__email_equal = "'.$__email_equal.'";
		__email_used = "'.$__email_used.'";
	</script>
	<div title="'.$__secure.'" class="secure_status"><div class="points"></div><div class="img unsecure"></div></div>
	<div class="registration">
		<div class="extra_data">';
	//Plug-ins inclusion (if a developer want to make a plug-in that add some data in the registration)
	foreach (PLUGINS::in('core','reg','add_camps1') as $p) include($p);
echo	'</div>
		<div class="left">'.$__nick.'*</div>
		<div class="right"><input type="text" class="required" id="nick" /><span class="info"></span></div>		
		<div class="left">'.$__pass.'*</div>
		<div class="right"><input type="password" class="required" id="pass" /><span class="info"></span></div>	
		<div class="left">'.$__pass2.'*</div>		
		<div class="right"><input type="password" class="required" id="pass2" /><span class="info"></span></div>		
		<div class="left">'.$__email.'*</div>
		<div class="right"><input type="email" class="required" id="email" /><span class="info"></span></div>	
		<div class="left">'.$__email2.'*</div>		
		<div class="right"><input type="email" class="required" id="email2" /><span class="info"></span></div>
		<div class="left">'.$__name.'</div>
		<div class="right"><input type="text" id="name" /></div>			
		<div class="left">'.$__lname.'</div>
		<div class="right"><input type="text" id="lname" /></div>
		<div class="extra_data">';
	//Plug-ins inclusion (if a developer want to make a plug-in that add some data in the registration)
	foreach (PLUGINS::in('core','reg','add_camps2') as $p) include($p);
echo	'</div>
		<div class="left"><br/><br/><br/></div>
		<div class="left">'.$captcha->text().'</div>
		<div class="right">'.$captcha->get_img().'</div>
	</div>';
}
?>