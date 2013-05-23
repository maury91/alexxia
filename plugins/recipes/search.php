<?php
$nc_res = DB::simple_select(array(
		array('nc__translatesR','*'),
		array('nc__r_images','url')),
	array('nc__translatesR','nc__r_images'),
	array('WHERE' => 
		array(
			'(',
			array('name','LIKE','%'.$q.'%'),
			'OR',
			array('ingredients','LIKE','%'.$q.'%'),
			'OR',
			array('preparation','LIKE','%'.$q.'%'),
			') AND',
			array('nc__recipes_ref','=','nc__recipes_ref','nc__translatesR','nc__r_images')
			),
		'GROUP' => 'nc__recipes_ref'));
while($nc_r = DB::assoc($nc_res))
	$results[] = array('name' => $nc_r['name'],'url' => __http.'com/recipes/show/'.$nc_r['nc__recipes_ref'].'-'.$nc_r['name'].'.html','image' => $nc_r['url']);
?>