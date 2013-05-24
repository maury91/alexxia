/**
 *	Ecommerce Component for ALExxia
 *	This component is only for didactical use
 *	You can't use this component for commercial purpuose without authorization from the authors
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
var p_cur_lang = 'it';
var p_data = {};
var langs = [];
var prod_id = -1;
product_images=[];

function choose_img(a) {
	for (i in a) {
		//inserisci immagine a[i].n
		id = product_images.push(a[i].n);
		$('.images .thumbs').append($('<p></p>').data('id',id-1).addClass('thumb').css('background-image','url('+a[i].n+')').mouseenter(function() {
			$('.images .image').css('background-image',$(this).css('background-image'));
		}).append($('<span></span>').addClass('del').text('x').click(function(){
			product_images.splice($(this).closest('.thumb').data('id'),1);
			$(this).closest('.thumb').fadeOut(500,function(){$(this).remove()});
		})));
		$('.images .thumbs .thumb:last').trigger('mouseenter');
	}
}
function product_get_all_data() {
	return {name : $('#prod_name').html(),description : $('#prod_details').html()};
}
function save_product(save_new) {
	p_data[p_cur_lang] = product_get_all_data();
	prices = {};
	$('.price_group').each(function(i,el) {
		that_cat=$(this).attr('id').slice(3,$(this).attr('id').length);
		prices[that_cat] = [];
		$(this).find('.n_price').each(function(i,el) {
			prices[that_cat].push({price : $(el).html(), quantity : $(el).parent().next('.price_q').html()});
		});
	
	});
	cats = [];
	$('.cat').each(function(i,el) {
		if ($(el).val() != null)
			cats.push($(el).val());
	});
	to_save = {prices : prices,conf_regalo : {enable : $('#pacco_regalo').is(':checked'),price : $('#regalo_p').html()},peso : $('#peso').html(),cats : cats,images : product_images,marc : $('.marc').val(),langs:p_data,dimensions : {'w' : $('#dimW').html(),'h' : $('#dimH').html(),'l' : $('#dimL').html()},duration : $('#duration').html()};
	//COntrolli
	for (i in prices)
		for (j in prices[i])
			if (parseFloat(prices[i][j].price)<=0)
				return alert('Controlla i prezzi inseriti, i prezzi devono essere maggiori di 0');
	if ($('.marc').val()==null)
		return alert('Seleziona una marca');
	if (cats.length<1)
		return alert('Seleziona almeno una categoria');
	if (product_images.length<1)
		return alert('Seleziona almeno un\'immagine');
	if (parseFloat($('#peso').html())<=0)
		return alert('Il peso deve essere maggiore di 0');
	var data_to_save = {add_prod : to_save};
	if ((prod_id!=-1)&&(save_new != true))
		data_to_save['edit'] = prod_id;	
	ajax_request({
		type : 'component',
		data : data_to_save,
		url : 'ecommerce/config/products.php',
		success : function(d) {
			if (d.content.r == 'y') {
				alert('Prodotto salvato');
				prod_id = d.content.id;
				$('.special_save').show();
				$('.normal_save').hide();
			} else
				alert('Errore : '+d.content.err);
		}
	});
}
$(function() {
	CKEDITOR.disableAutoInline = true;
	CKEDITOR.on( 'instanceCreated', function( event ) {
		var editor = event.editor,
			element = editor.element;
		if ( element.is( 'h1', 'h2', 'h3', 'span' ) || element.getAttribute( 'id' ) == 'taglist' ) {
			editor.on( 'configLoaded', function() {
				editor.config.removePlugins = 'colorbutton,find,flash,font,' +
					'forms,iframe,image,newpage,removeformat,' +
					'smiley,specialchar,stylescombo,templates,link,unlink,anchor';
				editor.config.toolbarGroups = [
					{ name: 'editing',		groups: [ 'basicstyles' ] },
					{ name: 'undo' },
					{ name: 'clipboard',	groups: [ 'selection', 'clipboard' ] }
				];
			});
		} else {
			editor.on( 'configLoaded', function() {
				editor.config.removePlugins = 'about';
			});
		}	
	});
	CKEDITOR.inline( document.getElementById( 'prod_name' ) );
	CKEDITOR.inline( document.getElementById( 'prod_details' ) );
	
	$('.abutton').button();
	price_change = function() {
		if ($(this).closest('p').hasClass('secondary')&&isFinite($('.n_price:first').html())&&parseInt($('.n_price:first').html())&&isFinite($(this).html()))
			$(this).parent().next().next('.percentual').text(Math.round((100-parseFloat($(this).html())/parseFloat($('.n_price:first').html())*100)*100)/100);
	}
	inverse_price_change = function() {
		$('.secondary .n_price').each(function(i,el) {
			if (isFinite($('.n_price:first').html())&&parseInt($(this).closest('p').find('.n_price:first').html())&&isFinite($(this).html()))
				$(this).parent().next().next('.percentual').text(Math.round((100-parseFloat($(this).html())/parseFloat($(this).closest('p').find('.n_price:first').html())*100)*100)/100);
		});
	}
	percenutal_price_change = function() {
		if (isFinite($(this).closest('p').find('.n_price:first').html())&&parseInt($(this).closest('p').find('.n_price:first').html())&&isFinite($(this).html()))
			$(this).prev().prev('.price').find('.n_price').text(Math.round(parseFloat($(this).closest('p').find('.n_price:first').html())*100*(1-parseFloat($(this).html())/100))/100);
	}
	$('.price .n_price').on('input',inverse_price_change);
	del_price_f = function() {
		$(this).closest('p').slideUp(600,function(){$(this).remove()});
	}
	add_price_f = function() {
		$(this).before($('<p></p>').addClass('secondary').html($('<span></span>').addClass('price').html('&euro; ').append($('<span></span>').attr('contenteditable','true').on('input',price_change).addClass('n_price').text('0.0'))).append('/').append($('<span></span>').attr('contenteditable','true').addClass('price_q').text('1-2')).append(' pezzi (-').append($('<span></span>').addClass('percentual').on('input',percenutal_price_change).text('100').attr('contenteditable','true')).append('%)').append($('<a></a>').addClass('img del').click(del_price_f)));
	};
	$('.add_price').click(add_price_f);
	cat_change = function() {
		that=this;
		if($(this).val()=='add') {
			cat_names={};
			for (i in langs) {
				cat_names[langs[i]] = prompt('Nome Categoria (in '+langs[i]+') : ','nuova categoria');
				if (!cat_names[langs[i]])
					return false;
			}
			ajax_request({
				type : 'component',
				data : {new_cat : cat_names},
				url : 'ecommerce/config/products.php',
				success : function(d) {
					if (d.content.r == 'y') {
						$('.cat option[value="add"]').before($('<option></option>').val(d.content.id).text(d.content.name['it']));
						$(that).val(d.content.id);
					}
				}
			});
		}
	};
	$('.cat').on('change',cat_change);
	$('.marc').bind('change',function() {
		that=this;
		if($(this).val()=='add') {
			marc_name = prompt('Nome Marca  : ','nuova marca');
			if (marc_name)
				ajax_request({
					type : 'component',
					data : {new_marc : marc_name},
					url : 'ecommerce/config/products.php',
					success : function(d) {
						if (d.content.r == 'y') {
							$('.marc option[value="add"]').before($('<option></option>').val(d.content.id).text(d.content.name));
							$(that).val(d.content.id);
						}
					}
				});
		}
	});
	$('#add_cat').click(function() {
		$('.cat:first').clone().insertBefore($('#add_cat').parent());
		$('.cat').on('change',cat_change);
	});
	$('#prod_save,#prod_save_over').click(function(){save_product(false);});
	$('#prod_save_new').click(function(){save_product(true);});
	$('.special_save').hide();
	$('#price_accordion').accordion();
	$('#add_t_price').click(function() {
		a = prompt('Nome Tipologia Utenti : ','Esempio : (clienti) fedeli');
		if (a) {
			ajax_request({
				type : 'component',
				data : {new_catU : a},
				url : 'ecommerce/config/products.php',
				success : function(d) {
					if (d.content.r == 'y') {
						$('#add_t_price').before($('<h3></h3>').text('Prezzo Utente '+d.content.name)).before(
							$('<div></div>').append(
								$('<div></div>').addClass('left').text('Prezzo')).append(
								$('<div></div>').addClass('right').addClass('.price_group').attr('id','pc_'+d.content.id).append(
									$('<p></p>').append($('<span></span>').addClass('price').html('&euro; ').append($('<span></span>').attr('contenteditable','true').addClass('n_price').text('0.0'))).append('/').append($('<span></span>').attr('contenteditable','true').addClass('price_q').text('1-2')).append(' pezzi')).append(
									$('<p></p>').append($('<a></a>').text('Aggiungi').addClass('abutton add_price').click(add_price_f).button()))));
						$('#price_accordion').accordion('destroy').accordion({active:$('#price_accordion h3').length-2});
					}
				}
			});
		
			
		}
	});
	$('.img.flag').each(function(i,el){
		langs.push($(this).attr('title'));
		$(this).data('lang',$(this).attr('title')).removeAttr('title');
	}).click(function() {
		$('.img.flag').css('border-bottom','');
		$(this).css('border-bottom','2px solid #69F');
		p_data[p_cur_lang] = product_get_all_data();
		p_cur_lang=$(this).data('lang');
		if (p_data[p_cur_lang]) {
			$('#prod_name').html(p_data[p_cur_lang].name);
			$('#prod_details').html(p_data[p_cur_lang].description);
			//Caricamento dati
		} else {
			//Svuota tutti i campi
			$('#prod_name').html('Nome Prodotto ('+p_cur_lang+')');
			$('#prod_details').html('<p>Scrivi qui i dettagli del prodotto</p>');
		}
		
	})
});
function load_product() {
	prod_id = product_data.id;
	$('.special_save').show();
	$('.normal_save').hide();
	for (i in product_data.langs)
		p_data[product_data.langs[i].lang] = {name : product_data.langs[i].name, description : product_data.langs[i].descrizione};
	$('#prod_name').html(p_data[p_cur_lang].name);
	$('#prod_details').html(p_data[p_cur_lang].description);
	for (i in product_data.prices) {
		price_cat = $('#pc_'+i);
		for (j in product_data.prices[i]) {
			if (product_data.prices[i][j].q_max) 
				p_range = product_data.prices[i][j].q_min+'-'+product_data.prices[i][j].q_max;
			else
				p_range = product_data.prices[i][j].q_min+'+';
				
			if (isFinite($('.n_price:first').html())&&parseInt($('.n_price:first').html())&&isFinite($(this).html()))
			$(this).prev().prev('.price').find('.n_price').text();
			if (j>0) {
				price_cat.find('.add_price').before($('<p></p>').addClass('secondary').html($('<span></span>').addClass('price').html('&euro; ').append($('<span></span>').attr('contenteditable','true').on('input',price_change).addClass('n_price').text(product_data.prices[i][j].price))).append('/').append($('<span></span>').attr('contenteditable','true').addClass('price_q').text(p_range)).append(' pezzi (-').append($('<span></span>').addClass('percentual').on('input',percenutal_price_change).text(Math.round(product_data.prices[i][0].price*100*(1-product_data.prices[i][j].price/100))/100).attr('contenteditable','true')).append('%)').append($('<a></a>').addClass('img del').click(del_price_f)));
			} else 
				price_cat.find('.n_price').html(product_data.prices[i][j].price).end().find('.price_q').html(p_range);
		}
	}
	$('.cat').val(product_data.nc__categories_ref);
	$('.marc').val(product_data.nc__creators_ref);
	$('#peso').html(product_data.peso);
	for (i in product_data.images)
		choose_img([{n : product_data.images[i].url}])
	$('#dimW').html(product_data.dimension_W);
	$('#dimH').html(product_data.dimension_H);
	$('#dimL').html(product_data.dimension_L);
	$('#duration').html(product_data.duration);
}