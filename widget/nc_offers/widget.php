<?php
function widget_nc_offers() {
	if (USER::logged()) {
		//identificazione tipo
	} else
		$u_type=1;
	$prods = DB::simple_select(
		array(
			array('nc__products','*'),
			array('nc__images','url','image'),
			array('nc__translates',array('name','descrizione')),
			array('nc__prices','price'),
			array('nc__sales','sale','salee','MAX')
		),
		array('nc__products','nc__images','nc__translates','nc__prices','nc__sales','NxN__nc__productsxnc__sales_sxs'),
		array(
			'WHERE'=> array(
				array('nc__products_ref','=','id','nc__images','nc__products'),
				'and',
				array('nc__products_ref','=','id','nc__translates','nc__products'),
				'and',
				array('lang','=',LANG::short()),
				'and',
				array('nc__products_ref','=','id','nc__prices','nc__products'),
				'and',
				array('nc__categoriesU_ref','=',$u_type),
				'and',
				array('q_min','<',2),
				'and',
				array('nc__products','=','id','NxN__nc__productsxnc__sales_sxs','nc__products'),
				'and',
				array('nc__sales','=','id','NxN__nc__productsxnc__sales_sxs','nc__sales')
				
				
			),
			'GROUP'=> 'id',
			'ORDER'=> 'sells'
		)
	);
	$mod = '<ol class="list">';
	while ($pr = DB::assoc($prods)) {
		$c_sale=floatval($pr['salee']);
		$mod .= '<a href="'.__http_host.__http_path.'/com/ecommerce/show/'.$pr['id'].'-'.$pr['name'].'.html"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')"><span class="sale">-'.$c_sale.'%</span><div class="stars">';
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