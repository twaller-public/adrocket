<?php
/*
Plugin Name: PayPal WPS
Description: PayPal WPS Functionality for CMS Builder
Version: 1.01
Required System Plugin: No
*/



/*
  Notes:
 
  In custom cart use: $GLOBALS['SC_ORDER_OVERRIDE_FUNCTION']  = 'payPalStandard_simpleCartCheckout';
  SimpleCart IPN function: payPalStandard_IPN_simpleCart();
  
*/

define("LOG_FILE", "./ipn.log");

$GLOBALS['PAYPAL_STANDARD']['LIVE_BUSINESS_ID']   = '';  // your PayPal ID or an email address associated with your PayPal account
$GLOBALS['PAYPAL_STANDARD']['IPN_URL']            = '';
$GLOBALS['PAYPAL_STANDARD']['IPN_DEBUG_IP']       = "";//'174.88.54.64'; // this IP is allowed to pretend to be PayPal when submitting IPNs
$GLOBALS['PAYPAL_STANDARD']['IPN_SECRET_KEY']     = ''; // Used to verify if an IPN if from us


$GLOBALS['PAYPAL_STANDARD']['DEBUG_EMAIL']        = ''; // this IP is allowed to pretend to be PayPal when submitting IPNs

$GLOBALS['PAYPAL_STANDARD']['CURRENCY']           = 'CAD';
$GLOBALS['PAYPAL_STANDARD']['COUNTRY']            = 'CA';

$GLOBALS['PAYPAL_STANDARD']['TEST_MODE']          = FALSE; // set to false to process live transactions
$GLOBALS['PAYPAL_STANDARD']['TEST_BUSINESS_ID']   = '';  // your PayPal ID or an email address associated with your PayPal account
//$GLOBALS['PAYPAL_STANDARD']['TEST_BUSINESS_ID']   = '';  // your PayPal ID or an email address associated with your PayPal account

/**********************DO NOT UPDATE ANYTHING BELOW THIS LINE********************************/

$GLOBALS['PAYPAL_STANDARD']['LIVE_PAYMENT_HOST'] = "www.paypal.com";
$GLOBALS['PAYPAL_STANDARD']['TEST_PAYMENT_HOST'] = "www.sandbox.paypal.com";

if($GLOBALS['PAYPAL_STANDARD']['TEST_MODE']) {
  $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'] = $GLOBALS['PAYPAL_STANDARD']['TEST_PAYMENT_HOST'];
  $GLOBALS['PAYPAL_STANDARD']['BUSINESS_ID'] = $GLOBALS['PAYPAL_STANDARD']['TEST_BUSINESS_ID'];
}
else {
  $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'] = $GLOBALS['PAYPAL_STANDARD']['LIVE_PAYMENT_HOST'];
  $GLOBALS['PAYPAL_STANDARD']['BUSINESS_ID'] = $GLOBALS['PAYPAL_STANDARD']['LIVE_BUSINESS_ID'];
}



