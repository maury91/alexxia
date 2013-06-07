<?php
 
// STEP 1: Read POST data
 
// reading posted data from directly from $_POST causes serialization 
// issues with array data in POST
// reading raw POST data from input stream instead. 
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
  $keyval = explode ('=', $keyval);
  if (count($keyval) == 2)
    $myPost[$keyval[0]] = urldecode($keyval[1]);
}
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc')) {
   $get_magic_quotes_exists = true;
} 
foreach ($myPost as $key => $value) {        
  if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
    $value = urlencode(stripslashes($value)); 
  else 
    $value = urlencode($value);
  $req .= "&$key=$value";
}


// STEP 2: Post IPN data back to paypal to validate

//$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
 
// In wamp like environments that do not come bundled with root authority certificates,
// please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
if( !($res = curl_exec($ch)) ) {
    // error_log("Got " . curl_error($ch) . " when processing IPN data");
    curl_close($ch);
    exit;
}
curl_close($ch);
 
 
// STEP 3: Inspect IPN validation result and act accordingly

if (strcmp ($res, "VERIFIED") == 0) {
  mail('maury91@gmail.com',$res,var_export($_POST,true));
  //Check invoice
  $invoice = DB::select('*','nc__orders','WHERE id = ',$_POST['invoice']);
  if ($invoice&&($invoice_data = DB::assoc($invoice))) {
    $currency = array('&euro;'=>'EUR');
    mail('maury91@gmail.com',$currency[CURRENCY],var_export($invoice_data,true));
    //Check the value
    if (strtolower($currency[CURRENCY])!=strtolower($_POST['mc_currency'])) {
      //Convert valuta
      $url = trim("https://svcs.sandbox.paypal.com/AdaptivePayments/ConvertCurrency");
      //Default App ID for Sandbox  
      $API_AppID = "APP-80W284485P519543T";
      $API_RequestFormat = "NV";
      $API_ResponseFormat = "NV";
      $bodyparams = array('requestEnvelope.errorLanguage' => 'en_US',
        'baseAmountList.currency(0).code' => $_POST['mc_currency'],
        'baseAmountList.currency(0).amount' => $_POST['mc_gross'],
        'convertToCurrencyList.currencyCode' => $currency[CURRENCY]);
      $body_data = http_build_query($bodyparams, "", chr(38));
      $resp='';
      include(__base_path.'com/ecommerce/payments/paypal/config/config.php');
      $params = array("http" => array(
        "method" => "POST",
        "content" => $body_data,
        "header" =>  "X-PAYPAL-SECURITY-USERID: " . $paypal_api_user . "\r\n" .
          "X-PAYPAL-SECURITY-SIGNATURE: " . $paypal_api_sign . "\r\n" .
          "X-PAYPAL-SECURITY-PASSWORD: " . $paypal_api_pass . "\r\n" .
          "X-PAYPAL-APPLICATION-ID: " . $API_AppID . "\r\n" .
          "X-PAYPAL-REQUEST-DATA-FORMAT: " . $API_RequestFormat . "\r\n" .
          "X-PAYPAL-RESPONSE-DATA-FORMAT: " . $API_ResponseFormat . "\r\n" 
        ));
      $resp .= var_export($params,true);
      $ctx = stream_context_create($params);
      $fp = @fopen($url, "r", false, $ctx);
      $response = stream_get_contents($fp);
      if ($response === false) 
        throw new Exception("php error message = " . "$php_errormsg");
      fclose($fp);
      //parse the ap key from the response
      $keyArray = explode("&", $response);
      foreach ($keyArray as $rVal){
        list($qKey, $qVal) = explode ("=", $rVal);
        $kArray[$qKey] = $qVal;
      }
      if ( $kArray["responseEnvelope.ack"] == "Success") 
        $price = $kArray['estimatedAmountTable.currencyConversionList(0).currencyList.currency(0).amount'];
      else {
        $resp .= 'ERROR Code: ' .  $kArray["error(0).errorId"] . "\n".'ERROR Message: ' .  urldecode($kArray["error(0).message"]) . " <br/>";
        $price = 0;
        mail('maury91@gmail.com',$res,$resp);
      }
    } else
      $price = $_POST['mc_gross'];
    //Controllo prezzo sufficiente
    if (floatval($invoice_data['total'])<=floatval($price)) {
      //Pagamento OK
      DB::update('nc__orders',array('status'=>1),' WHERE id = ',$invoice_data['id']);
      //Invio email all'utente
      
    } else {
      //Segna tutto
    }
  }
} else if (strcmp ($res, "INVALID") == 0) {
  // log for manual investigation
  mail('maury91@gmail.com',$res,var_export($_POST,true));
}
exit(0);
?>