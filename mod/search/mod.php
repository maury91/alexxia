<div class="cerca">
	<input id="all_search" type="text" />
</div>
<script type="text/javascript">
$(function() {
	$('#all_search').keydown(function(e) {
		if (e.which == 13) 
			location.href=__http_base+'com/all_search/q/'+encodeURIComponent($(e.target).val())+'.html';
	});
})
</script>