/*
Process a PayPal Standard checkout
On success this function ends execution and sends the browser to PayPal.
list($payPalURL, $errorsAndAlerts) = payPalStandard_buildCheckoutURL(array(
  'items'         =>   $items      //REQUIRED - An array of items for purchase
                                    $items[0] => array(   'name'      => '',         //REQUIRED
                                                          'amount     => '0.00',     //REQUIRED - Item amount to be charged to 2 decimal places.  NO $ or commas
                                                          'quantity'  => '1')        //OPTIONAL - Default 1
  'shipping'      =>   '0.00'      //OPTIONAL - Total shipping amount to be charged to 2 decimal places.  NO $ or commas
  'custom'        =>   '',         //OPTIONAL - Passthrough data
  'tax'           =>   '0.00'      //OPTIONAL - Total tax amount to be charged to 2 decimal places.  NO $ or commas
  'paymentaction' =>   'sale',     //OPTIONAL - Type of transaction.  DEFAULT = sale.  Valid Values: sale, authorization
  'invoice'       =>   '',         //OPTIONAL - Invoice Number
  'description'   =>   '',         //OPTOINAL - Description of Transaction (Maximum of 255 characters)

));

*/
function payPalStandard_buildCheckoutURL($options) {
  // https://merchant.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
  $errors = '';
  $buyNowUrl= '';
  $requestParams = array();
  $options['type'] = coalesce(@$options['type'],'sale');

  $errors = _payPalStandard_getOptionErrors($options);  

  if(!$errors) {
    
    $business = $GLOBALS['PAYPAL_STANDARD']['BUSINESS_ID'];
    $buyNowUrl = "https://" . $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'] . "/cgi-bin/webscr";

    
    
    // build query
    //$customPassthroughData = sha1($GLOBALS['PAYPAL_STANDARD']['SECRET_KEY']) . ';' . $order['num'];
    $requestParams = array(
      'business'      => $business,
      'cmd'           => '_cart',      
      'upload'        => '1',
      'country'       => $GLOBALS['PAYPAL_STANDARD']['COUNTRY'],
      'currency_code' => $GLOBALS['PAYPAL_STANDARD']['CURRENCY'],
      'notify_url'    => $GLOBALS['PAYPAL_STANDARD']['IPN_URL'],
      
    // 'cancel_return' => realUrl($GLOBALS['SC_CART_LIST_URL']),                   // user is returned here after a cancelled transaction
    
    );
    
    
    
    $itemCount = 0;
    foreach($options['items'] as $item) {
      $itemCount++;
      $requestParams['item_name_' . $itemCount] = $item['name'];
      $requestParams['amount_' . $itemCount] = $item['amount'];
      $requestParams['quantity_' . $itemCount] = coalesce(@$item['quantity'],"1");
    }
    if(@$options['tax']) {
      $requestParams['tax_cart'] = $options['tax'];
    }
    if(@$options['shipping']) {
      $requestParams['handling_cart'] = $options['shipping'];
    }
    if(@$options['invoice']) {
      $requestParams['invoice'] = $options['invoice'];      
    }
    if(@$options['return']) {
      $requestParams['return'] = $options['return'];      
    }
	if(@$options['cancel_return']) {
      $requestParams['cancel_return'] = $options['cancel_return'];      
    }
    if(@$options['custom']) {
      $requestParams['custom'] = $options['custom'];      
    }
  

    $buyNowUrl .= '?' . http_build_query($requestParams);
  }
  
  return array($buyNowUrl,$errors);

}


function _payPalStandard_validateMoneyFormat($number) {

  return true;
  
}


// error check options
function _payPalStandard_getOptionErrors($options) {
  
  $requiredKeys = array('items');
  
  $errors                  = array();
  $validTransactionTypes   = array('sale', 'authorization');
  
  if (!in_array(@$options['type'], $validTransactionTypes)) {
    $errors[] = "Invalid Transaction Type ". $options['type']."! Valid options are ". join(', ', $validTransactionTypes);
  }
  
  foreach($requiredKeys as $requiredKey) {
    if(!@$options[$requiredKey]) {
      $errors[] = "Option: " . $requiredKey . " is required";
    }
  }
  
  foreach($options['items'] as $key => $item) {
    if(!@$item['name'])   { $errors[]   = " Item name missing for item " . $key; }
    if(!@$item['amount']) { $errors[]   = " Item amount missing for item " . $key; }
    
    if(!_payPalStandard_validateMoneyFormat($item['amount'])) {
       $errors[] = "Invalid Character in amount field for item " . $key;
    }
    
  }
  
  if(@$options['shipping']) {
    if(!_payPalStandard_validateMoneyFormat($options['shipping'])) {
       $errors[] = "Invalid Character in shipping";
    }
  }
  if(@$options['tax']) {
    if(!_payPalStandard_validateMoneyFormat($options['tax'])) {
       $errors[] = "Invalid Character in tax";
    }
  }
  
  return implode("\n<br>",$errors);
}



