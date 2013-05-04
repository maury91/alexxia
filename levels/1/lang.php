<?php
class LANG {
	private static $lang=null;

	private static function get_lang_info($lang) {
		if (file_exists(__base_path.'langs/'.$lang.'/'.$lang.'.php')) {
			include __base_path.'langs/'.$lang.'/'.$lang.'.php';
			if (isset($short_name)&&isset($name)&&isset($accept))
				return array('sn'=>$short_name,'n'=>$name,'a'=>$accept);
			else
				return false;
		} else
			return false;
	}

	private static function list_langs() {
		$langs = FUNCTIONS::list_dir(__base_path.'langs/');
		$__langs = array();
		$__flangs = array();
		foreach ($langs as $v) {
			$info = self::get_lang_info($v);
			if ($info) {
				$__langs[$info['sn']] = $info;
				foreach ($info['a'] as $v)
					$__flangs[strtolower($v)] = $info['sn'];
			}
		}
		file_put_contents(__base_path.'cache/langs.php','<?php $__langs = '.var_export($__langs,true).'; $__flangs = '.var_export($__flangs,true).'; ?>');
		return $__langs;
	}
	
	public static function get_list() {
		if (file_exists(__base_path.'cache/langs.php'))
			include __base_path.'cache/langs.php';
		else
			return self::list_langs();
		return $__langs;
	}
	
	private static function get_flist() {
		if (!file_exists(__base_path.'cache/langs.php'))
			self::list_langs();
		include __base_path.'cache/langs.php';
		return $__flangs;
	}
	
	private static function find_lang() {
		if (GET::exists('lang'))
			$__lang = GET::val('lang');
		elseif (COOKIE::exists('ale_lang')) 
			$__lang = COOKIE::val('ale_lang');
		elseif (USER::logged()&&(USER::data('lang')!=''))
			$__lang = USER::data('lang');
		else {
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) { //Scorro tutti i linguaggi accettati dal browser
				$__flangs = self::get_flist();
				$langs = explode(",",strtr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']),';',','));
				foreach ($langs as $lang) {
					if (isset($__flangs[$lang])) {
						$__lang = $__flangs[$lang];
						break;
					}
				}
			}
			if (empty($__lang)) //Se non c'è ne nessuno
				foreach($__langs as $__lang => $v) //Prima lingua presente (solitamente inglese)
					break;
		}
		$__langs = self::get_list();
		if (!isset($__langs[$__lang])) {
			foreach($__langs as $__lang=>$v) //Prima lingua presente (solitamente inglese)
				break;
		}
		COOKIE::set('ale_lang',$__lang,7*24*60);
		self::$lang = $__langs[$__lang];
		$GLOBALS['__lang'] = $__lang;
	}
	
	public static function name() {
		if (self::$lang==null)
			self::find_lang();
		return self::$lang['n'];
	}
	
	public static function short() {
		if (self::$lang==null)
			self::find_lang();
		return self::$lang['sn'];
	}
	
}
?>