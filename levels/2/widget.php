<?php
class WIDGET {
	static public function load($widget) {
		include(__base_path.'widget/'.$widget.'/widget.php');
		$arg = array_slice(func_get_args(),1);
		return call_user_func_array('widget_'.$widget,$arg);
	}
}
?>