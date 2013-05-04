$(function() {
	//Costruzione prezzi
	prices = [];
	$('.price').each(function() {
		if ($(this).find('.price_n').length)
			prices.push({price : parseFloat($(this).find('.price_n').html()),q : $(this).next('.price_q').html()})
	});
	for (i in prices) {
		if (prices[i].q[prices[i].q.length-1]=='+')
			prices[i].q = {min:parseInt(prices[i].q.substr(0,prices[i].q.length-1)),max:0}
		else{
			mm = prices[i].q.split('-');
			prices[i].q = {min:parseInt(mm[0]),max:parseInt(mm[1])}
		}
	}
	$('#quantity').on('input',function() {
		if (!isNaN(parseInt($(this).val()))) {
			//Controllo range
			for (i in prices) {
				if (prices[i].q.min<=parseInt($(this).val()))
					$('.price_tot').html(prices[i].price*parseInt($(this).val()));
			}
		}
	});
	$('.images .image').css('background-image',$('.thumbs .thumb:first').css('background-image'));
	$('.thumbs .thumb').mouseenter(function() {
		$('.images .image').css('background-image',$(this).css('background-image'));
	});
})