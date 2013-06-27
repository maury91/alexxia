<?php
class UPLOAD {
	//First use
	protected static $first=true;
	
	//Make a madia manager
	static public function make($dir = '.',$extensions = 'all', $multiple = false,$onupload='') {
		//Check if use the secured media manager for admins
		if (self::$first) {
			self::$first=false;
			//Add the scripts of the media manager
			HTML::add_script('js/upload.js');
		}
		//Make a random id for this media manager
		$media_id=RAND::word(15);
		//Add avaibles extensions
		if (gettype($extensions) == 'array')
			$extensions = array_map("strtolower", $extensions);
		if ($extensions=='images')
			$extensions = array('png','jpg','jpeg','bmp','gif');
		@session_start();
		//Create the session
		$_SESSION['media_man'][$media_id] = array('dir' => $dir, 'extensions' => $extensions, 'multiple' => $multiple, 'upload' => true,'del' => false,'navigable' => false,'onupload' => $onupload,'ondelete' => '','show_files'=>false,'show_folder'=>false);
		//Return the id of the media manager
		return $media_id;
	}
}
?>