<?php
/**
 *	Media manager for ALExxia
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
if (GET::exists('langvars')) {
	header("Content-type: application/x-javascript");
	include(__base_path.'langs/'.LANG::short().'/media_man.php');
	echo 'var l__name = "'.$__name.' : ";
var l__del = "'.$__del_file.'";
var l__one_file = "'.$__one_file.'";
var l__no_file = "'.$__no_file.'";
var l__ok = \''.$__ok.'\';
var l__abort = \''.$__abort.'\';
';
	exit(0);
}
if (!GET::exists('act')) {
	echo '{"r" : "404"}';
	exit(0);
}
if (GET::val('act') == 'perms') {
	session_start();
	echo json_encode($_SESSION['media_man'][GET::val('uid')]);
	exit(0);
}
//Controllo permessi
if (!PERMISSION::has('mediamanager_navigate')) {
	//Controllo azione da intraprendere
	session_start();
	//Controllo esistenza chiave
	if (GET::exists('uid'))&&isset($_SESSION['media_man'][GET::val('uid')])) {
		$data = $_SESSION['media_man'][GET::val('uid')];
		switch (GET::val('act')) {
			case 'del' :
				//Controllo che abbia i permessi per eliminare
				if (!$data['del']) { echo '{"r" : "407"}'; exit(0);}
				break;
			case 'upl' :
				if (!$data['upload']) { echo '{"r" : "407"}'; exit(0);}
				break;
			case 'list' :
				//Controllo che la directory richiesta sia valida
				if (((!$data['navigable'])&&(GET::val('d')!=$data['dir']))||(((!(strpos(GET::val('d'),'..')===false))||(strpos('!'.GET::val('d'),$data['dir'])!=1)))) { echo json_encode(array('r'=>406,'data'=>$data)); exit(0);}
				break;			
		}		
	} else {
		//Autorizzazione non valida
		echo '{"r" : "405"}';
		exit(0);
	}
} else {
	if (GET::exists('uid')) {
		session_start();
		$data = $_SESSION['media_man'][GET::val('uid')];
	} else 
		$data = array('dir' => '', 'extensions' => 'all', 'multiple' => true,'navigable' => true);
	$data['upload'] =  PERMISSION::has('mediamanager_upload');
	$data['del'] =  PERMISSION::has('mediamanager_del');
}
switch (GET::val('act')) {
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
			$r = '';
			foreach ($dirs as $v)
				$r .= '{ "t" : "d", "n" : "'.$v.'", "p" : "'.get_perms("$dir/$v/").'" },';
			foreach ($files as $v)
				$r .= '{ "t" : "f", "n" : "'.$v.'", "p" : "'.get_perms("$dir/$v").'" , "s" : "'.size_m(filesize("$dir/$v")).'"},';
			$r .= '{ "t" : "s", "n" : "'.md5($r).'" }';
			echo '{ "data": [ '.$r.' ] } ';
			exit(0);		
		}
		show_dir(GET::val('d')); 	
		/* Do il contenuto della cartella */ 
	break;
	case 'del' : /* Elimino un file */
		function del_file($a,$b) {
			//Elimina un file o una directory
			if ($b=='d') del_dir("$a/"); else unlink($a);
		}
		for ($i=0;$i<count(GET::val('f'));$i++) {			
			$dir = dirname(GET::val('f',$i));	
			//ultimo carattere della stringa
			if (substr($data['dir'],-1)=='/')
				$dir .= '/';
			if (!(((!$data['navigable'])&&($dir!=$data['dir']))||(((!(strpos($dir,'..')===false))||(($data['dir']!='')&&(strpos('!'.$dir,$data['dir'])!=1))) ))) {
				if ($data['ondelete'] != '') {
					$fname = $_GET['f'][$i];
					$point = 'ondelete';
					include($data['ondelete']);
				}
				del_file(GET::val('f',$i),GET::val('d',$i));
			}
		}		
		exit(0);
	case 'newd' : /* Nuova cartella */if (mkdir(GET::val('f'))) echo ' { "s" : "y"} '; else echo ' { "s" : "n"} '; exit(0); break;
	case 'upl' :
		$dir = GET::val('d').'/';
		$sizeLimit = trim(ini_get('upload_max_filesize'));
		$last = strtolower($sizeLimit[strlen($sizeLimit)-1]);
		switch($last) {
			case 'g': $sizeLimit *= 1024;
			case 'm': $sizeLimit *= 1024;
			case 'k': $sizeLimit *= 1024;        
		}
		if (!is_writable($dir)) {
            echo '{ error : 1 }';
			exit(0);
		}
		if (GET::exists('myfile')) {
			if (isset($_SERVER["CONTENT_LENGTH"]))
				$filesize = (int)$_SERVER["CONTENT_LENGTH"];	
			function save_file($path) {    
				$input = fopen("php://input", "r");
				$temp = tmpfile();
				$realSize = stream_copy_to_stream($input, $temp);
				fclose($input);
				
				if ($realSize != $GLOBALS['filesize']){            
					return false;
				}
				
				$target = fopen($path, "w");        
				fseek($temp, 0, SEEK_SET);
				stream_copy_to_stream($temp, $target);
				fclose($target);
				
				return true;
			}
			$filename = GET::val('myfile');
				
		} elseif (isset($_FILES['myfile'])) {
			function save_file($path) {
				if(!move_uploaded_file($_FILES['myfile']['tmp_name'], $path)){
					return false;
				}
				return true;
			}
			$filename = $_FILES['myfile']['name'];
			$filesize = $_FILES['myfile']['size'];
		} else {
            echo '{ error : 2 }';
			exit(0);
		}
        if (isset($filesize)) {
			if ($filesize == 0) {
				echo '{ error : 3 }';
				exit(0);
			}
			if ($filesize > $sizeLimit) {
				echo '{ error : 4 }';
				exit(0);
			}
		}
		if ((!(strpos($filename,'/')===false))||(!(strpos($filename,'\\')===false))){
			echo '{ error : 5 }';
			exit(0);
		}
		$pathinfo = pathinfo($filename);
        $filename = $pathinfo['filename'];
        $ext = $pathinfo['extension'];		
        if(($data['extensions'] != 'all')&&!in_array(strtolower($ext), $data['extensions'])){
            echo '{ error : 6 }';
			exit(0);
        }
		$exnum = 1;
        $extra = '';
		while (file_exists($dir . $filename . $extra . '.' . $ext)) {
			$extra = '('.$exnum.')';
			$exnum++;
		}         
        if (save_file($dir . $filename . $extra . '.' . $ext)) {
			$fname = $filename. $extra.'.'.$ext;
			$changed = $extra!='';
			if ($data['onupload'] != '') {
				$point = 'onupload';
				include($data['onupload']);
			}
			echo htmlspecialchars(json_encode(array('success'=>true,'filename'=>$fname,'changed'=>$changed)), ENT_NOQUOTES);
	    } else 
            echo '{ error : 7 }';
		exit(0);
}
?>