<?php
/**
 *	Secure Media Manager for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
//Controllo permessi
if ($external['act'] == 'perms') 
	$content = $_SESSION['media_man'][$external['uid']];
elseif (PERMISSION::has('mediamanager_navigate')) {
	if (isset($external['uid'])) 
		$data = $_SESSION['media_man'][$external['uid']];
	else 
		$data = array('dir' => '', 'extensions' => 'all', 'multiple' => true,'navigable' => true);
	$data['upload'] =  PERMISSION::has('mediamanager_upload');
	$data['del'] =  PERMISSION::has('mediamanager_del');
	switch ($external['act']) {
		case 'list' : 
			function get_perms($filename)
			{ // Questa funzione è stata presa da : http://it2.php.net/manual/it/function.fileperms.php
				//Restituisce i permessi in forma legibile (drwx-rw-r--)
				$perms = fileperms($filename);
				if (($perms & 0xC000) == 0xC000) 
					$info = 's';
				else if (($perms & 0xA000) == 0xA000)
					$info = 'l';
				else if (($perms & 0x8000) == 0x8000) 
					$info = '-';
				else if (($perms & 0x6000) == 0x6000) 
					$info = 'b';
				else if (($perms & 0x4000) == 0x4000) 
					$info = 'd';
				else if (($perms & 0x2000) == 0x2000) 
					$info = 'c';
				else if (($perms & 0x1000) == 0x1000) 
					$info = 'p';
				else 
					$info = 'u';

				$info .= (($perms & 0x0100) ? 'r' : '-');
				$info .= (($perms & 0x0080) ? 'w' : '-');
				$info .= (($perms & 0x0040) ?
						(($perms & 0x0800) ? 's' : 'x' ) :
						(($perms & 0x0800) ? 'S' : '-'));

				$info .= (($perms & 0x0020) ? 'r' : '-');
				$info .= (($perms & 0x0010) ? 'w' : '-');
				$info .= (($perms & 0x0008) ?
						(($perms & 0x0400) ? 's' : 'x' ) :
						(($perms & 0x0400) ? 'S' : '-'));

				$info .= (($perms & 0x0004) ? 'r' : '-');
				$info .= (($perms & 0x0002) ? 'w' : '-');
				$info .= (($perms & 0x0001) ?
						(($perms & 0x0200) ? 't' : 'x' ) :
						(($perms & 0x0200) ? 'T' : '-'));

				return $info;
			}
			function size_m($a) {
				//Trasforma una dimensione da numero a byte
				$s = array('B','KB','MB','GB','TB','PB','EB','ZB','YB'); $x = 0;
				while ($a > 1024) {$x++; $a=($a/1024);}
				$a = ceil($a*100)/100;
				return $a.$s[$x];
			}
			function show_dir($dir) {
				//Restituisce come json il contenuto di una cartella
				$dirs= array();
				$files= array();
				if ($handle = opendir($dir."/"))
				{
					while ($file = readdir($handle))
					{
						if (is_dir($dir."/{$file}"))
						{
							if ($file != "." & $file != "..") $dirs[] = $file;
						} else $files[] = $file;					
					}
				}
				closedir($handle);
				reset($dirs);
				sort($dirs);
				reset($dirs);
				reset($files);
				sort($files);
				reset($files);
				$r = array();
				foreach ($dirs as $v)
					$r[] = array('t' => 'd','n' => $v,'p' => get_perms("$dir/$v/"));
				foreach ($files as $v)
					$r[] = array('t' => 'f','n' => $v,'p' => get_perms("$dir/$v"),'s' => size_m(filesize("$dir/$v")));
				$r[] = array('t' => 's');
				return array('data' => $r);
			}
			$content = show_dir(__base_path.$external['d']); 	
			//		Do il contenuto della cartella 
		break;
		case 'del' : // Elimino un file
			function del_file($a,$b) {
				//Elimina un file o una directory
				if ($b=='d') del_dir(__base_path.$a.'/'); else @unlink(__base_path.$a);
			}
			$content = $external;
			foreach ($external['sel'] as $v) {
				$dir = dirname($v['n']);	
				//ultimo carattere della stringa
				if (substr($data['dir'],-1)=='/')
					$dir .= '/';
				if (!(((!$data['navigable'])&&($dir!=$data['dir']))||(((!(strpos($dir,'..')===false))||(($data['dir']!='')&&(strpos('!'.$dir,$data['dir'])!=1))) ))) {
					if ($data['ondelete'] != '') {
						$fname = $v['n'];
						$point = 'ondelete';
						include($data['ondelete']);
					}
					del_file($v['n'],$v['t']);
				}
			}
		break;
		case 'newd' : /* Nuova cartella */
			$content = (mkdir(__base_path.$external['f']))?array('s' => 'y'):array('s' => 'n');
		break;
		case 'upl' :
			$content=$external;
			$dir = $external['d'].'/';
			$sizeLimit = trim(ini_get('post_max_size'));
			$last = strtolower($sizeLimit[strlen($sizeLimit)-1]);
			switch($last) {
				case 'g': $sizeLimit *= 1024;
				case 'm': $sizeLimit *= 1024;
				case 'k': $sizeLimit *= 1024;        
			}
			if (!is_writable($dir))
				$content = array('error' => 1);
			$filename = $external['filename'];
			if ((!(strpos($filename,'/')===false))||(!(strpos($filename,'\\')===false)))
				$content = array('error' => 5);
			$pathinfo = pathinfo($filename);
			$filename = $pathinfo['filename'];
			$ext = $pathinfo['extension'];		
			if(($data['extensions'] != 'all')&&!in_array(strtolower($ext), $data['extensions']))
				$content = array('error' => 6);
			$exnum = 1;
			$extra = '';
			while (file_exists(__base_path.$dir.$filename.$extra.'.'.$ext)) {
				$extra = '('.$exnum.')';
				$exnum++;
			}         
			if (file_put_contents(__base_path.$dir.$filename.$extra.'.'.$ext,base64_decode($external['filecontent']))) {
				$fname = $filename. $extra.'.'.$ext;
				$changed = $extra!='';
				if ($data['onupload'] != '') {
					$point = 'onupload';
					include($data['onupload']);
				}
				$content = array('success'=>true,'filename'=>$fname,'changed'=>$changed);
			} else
				$content = array('error' => 7);
	}
}
?>