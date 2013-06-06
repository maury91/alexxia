<?php
$cat = DB::select(DB::$pre.'nc__translatesC.name,'.DB::$pre.'nc__categories.*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref IS NULL AND lang = ',LANG::short());
echo '<nav><ul>';
while($c = DB::assoc($cat))
	echo '<li><a href="'.__http.'com/ecommerce/category/'.$c['id'].'-'.$c['name'].'.html">'.$c['name'].'</a></li>';
echo '</ul></nav>';
?>