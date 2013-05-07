function captcha_click(recall) {
	$('.ale_captcha').click(function(e) {
		var c_data = {x : e.offsetX,y: e.offsetY,id :$(this).attr('id')};
		recall(c_data);
	});
}
function captcha_error(c_id) {
	$.ajax({
		url : 'captcha.php',
		data : {'new' : c_id},
		dataType : 'json',
		success : function(d) {
			$('#t'+c_id).html(d.txt);
			rnd = new Date().getTime();
			$('#'+c_id).css('background-image',$('#'+c_id).css('background-image'));
		}
	})
}
