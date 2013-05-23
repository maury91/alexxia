/**
 *	User Registration (js) for ALExxia
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
var reg_step=0,lat_cost=0.01308996938995747182692768076366,reg_animation;
$(function() {
	//Initialize loading
	for (i=0;i<5;i++)
		$('.reg_loading').append($('<span></span>').html('&#9679;'));
	$('.reg_loading').hide();
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
	//Check the nick while the user is writing
	$('#nick').on('input',function() {
		if ($(this).val().length<4)
			$(this).addClass('error').removeClass('ok').next('.info').html(__nick_short);
		else if (!$(this).val().match(/^[a-z0-9]+$/i))
			$(this).addClass('error').removeClass('ok').next('.info').html(__nick_invalid);
		else
			$.ajax({
				url : 'zone_reg.html',
				data : {check : {nick : $(this).val()}},
				dataType : 'json',
				type : 'post',
				success : function (d) {
					if (d.r=='y')
						$('#nick').addClass('ok').removeClass('error').next('.info').html('');
					else {
						if (d.err==1)
							$('#nick').addClass('error').removeClass('ok').next('.info').html(__nick_used);
					}
				}
			
			});
	}).val('');
	//Check the password(s) while the user is writing
	$('#pass').bind('input',function() {
		if ($(this).val().length<6)
			$(this).removeClass('ok').addClass('error').next('.info').html(__pass_short);
		else
			$(this).removeClass('error').addClass('ok').next('.info').html('');
		if ($(this).val() != $('#pass2').val())
			$('#pass2').removeClass('ok').addClass('error').next('.info').html(__pass_equal);
		else
			$('#pass2').removeClass('error').addClass('ok').next('.info').html('');
	}).val('');
	$('#pass2').bind('input',function() {
		if ($('#pass').val() != $(this).val())
			$(this).removeClass('ok').addClass('error').next('.info').html(__pass_equal);
		else
			$(this).removeClass('error').addClass('ok').next('.info').html('');
	}).val('');
	//Check the email(s) while the user is writing
	$('#email').bind('input',function() {
		if(!$(this).val().match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i))
			$(this).removeClass('ok').addClass('error').next('.info').html(__email_invalid);
		else 
			$.ajax({
				url : 'zone_reg.html',
				data : {check : {email : $(this).val()}},
				dataType : 'json',
				type : 'post',
				success : function (d) {
					if (d.r=='y')
						$('#email').addClass('ok').removeClass('error').next('.info').html('');
					else {
						if (d.err==1)
							$('#email').addClass('error').removeClass('ok').next('.info').html(__email_used);
					}
				}
			});
		if ($(this).val() != $('#email2').val())
			$('#email2').removeClass('ok').addClass('error').next('.info').html(__email_equal);
		else
			$('#email2').removeClass('error').addClass('ok').next('.info').html('');
	}).val('');
	$('#email2').bind('input',function() {
		if ($('#email').val() != $(this).val())
			$(this).removeClass('ok').addClass('error').next('.info').html(__email_equal);
		else
			$(this).removeClass('error').addClass('ok').next('.info').html('');
	}).val('');
	//When the user click on the captcha
	captcha_click(function(captcha_data) {
		//Check the user have compiled the data correctly
		ok=true;
		$('.required').each(function() {
			if (!$(this).hasClass('ok'))
				ok=false;
		});
		if (ok) {
			//Show loading dialog
			reg_animation = setInterval(function() {
				reg_step=(reg_step+1)%480;
				rst=Math.cos(reg_step*Math.PI/240)*480;
				sst=40;
				//sst=((reg_step>240)?(480-reg_step)*40/240:reg_step*40/240);
				$('.reg_loading span').each(function(i,el) {
					$(this).animate({left : (50-Math.cos((i*25+rst)*lat_cost-1.57)*sst)+'%',top : (50-Math.sin((i*25+rst)*lat_cost-1.57)*sst)+'%'},12);
				})
			},12);
			$('.reg_loading').show();
			//Cript password(1)
			salt_a = bcrypt.gensalt(6);
			bcrypt.hashpw($('#pass').val(), salt_a, function(pass_s) {
				//Cript password(2)
				salt_b = bcrypt.gensalt(7);
				bcrypt.hashpw($('#pass').val(),salt_b,function(pass_r) {
					//Cript the password(3)
					var pass=CryptoJS.MD5(pass_s).toString()+':'+pass_s.substr(0,29)+'|'+pass_r;
					//Send the data via secure connection
					extra = $(".extra_data :input").serializeArray();
					extr_ = {};
					for (i in extra)
						extr_[extra[i].name] = extra[i].value;
					$().secure({host:'',act:'ajax_page',page:{zone:'reg'},params:{captcha : captcha_data,nick : $('#nick').val(),pass : pass,email : $('#email').val(),name : $('#name').val(),lname : $('#lname').val(),extra : extr_},
						user_func:function (data) {
							$('.reg_loading').hide();
							clearInterval(reg_animation);
							$('html, body').animate({scrollTop: $('.registration').offset().top}, 400);
							//Check if was a error
							console.log(data);
							if (data.ok) 
								$('.registration').html(data.html);
							else {
								switch(data.err) {
									case 'captcha' : 
										captcha_error(data.captcha);
										break;
									case 'nick' :
										$('#nick').trigger('input');
										break;
									case 'email' :
										$('#email').trigger('input');
										break;
								}
							}
						}
					},"ajax_call");				
				}, function() {});
			}, function() {});
		} else
			return false;
	});
})
