<?php
/**
 *	Administration for ALExxia
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
session_start();
//Define base constants
define('__base_path',dirname(dirname(__FILE__)).'/');
include(__base_path.'config/infoserver.php');
define('__http',rtrim(__http_host.__http_path, '/').'/');
require(__base_path.'admin/levels/0/loader.php');
//Initialize secure connection
SECURE::init();
//Check if is using the secure connection
if (SECURE::active()) {
	//Save the data in a support var
	$input = SECURE::get('params');
	switch (SECURE::get('action')) {
		case 'login' :
			//Default login insuccess
			$to_c = array('login'=>'no');
			//Retrieve the data from the database
			$res = DB2::select('*','admins','WHERE nick = ',$input['nick']);
			if ($res) {
				$data = DB2::assoc($res);
				//Compare the passwords
				if ((strcmp(md5($input['pass']).':'.substr($input['pass'],0,29),substr($data['password'],0,62))==0)&&(strcmp(md5(substr($data['password'],63,60).$_SESSION[$_POST['cr']]['tokens'][$input['id']]),$input['pass2'])==0)) {
					//In case of success crypt the "clear" password
					$new_k = CRYPT::BF(substr($data['password'],63,60),7);
					//Create a new session
					$sess_id = base64_encode(crypt_random());
					//The new key is composed from the old key and a random hash of the  crypted password
					$_SESSION[$sess_id]['key'] = substr($_SESSION[$_POST['cr']]['key'],0,16).substr(md5($new_k),0,16);
					$_SESSION[$sess_id]['type'] = 'aes';
					$_SESSION[$sess_id]['id'] = $data['id'];
					//Return the data and the random salt for crypt the password
					$to_c = array('login'=>'ok','sess'=>$sess_id,'tk'=>substr($new_k,0,29));
				}
			}
			//Destroy token (can't use 2 times)
			unset($_SESSION[$_POST['cr']]['tokens'][$input['id']]);
			//Returns the sucess or failure of the login
			SECURE::returns($to_c);
			break;
		case 'salt_pass' :
			//Make a new token
			$a = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF));
			$b = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF));
			$_SESSION[$_POST['cr']]['tokens'][$a] = $b;
			//Retrieve data from the database
			$salt_a=$salt_b='';
			$res = DB2::select('*','admins','WHERE nick = ',$input['nick']);
			if ($res) {
				$data = DB2::assoc($res);
				//Retrieve the salt of the hash of the password
				$salt_a = substr($data['password'],33,29);
				$salt_b = substr($data['password'],63,29);
			}
			//Returns the token data and the salts
			SECURE::returns(array('token'=>$b,'id'=>$a,'salt_a'=>$salt_a,'salt_b'=>$salt_b));
			break;
		case 'area' :
			//Increase the exection_time (the crypt/encrypt for big data is slow)
			ini_set('max_execution_time', 100);
			//No output = error
			$content=array('r' => 'Error!','in' =>$input);
			//Check the session
			if (isset($_SESSION[$_POST['cr']]['id'])) {
				//Retrieve from the database the data of the user
				$res = DB2::select('*','admins','WHERE id = ',$_SESSION[$_POST['cr']]['id']);
				if ($res) {
					$user = DB2::assoc($res);
					//Assoc the user
					USER::admin($user);
					//Use a support variable for the datas passed as input
					$external = $input['params'];
					//Call the script
					if (file_exists(__base_path.'admin/panel/'.$input['page'].'.php'))
						include(__base_path.'admin/panel/'.$input['page'].'.php');
				}
			}
			//Return the success
			SECURE::returns(array('content'=>$content));
			break;
	}
} else {
	//Initializzation script
	$__lang = LANG::short();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Administration</title>		
		<link rel="stylesheet" href="<?php echo __http_host.__http_path ?>css/niiwin.css" />
		<link rel="stylesheet" href="<?php echo __http_host.__http_path ?>css/images.css" />
		<link rel="stylesheet" href="css/main.css" />
		<link rel="stylesheet" href="css/jquery/jquery-ui.min.css" />
		<link rel="stylesheet" href="<?php echo __http_host.__http_path ?>css/media_man.css" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/jsbn.js"></script>
		<script type="text/javascript" src="js/jsbn2.js"></script>
		<script type="text/javascript" src="js/base64.js"></script>
		<script type="text/javascript" src="js/base64_2.js"></script>
		<script type="text/javascript" src="js/utf8.js"></script>
		<script type="text/javascript" src="js/prng4.js"></script>
		<script type="text/javascript" src="js/rng.js"></script>
		<script type="text/javascript" src="js/aes.js"></script>
		<script type="text/javascript" src="js/rsa.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>
		<script type="text/javascript" src="js/rsa2.js"></script>
		<script type="text/javascript" src="js/md5.js"></script>
		<script type="text/javascript" src="js/secure.js"></script>
		<script type="text/javascript" src="js/isaac.js"></script>
		<script type="text/javascript" src="js/bCrypt.js"></script>
		<script type="text/javascript" src="js/pad-zeropadding-min.js"></script>
		<script type="text/javascript" src="<?php echo __http_host.__http_path ?>editors/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="<?php echo __http_host.__http_path.'js/lang/mediam.'.$__lang.'.js' ?>"></script>
		<script type="text/javascript">
			host_path = '<?php echo __http_host.__http_path ?>';
			admin_host_path = 'http://<?php echo $_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']); ?>/';
			$(function(){
				$().secure_init(
					admin_host_path,
					function(stat) {
						$('.main .points').html('');
						for (i=0;i<4;i++) 
							$('.main .points').append($('<span></span>').text('.').css({'margin-left':Math.cos((stat+i)*0.17-1.57)*80,'margin-top':Math.sin((stat+i)*0.17-1.57)*80}));
					},
					function() {
						$('.main').hide();
						$('.login').slideDown(800);
						$('.login #nick').focus();
					}
				);
			})
		</script>
	</head>
	<body>
		<!--ALExxiaSecure-->
		<script type="text/javascript">
			var __lang="<?php echo $__lang ?>";
		</script>
		<div class="logo"></div>
		<div class="main load">
			<div class="img secure"></div>
			<div class="points"></div>
		</div>
		<div class="login">
			<form onsubmit="{$().secure('do_login');return false;}">
				<h1>Admin</h1>
				<p>Username</p>
				<input type="text" id="nick" />
				<p>Password</p>
				<input type="password" id="pass" />
				<input type="submit" value="Login" />
			</form>
		</div>
	</body>
</html>
<?php
}

?>