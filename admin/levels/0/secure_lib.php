<?php
@session_start();
set_include_path(__base_path.'admin/levels/0/');
require_once(__base_path.'admin/levels/0/Crypt/RSA.php');

class SECURE {
	
	static public function new_aes($session,$cripted_data) {
		$rsa_pub_key = json_decode(SECURE::decrypt_RSA($session,$cripted_data) ,true);
		$aes_key = base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF));
		$sess_id = 'aes1_'.base64_encode(crypt_random());
		$_SESSION[$sess_id]['key'] = $aes_key;
		$crypted=SECURE::crypt_RSA($rsa_pub_key,$aes_key);
		return json_encode(array('key'=>$crypted,'sess'=>$sess_id));
	}
	
	static public function crypt_AES($session,$value) {
		require_once('Crypt/aes2.php');
		$res = AesCtr::encrypt($value, $_SESSION[$session]['key'], 256);
		return $res;
	}
	
	static public function decrypt_AES($session,$value) {
		require_once('Crypt/aes2.php');
		$res = AesCtr::decrypt($value, $_SESSION[$session]['key'], 256);
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
		$rsa->loadKey($_SESSION[$session]['privatekey'],CRYPT_RSA_PRIVATE_FORMAT_PKCS1); // private key
		$s = new Math_BigInteger($value, 16);
		return $rsa->decrypt($s->toBytes());
	}

	static public function init() {
		define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
		$rsa = new Crypt_RSA();
		$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_RAW);
		$key = $rsa->createKey(512);
		$sess_id = base64_encode(crypt_random());
		$_SESSION[$sess_id] = $key;
		$e = new Math_BigInteger($key['publickey']['e'], 10);
		$n = new Math_BigInteger($key['publickey']['n'], 10);
		return json_encode(array('RSA' => array('e' => $e->toHex(), 'n' => $n->toHex()), 'id' => $sess_id));
	}

}

?>