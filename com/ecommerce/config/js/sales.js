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
$(function () {
	$('#salesStart, #salesEnd').datepicker({
		showOn: "both",
		beforeShow: customRange,
		dateFormat: "dd/mm/yy",
		firstDay: 1, 
		changeFirstDay: false
	});
	$('#save_sale').click(function() {
		//Controllo date e sconto
		sale = parseFloat($('#sale').text());
		if (isNaN(sale)||sale<=0)
			return alert('Scrivi uno sconto valido');
		if (($("#salesStart").datepicker("getDate") === null)||($("#salesEnd").datepicker("getDate") === null))
			return alert('Scrivi delle date valide');
		config_load_page('ecommerce/config/products.php','components',{new_offer : {id : sal_id,sale : sale, start : $.datepicker.formatDate('yy-mm-ddT00:00:00.000Z',$("#salesStart").datepicker("getDate")),end : $.datepicker.formatDate('yy-mm-ddT00:00:00.000Z',$("#salesEnd").datepicker("getDate"))},prod : prods});
	})
});