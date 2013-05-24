<?php
if (GET::exists('show')) {
	HTML::add_style('com/ecommerce/css/style.css','com/recipes/css/images.css');
	HTML::add_script('com/ecommerce/js/script.js');
	$recipe = DB::select((DB::$pre).'nc__recipes.*,`'.(DB::$pre).'nc__translatesR`.`name`,`'.(DB::$pre).'nc__translatesR`.`ingredients`,`'.(DB::$pre).'nc__translatesR`.`preparation`',array('nc__recipes','nc__translatesR'),'WHERE nc__recipes_ref = '.(DB::$pre).'nc__recipes.id AND '.(DB::$pre).'nc__recipes.id = ',GET::val('show'));
	if ($recipe) {
		$recipe = DB::assoc($recipe);
		echo '<div class="product_info">
		<div class="images">
			<div class="image"></div>
			<div class="thumbs">';
		$images = DB::select('url','nc__r_images','WHERE nc__recipes_ref = ',$recipe['id']);
		while ($img=DB::assoc($images))
			echo '<div class="thumb" style="background-image:url('.$img['url'].')"></div>';
		echo '</div>
		</div>
		<div class="scheda">
			<h1>'.$recipe['name'].'</h1>
			<div class="left">Difficolt&agrave; di preparazione</div>
			<div class="right">';
		for ($i=0;$i<$recipe['difficulty'];$i++)
			echo '<a class="img hat"></a>';
		echo '</div>
			<div class="left">Tempo di preparazione</div>
			<div class="right">circa '.$recipe['tempo'].' minuti</div>
			<h3>Ingredienti</h3>
			<div id="recipe">
				'.$recipe['ingredients'].'
			</div>
		</div>
		<div class="ale_bar">
			<ul>
				<li><a href="#recip_preparation">Preparazione</a></li>
				<li><a href="#recip_buy">Compra Ingredienti</a></li>
			</ul>
			<div id="recip_preparation">
				'.$recipe['preparation'].'
			</div>
			<div id="recip_buy"><ol class="list">';
		//Lista prodotti
		if (USER::logged()) 
			$u_type = (USER::data('nc_cat')==0)? 1 : intval(USER::data('nc_cat'));
		else
			$u_type = 1;
		$prods = DB::select('`'.(DB::$pre).'nc__products`.*,`url` as `image`,`'.(DB::$pre).'nc__translates`.`name`,`'.(DB::$pre).'nc__translates`.`descrizione`,`'.(DB::$pre).'nc__prices`.`price`',array('NxN__nc__recipesxnc__products_sxs','nc__products','nc__images','nc__translates','nc__prices'),'WHERE nc__products = '.(DB::$pre).'nc__products.id AND nc__recipes = ',$recipe['id'],' AND '.(DB::$pre).'nc__images.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND '.(DB::$pre).'nc__translates.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND lang = ',LANG::short(),' AND '.(DB::$pre).'nc__prices.`nc__products_ref` = `'.(DB::$pre).'nc__products`.`id` AND nc__categoriesU_ref = ',$u_type,' AND q_min<2 GROUP BY id');
		while ($pr = DB::assoc($prods)) {
			echo '<a href="com_ecommerce.html?show='.$pr['id'].'"><li><span class="title">'.$pr['name'].'</span><div class="image" style="background-image:url('.$pr['image'].')"><div class="stars">';
			for ($i=0;$i<intval($pr['stars']);$i++)
				echo '<span class="on">';
			for ($i=intval($pr['stars']);$i<5;$i++)
				echo '<span class="off">';
			$desc = substr(strip_tags($pr['descrizione']),0,40);
			$desc = substr($desc,0,strrpos($desc,' '));
			echo '</div></div><div class="desc">'.$desc.'</div><span class="price">'.$pr['price'].' &euro;</span></li></a>';
		}
		echo '</ol></div>
		</div>
		<script type="text/javascript">
			$(\'.ale_bar\').tabs();
			$(\'.abutton\').button();
		</script>
	</div>';
	} else echo 'Prodotto non trovato!';
} else {
	//Lista ricette
	HTML::add_style('com/recipes/css/home.css','com/recipes/css/images.css');
	$ricette = DB::select((DB::$pre).'nc__recipes.*,`'.(DB::$pre).'nc__translatesR`.`name`,url as image',array('nc__recipes','nc__r_images','nc__translatesR'),'WHERE `'.(DB::$pre).'nc__translatesR`.nc__recipes_ref = '.(DB::$pre).'nc__recipes.id AND `'.(DB::$pre).'nc__r_images`.nc__recipes_ref = '.(DB::$pre).'nc__recipes.id GROUP BY id');
	echo '<ul class="recipes">';
	while($ric = DB::assoc($ricette)) {
		echo '<li><a href="com/recipes/show/'.$ric['id'].'-'.urlencode($ric['name']).'.html"><span class="image" style="background-image:url('.$ric['image'].')"></span><span class="title2"><h2>'.$ric['name'].'</h2></span></a>';
		
		
		
							echo '<div class="scheda">
								<div class="diff_left">Difficolt&agrave; </div>
								<div class="diff_right">';
							for ($i=0;$i<$ric['difficulty'];$i++)
								echo '<a class="img hat"></a>';
							echo '</div>
								<div class="time_left">Tempo:  </div>
								<div class="time_right">circa '.$ric['tempo'].' minuti</div></li>';
		
		
	}
	echo '</ul>';
}
?>