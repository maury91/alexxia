<?php
//Aggiungi ricetta
//Lista ricette esistenti
if (isset($external['show_cats'])) {
	$cats = DB::select(DB::$pre.'nc__translatesC.name,'.DB::$pre.'nc__categories.*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref IS NULL AND lang = ',LANG::short());
	$content = array('cats' => array());
	while($c = DB::assoc($cats)) {
		$cat = array('id' => $c['id'],'name' => $c['name'],'subs' => array());
		$subcat = DB::select('*',array('nc__categories','nc__translatesC'),'WHERE '.DB::$pre.'nc__translatesC.nc__categories_ref = '.DB::$pre.'nc__categories.id AND '.DB::$pre.'nc__categories.nc__categories_ref = ',$c['id'],' AND lang = ',LANG::short());
		while ($d = DB::assoc($subcat))
			$cat['subs'][] = array('id' => $d['id'],'name' => $d['name']);
		$content['cats'][] = $cat;
	}

} elseif (isset($external['category'])) {
	$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`',array('nc__products','nc__translates'),'WHERE  `nc__categories_ref` IN ((SELECT id FROM  `'.(DB::$pre).'nc__categories`  WHERE  `nc__categories_ref` = ',$external['category'],'),',$external['category'],')  AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' GROUP BY id');
	$content = array('prods' => array());
	while ($pr = DB::assoc($prods)) 
		$content['prods'][] = array('id' => $pr['id'],'name' =>$pr['name']);
} elseif (isset($external['add_recipe'])) {
	$content = array('input' => $external);
	if (isset($external['edit'])) {
		if (DB::delete('nc__translatesR','WHERE nc__recipes_ref = ',$external['edit'])&&DB::delete('nc__r_images','WHERE nc__recipes_ref = ',$external['edit'])&&DB::delete('NxN__nc__recipesxnc__products_sxs','WHERE nc__recipes = ',$external['edit'])&&DB::update('nc__recipes',array('difficulty'=>$external['add_recipe']['difficulty'],'tempo'=>$external['add_recipe']['tempo']),'WHERE id = ',$external['edit']))
			$recipe_id = $external['edit'];
	} else
		$recipe_id = DB::insert('nc__recipes',array('difficulty'=>$external['add_recipe']['difficulty'],'tempo'=>$external['add_recipe']['tempo']));
	if ($recipe_id) {
		//Collegamento traduzioni
		foreach ($external['add_recipe']['langs'] as $k => $v)
			DB::insert('nc__translatesR',array('nc__recipes_ref'=>$recipe_id,'name'=>strip_tags($v['name']),'ingredients'=>$v['ingredients'],'preparation'=> $v['preparation'],'lang' => $k));
		//Collegamento prodotti
		foreach ($external['add_recipe']['prods'] as $v)
			DB::insert('NxN__nc__recipesxnc__products_sxs',array('nc__products' => $v,'nc__recipes' => $recipe_id));		
		//Collegamento immagini
		foreach ($external['add_recipe']['images'] as $v)
			DB::insert('nc__r_images',array('nc__recipes_ref'=>$recipe_id,'url'=>$v));
		$content = array('r' => 'y', 'id' => $recipe_id);
	} else
		$content = array('r' => 'n','err' => DB::error());
} elseif (isset($external['add'])||isset($external['edit'])) {
	$media_id = MEDIA_MAN::make('./media',array('png','jpg'), true, true, true, true, true, true, '', '',true);	
	$content['html'] = '
	<a title="it-IT" class="img flag it-IT" style="border-bottom: 2px solid #69F;"> </a><a title="en-US" class="img flag en-US"> </a>
	Clicca sui campi per modificarli
	<div class="product_info">
		<div class="images">
			<div class="image"></div>
			<div class="thumbs"></div>
			<a class="abutton" style="clear: both;" onclick=\'media_manager({uid : "'.$media_id.'", onSelected : choose_img, dir : "./media/images/",base_path : "'.__http.'"})\'>Aggiungi</a>
		</div>
		<div class="scheda">
			<h1 contenteditable="true" id="recip_name">Nome Ricetta</h1>
			<div class="left">Difficolt&agrave; di preparazione</div>
			<div class="right"><select id="difficulty"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value=5">5</option></select></div>
			<div class="left">Tempo di preparazione</div>
			<div class="right"><select id="time"><option value="15">Meno di 15 minuti</option><option value="30">15-30 minuti</option><option value="60">30-60 minuti</option><option value="90">1 ora - 1 ora e mezzo</option><option value="120">1 ora e mezzo - 2 ore</option><option value="180">2-3 ore</option><option value="181">Pi&ugrave di 3 ore</option></select></div>
			<h3>Ingredienti</h3>
			<div id="recipe" contenteditable="true">
				Ingredienti della ricetta
			</div>
			<div class="left">&nbsp;</div>
			<div class="right"><br/><br/><a class="abutton special normal_save" id="recipe_save">Salva</a><a class="abutton special special_save" id="recipe_save_new">Salva come nuovo</a><a class="abutton special special_save" id="recipe_save_over">Salva sul prodotto aperto</a></div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#recip_preparation">Preparazione</a></li>
				<li><a href="#recip_buy">Compra Ingredienti</a></li>
			</ul>
			<div id="recip_preparation" contenteditable="true">
				Scrivi qui la ricetta
			</div>
			<div id="recip_buy">
				<a class="abutton">Collega prodotto</a>
				<ul id="recip_prods">
				</ul>
			</div>
		</div>
		<div id="recipe_products_win">
		</div>
		<script>
			$(\'.ale_bar\').tabs();
		</script>
	</div>';
	SCRIPT::add('js/config.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/style.css');
	if (isset($external['edit'])) {
		$recipe = DB::select('*','nc__recipes','WHERE id = ',$external['edit']);
		if ($recipe) {
			$recipe_data = DB::assoc($recipe);
			$prods = DB::select('`'.(DB::$pre).'nc__products`.id,`'.(DB::$pre).'nc__translates`.`name`',array('NxN__nc__recipesxnc__products_sxs','nc__products','nc__translates'),'WHERE nc__products = '.(DB::$pre).'nc__products.id AND nc__recipes = ',$recipe_data['id'],' AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' GROUP BY id');
			$recipe_data['prods'] = array();
			while ($prod_data = DB::assoc($prods))
				$recipe_data['prods'][] = $prod_data;
			$recipe_data['langs'] = array();
			$trads = DB::select('*','nc__translatesR','WHERE nc__recipes_ref = ',$recipe_data['id']);
			while ($trad = DB::assoc($trads))
				$recipe_data['langs'][] = $trad;
			$recipe_data['images'] = array();
			$images = DB::select('*','nc__r_images','WHERE nc__recipes_ref = ',$recipe_data['id']);
			while ($image = DB::assoc($images))
				$recipe_data['images'][] = $image;
			$content['html'] .= '<script>recipe_data = '.json_encode($recipe_data).'; setTimeout("load_recipe()",600)</script>';
		} else
			$content['html'] = 'Ricetta inesistente';
	}
} else {
	//Eliminazione ricette
	if (isset($external['del'])) {
		if (DB::delete('nc__translatesR','WHERE nc__recipes_ref = ',$external['del'])&&DB::delete('nc__r_images','WHERE nc__recipes_ref = ',$external['del'])&&DB::delete('NxN__nc__recipesxnc__products_sxs','WHERE nc__recipes = ',$external['del'])&&DB::delete('nc__recipes','WHERE id = ',$external['del']))
			echo 'La ricetta &egrave; stata eliminata<br/><br/>';
		else
			echo 'Problemi nell\'eliminazione della ricetta<br/><br/>';
	}
	//Aggiungi ricetta
	SCRIPT::add('js/home.js');
	STYLE::add('css/style.css','ecommerce/');
	STYLE::add('css/icons.css','ecommerce/');
	echo '<a class="com config_link abutton" href="recipes/config/recipes.php?add">Aggiungi Ricetta</a><script type="text/javascript">$(".abutton").button();</script><br/><br/><table width="100%" cellpadding="0" cellspacing="0"><thead><tr><td>#</td><td>Nome</td><td></td></tr></thead>';
	$ricette = DB::select((DB::$pre).'nc__recipes.*,`'.(DB::$pre).'nc__translatesR`.`name`,url as image',array('nc__recipes','nc__r_images','nc__translatesR'),'WHERE `'.(DB::$pre).'nc__translatesR`.nc__recipes_ref = '.(DB::$pre).'nc__recipes.id AND `'.(DB::$pre).'nc__r_images`.nc__recipes_ref = '.(DB::$pre).'nc__recipes.id GROUP BY id');
	while ($recipe = DB::assoc($ricette)) {
		echo '<tr><td>'.$recipe['id'].'</td><td>'.$recipe['name'].'</td><td><a title="Modifica ricetta" class="img pr edit"></a> <a title="Elimina ricetta" class="img del pr"></a></tr>';
	}
	echo '</table>';
}
?>