<?php
HTML::add_style('css/profile.css');
if (GET::val('user') != 'me') {

} elseif (USER::logged()) {
	HTML::add_script('js/profile.js');
	echo '<h3>Profilo di '.USER::data('nick').'</h3>';
	echo '<div class="data">
	<div class="photo">
		<span class="img edit"></span>
	</div>
	<div class="info">
		<a id="change_user_info">Modifica</a>
		<span class="">'.USER::data('name').' '.USER::data('lastname').'</span>';
	foreach (PLUGINS::in('core','profile','add_fields') as $p) include($p);
	echo '
	</div>
	<a id="change_user_pass">Cambia Password</a>
</div>';
} else echo 'Non sei loggato';
?>