window.onbeforeunload = function() {
	return "Se ricaricherai la pagina dovrai rifare il login";
}
function load_home_sec(ret) {
	$('.main').html(ret.content);
	$('.main .secure_link').each(function(i,el) {
		$(el).data('page',$(el).attr('href')).removeAttr('href').removeClass('secure_link').addClass('internal_link').click(function() {
			$().secure({act:'load_page',page:$(this).data('page'),params:[]});
		});
	});
	$('.admin_nav_bar li.com').click(load_com);
	load_com();
}
function admin_home() {
	$().secure({host:admin_host_path,act:'ajax_page',page:'home',params:[],user_func : "load_home_sec"});
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
function config_load_page(url,lato) {
	if (typeof lato=="string")
		var zone=lato;
	else {
		var i = url.indexOf('/');
		zone=url.slice(0,i);
		switch(zone) {
			case 'com' : zone='components'; break;
		}
		href = url.slice(i+1).split('?');
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
		$().secure({host:admin_host_path,act:'ajax_page',page:zone,params:params,user_func:"elab_links_sec"});
	}
}
function open_com(param) {
	$().secure({host:admin_host_path,act:'ajax_page',page:'components',params:{config:param},user_func : "elab_links_sec"});
}
function load_com_sec(ret) {
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