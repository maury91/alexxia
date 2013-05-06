function product_edit() {
	config_load_page('com/ecommerce/config/products.php?edit='+$(this).data('id'));
}
function product_del() {
	config_load_page('com/ecommerce/config/products.php?del='+$(this).data('id'));
}
function product_double() {
	config_load_page('com/ecommerce/config/products.php?double='+$(this).data('id'));
}
$(function() {
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
	$('table tbody tr').each(function() {
		id = $(this).find('td:first').text();
		$(this).find('td:first').append($('<input/>').attr('type','checkbox').data('id',id));
	});
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