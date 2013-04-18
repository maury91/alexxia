<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="http://localhost//alexxia/template/mauro/style.css" />
		<?php echo HTML::get_head(); ?>
	</head>
	<body<?php echo HTML::get_body_tag(); ?>>
		<?php echo HTML::get_body(); ?>
		<div class="wrapper">
			<header>
				<span class="logo"><?php echo HTML::get_logo(); ?></span>
				<div class="cerca">
					<input type="text" />
				</div>
				<nav>
					<ul>
						<?php echo MENU::get('top') ?>
					</ul>
				</nav>
			</header>
			<div class="main">
				<div class="banner"><?php echo MENU::get('slider') ?></div>
				<div class="second_wrapper">
					<div class="page">
						<?php echo $html; ?>
					</div>
					<div class="menu_r">
						<?php echo MENU::get('right') ?>
					</div>
				</div>
			</div>
			<footer>
				<?php echo MENU::get('footer') ?>
			</footer>
		</div>
	</body>
</html>