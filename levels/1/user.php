<?php
class USER {
	protected static $user=null;

	protected static function load() {
		if (self::$user==null) {
			if (COOKIE::exists('ale_user')) {
				$nick = COOKIE::val('ale_user');
				$user_data = DB::assoc(DB::select('*','users','WHERE nick = ',$nick));
				if (strcmp($user_data['nick'],$nick)) {
					COOKIE::set('ale_user');
					COOKIE::set('ale_auth');
					self::$user=false;
				} else {
					if (CRYPT::BF_check(COOKIE::val('ale_auth'),$line['sauth'])) {
						COOKIE::set('ale_user');
						COOKIE::set('ale_auth');
						self::$user=false;
					} else {
						DB::update('users',array('last'=>time()),'WHERE id = ',$user_data['id']);
						self::$user=$user_data;
						COOKIE::extend('ale_user');
						COOKIE::extend('ale_auth');
					}
				}
			} else
				self::$user=false;
		}
	}
	
	public static function logged() {
		self::load();
		return (self::$user===false)?false:true;
	}
	
	public static function level() {
		self::load();
		return (self::$user===false)?10:$user['level'];
	}
	
	public static function data($k) {
		self::load();
		if (self::$user===false)
			return false;
		else
			return self::$user[$k];
	}
}
?>