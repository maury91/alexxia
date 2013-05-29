$(function(){
	$('table :not(thead) tr').hover(function() {
		$(this).css({'background':'#EEE','cursor':'pointer'});
	},function() {
		$(this).css('background','#FFF');
	}).click(function() {
		that=this;
		if ($(this).hasClass('opened'))
			$(this).removeClass('opened').next('.ajax_row').remove().end().find('td:last').html('\\/');
		else {
			opened=$(this).find('td:first').text();
			ajax_request({
				url : 'ecommerce/config/orders.php',
				type : 'component',
				data : {view : opened},
				success : function (e) {
					$('<tr></tr>').addClass('ajax_row').append(
						$('<td></td>').attr('colspan',999).html(Base64.decode(e.content.html)))
					.insertAfter(that).find('.change_state').data('tr',that).data('conf',opened).change(function() {
						changer=this;
						ajax_request({
							url : 'ecommerce/config/orders.php',
							type : 'component',
							data : {change_state : {key : $(this).data('conf'), value : $(this).val()}},
							success : function(d) {
								console.log(d);
								console.log(Base64.decode(d.content.html));
								if (d.content.r=='n') 
									$(changer).val(d.value);
								else
									$($(changer).data('tr')).find('td:eq(5)').html($(changer).find('option:selected').html());
							}
						})
					});
					$(that).addClass('opened').find('td:last').html('/\\');

				}
			});
		}
	})
})