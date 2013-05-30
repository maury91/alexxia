<?php
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
		<div class="prod_id">'.$__prod_id.' : <span id="prod_id">'. $prod['id'].'</span></div>
	</div>
	<div class="scheda">
		<h1 id="prod_name">'.$prod['name'].'</h1>
		<div class="left">'.$__prod_p.'</div><div class="right">';
	$prices = DB::select('*','nc__prices','WHERE nc__products_ref = ',$prod['id'],' AND nc__categoriesU_ref = ',$u_type,' ORDER BY price DESC');
	$first=true;
	while ($price = DB::assoc($prices)) {
		$price['price'] = floatval($price['price'])*(100-$c_sale)/100;
		if ($first)$tprice=$price['price'];
		echo '<p '.(($first)?'':'class="secondary"').'><span class="price">'.CURRENCY.' <b class="price_n">'.$price['price'].'</b></span>/<span class="price_q">'.(($price['q_max'])?$price['q_min'].'-'.$price['q_max']:$price['q_min'].'+').'</span> '.$__prod_pi.'</p>';
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
			<span class="price">'.CURRENCY.' <b class="price_tot">'.$tprice.'</b></span>
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
?>