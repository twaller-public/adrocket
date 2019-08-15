<?php
/*
Plugin Name: Bambora Payment processor
Description: Take payments using Bambora
Author: Greg Thomas - modified from Beanstream to Bambora by Tom Waller
Version: 1.0
Required System Plugin: False
*/

$GLOBALS['BAMBORA_TEST_MODE']            = true;//($GLOBALS['SETTINGS']['licenseDomainName'] == 'docmitto.com.cmsb.me')? true : false; //IMPORTANT!!! First variable after the if statement will is the setting for the test server, the second variable is for the live server. 

//TEST SETTINGS
$GLOBALS['BAMBORA_TEST_MERCHANT_ID']     = '300204573'; //Created on sign up.
$GLOBALS['BAMBORA_TEST_API_ACCESS_CODE'] = '04a11d86EE1445e48e610E3b65a4697c'; //To Find: Log into your account and Then navigate to Administration -> Account -> Order Settings






//LIVE SETTINGS
$GLOBALS['BAMBORA_LIVE_MERCHANT_ID_CDN']     = '348800000'; //Created on sign up.
$GLOBALS['BAMBORA_LIVE_API_ACCESS_CODE_CDN'] = 'C16CE0A94239429E9260631F09651496'; //To Find: Log into your account and Then navigate to Administration -> Account -> Order Settings

$GLOBALS['BAMBORA_LIVE_MERCHANT_ID_US']     = '348810000'; //Created on sign up.
$GLOBALS['BAMBORA_LIVE_API_ACCESS_CODE_US'] = '13E5F46c718c483481EA62143aCCcB83'; //To Find: Log into your account and Then navigate to Administration -> Account -> Order Settings


$GLOBALS['BAMBORA_TOKEN_URL']            = "https://api.na.bambora.com/scripts/tokenization/tokens";
$GLOBALS['BAMBORA_API_URL']              = "https://api.na.bambora.com/v1/payments";


/*

	Test numbers
	============

*/




