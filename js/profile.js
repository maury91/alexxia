$(function() {
	$('#change_user_info').button().click(function(){

	});
	$('#change_user_pass').button().click(function(){
		$().secure('force_login',{
			target : $('#passform')[0],
			page:{zone:'profile'},
			params:{changeP : true},
			nick:__nick,
			success: function(data) {
				$('#passform').slideDown();
				$('.data:first').slideUp();
				//Check the password(s) while the user is writing
				$('#pass').bind('input',function() {
					if ($(this).val().length<6)
						$(this).removeClass('ok').addClass('error').next('.infox').html(data.lang.__pass_short);
					else
						$(this).removeClass('error').addClass('ok').next('.infox').html('');
					if ($(this).val() != $('#pass2').val())
						$('#pass2').removeClass('ok').addClass('error').next('.infox').html(data.lang.__pass_equal);
					else
						$('#pass2').removeClass('error').addClass('ok').next('.infox').html('');
				}).val('');
				$('#pass2').bind('input',function() {
					if ($('#pass').val() != $(this).val())
						$(this).removeClass('ok').addClass('error').next('.infox').html(data.lang.__pass_equal);
					else
						$(this).removeClass('error').addClass('ok').next('.infox').html('');
				}).val('');
				$('#savepass').button().click(function(){
					//Send password
					if ($('#pass').hasClass('ok')&& $('#pass2').hasClass('ok')) {
						//Cript password(1)
						salt_a = bcrypt.gensalt(6);
						bcrypt.hashpw($('#pass').val(), salt_a, function(pass_s) {
							//Cript password(2)
							salt_b = bcrypt.gensalt(7);
							bcrypt.hashpw($('#pass').val(),salt_b,function(pass_r) {
								//Cript the password(3)
								var pass=CryptoJS.MD5(pass_s).toString()+':'+pass_s.substr(0,29)+'|'+pass_r;
								//Send the data via secure connection
								secure_ajax({
									page : {zone : 'profile'},
									params : {pass : pass},
									success : function(data) {
										console.log(data);
										$('#passform').slideUp().html('');
										$('.data:first').slideDown();
									}
								});
							}, function() {});
						}, function() {});
					}
					
				});
			}
		});
	});
	$('.data .photo').aleUpload({
		uid : __upload_id,
		multiple : false,
		success : function(img) {
			$('.data .photo').css('background-image','url(\''+__http_base+img.path+'/'+img.filename+'\')');
		},
		error : function(a) {
			console.log(a);
		},
		button : $('.data .photo .edit')
	});
	$('.data:first').after(
		$('<div></div>').addClass('data').attr('id','passform').hide()
	);
	$().secure_init(__http_base,
		function(stat) {
			$(".secure_status .points").html("");
			for (i=0;i<4;i++) 
				$(".secure_status .points").append($("<span></span>").text(".").css({"margin-left":Math.cos((stat+i)*0.17-1.57)*30,"margin-top":Math.sin((stat+i)*0.17-1.57)*30}));
		},
		function() {
			$(".secure_status .points").html("");
			$(".secure_status .img.unsecure").removeClass("unsecure").addClass("secure");
			$('#change_user_pass').removeClass('inactive');
		}
	);
})