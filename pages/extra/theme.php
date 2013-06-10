<!DOCTYPE html>
<html>
	<head>
		<?php echo HTML::get_head(); ?>
		<style type="text/css">
			@import url(http://fonts.googleapis.com/css?family=Lobster);
			body {
				background:url('media/images/back.jpg') repeat-x #1f1f1f;
				text-align:center;
			}
			h1 {
				font-family: 'Lobster', cursive;
				color:#DDD;
				font-size:70px;	
			}
			.logo .img{
				width:194px;
				height:176px;
				background:url('images/logo.png');
				display:inline-block;
			}
		</style>
	</head>
	<body>
		<?php echo HTML::get_body(); ?>	
		<?php echo $html; ?>
	</body>
</html>