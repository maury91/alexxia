/**
 *	Secure connection (js) for ALExxia
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

function sec_page_load(ret) {
	$('.main').html(ret.content);
	$('.main .secure_link').each(function(i,el) {
		$(el).data('page',$(el).attr('href')).removeAttr('href').removeClass('secure_link').addClass('internal_link').click(function() {
			$().secure({act:'load_page',page:$(this).data('page'),params:[]});
		});
	});
}
function load_home_sec(ret) {
	$('.main').html(ret.content.html);
	$('.main .secure_link').each(function(i,el) {
		$(el).data('page',$(el).attr('href')).removeAttr('href').removeClass('secure_link').addClass('internal_link').click(function() {
			$().secure({act:'load_page',page:$(this).data('page'),params:[]});
		});
	});
}
function supports_history_api() {
	return !!(window.history && history.pushState);
}
function secure_ajax(opt) {
	if ($().secure('loaded'))
		$().secure({host:__http_base,act:'ajax_page',page:opt.page,params : opt.params, user_func : opt.success},"ajax_call");
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
var bcrypt=null;
(function($) {
	var do_nothing=function() {};
	var code,sess,cr_type,bc_controller,login_success=false,bc_stat=0,load_animation=load_complete=do_nothing,log_err_anim=0,host;
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
						case 'restore' : {
							if ($().secure('loaded')) 
								if ((sessionStorage.ale_sess != undefined)&&(sessionStorage.ale_key != undefined)) {
									sess = sessionStorage.ale_sess;
									code = sessionStorage.ale_key;
									//Test is valid
									crypto_send({
										data : {action : "test",params : []},
										success : function (e) {
											secure_ajax({
												page : opt2.page,
												params : opt2.params,
												success : function (data) {
													$(opt2.target).html(data.content.html);
												}
											});
										},
										error : function() {
											sessionStorage.removeItem('ale_sess');
											sessionStorage.removeItem('ale_key');
											//Reinit
											$().secure_init(__http_base,
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
										}
									});
								} else {
									//Show login dialog
									crypto_send({
										data : {action : "lang",params : []},
										success : function (e) {
											data = decrypt(e.cr);
											__lang = data;
											$('head').append($('<link rel="stylesheet" type="text/css" />').attr('href',host+'css/login.css')).append($('<link rel="stylesheet" type="text/css" />').attr('href',host+'css/slogin.css'));
											$(opt2.target).css('position','relative')
											$('<div></div>').addClass('secure login')
												.append($('<h2></h2>').html(data.__login))
												.append($('<form></form>').addClass('datas')
													.append($('<span></span>').addClass('label').html(data.__nick))
													.append($('<input/>').attr({'type':'text','id':'nick'}))
													.append($('<span></span>').addClass('label').html(data.__pass))
													.append($('<input/>').attr({'type':'password','id':'pass'}))
													.append($('<input/>').attr({'type':'submit','value':data.__submit}))
												).attr('id','dologin').bind('submit',function() {
															//Request the salt
															secure_ajax({
																page:{zone:'login'},
																params:{act : 'salt_pass',nick : $('#nick').val()},
																success: function (data) {
																	//Hash password
																	if (data.salt_a)
																		bcrypt.hashpw($('#pass').val(), data.salt_a, function(pass_s) {
																			bcrypt.hashpw($('#pass').val(),data.salt_b,function(pass_r) {
																				//Send password hashed and do login
																				secure_ajax({
																					page : {zone:'login'},
																					params:{act : 'login',nick : $('#nick').val(),pass : pass_s,pass2 : CryptoJS.MD5(pass_r+data.token).toString(),id : data.id},
																					success:function (dat) {
																						if (dat.login=='ok') {
																							//Save key
																							sessionStorage.ale_sess = dat.sess;
																							bcrypt.hashpw(pass_r, dat.tk, function(to_aes) {
																								$().secure('set_sess',{'sess' : dat.sess,'code' : CryptoJS.MD5(to_aes).toString()});
																								$('.login').html(__lang.__login_success).fadeOut(1500,function(){$(this).remove()});
																								var key = sessionStorage.ale_key = CryptoJS.MD5(to_aes).toString();
																								$().secure('set_sess',{sess : dat.sess,code : key});
																								//and get the next data
																								secure_ajax({
																									page:opt2.page,
																									params: opt2.params, 
																									success: function (data) {
																										$(opt2.target).html(data.content.html);
																										}
																								});
																							}, function() {});
																						} else
																							login_err();
																					}
																				});
																			}, function() {});
																		}, function() {});
																 	else
																		login_err();
																}
															});
															return false;
														})
												.appendTo(opt2.target).hide().fadeIn(600);
										},
										error : function() {
											//Nulla...
										}
									});
								}
							}
						break;
						case 'set_sess' :
							sess = opt2.sess;
							code = opt2.code;
						break;
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
																	$().secure({host:admin_host_path,act:'ajax_page',page:'home',params:[],user_func : "load_home_sec"});
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
											for (i in data.content.js) {
												crypto_send({
													data : {action : "load_script",params : {script : data.content.js[i]}},
													progress : pr_func,
													success : function (e) {
														var data = decrypt(e.cr);
														if (data.script)
															jQuery.globalEval(Base64.decode(data.script))
													},
													error : function() {
														alert('303');
														console.log('unable to complete operation');
													}
												});
											}
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
					url : host+'index.php',
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