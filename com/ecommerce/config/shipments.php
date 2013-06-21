<?php
include(__base_path.'com/ecommerce/config/lang/'.LANG::short().'.php');
if (isset($external['edit'])) {
	SCRIPT::add('js/shipment.js');
	echo '<a title="it" class="img flag it-IT" style="border-bottom: 2px solid #69F;"> </a><a title="en" class="img flag en-US"> </a>'.$__click_to_edit.'
	<br/>
	<h2 contenteditable="true">SDA</h2>
	<br/>
	<div class="ins_data">
		<div class="left">
			Suplemento carburante
		</div>
		<div class="right">
			<span id="spese_amm" contenteditable="true">6.75</span> %
		</div>
	</div>
	<div id="fasce">
		<h3><span class="min" contenteditable="true">0</span> - <span class="max" contenteditable="true">3</span> Kg</h3>
		<div class="ins_data">
			<div class="left">
				Costo
			</div>
			<div class="right">
				<span class="costo" contenteditable="true">6.00</span> &euro;
			</div>
			<div class="left">
				Zone disagiate
			</div>
			<div class="right">
				<span class="extra" contenteditable="true">1.70</span> &euro; [Scegli]
			</div>
		</div>
		<h3><span class="min" contenteditable="true">3</span> - <span class="max" contenteditable="true">10</span> Kg</h3>
		<div class="ins_data">
			<div class="left">
				Costo
			</div>
			<div class="right">
				<span class="costo" contenteditable="true">7.70</span> &euro;
			</div>
			<div class="left">
				Zone disagiate
			</div>
			<div class="right">
				<span class="extra" contenteditable="true">2.08</span> &euro; [Scegli]
			</div>
		</div>
		<h3 id="new_fascia">Nuova fascia</h3>
	</div>
	<div class="ins_data">
		<div class="left">
			Formula peso volumetrico
		</div>
		<div class="right">
			<span id="peso_vol" title="V=Volume,H=Altezza,D=Profodita\',W=Larghezza" contenteditable="true">V/4000</span>
		</div>
		<div class="left">
			<input type="checkbox" />Contrasegno
		</div>
		<div class="right">
			<span id="contrasegno" contenteditable="true">5</span> &euro;
		</div>
		<div class="left">
			Supplemento Colli
		</div>
		<div class="right">
			<span id="colli" contenteditable="true">0</span> &euro;
		</div>
		<div class="left">
			<input type="checkbox" />Assicurabile
		</div>
		<div class="right">
			<span id="ass_perc" contenteditable="true">0</span> % + <span id="ass_perc" contenteditable="true">10</span> &euro;
		</div>
		<div class="left">
			Tempo di consegna
		</div>
		<div class="right">
			<span id="cons_min" contenteditable="true">2</span> - <span id="cons_max" contenteditable="true">5</span> 
			<select>
				<option value="0">Ore</option>
				<option value="1" selected="selected">Giorni</option>
				<option value="2">Settimane</option>
			</select>
		</div>
	</div>
	';
	STYLE::add('css/style.css','ecommerce/');
} else {
	//Info di base
	STYLE::add('css/sales_data.css','ecommerce/');
	SCRIPT::add('js/shipments.js');
	echo '
	<div class="ins_data">
		<div class="left">
			Spese di Amministrazione
		</div>
		<div class="right">
			<span id="spese_amm" contenteditable="true">0</span> &euro;
		</div>
		<div class="left">
			Spedizione gratuita a partire da
		</div>
		<div class="right">
			<span id="spese_amm" contenteditable="true">-</span> &euro;
		</div>
		<div class="left">
			Spedizione gratuita a partire da
		</div>
		<div class="right">
			<span id="spese_amm" contenteditable="true">-</span> Kg
		</div>
		<div class="left">
			<a class="abutton">Salva modifiche</a>
		</div>
	</div>
	<br/><br/>
	<h3>Corrieri</h3>
	<a class="abutton">Nuovo corriere</a>
	<ul>
		<li><a class="com config_link" href="ecommerce/config/shipments.php?edit=SDA">SDA</a></li>
	</ul>';
	//Lista corrieri
}
?>