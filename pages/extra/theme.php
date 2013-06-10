<!DOCTYPE html>
<html>
	<head>
		<?php echo HTML::get_head(); ?>
		<style type="text/css">
			@import url(http://fonts.googleapis.com/css?family=Lobster);
			body {
				background:url('media/offline/back.jpg') repeat-x #1f1f1f;
				text-align:center;
				color:white;
				overflow: hidden;
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
		<img src="media/offline/underconstruction.png" alt="in costruzione"/>
	</body>
</html>