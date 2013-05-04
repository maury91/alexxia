<style type="text/css">
@import url("<?= __http_host.__http_path ?>mod/nivoslider/nivo-slider.css")
</style>
<div id="slider" class="nivoSlider">
	<?php
	$directory=__base_path.'media/images/slider/';
	if ($handle = opendir($directory.'/'))
		while ($file = readdir($handle))
			if (!is_dir($directory."/{$file}"))
				echo '<img src="'.__http_host.__http_path.'media/images/slider/'.$file.'" data-thumb="media/images/slider/'.$file.'" alt="" />';
	?>
</div>
<script type="text/javascript" src="<?= __http_host.__http_path ?>mod/nivoslider/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript">
$(window).load(function() {
	$('#slider').nivoSlider();
});
</script>