function payPalStandard_simpleCartCheckout() {

  //Taking care of SimpleCart tasks that we need to do because we are using an override function
  
  if ( $GLOBALS['SC_SHIPPING_ENABLED'] ) {
    _sc_applyBillingIsSameAsShippingCheckbox();
  }

  // require login
  if ( $GLOBALS['SC_LOGIN_REQUIRED'] && !@$CURRENT_USER ) {
    redirectBrowserToURL( $GLOBALS['WEBSITE_LOGIN_LOGIN_FORM_URL'] );
  }
  
  // look for an existing, unlocked order
  $where = "locked = '0' AND " . _sc_getOwnedByVisitorWhere();
  $order = mysql_get('_sc_orders', null, $where);

  // create or update the order (tally or re-tally totals and extra line items)
  $order = sc_createOrUpdateOrder($order);

  // if there are no cart items, redirect back to cart list
  if (!sc_getCartItemCount(@$existingOrder['num'])) { redirectBrowserToURL($GLOBALS['SC_CART_LIST_URL']); }


  //If the user doesn't want to process yet just return
  if(!@$_REQUEST['process_order']) {
    return;
  }

  //Simple Cart validation task
  $errorsAndAlerts = _sc_validateOrderRequest($order);
  if ($errorsAndAlerts) { return $errorsAndAlerts; }


  list($grandTotal, $subTotal, $extraLineItems, $cartItems) = sc_tallyCart($order['num']);


  $payPalItems = array();
  foreach($cartItems as $item) {
    $payPalItems[] = array( 'name'      =>  $item['name'],
                            'amount'    =>  coalesce($item['unitPrice'],'0.00'),
                            'quantity'  =>  $item['quantity']);
  }


  $payPalParams['items'] = $payPalItems;
  if(@$extraLineItems['tax']) {
    $payPalParams['tax'] = $extraLineItems['tax']['TOTAL'];
  }
  if(@$extraLineItems['shipping']) {
    $payPalParams['shipping'] = $extraLineItems['shipping']['TOTAL'];
  }
  $payPalParams['custom'] = sha1($GLOBALS['SC_SECRET_KEY']) . ";" . $order['num'];

  
  list($payPalURL,$errors) = payPalStandard_buildCheckoutURL($payPalParams);
                                                      

  if(!$payPalURL) {
    return " There was a redirecting to PayPal. :" . $errors;
  }

//echo $payPalURL;die();

  // Everything looks good so lock it!
  sc_orderLock($order['num']);
  
  
  
  redirectBrowserToURL($payPalURL);
  die();
  
}


function payPalStandard_IPN_simpleCart() {
  sc_log(0, 'sc_paypalIPN()', $_REQUEST);
  $GLOBALS['IGNORE_ORDER_OWNER'] = true;

  // set globals
  global $PAYPAL_HOST;
  $PAYPAL_HOST = $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'];
  $IS_DEBUG_IP = ($_SERVER['REMOTE_ADDR'] == @$GLOBALS['IPN_DEBUG_IP']); // Allow us to bypass security checks and insert an order for testing

  // UTF-8 Encode Incoming Data - Otherwise we get errors when trying to insert into database on non-english chars
  // This can be set to UTF8 at: Paypal > My Account > Profile > Language Encoding > More Options > Encoding > UTF-8
  if (@$_REQUEST['charset'] == 'windows-1252') { // we receive this charset from paypal
    mb_convert_variables('UTF-8', 'Windows-1252', $_REQUEST); // CP1252, ASCII, ISO-8859-1 - More: http://php.net/manual/en/mbstring.supported-encodings.php
  }

  // verify order sent by paypal
  //if (!payPalStandard_IPN_verify()) { die("Not a valid PayPal IPN request!"); }

  // extract data from 'custom' passthrough field
  @list($handshake, $orderNum) = explode(';', @$_REQUEST['custom']);
  

  // verify order was initiated by this software
  if ($handshake != sha1($GLOBALS['SC_SECRET_KEY'])) { payPalStandard_IPN_debugEmail("Not our order - PayPal 'custom' field prefix doesn't match!"); }

  // security checks
  $errors = payPalStandard_IPN_getSecurityCheckErrors($orderNum);
  if ($errors) { payPalStandard_IPN_debugEmail("Security Check Errors", $errors);die(); }

  // log this IPN
  sc_log($orderNum, 'paypal_ipn', $_REQUEST);

  // process order

  // set extra order fields
  $addressLines = explode("\r\n", @$_REQUEST['address_street']);
  mysql_update('_sc_orders', $orderNum, null, array(
    'updatedDate'     => mysql_datetime(),

    'payment_method'  => 'PayPal WPS',
    'transactionId'   => @$_REQUEST['txn_id'],
    'transactionData' => payPalStandard_IPN_getValuesFromRequest(),

    'billing_first_name'      => @$_REQUEST['first_name'],
    'billing_last_name'       => @$_REQUEST['last_name'],
    'billing_address_line_1'  => @$addressLines[0],
    'billing_address_line_2'  => @$addressLines[1],
    'billing_city'            => @$_REQUEST['address_city'],
    'billing_province'        => @$_REQUEST['address_state'],
    'billing_postal_code'     => @$_REQUEST['address_zip'],
    'billing_country'         => @$_REQUEST['address_country'], // or address_country_code
    'billing_email'           => @$_REQUEST['payer_email'],
  ));

  // mark order as paid
  sc_orderPaid($orderNum);

  doAction('sc_order_complete_cc', $orderNum);

  //
  print "done";
  exit;
}


