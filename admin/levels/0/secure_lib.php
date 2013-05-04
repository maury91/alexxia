<?php
@session_start();
set_include_path(__base_path.'admin/levels/0/');
require_once(__base_path.'admin/levels/0/Crypt/RSA.php');

class SECURE {
	
	static public function new_aes($rsa_pub_key) {
		$aes_key = substr(base64_encode(crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF).crypt_random($min = 0, $max = 0xEFFFFFFF)),0,32);
		$sess_id = base64_encode(crypt_random());
		$_SESSION[$sess_id]['key'] = $aes_key;
		$_SESSION[$sess_id]['type'] = 'aes';
		$crypted=SECURE::crypt_RSA($rsa_pub_key,$aes_key);
		return json_encode(array('key'=>$crypted,'sess'=>$sess_id));
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

	static public function init() {
		define('CRYPT_RSA_MODE', CRYPT_RSA_MODE_INTERNAL);
		$rsa = new Crypt_RSA();
		$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_RAW);
		$key = $rsa->createKey(512);
		$sess_id = base64_encode(crypt_random());
		$_SESSION[$sess_id]['key'] = $key;
		$_SESSION[$sess_id]['type'] = 'rsa';
		$e = new Math_BigInteger($key['publickey']['e'], 10);
		$n = new Math_BigInteger($key['publickey']['n'], 10);
		return json_encode(array('RSA' => array('e' => $e->toHex(), 'n' => $n->toHex()), 'id' => $sess_id));
	}

}

?>