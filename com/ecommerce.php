<?php

if (isset($_GET['install']))
include('ecommerce/install.php');
if (isset($_GET['show_cats'])) {
	HTML::add_style('com/ecommerce/css/cats.css');
	$cat = DB::select(DB::$pre.'nc__translatesC.name,'.DB::$pre.'nc__categories.*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref IS NULL AND lang = ',LANG::short());
	while($c = DB::assoc($cat)) {
		echo '<ul class="cats"><li class="title"><a href="'.__http_host.__http_path.'/com/ecommerce/category/'.$c['id'].'-'.$c['name'].'.html">'.$c['name'].'</a></li>';
		$subcat = DB::select('*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref = ',$c['id'],' AND lang = ',LANG::short());  DB::select('*','nc__categories','WHERE');
		while ($d = DB::assoc($subcat))
			echo '<li><a href="'.__http_host.__http_path.'/com/ecommerce/category/'.$d['id'].'-'.$d['name'].'.html">'.$d['name'].'</a></li>';
		echo '</ul>';
	}
} elseif (isset($_GET['category'])) {
	if (USER::logged()) {
		//identificazione tipo
	} else
		$u_type=1;
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE  `nc__categories_ref` IN ((SELECT id FROM  `'.(DB::$pre).'nc__categories`  WHERE  `nc__categories_ref` = ',$_GET['category'],'),',$_GET['category'],')  AND '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id');
	echo '<ol class="list">';
	while ($pr = DB::assoc($prods)) {
		echo '<a href="'.__http_host.__http_path.'/com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')"><div class="stars">';
		for ($i=0;$i<intval($pr['stars']);$i++)
			echo '<span class="on">';
		for ($i=intval($pr['stars']);$i<5;$i++)
			echo '<span class="off">';
		$desc = substr(strip_tags($pr['descrizione']),0,40);
		$desc = substr($desc,0,strrpos($desc,' '));
		echo '</div></div><div class="desc">'.$desc.'</div><span class="price">'.$pr['price'].' &euro;</span></li></a>';
	}
	echo '</ol>';
} elseif (isset($_GET['show'])) {
	include(__base_path.'com/ecommerce/lang/'.LANG::short().'.php');
	HTML::add_style('com/ecommerce/css/style.css');
	HTML::add_script('com/ecommerce/js/script.js');
	$prod = DB::select('`'.(DB::$pre).'nc__products`.*,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`',array('nc__products','nc__translates'),'WHERE `nc__products_ref` = `'.(DB::$pre).'nc__products`.id AND `'.(DB::$pre).'nc__products`.id = ',$_GET['show'],' AND lang = ',LANG::short());
	if ($prod) {
		if (USER::logged()) {
			//identificazione tipo
		} else
			$u_type=1;
		$prod = DB::assoc($prod);
		$sales = DB::select(array(array('nc__sales','*')),array('nxn__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				array('nc__products','=',$prod['id']),
				'and',
				array('nc__sales','=','id','nxn__nc__productsxnc__sales_sxs','nc__sales'),
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
			<div class="prod_id">'.$__prod_id.' : '.$prod['id'].'</div>
		</div>
		<div class="scheda">
			<h1 id="prod_name">'.$prod['name'].'</h1>
			<div class="left">'.$__prod_p.'</div><div class="right">';
		$prices = DB::select('*','nc__prices','WHERE nc__products_ref = ',$prod['id'],' AND nc__categoriesU_ref = ',$u_type);
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
			<div class="right"><br/><br/><a class="abutton special">'.$__prod_bn.'</a> <a class="abutton special">'.$__prod_cart.'</a></div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#prod_details">'.$__prod_det.'</a></li>
				<li><a href="#prod_shipments">'.$__prod_shp.'</a></li>
				<li><a href="#prod_payments">'.$__prod_pay.'</a></li>
			</ul>
			<div id="prod_details">
				'.$prod['descrizione'].'
			</div>
			<div id="prod_shipments">
				Da fare...
			</div>
			<div id="prod_payments">
				Da fare...
			</div>
		</div>
		<script type="text/javascript">
			$(\'.ale_bar\').tabs();
			$(\'.abutton\').button();
		</script>
	</div>';
	} else echo $__prod_nf;
}
?>