function bambora_takeAPayment($paymentDetails){

	$errorsAndAlerts = "";
	$transactionId   = "";

	//Carry out error checking.
	if(!@$paymentDetails['order_number'])              { $errorsAndAlerts .= "No Order number found<br>\r\n"; }
	if(!@$paymentDetails['amount'])                    { $errorsAndAlerts .= "No amount found.<br>\r\n"; }
	if(!@$paymentDetails['name'])                      { $errorsAndAlerts .= "No name found.<br>\r\n"; }
	if(!@$paymentDetails['number'])                    { $errorsAndAlerts .= "No card number found.<br>\r\n"; }
	if(!ctype_digit(@$paymentDetails['number']))       { $errorsAndAlerts .= "Card Number must be numeric.\r\n"; }
	if(!@$paymentDetails['expiry_month'])              { $errorsAndAlerts .= "No expiry month found.<br>\r\n"; }
	if(!ctype_digit(@$paymentDetails['expiry_month'])) { $errorsAndAlerts .= "Expiry month must be numeric.\r\n"; }
	if(!@$paymentDetails['expiry_year'])               { $errorsAndAlerts .= "No expiry year found.<br>\r\n"; }
	if(!ctype_digit(@$paymentDetails['expiry_year']))  { $errorsAndAlerts .= "Expiry year must be numeric.\r\n"; }
	if(!@$paymentDetails['cvd'])                       { $errorsAndAlerts .= "No CCV found.<br>\r\n"; }
	if(!ctype_digit(@$paymentDetails['cvd']))          { $errorsAndAlerts .= "CCV must be numeric.\r\n"; }
	//if(!isset($paymentDetails['is_CAN_currency']))     { $errorsAndAlerts .= "You Must Enter a Country.\r\n"; }
	
	//billing address validation
	if(!@$paymentDetails["billing_address"])           { $errorsAndAlerts .= "No Billing Address found<br>\r\n"; }
	
	//return $errorsAndAlerts;  //REMOVE ME
	
	if(!$errorsAndAlerts){

		//Create the security code we need to pass
		/* twaller : added the merchant_account variable which determines the merchant account into which to deposit the funds from the sale.*/
		$merchant_account = "{$GLOBALS['BAMBORA_LIVE_MERCHANT_ID_CDN']}:{$GLOBALS['BAMBORA_LIVE_API_ACCESS_CODE_CDN']}";
		$securityCodePreEncoding = ($GLOBALS['BAMBORA_TEST_MODE'])? "{$GLOBALS['BAMBORA_TEST_MERCHANT_ID']}:{$GLOBALS['BAMBORA_TEST_API_ACCESS_CODE']}" : $merchant_account;
		$passcode = base64_encode($securityCodePreEncoding);
		
		$billing = $paymentDetails["billing_address"];


		//json encode all ofthe variables we need to pass.
		foreach($paymentDetails as $key => $field){
			$paymentDetails[$key] = json_encode($field);
		}
		
		//json encode all ofthe variables we need to pass.
		foreach($billing as $key => $field){
			$billing[$key] = json_encode($field);
		}
		
		//Tokenize the card information
		$jsonTokenize = "{
			\"number\":{$paymentDetails['number']},
		    \"expiry_month\":{$paymentDetails['expiry_month']},
		    \"expiry_year\":{$paymentDetails['expiry_year']},
		    \"cvd\":{$paymentDetails['cvd']}
		}";
		
		//Create the headers we need to send.
		$headers = array(
			'Content-Type' => 'application/json',
			'Authorization' => "Passcode $passcode"
		);

		//Carry out the request.
		list($returnedData, $httpStatusCode, $header, $request) = getPage($GLOBALS['BAMBORA_TOKEN_URL'] . "?", 5, $headers, true, $jsonTokenize);
		
		//Convert the returned json into an array.
		$dataArray = json_decode($returnedData, true);
		$token     = json_encode($dataArray["token"]);


		
		$jsonPayload = "{
		  \"order_number\":{$paymentDetails['order_number']},
		  \"amount\": {$paymentDetails['amount']},
		  \"payment_method\":\"token\",
		  \"token\": {
			  \"name\": {$paymentDetails['name']},
			  \"code\": $token
		  },
		  \"billing\":{
			\"name\":{$billing['name']},
			\"address_line1\":{$billing['address_line1']},
			\"address_line2\":{$billing['address_line2']},
			\"city\":{$billing['city']},
			\"province\":{$billing['province']},
			\"country\":{$billing['country']},
			\"postal_code\":{$billing['postal_code']},
			\"phone_number\":{$billing['phone_number']},
			\"email_address\":{$billing['email_address']},
		  },
		}";
		
		
		//echo $jsonPayload;

		//Create the headers we need to send.
		$headers = array(
			'Content-Type' => 'application/json',
			'Authorization' => "Passcode $passcode"
		);

		//Carry out the request.
		list($returnedData, $httpStatusCode, $header, $request) = getPage($GLOBALS['BAMBORA_API_URL'] . "?", 5, $headers, true, $jsonPayload);

		//NOTE: We're not checking the https status code here, because payment issues return 400/404, despite the correct URL being hit.

		//Convert the returned json into an array.
		$dataArray = json_decode($returnedData, true);
    
		//If we got the approved message, return success.
		if(@$dataArray['approved'] == '1')   { return array($dataArray, ""); }
		

		$errorsAndAlerts = @$dataArray['message'] . "<br>";

		if(isset($dataArray['details']) && is_array($dataArray['details'])){

		  $messageArray = array();
		  foreach ($dataArray['details'] as $key => $fieldData) {
			if(!in_array($fieldData['message'], $messageArray)){
			  $messageArray[]   = $fieldData['message'];
			  $errorsAndAlerts .= $fieldData['message'] . '<br>';
			}
		  }
		}

		//Else return the error
		return array("", @$dataArray['code']." - ".$errorsAndAlerts);
		
	}

	//Return any errors.
	return array("", $errorsAndAlerts);

}

