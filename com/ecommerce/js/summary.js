$(function() {
	$('#cart_end').button({
        icons: {
            primary: '', 
            secondary: 'ui-icon-triangle-1-e'
        }
    }).click(function() {
    	secure_ajax({
			page : {com : 'ecommerce'},
			params : {end :true},
			success : function(data) {
				console.log(data);
				$('h3.title').html(data.content.title);
				$('.summary_data').replaceWith(data.content.html);
				$('.minicart').animate({'left':'100%'},2000);
			}
		});
    })
})