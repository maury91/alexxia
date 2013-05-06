<?php
function widget_nc_products_mb() {
	if (USER::logged()) {
		//identificazione tipo
	} else
		$u_type=1;
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id LIMIT 4');
	$mod = '<div class="list-title">Le scelte di oggi</div>
<ul class="list">';
	$first=true;
	while ($pr = DB::assoc($prods)) {
		$sales = DB::select(array(array('nc__sales','*')),array('nxn__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				 array('nc__products','=',$pr['id']),
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
		$mod .= '<a href="'.__http_host.__http_path.'/com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li class="'.($first?'first':'').'"><div class="image" style="background-image:url('.$pr['image'].')">';
		if ($c_sale)
			$mod .= '<span class="sale">-'.$c_sale.'%</span>';
		$mod .= '<div class="stars"><span class="on"></span><span class="on"></span><span class="on"></span><span class="off"></span><span class="off"></span></div></div><span class="title">'.$pr['name'].'</span><span class="price">'.floatval($pr['price'])*((100-$c_sale)/100).' &euro;</span><span class="id">'.$pr['id'].'</span></li></a>';
		$first=false;
	}
	return $mod.'
</ul>';
}
?>