function payPalStandard_IPN_custom() {
  $GLOBALS['IGNORE_ORDER_OWNER'] = true;

  // set globals
  global $PAYPAL_HOST;
  $PAYPAL_HOST = $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'];
  $IS_DEBUG_IP = ($_SERVER['REMOTE_ADDR'] == @$GLOBALS['IPN_DEBUG_IP']); // Allow us to bypass security checks and insert an order for testing

  // UTF-8 Encode Incoming Data - Otherwise we get errors when trying to insert into database on non-english chars
  // This can be set to UTF8 at: Paypal > My Account > Profile > Language Encoding > More Options > Encoding > UTF-8
  if (@$_REQUEST['charset'] == 'windows-1252') { // we receive this charset from paypal
    mb_convert_variables('UTF-8', 'Windows-1252', $_REQUEST); // CP1252, ASCII, ISO-8859-1 - More: http://php.net/manual/en/mbstring.supported-encodings.php
  }

  // verify order sent by paypal
  if (!payPalStandard_IPN_verify()) { die("Not a valid PayPal IPN request!"); }
 

  //ORDER COMPLETED LOGIC GOES HERE
  
  
  //LOGGING POST DATA FOR DEBUGGING
  $res = "";
  $post = $_POST;
  foreach($post as $k=>$p) $res .= $k . " => " . $p . "\n";
  error_log(date('[Y-m-d H:i e] '). "PURCHASE INFO:\n" . $res . PHP_EOL, 3, LOG_FILE);
  
  $sc_num        = @$_POST['custom'];
  $record_exists = false;
  
  //debugging
  $debug_res  = "";
  $debug_res .= "Purchase Processing for: \n";
  $debug_res .= "CAMPAIGN # $sc_num \n";
  

  error_log("\n\n\n" . date('[Y-m-d H:i e] '). "COMPLETED PURCHASE INFO:\n" . $debug_res . PHP_EOL, 3, LOG_FILE);
  
  exit;
  
  
  
}



