/**
 *	Admin.js for ALExxia
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

window.onbeforeunload = function() {
	return "Se ricaricherai la pagina dovrai rifare il login";
}
function ajax_request(params) {
	params.data['config'] = params.url;
	$().secure({host:admin_host_path,act:'ajax_page',page:params.type+'s',params:params.data,user_func : params.success},"ajax_call");
}
function elab_links_sec(data) {
	$('#admin_page').html($('<div></div>').addClass('sub_page').html(Base64.decode(data.content.html)));
	elab_links();
}
function elab_links() {
	$('.main .secure_link').each(function(i,el) {
		$(el).data('page',$(el).attr('href')).removeAttr('href').removeClass('secure_link').addClass('internal_link').click(function() {
			$().secure({host:admin_host_path,act:'ajax_page',page:$(this).data('page'),params:[],user_func:"elab_links_sec"});
		});
	});
	$('.main .config_link').each(function(i,el) {
		if ($(el).hasClass('com'))
			$(el).data('zone','components').removeClass('com');
		href=$(el).attr('href').split('?');
		params={};
		par=href[1].split('&');
		for (i in par) {
			dat=par[i].split('=');
			if (typeof dat[1] == 'string')
				params[dat[0]]=dat[1];
			else
				params[dat[0]]='';
		}
		params['config']=href[0];
		$(el).data('params',params).removeAttr('href').removeClass('config_link').addClass('internal_link').click(function() {
			$().secure({host:admin_host_path,act:'ajax_page',page:$(this).data('zone'),params:$(this).data('params'),user_func:"elab_links_sec"});
		});
	});
}
function config_load_page(url,lato,param) {
	if (typeof lato=="string")
		zone=lato;
	else {
		var i = url.indexOf('/');
		zone=url.slice(0,i);
		switch(zone) {
			case 'com' : zone='components'; break;
		}
	}
	href = url.slice(i+1).split('?');
	params={};
	if (href[1] != null) {
		par=href[1].split('&');
		for (i in par) {
			dat=par[i].split('=');
			if (typeof dat[1] == 'string')
				params[dat[0]]=dat[1];
			else
				params[dat[0]]='';
		}
	}
	params=$.extend(params,param);
	params['config']=href[0];
	$().secure({host:admin_host_path,act:'ajax_page',page:zone,params:params,user_func:"elab_links_sec"});
}
function open_com(param) {
	$().secure({host:admin_host_path,act:'ajax_page',page:'components',params:{config:param},user_func : "elab_links_sec"});
}
function load_com_sec(ret) {
	$('.admin_nav_bar li').removeClass('open').find('.com').addClass('open');
	var com_div = $('<div></div>').addClass('inner_com');
	$('#admin_page').html($('<div></div>').addClass('com_div').append(com_div));
	for (i in ret.content) {
		if (typeof ret.content[i].name[__lang] == "string")
			com_name=ret.content[i].name[__lang];
		else {
			for (j in ret.content[i].name) {
				com_name=ret.content[i].name[j];
				break;
			}
		}
		if (typeof ret.content[i].description[__lang] == "string")
			com_description=ret.content[i].description[__lang];
		else {
			for (j in ret.content[i].description) {
				com_description=ret.content[i].description[j];
				break;
			}
		}
		com_div.append($('<div></div>').addClass('com').data('page',ret.content[i].page).click(function() {
				open_com($(this).data('page'));
			})
			.append($('<span></span>').addClass('icon').css('background-image','url('+host_path+'com/'+ret.content[i].icon+')'))
			.append($('<span></span>').addClass('name').text(com_name))
			.append($('<span></span>').addClass('description').text(com_description))
		);
	}
	com_div.css('width',$('.com_div .com').length*$('.com_div .com').outerWidth()).mousewheel(function(event, delta, deltaX, deltaY) {
		$('.com_div').animate({scrollLeft: '+='+(100*delta)}, 100);
	});;
}
function load_com() {
	$().secure({host:admin_host_path,act:'ajax_page',page:'components',params:[],user_func : "load_com_sec"});
}
/*
	GESTIONE UTENTI
*/
function unban_user() {
	that=this;
	ajax_request({
		url : {act :'unban',id : $(this).closest('.usr').data('id')},
		type : 'user',
		data : {},
		success : function (e) {
			if (e.content.r=='y')
				$(that).unbind('click').addClass('ban').removeClass('unban').click(ban_user);
		}
	});
}
function ban_user() {
	that=this;
	ajax_request({
		url : {act :'ban',id : $(this).closest('.usr').data('id')},
		type : 'user',
		data : {},
		success : function (e) {
			if (e.content.r=='y')
				$(that).unbind('click').addClass('unban').removeClass('ban').click(unban_user);
		}
	});
}
function approve_user() {
	that=this;
	ajax_request({
		url : {act :'approve',id : $(this).closest('.usr').data('id')},
		type : 'user',
		data : {},
		success : function (e) {
			if (e.content.r=='y')
				$(that).fadeOut(600);
		}
	});
}
function level_user() {

}
function del_user() {
	that=this;
	ajax_request({
		url : {act :'delete',id : $(this).closest('.usr').data('id')},
		type : 'user',
		data : {},
		success : function (e) {
			if (e.content.r=='y')
				$(that).closest('.usr').slideUp(600);
		}
	});
}
function edit_user() {
	// body...
}
function show_grp () {
	// body...
	$('body').css('overflow','hidden');
	$('.grp_main').css({'float':'left'});
	$('.usr_main').css({'position':'relative','float':'left','width':'100%','height':'100%'}).animate({'margin-left':'-100%'},{
			duration : 1000,
			progress : function(a) {
				$('.grp_main').css('width',parseFloat($(this).css('margin-left').match(/-?([0-9\.]*)/)[1])-190);
			},
			complete : function () {
				$('.usr_next').animate({'width':0},100,'swing',function () {
					$(this).removeAttr('style').hide();
					$('.usr_main').removeAttr('style').hide();
					$('.grp_main').css({'float':'','width':''});
					$('body').css('overflow','');
				});
			}
	});
	$('.grp_main').show();
}
function show_usr () {
	$('body').css('overflow','hidden');
	$('.grp_main').css({'float':'left'});
	$('.usr_next').show();
	$('.usr_main').show().css({'position':'relative','float':'left','width':'100%','height':'100%','margin-left':'-100%'})
	.animate({'margin-left':'0%'},{
		duration : 1000,
		progress : function(a) {
			$('.grp_main').css('width',parseFloat($(this).css('margin-left').match(/-?([0-9\.]*)/)[1])-190);
		},
		complete : function () {
			$(this).removeAttr('style');
			$('.usr_next').removeAttr('style');
			$('body').css('overflow','');
			$('.grp_main').css({'float':'','width':''});
			$('.grp_main').hide();
		}});
}
function load_usr_sec(ret) {
	$('.admin_nav_bar li').removeClass('open').find('.usr').addClass('open');
	var usr_div = $('<div></div>').addClass('inner_usr');
	gr_text = $('<span></span>').addClass('vertical_text').text('UTENTI');
	usr_text = $('<span></span>').addClass('vertical_text').text('GRUPPI');
	$('#admin_page').html(
		$('<div></div>').addClass('usr_main')
			.html($('<div></div>').addClass('usr_bar')
				.append($('<a></a>').text(ret.content.lang._new).button())
				.append($('<input/>')))
			.append($('<div></div>').addClass('usr_div').append(usr_div))
			.append($('<div></div>').addClass('usr_next').append(usr_text).append($('<span></span>').addClass('arrow')).click(show_grp)));
	if (ret.content.groups!='no') {
		var grp_div = $('<div></div>').addClass('inner_grp');
		$('#admin_page').append($('<div></div>').addClass('grp_main')
			.append($('<div></div>').addClass('grp_prev').append(gr_text).append($('<span></span>').addClass('arrow')).click(show_usr))
			.append($('<div></div>').addClass('usr_bar').append($('<a></a>').text(ret.content.lang._new).button()))
			.append($('<div></div>').addClass('grp_div').append(grp_div)));
		for (i in ret.content.groups.e) {
			grp_div.append($('<div></div>').addClass('grp')
				.append($('<span></span>').addClass('lvl').text(i))
				.append($('<span></span>').addClass('name').text(ret.content.groups.e[i]))
				.append($('<div></div>').addClass('actions').append($('<span></span>').addClass('change'))));
		}
	} else
		$('.usr_text').hide();
	usr_text.css('margin-top',-gr_text.height()/2);
	gr_text.css('margin-top',-gr_text.height()/2);
	$('.grp_main').hide();
	for (i in ret.content.users) {
		actions = $('<div></div>').addClass('actions');
		if (parseInt(ret.content.users[i].level)>parseInt(ret.content.self.level)) {
			if (!parseInt(ret.content.users[i].actived))
				actions.append($('<span></span>').addClass('approve').click(approve_user));
			if (parseInt(ret.content.users[i].banned))
				actions.append($('<span></span>').addClass('unban').click(unban_user));
			else
				actions.append($('<span></span>').addClass('ban').click(ban_user));
			actions.append($('<span></span>').addClass('level').click(level_user))
			.append($('<span></span>').addClass('del').click(del_user))
			.append($('<span></span>').addClass('edit').click(edit_user))
		}
		actions.append(
			$('<span></span>').addClass('info').addClass('i'+ret.content.users[i].info));
		usr_div.append(
			$('<div></div>').addClass('usr').data('id',ret.content.users[i].id)
				.append($('<span></span>').addClass('name').text(ret.content.users[i].nick+' ('+ret.content.users[i].email+')'))
				.append(actions)
		)
	}
	console.log(ret);
}
function load_usr() {
	$().secure({host:admin_host_path,act:'ajax_page',page:'users',params:[],user_func : "load_usr_sec"});
}
$(function() {
	load_com();
	$('.admin_nav_bar li.com').click(load_com);
	$('.admin_nav_bar li.usr').click(load_usr);
})