/**
 *	Captcha module (js) for ALExxia
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

//This class assoc the the captcha image a function, the function passed by parameter is called with the information of the captcha as parameter
function captcha_click(recall) {
	$('.ale_captcha').click(function(e) {
		var c_data = {x : e.offsetX,y: e.offsetY,id :$(this).attr('id')};
		recall(c_data);
	});
}

//This class provide a new captcha
function captcha_error(c_id) {
	$.ajax({
		url : 'captcha.php',
		data : {'new' : c_id},
		dataType : 'json',
		success : function(d) {
			$('#t'+c_id).html(d.txt);
			rnd = new Date().getTime();
			$('#'+c_id).css('background-image',$('#'+c_id).css('background-image'));
		}
	})
}
