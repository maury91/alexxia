<?php
$__plugins = array(
	'core' => array(
		'index' => array(
			'begin' => array(),
			'before_page' => array(),
			'after_page' => array(),
			'template' => array(),
			'end' => array()
		),
		'reg' => array(
			'add_fields1' => array('ecommerce/reg_add1'),
			'add_fields2' => array('ecommerce/reg_add2'),
			'insert_data' => array('ecommerce/reg_ins')
		),
		'profile' => array(
			'add_fields' => array('ecommerce/show_fields')
		)
	),
	'com' => array(
		'all_search' => array(
			'search_data' => array('ecommerce/search','recipes/search')
		)
	)
);
?>