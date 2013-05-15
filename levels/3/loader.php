<?php
/**
 *	Class auto-loader for ALExxia
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

function level_3_autoload($class) {
	switch($class) {
		case 'TEMPLATE' : 
			include __base_path.'levels/3/template.php'; break;
		case 'MAIL' :
			include __base_path.'levels/3/mail.php'; break;
		case 'SECURE' : 
			include __base_path.'admin/levels/0/secure_lib.php'; break;
		default :
			include_once(__base_path.'levels/2/loader.php');
			level_2_autoload($class);
	}
}
spl_autoload_register('level_3_autoload');
?>