<?php
header('Installed:no');
//Controlli pre installazione
//Required
include(LANG::short().'.php');
$lngs = LANG::get_list();
$lng='';
foreach ($lngs as $k=>$v) {
	$lng.='<option value="'.$k.'" '.(($v['sn']==LANG::short())?'selected="selected"':'').'>'.$v['n'].'</option>';
}
$lang_sel=$__lang_sel.'<select onchange="location.href=\'?lang=\'+this.value">'.$lng.'</select>';
$error='';
$bt_class='go';
if (isset($_POST['next'])) {
	switch ($point) {
		case 1 :
			function test_write_cf() {
				if (is_writable(__base_path.'config/dbconfig.php'))
					return true;
				if (file_put_contents(__base_path.'config/dbconfig.php',' '))
					return true;
			}
			function test_write_ch() {
				if (is_writable(__base_path.'cache/template.php'))
					return true;
				if (file_put_contents(__base_path.'cache/template.php',' '))
					return true;
			}
			$required = array(
				'version'=>version_compare("5.1", phpversion(), '<'),
				'rglobals'=>ini_get('register_globals')==false,
				'zlib'=>extension_loaded('zlib'),
				'db'=>extension_loaded('mysql')||extension_loaded('mysqli')||extension_loaded('SQLite'),
				'json'=>extension_loaded('json'),
				'write'=>test_write_cf()&&test_write_ch()&&(unlink(__base_path.'cache/template.php')),
				'http'=>(ini_get('allow_url_fopen') == true)||function_exists('curl_init'),
				'output'=>ini_get('output_buffering')==true
			);
			$sup=true;
			foreach ($required as $v) if (!$v) $sup=false;
			if ($sup)
				$_SESSION['step']=$point=2;
		break;
		case 2 :
			if (!(ctype_alnum($_POST['nick'])&&(strlen($_POST['nick'])>3)))
				$error .= '<div class="error">'.$__not_nick.'</div>';
			if (strlen($_POST['pass'])<6)
				$error .= '<div class="error">'.$__not_pass.'</div>';
			elseif (strcmp($_POST['pass'],$_POST['pass2']))
				$error .= '<div class="error">'.$__not_pass2.'</div>';
			if (!FUNCTIONS::is_valid_email($_POST['email']))
				$error .= '<div class="error">'.$__not_email.'</div>';
			if (strlen(trim($_POST['site_name']))<6)
				$error .= '<div class="error">'.$__not_sname.'</div>';
			if ($error=='') {
				$_SESSION['site_data'] = array('nick'=>$_POST['nick'],'sname'=>$_POST['site_name'],'sdesc'=>$_POST['site_desc'],'email'=>$_POST['email'],'pass'=>$_POST['pass']);
				$_SESSION['step']=$point=3;
			}
		break;
		case 3 :
			switch ($_POST['dbt']) {
				case 'mysql' :
					if (!mysql_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpass']))
						$error .= '<div class="error">'.$__not_db.'</div>';
					elseif (!mysql_select_db($_POST['dbname']))
						$error .= '<div class="error">'.$__not_dbname.'</div>';
					elseif ($_POST['dbhost2']!='') {
						if (!mysql_connect($_POST['dbhost2'],$_POST['dbuser2'],$_POST['dbpass2']))
							$error .= '<div class="error">'.$__not_db2.'</div>';
						elseif (!mysql_select_db($_POST['dbname2']))
							$error .= '<div class="error">'.$__not_dbname2.'</div>';
					}
				break;
				case 'mysqli' :
					if (!$con = mysqli_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpass']))
						$error .= '<div class="error">'.$__not_db.'</div>';
					elseif (!mysqli_select_db($con,$_POST['dbname']))
						$error .= '<div class="error">'.$__not_dbname.'</div>';
					elseif ($_POST['dbhost2']!='') {
						if (!$con2 = mysqli_connect($_POST['dbhost2'],$_POST['dbuser2'],$_POST['dbpass2']))
							$error .= '<div class="error">'.$__not_db2.'</div>';
						elseif (!mysqli_select_db($con2,$_POST['dbname2']))
							$error .= '<div class="error">'.$__not_dbname2.'</div>';
					}
				break;
			}
			if ($error=='') {
				$_SESSION['db_data'] = array('dbhost'=>$_POST['dbhost'],'dbuser'=>$_POST['dbuser'],'dbpass'=>$_POST['dbpass'],'dbname'=>$_POST['dbname'],'dbpref'=>$_POST['dbpref'],'dbhost2'=>$_POST['dbhost2'],'dbuser2'=>$_POST['dbuser2'],'dbpass2'=>$_POST['dbpass2'],'dbname2'=>$_POST['dbname2'],'dbpref2'=>$_POST['dbpref2']);
				$_SESSION['step']=$point=4;
			}
		break;
	}
}
elseif (isset($_GET['back'])) {
	if ($point>1)
		$_SESSION['step']=--$point;	
}
if (($point==2)&&isset($_SESSION['site_data'])) {
	$_POST['nick']= $_SESSION['site_data']['nick'];
	$_POST['site_name']= $_SESSION['site_data']['sname'];
	$_POST['site_desc']= $_SESSION['site_data']['sdesc'];
	$_POST['email']= $_SESSION['site_data']['email'];
	$_POST['pass']= $_SESSION['site_data']['pass'];
}
if (($point==3)&&isset($_SESSION['db_data'])) {
	$_POST['dbhost']= $_SESSION['db_data']['dbhost'];
	$_POST['dbuser']= $_SESSION['db_data']['dbuser'];
	$_POST['dbpass']= $_SESSION['db_data']['dbpass'];
	$_POST['dbname']= $_SESSION['db_data']['dbname'];
	$_POST['dbpref']= $_SESSION['db_data']['dbpref'];
	$_POST['dbhost2']= $_SESSION['db_data']['dbhost2'];
	$_POST['dbuser2']= $_SESSION['db_data']['dbuser2'];
	$_POST['dbpass2']= $_SESSION['db_data']['dbpass2'];
	$_POST['dbname2']= $_SESSION['db_data']['dbname2'];
	$_POST['dbpref2']= $_SESSION['db_data']['dbpref2'];
}
switch ($point) {
	case 1 : 
		function test_write_cf() {
			if (is_writable(__base_path.'config/dbconfig.php'))
				return true;
			if (file_put_contents(__base_path.'config/dbconfig.php',' '))
				return true;
		}
		function test_write_ch() {
			if (is_writable(__base_path.'cache/template.php'))
				return true;
			if (file_put_contents(__base_path.'cache/template.php',' '))
				return true;
		}
		$required = array(
			'version'=>version_compare("5.1", phpversion(), '<'),
			'rglobals'=>ini_get('register_globals')==false,
			'zlib'=>extension_loaded('zlib'),
			'db'=>extension_loaded('mysql')||extension_loaded('mysqli')||extension_loaded('SQLite'),
			'json'=>extension_loaded('json'),
			'write'=>test_write_cf()&&test_write_ch()&&(unlink(__base_path.'cache/template.php')),
			'http'=>(ini_get('allow_url_fopen') == true)||function_exists('curl_init'),
			'output'=>ini_get('output_buffering')==true
		);
		$requireds = '';
		$i=0;
		$bt_class='next';
		foreach ($required as $k => $v) {
			$i=($i+1)&1;
			$requireds .= '<div class="row '.($i?'p':'d').'">'.$req[$k].'<span class="'.($v?'ok':'no').'">'.($v?$__y:$__n).'</span></div>';
			if(!$v) $bt_class='error';
		}
		//Optional
		$raccomand = array(
			'magic' => !((function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc())||ini_get('magic_quotes_sybase')),
			'safe' => ini_get('safe_mode')==false,
			'error' => ini_get('display_errors')==0,
			'upload' => ini_get('file_uploads') == 1
		);
		$raccomanded = '';
		$i=0;
		foreach ($raccomand as $k => $v) {
			$i=($i+1)&1;
			$raccomanded .= '<div class="row '.($i?'p':'d').'">'.$rac[$k].'<span class="'.($v?'ok':'no').'">'.($v?$__y:$__n).'</span></div>';
		}
		$content='<div class="left">
				<h2>'.$__req.'</h2>
				<hr/>
				<p>'.$__req_d.'</p>
				'.$requireds.'
				<div class="row l"></div>
			</div>
			<div class="right">
				<h2>'.$__rac.'</h2>
				<hr/>
				<p>'.$__rac_d.'</p>
				'.$raccomanded.'
				<div class="row l"></div>
			</div>';
		break;
	case 2 :
		$content='<h2>'.$__configuration.'</h2>
		<div class="left">
			<div class="data">
				<div class="l">
					'.$__name.'*
				</div>
				<div class="r">
					<input type="text" name="site_name" value="'.(isset($_POST['site_name'])?$_POST['site_name']:$__site_with).'" />
					<div>'.$__name_d.'</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__desc.'
				</div>
				<div class="r">
					<textarea name="site_desc">'.(isset($_POST['site_desc'])?$_POST['site_desc']:'').'</textarea>
					<div>'.$__desc_d.'</div>
				</div>
			</div>
		</div>
		<div class="right">
			<div class="data">
				<div class="l">
					'.$__email.'*
				</div>
				<div class="r">
					<input type="text" name="email" value="'.(isset($_POST['email'])?$_POST['email']:'').'" />
					<div>'.$__email_d.'</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__nick.'*
				</div>
				<div class="r">
					<input type="text" name="nick" value="'.(isset($_POST['nick'])?$_POST['nick']:'admin').'" />
					<div>'.$__nick_d.'</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__pass.'*
				</div>
				<div class="r">
					<input name="pass" type="password" value="'.(isset($_POST['pass'])?$_POST['pass']:'').'" />
					<div>'.$__pass_d.'</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__pass2.'*
				</div>
				<div class="r">
					<input name="pass2" type="password" />
				</div>
			</div>
		</div>';
		break;
	case 3 :
		$dbs='';
		$content='<h2>Database</h2>
		<script type="text/javascript">
			
		</script>
		<div class="data">
			<div class="l">
				'.$__dbt.'*
			</div>
			<div class="r">
				<select name="dbt">'.(extension_loaded('mysql')?'<option value="mysql">MySQL</option>':'').(extension_loaded('mysqli')?'<option value="mysqli">MySQLi</option>':'').(class_exists('SQLite3')?'<option value="SQLite3">SQLite3</option>':'').(extension_loaded('SQLite')?'<option value="SQLite">SQLite</option>':'').'</select>
				<div>'.$__dbt_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__host.'*
			</div>
			<div class="r">
				<input type="text" name="dbhost" value="'.(isset($_POST['dbhost'])?$_POST['dbhost']:'localhost').'" />
				<div>'.$__host_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbuser.'*
			</div>
			<div class="r">
				<input type="text" name="dbuser" value="'.(isset($_POST['dbuser'])?$_POST['dbuser']:'root').'" />
				<div>'.$__dbuser_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbpass.'
			</div>
			<div class="r">
				<input type="password" name="dbpass" value="'.(isset($_POST['dbpass'])?$_POST['dbpass']:'').'"/>
				<div>'.$__dbpass_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbname.'*
			</div>
			<div class="r">
				<input type="text" name="dbname" value="'.(isset($_POST['dbname'])?$_POST['dbname']:'').'"/>
				<div>'.$__dbname_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbpref.'*
			</div>
			<div class="r">
				<input type="text" name="dbpref" value="'.(isset($_POST['dbpref'])?$_POST['dbpref']:(RAND::word()).'__').'"/>
				<div>'.$__dbpref_d.'</div>
			</div>
		</div>
		<h2>'.$__db2.'</h2>
		<p>'.$__db2_d.'</p>
		<div class="data">
			<div class="l">
				'.$__host.'
			</div>
			<div class="r">
				<input type="text" name="dbhost2" value="'.(isset($_POST['dbhost2'])?$_POST['dbhost2']:'').'"/>
				<div>'.$__host_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbuser.'
			</div>
			<div class="r">
				<input type="text" name="dbuser2" value="'.(isset($_POST['dbuser2'])?$_POST['dbuser2']:'').'"/>
				<div>'.$__dbuser_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbpass.'
			</div>
			<div class="r">
				<input type="password" name="dbpass2" value="'.(isset($_POST['dbpass2'])?$_POST['dbpass2']:'').'"/>
				<div>'.$__dbpass_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbname.'
			</div>
			<div class="r">
				<input type="text" name="dbname2" value="'.(isset($_POST['dbname2'])?$_POST['dbname2']:'').'"/>
				<div>'.$__dbname_d.'</div>
			</div>
		</div>
		<div class="data">
			<div class="l">
				'.$__dbpref.'
			</div>
			<div class="r">
				<input type="text" name="dbpref2" value="'.(isset($_POST['dbpref2'])?$_POST['dbpref2']:'').'"/>
				<div>'.$__dbpref_d.'</div>
			</div>
		</div>';
		break;
		case 4 :
			//Installazione!
			
		break;
}
echo '<!DOCTYPE html>
<html>
	<head>
		<title>ALExxia Installation</title>
		<link rel="stylesheet" href="install/style.css" />
		<link rel="icon" href="media/images/favicon.png" sizes="64x64" type="image/png" />
		<script type="text/javascript" src="js/jquery.js"></script>
	</head>
	<body>
		<form method="post">
			<header>
				<img height="120" class="logo" src="media/images/logo.png"/>
				<div class="continue">
					'.$lang_sel.'
					<input type="hidden" name="next" value=" "/>'.(($point>2)?'<button class="comb l" type="button" onclick="location.href=\'?back\'">'.$__prev.'</button>':'').'<button class="'.(($point>2)?'comb r ':'').$bt_class.'">'.$__continue.'</button>
				</div>
			</header>
			<div class="content">
				'.$error.$content.'
			</div>
		</form>
	</body>
</html>';
exit(0);
?>