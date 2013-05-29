<?php
/*Come si gestisce un conto paypal? 

Gestione email associata
*/
include(__base_path.'com/ecommerce/payments/paypal/config/config.php');
SCRIPT::add('payments/paypal/js/config.js','ecommerce/');
STYLE::add('payments/paypal/css/config.css','ecommerce/');
echo '<div class="paypal_config">
<span class="left">Email paypal : </span><span class="right"><input type="text" id="paypal_email" value="'.$paypal_email.'"/><a class="abutton">Salva modifica</a></span>
<span class="left">Callback URL : </span><span class="right">'.__http.'com/ecommerce/pay_methods/paypal.html?ipn='.$ipn_code.'</span></div>';
?>