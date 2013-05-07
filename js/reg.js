$(function() {
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
	captcha_click(function(captcha_data) {
		if ($('#nick').hasClass('ok')&&$('#pass').hasClass('ok')&&$('#email').hasClass('ok')) {
			salt_a = bcrypt.gensalt(6);
			bcrypt.hashpw($('#pass').val(), salt_a, function(pass_s) {
				salt_b = bcrypt.gensalt(7);
				bcrypt.hashpw($('#pass').val(),salt_b,function(pass_r) {
					var pass=CryptoJS.MD5(pass_s).toString()+':'+pass_s.substr(0,29)+'|'+pass_r;
					$().secure({host:'',act:'ajax_page',page:{zone:'reg'},params:{captcha : captcha_data,nick : $('#nick').val(),pass : pass,email : $('#email').val(),name : $('#name').val(),lname : $('#lname').val(),extra : $(".extra_data :input").serializeArray()},
						user_func:function (data) {
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
