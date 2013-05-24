<?php
/**
 *	Includer for ALExxia
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
class SCRIPT {
	protected static $bpath='',$rpath='';
	private static $scripts=array();

	public static function base_path($path) {
		self::$bpath=$path;
	}
	
	public static function rel_path($path) {
		self::$rpath=$path;
	}
	
	public static function add($script,$rpath=null,$bpath=null) {
		self::$scripts[] = (($bpath!=null)?$bpath:self::$bpath).(($rpath!=null)?$rpath:self::$rpath).$script;
	}
	
	public static function get() {
		return self::$scripts;
	}
}
class STYLE {
	protected static $bpath='',$rpath='';
	private static $styles=array();

	public static function base_path($path) {
		self::$bpath=$path;
	}
	
	public static function rel_path($path) {
		self::$rpath=$path;
	}
	
	public static function add($style,$rpath=null,$bpath=null) {
		self::$styles[] = (($bpath!=null)?$bpath:self::$bpath).(($rpath!=null)?$rpath:self::$rpath).$style;
	}
	
	public static function get() {
		return self::$styles;
	}
}

?>