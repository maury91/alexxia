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
else
if (GET::exists('cart_edit')) { //Modifica elementi dal carrello
	@session_start();
	$u_type = get_user_type();
	$prod = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,MIN(`'.(DB::$pre).'nc__prices`.`price`) as price',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE  `'.(DB::$pre).'nc__products`.`id` = ',GET::val('cart_edit'),'  AND '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<'.(GET::val('q')+1).' GROUP BY id');
	$prod_data = @DB::assoc($prod);
	if (isset($prod_data['id'])) {
		$_SESSION['nc_cart'][$prod_data['id']] = array('tot'=>intval(GET::val('q')),'price'=>$prod_data['price'],'img'=>$prod_data['image'],'name'=>$prod_data['name']);
		echo json_encode(array('r'=>'y','data'=>$_SESSION['nc_cart'][$prod_data['id']]));
	} else
		echo json_encode(array('r'=>'n'));
	exit(0);
} elseif (GET::exists('cart_del')) {	//Elimazione elementi dal carrello
	//Eliminazione in json
	@session_start();
	if (!isset($_SESSION['nc_cart']))
		$_SESSION['nc_cart']=array();
	if (isset($_SESSION['nc_cart'][GET::val('cart_del')])) 
		unset($_SESSION['nc_cart'][GET::val('cart_del')]);
	echo json_encode(array('r' => 'y'));
	exit(0);
} elseif (GET::exists('cart')) { //Mostro il carrello
	@session_start();
	HTML::add_style('com/ecommerce/css/style.css','com/ecommerce/css/cart.css');
	HTML::add_script('com/ecommerce/js/cart.js');
	if (!isset($_SESSION['nc_cart']))
		$_SESSION['nc_cart']=array();
	switch (GET::val('cart')) {
		case '' :
			echo '<div class="fp_cart"><h2>'.$__cart.'</h2>';
			if (empty($_SESSION['nc_cart'])) {
				echo '<h3>'.$__cart_emp.'</h3>
		<p>'.$__cart_empt.'</p>';
			} else {
				echo '<script type="text/javascript">__cart_emp = "'.addcslashes($__cart_emp,'"').'";__cart_empt="'.addcslashes($__cart_empt,'"').'";__cart_removed = "'.$__cart_removed.'";cart='.json_encode($_SESSION['nc_cart']).'</script>';
				if (GET::exists('del')) {
					if (isset($_SESSION['nc_cart'][GET::val('del')])) {
						echo '<p class="information">'.$_SESSION['nc_cart'][GET::val('del')]['name'].$__cart_rem.'</p>';
						unset($_SESSION['nc_cart'][GET::val('del')]);
					}
				}
				$tot=0;
				foreach($_SESSION['nc_cart'] as $k=>$v) {
					$tot += $v['price']*$v['tot'];
					$url = __http.'com/ecommerce/show/'.$k.'-'.$v['name'];
					echo '<div id="'.$k.'" class="fp_cart_prod">
					<a class="fp_cart_img" style="background-image:url('.$v['img'].')" href="'.$url.'.html">&nbsp;</a>
					<a class="fp_cart_name" href="'.$url.'.html">'.$v['name'].'</a>
					<span class="fp_cart_price">'.$v['price'].' &euro;</span>
					<input class="fp_cart_q" type="text" value="'.$v['tot'].'" />
					<a class="fp_update">Aggiorna</a>
					<div class="fp_cart_actions"><a class="fp_cart_del" href="'.__http.'com/ecommerce/cart.html?del='.$k.'">'.$__remove.'</a></div>
				</div>';
				}
				echo '<p class="fp_cart_tot">Totale provvisorio : <span id="fp_cart_tot">'.$tot.'</span> &euro;</p>
				<p class="cart_buttons"><a href="'.__http.'com/ecommerce/cart/next.html" class="abutton special" id="cart_next">'.$__proced.'</a>';
			}
			echo '</div>';
		break;
		case 'next' :
			if (!isset($_SESSION['nc_cart']['secure']))
				$secure = $_SESSION['nc_cart']['secure'] = '';
			else
				$secure = $_SESSION['nc_cart']['secure'];
			if (SECURE::is_secure($secure)) {

			} else
				$_SESSION['nc_cart']['secure'] = SECURE::login();
		break;
	}	
} elseif (GET::exists('cart_add_json')) {
	@session_start();
	if (!isset($_SESSION['nc_cart']))
		$_SESSION['nc_cart']=array();
	$u_type = get_user_type();
	$buyed = (isset($_SESSION['nc_cart'][GET::val('cart_add_json')]))?$_SESSION['nc_cart'][GET::val('cart_add_json')]['tot']+GET::int('q'):GET::int('q');
	$prod = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,MIN(`'.(DB::$pre).'nc__prices`.`price`) as price',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE  `'.(DB::$pre).'nc__products`.`id` = ',GET::val('cart_add_json'),'  AND '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<'.($buyed+1).' GROUP BY id');
	$prod_data = @DB::assoc($prod);
	if (isset($prod_data['id'])) {
		$_SESSION['nc_cart'][$prod_data['id']] = array('tot'=>$buyed,'price'=>$prod_data['price'],'img'=>$prod_data['image'],'name'=>$prod_data['name']);
		echo json_encode(array('r'=>'y','data'=>$_SESSION['nc_cart'][$prod_data['id']]));
	} else
		echo json_encode(array('r'=>'n'));
	exit(0);
} elseif (GET::exists('cart_json')) {
	@session_start();
	if (!isset($_SESSION['nc_cart']))
		$_SESSION['nc_cart']=array();
	echo json_encode($_SESSION['nc_cart']);
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
		echo '</div></div><div class="desc">'.$desc.'</div><span class="price">'.$pr['price'].' &euro;</span></li></a>';
	}
	echo '</ol>';
} elseif (GET::exists('show')) {
	HTML::add_style('com/ecommerce/css/style.css');
	HTML::add_script('com/ecommerce/js/script.js');
	$prod = DB::select('`'.(DB::$pre).'nc__products`.*,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`',array('nc__products','nc__translates'),'WHERE `nc__products_ref` = `'.(DB::$pre).'nc__products`.id AND `'.(DB::$pre).'nc__products`.id = ',GET::val('show'),' AND lang = ',LANG::short());
	if ($prod) {
		$u_type=get_user_type();
		$prod = DB::assoc($prod);
		$sales = DB::simple_select(array(array('nc__sales','*')),array('NxN__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				array('nc__products','=',$prod['id']),
				'and',
				array('nc__sales','=','id','NxN__nc__productsxnc__sales_sxs','nc__sales'),
				'and',
				array('start','<=',CURRENT),
				'and',
				array('end','>',CURRENT)
			)));
		$c_sale=0;
		while ($sale = DB::assoc($sales)) {
			if (floatval($sale['sale'])>$c_sale)
				$c_sale=floatval($sale['sale']);
		}
		echo '<div class="product_info">
		<div class="images">';
		if ($c_sale)
			echo '<span class="sale">-'.$c_sale.'%</span>';
		echo '<div class="image"></div>
			<div class="thumbs">';
		$images = DB::select('url','nc__images','WHERE nc__products_ref = ',$prod['id']);
		while ($img=DB::assoc($images))
			echo '<div class="thumb" style="background-image:url('.$img['url'].')"></div>';
		echo '</div>
			<div class="prod_id">'.$__prod_id.' : <span id="prod_id">'.$prod['id'].'</span></div>
		</div>
		<div class="scheda">
			<h1 id="prod_name">'.$prod['name'].'</h1>
			<div class="left">'.$__prod_p.'</div><div class="right">';
		$prices = DB::select('*','nc__prices','WHERE nc__products_ref = ',$prod['id'],' AND nc__categoriesU_ref = ',$u_type,' ORDER BY price DESC');
		$first=true;
		while ($price = DB::assoc($prices)) {
			$price['price'] = floatval($price['price'])*(100-$c_sale)/100;
			if ($first)$tprice=$price['price'];
			echo '<p '.(($first)?'':'class="secondary"').'><span class="price">&euro; <b class="price_n">'.$price['price'].'</b></span>/<span class="price_q">'.(($price['q_max'])?$price['q_min'].'-'.$price['q_max']:$price['q_min'].'+').'</span> '.$__prod_pi.'</p>';
			if($first)$first=false;
		}
		echo '</div>
			<div class="left">
				'.$__prod_q.'
			</div>
			<div class="right">
				<input type="text" id="quantity" value="1" /> '.$__prod_pi.'
			</div>
			<div class="left">
				'.$__prod_w.'
			</div>
			<div class="right">
				'.$prod['peso'].' kg
			</div>
			<div class="left">
				'.$__prod_tp.'
			</div>
			<div class="right">
				<span class="price">&euro; <b class="price_tot">'.$tprice.'</b></span>
			</div>
			<div class="left">&nbsp;</div>
			<div class="right"><br/><br/><a class="abutton special">'.$__prod_bn.'</a> <a id="addcart" class="abutton special">'.$__prod_cart.'</a></div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#prod_details">'.$__prod_det.'</a></li>
				<!--<li><a href="#prod_shipments">'.$__prod_shp.'</a></li>
				<li><a href="#prod_payments">'.$__prod_pay.'</a></li>-->
			</ul>
			<div id="prod_details">
				'.$prod['descrizione'].'
			</div>
			<!--<div id="prod_shipments">
				Da fare...
			</div>
			<div id="prod_payments">
				Da fare...
			</div>-->
		</div>
		<script type="text/javascript">
			$(\'.ale_bar\').tabs();
			$(\'.abutton\').button();
		</script>
	</div>';
	} else echo $__prod_nf;
}
?>
