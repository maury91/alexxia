function sec_page_load(ret) {
	$('.main').html(ret.content);
	$('.main .secure_link').each(function(i,el) {
		$(el).data('page',$(el).attr('href')).removeAttr('href').removeClass('secure_link').addClass('internal_link').click(function() {
			$().secure({act:'load_page',page:$(this).data('page'),params:[]});
		});
	});
}
function supports_history_api() {
	return !!(window.history && history.pushState);
}
$(function() {
	if (!supports_history_api()) { return; }
	just_started=true;
	window.setTimeout(function() { just_started=false;},600);
	window.addEventListener("popstate", function(e) {
		//Controllo pagina
		if (!just_started) {
			pp = location.pathname.split("/").pop();
			if (pp=='') {
				//Ritorno stato iniziale
				
			} else {
				//Caricamento pp
				$().secure(e.state,"history");
			}
		}
	}, false);
});
(function($) {
	var do_nothing=function() {};
	var code,sess,cr_type,bcrypt=null,bc_controller,login_success=false,bc_stat=0,load_animation=load_complete=do_nothing,log_err_anim=0,host;
	var bc_enable = function() {
		bc_stat++;
		load_animation(bc_stat);
		if(bcrypt.ready()&&login_success){
			load_complete();
			clearInterval(bc_controller);
		}
	};
	var login_err = function() {
		$('.login').animate({'left':'49%'},150).animate({'left':'51%'},300).animate({'left':'49%'},300).animate({'left':'51%'},300).animate({'left':'49%'},300).animate({'left':'50%'},150);
	}
	var decrypt_aes = function(data) {
		try {
			key = CryptoJS.enc.Utf8.parse(code);
			iv  = CryptoJS.enc.Base64.parse(data.substr(0,44));
			cr = CryptoJS.AES.decrypt(data.substr(44),key,{padding: CryptoJS.pad.ZeroPadding,iv : iv} );
			data = JSON.parse(cr.toString(CryptoJS.enc.Utf8));
		} catch (e) {
			data=false;
			console.log(e);
		} finally {
			return data;
		}
	}
	var encrypt_aes = function(data) {
		key = CryptoJS.enc.Utf8.parse(code);
		iv  = CryptoJS.lib.WordArray.random(256/8);
		crypted = CryptoJS.AES.encrypt(data, key, {padding: CryptoJS.pad.ZeroPadding,iv : iv});
		return  iv.toString(CryptoJS.enc.Base64)+crypted.ciphertext.toString(CryptoJS.enc.Base64);
	}
	var encrypt_rsa = function(data) {
		var rsa = new RSAKey();
		rsa.setPublic(code.n, code.e);
		return rsa.encrypt(data);
	}
	var encrypt = function(data) {
		if (cr_type=='rsa')
			return encrypt_rsa(data);
		else
			return encrypt_aes(data);
	}
	var decrypt = function(data) {
		if (cr_type=='aes')
			return decrypt_aes(data);
		else
			return decrypt_rsa(data);
	}
	var crypto_send = function(data) {
		res = encrypt(JSON.stringify(data.data, null, 2));
		options = {
			url : host+'index.php',
			data : {cr:sess,data:res},
			type : 'post',
			dataType : 'json',
			success : data.success,
			error : data.error
		};
		if (data.progress)
			options['xhr'] = function() {
				var xhr = new window.XMLHttpRequest();
				//Upload progress
				xhr.upload.addEventListener("progress", data.progress, false);
				return xhr;
			}
		if (res) {
			$.ajax(options);
		} else 
			data.error();
	}
	
	var secure_js = function() {
		return {
			call : function (opt,opt2) {
				if (typeof opt=="string") {
					switch (opt) {
						case 'session' :
							return sess;
						break;
						case 'loaded' :
							return bcrypt.ready()&&login_success;
						break;
						case 'decrypt' :
							return decrypt(opt2);
						break;
						case 'encrypt' :
							return encrypt(opt2);						
						break;
						case 'send' :
						case 'do_login' :
							//Richiesta token
							crypto_send({
								data : {action : "salt_pass",params : {nick : $('#nick').val()}},
								success : function (e) {
									data = decrypt(e.cr);
									if (data) {
										if (data.salt_a) {
											bcrypt.hashpw($('#pass').val(), data.salt_a, function(pass_s) {
												bcrypt.hashpw($('#pass').val(),data.salt_b,function(pass_r) {
													crypto_send({
														data : {action : "login",params : {nick : $('#nick').val(),pass : pass_s,pass2 : CryptoJS.MD5(pass_r+data.token).toString(),id : data.id}},
														success : function (r) {
															//Controllo se il login è andato a buon fine
															data = decrypt(r.cr);
															if (data.login=='ok') {
																sess=data.sess;
																bcrypt.hashpw(pass_r, data.tk, function(to_aes) {
																	code=code.substr(0,16)+CryptoJS.MD5(to_aes).toString().substr(0,16);
																	$('.main').removeClass('load').html('').show();
																	$('.login,.logo').slideUp(600);
																	admin_home();
																}, function() {});
															} else {
																$('#pass').val('');
																log_err_anim=0;
																login_err();
															}
														},
														error : function() {
															//Nulla...
														}
													})
												}, function() {});
											}, function() {});
										} else
											login_err();
									} else {
										//Ritenta login
										$().secure('do_login');
									}
								},
								error : function() {
									//Nulla...
								}
							});
						break;
					}
				} else {
					switch (opt.act) {					
						case 'login' :
							login_success=true;
							code=opt.aes;
							sess=opt.sess;
							cr_type = 'aes';
						break;
						case 'load_page' :
							opt.user_func = "sec_page_load";
						case 'ajax_page' :
							pr_func= (opt.progress)?opt.progress:function(){};
							crypto_send({
								data : {action : "area",params : {page : opt.page,params : opt.params}},
								progress : pr_func,
								success : function (e) {
									var data = decrypt(e.cr);
									var page = opt.page;
									if (typeof opt.params.config == 'string')
										switch (opt.page) {
											case 'components' :
												page = 'com_'+opt.params.config.replace('/config/','/').replace('.php','');
										}
									if (!opt2)
										history.pushState(opt, null, admin_host_path+page+'.html');
									//Caricamento js e css
									if (typeof (data.content) == "object") {
										if (typeof data.content.css == "object") {
											for (i in data.content.css)
												$('head').append($('<link rel="stylesheet" type="text/css" />').attr('href',data.content.css[i]));
										}
										if (typeof data.content.js == "object")
											for (i in data.content.js)
												$.getScript(data.content.js[i]);
									}
									if (opt2=='ajax_call')
										opt.user_func(data);
									else
										window[opt.user_func](data);
								},
								error : function() {
									alert('303');
									console.log('unable to complete operation');
								}
							});
						break;
					}
				}
			},
			init : function (ahost,anim_load,compl_load) {
				host=ahost;
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
						code = data.RSA;
						sess = data.id;
						cr_type = 'rsa';
						var rsa = new RSAKey();
						rsa.generate(512,"10001");
						crypto_send({
							data : {action : "new_aes",params : {rsa_key : {e : rsa.e.toString(16), n : rsa.n.toString(16)}}},
							success : function (e) {
								if (e.key!='') {
									decript = rsa.decrypt(e.key);
									if (decript==null)
										$().secure_init(ahost,anim_load,compl_load);
									else
										$().secure({act:'login',aes:decript,sess:e.sess})
								} else
									$().secure_init(ahost,anim_load,compl_load);
							},
							error : function() {
								$().secure_init(ahost,anim_load,compl_load);
							}
						});
					},
					error : function() {
						$().secure_init(ahost,anim_load,compl_load);
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