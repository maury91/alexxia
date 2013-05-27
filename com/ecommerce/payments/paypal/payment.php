<?php
$currency = array('&euro;'=>'EUR');
include(__base_path.'com/ecommerce/payments/paypal/config/config.php');
$html = '<form action="https://www.paypal.com/cgi-bin/webscr" id="redir_paypal" method="post">
<input type="hidden" name="cmd" value="_cart">
<input type="hidden" name="upload" value="1">
<input type="hidden" name="business" value="'.$paypal_email.'">
<input type="hidden" name="currency_code" value="'.$currency[CURRENCY].'">
<input type="hidden" name="invoice" value="'.$invoice.'">
<input type="hidden" name="shipping" value="1.00">
<input type="hidden" name="no_shipping" value="1">';
$i=0;
foreach($_SESSION['nc_cart'] as $k => $v) {
	$i++;
	$html .= '<input type="hidden" name="item_name_'.$i.'" value="'.$v['name'].'">
<input type="hidden" name="amount_'.$i.'" value="'.$v['price'].'">
<input type="hidden" name="quantity_'.$i.'" value="'.$v['tot'].'">
<input type="hidden" name="item_number_'.$i.'" value="'.$k.'">';
}
$html .= '</form>';
$js[] = 'com/ecommerce/payments/paypal/js/load.js';
?>