<?php
class CRYPT {	
	const BF_STRENGH = 8;
	
	public static function BF($val,$strength=0) {
		if ($strength<4||$strength>31) $strength=self::BF_STRENGH;
		$salt = '$2a$'.str_pad($strength,2,'0',STR_PAD_LEFT).'$'.(RAND::word(22));
		return crypt($val,$salt);
	}
	
	public static function is_BF($val) {
		return substr($val, 0, 4)=='$2a$';
	}
	
	public static function BF_check($original,$crypted) {
		if (is_bf($crypted))
			return crypt($original, $crypted) == $crypted;
		else
			return false;
	}
}
?>