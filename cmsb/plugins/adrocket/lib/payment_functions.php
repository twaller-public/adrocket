<?php

function billingFieldValidationFromRequest(){

	$errors = array();

	if(!@$_REQUEST['cc_name'])        { $errors["cc_name"]         = "You must enter your first name."; }
	if(!@$_REQUEST['cc_num'])         { $errors["cc_num"]          = "You must enter your Card number."; }
	if(!@$_REQUEST['cc_expiry_month']){ $errors["cc_expiry_month"] = "You must enter your expiry month."; }
	if(!@$_REQUEST['cc_expiry_year']) { $errors["cc_expiry_year"]  = "You must enter your expiry year."; }
	if(!@$_REQUEST['cc_ccv'])         { $errors["cc_ccv"]          = "You must enter the last 3 digits of your CCV number."; }

	$errors["cc_num"]          = adr_validateCCNumber(@$_REQUEST['cc_num']);
	$errors["cc_expiry_month"] = adr_validateCCMonth(@$_REQUEST['cc_expiry_month']);
	$errors["cc_expiry_year"]  = adr_validateCCYear(@$_REQUEST['cc_expiry_year']);
	$errors["cc_ccv"]          = adr_validateCCV(@$_REQUEST['cc_ccv']);

	foreach($errors as $index=>$error) if(!$error) unset($errors["$index"]);
    return $errors;

}



function adr_validateCCNumber($number){

if(!$number)              { return "You must enter a CC Number."; }
if(!ctype_digit($number)) { return "You must enter the credit card number as all numeric digits."; }
return "";
}



function adr_validateCCMonth($number){

  if(!$number)                     { return "You must enter a CC Month."; }
  if(!ctype_digit($number))        { return "You must enter the CC month as all numeric digits."; }
  if(strlen($number) != 2)         { return "The CC month must be 2 digits long. EG: 09"; }
  if($number < 1 || $number > 12 ) { return "The CC month must be between 1  and 12."; }
  return "";
}



function adr_validateCCYear($number){

  if(!$number)                            { return "You must enter a CC year."; }
  if(!ctype_digit($number))               { return "You must enter the CC year as all numeric digits."; }
  if(strlen($number) != 2)                { return "The CC year must be 2 digits long."; }
  if(intval($number) < intval(date('y'))) { return "The CC year is less than the current year. Your card might have expired."; }
  return "";
}



function adr_validateCCV($number){

  if(!$number)              { return "You must enter a CCV Number."; }
  if(!ctype_digit($number)) { return "You must enter the CCV as all numeric digits."; }
  return "";
}



function takePaymentFromRequest($cpnNum){

	global $CURRENT_USER, $GLOBALS, $SETTINGS;

	$purchaseNum     = createOrUpdatePurchaseEntry($cpnNum);
	$purchase        = mysql_get('purchases', $purchaseNum);
	$errorsAndAlerts = "";
	$recieptLink     = "";
	$cpn             = getCampaignFromNumber($cpnNum);
	
	$province = determineProvinceCode(@$_REQUEST['province']);

	$paymentDetails = array();
	$paymentDetails['order_number']    = 32;//$purchaseNum; 
	$paymentDetails['amount']          = priceFormatNumber(floatval($purchase['total']));       
	$paymentDetails['name']            = @$_REQUEST['cc_name'];         
	$paymentDetails['number']          = @$_REQUEST['cc_num'];       
	$paymentDetails['expiry_month']    = @$_REQUEST['cc_expiry_month']; 
	$paymentDetails['expiry_year']     = @$_REQUEST['cc_expiry_year'];  
	$paymentDetails['cvd']             = @$_REQUEST['cc_ccv'];  
	$paymentDetails['billing_address'] = array(
		"name"          => @$_REQUEST['cc_name'],
		"address_line1" => @$_REQUEST['addr_1'],
		"address_line2" => @$_REQUEST['addr_2'],
		"city"          => @$_REQUEST['city'],
		"province"      => $province,
		"country"       => "CA",
		"postal_code"   => @$_REQUEST['postal'],
		"phone_number"  => @$CURRENT_USER['phone'],
		"email_address" => @$CURRENT_USER['email'],
	);


	list($transactionDetails, $paymentErrors) = bambora_takeAPayment($paymentDetails);
	
	
	
	
	//$paymentErrors     = ""; debugging only - clear payment errors to force storage

	if(@$paymentErrors){
		$errorsAndAlerts .= $paymentErrors;
	}else{
		//showme($transactionDetails);
		$transactionNumber = $transactionDetails['id'];

		//Payment taken successfully.
		mysql_update('purchases', $purchaseNum, null, array('completed' => '1', 'transaction_number' => $transactionNumber));


		$placeholders = array();//$CURRENT_USER;

		//Create a link to the receipt for the order if the user is logged in.
		$receiptUrl   = 'http://' . $SETTINGS['licenseDomainName'] . "/campaign/receipt.php?num=" . $purchaseNum;

		$recieptLink  = str_replace('#url#', $receiptUrl, "<p>You can view your receipt <a href=\"#url#\">here</a>.</p>");
		$placeholders['receiptLink'] = $recieptLink;
		$placeholders['email']       = $cpn->get('contact_email');
		

		
		$emailHeaders = emailTemplate_loadFromDB(array(
			'template_id'  => 'CAMPAIGN-PURCHASE',
			'placeholders' => $placeholders,
			'subject'      => "Thank you for your Adrocket campaign purchase",
			'from'         => $SETTINGS['adminEmail'],
			'to'           => $placeholders['email'],
		));
		$mailErrors   = sendMessage($emailHeaders);
		
		echo $mailErrors;
	}

	return array($purchaseNum, $errorsAndAlerts);
}



