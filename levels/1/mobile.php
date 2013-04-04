<?php
class MOBILE {
	static protected $mobile=null;

	public static function is_mobile() {
		if (self::$mobile==null) {
			if (GET::exists('nomob')) {
				COOKIE::set('ale_mobile','false',30*24*60);
				self::$mobile=false;
			} else {
				//Se ci son dei cookies imposto la visualizzazione mobile secondo i valori dei cookies
				if (COOKIE::exists('ale_mobile'))
					self::$mobile=COOKIE::val('ale_mobile')=='true';
				else {
					//Se non ci son cookies controllo che dispositivo è
					self::$mobile = false;
					$devices = array(
							"Android" => "android.*mobile",
							"Androidtablet" => "android(?!.*mobile)",
							"Blackberry" => "blackberry",
							"Blackberrytablet" => "rim tablet os",
							"Iphone" => "(iphone|ipod)",
							"Ipad" => "(ipad)",
							"Palm" => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
							"Windows" => "windows ce; (iemobile|ppc|smartphone)",
							"Windowsphone" => "windows phone os",
							"Generic" => "(webos|android|kindle|mobile|mmp|midp|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap|opera mini|opera mobi)"
						);
					//Se ha un WAP PROFILE è un cellulare
					if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
						self::$mobile = true;
					} elseif (strpos($_SERVER['HTTP_ACCEPT'], 'text/vnd.wap.wml') > 0 || strpos($_SERVER['HTTP_ACCEPT'], 'application/vnd.wap.xhtml+xml') > 0) {
						//Se accetta connessioni WAP è un cellulare
						self::$mobile = true;
					} else {
						//Controllo tutta la lista dei cellulari finchè non lo trovo
						foreach ($devices as $device => $regexp) 
							if(preg_match("/$regexp/i", $_SERVER['HTTP_USER_AGENT'])) 
								self::$mobile = true;	
					}
				}
				//Imposto un cookie in modo da non dover rifare questa procedura
				COOKIE::set('ale_mobile',(self::$mobile)?'true':'false',30*24*60);
			}
		}
		return self::$mobile;
	}
}
?>