<?php
  
	require_once "../assets/_app_init.php";

	//permissions
	blockAgentType("MANAGER", "/index.php");
	blockAgentType("GUEST", "/index.php");
	
	
	
	
	//detemrine if user or admin/ceo is accessing the page
	$user_num = $CURRENT_USER["num"];
	
	
	
	
	
	//determine campaign number
	if(!@$_REQUEST["campaign"])             { die("Error: No Camapign Number Given"); }
	if(!userOwnsCpn($_REQUEST["campaign"])) { die("Error: Cannot Access Campaign"); }
	
	$cpnNum          = $_REQUEST["campaign"];
	$errors          = "";
	$errorsAndAlerts = "";
	$ppErrors        = "";
	$completed       = false;
	
	
	

	
	
	
	
	//load the campaign
	$where  = "num = $cpnNum";//"num = $cpnNum AND user_paid = 0";
	$opts   = array("where" => $where);
	$cpn    = new MultiRecordType("campaign_component", $opts, true);
	
	if($cpn->meta()["noRecordsFound"]) die("campaign not found");
	
	$cpn = $cpn->records()[0];
	
	
	
	
	
	
	
	/*
	the payment form is submitted - process payment
	*/
	if(@$_REQUEST['purchase'] == 'success'){   
			
		$update = array(
			"campaign_status" => 3,
			"admin_status"    => 3,
			"user_paid"       => 1
		);
			
		$cpn->set($update);
		$completed = true;
		
		
		
		/*
		$errors = billingFieldValidationFromRequest();   //validate the form input
		showme($errors);
		
		if(!$errors){   //form input was valid
			
			list($purchaseNum, $errorsAndAlerts) = takePaymentFromRequest($cpnNum);   //process the payemnt information
			
			//echo @$purchaseNum;
			//echo @$errorsAndAlerts;
			
			//update the campaign record if payment was successful
			if($purchaseNum && !$errorsAndAlerts){
			
				$update = array(
					"campaign_status" => 3,
					"admin_status"    => 3,
					"user_paid"       => 1
				);
				
				$cpn->set($update);
				$completed = true;
			}
		}
		*/
	}
	else if(@$_REQUEST['purchase'] == 'canceled'){
		
		$errorsAndAlerts = "Purchase was not completed.";
	}
	
	
	
	
	
	
	//campaign information
	$cpnNum          = $cpn->get("num");
	$title           = $cpn->get("title");
	$vendorTitle     = $cpn->get("vendor:label");
	$provinceTitle   = $cpn->get("province:label");
	$industryTitle   = $cpn->get("industry:label");
	$budget          = $cpn->get("budget");
	$leads           = $cpn->get("leads");
	
	$daystimes       = getCampaignDaysTimes($cpnNum);
	$start           = $daystimes->get("start_date");
	$recur           = $daystimes->get("recur");
	$end             = null;
	if(!$recur) $end = $daystimes->get("end_date");
	
	$locationTitles  = getLocationTitles(getCampaignLocations($cpnNum)["locations"]);
	
	
	
	
	
	//billing addresses
	$opts              = array("where" => mysql_escapef("user_acct = ?", $cpn->get('user')));
	$billing_addresses = new MultiRecordType("billing_addresses", $opts, true); 
	$billing_json      = "";

	
	/*
	if($billing_addresses->meta()["noRecordsFound"]){   //no billing addresses for the user
		
		$billing_addr      = null;
		$billing_addresses = null;
	}
	else{
		
		$address_meta      = $billing_addresses->meta();     //for number of stored addresses
		$billing_addresses = $billing_addresses->records();  //the record data
		$billing_addr      = $billing_addresses[0];          //the first record
		$billing_json      = array();                        //the json array before encoding
		
		
		//store all billing addresses for json string
		foreach($billing_addresses as $ba){
			
			$billing_vals = $ba->vals();
			
			//unset the uneeded fields
			unset($billing_vals["_filename"]);
			unset($billing_vals["_link"]);
			unset($billing_vals["_tableName"]);
			unset($billing_vals["num"]);
			unset($billing_vals["createdByUserNum"]);
			unset($billing_vals["createdDate"]);
			unset($billing_vals["dragSortOrder"]);
			unset($billing_vals["province:label"]);
			//unset($billing_vals["province"]);
			unset($billing_vals["updatedByUserNum"]);
			unset($billing_vals["updatedDate"]);
			unset($billing_vals["user_acct"]);
			unset($billing_vals["user_acct:label"]);
			
			array_push($billing_json, $billing_vals);
		}
		
		//set first address to the default
		foreach($billing_json[0] as $name=>$value) {
			if (array_key_exists($name, $_REQUEST)) { continue; }
			$_REQUEST[$name] = $value;
		}
		
		//encode the json string
		$billing_json = json_encode($billing_json);
	}
	
	*/
	
	
	$billing_addr = $billing_addresses->records()[0];

	
	
	//purchase information
	$budget   = @$cpn->get('budget');
	$mgmtFee  = calculateMgmtFee($budget);
	$subTotal = $budget + $mgmtFee;
	$tax      = number_format(getTaxFromPurchase($subTotal, $billing_addr->get('province')), 2);
	$total    = $subTotal + $tax;
	
	//TESTING PRICES
	//$subTotal = '0.01';
	//$tax      = '0.00';
	//$total    = '0.01';
	
	
	//province options
	$campaign_options = getCampaignOptions();
	$records          = $campaign_options["Provinces"]->records(); 
	$provinces        = getHTMLOptionsFromRecordData($records, @$_REQUEST["province"]);
	
	
	
	if(!$completed) list($payPalURL, $ppErrors) = payPalStandard_buildCheckoutURL(createPayPalOptonsArray($cpnNum, $subTotal, $tax, $total));
	
	$errorsAndAlerts .= $ppErrors;
	
	/*if(!$completed){//if($cpn["user"] != @$CURRENT_USER["num"]) die("Error: Campaign does not match User Number");}*/
	
	//showme($_REQUEST);
	

	
	function createPayPalOptonsArray($cpnNum, $subtotal, $tax, $total){
		
		$items = array();
		
		//add each image info to the items array
		
			
		$item = array(
			'name'     => "Adrocket Campaign",
			'amount'   => $subtotal,
			'quantity' => 1
		);
		
		array_push($items, $item);
		
		
		$opts = array(
			"image_url"     => "http://www.adrocketdev.ca/img/adrocketlogo-002.png",
			"notify_url"    => $GLOBALS['PAYPAL_STANDARD']['IPN_URL'],
			"return"        => "http://www.adrocketdev.ca/campaign/purchase.php?campaign=$cpnNum&purchase=success",
			"cancel_return" => "http://www.adrocketdev.ca/campaign/purchase.php?campaign=$cpnNum&purchase=canceled",
			'items'         => $items,
			'tax'           => $tax,
			'description'   => "Adrocket Campaign",
			'custom'        => $cpnNum
		);
		
		//showme($opts);
		return $opts;
	}

	
	
	
	
	
	
?>