<?php
function widget_nc_products_tc() {
	if (USER::logged()) {
		//identificazione tipo
	} else
		$u_type=1;
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id ORDER BY sells LIMIT 12');
	$mod = '<div class="list-title">Pi&ugrave; Comprati</div>
<ol class="list">';
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
		$mod .= '<a href="'.__http_host.__http_path.'/com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')">';
		if ($c_sale)
			$mod .= '<span class="sale">-'.$c_sale.'%</span>';
		$mod .= '<div class="stars">';
		for ($i=0;$i<intval($pr['stars']);$i++)
			$mod .= '<span class="on">';
		for ($i=intval($pr['stars']);$i<5;$i++)
			$mod .= '<span class="off">';
		$desc = substr(strip_tags($pr['descrizione']),0,40);
		$mod .= '</div></div><span class="desc">'.substr($desc,0,strrpos($desc,' ')).'</span><span class="price">'.floatval($pr['price'])*((100-$c_sale)/100).' &euro;</span><span class="id">'.$pr['id'].'</span></li></a>';
	}
	return $mod.'
</ol>';
}
?>