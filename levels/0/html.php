<?php
class HTML {
	static protected $scripts=array(),$styles=array();
	
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
	
	static public function get_head() {
		return implode("\n",array_merge(array_map('HTML::style_form',array_unique(self::$styles)),array_map('HTML::script_form',array_unique(self::$scripts))));
	}
}