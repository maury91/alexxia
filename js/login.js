/**
 *	Login (js) for ALExxia
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
function login_err() {
	//Vibra e messaggio
	var log_error = $('<div></div>').addClass('info').html(__login_error).appendTo('.login').hide().fadeIn(400).fadeOut(3000,function() {
		$(this).remove();
	});
}
function login_success() {
	//Show a message
	$('.login').html(__login_success);
	//and redirect to home
	setTimeout(function() { location.href = __http_base},2000);
}
$(function() {
	//Initialize the secure connection
	$().secure_init('',
		function(stat) {
			$(".secure_status .points").html("");
			for (i=0;i<4;i++) 
				$(".secure_status .points").append($("<span></span>").text(".").css({"margin-left":Math.cos((stat+i)*0.17-1.57)*30,"margin-top":Math.sin((stat+i)*0.17-1.57)*30}));
		},
		function() {
			$(".secure_status .points").html("");
			$(".secure_status .img.unsecure").removeClass("unsecure").addClass("secure");
		}
	);
	$('#dologin').bind('submit',function() {
		//Request the salt
		$().secure({host:'',act:'ajax_page',page:{zone:'login'},params:{act : 'salt_pass',nick : $('#nick').val()},
			user_func:function (data) {
				//Hash password
				if (data.salt_a)
					bcrypt.hashpw($('#pass').val(), data.salt_a, function(pass_s) {
						bcrypt.hashpw($('#pass').val(),data.salt_b,function(pass_r) {
							//Send password hashed and do login
							$().secure({host:'',act:'ajax_page',page:{zone:'login'},params:{act : 'login',nick : $('#nick').val(),pass : pass_s,pass2 : CryptoJS.MD5(pass_r+data.token).toString(),id : data.id},
								user_func:function (dat) {
									if (dat.login=='ok') {
										//Save key
										sessionStorage.ale_sess = dat.sess;
										bcrypt.hashpw(pass_r, dat.tk, function(to_aes) {
											//Is insecure (possible script injection during the normal navigation)
											var key = CryptoJS.MD5(to_aes).toString();
											sessionStorage.ale_key = CryptoJS.MD5(to_aes).toString();
											$().secure('set_sess',{sess : dat.sess,code : key});
											login_success();
										}, function() {});
									} else
										login_err();
									console.log(dat);
								}
							},"ajax_call");
						}, function() {});
					}, function() {});
			 	else
					login_err();
			}
		},"ajax_call");
		return false;
	})
});