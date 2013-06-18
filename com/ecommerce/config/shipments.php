<?php
include(__base_path.'com/ecommerce/config/lang/'.LANG::short().'.php');
if (isset($external['pay_config'])) {
	$__pay = DB::assoc(DB::select(array(array('nc__payments','*'),array('nc__translatesP','name')),array('nc__payments','nc__translatesP'),' WHERE lang = ',LANG::short(),' AND nc__payments_ref = '.DB::$pre.'nc__payments.id AND '.DB::$pre.'nc__payments.id = ',$external['pay_config']));
	include(__base_path.'com/ecommerce/payments/'.$__pay['UNI_ID'].'/config.php');
} else {
	//Info di base
	echo '<div class="left">
			Spese di Amministrazione
		</div>
		<div class="right">
			<span id="spese_amm">0</span>&euro;
		</div>
		<div class="left">
			Spedizione gratuita a partire da
		</div>
		<div class="right">
			<span id="spese_amm">-</span>&euro;
		</div>
		<div class="left">
			Spedizione gratuita a partire da
		</div>
		<div class="right">
			<span id="spese_amm">-</span>Kg
		</div>';
	//Lista corrieri
}
?>