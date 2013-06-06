<?php
$cat = DB::select('*',array('nc__creators'));
echo '<nav><ul>';
while($c = DB::assoc($cat))
	echo '<li><a href="'.__http.'com/ecommerce/creator/'.$c['id'].'-'.$c['name'].'.html">'.$c['name'].'</a></li>';
echo '</ul></nav>';
?>