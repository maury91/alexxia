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
	})

})