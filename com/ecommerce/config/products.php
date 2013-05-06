<?php
if (isset($external['offer'])||isset($external['edit_offer'])) { //edit_offer
	if (isset($external['edit_offer'])) {
		function date_convert($d) {
			return date( 'd/m/y', strtotime($d));
		}
		$saled = DB::assoc(DB::select('*','nc__sales',array('WHERE'=>array(array('id','=',$external['edit_offer'])))));
		$sale=$saled['sale'];
		$start=date_convert($saled['start']);
		$end=date_convert($saled['end']);
		$sal_id=$saled['id'];
		$prods = DB::select('*',array('nc__sales','nxn__nc__productsxnc__sales_sxs'),array('WHERE'=>array(
			array('nc__sales','=','id','nxn__nc__productsxnc__sales_sxs','nc__sales'),
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
			Sconto sui prodotti scelti
		</div>
		<div class="right">
			<span contenteditable="true" id="sale">'.$sale.'</span>%
		</div>
		<div class="left">
			Data inizio
		</div>
		<div class="right">
			<input type="text" id="salesStart" value="'.$start.'"/>
		</div>
		<div class="left">
			Data fine
		</div>
		<div class="right">
			<input type="text" id="salesEnd" value="'.$end.'"/>
		</div>
		<div class="left">&nbsp;</div>
		<div class="right">
			<a class="abutton" id="save_sale">Salva</a>';
	if ($start)
		$content['html'] .= '<a class="com config_link abutton" href="ecommerce/config/products.php?offer_del='.$sal_id.'">Elimina</a>';
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
} elseif (isset($external['new_catU'])) {
	$last = DB::insert('nc__categoriesU',array('name'=>$external['new_catU']));
	if ($last)
		$content = array('r' => 'y','id' => $last,'name' => $external['new_catU']);
	else
		$content = array('r' => 'n', 'err' => DB::error());
} elseif (isset($external['add'])||isset($external['edit'])) {
	$cats='';
	$cat = DB::select(DB::$pre.'nc__categories.id,'.DB::$pre.'nc__translatesC.name',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id GROUP BY '.DB::$pre.'nc__categories.id');
	while($c = DB::assoc($cat)) {
		$cats .= '<option value="'.$c['id'].'">'.$c['name'].'</option>';
	}
	$media_id = MEDIA_MAN::make('./media',array('png','jpg'), true, true, true, true, true, true, '', '',true);
	$content['html'] = '
	<a title="it-IT" class="img flag it-IT" style="border-bottom: 2px solid #69F;"> </a><a title="en-US" class="img flag en-US"> </a>Clicca sui campi per modificarli
	<div class="product_info">
		<div class="images">
			<div class="image"></div>
			<div class="thumbs"></div>
			<a class="abutton" style="clear: both;" onclick=\'media_manager({uid : "'.$media_id.'", onSelected : choose_img, dir : "./media/images/",base_path : "'.__http_host.__http_path.'"})\'>Aggiungi</a>
		</div>
		<div class="scheda">
			<h1 contenteditable="true" id="prod_name">Nome Prodotto</h1>
			<div id="price_accordion">';
	$pcats = DB::select('*','nc__categoriesU');
	while($pcat = DB::assoc($pcats))
	$content['html'] .= '
				<h3>Prezzo Utente '.$pcat['name'].'</h3>
				<div>
					<div class="left">Prezzo</div><div class="right price_group" id="pc_'.$pcat['id'].'">
						<p><span class="price">&euro; <span contenteditable="true" class="n_price">0.0</span></span>/<span contenteditable="true" class="price_q">1-2</span> pezzi</p>
						<p><a class="abutton add_price">Aggiungi</a></p>
					</div>
				</div>';
	$content['html'] .= '
				<h3 id="add_t_price">Aggiungi Tipologia di prezzi</h3>
			</div>
			<div class="left">
				Dimensioni : <br/>
				Larghezza
			</div>
			<div class="right">
				<span contenteditable="true" id="dimW">0.0</span> cm
			</div>
			<div class="left">
				Altezza
			</div>
			<div class="right">
				<span contenteditable="true" id="dimH">0.0</span> cm
			</div>
			<div class="left">
				Profondit&agrave;
			</div>
			<div class="right">
				<span contenteditable="true" id="dimL">0.0</span> cm
			</div>
			<div class="left">
				Durata (deteroriamento)
			</div>
			<div class="right">
				<span contenteditable="true" id="duration">0</span> giorni
			</div>
			<div class="left">
				<input type="checkbox" checked="checked" id="pacco_regalo" />Confezione regalo
			</div>
			<div class="right">
				&euro; <span contenteditable="true" id="regalo_p">0.0</span>
			</div>
			<div class="left">
				Peso
			</div>
			<div class="right">
				<span contenteditable="true" id="peso">0.0</span> kg
			</div>
			<div class="left">
				Categorie
			</div>
			<div class="right">
				<select class="cat"><option disabled selected>Scegli una categoria</option>'.$cats.'<option value="add">Aggiungi</option></select>
				<!--<p><a class="abutton" id="add_cat">Aggiungi un\'altra categoria</a></p>-->
			</div>
			<div class="left">&nbsp;</div>
			<div class="right"><br/><br/><a class="abutton special normal_save" id="prod_save">Salva</a><a class="abutton special special_save" id="prod_save_new">Salva come nuovo</a><a class="abutton special special_save" id="prod_save_over">Salva sul prodotto aperto</a></div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#prod_details">Dettagli Prodotto</a></li>
				<li><a href="#prod_shipments">Spedizioni</a></li>
				<li><a href="#prod_payments">Pagamenti</a></li>
			</ul>
			<div id="prod_details" contenteditable="true">
				Scrivi qui i dettagli del prodotto
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
			$content['html'] .= '<script>product_data = '.json_encode($prod_data).'; setTimeout("load_product()",600)</script>';
		} else
			$content['html'] = 'Prodotto inesistente';
	}
	SCRIPT::add('js/config.js');
	STYLE::add('css/style.css','ecommerce/');
} else {
	//Eliminazione Offerte
	if (isset($external['offer_del'])) {
		if (DB::delete('nxn__nc__productsxnc__sales_sxs',array('WHERE'=>array(array('nc__sales','=',$external['offer_del']))))&&DB::delete('nc__sales',array('WHERE'=>array(array('id','=',$external['offer_del'])))))
			echo 'Offerta Cancellata<br/>';
		else
			echo $GLOBALS['query'];
	}
	//Creazione offerte
	if (isset($external['new_offer'])) {
		if ($external['new_offer']['id']!=-1) {
			if (DB::update('nc__sales', array('sale' => $external['new_offer']['sale'],'start' => array('date' => strtotime($external['new_offer']['start'])),'end' => array('date' => strtotime($external['new_offer']['end']))),array('WHERE'=>array(array('id','=',$external['new_offer']['id']))))) 
				echo 'Offerta Modificata<br/>';
		} else {
			$sale_id = DB::insert('nc__sales', array('sale' => $external['new_offer']['sale'],'start' => array('date' => strtotime($external['new_offer']['start'])),'end' => array('date' => strtotime($external['new_offer']['end']))));
			if ($sale_id) {
				foreach ($external['prod'] as $v)
					DB::insert('nxn__nc__productsxnc__sales_sxs',array('nc__sales' => $sale_id,'nc__products' => $v));
				echo 'Offerta Inserita<br/>';
			}
		}
	}
	//Eliminazione prodotti
	if (isset($external['del'])) {
		if (DB::delete('nc__prices','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__translates','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__images','WHERE nc__products_ref = ',$external['del'])&&DB::delete('nc__products','WHERE id = ',$external['del']))
			echo 'Il prodotto &egrave; stato eliminato<br/><br/>';
		else
			echo 'Problemi nell\'eliminazione del prodotto<br/><br/>';
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
			} else echo 'Errore nella duplicazione<br/>';
		} else echo 'Errore nella duplicazione<br/>';
	}
	//Aggiungi prodotto
	SCRIPT::add('js/home.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/icons.css','ecommerce/');
	echo '<a class="com config_link abutton" href="ecommerce/config/products.php?add">Aggiungi Prodotto</a><a id="offer_add" class="abutton">Aggiungi Offerta</a><script type="text/javascript">$(".abutton").button();</script><br/><br/><table width="100%" cellpadding="0" cellspacing="0"><thead><tr><td>#</td><td>Nome</td><td>Categoria</td><td>Offerte</td><td></td></tr></thead>';
	//Lista prodotti
	$prods = DB::select(DB::$pre.'nc__products.id,'.DB::$pre.'nc__translates.name,'.DB::$pre.'nc__translatesC.name AS cat_name',array('nc__products','nc__translates','nc__translatesC'),'WHERE  `nc__products_ref` =  `'.DB::$pre.'nc__products`.id AND `'.DB::$pre.'nc__translatesC`.`nc__categories_ref` =  `'.DB::$pre.'nc__products`.`nc__categories_ref` GROUP BY id');
	function date_convert($d) {
		return date( 'd/m/y', strtotime($d));
	}
	while ($prod = DB::assoc($prods)) {
		echo '<tr><td>'.$prod['id'].'</td><td>'.$prod['name'].'</td><td>'.$prod['cat_name'].'</td><td>';
		//Offerte
		$sales = DB::select(array(array('nc__sales','*')),array('nxn__nc__productsxnc__sales_sxs','nc__sales'),array(
			'WHERE'=>array(
				'nc__products' => array('=',$prod['id']),
				'and',
				'nc__sales' => array('=','id','nxn__nc__productsxnc__sales_sxs','nc__sales')	
			)));
		while ($sale = DB::assoc($sales))
			echo '<a class="com config_link" href="ecommerce/config/products.php?edit_offer='.$sale['id'].'">'.$sale['sale'].'% ('.date_convert($sale['start']).' - '.date_convert($sale['end']).')</a> ';
		echo '</td><td><a class="img pr edit"></a> <a class="img del pr"></a> <a class="img pr double"></a></tr>';
	}
	echo '</table>';
}
?>