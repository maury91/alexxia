$(function() {
	//Individuazione pagina
	if ($('input[name="site_name"]').length>0) {
		$('input[name="pass"]').bind('input',function() {
			if ($('input[name="pass"]').val().length<6)
				$('input[name="pass"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="pass"]').removeClass('error').addClass('selected ok');
			if ($('input[name="pass"]').val() != $('input[name="pass2"]').val())
				$('input[name="pass2"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="pass2"]').removeClass('error').addClass('selected ok');
		}).trigger('input');
		$('input[name="pass2"]').bind('input',function() {
			if ($('input[name="pass"]').val() != $('input[name="pass2"]').val())
				$('input[name="pass2"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="pass2"]').removeClass('error').addClass('selected ok');
		}).trigger('input');
		$('input[name="nick"]').bind('input',function() {
			if (($('input[name="nick"]').val().length<4)||(!$('input[name="nick"]').val().match(/^[a-z0-9]+$/i)))
				$('input[name="nick"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="nick"]').removeClass('error').addClass('selected ok');
		}).trigger('input');
		$('input[name="email"]').bind('input',function() {
			if(!$('input[name="email"]').val().match(/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i))
				$('input[name="email"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="email"]').removeClass('error').addClass('selected ok');
		}).trigger('input');
		$('input[name="site_name"]').bind('input',function() {
			if ($('input[name="site_name"]').val().length<6)
				$('input[name="site_name"]').removeClass('ok').addClass('selected error');
			else
				$('input[name="site_name"]').removeClass('error').addClass('selected ok');
		}).trigger('input');
	}
	if ($('select[name="dbt"]').length>0) {
		$('select[name="dbt"]').change(function() {
			switch ($('select[name="dbt"]').val()) {
				case 'mysql' :
				case 'mysqli' :
					$('.first.sql').slideDown(600);
					$('.first.lite').slideUp(600);
					break;
				case 'SQLite3' :
				case 'SQLite' :
					$('.first.sql').slideUp(600);
					$('.first.lite').slideDown(600);
					break;
			}
		});
		$('select[name="dbt2"]').change(function() {
			switch ($('select[name="dbt2"]').val()) {
				case 'mysql' :
				case 'mysqli' :
					$('.second.sql').slideDown(600);
					$('.second.lite').slideUp(600);
					break;
				case 'SQLite3' :
				case 'SQLite' :
					$('.second.sql').slideUp(600);
					$('.second.lite').slideDown(600);
					break;
			}
		});
	}
})