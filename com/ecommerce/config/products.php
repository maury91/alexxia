<?php
/**
 *	Ecommerce Component for ALExxia
 *	This component is only for didactical use
 *	You can't use this component for commercial purpuose without authorization from the authors
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
include(__base_path.'com/ecommerce/config/lang/'.LANG::short().'.php');
if (isset($external['offer'])||isset($external['edit_offer'])) { //edit_offer
	if (isset($external['edit_offer'])) {
		function date_convert($d) {
			return @date( 'd/m/y', strtotime($d));
		}
		$saled = DB::assoc(DB::simple_select('*','nc__sales',array('WHERE'=>array(array('id','=',$external['edit_offer'])))));
		$sale=$saled['sale'];
		$start=date_convert($saled['start']);
		$end=date_convert($saled['end']);
		$sal_id=$saled['id'];
		$prods = DB::simple_select('*',array('nc__sales','NxN__nc__productsxnc__sales_sxs'),array('WHERE'=>array(
			array('nc__sales','=','id','NxN__nc__productsxnc__sales_sxs','nc__sales'),
			'and',
			array('id','=',$external['edit_offer'],'nc__sales')
		)));
		$external['prod'] = array();
		while ($p = DB::assoc($prods))
			$external['prod'][] = $p['id'];
	} else {
		$sale=0;
		$sal_id=-1;
		$start=$end='';
	}
	$content['html'] = '
	<div class="ins_data">
		<div class="left">
			'.$__sales_on	.'
		</div>
		<div class="right">
			<span contenteditable="true" id="sale">'.$sale.'</span>%
		</div>
		<div class="left">
			'.$__start_date.'
		</div>
		<div class="right">
			<input type="text" id="salesStart" value="'.$start.'"/>
		</div>
		<div class="left">
			'.$__end_date.'
		</div>
		<div class="right">
			<input type="text" id="salesEnd" value="'.$end.'"/>
		</div>
		<div class="left">&nbsp;</div>
		<div class="right">
			<a class="abutton" id="save_sale">'.$__save.'</a>';
	if ($start)
		$content['html'] .= '<a class="com config_link abutton" href="ecommerce/config/products.php?offer_del='.$sal_id.'">'.$__delete.'</a>';
	$content['html'] .= '</div>
	</div>
	<script>
		prods = '.json_encode($external['prod']).';
		sal_id = '.$sal_id.';
		$(".abutton").button();
	</script>
	';
	STYLE::add('css/sales_data.css','ecommerce/');
	SCRIPT::add('js/sales.js');
} elseif (isset($external['add_prod'])) {
	if (isset($external['edit'])) {
		if (DB::delete('nc__prices','WHERE nc__products_ref = ',$external['edit'])&&DB::delete('nc__translates','WHERE nc__products_ref = ',$external['edit'])&&DB::delete('nc__images','WHERE nc__products_ref = ',$external['edit'])&&DB::update('nc__products',array('nc__categories_ref' => $external['add_prod']['cats'][0]),'WHERE id = ',$external['edit'])&&DB::update('nc__products',array('peso'=>$external['add_prod']['peso'],'duration'=>$external['add_prod']['duration'],'dimension_H'=>$external['add_prod']['dimensions']['h'],'dimension_W'=>$external['add_prod']['dimensions']['w'],'dimension_L'=>$external['add_prod']['dimensions']['l']),'WHERE id = ',$external['edit']))
			$prod_id = $external['edit'];
	} else
		$prod_id = DB::insert('nc__products',array('nc__categories_ref' => $external['add_prod']['cats'][0],'peso'=>$external['add_prod']['peso'],'duration'=>$external['add_prod']['duration'],'dimension_H'=>$external['add_prod']['dimensions']['h'],'dimension_W'=>$external['add_prod']['dimensions']['w'],'dimension_L'=>$external['add_prod']['dimensions']['l']));
	$other=array();
	if ($prod_id) {
		//Collegamento prezzi
		foreach ($external['add_prod']['prices'] as $k=>$v)
			foreach ($v as $a) {
				if ($a['quantity'][strlen($a['quantity'])-1]=='+') {
					$min=substr($a['quantity'],0,-1);
					$max=0;
				} else {
					$mm = explode('-',$a['quantity']);
					$min = $mm[0];
					$max = $mm[1];
				}
				DB::insert('nc__prices',array('price'=>$a['price'],'q_min'=>$min,'q_max'=>$max,'nc__categoriesU_ref'=>$k,'nc__products_ref'=>$prod_id));
			}
		//Collegamento prezzi con sconto fisso
		$fixed_saless = DB::SELECT('*','nc__categoriesU',' WHERE fixed_sale != 0');
		while ($fs = DB::assoc($fixed_saless)) {
			list($k,$v) = each($external['add_prod']['prices']);
			foreach ($v as $a) {
				if ($a['quantity'][strlen($a['quantity'])-1]=='+') {
					$min=substr($a['quantity'],0,-1);
					$max=0;
				} else {
					$mm = explode('-',$a['quantity']);
					$min = $mm[0];
					$max = $mm[1];
				}
				DB::insert('nc__prices',array('price'=>floatval($a['price'])*(100-floatval($fs['fixed_sale']))/100,'q_min'=>$min,'q_max'=>$max,'nc__categoriesU_ref'=>$fs['id'],'nc__products_ref'=>$prod_id));
			}
		}
		//Collegamento dati multi lingua
		foreach ($external['add_prod']['langs'] as $k=>$v)
			DB::insert('nc__translates',array('lang'=>$k,'name'=>$v['name'],'descrizione'=>$v['description'],'nc__products_ref'=>$prod_id));
		//Collegamento immagini
		foreach ($external['add_prod']['images'] as $v)
			DB::insert('nc__images',array('nc__products_ref'=>$prod_id,'url'=>$v));
		$content = array('r' => 'y','id' => $prod_id);
	} else
		$content = array('r' => 'n','err' => DB::error(),'input'=>$external,'query'=>$GLOBALS['query']);
} elseif (isset($external['new_cat'])) {
	$last = DB::insert('nc__categories',array('nc__categories_ref' => NULL));
	if ($last) {
		foreach ($external['new_cat'] as $k=>$v) {
			DB::insert('nc__translatesC',array('name'=>$v,'lang'=>$k,'nc__categories_ref'=>$last));
		}
		$content = array('r' => 'y','id' => $last,'name' => $external['new_cat']);
	} else
		$content = array('r' => 'n', 'err' => DB::error());
} elseif (isset($external['new_marc'])) {
	$last = DB::insert('nc__creators',array('name' => $external['new_marc']));
	if ($last) 
		$content = array('r' => 'y','id' => $last,'name' => $external['new_marc']);
	else
		$content = array('r' => 'n', 'err' => DB::error());
} elseif (isset($external['new_catU'])) {
	$last = DB::insert('nc__categoriesU',array('name'=>$external['new_catU']));
	if ($last)
		$content = array('r' => 'y','id' => $last,'name' => $external['new_catU']);
	else
		$content = array('r' => 'n', 'err' => DB::error());
} elseif (isset($external['add'])||isset($external['edit'])) {
	$cats='';
	$cat = DB::select(DB::$pre.'nc__categories.id,'.DB::$pre.'nc__translatesC.name',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id GROUP BY '.DB::$pre.'nc__categories.id');
	while($c = DB::assoc($cat))
		$cats .= '<option value="'.$c['id'].'">'.$c['name'].'</option>';
	$marcs='';
	$marc = DB::select('*','nc__creators');
	while ($m = @DB::assoc($marc)) 
		$marcs .= '<option value="'.$m['id'].'">'.$m['name'].'</option>';
	$media_id = MEDIA_MAN::make('./media',array('png','jpg'), true, true, true, true, true, true, '', '',true);
	$content['html'] = '
	<a title="it" class="img flag it-IT" style="border-bottom: 2px solid #69F;"> </a><a title="en" class="img flag en-US"> </a>'.$__click_to_edit.'
	<div class="product_info">
		<div class="images">
			<div class="image"></div>
			<div class="thumbs"></div>
			<a class="abutton" style="clear: both;" onclick=\'media_manager({uid : "'.$media_id.'", onSelected : choose_img, dir : "./media/images/",base_path : "'.__http.'"})\'>'.$__add.'</a>
		</div>
		<div class="scheda">
			<h1 contenteditable="true" id="prod_name">'.$__prod_name.'</h1>
			<div id="price_accordion">';
	$pcats = DB::select('*','nc__categoriesU');
	while($pcat = DB::assoc($pcats)) {
		if ($pcat['fixed_sale']=='0')
			$content['html'] .= '
				<h3>'.$__user_price.' '.$pcat['name'].'</h3>
				<div>
					<div class="left">'.$__price.'</div><div class="right price_group" id="pc_'.$pcat['id'].'">
						<p><span class="price">&euro; <span contenteditable="true" class="n_price">0.0</span></span>/<span contenteditable="true" class="price_q">1-2</span> '.$__pezzi.'</p>
						<p><a class="abutton add_price">'.$__add.'</a></p>
					</div>
				</div>';
		else
			$content['html'] .= '
				<h3>'.$__user_price.' '.$pcat['name'].'</h3>
				<div>
					'.$__fixed_discount.' : -'.$pcat['fixed_sale'].'% ('.$__fixed_discountNT.').
				</div>
		';
	}
	$content['html'] .= '
				<h3 id="add_t_price">'.$__add_price_type.'</h3>
			</div>
			<div class="left">
				<b>'.$__dimensions.' : </b>
			</div>
			<div class="left">
				'.$__width.'
			</div>
			<div class="right">
				<span contenteditable="true" id="dimW">0.0</span> cm
			</div>
			<div class="left">
				'.$__height.'
			</div>
			<div class="right">
				<span contenteditable="true" id="dimH">0.0</span> cm
			</div>
			<div class="left">
				'.$__depth.'
			</div>
			<div class="right">
				<span contenteditable="true" id="dimL">0.0</span> cm
			</div>
			<div class="left">
				'.$__life.'
			</div>
			<div class="right">
				<span contenteditable="true" id="duration">0</span> giorni
			</div>
			<div class="left">
				<input type="checkbox" checked="checked" id="pacco_regalo" />'.$__gift.'
			</div>
			<div class="right">
				&euro; <span contenteditable="true" id="regalo_p">0.0</span>
			</div>
			<div class="left">
				'.$__weight.'
			</div>
			<div class="right">
				<span contenteditable="true" id="peso">0.0</span> kg
			</div>
			<div class="left">
				'.$__category.'
			</div>
			<div class="right">
				<select class="cat"><option disabled selected>'.$__category_sel.'</option>'.$cats.'<option value="add">'.$__add.'</option></select>
				<!--<p><a class="abutton" id="add_cat">Aggiungi un\'altra categoria</a></p>-->
			</div>
			<div class="left">
				'.$__creator.'
			</div>
			<div class="right">
				<select class="marc"><option disabled selected>'.$__creator_sel.'</option>'.$marcs.'<option value="add">'.$__add.'</option></select>
			</div>
			<div class="left">&nbsp;</div>
			<div class="right"><br/><br/><a class="abutton special normal_save" id="prod_save">'.$__save.'</a><a class="abutton special special_save" id="prod_save_new">'.$__save_new.'</a><a class="abutton special special_save" id="prod_save_over">'.$__save_open.'</a></div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#prod_details">'.$__details.'</a></li>
				<li><a href="#prod_shipments">'.$__shipments.'</a></li>
				<li><a href="#prod_payments">'.$__payments.'</a></li>
			</ul>
			<div id="prod_details" contenteditable="true">
				'.$__details_here.'
			</div>
			<div id="prod_shipments">
				Da fare...
			</div>
			<div id="prod_payments">
				Da fare...
			</div>
		</div>
		<script>
			$(\'.ale_bar\').tabs();
		</script>
	</div>';
	if (isset($external['edit'])) {
		$prod = DB::select('*','nc__products','WHERE id = ',$external['edit']);
		if ($prod) {
			$prod_data = DB::assoc($prod);
			$prices = DB::select('*','nc__prices','WHERE nc__products_ref = ',$prod_data['id']);
			$prod_data['prices'] = array();
			while ($price_data = DB::assoc($prices))
				$prod_data['prices'][$price_data['nc__categoriesU_ref']][] = $price_data;
			$prod_data['langs'] = array();
			$trads = DB::select('*','nc__translates','WHERE nc__products_ref = ',$prod_data['id']);
			while ($trad = DB::assoc($trads))
				$prod_data['langs'][] = $trad;
			$prod_data['images'] = array();
			$images = DB::select('*','nc__images','WHERE nc__products_ref = ',$prod_data['id']);
			while ($image = DB::assoc($images))
				$prod_data['images'][] = $image;
			$content['html'] .= '<script type="text/javascript">product_data = '.json_encode($prod_data).'; setTimeout("load_product()",600)</script>';
		} else
			$content['html'] = $__no_product_found;
	}
	SCRIPT::add('js/config.js');
	STYLE::add('css/style.css','ecommerce/');
} else {
	//Eliminazione Offerte
	if (isset($external['offer_del'])) {
		if (DB::simple_delete('NxN__nc__productsxnc__sales_sxs',array('WHERE'=>array(array('nc__sales','=',$external['offer_del']))))&&DB::simple_delete('nc__sales',array('WHERE'=>array(array('id','=',$external['offer_del'])))))
			echo $__sales_del.'<br/>';
		else
			echo $GLOBALS['query'];
	}
	//Creazione offerte
	if (isset($external['new_offer'])) {
		if ($external['new_offer']['id']!=-1) {
			if (DB::update('nc__sales', array('sale' => $external['new_offer']['sale'],'start' => array('date' => @strtotime($external['new_offer']['start'])),'end' => array('date' => @strtotime($external['new_offer']['end']))),array('WHERE'=>array(array('id','=',$external['new_offer']['id']))))) 
				echo $__sales_edit.'<br/>';
		} else {
			$sale_id = DB::insert('nc__sales', array('sale' => $external['new_offer']['sale'],'start' => array('date' => @strtotime($external['new_offer']['start'])),'end' => array('date' => @strtotime($external['new_offer']['end']))));
			if ($sale_id) {
				foreach ($external['prod'] as $v)
					DB::insert('NxN__nc__productsxnc__sales_sxs',array('nc__sales' => $sale_id,'nc__products' => $v));
				echo $__sales_ins.'<br/>';
			} else
				echo $GLOBALS['query'].DB::error();
		}
	}
	//Eliminazione prodotti
	if (isset($external['del'])) {
		if (DB::delete('nc__prices','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__translates','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__images','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__products','WHERE id = ',$external['del']))
			echo $__prod_deleted.'<br/><br/>';
		else
			echo $__prod_no_del.'<br/><br/>';
	}
	//Duplicazione prodotti
	if (isset($external['double'])) {
		//Prendo tutti i dati
		$prod = DB::select('*','nc__products','WHERE id = ',$external['double']);
		if ($prod&&($data = DB::assoc($prod))) {
			$prod_id = DB::insert('nc__products',array('nc__categories_ref' => $data['nc__categories_ref'],'peso'=>$data['peso'],'duration'=>$data['duration'],'dimension_H'=>$data['dimension_H'],'dimension_W'=>$data['dimension_W'],'dimension_L'=>$data['dimension_L']));
			if ($prod_id) {
				$prices = DB::select('*','nc__prices','WHERE nc__products_ref = ',$external['double']);
				while ($price = DB::assoc($prices))
					DB::insert('nc__prices',array('price'=>$price['price'],'q_min'=>$price['q_min'],'q_max'=>$price['q_max'],'nc__categoriesU_ref'=>$price['nc__categoriesU_ref'],'nc__products_ref'=>$prod_id));
				$langs = DB::select('*','nc__translates','WHERE nc__products_ref = ',$external['double']);
				while ($lang = DB::assoc($langs))
					DB::insert('nc__translates',array('lang'=>$lang['lang'],'name'=>$lang['name'],'descrizione'=>$lang['descrizione'],'nc__products_ref'=>$prod_id));
				$images = DB::select('*','nc__images','WHERE nc__products_ref = ',$external['double']);
				while ($image = DB::assoc($images))
					DB::insert('nc__images',array('nc__products_ref'=>$prod_id,'url'=>$image['url']));
			} else echo $__copy_error.'<br/>';
		} else echo $__copy_error.'<br/>';
	}
	//Aggiungi prodotto
	SCRIPT::add('js/home.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/icons.css','ecommerce/');
	echo '<a class="com config_link abutton" href="ecommerce/config/products.php?add">'.$__prod_add.'</a><a id="offer_add" class="abutton">'.$__sales_add.'</a><script type="text/javascript">$(".abutton").button();</script><br/><br/><table width="100%" cellpadding="0" cellspacing="0"><thead><tr><td>#</td><td>'.$__name.'</td><td>'.$__category.'</td><td>'.$__sales.'</td><td></td></tr></thead>';
	//Lista prodotti
	$prods = DB::select(DB::$pre.'nc__products.id,'.DB::$pre.'nc__translates.name,'.DB::$pre.'nc__translatesC.name AS cat_name',array('nc__products','nc__translates','nc__translatesC'),'WHERE  `nc__products_ref` =  `'.DB::$pre.'nc__products`.id AND `'.DB::$pre.'nc__translatesC`.`nc__categories_ref` =  `'.DB::$pre.'nc__products`.`nc__categories_ref` GROUP BY id');
	function date_convert($d) {
		return @date( 'd/m/y', strtotime($d));
	}
	while ($prod = DB::assoc($prods)) {
		echo '<tr><td>'.$prod['id'].'</td><td>'.$prod['name'].'</td><td>'.$prod['cat_name'].'</td><td>';
		//Offerte
		$sales = DB::simple_select(array(array('nc__sales','*')),array('NxN__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				array('nc__products','=',$prod['id']),
				'and',
				array('nc__sales','=','id','NxN__nc__productsxnc__sales_sxs','nc__sales')	
			)));
		while ($sale = DB::assoc($sales))
			echo '<a class="com config_link" href="ecommerce/config/products.php?edit_offer='.$sale['id'].'">'.$sale['sale'].'% ('.date_convert($sale['start']).' - '.date_convert($sale['end']).')</a> ';
		echo '</td><td><a title="'.$__prod_edit.'" class="img pr edit"></a> <a title="'.$__prod_del.'" class="img del pr"></a> <a title="'.$__prod_copy.'" class="img pr double"></a></tr>';
	}
	echo '</table>';
}
?>