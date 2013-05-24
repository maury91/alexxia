$(function(){
	$('.address_data .info').hide();
	$('.address_data input').each(function() {
		if ($(this).attr('title') != undefined) {
			var v = $(this).attr('title');
			$(this).data('def',v).val(v).css('color','#999').blur(function(){
				if(($(this).val() == '')&&($(this).attr('id')!='address2'))
					$(this).val($(this).data('def')).css('color','#999');
			}).focus(function() {
				if($(this).val() == $(this).data('def'))
					$(this).val('').css('color','');
			});
		}
	}).focus(function(){
		$(this).next('.info').slideUp(400);
	});
	$('#cart_next').button({
        icons: {
            primary: '', 
            secondary: 'ui-icon-triangle-1-e'
        }
    }).click(function() {
    	//Passa alla prossima fase, animazione carrello
    	//Check data
    	$('.address_data input').each(function() {
    		if($(this).val() == $(this).data('def'))
				$(this).val('').css('color','');
    	});
    	var test = $('#fname').val().match(/([a-zA-Z]*) ([a-zA-Z]*)/);
		if ((test==null)||(test.length<3))
			return $('#fname').next('.info').html(__invalid_fname).slideDown(400);
		if (('#telephone').val().match(/^(\+\d*)? ?([\d -]*)$/)==null)
			return $('#telephone').next('.info').html(__invalid_telephone).slideDown(400);
		
    	$('.minicart').animate({'left':'25%'});
    })
})