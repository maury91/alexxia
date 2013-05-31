<?php
/**
 *	Ecommerce Component for ALExxia
 *	This component is only for didactical use
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
define('CURRENCY','&euro;');
//Ritorna il tipo di utente
function get_user_type() {
	if (USER::logged()) 
		return (USER::data('nc_cat')==0)? 1 : intval(USER::data('nc_cat'));
	else
		return 1;
}
include(__base_path.'com/ecommerce/lang/'.LANG::short().'.php');
if (GET::exists('install'))
	include('ecommerce/install.php');
elseif (isset($_CRIPTED)) {
	//Parte sicura
	include(__base_path.'com/ecommerce/modules/secure_zone.php');
} elseif (GET::exists('pay')) {
	$q = DB::select('*','nc__orders',' WHERE id = ',GET::val('pay'));
	if ($q&&($data = DB::assoc($q))) {
		$invoice = $data['id'];
		$js = $css = array();
		$cart=array();
		$prods = DB::simple_select(
			array(
				array('nc__products','*'),
				array('nc__translates','name'),
				array('NxN__quantity_nc__ordersxnc__products','quantity','tot'),
				array('nc__images','url','image'),
				array('nc__prices','price','price','MIN')
				),
			array('nc__products','nc__translates','NxN__quantity_nc__ordersxnc__products','nc__images','nc__prices'),
			array('WHERE' =>
				array(
					array('id','=','nc__products_ref','nc__products','nc__translates'),
					'AND',
					array('id','=','nc__products_ref','nc__products','nc__images'),
					'AND',
					array('id','=','nc__products','nc__products','NxN__quantity_nc__ordersxnc__products'),
					'AND',
					array('nc__orders','=',$data['id'],'NxN__quantity_nc__ordersxnc__products'),
					'AND',
					array('nc__categoriesU_ref','=',get_user_type()),
					'AND',
					array('nc__products_ref','=','id','nc__prices','nc__products'),
					'AND',
					array('q_min','<=','quantity','nc__prices','NxN__quantity_nc__ordersxnc__products'),
					'AND',
					array('lang','=',LANG::short(),'nc__translates')
					),
				'GROUP' => 'id'));
		$payment=DB::assoc(DB::select('*','nc__payments',' WHERE id = ',$data['nc__payments_ref']));
		while ($prod=DB::assoc($prods))
			$cart[$prod['id']] = $prod;
		include(__base_path.'com/ecommerce/payments/'.$payment['UNI_ID'].'/payment.php');
		echo $html;
		foreach($js as $v)
			HTML::add_script($v);
		foreach($css as $v)
			HTML::add_style($v);
	}
	/*if ((strpos(GET::val('pay_methods'),'..') === false)&&(file_exists(__base_path.'com/ecommerce/payments/'.GET::val('pay_methods').'/callback.php')))
	
	exit(0);*/
}  elseif (GET::exists('pay_methods')) {
	if ((strpos(GET::val('pay_methods'),'..') === false)&&(file_exists(__base_path.'com/ecommerce/payments/'.GET::val('pay_methods').'/callback.php')))
	include(__base_path.'com/ecommerce/payments/'.GET::val('pay_methods').'/callback.php');
	exit(0);
} elseif (GET::exists('show_cats')) {
	HTML::add_style('com/ecommerce/css/cats.css');
	$cat = DB::select(DB::$pre.'nc__translatesC.name,'.DB::$pre.'nc__categories.*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref IS NULL AND lang = ',LANG::short());
	while($c = DB::assoc($cat)) {
		echo '<ul class="cats"><li class="title"><a href="'.__http.'com/ecommerce/category/'.$c['id'].'-'.$c['name'].'.html">'.$c['name'].'</a></li>';
		$subcat = DB::select('*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref = ',$c['id'],' AND lang = ',LANG::short());
		while ($d = DB::assoc($subcat))
			echo '<li><a href="'.__http.'com/ecommerce/category/'.$d['id'].'-'.$d['name'].'.html">'.$d['name'].'</a></li>';
		echo '</ul>';
	}
} elseif (GET::exists('category')) {
	$u_type=get_user_type();
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE  `nc__categories_ref` IN ((SELECT id FROM  `'.(DB::$pre).'nc__categories`  WHERE  `nc__categories_ref` = ',GET::val('category'),'),',GET::val('category'),')  AND '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id');
	echo '<ol class="list">';
	while ($pr = DB::assoc($prods)) {
		echo '<a href="'.__http.'com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')"><div class="stars">';
		for ($i=0;$i<intval($pr['stars']);$i++)
			echo '<span class="on">';
		for ($i=intval($pr['stars']);$i<5;$i++)
			echo '<span class="off">';
		$desc = substr(strip_tags($pr['descrizione']),0,40);
		$desc = substr($desc,0,strrpos($desc,' '));
		echo '</div></div><div class="desc">'.$desc.'</div><span class="price">'.$pr['price'].' '.CURRENCY.'</span></li></a>';
	}
	echo '</ol>';
} elseif (GET::exists('show')) 
	include(__base_path.'com/ecommerce/modules/show.php');
else 
	include(__base_path.'com/ecommerce/modules/cart.php');
?>