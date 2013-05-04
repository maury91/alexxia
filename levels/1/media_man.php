<?php
class MEDIA_MAN {
	
	protected static $first=true;
	
	static public function make($dir = '.',$extensions = 'all', $multiple = false, $upload = true, $del = false,$navigable = true,$show_files=true,$show_folder=true,$onupload='',$ondelete='',$admin = false) {
		if (self::$first&&!$admin) {
			self::$first=false;
			echo '<script type="text/javascript" src="'.__http_host.__http_path.'js/media_man.js"></script><script type="text/javascript" src="'.__http_host.__http_path.'zone_media_man.html?langvars"></script><script type="text/javascript">__media_man_base_path = "'.__http_host.__http_path.'"</script>';
		}
		$media_id=RAND::word(15);
		if (gettype($extensions) == 'array')
			$extensions = array_map("strtolower", $extensions);
		@session_start();
		$_SESSION['media_man'][$media_id] = array('dir' => $dir, 'extensions' => $extensions, 'multiple' => $multiple, 'upload' => $upload,'del' => $del,'navigable' => $navigable,'onupload' => $onupload,'ondelete' => $ondelete,'show_files'=>$show_files,'show_folder'=>$show_folder);
		return $media_id;
	}
	
}
?>