<?php
/**
 *	Login module for ALExxia
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
if (isset($_CRIPTED)) {
	switch ($_CRIPTED['act']) {
		case 'login' :
			$to_c = array('login'=>'no');
			//Search the user
			$res = DB::select('*','users','WHERE nick = ',$_CRIPTED['nick']);
			if ($res) {
				$data = DB::assoc($res);
				//Compare the pass
				if ((strcmp(md5($_CRIPTED['pass']).':'.substr($_CRIPTED['pass'],0,29),substr($data['password'],0,62))==0)&&(strcmp(md5(substr($data['password'],63,60).$_SESSION[$_POST['cr']]['tokens'][$_CRIPTED['id']]),$_CRIPTED['pass2'])==0)) {
					//Check if the user is banned (TODO)
					if (true) {
						//UnSecure session
						$cookieCode = RAND::word(10);
						COOKIE::set('ale_user',$_CRIPTED['nick'],60*24*7);
						COOKIE::set('ale_auth',$cookieCode,60*24*7);
						DB::update('users',array('cookieCode' => CRYPT::BF($cookieCode,6)),' WHERE id = ',$data['id']);
						//Secure session
						$new_k = CRYPT::BF(substr($data['password'],63,60),7);
						$_SESSION[$cookieCode]['key'] = md5($new_k);
						$_SESSION[$cookieCode]['type'] = 'aes';
						$_SESSION[$cookieCode]['id'] = $data['id'];
						if (isset($_CRIPTED['ext_key']))
							$_SESSION[$cookieCode]['ext_key'] = $_CRIPTED['ext_key'];
						//Return secure data
						$to_c = array('login'=>'ok','sess'=>$cookieCode,'tk'=>substr($new_k,0,29));
					} else
						$to_c['err'] = 'ban';
				}
			}
			//Distruzione token
			unset($_SESSION[$_POST['cr']]['tokens'][$_CRIPTED['id']]);
			SECURE::returns($to_c);
			break;
		case 'salt_pass' :
			//Get the data to salt the password like the pass in the database
			$a = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF));
			$b = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF));
			//Use the token to randomize the criptation
			$_SESSION[$_POST['cr']]['tokens'][$a] = $b;
			$salt_a=$salt_b='';
			//Search the user
			$res = DB::select('*','users','WHERE nick = ',$_CRIPTED['nick']);
			if ($res) {
				$data = DB::assoc($res);
				$salt_a = substr($data['password'],33,29);
				$salt_b = substr($data['password'],63,29);
			}
			//Return the salt
			SECURE::returns(array('token'=>$b,'id'=>$a,'salt_a'=>$salt_a,'salt_b'=>$salt_b));
		break;
	}
} else {
	//Include the language file
	include(LANG::path().'login.php');
	//Change page title
	HTML::append_title(' - '.$__title);
	if (USER::logged()) 
		echo $__already_logged;
	else {
		//Use the secure modal
		SECURE::libs();
		//Add style e script to the head of the page
		HTML::add_style('css/login.css');
		HTML::add_script('js/login.js');
		//Print the page content
		echo '<script type="text/javascript">
			__login_success = "'.$__login_success.'";
			__login_error	= "'.$__login_error.'";
			__login_load	= "'.$__login_load.'";
		</script>
		<div title="'.$__secure.'" class="secure_status"><div class="points"></div><div class="img unsecure"></div></div>
		<div class="login">
			'.((GLOBALS::val('offline'))?'':'<h2>'.$__login.'</h2>').'
			<form id="dologin" class="datas">
				<span class="label">'.$__nick.'</span>
				<input type="text" id="nick" />
				<span class="label">'.$__pass.'</span>
				<input type="password" id="pass" />
				<input type="submit" value="'.$__submit.'" />
			</form>
			'.((GLOBALS::val('offline'))?'':$__no_accout).'
		</div>';
	}
}

?>