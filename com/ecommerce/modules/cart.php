<?php
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
	//Use the secure modal
	SECURE::libs();
	echo '<div class="secure_status"><div class="points"></div><div class="img unsecure"></div></div>
	<div class="fp_cart"><h2>'.$__cart.'</h2>';
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
			<span class="fp_cart_price">'.$v['price'].' '.CURRENCY.'</span>
			<input class="fp_cart_q" type="text" value="'.$v['tot'].'" />
			<a class="fp_update">Aggiorna</a>
			<div class="fp_cart_actions"><a class="fp_cart_del" href="'.__http.'com/ecommerce/cart.html?del='.$k.'">'.$__remove.'</a></div>
		</div>';
		}
		echo '<p class="fp_cart_tot">Totale provvisorio : <span id="fp_cart_tot">'.$tot.'</span> '.CURRENCY.'</p>
		<p class="cart_buttons"><a class="abutton inactive" id="cart_next">'.$__proced.'</a>';
	}
	echo '</div>';
		/*case 'next' :
			$_SESSION['nc_cart']['secure'] = SECURE::login(array(
				'page' => array('com' => 'ecommerce'),
				'params' => array()));
		break;*/
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
}
?>