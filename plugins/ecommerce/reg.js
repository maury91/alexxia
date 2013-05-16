$(function() {
	$('.nc_ditta').css('clear','both').hide();
	$('select[name="nc_type"]').change(function(){
		switch($(this).val()) {
			case '0' :
				$('.nc_ditta').slideUp(500).find('input').removeClass('required');
			break;
			case '99' :
				$('.nc_ditta').slideDown(500).find('input').addClass('required');
				if ($('input[name="nc_nation"]').val()=='NE')
					$('input[name="nc_piva"]').removeClass('required')
			break;
		}
	});
	$('select[name="nc_nation"]').change(function() {
		if ($(this).val()=='NE')
			$('input[name="nc_piva"]').removeClass('required');
		else {
			if ($(this).hasClass('required'))
				$('input[name="nc_piva"]').addClass('required');
			$('input[name="nc_piva"]').val($(this).val());
			if ($(this).val()=='AT')
				$('input[name="nc_piva"]').val('ATU');
			$('input[name="nc_piva"]').trigger('input');
		}
	}).trigger('change');
	$('input[name="nc_soc"]').bind('input',function(){
		if ($(this).val().length<3)
			$(this).removeClass('ok').addClass('error').next('.info').html(__nc_soc_short);
		else
			$(this).removeClass('error').addClass('ok').next('.info').html('');
	});
	$('input[name="nc_piva"]').bind('input',function(){
		if ($('select[name="nc_nation"]').val()=='NE')
			$(this).removeClass('ok').removeClass('error').next('.info').html('');
		else {
			var newVATNumber = checkVATNumber($(this).val());
			if (newVATNumber) {
				$(this).val(newVATNumber);
				$(this).removeClass('error').addClass('ok').next('.info').html('');
			}  
			else 
				$(this).removeClass('ok').addClass('error').next('.info').html(__nc_piva_err);
		}
	});
})