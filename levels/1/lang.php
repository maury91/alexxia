<?php
/**
 *	Lang module for ALExxia
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

//This class manage the language
class LANG {
	//Language releved
	private static $lang=null;

	//Get data of a language
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

	//Return the list of avaibles languages
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
	
	//Return list of avaible language (external)
	public static function get_list() {
		if (file_exists(__base_path.'cache/langs.php'))
			include __base_path.'cache/langs.php';
		else
			return self::list_langs();
		return $__langs;
	}
	
	//Return list of avaible language (in short name form)
	private static function get_flist() {
		if (!file_exists(__base_path.'cache/langs.php'))
			self::list_langs();
		include __base_path.'cache/langs.php';
		return $__flangs;
	}
	
	//Find the language in use from the user
	private static function find_lang() {
		if (GET::exists('lang'))
			$__lang = GET::val('lang');			//Language set by GET value (1°)
		elseif (COOKIE::exists('ale_lang')) 
			$__lang = COOKIE::val('ale_lang');	//Language set by cookie (2°)
		elseif (USER::logged()&&(USER::data('lang')!=''))
			$__lang = USER::data('lang');		//Language set by database (3°)
		else {
			//Language set by the browser (4°)
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				//List all language suported by the browser
				$__flangs = self::get_flist();
				$langs = explode(",",strtr(strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']),';',','));
				foreach ($langs as $lang) {
					//Stop at the first avaible
					if (isset($__flangs[$lang])) {
						$__lang = $__flangs[$lang];
						break;
					}
				}
			}
			//If no language found
			if (empty($__lang))
				list($__lang,$v) = each($__langs);	//First language avaible
		}
		$__langs = self::get_list();
		//If the language don't exists
		if (!isset($__langs[$__lang]))
			list($__lang,$v) = each($__langs);	//First language avaible
		COOKIE::set('ale_lang',$__lang,7*24*60);
		self::$lang = $__langs[$__lang];
		$GLOBALS['__lang'] = $__lang;
	}
	
	//Get the name of the language in use
	public static function name() {
		if (self::$lang==null)
			self::find_lang();
		return self::$lang['n'];
	}
	
	//Get the short name of the language in use
	public static function short() {
		if (self::$lang==null)
			self::find_lang();
		return self::$lang['sn'];
	}
	
	//Get the path of a language file
	public static function path($f='') {
		if (self::$lang==null)
			self::find_lang();
		return __base_path.'langs/'.self::$lang['sn'].'/'.$f;
	}
	
}
?>