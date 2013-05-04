/*
	NiiWin v0.1.1, bt Maurizio Carboni (maury91@gmail.com|http://www.facebook.com/maury91)
	
	Questo widget per jquery è un gestore di finestre alternativo al classico jqueryUI dialog
	Utilizza un sistema simile (uguale) a windows 7
	Le finestre aperte vengono categorizzate secondo la categoria che l'utente da' in gruppi nella barra in basso
	Le funzioni delle finestre son le stesse di quelle di windows
	Passando il mouse nel retangolo in basso a destra le finestre diventano trasparenti, cliccandoci vengono tutte minimizate mostrando il desktop
	Facendo doppio click sulla barra delle applicazioni invece va in modalità Aero
	Unica cosa che manca è il rettangolino che mostra il gruppo di finestre che si sdoppia quando ci son più finestre di quel gruppo.
	Internet Explorer non è supportato (non ho nemmeno voglia di sbatterci la testa)
	
	
	Utilizzo :
		//Inizializzazione
	$('#miafinestra').niiwin({
		title : ''							//Titolo finestra(quello che va' sopra)
		width : 640,						//Larghezza
		height : 480,						//Altezza
		zindex : 1000,						//Z-Index di partenza
		onClose : f_nothing,				//Funzione da eseguire quando è richiesta la chiusura, se la funziona ritorna true la finestra si chiuderà, altrimenti no, su this è contenuto il div della finestra
		onMaximize : f_nothing,				//Funzione da eseguire quando è richiesta la massimizazione, idem
		onMinimize : f_nothing,				//Funzione da eseguire quando è richiesta la minimazione, idem
		onNormalize : f_nothing,			//Funzione da eseguire quando è richiesta la normalizzazione, idem
		onShow : f_nothing,					//Funzione da eseguire quando è richiesto di mostrare la finestra, idem
		open : false,						//Aprire la finestra appena viene creata
		icon : 'none',						//Classe dell'icona
		set : 'none'						//Nome del gruppo di finestra (più finestre con lo stesso nome del gruppo verrano ragruppate)
		buttons : {'testo' : function() {}}	//Bottoni da mettere nella finestra
	});
	
	Tutti i parametri son opzionali, se omessi veranno usati quelli di default (quelli messi sopra).
	Il titolo della finestra se presente sarà preso dall'attributo title dell'elemento passato
	Si possono passare anche gruppi di elementi :
		$('.miefinestre,.altrefinestre,#finestraspecifica').niiwin({set:'gruppoA'});
	
		//Funzioni dopo l'inizializzazione
	$('#miafinestra').niiwin('open');			//Apertura
	$('#miafinestra').niiwin('minimize');		//Riduci a icona
	$('#miafinestra').niiwin('maximize');		//Ingrandisci
	$('#miafinestra').niiwin('normalize');		//Normalizza
	$('#miafinestra').niiwin('close');			//Chiudi
	$('#miafinestra').niiwin('bringToTop');		//Porta in primo piano
	$('#miafinestra').niiwin('transparent');	//Rendila trasparente
	$('#miafinestra').niiwin('notransparent');	//Rendila non trasparente
	
		//Icona
	Per dare un'icona a una finestra creare una classe css di questo tipo :
	<style>
		.niiWinIcon.nomeclasse {
			background-image:url('nomefileicona.png');
		}
	</style>
	quando si inizializza passare il nome della classe nel parametro icon
		$('#finestra').niiwin({icon:'nomeclasse'});
		
		//Traybar
	tray = $('').niiwinIcon({
		hint : '',					//Suggerimento che deve apparire passando il mouse nella traybar (dove normalmente sta il volume)
		icon : 'none',				//Classe dell'icona (si usa lo stesso modo delle altre icone)
		onClick : f_nothing			//Funzione da usare quando si ci clicca sopra
	})
	
		//Icone nella trayicon
	Per dare un'icona a un'icona nella traybar si fa esattamente come per la finestra, con la differenza che cambia la classe abbinata :
	<style>
		.icon.nomeclasse {
			background-image:url('nomefileicona.png');
		}
	</style>
	
*/
var
	totWins=0;
	topZindex=1;