function beanstream_refundPayment($transactionId, $amount){

  $errorsAndAlerts = "";
  $success         = "";

  global $GLOBALS, $SETTINGS, $CURRENT_USER;


  if(!$transactionId){ $errorsAndAlerts = "No Payment ID found<br>\r\n"; }

  //
  $transactionNumberSafe = mysql_real_escape_string($transactionId);
  $originalTransaction   = mysql_get('purchases', null, "`transaction_number` = '$transactionNumberSafe'");
  if(!isset($originalTransaction['num'])){ $errorsAndAlerts .=  "No linked transaction found<br>\r\n"; }


  if(!$errorsAndAlerts){

    //Create the security code we need to pass
    $securityCodePreEncoding = ($GLOBALS['BEANSTREAM_TEST_MODE'])? "{$GLOBALS['BEANSTREAM_TEST_MERCHANT_ID']}:{$GLOBALS['BEANSTREAM_TEST_API_ACCESS_CODE']}" : "{$GLOBALS['BEANSTREAM_LIVE_MERCHANT_ID']}:{$GLOBALS['BEANSTREAM_LIVE_API_ACCESS_CODE']}" ;
    $passcode = base64_encode($securityCodePreEncoding);


    //Create the headers we need to send.
    $headers = array(
      'Content-Type' => 'application/json',
      'Authorization' => "Passcode $passcode"
    );

    $amount = priceFormatNumber($amount);


    $jsonPayload = "{
      \"amount\": $amount
    }";


    //Carry out the request.
    list($returnedData, $httpStatusCode, $header, $request) = getPage($GLOBALS['BEANSTREAM_API_URL'] . "/" . $transactionId . "/returns", 5, $headers, true, $jsonPayload);

    //Convert the returned json into an array.
    $dataArray = json_decode($returnedData, true);



    //If we got the approved message, return success.
    if(@$dataArray['approved'] == '1')   { 


      $date = date('Y-m-d H:i:s');

      $insertArray = [
        'createdDate'                 => $date,
        'createdByUserNum'            => $CURRENT_USER['num'],
        'updatedDate'                 => $date,
        'updatedByUserNum'            => $CURRENT_USER['num'],
        'amount'                      => $amount,
        'purchaseNum'                 => $originalTransaction['num'],
        'transaction_number'          => $dataArray['id'],
        'json_returned'               => $returnedData,
      ];

      mysql_insert('refund_log', $insertArray);

      $success         .= "Refunded &dollar $amount <br>\r\n";
    }else{
      $errorsAndAlerts .= @$dataArray['message'];
    }
  }

  return array($success, $errorsAndAlerts);
}


addAction('viewer_postinit',           '_sc_dispatch',                 999,  0); // Set priority to 999 so this runs after websiteMembership plugin and $CURRENT_USER is already defined

// dispatch user actions
function _sc_dispatch() {
  if (defined('IS_CMS_ADMIN')) { return; } // only run this from website viewers, not CMS admin pages


  // perform website plugin actions
  $action = @$_REQUEST['_sc_action'];
  if (!$action) { return; }

  elseif ($action == 'getProvinceField')   { 
    
    $state =   (@$_REQUEST['stateFieldName'])?   $_REQUEST['stateFieldName'] : 'state' ;
    $country = (@$_REQUEST['countryFieldName'])? $_REQUEST['countryFieldName'] : 'country' ;
    _sc_ajax_getProvinceField($state, $country);

  }elseif ($action == 'getTaxRate')         { _sc_ajax_getTaxRate(); }
  //elseif ($action == 'order')            { _sc_cart_createOrder(); }
  else                                   { die("Unknown _sc_action '" .htmlspecialchars($action). "'"); }

  // redirect
  $nextUrl = @$_REQUEST['_sc_nextUrl'] ? $_REQUEST['_sc_nextUrl'] : $GLOBALS['SC_CART_LIST_URL'];
  redirectBrowserToURL( $nextUrl );
  exit;
}


function _sc_ajax_getProvinceField($state = 'state', $country = 'country') {
  sc_displayProvinceField($state, $country);
  exit;
}

