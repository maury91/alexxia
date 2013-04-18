<?php
define('__base_path',dirname(dirname(__FILE__)).'/');
require(__base_path.'admin/levels/0/secure_lib.php');
if (isset($_POST['new_aes'])) {
	echo SECURE::new_aes($_POST['new_aes'],$_POST['cripted']);
} elseif (isset($_POST['new_token'])) {
	$a = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF));
	$b = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF));
	$to_c = json_encode(array('token'=>$b,'id'=>$a));
	$_SESSION[$_POST['new_token']]['tokens'][$a] = $b;
	echo json_encode(array('cr' => (SECURE::crypt_AES($_POST['new_token'],$to_c))));
} elseif (isset($_POST['salt_pass'])) {
	require_once('Crypt/aes2.php');
	$data = AesCtr::encrypt($_POST['cr'], $_SESSION[$_POST['salt_pass']]['key'], 256);
	$data_j = json_decode($data);
	if ($data_j!=NULL) {
		DB2::select($data['nick']);
	
		$res = AesCtr::encrypt($to_c, $_SESSION[$_POST['salt_pass']]['key'], 256);
	}
	
	
	
	
} elseif (isset($_POST['secure'])) {
	
	
} elseif (isset($_POST['init'])) {
	echo SECURE::init();
} else {
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Administration</title>
		<link rel="stylesheet" href="css/main.css" />		
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<script type="text/javascript" src="js/jsbn.js"></script>
		<script type="text/javascript" src="js/jsbn2.js"></script>
		<script type="text/javascript" src="js/base64.js"></script>
		<script type="text/javascript" src="js/base64_2.js"></script>
		<script type="text/javascript" src="js/utf8.js"></script>
		<script type="text/javascript" src="js/prng4.js"></script>
		<script type="text/javascript" src="js/rng.js"></script>
		<script type="text/javascript" src="js/aes.js"></script>
		<script type="text/javascript" src="js/aes-ctr.js"></script>
		<script type="text/javascript" src="js/rsa.js"></script>
		<script type="text/javascript" src="js/json2.js"></script>
		<script type="text/javascript" src="js/RSA2.js"></script>
		<script src="http://crypto-js.googlecode.com/svn/tags/3.1.2/build/rollups/md5.js"></script>
		<script type="text/javascript" src="js/secure.js"></script>
		<script type="text/javascript" src="js/isaac.js"></script>
		<script type="text/javascript" src="js/bCrypt.js"></script>
		<script type="text/javascript">
			$(function(){
				$().secure_init(
					function(stat) {
						$('.main .points').html('');
						for (i=0;i<4;i++) 
							$('.main .points').append($('<span></span>').text('.').css({'margin-left':Math.cos((stat+i)*0.17-1.57)*80,'margin-top':Math.sin((stat+i)*0.17-1.57)*80}));
					},
					function() {
						$('.main').hide();
						$('.login').slideDown(800);
					}
				);
			})
		</script>
	</head>
	<body>
		<script type="text/javascript">
			
		</script>
		<div class="logo"></div>
		<div class="main load">
			<div class="img secure"></div>
			<div class="points"></div>
		</div>
		<div class="login">
			<h1>Login</h1>
			<p>Nick</p>
			<input type="text" id="nick" />
			<p>Password</p>
			<input type="password" id="pass" />
			<input type="button" onclick="$().secure('do_login')" value="Login" />
		</div>
	</body>
</html>
<?php
}

?>