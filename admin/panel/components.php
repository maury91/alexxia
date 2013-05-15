<?php
/**
 *	Components Management for ALExxia
 *	
 *	Copyright (c) 2013 Maurizio Carboni. All rights reserved.
 *
 *	This file is part of ALExxia.
 *	
 *	ALExxia is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *	
 *	ALExxia is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with ALExxia.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     alexxia
 * @author      Maurizio Carboni <maury91@gmail.com>
 * @copyright   2013 Maurizio Carboni
 * @license     http://www.gnu.org/licenses/  GNU General Public License
**/
if (isset($external['config'])) {
	$content = array('html' => '','ext' => $external,'js' => array(),'css' => array());
	if (file_exists(__base_path.'com/'.$external['config'])) {
		SCRIPT::base_path(__http_host.__http_path.'com/');
		SCRIPT::rel_path(dirname($external['config']).'/');
		ob_start();
		include(__base_path.'com/'.$external['config']);
		$content['html'] .= ob_get_contents();
		$content['js'] = array_merge($content['js'],SCRIPT::get());
		$content['css'] = array_merge($content['css'],STYLE::get());
		ob_end_clean();
	} else
		$content['html'] = 'Error!';
	$content['html'] = base64_encode($content['html']);
} else {
	if (!file_exists(__base_path.'admin/cache/coms.php')) {
		//Creazione cache
		//Lista cartelle
		$coms = FUNCTIONS::list_dir(__base_path.'com/');
		$com_conf=array();
		foreach ($coms as $c) {
			if (file_exists(__base_path.'com/'.$c.'/config.json')) {
				$com_data = json_decode(file_get_contents(__base_path.'com/'.$c.'/config.json'),true);
				if($com_data!=null) {
					foreach ($com_data as $info) {
						$info['page'] = $c.'/config/'.$info['page'].'.php';
						$info['icon'] = $c.'/config/'.$info['icon'];
						$com_conf[] = $info;
					}
				}
			}
		}
		file_put_contents(__base_path.'admin/cache/coms.php','<?php $com_conf = '.var_export($com_conf,true).'; ?>');
	} else
		include(__base_path.'admin/cache/coms.php');
	$content=array();
	foreach ($com_conf as $v) 
		$content[] = $v;
}
?>