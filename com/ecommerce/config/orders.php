<?php
define('CURRENCY','&euro;');
include(__base_path.'com/ecommerce/config/lang/'.LANG::short().'.php');
if (isset($external['change_state'])) {
	$cur = DB::assoc(DB::select('*','nc__orders',' WHERE id = ',$external['change_state']['key']));
	if (DB::update('nc__orders',array('status'=>$external['change_state']['value']),' WHERE id = ',$external['change_state']['key'])) {
		$content = array('r' => 'y','infos' => array('a' => intval($external['change_state']['value'])<2, 'b' => intval($cur['status'])<2));
		if ((intval($external['change_state']['value'])<2)||(intval($cur['status'])<2)) {
			//Invio email
			include(__base_path.'com/ecommerce/config/config.php');
			$sended = MAIL::send($info_email,$__order_change_sub,str_replace(array('%ord_id%','%old%','%new%'), array($external['change_state']['key'],$__order_stat[$cur['status']],$__order_stat[$external['change_state']['value']]), $__order_change));
			if ($sended !==true)
				$content['err_email'] = $sended;
		}
	}
	else 
		$content = array('r' => 'n','value' => $cur['status'],'queryes'=>DB::debug());
} elseif (isset($external['view'])) {
	$order = DB::simple_select(
		array(
			array('nc__translatesP','name','pay_name'),
			array('nc__orders','*'),
			array('users',array('nick','nc_cat'))),
		array('nc__orders','nc__translatesP','users'),
		array('WHERE'=>
			array(
				array('nc__payments_ref','=','nc__payments_ref','nc__translatesP','nc__orders'),
				'AND',
				array('id','=','users_ref','users','nc__orders'),
				'AND',
				array('lang','=',LANG::short(),'nc__translatesP'),
				'AND',
				array('id','=',$external['view'],'nc__orders')
				))
		);
	if ($order_data = @DB::assoc($order)) {
		$nc_cat = ($order_data['nc_cat']==0)? 1 : intval($order_data['nc_cat']);
		echo '<div class="left">';
		$prods = DB::simple_select(
			array(
				array('nc__products','*'),
				array('nc__translates','name'),
				array('NxN__quantity_nc__ordersxnc__products','quantity'),
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
					array('nc__orders','=',$order_data['id'],'NxN__quantity_nc__ordersxnc__products'),
					'AND',
					array('nc__categoriesU_ref','=',$nc_cat),
					'AND',
					array('nc__products_ref','=','id','nc__prices','nc__products'),
					'AND',
					array('q_min','<=','quantity','nc__prices','NxN__quantity_nc__ordersxnc__products'),
					'AND',
					array('lang','=',LANG::short(),'nc__translates')
					),
				'GROUP' => 'id'));
		echo '<b>Prodotti comprati:</b><ul>';
		while ($prod = DB::assoc($prods)) {
			echo '<li class="prod">
				<a href="'.__http.'com/ecommerce/show/'.$prod['id'].'.html" target="_blank">
					<span class="image" style="background-image:url('.$prod['image'].')"></span>
					<span class="name">'.$prod['name'].'</span>
					<span class="price">'.$prod['price'].' '.CURRENCY.'</span>
					<span class="quantity">Quantit&agrave; : '.$prod['quantity'].'</span>
				</a>
			</li>';
		}
		$indirizzo = DB::assoc(DB::select('*','nc__address','WHERE id = ',$order_data['nc__address']));
		echo '</ul></div><div class="right">
			<b>Indirizzo di spedizione:</b>
			<ul>
				<li>'.$indirizzo['fname'].'</li>
				<li>'.$indirizzo['address'].(($indirizzo['address2']!='')?'('.$indirizzo['address2'].')':'').','.$indirizzo['city'].'('.$indirizzo['province'].'), '.$indirizzo['cap'].' '.$indirizzo['state'].'</li>
				<li>'.$indirizzo['telephone'].'</li>
			</ul>

			<b>Modifica stato</b>
			<select class="change_state">
			';
		foreach ($__order_stat as $k => $v) 
			echo '<option value="'.$k.'" '.(($k==$order_data['status'])?'selected':'').'>'.$v.'</option>';
		echo '
			</select>
			</div>';
	} else
		echo 'Ordine non trovato!';
} else {
	$orders = DB::simple_select(
		array(
			array('nc__translatesP','name','pay_name'),
			array('nc__orders','*'),
			array('users','nick')),
		array('nc__orders','nc__translatesP','users'),
		array('WHERE'=>
			array(
				array('nc__payments_ref','=','nc__payments_ref','nc__translatesP','nc__orders'),
				'AND',
				array('id','=','users_ref','users','nc__orders'),
				'AND',
				array('lang','=',LANG::short(),'nc__translatesP')
				),
			'ORDER' => 'BY CASE WHEN status = 0 THEN 1 WHEN status = 1 THEN 0 WHEN status > 1 THEN `status` END ASC, DATA DESC')
		);
	SCRIPT::add('js/orders.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/order.css','ecommerce/');
	echo '<table width="100%" cellpadding="0" cellspacing="0"><thead><tr><td>#</td><td>'.$__order_user.'</td><td>'.$__order_pay.'</td><td>'.$__order_ship.'</td><td>'.$__order_tot.'</td><td>'.$__order_status.'</td><td>'.$__order_data.'</td><td></td></tr></thead>';
	while ($order = DB::assoc($orders)) {
		echo '<tr><td>'.$order['id'].'</td><td>'.$order['nick'].'</td><td>'.$order['pay_name'].'</td><td>---</td><td>'.$order['total'].'</td><td>'.$__order_stat[$order['status']].'</td><td>'.$order['data'].'</td><td>\/</td></tr>';
	}
	echo '</table>';
}
?>