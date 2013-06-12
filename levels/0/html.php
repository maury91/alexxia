<?php
/*
	TODO : completare META_TAGS
*/
/**
 *	HTML info for ALExxia
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

//This class permit to manage the meta tags of the page
class META_TAGS {
	//Tags used
	static protected $tags=array();
	
	//All avaible meta tags
	static protected $meta_tags = array('description' => array('name','Description'),'keywords' => array('name','Keywords'),'generator' => array('name','Generator'),'lang' => array('http-equiv','content-language'), 'favicon' => array('itemprop','image'));
	
	//Default values for the meta tags
	static protected function default_tags() {
		if (empty(self::$tags)) {
			self::$tags = array(
				'title' => HTML::get_title(),
				'site_name' => GLOBALS::val('sitename'),
				'description' => GLOBALS::val('sitedesc'),
				'favicon' => __http.GLOBALS::val('favicon'),
				'generator' => 'Alexxia V'.__ALE_version);
			if(isset($GLOBALS['__lang']))
				self::$tags['lang'] = $GLOBALS['__lang'];
		}
	}
	
	//Add a value to a meta tags
	static public function add($tag,$val) {
		self::default_tags();
		self::$tags[$tag] = $val;
	}
	
	//Return the meta tags in form of html
	static public function get_html() {
		self::default_tags();
		$meta='';
		foreach (self::$tags as $k => $v) {
			if (isset(self::$meta_tags[$k]))
				$meta.="\t\t".'<meta '.self::$meta_tags[$k][0].'="'.self::$meta_tags[$k][1].'" content="'.addcslashes($v,'"').'"/>'."\n";
		}
		return $meta;
	}

}

//This class permit to manage the HTML of the page
class HTML {
	//Content of the page
	static protected $scripts=array(),$styles=array(),$title=null,$body_tag='',$body='',$logo=null;
	
	//Add a new script to the page
	static public function add_script() {
		$arr = func_get_args();
		foreach ($arr as $v)
			self::$scripts[] = $v;
	}
	
	//Add a new style to the page
	static public function add_style() {
		$arr = func_get_args();
		foreach ($arr as $v)
			self::$styles[] = $v;
	}
	
	//Return the script in html form
	static public function script_form($v) {
		return "\t\t".'<script type="text/javascript" src="'.__http.addslashes($v).'"></script>'."\n";
	}
	
	//Return the style in html form
	static public function style_form($v) {
		return "\t\t".'<link rel="stylesheet" type="text/css" href="'.__http.addslashes($v).'" />'."\n";
	}
	
	//Return the current title of the page
	static public function get_title() {
		if (self::$title==null)
			self::$title=GLOBALS::val('sitename');
		return self::$title;
	}
	
	//Modify the title of the page
	static public function set_title($val) {
		self::$title=$val;
	}
	
	//Add a string in the end of the title of the page
	static public function append_title($val) {
		self::$title=(self::get_title()).$val;
	}
	
	//Modify the content of the body
	static public function set_body($val) {
		self::$body = $val;
	}
	
	//Return the content of the body
	static public function get_body() {
		return self::$body;
	}
	
	//Set the value of the logo
	static public function set_logo($val) {
		self::$logo = $val;
	}
	
	//Return the value of the logo (is returned a <img/> if is the path of a image)
	static public function get_logo() {
		if (self::$logo==null)
		return (in_array(strtolower(FUNCTIONS::fext(self::$logo)),array('png','jpe','jpeg','jpg','gif','bmp','ico','tiff','tif','svg','svgz')))?'<img src="'.self::$logo.'">':self::$logo;
	}
	
	//Modify the tag of the body (<body %value%>)
	static public function set_body_tag($val) {
		self::$body_tag = $val;
	}
	
	//Get the content of the tag body
	static public function get_body_tag() {
		return self::$body_tag;
	}
	
	//Return the head
	static public function get_head() {
		return
		'<title>'.(self::get_title()).'</title>'."\n"
		.(META_TAGS::get_html())
		."\t\t".'<link rel="shortcut icon" type="image/x-icon" href="'.__http.GLOBALS::val('favicon').'" />'."\n"
		.'<script type="text/javascript">__http_base = "'.__http.'"</script>'
		.implode('',array_merge(array_map('HTML::style_form',array_unique(self::$styles)),array_map('HTML::script_form',array_unique(self::$scripts))));
	}
}
?>