<?php
/*
	TODO : completare META_TAGS
*/

class META_TAGS {
	static protected $tags=array();
	
	static protected $meta_tags = array('description' => array('name','Description'),'keywords' => array('name','Keywords'),'generator' => array('name','Generator'),'lang' => array('http-equiv','content-language'));
	
	static protected function default_tags() {
		if (empty(self::$tags)) {
			self::$tags = array(
				'title' => HTML::get_title(),
				'site_name' => GLOBALS::val('sitename'),
				'description' => GLOBALS::val('sitedesc'),
				'generator' => 'Alexxia V'.__ALE_version);
			if(isset($GLOBALS['__lang']))
				self::$tags['lang'] = $GLOBALS['__lang'];
		}
	}
	
	static public function add($tag,$val) {
		self::default_tags();
		self::$tags[$tag] = $val;
	}
	
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

class HTML {
	static protected $scripts=array(),$styles=array(),$title=null,$body_tag='',$body='',$logo=null;
	
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
		return "\t\t".'<script type="text/javascript" src="'.addslashes($v).'"></script>'."\n";
	}
	
	static public function style_form($v) {
		return "\t\t".'<link rel="stylesheet" type="text/css" href="'.addslashes($v).'" />'."\n";
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
	
	static public function set_body($val) {
		self::$body = $val;
	}
	
	static public function get_body() {
		return self::$body;
	}
	
	static public function set_logo($val) {
		self::$logo = $val;
	}
	
	static public function get_logo() {
		if (self::$logo==null)
		return (in_array(strtolower(FUNCTIONS::fext(self::$logo)),array('png','jpe','jpeg','jpg','gif','bmp','ico','tiff','tif','svg','svgz')))?'<img src="'.self::$logo.'">':self::$logo;
	}
	
	static public function set_body_tag($val) {
		self::$body_tag = $val;
	}
	
	static public function get_body_tag() {
		return self::$body_tag;
	}
	
	static public function get_head() {
		return
		'<title>'.(self::get_title()).'</title>'."\n"
		.(META_TAGS::get_html())
		.implode('',array_merge(array_map('HTML::style_form',array_unique(self::$styles)),array_map('HTML::script_form',array_unique(self::$scripts))));
	}
}
?>