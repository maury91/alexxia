$(function(){
	$('#cart_button').append(
		$('<div></div>').addClass('cart_content').append($('<a></a>').addClass('cart_loading')).hide()
	).hover(function() {
		that=this;
		$.ajax({
			url : __http_base+'com_ecommerce.html',
			data : {cart_json:''},
			dataType : 'json',
			success : function(d) {
				carth=$(that).find('.cart_content');
				added=0;
				carth.html('');
				price_tot=0;
				for (i in d) {
					if (parseInt(i)==i) {
						added++;
						price_tot+=parseFloat(d[i].price)*d[i].tot;
						carth.append($('<div></div>').addClass('cart_prod')
							.append($('<span></span>').addClass('cart_img').css('background-image','url('+d[i].img+')'))
							.append($('<span></span>').addClass('cart_name').text(d[i].name))
							.append($('<span></span>').addClass('cart_q').html(__cart_q+' : '+d[i].tot))
							.append($('<span></span>').addClass('cart_price').html(__cart_price+' : '+d[i].price+' &euro;'))
							.data('id',i)
						);
					}
				}
				if (added)
					carth.append($('<p></p>').addClass('cart_tot').html(__cart_tot_price+' : '+price_tot+' &euro;')).append($('<a></a>').text(__cart_go).button().css({'margin-bottom': 20,width: '90%'}).attr('href',__http_base+'com/ecommerce/cart.html'));
				else
					carth.html(__cart_empty);
			}
		})
		$(this).find('.cart_content').stop().fadeIn();
	},function() {
		$(this).find('.cart_content').stop().fadeOut();
	});

});