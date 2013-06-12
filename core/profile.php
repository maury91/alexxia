<?php
if (GET::val('user') != 'me') {

} elseif (USER::logged()) {
	echo '<h3>Profilo di '.USER::data('nick').'</h3>';
	echo '<div class="data">
	<div class="photo">
		<span class="img edit"></span>
	</div>
	<div class="info">
		<span class="">'.USER::data('name').' '.USER::data('lastname').'</span>';
	foreach (PLUGINS::in('core','profile','add_fields') as $p) include($p);
	echo '</div>
</div>';
} else echo 'Non sei loggato';
?>