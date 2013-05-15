<?php
/**
 *	Installation for ALExxia
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

//Set a header information
header('Installed:no');
include(LANG::short().'.php');
//Check CMS dependencies
function point1() {
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
		'db'=>extension_loaded('mysql')||class_exists('mysqli')||extension_loaded('SQLite')||class_exists('SQLite3'),
		'json'=>extension_loaded('json'),
		'write'=>test_write_cf()&&test_write_ch()&&(unlink(__base_path.'cache/template.php')),
		'http'=>(ini_get('allow_url_fopen') == true)||function_exists('curl_init'),
		'output'=>ini_get('output_buffering')==true,
		'bcrypt'=>(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH)
	);
	$requireds = '';
	$i=0;
	$bt_class='next';
	foreach ($required as $k => $v) {
		$i=($i+1)&1;
		$requireds .= '<div class="row '.($i?'p':'d').'">'.$GLOBALS['req'][$k].'<span class="'.($v?'ok':'no').'">'.($v?$GLOBALS['__y']:$GLOBALS['__n']).'</span></div>';
		if(!$v) $bt_class='error';
	}
	//Optional
	$raccomand = array(
		'magic' => !((function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc())||ini_get('magic_quotes_sybase')),
		'safe' => ini_get('safe_mode')==false,
		'error' => ini_get('display_errors')==0,
		'upload' => ini_get('file_uploads') == 1,
		'double' => (extension_loaded('mysql')||class_exists('mysqli'))&&(extension_loaded('SQLite')||class_exists('SQLite3'))
	);
	$raccomanded = '';
	$i=0;
	foreach ($raccomand as $k => $v) {
		$i=($i+1)&1;
		$raccomanded .= '<div class="row '.($i?'p':'d').'">'.$GLOBALS['rac'][$k].'<span class="'.($v?'ok':'no').'">'.($v?$GLOBALS['__y']:$GLOBALS['__n']).'</span></div>';
	}
	$content='<div class="left">
			<h2>'.$GLOBALS['__req'].'</h2>
			<hr/>
			<p>'.$GLOBALS['__req_d'].'</p>
			'.$requireds.'
			<div class="row l"></div>
		</div>
		<div class="right">
			<h2>'.$GLOBALS['__rac'].'</h2>
			<hr/>
			<p>'.$GLOBALS['__rac_d'].'</p>
			'.$raccomanded.'
			<div class="row l"></div>
		</div>';
	return array($bt_class,$content);
}
//Invio dei dati
if (SECURE::active()) {
	$params = SECURE::get('params');
	$data = $params['params'];
	$assoc = array();
	$error='';
	foreach ($data as $v)
		$assoc[$v['name']] = $v['value'];
	if (isset($assoc['next'])) {
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
					'db'=>extension_loaded('mysql')||class_exists('mysqli')||extension_loaded('SQLite')||class_exists('SQLite3'),
					'json'=>extension_loaded('json'),
					'write'=>test_write_cf()&&test_write_ch()&&(unlink(__base_path.'cache/template.php')),
					'http'=>(ini_get('allow_url_fopen') == true)||function_exists('curl_init'),
					'output'=>ini_get('output_buffering')==true,
					'bcrypt'=>(defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH)
				);
				$sup=true;
				foreach ($required as $v) if (!$v) $sup=false;
				if ($sup)
					$_SESSION['step']=$point=2;
			break;
			case 2 :
				if (!(ctype_alnum($assoc['nick'])&&(strlen($assoc['nick'])>3)))
					$error .= '<div class="error">'.$__not_nick.'</div>';
				if (strlen($assoc['pass'])<6)
					$error .= '<div class="error">'.$__not_pass.'</div>';
				elseif (strcmp($assoc['pass'],$assoc['pass2']))
					$error .= '<div class="error">'.$__not_pass2.'</div>';
				if (!FUNCTIONS::is_valid_email($assoc['email']))
					$error .= '<div class="error">'.$__not_email.'</div>';
				if (strlen(trim($assoc['site_name']))<6)
					$error .= '<div class="error">'.$__not_sname.'</div>';
				if ($error=='') {
					$_SESSION['site_data'] = array('nick'=>$assoc['nick'],'sname'=>$assoc['site_name'],'sdesc'=>$assoc['site_desc'],'email'=>$assoc['email'],'pass'=>$assoc['pass']);
					$_SESSION['step']=$point=3;
				}
			break;
			case 3 :
				switch ($assoc['dbt']) {
					case 'mysql' :
						if (!@mysql_connect($assoc['dbhost'],$assoc['dbuser'],$assoc['dbpass']))
							$error .= '<div class="error">'.$__not_db.' : '.mysql_error().'</div>';
						elseif (!@mysql_select_db($assoc['dbname']))
							$error .= '<div class="error">'.$__not_dbname.' : '.mysql_error().'</div>';
					break;
					case 'mysqli' :
						if (!$con = @mysqli_connect($assoc['dbhost'],$assoc['dbuser'],$assoc['dbpass']))
							$error .= '<div class="error">'.$__not_db.' : '.mysqli_error().'</div>';
						elseif (!@mysqli_select_db($con,$assoc['dbname']))
							$error .= '<div class="error">'.$__not_dbname.' : '.mysqli_error().'</div>';
					break;
					case 'SQLite' :
						if (!sqlite_open(__base_path.'config/'.$assoc['dbfile'].'.db'))
							$error .= '<div class="error">'.$__not_dblite.'</div>';
					break;
					case 'SQLite3' :
						try {
							new SQLite3(__base_path.'config/'.$assoc['dbfile'].'.db3');
						} catch (Exception $e) {
							$error .= '<div class="error">'.$e.'</div>';
						}
					break;
				}
				switch ($assoc['dbt2']) {
					case 'mysql' :
						if (($error=='')&&($assoc['dbhost2']!='')) {
							if (!@mysql_connect($assoc['dbhost2'],$assoc['dbuser2'],$assoc['dbpass2']))
								$error .= '<div class="error">'.$__not_db2.' : '.mysql_error().'</div>';
							elseif (!@mysql_select_db($assoc['dbname2']))
								$error .= '<div class="error">'.$__not_dbname2.' : '.mysql_error().'</div>';
						}
					break;
					case 'mysqli' :
						if (($error=='')&&($assoc['dbhost2']!='')) {
							if (!$con2 = @mysqli_connect($assoc['dbhost2'],$assoc['dbuser2'],$assoc['dbpass2']))
								$error .= '<div class="error">'.$__not_db2.' : '.mysqli_error().'</div>';
							elseif (!@mysqli_select_db($con2,$assoc['dbname2']))
								$error .= '<div class="error">'.$__not_dbname2.' : '.mysqli_error().'</div>';
						}
					break;
					case 'SQLite' :
						if ($error=='')
						if (!sqlite_open(__base_path.'config/'.$assoc['dbfile2'].'.db'))
							$error .= '<div class="error">'.$__not_dblite.'</div>';
					break;
					case 'SQLite3' :
						if ($error=='') {
							try {
								new SQLite3(__base_path.'config/'.$assoc['dbfile2'].'.db3');
							} catch (Exception $e) {
								$error .= '<div class="error">'.$e.'</div>';
							}
						}
					break;
				}
				if ($error=='') {
					$_SESSION['db_data'] = array('dbt'=>$assoc['dbt'],'dbhost'=>$assoc['dbhost'],'dbuser'=>$assoc['dbuser'],'dbpass'=>$assoc['dbpass'],'dbname'=>$assoc['dbname'],'dbpref'=>$assoc['dbpref'],'dbfile'=>$assoc['dbfile'],'dbt2'=>$assoc['dbt2'],'dbhost2'=>$assoc['dbhost2'],'dbuser2'=>$assoc['dbuser2'],'dbpass2'=>$assoc['dbpass2'],'dbname2'=>$assoc['dbname2'],'dbpref2'=>$assoc['dbpref2'],'dbfile2'=>$assoc['dbfile2']);
					$_SESSION['step']=$point=4;
				}
			break;
			case 4 :
				//Installazione
				//Scrittura files di configurazione
				$include = 'include_once(__base_path.\'levels/0/db_'.$_SESSION['db_data']['dbt'].'.php\');';
				if (($_SESSION['db_data']['dbt']=='mysql')||($_SESSION['db_data']['dbt']=='mysqli'))
					$db = 'DB::set_DB(new ALE'.$_SESSION['db_data']['dbt'].'(\''.$_SESSION['db_data']['dbhost'].'\',\''.$_SESSION['db_data']['dbuser'].'\',\''.$_SESSION['db_data']['dbpass'].'\',\''.$_SESSION['db_data']['dbname'].'\',\''.$_SESSION['db_data']['dbpref'].'\'));';
				else
					$db = 'DB::set_DB(new ALE'.$_SESSION['db_data']['dbt'].'(\''.$_SESSION['db_data']['dbfile'].'\',\''.$_SESSION['db_data']['dbpref'].'\'));';
				file_put_contents(__base_path.'config/dbconfig.php','<?php '.$include.$db.' ?>');
				if ($_SESSION['db_data']['dbhost2'].$_SESSION['db_data']['dbfile2']!='') {
					$include = 'include_once(__base_path.\'levels/0/db_'.$_SESSION['db_data']['dbt2'].'.php\');';
					if (($_SESSION['db_data']['dbt2']=='mysql')||($_SESSION['db_data']['dbt2']=='mysqli'))
						$db = 'DB2::set_DB(new ALE'.$_SESSION['db_data']['dbt2'].'(\''.$_SESSION['db_data']['dbhost2'].'\',\''.$_SESSION['db_data']['dbuser2'].'\',\''.$_SESSION['db_data']['dbpass2'].'\',\''.$_SESSION['db_data']['dbname2'].'\',\''.$_SESSION['db_data']['dbpref2'].'\'));';
					else
						$db = 'DB2::set_DB(new ALE'.$_SESSION['db_data']['dbt2'].'(\''.$_SESSION['db_data']['dbfile2'].'\',\''.$_SESSION['db_data']['dbpref2'].'\'));';
				} else {
					$include = 'include_once(__base_path.\'levels/0/db_'.$_SESSION['db_data']['dbt'].'.php\');';
					if (($_SESSION['db_data']['dbt']=='mysql')||($_SESSION['db_data']['dbt']=='mysqli'))
						$db = 'DB2::set_DB(new ALE'.$_SESSION['db_data']['dbt'].'(\''.$_SESSION['db_data']['dbhost'].'\',\''.$_SESSION['db_data']['dbuser'].'\',\''.$_SESSION['db_data']['dbpass'].'\',\''.$_SESSION['db_data']['dbname'].'\',\''.$_SESSION['db_data']['dbpref'].'\'));';
					else
						$db = 'DB2::set_DB(new ALE'.$_SESSION['db_data']['dbt'].'(\''.$_SESSION['db_data']['dbfile'].'\',\''.$_SESSION['db_data']['dbpref'].'\'));';
				}
				file_put_contents(__base_path.'admin/config/dbconfig.php','<?php '.$include.$db.' ?>');
				//Creazione tabelle
				include(__base_path.'admin/levels/0/db.php');
				$users = DB::create('users');
				$admin = DB2::create('admins');
				try {
					$users->property('name')->dimension(30)->not_null()->end()
						->property('lastname')->dimension(30)->not_null()->end()
						->property('nick')->dimension(30)->not_null()->unique()->end()
						->property('password')->dimension(125)->not_null()->unique()->end()
						->property('email')->dimension(100)->not_null()->unique()->end()
						->property('lastVisit')->type('TIMESTAMP')->end()
						->property('registerDate')->type('TIMESTAMP')->set_default(CURRENT)->end()
						->property('actived')->type('BOOLEAN')->set_default(0)->not_null()->end()
						->property('verifyCode')->dimension(10)->not_null()->end()
						->property('cookieCode')->dimension(60)->not_null()->end()
						->property('level')->type('INT')->dimension(1)->set_default(9)->unsigned()->end()
						->property('info')->type('INT')->dimension(1)->set_default(0)->not_null()->end()
						->property('lang')->dimension(5)->not_null()->end()
						->property('banned')->type('BOOLEAN')->set_default(0)->not_null()->end();
					$admin->property('nick')->dimension(30)->not_null()->unique()->end()
						->property('email')->dimension(100)->not_null()->unique()->end()
						->property('lastVisit')->type('TIMESTAMP')->end()
						->property('password')->dimension(125)->not_null()->unique()->end()
						->property('sessionCode')->dimension(60)->not_null()->end()
						->property('level')->type('INT')->dimension(1)->set_default(3)->unsigned()->end()
						->property('lang')->dimension(5)->end()
						->property('banned')->type('BOOLEAN')->set_default(0)->not_null()->end();
					if ($users->save()&&$admin->save())
						$_SESSION['step']=$point=5;					
					$pass=CRYPT::BF($_SESSION['site_data']['pass'],6);
					$pass=md5($pass).':'.substr($pass,0,29).'|'.CRYPT::BF($_SESSION['site_data']['pass'],7);
					DB::insert('users',array('nick'=>$_SESSION['site_data']['nick'],'password'=>$pass,'email'=>$_SESSION['site_data']['email'],'level'=>0,'actived'=>1));
					$pass=CRYPT::BF($_SESSION['site_data']['pass'],6);
					$pass=md5($pass).':'.substr($pass,0,29).'|'.CRYPT::BF($_SESSION['site_data']['pass'],7);
					DB2::insert('admins',array('nick'=>$_SESSION['site_data']['nick'],'password'=>$pass,'email'=>$_SESSION['site_data']['email'],'level'=>0));
					//Files di configurazione
					file_put_contents(__base_path.'config/infoserver.php','<?php define(\'__http_path\',\''.dirname($_SERVER['SCRIPT_NAME']).'/\');
define(\'__http_host\',\'http://'.$_SERVER['SERVER_NAME'].'/\'); ?>');
					
				} catch (Exception $e) {
					$error .= '<div class="error">'.$e.'</div>';
				}
				if ($error=='')
					$point=5;
			break;
			case 5 :
				$point=$_SESSION['step']='end';
		}
	}
	if (isset($assoc['back'])) {
		if (($point>1)&&($point<5))
			$_SESSION['step']=--$point;	
	}
	if (($point==2)&&isset($_SESSION['site_data'])) {
		$assoc['nick']= $_SESSION['site_data']['nick'];
		$assoc['site_name']= $_SESSION['site_data']['sname'];
		$assoc['site_desc']= $_SESSION['site_data']['sdesc'];
		$assoc['email']= $_SESSION['site_data']['email'];
		$assoc['pass']= $_SESSION['site_data']['pass'];
	}
	if (($point==3)&&isset($_SESSION['db_data'])) {
		$assoc['dbhost']= $_SESSION['db_data']['dbhost'];
		$assoc['dbuser']= $_SESSION['db_data']['dbuser'];
		$assoc['dbpass']= $_SESSION['db_data']['dbpass'];
		$assoc['dbname']= $_SESSION['db_data']['dbname'];
		$assoc['dbpref']= $_SESSION['db_data']['dbpref'];
		$assoc['dbhost2']= $_SESSION['db_data']['dbhost2'];
		$assoc['dbuser2']= $_SESSION['db_data']['dbuser2'];
		$assoc['dbpass2']= $_SESSION['db_data']['dbpass2'];
		$assoc['dbname2']= $_SESSION['db_data']['dbname2'];
		$assoc['dbpref2']= $_SESSION['db_data']['dbpref2'];
	}
	switch ($point) {
		case 1 : 
			$arr = point1();
			$content=$arr[1];
			$bt_class=$arr[0];
			break;
		case 2 :
			$content='<h2>'.$__configuration.'</h2>
			<div class="left">
				<div class="data">
					<div class="l">
						'.$__name.'*
					</div>
					<div class="r">
						<input type="text" name="site_name" value="'.(isset($assoc['site_name'])?$assoc['site_name']:$__site_with).'" />
						<div>'.$__name_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__desc.'
					</div>
					<div class="r">
						<textarea name="site_desc">'.(isset($assoc['site_desc'])?$assoc['site_desc']:'').'</textarea>
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
						<input type="text" name="email" value="'.(isset($assoc['email'])?$assoc['email']:'').'" />
						<div>'.$__email_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__nick.'*
					</div>
					<div class="r">
						<input type="text" name="nick" value="'.(isset($assoc['nick'])?$assoc['nick']:'admin').'" />
						<div>'.$__nick_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__pass.'*
					</div>
					<div class="r">
						<input name="pass" type="password" value="'.(isset($assoc['pass'])?$assoc['pass']:'').'" />
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
			$content='<h2>'.$__database.'</h2>
			<div class="data">
				<div class="l">
					'.$__dbt.'*
				</div>
				<div class="r">
					<select name="dbt">'.(extension_loaded('mysql')?'<option value="mysql">MySQL</option>':'').(extension_loaded('mysqli')?'<option value="mysqli">MySQLi</option>':'').(class_exists('SQLite3')?'<option value="SQLite3">SQLite3</option>':'').(extension_loaded('SQLite')?'<option value="SQLite">SQLite</option>':'').'</select>
					<div>'.$__dbt_d.'</div>
				</div>
			</div>
			<div class="sql first">
				<div class="data">
					<div class="l">
						'.$__host.'*
					</div>
					<div class="r">
						<input type="text" name="dbhost" value="'.(isset($assoc['dbhost'])?$assoc['dbhost']:'localhost').'" />
						<div>'.$__host_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbuser.'*
					</div>
					<div class="r">
						<input type="text" name="dbuser" value="'.(isset($assoc['dbuser'])?$assoc['dbuser']:'root').'" />
						<div>'.$__dbuser_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbpass.'
					</div>
					<div class="r">
						<input type="password" name="dbpass" value="'.(isset($assoc['dbpass'])?$assoc['dbpass']:'').'"/>
						<div>'.$__dbpass_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbname.'*
					</div>
					<div class="r">
						<input type="text" name="dbname" value="'.(isset($assoc['dbname'])?$assoc['dbname']:'').'"/>
						<div>'.$__dbname_d.'</div>
					</div>
				</div>
			</div>
			<div class="lite first" style="display:none">
				<div class="data">
					<div class="l">
						'.$__dbfile.'*
					</div>
					<div class="r">
						<input type="text" name="dbfile" value="'.(isset($assoc['dbfile'])?$assoc['dbfile']:(RAND::word())).'"/>
						<div>'.$__dbfile_d.'</div>
					</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__dbpref.'*
				</div>
				<div class="r">
					<input type="text" name="dbpref" value="'.(isset($assoc['dbpref'])?$assoc['dbpref']:(RAND::word()).'__').'"/>
					<div>'.$__dbpref_d.'</div>
				</div>
			</div>
			<h2>'.$__db2.'</h2>
			<p>'.$__db2_d.'</p>
			<div class="data">
				<div class="l">
					'.$__dbt.'*
				</div>
				<div class="r">
					<select name="dbt2">'.(extension_loaded('mysql')?'<option value="mysql" selected="selected">MySQL</option>':'').(extension_loaded('mysqli')?'<option value="mysqli">MySQLi</option>':'').(extension_loaded('SQLite')?'<option value="SQLite" selected="selected">SQLite</option>':'').(class_exists('SQLite3')?'<option value="SQLite3" selected="selected">SQLite3</option>':'').'</select>
					<div>'.$__dbt_d.'</div>
				</div>
			</div>
			<div class="sql second" '.((extension_loaded('SQLite')||class_exists('SQLite3'))?'style="display:none"':'').'>
				<div class="data">
					<div class="l">
						'.$__host.'
					</div>
					<div class="r">
						<input type="text" name="dbhost2" value="'.(isset($assoc['dbhost2'])?$assoc['dbhost2']:'').'"/>
						<div>'.$__host_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbuser.'
					</div>
					<div class="r">
						<input type="text" name="dbuser2" value="'.(isset($assoc['dbuser2'])?$assoc['dbuser2']:'').'"/>
						<div>'.$__dbuser_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbpass.'
					</div>
					<div class="r">
						<input type="password" name="dbpass2" value="'.(isset($assoc['dbpass2'])?$assoc['dbpass2']:'').'"/>
						<div>'.$__dbpass_d.'</div>
					</div>
				</div>
				<div class="data">
					<div class="l">
						'.$__dbname.'
					</div>
					<div class="r">
						<input type="text" name="dbname2" value="'.(isset($assoc['dbname2'])?$assoc['dbname2']:'').'"/>
						<div>'.$__dbname_d.'</div>
					</div>
				</div>
			</div>
			<div class="lite second" '.((extension_loaded('SQLite')||class_exists('SQLite3'))?'':'style="display:none"').'>
				<div class="data">
					<div class="l">
						'.$__dbfile.'*
					</div>
					<div class="r">
						<input type="text" name="dbfile2" value="'.(isset($assoc['dbfile'])?$assoc['dbfile']:(RAND::word())).'"/>
						<div>'.$__dbfile_d.'</div>
					</div>
				</div>
			</div>
			<div class="data">
				<div class="l">
					'.$__dbpref.'
				</div>
				<div class="r">
					<input type="text" name="dbpref2" value="'.(isset($assoc['dbpref2'])?$assoc['dbpref2']:'').'"/>
					<div>'.$__dbpref_d.'</div>
				</div>
			</div>';
			break;
			case 4 :
				$content = '<h2>'.$__sum.'</h2>
				<div class="left sum">
					<h3>'.$__configuration.'</h3>
					<hr/><br/>
					<div class="row p">'.$__name.'<span class="info">'.$_SESSION['site_data']['sname'].'</span></div>
					<div class="row d">'.$__email.'<span class="info">'.$_SESSION['site_data']['email'].'</span></div>
					<div class="row p">'.$__nick.'<span class="info">'.$_SESSION['site_data']['nick'].'</span></div>
					<div class="row d">'.$__pass.'<span class="info">***</span></div>
					<div class="row"></div>
				</div>
				<div class="right">
					<h3>'.$__database.'</h3>
					<hr/><br/>
					<div class="row p">'.$__dbt.'<span class="info">'.$_SESSION['db_data']['dbt'].'</span></div>';
				switch ($_SESSION['db_data']['dbt']) {
					case 'mysql' :
					case 'mysqli' :
						$content .= '<div class="row d">'.$__host.'<span class="info">'.$_SESSION['db_data']['dbhost'].'</span></div>
					<div class="row p">'.$__dbuser.'<span class="info">'.$_SESSION['db_data']['dbuser'].'</span></div>
					<div class="row d">'.$__dbpass.'<span class="info">'.$_SESSION['db_data']['dbpass'].'</span></div>
					<div class="row p">'.$__dbname.'<span class="info">'.$_SESSION['db_data']['dbname'].'</span></div>
					<div class="row d">'.$__dbpref.'<span class="info">'.$_SESSION['db_data']['dbpref'].'</span></div>';
					break;
					case 'SQLite' :
					case 'SQLite3' :
						$content .= '<div class="row d">'.$__dbfile.'<span class="info">'.$_SESSION['db_data']['dbfile'].'</span></div>
					<div class="row d">'.$__dbpref.'<span class="info">'.$_SESSION['db_data']['dbpref'].'</span></div>';
					break;
				}
				if ($_SESSION['db_data']['dbhost2'].$_SESSION['db_data']['dbfile2'] != '') {
					$content .= '<h3>'.$__db2.'</h3><hr/><br/>
					<div class="row p">'.$__dbt.'<span class="info">'.$_SESSION['db_data']['dbt2'].'</span></div>';
					switch ($_SESSION['db_data']['dbt2']) {
						case 'mysql' :
						case 'mysqli' :
							$content .= '<div class="row d">'.$__host.'<span class="info">'.$_SESSION['db_data']['dbhost2'].'</span></div>
						<div class="row p">'.$__dbuser.'<span class="info">'.$_SESSION['db_data']['dbuser2'].'</span></div>
						<div class="row d">'.$__dbpass.'<span class="info">'.$_SESSION['db_data']['dbpass2'].'</span></div>
						<div class="row p">'.$__dbname.'<span class="info">'.$_SESSION['db_data']['dbname2'].'</span></div>
						<div class="row d">'.$__dbpref.'<span class="info">'.$_SESSION['db_data']['dbpref2'].'</span></div>';
						break;
						case 'SQLite' :
						case 'SQLite3' :
							$content .= '<div class="row d">'.$__dbfile.'<span class="info">'.$_SESSION['db_data']['dbfile2'].'</span></div>
						<div class="row d">'.$__dbpref.'<span class="info">'.$_SESSION['db_data']['dbpref2'].'</span></div>';
						break;
					}
				}
				$content .= '</div>';
				$arr = point1();
				$content.=$arr[1];
			break;
			case 5 :
				$content = '<h2>'.$__end.'</h2>'.$__end_d.'<br/><br/><center><button onclick="next()" class="next">'.$__fine.'</button></center>';
			break;
			case 'end' :
				$content = '<meta http-equiv="REFRESH" content="0;url=index.html"/>';
			break;
	}
	SECURE::returns(array('content'=>$content,'point'=>$point,'error'=>$error));
	
}
//Controlli pre installazione
//Required
$lngs = LANG::get_list();
$lng='';
foreach ($lngs as $k=>$v) {
	$lng.='<option value="'.$k.'" '.(($v['sn']==LANG::short())?'selected="selected"':'').'>'.$v['n'].'</option>';
}
$lang_sel=$__lang_sel.'<select onchange="location.href=\'?lang=\'+this.value">'.$lng.'</select>';
$bt_class='go';
if ($point==1) {
	$arr = point1();
	$content=$arr[1].'<script type="text/javascript">var load_sec=false;</script>';
	$bt_class=$arr[0];
} else {
	$content = '<h2>'.$__loading.'</h2><script type="text/javascript">var load_sec=true;</script>';
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>ALExxia Installation</title>
		<link rel="stylesheet" href="css/images.css" />
		<link rel="stylesheet" href="css/secure.css" />
		<link rel="stylesheet" href="install/style.css" />
		<link rel="icon" href="media/images/favicon.png" sizes="64x64" type="image/png" />
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="install/script.js"></script>
		<script type="text/javascript" src="admin/js/jsbn.js"></script>
		<script type="text/javascript" src="admin/js/jsbn2.js"></script>
		<script type="text/javascript" src="admin/js/base64.js"></script>
		<script type="text/javascript" src="admin/js/base64_2.js"></script>
		<script type="text/javascript" src="admin/js/utf8.js"></script>
		<script type="text/javascript" src="admin/js/prng4.js"></script>
		<script type="text/javascript" src="admin/js/rng.js"></script>
		<script type="text/javascript" src="admin/js/aes.js"></script>
		<script type="text/javascript" src="admin/js/pad-zeropadding-min.js"></script>
		<script type="text/javascript" src="admin/js/rsa.js"></script>
		<script type="text/javascript" src="admin/js/json2.js"></script>
		<script type="text/javascript" src="admin/js/rsa2.js"></script>
		<script type="text/javascript" src="admin/js/md5.js"></script>
		<script type="text/javascript" src="admin/js/secure.js"></script>
		<script type="text/javascript" src="admin/js/isaac.js"></script>
		<script type="text/javascript" src="admin/js/bCrypt.js"></script>
		<script type="text/javascript">
			var req_status="<?php echo $bt_class?>";
		</script>
	</head>
	<body>
		<header>
			<img height="120" class="logo" src="media/images/logo.png"/>
			<div class="continue"><input type="hidden" name="next" value=" "/>
				<?php echo $lang_sel?><button style="display:none" id="go_back" class="comb l" type="button"><?php echo $__prev?></button><button id="go_next" class="error"><?php echo $__continue?></button>
			</div>
		</header>
		<div class="content">
			<div class="main_content">
				<?php echo $content ?>
			</div>
			<div title="<?php echo $__secure ?>" class="secure_status">
				<div class="points"></div>
				<div class="img unsecure"></div>
			</div>
		</div>
	</body>
</html>
<?php
exit(0);
?>