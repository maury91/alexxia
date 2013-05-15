<?php
/**
 *	Secure Connection (php) for ALExxia
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
@session_start();
set_include_path(__base_path.'admin/levels/0/');
require_once(__base_path.'admin/levels/0/Crypt/RSA.php');

class SECURE {
	static private $params;

	static public function active() {
		return self::$params!=NULL;
	}
	
	static public function init() {
		if (isset($_POST['init']))
			SECURE::start();
		if (isset($_POST['cr'])&&isset($_POST['data'])) {
			$data = SECURE::decrypt($_POST['cr'],$_POST['data']);
			self::$params = json_decode($data,true);
			if ((self::$params!=NULL)&&(self::$params['action']=='new_aes'))
				SECURE::new_aes(self::$params['params']['rsa_key']);
		}
	}
	
	static public function all() {
		return self::$params;
	}
	
	static public function libs() {
		HTML::add_script('admin/js/jsbn.js','admin/js/jsbn2.js','admin/js/base64.js','admin/js/base64_2.js','admin/js/utf8.js','admin/js/prng4.js','admin/js/rng.js','admin/js/aes.js','admin/js/pad-zeropadding-min.js','admin/js/rsa.js','admin/js/json2.js','admin/js/rsa2.js','admin/js/md5.js','admin/js/secure.js','admin/js/isaac.js','admin/js/bCrypt.js');
		HTML::add_style('css/secure.css','css/images.css');
	}
	
	static public function get($v) {
		return isset(self::$params[$v])?self::$params[$v]:null;
	}
	
	static public function exists($v) {
		return isset(self::$params[$v]);
	}
	
	static public function returns($vals) {
		echo json_encode(array('cr' => (SECURE::crypt_AES($_POST['cr'],json_encode($vals)))));
		exit(0);
	}
	
	static public function new_aes($rsa_pub_key) {
		$aes_key = substr(base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF)),0,32);
		$sess_id = base64_encode(crypt_random());
		$_SESSION[$sess_id]['key'] = $aes_key;
		$_SESSION[$sess_id]['type'] = 'aes';
		$crypted=SECURE::crypt_RSA($rsa_pub_key,$aes_key);
		echo json_encode(array('key'=>$crypted,'sess'=>$sess_id));
		exit(0);
	}
	
	static public function crypt_AES($session,$value) {
		require_once('Crypt/AES.php');
		$cipher = new Crypt_AES();
		$iv = substr(base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF)),0,32);
		$cipher->setIV($iv);
		$cipher->setKey($_SESSION[$session]['key']);
		//Padding manuale (zero padding)
		$to_crypt = str_pad($value,ceil(strlen($value)/16)*16,"\0");
		$cipher->disablePadding();
		$res = base64_encode($iv).base64_encode($cipher->encrypt($to_crypt));
		return $res;
	}
	
	static public function decrypt_AES($session,$value) {
		require_once('Crypt/AES.php');
		$cipher = new Crypt_AES(); 
		$iv = substr($value,0,44);
		$cipher->setIV(substr(base64_decode($iv),0,32));
		$cipher->setKey($_SESSION[$session]['key']);
		$cipher->disablePadding();
		$data = substr($value,44);
		$res = trim($cipher->decrypt(base64_decode($data)));
		return $res;
	}
	
	static public function crypt_RSA($rsa_pub_key,$value) {
		$rsa = new Crypt_RSA();
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$rsa->loadKey( array ('e'=>new Math_BigInteger($rsa_pub_key['e'],16), 'n'=>new Math_BigInteger($rsa_pub_key['n'],16)) );
		return bin2hex($rsa->encrypt($value));
	}
	
	static public function decrypt_RSA($session,$value) {
		$rsa = new Crypt_RSA();
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$rsa->loadKey($_SESSION[$session]['key']['privatekey'],CRYPT_RSA_PRIVATE_FORMAT_PKCS1); // private key
		$s = new Math_BigInteger($value, 16);
		return $rsa->decrypt($s->toBytes());
	}
	
	static public function decrypt($session,$value) {
		switch ($_SESSION[$session]['type']) {
			case 'rsa' : return SECURE::decrypt_RSA($session,$value); break;
			case 'aes' : return SECURE::decrypt_AES($session,$value); break;
		}
	}

	static public function start() {
		define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
		$rsa = new Crypt_RSA();
		$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_RAW);
		$key = $rsa->createKey(512);
		$sess_id = base64_encode(crypt_random());
		$_SESSION[$sess_id]['key'] = $key;
		$_SESSION[$sess_id]['type'] = 'rsa';
		$e = new Math_BigInteger($key['publickey']['e'], 10);
		$n = new Math_BigInteger($key['publickey']['n'], 10);
		echo json_encode(array('RSA' => array('e' => $e->toHex(), 'n' => $n->toHex()), 'id' => $sess_id));
		exit(0);
	}

}

?>