<?php
/*
	TODO : completare META_TAGS
*/

class META_TAGS {
	static protected $tags=array();
	
	static protected function default_tags() {
		if (empty(self::$tags)) {
			self::$tags = array(
				'title' => HTML::get_title(),
				'site_name' => GLOBALS::val('sitename'),
				'description' => GLOBALS::val('sitedesc'));
		}
	}
	
	static public function add($tag,$val) {
		self::default_tags();
		self::$tags[$tag] = $val;
	}
	
	static public function get_html() {
		return '';
	}

}

class HTML {
	static protected $scripts=array(),$styles=array(),$title=null;
	
	static public function add_script() {
		$arr = func_get_args();
		foreach ($arr as $v)
			self::$scripts[] = $v;
	}
	
	static public function add_style() {
		$arr = func_get_args();
		foreach ($arr as $v)
			self::$styles[] = $v;
	}
	
	static public function script_form($v) {
		return "\t\t".'<script type="text/javascript" src="'.addslashes($v).'"></script>';
	}
	
	static public function style_form($v) {
		return "\t\t".'<link rel="stylesheet" type="text/css" href="'.addslashes($v).'" />';
	}
	
	static public function get_title() {
		if (self::$title==null)
			self::$title=GLOBALS::val('sitename');
		return self::$title;
	}
	
	static public function set_title($val) {
		self::$title=$val;
	}
	
	static public function append_title($val) {
		self::$title=(self::get_title()).$val;
	}
	
	static public function get_head() {
		return
		'<title>'.(self::get_title()).'</title>'
		.(META_TAGS::get_html())
		.implode("\n",array_merge(array_map('HTML::style_form',array_unique(self::$styles)),array_map('HTML::script_form',array_unique(self::$scripts))));
	}
}
?>