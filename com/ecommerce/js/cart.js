$(function(){
	$('.fp_cart_del').each(function() {
		$(this).removeAttr('href').click(function() {
			id = $(this).closest('.fp_cart_prod').attr('id');
			that=this;
			$.ajax({
				url : __http_base+'com_ecommerce.html',
				data : {cart_del:id},
				dataType : 'json',
				success : function(d) {
					if (d.r == 'y') {
						$(that).closest('.fp_cart_prod').slideUp(1000,function() {
							$(this).after($('<p></p>').addClass('information').html(__cart_removed).slideDown(300,function() {
								$(this).fadeOut(4000)
							})).remove();
						});
						delete cart[id];
						price_tot=0;
						for (i in cart) 
							price_tot += parseFloat(cart[i].price)*cart[i].tot;
						$('#fp_cart_tot').text(price_tot);
					}
			}})
		});
	})
});