<?php
$nc_res = DB::simple_select(array(
		array('nc__translates','*'),
		array('nc__images','url')),
	array('nc__translates','nc__images'),
	array('WHERE' => 
		array(
			'(',
			array('descrizione','LIKE','%'.$q.'%'),
			'OR',
			array('name','LIKE','%'.$q.'%'),
			') AND',
			array('nc__products_ref','=','nc__products_ref','nc__translates','nc__images')
			),
		'GROUP' => 'nc__products_ref'));
while($nc_r = DB::assoc($nc_res))
	$results[] = array('name' => $nc_r['name'],'url' => __http.'com/ecommerce/show/'.$nc_r['nc__products_ref'].'-'.$nc_r['name'].'.html','image' => $nc_r['url']);
?>