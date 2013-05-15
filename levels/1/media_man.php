<?php
/**
 *	Madia manager module for ALExxia
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

class MEDIA_MAN {
	//First use
	protected static $first=true;
	
	//Make a madia manager
	static public function make($dir = '.',$extensions = 'all', $multiple = false, $upload = true, $del = false,$navigable = true,$show_files=true,$show_folder=true,$onupload='',$ondelete='',$admin = false) {
		//Check if use the secured media manager for admins
		if (self::$first&&!$admin) {
			self::$first=false;
			//Add the scripts of the media manager
			HTML::add_script('js/media_man.js','zone_media_man.html?langvars"')
			//Print the script
			echo '<script type="text/javascript">__media_man_base_path = "'.__http.'"</script>';
		}
		//Make a random id for this media manager
		$media_id=RAND::word(15);
		//Add avaibles extensions
		if (gettype($extensions) == 'array')
			$extensions = array_map("strtolower", $extensions);
		@session_start();
		//Create the session
		$_SESSION['media_man'][$media_id] = array('dir' => $dir, 'extensions' => $extensions, 'multiple' => $multiple, 'upload' => $upload,'del' => $del,'navigable' => $navigable,'onupload' => $onupload,'ondelete' => $ondelete,'show_files'=>$show_files,'show_folder'=>$show_folder);
		//Return the id of the media manager
		return $media_id;
	}
	
}
?>