<?php

/**
* This is the control file for the campaign creation process.
*/
require_once "../assets/_app_init.php";



if($_FILES){ campaign_processUpload(); }


//deterine access permissions - managers may not create campaigns
blockAgentType("MANAGER");


$alerts = "";


//showme($_REQUEST);
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/
$campaign = getCampaginForCreate(false);   //try to load an exiting campaign first
if(!$campaign) die("campaign could not be found");



//get the validation errors for the campaign
$valid_errors = campaign_isReadyForCheckout($campaign);

foreach($valid_errors as $index=>$value){
	
	if($value === TRUE) unset($valid_errors[$index]);
}
//showme($valid_errors);

if(empty($valid_errors)) $campaign->set(array("user_completed" => 1));
calculateCampaignLeads_num($campaign->get("num"), 0, true);

$campaign = getCampaginForCreate(false);  //reload




/**
* Ajax Handler for custom location text
*/
if(@$_REQUEST['custom_location']){
	
	$t      = @$_REQUEST['text'];
	$cpnNum = @$_REQUEST['cpn'];
	
	$opts = array("where" => "num = $cpnNum");
	
	$cpn = new MultiRecordType("campaign_component", $opts, true);
	
	
	$cpn = $cpn->records()[0];
	
	$cpn->set(array("custom_location_text" => $t));
	
	
	getLocationCoords($_REQUEST['location_name']);
}


/**
* Ajax handler for coordinate fetching on the google map
*/
if(@$_REQUEST['location_name']){
	getLocationCoords($_REQUEST['location_name']);
}



/**
* Ajax handler for remove location from google map
*/
if(@$_REQUEST['location_remove']){
	removeLocationFromMap();
}




/**
* Ajax request Handler for submission of campaign adwords ads
*/
if(@$_REQUEST["adwords_update"]){ 


	$ads       = @$_REQUEST['adwords'];
	$split_ads = array();
	
	foreach($ads as $index=>$ad){ parse_str($ad, $split_ads[$index]); }
	$r = doCampaignUpdate_adwords($split_ads, @$_REQUEST['cpn']);
	
	echo json_encode(1);
	exit();
}



/**
* Ajax request Handler for submission of campaign settings modals
*/
if(@$_REQUEST["campaign_update"]){ 
	
	doCampaignUpdate($campaign);
	exit();
}


/**
* Set some general campaign information variables
*/
$cpnNum         = $campaign->get("num");
$userNum        = $campaign->get("user");
$title          = $campaign->get("title");
$vendor         = $campaign->get("vendor");
$vendorTitle    = $campaign->get("vendor:label");
//$province       = $campaign->get("province");
//$provinceTitle  = $campaign->get("province:label");
$industry       = $campaign->get("industry");
$industryTitle  = $campaign->get("industry:label");
$budget         = $campaign->get("budget");
$leads          = $campaign->get("leads");

$c_name     = @$campaign->get("contact_name")     ?: /*$CURRENT_USER["fullname"] ?: */"";
$comp_name  = @$campaign->get("company_name")     ?: /*$CURRENT_USER["company"]  ?: */"";
$c_phone    = @$campaign->get("contact_phone")    ?: /*$CURRENT_USER["phone"]    ?: */"";
$c_email    = @$campaign->get("contact_email")    ?: /*$CURRENT_USER["email"]    ?: */"";
$c_province = @$campaign->get("contact_province") ?: /*$CURRENT_USER["email"]    ?: */"";
$comments   = @$campaign->get("comments");

$user_completed = $campaign->get("user_completed");






//loading the related records for campaigns
$locations  = getCampaignLocations($cpnNum);
$keywords   = getCampaignKeywords($cpnNum);
$daystimes  = getCampaignDaysTimes($cpnNum);
$dayparting = getCampaignDayParting($cpnNum);

$recur      = $daystimes->get("recur");




//redirect if the campaign has been paid for
if($campaign->get("user_paid") == 1) header("Location: view.php?num=$cpnNum");

	
	
	
	
//dayparting details
if($dayparting && $daystimes->get("day_parting")){
	
	$dp_dets  = getDayPartingDetails($cpnNum);
	$dp_table = getDaypartingTableHTML($dp_dets, true);
}






//campaign ads info
$ads = getCampaignAds($cpnNum);
$adwords  = @$ads["adwords"];
$display  = @$ads["display"][0];
$remarket = @$ads["remarketing"][0];

if(!$adwords && !$display && !$remarket) $ads = null;

$display_url          = null;
$display_preview_img  = null;
$remarket_preview_img = null;

$adwords_json         = "";

if($ads["display"]){
	
	$display_preview_img = $ads["display"][0]->vals()["user_display_ad"][0]["urlPath"];
	$display_url         = $ads["display"][0]->get("destination_url");
	
}

if($ads["remarketing"]){
	
	$remarket_preview_img = $ads["remarketing"][0]->vals()["user_display_ad"][0]["urlPath"];
	
}

if($ads['adwords']){
		
	foreach($ads['adwords'] as $adw){
		
		$vals = $adw->vals();
		
		unset($vals['_link']);
		unset($vals['_filename']);
		unset($vals['_tableName']);
		unset($vals['createdByUserNum']);
		unset($vals['createdDate']);
		unset($vals['dragSortOrder']);
		unset($vals['updatedByUserNum']);
		unset($vals['updatedDate']);
		unset($vals['num']);
		unset($vals['campaign']);
		unset($vals['campaign:label']);
		
		//array_push($adwords_json, json_encode($vals));
		$adwords_json .= json_encode($vals) . ",";
	}
	
}

//showme($adwords);







//if none of these are filled out we will show a 'start here' help box on the general tab
$fresh_campaign = !($vendor || $industry);

//get tax/total info in user is logged in 
if(/*$CURRENT_USER && */$budget){
	
	
	//if($userNum){
		
	if(!$userNum) $userNum = 0;
		
	$province     = null;
	$billing_addr = new MultiRecordType("billing_addresses", array("where" => "user_acct = $userNum"), true);
	
	if($billing_addr->meta()["noRecordsFound"]) $province = @$CURRENT_USER["province"];
	else{
		
		$billing_addr = $billing_addr->records()[0];
		$province     = $billing_addr->get('province');
		if(!$province)  $province = @$CURRENT_USER["province"];
	}
	
	if(!$province) $province = $c_province;
	
	//if(!$billing_addr->meta()["noRecordsFound"]){
		
		
		//$billing_addr = $billing_addr->records()[0];
	
	$mgmtFee  = calculateMgmtFee($budget);
	$subTotal = $budget + $mgmtFee;
	$tax      = number_format(getTaxFromPurchase($subTotal, $province), 2);
	$total    = number_format($subTotal + $tax, 2);
	//}
		
	//}
}





/**
* load all the campaign creation data (options)
*	*Vendors
*	*Locations
*	*Industries
*	*Keywords
*	etc.
*/
$campaign_options = getCampaignOptions();



?>
