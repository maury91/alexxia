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

//Impedisce di selezionare una data di inizio maggiore di una data di fine e viceversa
function customRange(input) { 
	var dateMin = null;
	var dateMax = null;   
	if ((input.id == "salesStart")&&($("#salesEnd").datepicker("getDate") !== null))
		dateMax = $("#salesEnd").datepicker("getDate");
	else if ((input.id == "salesEnd")&&($("#salesStart").datepicker("getDate") !== null))
			dateMin = $("#salesStart").datepicker("getDate");
	return {
		minDate: dateMin, 
		maxDate: dateMax
	};
}
//Inizializzazione
$(function () {
	//Inizialzzazione calendari
	$('#salesStart, #salesEnd').datepicker({
		showOn: "both",
		beforeShow: customRange,
		dateFormat: "dd/mm/yy",
		firstDay: 1, 
		changeFirstDay: false
	});
	//Salvataggio
	$('#save_sale').click(function() {
		//Controllo date e sconto
		sale = parseFloat($('#sale').text());
		if (isNaN(sale)||sale<=0)
			return alert('Scrivi uno sconto valido');
		if (($("#salesStart").datepicker("getDate") === null)||($("#salesEnd").datepicker("getDate") === null))
			return alert('Scrivi delle date valide');
		//Carico la pagina
		//config_load_page(@filename,@type[optional],@params[optional]);
		config_load_page('ecommerce/config/products.php','components',{new_offer : {id : sal_id,sale : sale, start : $.datepicker.formatDate('yy-mm-ddT00:00:00.000Z',$("#salesStart").datepicker("getDate")),end : $.datepicker.formatDate('yy-mm-ddT00:00:00.000Z',$("#salesEnd").datepicker("getDate"))},prod : prods});
	})
});