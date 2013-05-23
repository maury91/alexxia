$(function(){
	$('#cart_next').button({
        icons: {
            primary: '', 
            secondary: 'ui-icon-triangle-1-e'
        }
    })
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
								$(this).fadeOut(4000,function() {
									if (price_tot==0) {
										$('.fp_cart h2').after($('<p></p>').html(__cart_empt)).after($('<h3></h3>').html(__cart_emp));
										$('.fp_cart_tot').fadeOut(400);
									}
								})
							})).remove();
						});
						delete cart[id];
						price_tot=0;
						for (i in cart) 
							price_tot += parseFloat(cart[i].price)*cart[i].tot;
						$('#fp_cart_tot').text(price_tot);
					}
				}
			})
		});
	});
	$('.fp_cart_q').each(function() {		
		$(this).data('def',$(this).val()).next('.fp_update').click(function() {
			id = $(this).closest('.fp_cart_prod').attr('id');
			that=this;
			q = $(this).prev('.fp_cart_q').val();
			$.ajax({
				url : __http_base+'com_ecommerce.html',
				data : {cart_edit:id,q : q},
				dataType : 'json',
				success : function(d) {
					if (d.r=='y'){
						cart[id].tot = parseInt(q);
						cart[id].price = d.data.price;
						var cart_q=$(that).hide().prev('.fp_cart_q');
						cart_q.data('def',cart_q.val());
						$(that).parent().find('.fp_cart_price').html(d.data.price+' &euro;');
						price_tot=0;
						for (i in cart) 
							price_tot += parseFloat(cart[i].price)*cart[i].tot;
						$('#fp_cart_tot').text(price_tot);
					}


				}
			});
		}).hide();
	}).bind('input',function() {
		var q=$(this).val();
		if (($(this).data('def')!=q)&&(parseInt(q)==q)&&(parseInt(q)>0))
			$(this).next('.fp_update').show();
		else
			$(this).next('.fp_update').hide();
	})
});