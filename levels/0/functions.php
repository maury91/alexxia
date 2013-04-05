<?php
class FUNCTIONS {
	public static function list_dir($directory) {
		//Lista di tutte le cartelle dentro una directory
		$dirs= array();
		if ($handle = opendir($directory."/"))
		{
			while ($file = readdir($handle))
			{
				if (is_dir($directory."/{$file}"))
				{
					if ($file != "." & $file != "..") $dirs[] = $file;
				}
			}
		}
		closedir($handle);
		reset($dirs);
		sort($dirs);
		reset($dirs);
		return $dirs;
		$valore = '';
	}
	
	public static function fext($filename) {
		//Estensione di un file
		$path_info = pathinfo($filename);
		if (isset($path_info['extension']))
			return strtolower($path_info['extension']);
		else
			return '';
	}
}
?>