function _sc_ajax_getTaxRate() {
  $_REQUEST['tax'] =  calculateTax(@$_REQUEST['price']);
  $_REQUEST['total'] = priceFormatNumber(floatval($_REQUEST['price']) + floatval($_REQUEST['tax']));
  
  if(@$_REQUEST['json']){
  	header('Content-Type: application/json');
  	echo json_encode(array('tax' => $_REQUEST['tax'], 'total' => $_REQUEST['total']));
  }else{
	  
	  $libraryPath = 'assets/billing_totals.php';
  	$dirsToCheck = array('W:/t/transdocx.com/htdocs/','','../','../../','../../../');
  	foreach ($dirsToCheck as $dir) { if (@include_once("$dir$libraryPath")) { break; }}

  }
  exit;

}

function sc_displayCountryField($countryFieldName, $provinceFieldName) {
  global $TABLE_PREFIX;
  $sql  = "SELECT name FROM `{$TABLE_PREFIX}_sc_countries` ORDER BY dragSortOrder";
  $rows = mysql_query_fetch_all_assoc($sql);
  echo "<select class=\"form-control\" id=\"$countryFieldName\" name=\"$countryFieldName\" >\r\n";
  echo "<option value=''>-- select country --</option>\n";
  echo getSelectOptions(@$_REQUEST[$countryFieldName], array_pluck($rows, 'name'));
  echo "</select>\n";
}

//
function sc_displayProvinceField($provinceFieldName, $countryFieldName) {
  global $TABLE_PREFIX;
  $sql  = mysql_escapef("SELECT name FROM `{$TABLE_PREFIX}_sc_provinces` WHERE country=? ORDER BY dragSortOrder DESC", @$_REQUEST[$countryFieldName]);
  $rows = mysql_query_fetch_all_assoc($sql);
  if ($rows) {
    echo "<select id=\"".htmlspecialchars($provinceFieldName)."\" name='".htmlspecialchars($provinceFieldName)."' class=\"form-control\">\n";
    echo "<option value=''>-- select state / province --</option>\n";
    echo getSelectOptions(@$_REQUEST[$provinceFieldName], array_pluck($rows, 'name'));
    echo "</select>\n";
  }
  else {
    echo "<input type='text' id=\"".htmlspecialchars($provinceFieldName)."\" name='".htmlspecialchars($provinceFieldName)."' value='".htmlspecialchars(@$_REQUEST[$provinceFieldName])."' class=\"form-control\">\n";
  }
}


function calculateTax($price){
	if(!$price)                { return '0.00'; }
	if(!@$_REQUEST['country']) { return '0.00'; }


	$stateValue   = mysql_real_escape_string(@$_REQUEST['state']);
  $countryValue = mysql_real_escape_string($_REQUEST['country']);

	$state = ($stateValue)? mysql_get('_sc_provinces', null, "`name` = '$stateValue' && `country` = '$countryValue'") : array();

	if(@$state['tax_rate']){
		return priceFormatNumber( floatval($price) *  (floatval($state['tax_rate']) / 100 ) );
	}

	$country = ($countryValue)? mysql_get('_sc_countries', null, "`name` = '$countryValue'") : array();

	if(@$country['tax_rate']){
		return priceFormatNumber( floatval($price) *  (floatval($country['tax_rate']) / 100 ) );
	}

	//No vaules found, so return a default value
	return '0.00';
}


function priceFormatNumber($number){
  
  $number = $number * 100;
  $number = round($number);
  $number = $number / 100;
  return number_format($number, 2, '.', '');
}

function getTotalRefundedOnPurchase($purchase){

  //Basic Error Checking.
  if(!isset($purchase['num'])){ return 0; }

  //Get all purchase refunds.
  $refunds = mysql_select('refund_log', "`purchaseNum` = '{$purchase['num']}'");

  //set the total that will be returned.
  $total = 0.00;

  //Cycle through each refund and total up.
  foreach($refunds as $key => $refund) {
    $total += floatval($refund['amount']);
  }

  //Return the amount refunded.
  return priceFormatNumber($total);

}


function amountLeftToRefundOnPurchase($purchase){

  $amountRefunded = floatval(getTotalRefundedOnPurchase($purchase));

  return floatval($purchase['total']) - $amountRefunded;
}
