<?php
/*
	TODO : coupons

*/

//Controllo dati
$html = '<ul class="cart_status">
	<li class="binary"></li>
	<li class="minicart"></li>
	<li>'.$__step_addr.'</li>
	<li>'.$__step_ship.'</li>
	<li>'.$__step_pay.'</li>
	<li>'.$__step_sum.'</li>
</ul>';
$ship_address = DB::select('*','nc__address',' WHERE users_ref = ',USER::data('id'));
if ((($ship_address)&&($ship_data=DB::assoc($ship_address)))||isset($_CRIPTED['address'])) {
	//Dati sulla spedizione già in possesso
	if (isset($_CRIPTED['address'])) {
		$ship_id = DB::insert('nc__address',array(
			'fname'=>$_CRIPTED['address']['fname'],
			'address'=>$_CRIPTED['address']['address'],
			'address2'=>$_CRIPTED['address']['address2'],
			'city'=>$_CRIPTED['address']['city'],
			'province'=>$_CRIPTED['address']['province'],
			'cap'=>$_CRIPTED['address']['cap'],
			'state'=>$_CRIPTED['address']['state'],
			'telephone'=>$_CRIPTED['address']['telephone'],
			'users_ref'=>USER::data('id')
			));
		$html = '';
		$ship_data=$_CRIPTED['address'];
		$ship_data['id'] = $ship_id;
	} else $html .= '<h3 class="title">'.$__title_ship.'</h3>
	<script type="text/javascript">
		$(".minicart").css({"left":"25%"});
	</script>';
	if (isset($_CRIPTED['end'])) {
		//Redirect al pagamento
		$__pay = DB::assoc(DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesp'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id AND '.DB::$pre.'nc__payments.id = ',$_SESSION['nc_cart_send']['payment']));
		$html='';
		$js = $css = array();
		$tot = 0;
		foreach($_SESSION['nc_cart'] as $v)
			$tot += floatval($v['price'])*intval($v['tot']);
		$tot += floatval($__pay['price']);
		$invoice = DB::insert('nc__orders',array('total' => $tot,'users_ref' => USER::data('id'),'nc__payments_ref' => $__pay['id']));
		if ($invoice){
			foreach($_SESSION['nc_cart'] as $k => $v)
				DB::insert('NxN__quantity_nc__ordersxnc__products',array('nc__orders'=>$invoice,'quantity'=>$v['tot'],'nc__products' => $k));
			//TODO : coupons
			/*$css = '';
			MAIL::send(USER::data('email'),$__order_conf_sub,$ccs.str_replace(array('%sitename%','%fname%','%pay_link%','%prod_list%'), array(), $__order_conf_html));*/
			include(__base_path.'com/ecommerce/payments/'.$__pay['UNI_ID'].'/payment.php');
			SECURE::returns(array('content' => array(
				'html'=>$html,
				'title'=>sprintf($__redirect,$__pay['name']),
				'js' => $js,
				'css' => $css)));
		} else 
			SECURE::returns(array('content' => array(
				'html'=>'ERROR!'.var_export(DB::debug(),true) ,
				'title'=>'error',
				'js' => $js,
				'css' => $css)));
	} elseif (isset($_CRIPTED['payment'])) {
		//Riepilogo
		$_SESSION['nc_cart_send']['payment'] = $_CRIPTED['payment'];
		$tot = 0;
		$html = '<div class="summary_data">
			<div class="left">
				<h3>'.$__sped_info.'</h3>
				<span class="ship_to">'.str_replace(array('%fname%','%address%','%city%','%province%','%cap%','%state%'), array($ship_data['fname'],$ship_data['address'],$ship_data['city'],$ship_data['province'],$ship_data['cap'],$ship_data['state']), $__ship_to).'</span>
				<h3>'.$__prods.'</h3>
				<ul class="data_cart">';
		foreach($_SESSION['nc_cart'] as $v) {
			$tot += floatval($v['price'])*intval($v['tot']);
			$html .= '<li>
				<span class="sname">'.$v['name'].'</span>
				<span class="sprice">'.$v['price'].' '.CURRENCY.'</span><span> - </span><span class="squantity">'.$__prod_q.': '.$v['tot'].'</span>
			</li>';
		}
		$__pay = DB::assoc(DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesp'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id AND '.DB::$pre.'nc__payments.id = ',$_SESSION['nc_cart_send']['payment']));
		$tot += floatval($__pay['price']);
		$html .= '</ul>
				<a class="edit_del" href="'.__http.'com/ecommerce/cart.html">'.$__edit_del.'</a>					
			</div>
			<div class="right">
				<h3>'.$__sum_pay.'</h3>
				<span class="pay_meth">'.$__pay['name'].'</span>
				<h3>'.$__sum_ship.'</h3>
				<ul class="data_ship">
					<li>'.str_replace(array('%time%','%modal%'), array('3-5','corriere'), $__ship_det).'</li>
				</ul>
				<div class="tot">
					<h3>'.$__tot.'</h3>
					<span class="price">'.$tot.' '.CURRENCY.'</span>
				</div>
			</div>
			<p class="cart_buttons"><a class="abutton special" id="cart_end">'.$__order.'</a></p>
		</div>';
		SECURE::returns(array('content' => array(
			'html'=>$html,
			'title'=>$__title_sum,
			'js' => array(__base_path.'com/ecommerce/js/summary.js'),
			'css' => array(__http.'com/ecommerce/css/summary.css'))));
	} elseif (isset($_CRIPTED['shipment'])) {
		//Dati pagamento
		$_SESSION['nc_cart_send'] = array('ship' => $_CRIPTED['shipment']);
		$html = '<div class="payment_data">
			<ul>';
		$pay_methods = DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesp'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id');
		while ($pay_method = DB::assoc($pay_methods))
			$html .= '<li id="'.$pay_method['id'].'">
					<h3>'.$pay_method['name'].'</h3>
					<span class="image" style="background-image:url('.__http.'com/ecommerce/payments/'.$pay_method['image'].')"></span>'.
					((floatval($pay_method['price'])>0)?'<span class="price">'.$pay_method['price'].' '.CURRENCY.'</span>':'')
					.'</li>';
		$html .= '</ul>
		</div>';
		SECURE::returns(array('content' => array(
			'html'=>$html,
			'title'=>$__title_pay,
			'js' => array(__base_path.'com/ecommerce/js/payment.js'),
			'css' => array(__http.'com/ecommerce/css/payment.css'))));
	} else {
		//Richiesta metodo di spedizione
		$html .= '<div class="shipment_data">
			<div class="left">
				<h3>'.$__sped_info.'</h3>
				<span class="ship_to">'.str_replace(array('%fname%','%address%','%city%','%province%','%cap%','%state%'), array($ship_data['fname'],$ship_data['address'],$ship_data['city'],$ship_data['province'],$ship_data['cap'],$ship_data['state']), $__ship_to).'</span>
				<h3>'.$__prods.'</h3>
				<ul>';
		foreach($_SESSION['nc_cart'] as $v)
			$html .= '<li>
				<span class="sname">'.$v['name'].'</span>
				<span class="sprice">'.$v['price'].' '.CURRENCY.'</span><span> - </span><span class="squantity">'.$__prod_q.': '.$v['tot'].'</span>
			</li>';
		$html .= '</ul>
			<a class="edit_del" href="'.__http.'com/ecommerce/cart.html">'.$__edit_del.'</a>
			</div>
			<div class="right">
				<h3>'.$__ship_mode.'</h3>
				<ul>
					<li><input type="radio" checked="checked" class="sped_mode" value="1"/>'.str_replace(array('%time%','%modal%'), array('3-5','corriere'), $__ship_det).'</li>
				</ul>
			</div>
			<p class="cart_buttons"><a class="abutton special" id="cart_next">'.$__next.'</a></p>
		</div>';
		SECURE::returns(array('content' => array(
			'html'=>$html,
			'title'=>$__title_ship,
			'js' => array(__base_path.'com/ecommerce/js/shipment.js'),
			'css' => array(__http.'com/ecommerce/css/buy.css',__http.'com/ecommerce/css/shipment.css'))));
		}
} else {
	//Chiedi i dati sulla spedizione
	$state_list = '';
	foreach ($__state_list as $k => $v)
		$state_list .= '<option value="'.$k.'">'.$v.'</option>';
	$html .= '
	<script type="text/javascript">
		__invalid_fname = "'.$__invalid_fname.'";
		__invalid_telephone = "'.$__invalid_telephone.'";
	</script>
	<h3 class="title">'.$__title_addr.'</h3>
	<div class="address_data">
		<div class="left">'.$__fname.'</div>
		<div class="right"><input id="fname" type="text"><span class="info"></span></div>
		<div class="left">'.$__addr.'</div>
		<div class="right"><input id="address" type="text" title="Esempio : Via roma 1"></div>
		<div class="left">'.$__addr2.'</div>
		<div class="right"><input id="address2" type="text" title="Esempio : Secondo piano, interno 1"></div>
		<div class="left">'.$__city.'</div>
		<div class="right"><input id="city" type="text"></div>
		<div class="left">'.$__province.'</div>
		<div class="right"><input id="province" type="text"></div>
		<div class="left">'.$__cap.'</div>
		<div class="right"><input id="cap" type="text"></div>
		<div class="left">'.$__state.'</div>
		<div class="right"><select id="state">'.$state_list.'</select></div>
		<div class="left">'.$__telephone.'</div>
		<div class="right"><input id="telephone" type="text"><span class="info"></span></div>
		<p class="cart_buttons"><a class="abutton special" id="cart_next">'.$__next.'</a></p>
	</div>';
	SECURE::returns(array('content' => array(
		'html'=>$html,
		'js' => array(__base_path.'com/ecommerce/js/address.js'),
		'css' => array(__http.'com/ecommerce/css/buy.css',__http.'com/ecommerce/css/address.css'))));

}

//1 : Chiedi l'indirizzo e l'indirizzo di pagamento
//2 : Scegli la modalità di spedizione
/*
	Spedizione a: %fname%, %indirizzo%, %city%, %provincia%, %cap% %state
	%Elenco prodotti%
	--Nome prodotto
	--Costo - Quantità

	Modifica quantità o rimuovi

*/
//3 : Scegli la modalità di pagamento
//4 : Conferma (riepilogo)
/*$html = '<p>Scegli un metodo di spedizione (non ancora pronto)</p>
<div id="ship-methods">
</div>';*/

exit(0);
?>