(function($) {
	var $dragging = null;
	var niiWin = function() {
		var f_nothing = function(e) {
			return true;
		},
		defaults = {
			title : '',
			width : 640,
			height : 480,
			zindex : 1000,
			onClose : f_nothing,
			onMaximize : f_nothing,
			onMinimize : f_nothing,
			onNormalize : f_nothing,
			onShow : f_nothing,
			open : false,
			icon : 'none',
			set : 'none',
			buttons : {}
		},
		icondef = {
			hint : '',
			icon : 'none',
			onClick : f_nothing
		},
		preparewin = function(e,sx) {
			var my_win = $(sx).closest('.niiWin');
			if(!(my_win.hasClass('draggable')||my_win.hasClass('resizable'))) {
				coords = my_win.offset();
				el_x = e.pageX-coords.left;
				el_y = e.pageY-coords.top;
			}
			el_w = my_win.outerWidth();
			el_h = my_win.outerHeight();
			$dragging = my_win;
		},
		startdrag = function(e) {
			e.preventDefault();
			if ($(this).closest('.niiWin').hasClass('niiMaximized'))
				return false;
			preparewin(e,this);
			$dragging.addClass('draggable');
			wh = $(window).height();
			ww = $(window).width();
			$('body').on("mousemove", function(e) {
				if ($dragging&&$dragging.hasClass('draggable')) {
					//Evitiamo di poterle spostare fuori...
					my_y=e.pageY-el_y;
					my_x=e.pageX-el_x;
					if (my_y+el_h>wh)
						my_y=wh-el_h;
					if (my_x+el_w>ww)
						my_x=ww-el_w;
					if (my_y<0)my_y=0;
					if (my_x<0)my_x=0;
					$dragging.css({
						top: my_y,
						left: my_x
					});
				}
			});
		},
		stopdrag = function(e) {
			e.preventDefault();
			if ($dragging) {
				$dragging.removeClass('draggable').removeClass('resizable').removeClass('resN').removeClass('resW');
				$dragging = null;
			}
		},
		startresize = function(e,rs) {
			e.preventDefault();
			if ($(rs).closest('.niiWin').hasClass('niiMaximized'))
				return false;
			//Uso sempre $dragging per fare in modo che stopdrag funzioni su entrambi
			preparewin(e,rs);
			diff_sw=$dragging.outerWidth()-$dragging.find('.niiWinContent').width();
			diff_sh=$dragging.outerHeight()-$dragging.find('.niiWinContent').height();
			$dragging.addClass('resizable');
			wh = $(window).height();
			ww = $(window).width();
			to_resize = $dragging.find('.niiWinContent');
			resw = $dragging.hasClass('resW');
			resn = $dragging.hasClass('resN');
			$('body').on("mousemove", function(e) {
				if ($dragging&&$dragging.hasClass('resizable')) {
					//Evitiamo di poterla ridimensionare oltre le dimensioni...
					my_w=e.pageX-coords.left;
					my_h=e.pageY-coords.top;
					if (coords.top+my_h>wh)
						my_h=wh-coords.top;
					if (coords.left+my_w>ww)
						my_w=ww-coords.left;
					if (my_w<200) my_w=200;
					if (my_h<100) my_h=100;
					if (resw)
						to_resize.width(my_w-diff_sw);
					if (resn)
						to_resize.height(my_h-diff_sh);
				}
			});
		},
		startresizeN = function(e) {
			$(this).closest('.niiWin').addClass('resN');
			startresize(e,this);
		},
		startresizeW = function(e) {
			$(this).closest('.niiWin').addClass('resW');
			startresize(e,this);
		},
		startresizeNW = function(e) {
			$(this).closest('.niiWin').addClass('resN resW');
			startresize(e,this);
		},
		closewin = function(e) {
			my_win=$(this).closest('.niiWin');
			if (my_win.data('niiWinEvents').onClose.call(my_win.get(0))) {
				info = my_win.data('niiWinInfo');
				my_win.hide();
				if ($('#niiWinBottomBar .winMin.'+info.set).data('opened')<2)
					$('#niiWinBottomBar .winMin.'+info.set).remove();
				else {
					$('#niiWinBottomBar .winMin.'+info.set).data('opened',$('#niiWinBottomBar .winMin.'+info.set).data('opened')-1).find('#'+my_win.data('niiWinID')).remove();
				}
			}
				
		},
		miniclosewin = function() {
			$($(this).closest('p').data('win')).niiwin('close');
		},
		reducewin = function(e) {
			my_win=$(this).closest('.niiWin');
			if (my_win.data('niiWinEvents').onMinimize.call(my_win.get(0))) {
				info = my_win.data('niiWinInfo');
				my_win.hide();					
			}
		},
		reopen = function() {
			$($(this).data('win')).niiwin('open');
		},
		open = function(e) {
			if ($(e).data('niiWinEvents').onShow.call(e)) {
				go_up(e);
				$(e).show();
				info = $(e).data('niiWinInfo');
				if ($('#niiWinBottomBar .winMin.'+info.set+' #'+$(e).data('niiWinID')).length<1) {							
					if ($('#niiWinBottomBar .winMin.'+info.set).length<1) {
						$('#niiWinBottomBar').append($('<div></div>').addClass('winMin').addClass(info.set).append($('<span></span>').addClass('niiWinIcon').addClass(info.icon)));
						$('#niiWinBottomBar .winMin.'+info.set).data('opened',1).append($('<div></div>').addClass('niiThumb'));
					} else {
						$('#niiWinBottomBar .winMin.'+info.set).data('opened',$('#niiWinBottomBar .winMin.'+info.set).data('opened')+1);
					}
					$('#niiWinBottomBar .winMin.'+info.set+' .niiThumb').append($('<p></p>').text(info.title).attr('id',$(e).data('niiWinID')).data('win',e).click(reopen).append($('<span></span>').html('&times;').addClass('niiClose').click(miniclosewin)));
					if (info.icon!='none')
						$('#niiWinBottomBar .winMin.'+info.set+' #'+$(e).data('niiWinID')).prepend($('<span></span>').addClass('niiWinIcon').addClass(info.icon));
				}
			}
		},
		reduceall = function() {
			$('.niiWin').niiwin('minimize');
		},
		transparentall = function() {
			$('.niiWin').niiwin('transparent');
		},
		notransparentall = function() {
			$('.niiWin').niiwin('notransparent');
		},
		go_up = function(e) {
			if ($(e).data('isNoUp')) {
				$('.niiWin').data('isNoUp',true);
				$(e).data('isNoUp',false);
				if (parseInt($(e).css('z-index'))<=topZindex) 
					$(e).css('z-index',topZindex+1);
				topZindex=parseInt($(e).css('z-index'));
			}
		},
		modify_sizewin = function(e) {
			my_win=$(this).closest('.niiWin');
			if (my_win.hasClass('niiMaximized')) {
				if (my_win.data('niiWinEvents').onMinimize.call(my_win.get(0))) {
					lastdata = my_win.removeClass('niiMaximized').data('niiWinLastData');
					diff_sw=my_win.outerWidth()-my_win.find('.niiWinContent').width();
					diff_sh=my_win.outerHeight()-my_win.find('.niiWinContent').height();
					my_win.css({left:lastdata.position.left,top:lastdata.position.top}).find('.niiWinContent').css({width:lastdata.size.w-diff_sw,height:lastdata.size.h-diff_sh});
					$(this).removeClass('niiNormalize').addClass('niiMaximize');
				}
			} else {
				if (my_win.data('niiWinEvents').onMaximize.call(my_win.get(0))) {
					my_win.addClass('niiMaximized').data('niiWinLastData',{
						position : my_win.offset(),
						size : {w : my_win.width(), h : my_win.height()}
					});
					diff_sw=my_win.outerWidth()-my_win.find('.niiWinContent').width();
					diff_sh=my_win.outerHeight()-my_win.find('.niiWinContent').height();
					my_win.css({left:1,top:1}).find('.niiWinContent').css({width:$(window).width()-diff_sw-2,height:($(window).height()-$('#niiWinBottomBar').height())-diff_sh-2});
					$(this).addClass('niiNormalize').removeClass('niiMaximize');
				}
			}
		},
		normalmode = function() {
			$('body').unbind('click',normalmode).css({'-webkit-perspective':'','perspective':'','overflow':''});
			max=0;
			$('.niiWin:visible').unbind('mousedown',normalmode).removeClass('niiAero').removeClass('aeroanimation').each(function(i,el) {
				data = $(this).data('niiwin_offset');
				$(this).css({'left':data.l,'top':data.t,'z-index':data.z});
				if(max<parseInt($(this).css('z-index')))
					max = parseInt($(this).css('z-index'));
			});
			$('#niiWinBottomBar').show(400);
			if($(this).hasClass('niiWin'))
				$(this).css('z-index',max+1);
		},
		modeaero = function() {
			$(this).hide();			
			$('.niiWin:visible').bind('mousedown',normalmode).each(function(i,el) {
				$(this).data('niiwin_offset',{l:$(this).css('left'),t:$(this).css('top'),z:$(this).css('z-index')});
				$(this).animate({'left':$('body').width()/4+i*50,'top':30+i*20,'z-index':1000+i},600);
			});			
			setTimeout(function(){$('body').click(normalmode)},100);
			setTimeout(function() {$('body').css({'-webkit-perspective':'2000px','perspective':'2000px','overflow':'hidden'});$('.niiWin:visible').addClass('aeroanimation').addClass('niiAero');},610);
		};
		return {
			icon : function(opt) {
				opt = $.extend({}, icondef, opt||{});
				if ($('#niiWinBottomBar').length<1)
					$('body').append($('<div></div>').attr('id','niiWinBottomBar').dblclick(modeaero).append($('<div></div>').addClass('showDesktop').click(reduceall).hover(transparentall,notransparentall)).append($('<div></div>').addClass('trayIcons')));
				return $('<div></div>').addClass('trayIcon').append($('<span></span>').addClass('icon').addClass(opt.icon)).attr('title',opt.hint).click(opt.onClick).appendTo('#niiWinBottomBar .trayIcons');
				/*
					hint : '',
					icon : 'none',
					onClick : f_nothing
				*/
			},
			call : function(opt) {
				if (typeof opt=='string') {
					return this.each(function () {
						if ($(this).data('niiWinID')) {
							switch(opt) {
								case 'open':
									open(this);							
								break;
								case 'minimize':
									if ($(this).is(':visible'))
										$(this).find('.niiMinimize').click();
								break;
								case 'maximize':
									if (!$(this).hasClass('niiMaximized'))
										$(this).find('.niiMaximize').click();
								break;
								case 'normalize':
									if ($(this).hasClass('niiMaximized'))
										$(this).find('.niiNormalize').click();
								break;
								case 'close':
									$(this).find('.niiClose').click();
								break;
								case 'bringToTop':
									go_up(this);
								break;
								case 'transparent':
									if (!$(this).hasClass('niiTransparent')) {
										$(this).css({width:$(this).width(),height:$(this).height()}).addClass('niiTransparent').find('.niiTitleBar,.niiWinContent,.niiBottomBar').hide();
									}
								break;
								case 'notransparent':
									if ($(this).hasClass('niiTransparent')) {
										$(this).css({width:'',height:''}).removeClass('niiTransparent').find('.niiTitleBar,.niiWinContent,.niiBottomBar').show();
									}
								break;
							}
						}
					});
				} else {
					opt = $.extend({}, defaults, opt||{});
					return this.each(function () {
						if (!$(this).data('niiWinID')) {
							if ($('#niiWinBottomBar').length<1)
								$('body').append($('<div></div>').attr('id','niiWinBottomBar').dblclick(modeaero).append($('<div></div>').addClass('showDesktop').click(reduceall).hover(transparentall,notransparentall)).append($('<div></div>').addClass('trayIcons')));
							$(this).data('niiWinID','w'+totWins).data('isNoUp',true);
							if (typeof $(this).attr('title')== 'string') {
								opt.title=$(this).attr('title');
								$(this).removeAttr('title');
							}
							$(this).data('niiWinInfo',{icon : opt.icon, set : opt.set, title : opt.title});
							totWins++;
							$(this).data('niiWinEvents',{
								onClose : opt.onClose,
								onMaximize : opt.onMaximize,
								onMinimize : opt.onMinimize,
								onNormalize : opt.onNormalize,
								onShow : opt.onShow
							});							
							if (topZindex<opt.zindex)
								topZindex=opt.zindex;
							htm = $(this).html();
							if (opt.height+1>=$(window).height()-$('#niiWinBottomBar').height())
								opt.height=$(window).height()-$('#niiWinBottomBar').height()-2;
							if (opt.width+1>=$(window).width())
								opt.width=$(window).width()-2;
							bottom_bar = $('<div></div>').addClass('niiBottomBar').append('<div></div>');
							zero_size=1;
							for (k in opt.buttons) {
								zero_size=0;
								bottom_bar.find('div').append($('<button></button>').text(k).data('to_call',opt.buttons[k]).click(function(){$(this).data('to_call').call($(this).closest('.niiWin').get(0))}).button());
							}
							if (zero_size)
								bottom_bar='';
							$(this).addClass('niiWin').css({'z-index' : opt.zindex}).html($('<div></div>').addClass('niiTitleBar').on("mousedown", startdrag).html($('<span></span>').text(opt.title))
								.append($('<a></a>').addClass('niiButton niiClose').append('<span></span>').click(closewin))
								.append($('<a></a>').addClass('niiButton niiMaximize').append('<span></span>').click(modify_sizewin))
								.append($('<a></a>').addClass('niiButton niiMinimize').append('<span></span>').click(reducewin)))
							.append($('<span></span>').addClass('niiResize niiResizeN').on('mousedown', startresizeN))
							.append($('<span></span>').addClass('niiResize niiResizeW').on('mousedown', startresizeW))
							.append($('<span></span>').addClass('niiResize niiResizeNW').on('mousedown', startresizeNW))
							.append($('<div></div>').addClass('niiWinContent').css({width:opt.width,height:opt.height}).html(htm))
							.append(bottom_bar)
							.on('mousedown',function(){go_up(this)})
							.hide();
							if (opt.icon!='none')
								$(this).find('div.niiTitleBar').prepend($('<span></span>').addClass('niiWinIcon').addClass(opt.icon));
							if (opt.open)
								open(this);
							if (totWins<2)
								$('body').on("mouseup", stopdrag);
							if ((opt.width+3>$(window).width())||(opt.height+3>$(window).height()-$('#niiWinBottomBar').height()))
								$(this).offset({left:0,top:0});
							else {
								var p_left=(totWins-1)*30,
									p_top=(totWins-1)*30;
								h_s=Math.floor((($(window).height()-10)-($('#niiWinBottomBar').height()+$(this).height()))/30+1)*30;
								w_s=Math.floor((($(window).width()-10)-$(this).width())/30+1)*30;
								if ((p_top>(h_s-30))||(p_left>(w_s-30))) {									
									xn=Math.floor(p_top/h_s);
									p_top%=h_s;
									p_left=(p_top+xn*70)%w_s;
								}
								$(this).offset({left:p_left,top:p_top});
							}
						}
					});
				}
			}
		}
	}();
	$.fn.extend({
		niiwin : niiWin.call,
		niiwinIcon : niiWin.icon
	});
})(jQuery);