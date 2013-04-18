function page_loaded() {
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
}
function load_page() {
	if ($().secure('loaded')) {
		$.ajax({
			url : 'index.php',
			data : {crypted : $().secure('session'),data : $().secure('encrypt',JSON.stringify({load:'home'}, null, 2))},
			type : 'post',
			dataType : 'json',
			success : function (d) {
				//Decodifica
				data = $().secure('decrypt',d.cr);
				$('.main_content').html(data.error+data.content);
				page_loaded();
				if ((data.point>1)&&(data.point<5)) {
					$('#go_back').show();
					$('#go_next').attr('class','comb r next');
				} else {
					$('#go_back').hide();
					$('#go_next').attr('class','go');
				}
			}		
		});
	}
}
function next() {
	if ($().secure('loaded')) {
		$.ajax({
			url : 'index.php',
			data : {crypted : $().secure('session'),data : $().secure('encrypt',JSON.stringify($(":input").serializeArray(), null, 2))},
			type : 'post',
			dataType : 'json',
			success : function (d) {
				//Decodifica
				data = $().secure('decrypt',d.cr);
				$('.main_content').html(data.error+data.content);
				page_loaded();
				if ((data.point>1)&&(data.point<5)) {
					$('#go_back').show();
					$('#go_next').attr('class','comb r next');
				} else {
					$('#go_back').hide();
					$('#go_next').attr('class','go');
				}
			}			
		});
	}
}
function back() {
	if ($().secure('loaded')) {
		$.ajax({
			url : 'index.php',
			data : {crypted : $().secure('session'),data : $().secure('encrypt',JSON.stringify([{name:'back',value:' '}], null, 2))},
			type : 'post',
			dataType : 'json',
			success : function (d) {
				//Decodifica
				data = $().secure('decrypt',d.cr);
				$('.main_content').html(data.error+data.content);
				page_loaded();
				if ((data.point>1)&&(data.point<5)) {
					$('#go_back').show();
					$('#go_next').attr('class','comb r next');
				} else {
					$('#go_back').hide();
					$('#go_next').attr('class','go');
				}
			}			
		});
	}
}
$(function() {
	//Individuazione pagina
	$().secure_init(
		function(stat) {
			$(".secure_status .points").html("");
			for (i=0;i<4;i++) 
				$(".secure_status .points").append($("<span></span>").text(".").css({"margin-left":Math.cos((stat+i)*0.17-1.57)*30,"margin-top":Math.sin((stat+i)*0.17-1.57)*30}));
		},
		function() {
			$(".secure_status .points").html("");
			$(".secure_status .img.unsecure").removeClass("unsecure").addClass("secure");
			if (load_sec)
				load_page();
			else
				page_loaded();
			if (req_status!="error")
				$('header button.error').removeClass('error').addClass('go');
		}
	);
	$('#go_next').click(next);
	$('#go_back').click(back);
})