$(function() {
	$('#cart_next').button({
        icons: {
            primary: '', 
            secondary: 'ui-icon-triangle-1-e'
        }
    }).click(function() {
    	;
    	secure_ajax({
			page : {com : 'ecommerce'},
			params : {
				shipment : $('.sped_mode:checked').val()},
			success : function(data) {
				console.log(data);
				$('.address_data').replaceWith(data.content.html);
				$('.minicart').animate({'left':'25%'});
			}
		});
    });
})