function payPalStandard_IPN_verify() {
  $PAYPAL_HOST = $GLOBALS['PAYPAL_STANDARD']['PAYMENT_HOST'];
  
  if($_SERVER['REMOTE_ADDR'] == @$GLOBALS['PAYPAL_STANDARD']['IPN_DEBUG_IP']) {
    return true;
  }

  // create paypal verify request (contains all received data and new cmd on next line)
  $query = http_build_query( array('cmd' => '_notify-validate') + $_REQUEST ); // read the post from PayPal system and add 'cmd'
  $url   = "https://$PAYPAL_HOST/cgi-bin/webscr?$query";

  // get paypal response
  list($response,, $responseHeader, $request) = getPage($url, 10, null, 'POST');
  if (!$response) {
    payPalStandard_IPN_debugEmail("HTTP ERROR SENDING REQUEST", "Response:\n$response\n\nResponse Header:\n$responseHeader");
    return false;
  }
  // valid response
  if (strcmp($response, 'VERIFIED') == 0) {
    return true;
  }

  // invalid response
  elseif (strcmp($response, 'INVALID') == 0) {
    // send debugging email - log for manual investigation
    payPalStandard_IPN_debugEmail("INVALID IPN", "Response:\n$response\n\nResponse Header:\n$responseHeader");
    return false;
  }

  // unknown response
  else {
    // send debugging email - log for manual investigation
    payPalStandard_IPN_debugEmail("UNKNOWN RESPONSE", "Response:\n$response\n\nResponse Header:\n$responseHeader");
    return false;
  }

  return false;
}

function payPalStandard_IPN_getSecurityCheckErrors($orderNum) {
  $errors = '';

  // get order
  $order = mysql_get('_sc_orders', $orderNum);
  if (!$order) { $errors .= "Couldn't find order '" . htmlspecialchars($orderNum) . "'\n"; }

  // check the payment_status is Completed
  if (@$_REQUEST['payment_status'] != 'Completed') { $errors .= "Payment Status != Complete\n"; }

  // check that receiver_email is your Primary PayPal email
  $isReceiverEmailOurs = @$_REQUEST['receiver_email'] == $GLOBALS['PAYPAL_STANDARD']['BUSINESS_ID'];
  if (!$isReceiverEmailOurs) { $errors .= "receiver_email not {$GLOBALS['PAYPAL_STANDARD']['BUSINESS_ID']}!\n"; }

  // check that payment_currency is correct
  $isCorrectCurrency = @$_REQUEST['mc_currency'] == $GLOBALS['PAYPAL_STANDARD']['CURRENCY'];
  if (!$isCorrectCurrency) { $errors .= "Currency is '".@$_REQUEST['mc_currency']."' not {$GLOBALS['PAYPAL_STANDARD']['CURRENCY']}!\n"; }

  // check that payment_amount is correct
  $receivedAmount = @$_REQUEST['mc_gross'];
  if ($receivedAmount != $order['grandTotal']) {
    $errors .= "Customer paid '$receivedAmount' for order #'" .@$_REQUEST['invoice']. "', but actual price is '{$order['grandTotal']}'!\n";
  }

  //
  if ($errors) { print $errors; }
  return $errors;
}


function payPalStandard_IPN_debugEmail($subject, $body = '') {
  if (!@$GLOBALS['PAYPAL_STANDARD']['DEBUG_EMAIL']) { echo "no";return; }
echo "yes";
  //
  $from    = $GLOBALS['PAYPAL_STANDARD']['DEBUG_EMAIL'];
  $to      = $GLOBALS['PAYPAL_STANDARD']['DEBUG_EMAIL'];
  $subject = "{$_SERVER['HTTP_HOST']} IPN DEBUGGING - $subject";

  // add request values
  $body .= "\n\nIPN Request Values:\n" . payPalStandard_IPN_getValuesFromRequest();

  $body .= "\n\nRecreate Link: paypal_ipn.php?" . http_build_query($_REQUEST);
  
  error_log(date('[Y-m-d H:i e] '). "INVALID IPN: " . $subject . " -- " . $body . PHP_EOL, 3, LOG_FILE);
  
  // send message
  sendMessage(array(  'from'    => $from,
                      'to'      => $to,
                      'subject' => $subject,
                      'text'    => $body));
}

function payPalStandard_IPN_getValuesFromRequest() {
  ksort($_REQUEST);

  $ipnValues = '';
  foreach ($_REQUEST as $key => $value) {
    $ipnValues .= "$key: $value\n";
  }

  return $ipnValues;
}


