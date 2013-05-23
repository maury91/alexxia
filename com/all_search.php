<?php
//Safe input
$q = strip_tags($_GET['q']);
$results = array();
//Include lang
include(__base_path.'com/all_search/'.LANG::short().'.php');
HTML::add_style('com/all_search/css/style.css');
//Include plugins
foreach (PLUGINS::in('com','all_search','search_data') as $p) include($p);
//Show results
if (empty($results)) 
	echo '<div class="founded">'.sprintf($__no_results,$q).'</div>';
else {
	//List off all results
	echo '<div class="founded">'.sprintf($__results,count($results)).'</div><ul class="all_search">';
	foreach ($results as $v) {
		echo '<li class="result"><a href="'.$v['url'].'"><span class="image" style="background-image:url('.$v['image'].')"></span><h2 class="title">'.$v['name'].'</h2></a></li>';
	}
	echo '</ul>';
}
?>