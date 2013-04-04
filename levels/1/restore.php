<?php
/*
	Riparazione file corrotti del CMS
*/
class RESTORE {
	
	const $translate = array('string' => T_CONSTANT_ENCAPSED_STRING,'int' => T_LNUMBER,'bool' => T_STRING);

	public static function file($name,$fullname) {
		require __base_path.'struct/'.$name;
		$tokens = token_get_all(file_get_contents($fullname));
		$my_values = array();
		foreach ($__content as $k => $v) {
			$foundvar = false;
			$found_value = '';
			foreach($tokens as $a) {
				if ($foundvar) {
					if (($a[0] == $translate[$v])&&(($v!='bool')||($a[1]=='true')||($a[1]=='false'))) {
						$found_value=eval('return '.$a[1].';');
						break;
					}				
				} elseif (($a[0] == T_VARIABLE)&&($a[1] == '$'.$k))
					$foundvar = true;
			}
			if ($found_value==''&&$v=='int')
				$found_value=0;
			$my_values[$k] = $found_value;
		}
		PHP_WRITER::save($fullname,$my_values,$__content);
	}
}
?>