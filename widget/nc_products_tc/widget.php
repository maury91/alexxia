<?php
function widget_nc_products_tc() {
	if (USER::logged()) 
		$u_type=(USER::data('nc_cat')==0)? 1 : intval(USER::data('nc_cat'));
	else
		$u_type=1;
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('nc__products','nc__images','nc__translates','nc__prices'),'WHERE '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id ORDER BY sells LIMIT 12');
	$mod = '<div class="list-title with-sardinia">Pi&ugrave; Comprati</div>
<ol class="list">';
	while ($pr = DB::assoc($prods)) {
		$sales = DB::simple_select(array(array('nc__sales','*')),array('NxN__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				 array('nc__products','=',$pr['id']),
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
		$mod .= '<a href="'.__http.'com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')">';
		if ($c_sale)
			$mod .= '<span class="sale">-'.$c_sale.'%</span>';
		$mod .= '<div class="stars">';
		for ($i=0;$i<intval($pr['stars']);$i++)
			$mod .= '<span class="on"></span>';
		for ($i=intval($pr['stars']);$i<5;$i++)
			$mod .= '<span class="off"></span>';
		$desc = substr(strip_tags($pr['descrizione']),0,40);
		$mod .= '</div></div><span class="desc">'.substr($desc,0,strrpos($desc,' ')).'</span><span class="price">'.floatval($pr['price'])*((100-$c_sale)/100).' &euro;</span><span class="id">'.$pr['id'].'</span></li></a>';
	}
	return $mod.'
</ol>';
}
?>