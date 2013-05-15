<?php
/**
 *	Captcha show for ALExxia
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
//Define constants
define('__base_path',dirname(__FILE__).'/');
//Include class auto-loader
require(__base_path.'levels/3/loader.php');
//Include server info
include(__base_path.'config/infoserver.php');
if (GET::exists('id')) {
	//Show the captcha image
	$captcha = new CAPTCHA(GET::val('id'));
	$captcha->show();
} elseif (GET::exists('new')) {
	//Provide a new captcha (the last was wrong)
	$captcha = new CAPTCHA(GET::val('new'));
	echo json_encode(array('txt'=>$captcha->text(true)));
}
?>