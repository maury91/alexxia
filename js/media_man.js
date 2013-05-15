/**
 *	Media manager (js) for ALExxia
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
/*
	Ultima modifica 11/03/2013
	Estensione Media Manager
	Funziona con i seguenti gestori di finestre :
		- UI-dialog
		- NiiWin
*/
(function($) {
	var mediaMan = function() {
		var
			def = {uid : -1,multiple : false, selType : 'file', upload : true, del : false, extensions : 'all', dir : '.', navigable : true, show_folder : true, show_files : true },
			ext = function(b) {
				a = b.substring(b.lastIndexOf(".") + 1, b.length);
				return a.toLowerCase()
			},
			media_dir = function(mediam,dirx) {
				$(mediam).find('.media_del').addClass('imgdelbig_in');
				media_params = $(mediam).data('params');
				$.ajax({
					url: __media_man_base_path+"zone_media_man.html",
					data: { act : "list" , d : dirx, uid : media_params.uid},
					cache: false,
					dataType: "json",
					success: function (a) {
						filecon = "";
						subber = $(mediam).find('.media_file_list_sub').html('');
						$.each(a.data, function (a, b) {
							$(mediam).data('cur_dir',dirx);
							if (b) {
								if ((b.t == "f")&&(media_params.show_files)) {
									e = ext(b.n);
									if (e == "png" || e == "jpe" || e == "jpeg" || e == "jpg" || e == "gif" || e == "bmp" || e == "ico" || e == "tiff" || e == "tif" || e == "svg" || e == "svgz" ) cla = "load";
									else if (e == "js") cla = "jsbig";
									else if (e == "xml") cla = "xmlbig";
									else if (e == "php") cla = "phpbig";
									else if (e == "css") cla = "cssbig";
									else if (e == "htm" || e == "html" || e == "phtml") cla = "htmlbig";
									else if (e == "zip" || e == "rar" || e == "7z" || e == "tar" || e == "gz" || e == "iso") cla = "zipbig";
									else cla = "filebig";
									if (cla == "load")  
										add = "<img src='" + __media_man_base_path+dirx + "/" + b.n + "' class='media_man_imm' />";
									else add = "";
									info = {n : dirx+'/'+b.n,t : b.t,p : b.p,s : b.s};
									subber.append($('<li></li>').data('info',info).addClass('file').click(media_select_elem)
											.append($('<a></a>').addClass('img'+cla).html(add))
											.append($('<a></a>').addClass('fname').text(b.n)));
								}
								if ((b.t == "d")&&(media_params.show_folder)) 
									subber.append(
										$('<li></li>').data('info',b).addClass('file')
											.append($('<a></a>').addClass('imgdirbig'))
											.append($('<a></a>').addClass('fname').text(b.n).click(media_open_dir))
									);
								if (b.t == "s") {
									$(mediam).find(".media_file_list_sub").selectable({ filter: 'li',  cancel: "a"  ,stop: function() {
										onMedia_select_elem($(this).closest('.media-manager').get(0));
									}});									
									$(mediam).find('.media_man_imm').css('height',0).load(function(){
										em_size = parseFloat($(this).css("font-size"));
										//Controllo dimensioni
										$(this).hide().css('height','');
										if ($(this).height() > $(this).width()) {
											if ($(this).height() > em_size*8)
												$(this).height('8em');
										} else {
											if ($(this).width() > em_size*8)
												$(this).width('8em');
										}
										$(this).show(300);	
										$(this).parent().removeClass('imgload');
									});
									rnm = true
								}
							}
						})
					}
				});
			},
			media_dir_up = function() {
				madiax = $(this).closest('.media-manager');
				mediam_dir = madiax.data('cur_dir');
				//Controllo che non esca dalla directory scelta
				newd = mediam_dir.substring(0, mediam_dir.lastIndexOf("/"));	
				if (newd.indexOf(madiax.data('params').dir) != -1) media_dir(madiax.get(0),newd);
			},
			set_media_dir = function(a) {
				params = $(a).data('params');
				if (typeof params.dir == 'undefined')
					setTimeout(function(){set_media_dir(a)},20);
				else 
					$(a).data('dir',params.dir);
			},
			media_man_diag_open = function() {
				var self = this;
				this.repete = function() {
					media_man_dir =	$(self).find('.media-manager').data('dir');			
					if (typeof media_man_dir == 'undefined') 
						window.setTimeout(function() { self.repete(); }, 30);
					else {
						media_params = $(self).find('.media-manager').data('params');
						if (typeof media_params.del == 'undefined') 
							window.setTimeout(function() { self.repete(); }, 30);
						else {
							if (media_params.del)
								$(self).find('.media_del').show();
							else
								$(self).find('.media_del').hide();
							if (media_params.upload)
								$(self).find('.media_upload').show();
							else
								$(self).find('.media_upload').hide();
							$(self).find(".validateTips").hide();
							media_dir($(self).find('.media-manager').get(0),media_man_dir);
						}
					}
				}
				this.repete();
				return true;
			},
			media_select_elem = function() {
				if (!ctrlPressed)
					$(this).closest('.media-manager').find('.media_file_list_sub').removeClass('ui-selected');
				$(this).toggleClass('ui-selected');
				onMedia_select_elem($(this).closest('.media-manager').get(0));
			},
			media_open_dir = function() {
				main = $(this).closest('.media-manager');			
				if (main.data('params').navigable)
					media_dir(main.get(0),main.data('cur_dir')+'/'+$(this).closest('li').data('info').n);
			},
			onMedia_select_elem = function(a) {
				media_selected = new Array;
				$(a).find("li.ui-selected").each(function() {
					x = $(this).data('info');
					media_selected.push(x);
				});
				if (media_selected.length > 0)
					$(a).find('.media_del').removeClass('imgdelbig_in');
				else
					$(a).find('.media_del').addClass('imgdelbig_in');
				$(a).data('media_selected',media_selected);
			},
			media_notify_error = function(main,t) {
				url_error = $(main).find(".validateTips");
				url_error.show(200).html( t ).addClass( "ui-state-highlight" );
				setTimeout(function() {
					url_error.removeClass( "ui-state-highlight", 1500 ).hide(1500);
				}, 1000 );
			},
			media_man_diag_ok = function() {
				main = $(this).find('.media-manager');
				media_params = main.data('params');
				media_selected = main.data('media_selected')||[];
				//Eliminazione dei file indesiderati
				if (media_params.selType == 'file') {
					i = 0;
					while (i < media_selected.length) {
						if (media_selected[i].t == 'd')
							media_selected.splice(i,1);
						else
							i++;
					}
				}
				if (media_params.selType == 'dir') {
					i = 0;
					while (i < media_selected.length) {
						if (media_selected[i].t == 'f')
							media_selected.splice(i,1);
						else
							i++;
					}
				} else {
					//Eliminazione delle estensioni non adatte
					if (media_params.extensions != 'all') {
						if (typeof media_params.extensions == 'string') {
							c = media_params.extensions;
							media_params.extensions  = new Array();
							media_params.extensions.push(c);
						}
						i = 0;
						while (i < media_selected.length) {
							if ((media_selected[i].t == 'f')&&($.inArray(ext(media_selected[i].n),media_params.extensions)==-1)) 
								media_selected.splice(i,1);
							else
								i++;
						}
					}
				}
				for (i in media_selected) {
					if (media_selected[i].n[0]=='.')
						media_selected[i].n=media_selected[i].n.slice(2,media_selected[i].n.length);
					media_selected[i].n=__media_man_base_path+media_selected[i].n;
				}
				//Controllo selezione > 0
				if (media_selected.length>0) {
					//Controllo nel caso di selezione multipla o singola
					if ((media_selected.length>1)&&(!media_params.multiple))
						media_notify_error(main,l__one_file);
					else {
						if (typeof media_params.onSelected != 'undefined')
							media_params.onSelected(media_selected);
						
						if (typeof $('').niiwin == 'function')
							$(this).niiwin('close');
						else
							$(this).dialog('close');
					}
				} else media_notify_error(main,l__no_file);
			},
			processFiles = function(files,elem) {
				if(files && typeof FileReader !== "undefined") {
					for(var i=0; i<files.length; i++) {
						readFile(files[i],elem);
					}
				}
				else {
					//some message or fallback
				}
			},
			readFile = function(file,elem) {			
				if (file) {
					//Creazione del file fitizio nel gruppo	
					mediam_dir = $(elem).closest('.media-manager').data('cur_dir');
					params = $(elem).closest('.media-manager').data('params');
					var fileSize = 0;
					sizeA = file.size;
					sizeB = 0;
					sizes=['B','KB','MB','GB','TB'];
					while (sizeA > 1024) {
						sizeA /= 1024;
						sizeB++;
					}
					fileSize=(Math.round(sizeA*100)/100)+sizes[sizeB];
					var id = "media-upload-status-"+file.name;
					id = id.replace(/\./g,"-").replace(/ /g,"-");
					b = {n : file.name,t : 'f',p : '-rw-rw-rw-', s : fileSize};
					info = {n : mediam_dir+'/'+b.n,t : b.t,p : b.p,s : b.s};
					var pbar = $('<span></span>');
					$(elem).append($('<li></li>').data('info',info).addClass('file').click(media_select_elem)
						.append($('<a></a>').addClass('imgload')
							.append($('<p></p>').addClass('progress-bar').append(pbar)))
						.append($('<a></a>').addClass('fname').text(b.n)));
					$(elem).scrollTop(9999999);
					var xhr = new XMLHttpRequest();
					xhr.file = file; // not necessary if you create scopes like this
					xhr.upload.onprogress = function(e) {
						var done = e.position || e.loaded, total = e.totalSize || e.total;
						pbar.css('width',(Math.floor(done/total*1000)/10) + '%');
					};
					xhr.onreadystatechange = function(e) {
						if ( 4 == this.readyState ) {
							response = eval("(" + xhr.responseText + ")");
							if (response.success) {
								img = pbar.closest('.imgload');
								//Controllo se il nome è cambiato
								if (response.changed) {
									newn =response.filename;
									toop = img.parent();
									toop.attr('title',newn);
									img.attr('rev',newn);
									toop.find('.fname').html(newn);
								} else newn = file.name;
								//Controllo del tipo
								e = ext(newn);
								if (e == "png" || e == "jpg" || e == "bmp" || e == "gif") cla = "load";
								else if (e == "js") cla = "jsbig";
								else if (e == "xml") cla = "xmlbig";
								else if (e == "php") cla = "phpbig";
								else if (e == "css") cla = "cssbig";
								else if (e == "htm" || e == "html" || e == "phtml") cla = "htmlbig";
								else if (e == "zip" || e == "rar" || e == "7z" || e == "tar" || e == "gz" || e == "iso") cla = "zipbig";
								else cla = "filebig";
								if (cla == "load")  
									add = "<img src='" +__media_man_base_path+ mediam_dir + "/" + newn + "' class='media_man_imm' />";
								else add = "";
								img.removeClass('imgload').addClass('img'+cla).html(add);
								img.find('.media_man_imm').css('height',0).load(function(){
									em_size = parseFloat($(this).css("font-size"));
									//Controllo dimensioni
									$(this).hide().css('height','');
									if ($(this).height() > $(this).width()) {
										if ($(this).height() > em_size*8)
											$(this).height('8em');
									} else {
										if ($(this).width() > em_size*8)
											$(this).width('8em');
									}
									$(this).show(300);	
									$(this).parent().removeClass('imgload');
								});
							} else {
								pbar.closest('li').remove();
								alert(response.error);
							}
						}
					};
					url = __media_man_base_path+'zone_media_man.html?act=upl&uid='+params.uid+'&d='+mediam_dir+'&myfile='+file.name;
					xhr.open("POST", url, true);
					xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
					xhr.setRequestHeader("X-File-Name", encodeURIComponent(file.name));
					xhr.setRequestHeader("Content-Type", "application/octet-stream");
					xhr.send(file);	
				}
			},
			media_upload = function() {
				$(this).parent().find('input[type="file"]').click();
			},
			media_newfolder = function() {
				a = prompt(l__name);
				main = $(this).closest('.media-manager');
				mediam_dir = main.data('cur_dir');
				media_params = main.data('params');
				if (a != null) $.ajax({
					url: __media_man_base_path+"zone_media_man.html",
					data : {act : 'newd', f : mediam_dir + "/" + a, uid : media_params.uid},
					cache: false,
					dataType: "json",
					success: function (a) {
						if (a.s == "y") {
							media_dir(main.get(0),mediam_dir);
						}
					}
				})
			},
			media_delete = function() {
				//Richesta eliminazione
				main = $(this).closest('.media-manager');
				media_selected = main.data('media_selected');
				mediam_dir = main.data('cur_dir');
				if (confirm(l__del)) {
					elabq = "";
					for (i = 0; i < media_selected.length; i++) elabq += "&f[]=" + media_selected[i]["n"] + "&d[]=" + media_selected[i]["t"];
					$.ajax({
						url: __media_man_base_path+"zone_media_man.html?act=del&uid="+media_params.uid + elabq,
						success: function () {
							media_dir(main.get(0),mediam_dir);
						}
					})
				}	
			},
			media_man_diag_abort = function() {
				if (typeof $('').niiwin == 'function')
					$(this).niiwin('close');
				else
					$(this).dialog("close");
			};
		return {
			init : function() {
				var media_manager_div = $("<div></div>").addClass('media-manager-main').attr({'title':'Media Manager'}).appendTo('body');
				$(window).keydown(function(evt) {
					if (evt.which == 17)
						ctrlPressed = true;
				}).keyup(function(evt) {
					if (evt.which == 17)
						ctrlPressed = false;
				});
				$(function() {
					media_manager_div.append(
						$('<div></div>').addClass('media-manager')
							.append($('<div></div>').addClass('media_tips').append($('<p></p>').addClass('validateTips')))
							.append($('<div></div>').addClass('media_content')
								.append($('<div></div>').addClass('media_content_sub')
									.append($('<div></div>').addClass('media_files')
										.append($('<div></div>').addClass('media_files_sub')
											.append($('<div></div>').addClass('media_file_toolbar')
												.append($('<div></div>').addClass('media_file_toolbar_sub')
													.append($('<a></a>').addClass('imgupdirbig media_updir'))
													.append($('<input/>').hide().attr({type:'file',multiple:'true'}))
													.append($('<a></a>').addClass('imguploadbig media_upload'))
													.append($('<a></a>').addClass('imgdelbig imgdelbig_in media_del'))
													.append($('<a></a>').addClass('imgnfbig media_nf'))))
											.append($('<div></div>').addClass('media_file_list')
												.append($('<div></div>').addClass('media_file_list_sub prev')))))
									.append($('<div></div>').addClass('media_info').hide()
										.append($('<p></p>').addClass('fileName'))
										.append($('<p></p>').addClass('fileSize'))
										.append($('<p></p>').addClass('fileType'))
										.append($('<p></p>').addClass('progressNumber'))))));
					butt = {};
					butt[l__ok] = media_man_diag_ok;
					butt[l__abort] = media_man_diag_abort;
					if (typeof $('').niiwin == 'function')
						media_manager_div.niiwin({
							width:640,
							height:480,
							modal: true,
							buttons: butt,
							onShow : media_man_diag_open
						});
					else
						media_manager_div.dialog({
							autoOpen: false,
							width:800,
							height:500,
							modal: true,
							buttons: butt,
							open : media_man_diag_open
						});
						
						
					var dropzone = media_manager_div.find('.media_file_list_sub');
					dropzone.on('dragover', function() {
					   //add hover class when drag over
					   media_params = $(this).closest('.media-manager').data('params');
					   if (media_params.upload)
							$(this).addClass('media_hover');
					   return false;
					});
					dropzone.on('dragleave', function() {
					   //remove hover class when drag out
					   $(this).removeClass('media_hover');
					   return false;
					});
					dropzone.on('drop', function(e) {
					   //prevent browser from open the file when drop off
					   e.stopPropagation();
					   e.preventDefault();
					   $(this).removeClass('media_hover');
					   media_params = $(this).closest('.media-manager').data('params');
					   //retrieve uploaded files data
					   if (media_params.upload) {
						   var files = e.originalEvent.dataTransfer.files;
						   processFiles(files,this);
					   }
					   return false;
					});
					media_manager_div.find('.media_updir').click(media_dir_up);
					media_manager_div.find('.media_upload').click(media_upload);
					media_manager_div.find('.media_del').click(media_delete);
					media_manager_div.find('.media_nf').click(media_newfolder);
					media_manager_div.find('.media_file_toolbar_sub input').on('change', function() {
					   var files = $(this)[0].files;
					   processFiles(files,$(this).closest('.media_files_sub').find('.media_file_list_sub').get(0));
					   return false;
					});
				});
				return media_manager_div;
			},
			call : function(params) {
				return this.each(function () {
					media_data_zone = $(this).find('.media-manager');
					if (params==null) {
						media_params = def;
					} else {						
						if (typeof params == 'string') {
							media_params = {};
							media_params.uid = params;
						} else
							media_params = $.extend({}, def, params||{});
						if (media_params.uid != -1) {
							//Ricevo i parametri via ajax
							$.ajax({
								url : __media_man_base_path+'zone_media_man.html',
								data : {act : 'perms', uid : media_params.uid},
								dataType : 'json',
								success : function (d){
									dxnan = d;
									media_params.multiple = (typeof params.multiple == 'undefined')?d.multiple:params.multiple;
									media_params.upload = (typeof params.upload == 'undefined')?d.upload:params.upload;
									media_params.del = (typeof params.del == 'undefined')?d.del:params.del;
									media_params.extensions = params.extensions || d.extensions;
									media_params.dir = params.dir || d.dir;
									media_params.show_folder = (typeof params.show_folder == 'undefined')?d.show_folder:params.show_folder;
									media_params.show_files = (typeof params.show_files == 'undefined')?d.show_files:params.show_files;
									media_params.navigable = params.navigable || d.navigable;
									media_data_zone.data({'params':media_params});
								}
							});
						}
					}
					media_data_zone.data({'params':media_params,'dir':media_params.dir});
					set_media_dir(media_data_zone.get(0));
					if (typeof $('').niiwin == 'function')
						$(this).niiwin('open');
					else
						$(this).dialog('open').dialog("widget").css('z-index',10100);
				});
			}
		}
	}();
	$.fn.extend({
		mediaMan_init : mediaMan.init,
		mediaMan : mediaMan.call
	})
})(jQuery);
var media_manager_div;
var ctrlPressed = false;
media_manager_div = $("").mediaMan_init();
function media_manager(params) {
	media_manager_div.mediaMan(params);
}