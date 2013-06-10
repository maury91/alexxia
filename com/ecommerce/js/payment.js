$(function() {
	$('.payment_data li').click(function() {
    	console.log($(this).attr('id'));
    	secure_ajax({
			page : {com : 'ecommerce'},
			params : {
				payment : $(this).attr('id')},
			success : function(data) {
				console.log(data);
				$('h3.title').html(data.content.title);
				$('.payment_data').replaceWith(data.content.html);
				$('.minicart').animate({'left':'75%'},2000);
			}
		});
    });
})