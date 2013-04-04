<?php
class RAND {
	static public function bytes($len=3) {
		if (function_exists('openssl_random_pseudo_bytes'))
			return openssl_random_pseudo_bytes($len);
		$ret = '';
		for($i=0; $i < $len; $i++) 
			$ret .= chr(rand(1,255));
		return $ret;
	}
	
	static public function word($len=5) {
		if (function_exists('openssl_random_pseudo_bytes'))
			return substr(strtr(base64_encode(openssl_random_pseudo_bytes(ceil($len/1.4))), '+', '.'),0,$len);
		$ret = '';
		$chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
		for($i=0; $i < $len; $i++) 
			$ret .= $chars[array_rand($chars)];
		return $ret;
	}

}
?>