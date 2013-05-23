function recipes_edit() {
	config_load_page('com/recipes/config/recipes.php?edit='+$(this).data('id'));
}
function recipes_del() {
	config_load_page('com/recipes/config/recipes.php?del='+$(this).data('id'));
}
$(function() {
	$('table .img').each(function() {
		id = $(this).closest('tr').find('td:first').text();
		$(this).data('id',id);
		if ($(this).hasClass('edit'))
			$(this).click(recipes_edit);
		else if ($(this).hasClass('del'))
			$(this).click(recipes_del);
	})

})