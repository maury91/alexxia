(function($) {
	var do_nothing=function() {};
	var aes_code,aes_sess,bcrypt=null,bc_controller,login_success=false,bc_stat=0,load_animation=load_complete=do_nothing;
	var bc_enable = function() {
		bc_stat++;
		load_animation(bc_stat);
		if(bcrypt.ready()&&login_success){
			load_complete();
			clearInterval(bc_controller);
		}
	};
	var secure_js = function() {
		return {
			call : function (opt,opt2) {
				if (typeof opt=="string") {
					switch (opt) {
						case 'session' :
							return aes_sess;
						break;
						case 'loaded' :
							return bcrypt.ready()&&login_success;
						break;
						case 'decrypt' :
							try {
								data=JSON.parse(Aes.Ctr.decrypt(opt2, aes_code, 256));
							} catch (e) {
								data=false;
								console.log(e);
							} finally {
								return data;
							}
						break;
						case 'encrypt' :
							return Aes.Ctr.encrypt(opt2, aes_code, 256);						
						break;
						case 'do_login' :
							//Richiesta token
							$.ajax({
								url : 'index.php',
								data : {new_token : aes_sess},
								type : 'post',
								dataType : 'json',
								success : function (e) {
									data = $().secure('decrypt',e.cr);
									if (data) {
										pass_salt = bcrypt.gensalt(7);
										bcrypt.hashpw($('#pass').val(), pass_salt, function(pass_s) {
											to_send = JSON.stringify({nick : $('#nick').val(),pass : CryptoJS.MD5(pass_s+data.token)+':'+pass_salt,id : data.id}, null, 2);
											console.log(data);
											console.log(pass_s);
											console.log(to_send);
											$.ajax({
												url : 'index.php',
												data : {login : aes_sess,data : $().secure('encrypt',to_send)},
												type : 'post'
											});
										}, function() {});
									} else {
										//Ritenta login
										$().secure('do_login');
									}
								}
							});
						break;
					}
				} else {
					switch (opt.act) {					
						case 'login' :
							login_success=true;
							aes_code=opt.aes;
							aes_sess=opt.sess;
						break;
					}
				}
			},
			init : function (anim_load,compl_load) {
				if(bcrypt==null) {
					load_animation=anim_load;
					load_complete=compl_load;
					bcrypt = new bCrypt();
					bc_controller = setInterval(bc_enable,250);
				}
				$.ajax({
					url : 'index.php',
					data : {init : ''},
					type : 'post',
					dataType : 'json',
					success : function(data) {
						//Dati RSA						
						rsa_ext_public=data.RSA;
						id=data.id;
						var rsa2 = new RSAKey();
						rsa2.generate(512,"10001");
						var rsa = new RSAKey();
						rsa.setPublic(rsa_ext_public.n, rsa_ext_public.e);
						to_c = JSON.stringify({e : rsa2.e.toString(16), n : rsa2.n.toString(16)}, null, 2);
						var res = rsa.encrypt(to_c);
						if(res) {
							$.ajax({
								url : 'index.php',
								data : {new_aes : id,cripted : res},
								type : 'post',
								dataType : 'json',
								success : function (e) {
									if (e.key!='') {
										decript = rsa2.decrypt(e.key);
										if (decript==null)
											$().secure_init(anim_load,compl_load);
										else
											$().secure({act:'login',aes:decript,sess:e.sess})
									} else
										$().secure_init(anim_load,compl_load);
								},
								error : function() {
									$().secure_init(anim_load,compl_load);
								}
							})
						}
					},
					error : function() {
						$().secure_init(anim_load,compl_load);
					}
				});
			}
		}
	}();
	$.fn.extend({
		secure : secure_js.call,
		secure_init : secure_js.init
	});
})(jQuery);