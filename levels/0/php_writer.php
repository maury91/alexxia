<?php
class PHP_WRITER {
	public static function save($file,$arr,$content) {
		$f = fopen($file,"w");
		fwrite($f,'<?php'."\n");
		foreach ($content as $k => $v) {
			fwrite($f,"\t".'$'.$k.' = ');
			if ($v == 'string')
				fwrite($f,"'".addcslashes($arr[$k],"'")."'");
			elseif ($v == 'bool')
				fwrite($f,$arr[$k]?'true':'false');
			else
				fwrite($f,$arr[$k]);
			fwrite($f,";\n");
		}
		fwrite($f,'?>');
		fclose($f);
	}
}
?>