function getTaxFromPurchase($cost, $province){
	
	return number_format((mysql_get("provinces", $province, null)['tax_rate'] * 0.01) * $cost, 2);
	
}



function searchForPaymentCompletedCampaign($cpnNum){
	
	return mysql_count("purchases", "campaign = $cpnNum AND completed = 1");
}



function createOrUpdatePurchaseEntry($cpnNum){

	global $GLOBALS, $CURRENT_USER;

	//if(!$GLOBALS)     { return 0; }
	$cpn = getCampaignFromNumber($cpnNum);

	$user  = $CURRENT_USER;
	$price = number_format($cpn->get('budget'), 2);                                       
	$tax   = number_format(getTaxFromPurchase($cpn->get('budget'), $cpn->get('province')), 2); 
	$total = number_format(($cpn->get('budget') + $tax), 2);                              

	//Create update/insert array
	$insertArray                     = array();
	$insertArray['updatedDate']      = date('Y-m-d H:i:s');
	$insertArray['updatedByUserNum'] = 0;

	//Set the temp id
	//$tempId                          = getAndSetTempId();


	$insertArray['user']             = $user['num'];
	$insertArray['campaign']         = $cpnNum;
	$insertArray['price']            = $price;
	$insertArray['tax']              = $tax;
	$insertArray['total']            = $total;
	$insertArray['name']             = $_REQUEST['cc_name'];
	$insertArray['last_4_digits']    = substr(trim($_REQUEST['cc_num']), -4);
	$insertArray['completed']        = '0';
	$insertArray['ip_address']       = @$_SERVER['REMOTE_ADDR'];
	$insertArray['email']            = coalesce(@$CURRENT_USER['email'], @$_REQUEST['sender_email'], '');

	$ipData = getCountryFromIP(@$_SERVER['REMOTE_ADDR']);
	
	if($ipData && !isset($ipData['bogon'])){
		$insertArray['country_code'] = strtoupper(@$ipData['country']);
		$insertArray['city']         = @$ipData['city'];
		$insertArray['location']     = @$ipData['loc'];
	}


	if(mysql_count('purchases', "`completed` = '0' AND `user` = '{$user['num']}' AND `campaign` = $cpnNum")){

		//Get the already created purchase
		
		$purchase = mysql_get('purchases', null, "`completed` = '0' AND `user` = '{$user['num']}' AND `campaign` = $cpnNum");
		if(!$purchase){ return 0; }
		//showme($purchase);

		//update the record with the current purchase data.
		mysql_update('purchases', $purchase['num'], null, $insertArray);
		return $purchase['num'];

	}else{

		//Insert the record and return the num value.
		$insertArray['createdDate']      = date('Y-m-d H:i:s');
		$insertArray['createdByUserNum'] = 0;
		$insertArray['refunded']         = '0';

		return mysql_insert('purchases', $insertArray, true);
	}

}



function getCountryFromIP($ip){
	$url = "http://ipinfo.io/".urlencode($ip)."/json";

	list($response, $statusCode) = getPage($url);


	if($statusCode == '200'){

		return json_decode($response, true);

	}else{
		return array();
	}
}



function getAllPurchases(){
	
	list($purchases, $purchaseMeta) = getRecords(array(
		'tableName'   => 'purchases',
		'loadUploads' => false,
		'allowSearch' => false,
		'where'       => "",
	));

	return array(@$purchases, $purchaseMeta);
}



function determineProvinceCode($provNum){
	
	if(!$provNum) return "";
	
	$province = new Record("provinces", $provNum);
	if(!$province) return "";
	$code = $province->get("province_code");
	return $code;
}



?>