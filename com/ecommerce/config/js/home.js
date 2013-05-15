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
function product_edit() {
	//Carico la pagina
	//config_load_page(@filename,@type[optional],@params[optional]);
	config_load_page('com/ecommerce/config/products.php?edit='+$(this).data('id'));
}
function product_del() {
	//Carico la pagina
	//config_load_page(@filename,@type[optional],@params[optional]);
	config_load_page('com/ecommerce/config/products.php?del='+$(this).data('id'));
}
function product_double() {
	//Carico la pagina
	//config_load_page(@filename,@type[optional],@params[optional]);
	config_load_page('com/ecommerce/config/products.php?double='+$(this).data('id'));
}
//Inizializzazione
$(function() {
	//Collegamento pulsanti
	$('table .img').each(function() {
		id = $(this).closest('tr').find('td:first').text();
		$(this).data('id',id);
		if ($(this).hasClass('edit'))
			$(this).click(product_edit);
		else if ($(this).hasClass('del'))
			$(this).click(product_del);
		else if ($(this).hasClass('double'))
			$(this).click(product_double);
	});
	//Assegnazione dati alle righe
	$('table tbody tr').each(function() {
		id = $(this).find('td:first').text();
		$(this).find('td:first').append($('<input/>').attr('type','checkbox').data('id',id));
	});
	//Collegamento pulsante offerta
	$('#offer_add').click(function() {
		checks = $('tbody td input[type="checkbox"]:checked');
		if (checks.length > 0) {
			check = [];
			checks.each(function() {check.push($(this).data('id'))});
			config_load_page('ecommerce/config/products.php?offer=','components',{prod : check});
		} else
			alert('Seleziona almeno un prodotto');
	})
})