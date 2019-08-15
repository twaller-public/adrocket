<?php

/** 
* getCampaginForCreate fetches a campaign for use on the
* campagin create page.
* If the agent is a guest, we search for a campaign with the
* agent's session ID.
* Otherwise we check for a campaign number set in $_POST['num'].
* If we do not find a campaign, we create a new one and associate
* it with the User Agent's account, the Guest Agent's session ID,
* or, if the agent is an Admin or CEO, we leave the user and session
* values blank.
*
*/
function getCampaginForCreate($autoCreate = true){
	
	
	GLOBAL $AGENT_TYPE;
	
	if($AGENT_TYPE == "GUEST") return fetchCampaignForSession();
	else return fetchCampaignForUser($autoCreate);
	
}



function getCampaignFromNumber($cpnNum){
	
	global $CURRENT_USER;
	
	
	if($cpnNum && userOwnsCpn($cpnNum)){
		
		$opts = array(
			"where" => mysql_escapef("num = ?", $cpnNum),
			"limit" => 1
		);
		
		//load the campaign with the specified number
		$cpn = new MultiRecordType("campaign_component", $opts, true);
		if($cpn->meta()['noRecordsFound']) die("The Campaign with the specified number was not found.");
		
		return $cpn->records()[0];
	}
	return null;
}



/**
* fetchCampaignForSession searches the records for a campaign that
* has this user's session ID. If a record is found it is returned,
* otherwise a new campaign is created for the Session ID and returned.
*/
function fetchCampaignForSession(){
	
	$id  = session_id();
	$cpn = null;
	
	if(!$id) die("Session ID not found!");
	
	//search for a campaign with this session id
	$opts = array(
		"where" => mysql_escapef("session_id = ? AND inactive = 0", $id),
		"limit" => 1,
	);
	$cpn = new MultiRecordType("campaign_component", $opts, true);
	
	
	
	//determine if we need to create a new campaign
	//(one doesn't already exist with this session ID)
	if($cpn->meta()['noRecordsFound']){ $cpn = createCampaignForSession(); }  //no record
	else{ $cpn = $cpn->records()[0]; }                                        //has record
	
	//showme($cpn);
	return $cpn;
}



/**
* fetchCampaignForUser checks if $_POST['num'] is set, and looks
* in the records for the campaign with that number. If the number
* is not provided, create a new campaign.
* If the Agent is a User, associate the campaign with the user account.
* If the Agent is a Admin or CEO, leave the user/session ID blank
*/
function fetchCampaignForUser($autoCreate = true){
	
	GLOBAL $AGENT_TYPE, $CURRENT_USER;
	
	//$new = @$_REQUEST["new"];
	$num = @$_REQUEST["num"];   //the campaign number to retrieve
	$cpn = null;
	
	
	
	//determine how we return a campaign
	if($num && userOwnsCpn($num)){
		
		$opts = array(
			"where" => mysql_escapef("num = ? AND inactive = 0", $num),
			"limit" => 1
		);
		
		//load the campaign with the specified number
		$cpn = new MultiRecordType("campaign_component", $opts, true);
		if($cpn->meta()['noRecordsFound']) die("The Campaign with the specified number was not found.");
		
		return $cpn->records()[0];
	}
	
	//if we are here, we are creating a new campaign
	if($AGENT_TYPE == "USER"){
		
		if($autoCreate) return createCampaignForUser($CURRENT_USER["num"]); //new campaign for user
		else{  //load newest cpn
		
		
			$opts = array(
				"where" => mysql_escapef("user = ? AND inactive = 0", $CURRENT_USER["num"]),
				"limit" => 1,
				"orderBy" => "num DESC"
			);
			
			//load the campaign with the specified number
			$cpn = new MultiRecordType("campaign_component", $opts, true);
			if($cpn->meta()['noRecordsFound']) return createCampaignForUser($CURRENT_USER["num"]);
			
			return $cpn->records()[0];
		}
	} 
	else if($AGENT_TYPE != "USER") return createBlankCampaign();   //new campaign for admin/ceo
	
	return $cpn;
}



function checkForEmptyCampaign(){
	
	GLOBAL $CURRENT_USER;
	
	$uNum   = $CURRENT_USER["num"];
	$result = 0;                           //will contain the campaign, if found
	
	$whereString = "user = ? AND inactive = 0 AND title = '' AND budget = '' AND vendor = '' AND industry = ''";
	
	$opts = array(
		"where" => mysql_escapef($whereString, $uNum),
		"limit" => 1
	);
	
	//load the campaign with the specified number
	$cpn = new MultiRecordType("campaign_component", $opts, true);
	if($cpn->meta()['noRecordsFound']) return false;
	
	return $cpn->records()[0];

}




/**
* createCampaignForSession creates a new campaign record for the current
* agent session and returns the new campaign data.
*/
function createCampaignForSession(){
	
	$id          = session_id();
	$cpn         = new MultiRecordType("campaign_component");	
	$insertArray = array(
		"session_id" => $id, 
		"campaign_status" => 1, 
		"admin_status" => 1
	);
	$num         = $cpn->create($insertArray);
	
	//create the empty related records for this campaign
	createDefaultDateTimeCampaignRecord($num);
	createDefaultKeywordsCampaignRecord($num);
	
	$opts        = array(
		"where" => mysql_escapef("num = ?", $num),
		"limit" => 1
	);
	
	
	$cpn->load($opts);
	return $cpn->records()[0];
}




/**
* createCampaignForUser accepts a user number and creates a
* new campaign for that user.
*/
function createCampaignForUser($userNum){
	
	$cpn = checkForEmptyCampaign();
	
	if($cpn) return $cpn;
	
	
	$cpn         = new MultiRecordType("campaign_component");	
	$insertArray = array(
		"user" => $userNum, 
		"campaign_status" => 1, 
		"admin_status" => 1
	);
	$num         = $cpn->create($insertArray);
	
	//create the empty related records for this campaign
	createDefaultDateTimeCampaignRecord($num);
	createDefaultKeywordsCampaignRecord($num);
	
	$opts        = array(
		"where" => mysql_escapef("num = ?", $num),
		"limit" => 1
	);
	
	
	$cpn->load($opts);
	return $cpn->records()[0];
}




/**
* createBlankCampaign creates a new campaign record where no
* user or session is entered.
* This function is for use by Admins and CEO agents that may be creating
* a campaign for another user.
*/
function createBlankCampaign(){
	
	$cpn         = new MultiRecordType("campaign_component");	
	$num         = $cpn->create(array("campaign_status" => 1, "admin_status" => 1));
	
	//create the empty related records for this campaign
	createDefaultDateTimeCampaignRecord($num);
	createDefaultKeywordsCampaignRecord($num);
	
	$opts = array(
		"where" => mysql_escapef("num = ?", $num),
		"limit" => 1
	);
	
	$cpn->load($opts);
	return $cpn->records()[0];
}



/**
* getCampaignOptions returns an array of option data that
* users select from to build a campaign.
*	*Vendors
*	*Provinces
*	*Industries
*	*Locations
*	*Keywords
*	*CPC Data
*/
function getCampaignOptions(){
	
	GLOBAL $AGENT_TYPE, $vendor, /*$province, */$industry;
	
	$options = array(
		"Vendors"    => new MultiRecordType("vendors",    array("where" => "is_active = 1"), true),
		"Provinces"  => new MultiRecordType("provinces",  null, true),
		"Industries" => new MultiRecordType("industries", null, true),
		"Locations"  => array(),
		"Keywords"   => array(),
		"CPC"        => null
	);
	
	//if($province) $options["Locations"] = new MultiRecordType("locations",         array("where" => "province = $province"), true);
	if($industry) $options["Keywords"]  = new MultiRecordType("industry_keywords", array("where" => "industry = '" . $industry . "' "), true);
	if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO") $options["users"] = getUserOptionsList();
	
	return $options;
}











/**
*
*/
function createAdRecord($cpnNum, $onlyFetch = false, $vNum = false){
	
	if(!$vNum){
		
		$cpn  = new MultiRecordType("campaign_component", array("where" => "num = $cpnNum"), true);
		$cpn  = $cpn->records()[0];
		$vNum = $cpn->get("vendor");
	}
	
	
	if     ($vNum == 1) return createDefaultAdwords($cpnNum);
	else if($vNum == 2) return createDefaultDisplay($cpnNum);
	else if($vNum == 3) return createDefaultRemarketing($cpnNum);
	
	return null;
	
}




/**
*
*/
function createDefaultAdwords($cpnNum, $onlyFetch = false){ 
	return null; 
}




/**
*
*/
function createDefaultDisplay($cpnNum, $onlyFetch = false){ 
	
	$insertArray = array(
		"campaign" => $cpnNum,
	);
	
	$display = new MultiRecordType("google_display_campaign", array("where" => "campaign = $cpnNum"), true);
	
	if($display->meta()["noRecordsFound"] && !$onlyFetch){
		
		$recordNum = $display->create($insertArray);
		$display->load(array("where" => "num = $recordNum"));
	}
	
	return @$display->records()[0];
}




/**
*
*/
function createDefaultRemarketing($cpnNum, $onlyFetch = false, $added = false){ 
	
	$insertArray = array(
		"campaign" => $cpnNum,
		"added"    => $added? 1 : 0,
	);
	
	$rm = new MultiRecordType("google_remarketing_campaign", array("where" => "campaign = $cpnNum"), true);
	
	if($rm->meta()["noRecordsFound"] && !$onlyFetch){
		
		$recordNum = $rm->create($insertArray);
		$rm->load(array("where" => "num = $recordNum"));
	}
	
	return @$rm->records()[0];
	
}



/**
*
*/
function resetCampaignAds($cpnNum, $clearAddedRemarketing = false){
	
	/*NOTE*/
	//we leave added remarketing by default so the user can just attach the same remakreting
	//params to a new vendor type (if they change from adwords to display, for example)
	//this is so that the user doesn't have to re-enter the information.
	
	$opts = array("where" => "campaign = $cpnNum");
	
	$adwords  = new MultiRecordType("adwords_campaign",            $opts, true);
	$display  = new MultiRecordType("google_display_campaign",     $opts, true);
	$remarket = new MultiRecordType("google_remarketing_campaign", $opts, true);
	
	$adwords  = $adwords->records();
	$display  = $display->records();
	$remarket = $remarket->records();
	
	foreach($adwords  as $a){ $a->del(); }      //clear all adwords
	foreach($display  as $d){ $d->del(); }      //clear all display
	foreach($remarket as $r){                   //only clear remarketing if it is not added onto another
		if($clearAddedRemarketing) $r->del(); 
		else if(!$r->get("added")) $r->del();
	}
	
	return false;
}










/**
* createDefaultDateTimeCampaignRecord creates an empty time and date
* campaign record and asociates it with the current campaign
*/
function createDefaultDateTimeCampaignRecord($cpnNum){
	
	if(!$cpnNum) return null;
	
	$insertArray = array("campaign" => $cpnNum, "expiry_notification" => 1);
	$cpndaytime  = new MultiRecordType("campaign_days_times");
	
	return $cpndaytime->create($insertArray);
}



/**
* createDefaultDayPartCampaignRecord creates an empty time and date
* campaign record and asociates it with the current campaign
*/
function createDefaultDayPartCampaignRecord($cpnNum){
	
	if(!$cpnNum) return null;
	
	$insertArray = array("campaign" => $cpnNum);
	$cpndaytime  = new MultiRecordType("campaign_day_parting");
	
	return $cpndaytime->create($insertArray);
}




/**
* createDefaultLocationsCampaignRecord creates an empty location
* campaign record and asociates it with the current campaign
* - UNUSED - WE DO NOT CREATE DEFAULT LOCATION RECORDS CURRENTLY
*/
function createDefaultLocationsCampaignRecord($cpnNum){
	
	if(!$cpnNum) return null;
	
	$insertArray  = array("campaign" => $cpnNum);
	$cpnlocations = new MultiRecordType("campaign_locations");
	
	return $cpnlocations->create($insertArray);
}



/**
* createDefaultKeywordsCampaignRecord creates an empty keywords
* campaign record and asociates it with the current campaign
*/
function createDefaultKeywordsCampaignRecord($cpnNum){
	
	if(!$cpnNum) return null;
	
	$insertArray = array("campaign" => $cpnNum);
	$cpnkeyword  = new MultiRecordType("campaign_keywords");
	
	return $cpnkeyword->create($insertArray);
}




/**
* resetCampaignLocations removes all the location records for the given campaign number
*/
function resetCampaignLocations($cpnNum){
	
	$opts      = array("where" => "campaign = $cpnNum");
	$locations = new MultiRecordType("campaign_locations", $opts, true);
	
	if($locations->meta()["noRecordsFound"]) return;  //there are already no location records
	
	//other wise delete each location record
	$locations = $locations->records();
	foreach($locations as $l){ $l->del(); }
}



/**
* updateCampaignLocations removes all campaign location records, and re-adds them
* based on the given locations list. third param, $preset, will create a locations
* record for 'preset_text', if passed.
*/
function updateCampaignLocations($cpnNum, $locations, $preset = "0"){
	
	if(!@$cpnNum) return;
	resetCampaignLocations($cpnNum);  //reset the locations first
	$location_records = new MultiRecordType("campaign_locations");
	
	//create new campaign location record for each location
	foreach(@$locations as $name=>$num){
		
		if($name == "locationsModal" || $num == "1") continue;
		
		$insertArray = array(
			"campaign" => $cpnNum,
			"location" => $num
		);
		
		$location_records->create($insertArray);
	}
	
	//enter a selected preset if one was passed
	if($preset && $preset != "0"){
		
		$insertArray = array(
			"campaign" => $cpnNum,
			"location" => null,
			"preset_text" => $preset
		);
		
		$location_records->create($insertArray);
	}
}


/**
* resetCampaignKeywords removes all the keyword data for the given campaign number
* from the keywords record - the record is not deleted
*/
function resetCampaignKeywords($cpnNum, $industryNum = null){
	
	$opts      = array("where" => "campaign = $cpnNum");
	$keywords  = new MultiRecordType("campaign_keywords", $opts, true);
	
	//if there is no keywords record create one for this campaign and return
	if($keywords->meta()["noRecordsFound"]) return createDefaultKeywordsCampaignRecord($cpnNum);
	
	//other wise delete the keywords and negative keyords record data
	$keywords = $keywords->records()[0];
	
	$insertArray = array(
		"keywords"          => null,
		"negative_keywords" => null,
		"unused_defaults"   => null
	);
	
	
	//if the industry number is set, we insert the default
	//industry keywords into the campaign keywords record.
	if($industryNum){
		
		$indKw = trim(implode(",", getKeywordsForIndustry($industryNum)), ",");
		
		//insert the default industry keywords only
		$insertArray["default_keywords"] = $indKw;
	}
	
	
	
	$keywords->set($insertArray);
}



/**
*
*/
function updateCampaignKeywords($cpnNum, $keywordData){
	
	
	$opts      = array("where" => "campaign = $cpnNum");
	$keywords  = new MultiRecordType("campaign_keywords", $opts, true);
	
	//if there is no keywords record create one for this campaign
	if($keywords->meta()["noRecordsFound"]) createDefaultKeywordsCampaignRecord($cpnNum);
	$keywords->load($opts);
	
	//other wise delete the keywords and negative keyords record data
	$keywords = $keywords->records()[0];
	$keywords->set($keywordData);
	
	return false;
}



/**
* includeCampaignCreateModal takes parameters to create a modal for campaign creation
* and includes the default create section modal with the specified parameters
*/
function includeCampaignCreateModal($size = "", $modalID = "WasNotSet", $modalLabel = "WasNotSet", $modalTitle = "WasNotSet", $fileReqs = array(), $cpnVals = array()){
	
	GLOBAL $campaign_options, $cpnNum;
	
	include $_SERVER["DOCUMENT_ROOT"] . "/assets/modal/campaign-create-empty-modal.php";
	
	return null;
}



/**
* doCampaignUpdate
*/
function doCampaignUpdate($cpn, $ajax = true){
	
	$req         = @$_REQUEST;
	$num         = $cpn->get('num');
	$response    = getAjaxResponseArray_UpdateCampaign();   //default reponse array - empty
	
	
	
	//general values update (title, budget, province, industry, etc)
	list($generalValues, $response) = doCampaignUpdate_general($cpn, $response);

	
	/*here we know that the province and industry have been set already and will not change,*/
	/*  so we can load the options for locations and keywords                               */
	$kw_opts                         = getKeywordsForIndustry($generalValues['industry']);	
	$response["options"]["keywords"] = $kw_opts;        //the default keywords for the industry
	
	
	
	
	/*                                                                                                                          */
	
	
	
	//update the location values in the location record
	$response = doCampaignUpdate_locations($cpn, $response);
	
	/*here we know that the location data is set, so we can load the HTML options           */
	/*  for the presets and the locations since we know which are chosen already            */

	//adjust the response locations to be an array of location titles
	$response["selections"]["locations"]["locations"] = getLocationTitles($response["selections"]["locations"]["locations"]);
	
	
	
	
	/*                                                                                                                          */
	
	
	//update the ads for this campaign
	$response = doCampaignUpdate_ads($cpn, $response);
	
	//update the keyword information in this campaign.
	$response = doCampaignUpdate_keywords($cpn, $response);
	
	//update the day/time information for this campaign
	$response = doCampaignUpdate_daytime($cpn, $response);
	
	//update the pay parting information for this campaign
	$response = doCampaignUpdate_daypart($cpn, $response);
	
	//update the contact information for this campaign
	$response = doCampaignUpdate_contact($cpn, $response);
	
	
	/* Modal evaluation done                                                                                                   */
	


	//calculate the new leads value
	$generalValues['leads'] = calculateCampaignLeads_data(null, $generalValues['industry'], @$generalValues['budget'], getCampaignLocations($num)["locations"]);
	
	//update the campaign record
	$cpn->set($generalValues);
	
	//re-load the campaign
	$cpn = new MultiRecordType("campaign_component", array("where" => "num = $num", "limit" => 1), true);
	$cpn = $cpn->records()[0];
	
	//check if the campaign is complete enough to check out 
	$valid_errors = campaign_isReadyForCheckout($cpn);

	foreach($valid_errors as $index=>$value){ if($value === TRUE) unset($valid_errors[$index]); }
	if(empty($valid_errors)) $cpn->set(array("user_completed" => 1, "campaign_status" => 2, "admin_status" => 2));
	else $cpn->set(array("user_completed" => 0, "campaign_status" => 1, "admin_status" => 1));
	
	$response["selections"]["general"] = array_intersect_key($cpn->vals(), getDefaultCampaignReturnArray());
	if($response["selections"]["general"]["leads"] == 0) $response["selections"]["general"]["leads"] = $GLOBALS["ADROCKET_DEFINITIONS"]["No Leads Text"];
	
	if($ajax) echo json_encode($response);
	else return $response;
	
}


/**
* the structure of the response to the ajax call for creating campaigns
*/
function getAjaxResponseArray_UpdateCampaign(){
	
	
	return array(
		"selections" => array(
			"general"   => array(),//array_intersect_key($cpn->vals(), getDefaultCampaignReturnArray()),
			"locations" => array(),
			"keywords"  => array(),
			"ads"       => array(),
			"daytime"   => array(),
			"daypart"   => array(),
			"contact"   => array(
				"contact_name"  => "",
				"company_name"  => "",
				"contact_phone" => "",
				"contact_email" => "",
				"comments"      => "",
			),
		),
		"options"    => array(
			"locations" => array(
				"presetsList"         => array(),
				"locationsList"       => array(),
				"presetsOptionHTML"   => "",
				"locationOptionBoxes" => ""
			),
			"keywords"  => array(
				"keywords"          => "",
				"negative_keywords" => "",
				"default_keywords"  => "",
				"unused_defaults"   => "",
			),
		),
		"flags"      => array(
			"updateLocations" => false,
			"updateKeywords"  => false,
			"updateVendor"    => false,
		),
	);
}



/**
* update the general values that can go directly
* into the campaign component record.
* Return an array of values for the general section of the campaign
* creation process.
*/
function doCampaignUpdate_general($cpn, $response){
	
	$generalKeys = getDefaultCampaignUpdateArray();   //the campaign fields that we need to set in the record
	$num         = $cpn->get('num');
	//showme($_REQUEST);
	
	
	if(!@$_REQUEST['budget']) $_REQUEST['budget'] = 0;
	
	if(@$_REQUEST["generalModal"] || @$_REQUEST["qb-submit"]){
	
		//filter the request values passed through the general params
		$generalValues = array_intersect_key(@$_REQUEST, $generalKeys);

		
		//if the industry was changed, we need to reset the keyword data for this campaign
		if($generalValues['industry'] != $cpn->get("industry")){
			
			resetCampaignKeywords($num, $generalValues['industry']);
			$response["flags"]["updateKeywords"] = true;
		}
		
		if(@$generalValues["vendor"] != $cpn->get("vendor")){
			
			resetCampaignAds($num);
			$response["flags"]["updateVendor"] = true;
		}
	}
	else{
		//filter the current campaign values passed through the general params (none have changed)
		$generalValues = array_intersect_key($cpn->vals(), $generalKeys);
	}
	
	
	$generalValues['budget'] = $generalValues["budget"];
	
	return array($generalValues, $response);
}




/*
* update the location values in the location record
*/
function doCampaignUpdate_locations($cpn, $response){
	
	$num = $cpn->get("num");
	
	if(@$_REQUEST["locationsModal"]){
		
		
		$locations = $_REQUEST;
		$preset    = $_REQUEST["preset"];   //store the preset value for later
		
		
		//clear the locations of the un-needed request data
		unset($locations["num"]);
		unset($locations["locationsModal"]);
		unset($locations["preset"]);
		
		//every time we submit our locations, we are submitting every location
		//so we remove all locations data and re-enter it
		updateCampaignLocations($num, $locations, $preset);
		
		
	}
	else if(@$_REQUEST["city"]){   //quick build form single city submit
		
		updateCampaignLocations($num, array("city" => @$_REQUEST['city']), null);
	}
	$response["selections"]["locations"] = getCampaignLocations($num);
	
	return $response;
}




/*
* update the ads for the campaign
*/
function doCampaignUpdate_ads($cpn, $response){
	
	$num = $cpn->get("num");
	//$response["selections"]["ads"] = @$_REQUEST;
	
	if(@$_REQUEST["vendorModal"]){
		
		//determine which vendor type these ads are for
		$vNum = $cpn->get("vendor");
		$ad   = createAdRecord($num, $vNum);
		
		
		//update or create the ads in the records
		
		
		if($vNum == 1){     
			//google adwords
			//create/update adwords records done in doCampaignUpdate_adwords function
		
		
			$cpn->set(array("remarketing_added" => @$_REQUEST["remarketing_added"]?: 0));
		}
		else if($vNum == 2){
			//display ads
		
			//update the display record
			$insertArray = array(
				"ad_file_name"    => "",
				"destination_url" => @$_REQUEST["destination_url"],
				"request_contact" => @$_REQUEST["request_contact"]?: 0,
			);
			
			$ad->set($insertArray);
			
			$cpn->set(array("remarketing_added" => @$_REQUEST["remarketing_added"]?: 0));
		
		}
		else if($num == 7){       
			//remarketing only
			
			$insertArray = array(
				"ad_file_name"    => "",
				"custom_ad" => @$_REQUEST["custom_ad"]?: 0,
			);
			
			$ad->set($insertArray);
			
			$cpn->set(array("remarketing_added" => 0));
		}
		
		//set the response information
	}

	$response["selections"]["ads"] = getCampaignAds($num);
	
	return $response;
}




/*
* update the keywords values in the location record
*/
function doCampaignUpdate_keywords($cpn, $response){
	
	$num = $cpn->get("num");
	
	if(@$_REQUEST["keywordsModal"]){
		
		$keywordData = array(
			"keywords"          => @$_REQUEST["keywords"],
			"negative_keywords" => @$_REQUEST["negative_keywords"],
			"default_keywords"  => @$_REQUEST["default_keywords"],
			"unused_defaults"   => @$_REQUEST["unused_defaults"]
		);
		
		$response["selections"]["keywords"]["keywords"]          = $keywordData["keywords"];
		$response["selections"]["keywords"]["negative_keywords"] = $keywordData["negative_keywords"];
		$response["selections"]["keywords"]["default_keywords"]  = $keywordData["default_keywords"];
		$response["selections"]["keywords"]["unused_defaults"]   = $keywordData["unused_defaults"];
		
		updateCampaignKeywords($num, $keywordData);
		
		
	}
	else{
		$kw = getCampaignKeywords($num)->vals();
		$response["selections"]["keywords"]["keywords"]          = $kw["keywords"];
		$response["selections"]["keywords"]["negative_keywords"] = $kw["negative_keywords"];
		$response["selections"]["keywords"]["default_keywords"]  = $kw["default_keywords"];
		$response["selections"]["keywords"]["unused_defaults"]   = $kw["unused_defaults"];
	}
	
	
	return $response;
}



/**
* update the contact information for the campaign
*/
function doCampaignUpdate_contact($cpn, $response){
	
	$num = $cpn->get("num");
	
	if(@$_REQUEST["contactModal"]){
		
		
		$response["selections"]["contact"]["contact_name"]  = mysql_escape(@$_REQUEST["contact_name"]);
		$response["selections"]["contact"]["company_name"]  = mysql_escape(@$_REQUEST["company_name"]);
		$response["selections"]["contact"]["contact_phone"] = mysql_escape(@$_REQUEST["contact_phone"]);
		$response["selections"]["contact"]["contact_email"] = mysql_escape(@$_REQUEST["contact_email"]);
		$response["selections"]["contact"]["comments"]      = mysql_escape(@$_REQUEST["comments"]);
		
		$cpn->set($response["selections"]["contact"]);
	}
	else{
		$response["selections"]["contact"]["contact_name"]  = @$cpn->get("contact_name");
		$response["selections"]["contact"]["company_name"]  = @$cpn->get("company_name");
		$response["selections"]["contact"]["contact_phone"] = @$cpn->get("contact_phone");
		$response["selections"]["contact"]["contact_email"] = @$cpn->get("contact_email");
		$response["selections"]["contact"]["comments"]      = @$cpn->get("comments");
	}
	
	return $response;
}



/**
* update the day/time information for the campaign
*/
function doCampaignUpdate_daytime($cpn, $response){
	
	$num = $cpn->get("num");
	
	$daystimes = getCampaignDaysTimes($num);
	
	if(@$_REQUEST["daytimeModal"]){
		
		
		$response["selections"]["daytime"]["duration_select"]     = (@$_REQUEST["duration"] > 29)? 1 : -1;
		$response["selections"]["daytime"]["start_date"]          = datepickerTimetoSQLTime(@$_REQUEST["start_date"]);
		$response["selections"]["daytime"]["end_date"]            = datepickerTimetoSQLTime(@$_REQUEST["end_date"]);
		$response["selections"]["daytime"]["duration"]            = @$_REQUEST["duration"];
		$response["selections"]["daytime"]["recur"]               = @$_REQUEST["recur"]?: 0;
		//$response["selections"]["daytime"]["weekends"]            = @$_REQUEST["weekends"]?: 0;
		$response["selections"]["daytime"]["day_parting"]         = @$_REQUEST["day_parting"]?: 0;
		$response["selections"]["daytime"]["expiry_notification"] = @$_REQUEST["campaign_expiry_notification"]?: 0;
		
		$daystimes->set($response["selections"]["daytime"]);
		
		$response["selections"]["daytime"]["start_date"]          = @$_REQUEST["start_date"];
		$response["selections"]["daytime"]["end_date"]            = @$_REQUEST["end_date"];
	
	}
	else{

		$response["selections"]["daytime"]["duration_select"]     = $daystimes->get("duration_select");
		$response["selections"]["daytime"]["start_date"]          = sqlToDatepickerTime($daystimes->get("start_date"));
		$response["selections"]["daytime"]["end_date"]            = sqlToDatepickerTime($daystimes->get("end_date"));
		$response["selections"]["daytime"]["duration"]            = $daystimes->get("duration");
		$response["selections"]["daytime"]["recur"]               = $daystimes->get("recur");
		//$response["selections"]["daytime"]["weekends"]            = $daystimes->get("weekends");
		$response["selections"]["daytime"]["day_parting"]         = $daystimes->get("day_parting");
		$response["selections"]["daytime"]["expiry_notification"] = $daystimes->get("campaign_expiry_notification");
	}
	
	return $response;
}



/**
* update the dayyparting information for the campaign
*/
function doCampaignUpdate_daypart($cpn, $response){
	
	$num = $cpn->get("num");
	
	$day_part  = getCampaignDayParting($num);
	$daystimes = getCampaignDaysTimes($num);
	
	if(@$_REQUEST["daytimeModal"] && @$_REQUEST["day_parting"]){ 

		$response["selections"]["daypart"]["dp_preset_opt"] = @$_REQUEST["dp_preset_opt"];
		
		$response["selections"]["daypart"]["dp_st_mon"] = @$_REQUEST["dp_st_mon"];
		$response["selections"]["daypart"]["dp_st_tue"] = @$_REQUEST["dp_st_tue"];
		$response["selections"]["daypart"]["dp_st_wed"] = @$_REQUEST["dp_st_wed"];
		$response["selections"]["daypart"]["dp_st_thu"] = @$_REQUEST["dp_st_thu"];
		$response["selections"]["daypart"]["dp_st_fri"] = @$_REQUEST["dp_st_fri"];
		$response["selections"]["daypart"]["dp_st_sat"] = @$_REQUEST["dp_st_sat"];
		$response["selections"]["daypart"]["dp_st_sun"] = @$_REQUEST["dp_st_sun"];
		
		$response["selections"]["daypart"]["dp_ed_mon"] = @$_REQUEST["dp_ed_mon"];
		$response["selections"]["daypart"]["dp_ed_tue"] = @$_REQUEST["dp_ed_tue"];
		$response["selections"]["daypart"]["dp_ed_wed"] = @$_REQUEST["dp_ed_wed"];
		$response["selections"]["daypart"]["dp_ed_thu"] = @$_REQUEST["dp_ed_thu"];
		$response["selections"]["daypart"]["dp_ed_fri"] = @$_REQUEST["dp_ed_fri"];
		$response["selections"]["daypart"]["dp_ed_sat"] = @$_REQUEST["dp_ed_sat"];
		$response["selections"]["daypart"]["dp_ed_sun"] = @$_REQUEST["dp_ed_sun"];
		
		$day_part->set($response["selections"]["daypart"]);
	}
	else if($daystimes->get("day_parting")){
		
		//@$day_part->get(
		$response["selections"]["daypart"]["dp_preset_opt"] = @$day_part->get("dp_preset_opt");
		
		$response["selections"]["daypart"]["dp_st_mon"] = @$day_part->get("dp_st_mon");
		$response["selections"]["daypart"]["dp_st_tue"] = @$day_part->get("dp_st_tue");
		$response["selections"]["daypart"]["dp_st_wed"] = @$day_part->get("dp_st_wed");
		$response["selections"]["daypart"]["dp_st_thu"] = @$day_part->get("dp_st_thu");
		$response["selections"]["daypart"]["dp_st_fri"] = @$day_part->get("dp_st_fri");
		$response["selections"]["daypart"]["dp_st_sat"] = @$day_part->get("dp_st_sat");
		$response["selections"]["daypart"]["dp_st_sun"] = @$day_part->get("dp_st_sun");
		
		$response["selections"]["daypart"]["dp_ed_mon"] = @$day_part->get("dp_ed_mon");
		$response["selections"]["daypart"]["dp_ed_tue"] = @$day_part->get("dp_ed_tue");
		$response["selections"]["daypart"]["dp_ed_wed"] = @$day_part->get("dp_ed_wed");
		$response["selections"]["daypart"]["dp_ed_thu"] = @$day_part->get("dp_ed_thu");
		$response["selections"]["daypart"]["dp_ed_fri"] = @$day_part->get("dp_ed_fri");
		$response["selections"]["daypart"]["dp_ed_sat"] = @$day_part->get("dp_ed_sat");
		$response["selections"]["daypart"]["dp_ed_sun"] = @$day_part->get("dp_ed_sun");
	}
	
	return $response;
}




/**
* Update only the adwords ads for a campaign
*/
function doCampaignUpdate_adwords($ads, $cpnNum){
	
	//first we will clear all the adwords ads for the campaign
	resetCampaignAds($cpnNum);
	$adw    = new MultiRecordType("adwords_campaign");
	
	//store each one
	foreach($ads as $ad){
		
		$ad["campaign"] = $cpnNum;
		$adw->create($ad);
	}
	
	return true;
}





/**
*
*/
function userOwnsCpn($num){
	
	GLOBAL $AGENT_TYPE, $CURRENT_USER;
	
	if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO") return true; //only admins and CEOs can access any campaign

	
	$opts = array(
		"where" => mysql_escapef("user = ? AND num = ?", $CURRENT_USER["num"], $num),
		"limit" => 1
	);

	
	$record = new MultiRecordType("campaign_component", $opts, true);
	if($record->meta()["noRecordsFound"]) return false;
	
	//showme($record[0]);
	
	return true;
}




/**
*
*/
function getDefaultCampaignUpdateArray(){
	
	return array(
		"title"          => null,
		"vendor"         => null,
		/*"province"       => null,*/
		"industry"       => null,
		"budget"         => null,
		"leads"          => null,
		"user_completed" => null,
	);
}




/**
*
*/
function getDefaultCampaignReturnArray(){
	
	return array(
		"title"          => null,
		"vendor:label"   => null,
		/*"province:label" => null,*/
		"industry:label" => null,
		"budget"         => null,
		"leads"          => null,
		"user_completed" => null,
	);
}




/**
*
*/
function calculateCampaignLeads_num($num, $new_budget = 0, $saveToCpn = false){
		
	$opts = array(
		"where" => mysql_escapef("num = ?", $num),
		"limit" => 1
	);
	
	$cpn       = new MultiRecordType("campaign_component", $opts, true);
	$cpn       = $cpn->records()[0];
	
	$industry  = $cpn->get("industry");
	//$province  = $cpn->get("province");
	$budget    = $new_budget?: $cpn->get("budget");
	
	
	$locations = new MultiRecordType("campaign_locations", array("where" => "campaign = $num"), true);
	$locations = $locations->records();
	
	$leads = calculateCampaignLeads_data(null, $industry, $budget, $locations);
	
	if($saveToCpn) $cpn->set(array("leads" => $leads));
	
	return $leads;
}




/**
*
*/
function calculateCampaignLeads_data($province, $industry, $budget, $locations){
	
	$leads     = 0;
	$cpcs      = array();
	
	if(!$province && empty($locations) || !$industry || !$budget) return $leads;
	
	else{
		
		if(!$locations){
			
			$locations = new MultiRecordType("locations", array("where" => "province = $province"), true);
			
			$locations = $locations->records();
			
			foreach($locations as $loc){
				array_push($cpcs, $loc->get("cost_factor"));
			}
		}
		else{
			
			//get the cpc value for each location for this cpn's industry and province
			foreach($locations as $cpnloc){
				
				$loc_num = $cpnloc->get("location");
				//get the locaton record
				$loc = new Record("locations", $loc_num);
				
				array_push($cpcs, $loc->get("cost_factor"));
			}
		}
		
		
		//get the industry CF
		$ind   = new Record("industries", $industry);
		$indCF = $ind->get("cost_factor");
		
		//find the avg of the cpc values in the array
		$total  = array_sum($cpcs);                    //the sum of the location CFs
		$count  = count($cpcs);                        //the number of locations
		$loccpc = number_format($total/$count, 2);     //the average CF for all the locations
		$avgcpc = number_format($loccpc * $indCF, 2);  //the CPC given average location CF and the industry CF
		$leads  = floor($budget/$avgcpc);               //final leads count
		
		/*
		echo "Province: $province<br />";
		echo "Industry: $industry<br />";
		echo "Budget: $budget<br />";
		echo "# of locations: $count<br />";
		echo "total CPC: $total<br />";
		echo "Location Average CPC: $loccpc<br />";
		echo "Industry CF: $indCF<br />";
		echo "Overall Average CPC: $avgcpc<br />";
		echo "Total Leads: $leads<br /><br />";
		*/
	}
	return $leads;
}




/**
*
*/
function getCampaignLocations($cpnNum){
	
	$locations = new MultiRecordType("campaign_locations", array("where" => "campaign = $cpnNum"), true);
	
	$result = array(
		"preset"    => "",
		"locations" => array(),
	);
	
	
	$locations = $locations->records();
	
	foreach($locations as $l){
		
		if($l->get("preset_text")) $result["preset"] = $l->get("preset_text");
		else array_push($result["locations"], $l);
	}
	
	return $result;
}




/**
*
*/
function getCampaignKeywords($cpnNum){
	
	$keywords = new MultiRecordType("campaign_keywords", array("where" => "campaign = $cpnNum", "limit" => 1), true);
	return $keywords->records()[0];
}




/**
*
*/
function getCampaignDaysTimes($cpnNum, $createOnFail = true){
	
	$daystimes = new MultiRecordType("campaign_days_times", array("where" => "campaign = $cpnNum", "limit" => 1), true);
	$result = @$daystimes->records()[0];
	
	if(!$result && $createOnFail){
		
		$num     = createDefaultDateTimeCampaignRecord($cpnNum);
		$daystimes->load(array("where" => "num = $num"));
		$result  = @$daystimes->records()[0];
	}
	
	return $result;
}





/**
*
*/
function getCampaignDayParting($cpnNum, $createOnFail = true){
	
//createDefaultDayPartCampaignRecord($num)
	$daypart = new MultiRecordType("campaign_day_parting", array("where" => "campaign = $cpnNum", "limit" => 1), true);
	$result = @$daypart->records()[0];
	if(!$result && $createOnFail){
		
		$num     = createDefaultDayPartCampaignRecord($cpnNum);
		$daypart->load(array("where" => "num = $num"));
		$result = @$daypart->records()[0];
	}
	return $result;
}





/**
*
*/
function getCampaignAds($cpnNum){
	
	$where       = array("where" => "campaign = $cpnNum");
	$adwords     = new MultiRecordType("adwords_campaign",            $where, true);
	$display     = new MultiRecordType("google_display_campaign",     $where, true);
	$remarketing = new MultiRecordType("google_remarketing_campaign", $where, true);
	
	$result = array(
		"adwords"     => $adwords->records(),
		"display"     => $display->records(),
		"remarketing" => $remarketing->records(),
	);
	
	return $result;
}






/**
* Return array of unique preset values from the locations
* in the given province
*/
function getPresetListForProvince($province){
	
	$presets   = array();
	$opts      = array(
		"where"   => mysql_escapef("province = ?", $province),
		"orderBy" => "title DESC"
	);
	
	$locations = new MultiRecordType("locations", $opts, true);
	if($locations->meta()["noRecordsFound"]) return $presets;
	
	$presets = getPresetListFromLocations($locations->records());
	return $presets;
}






/**
* Return array of unique preset values from the given locations
*/
function getPresetListFromLocations($locations = array()){
	
	$presets = array();
	
	foreach(@$locations as $l){
		
		$loc_preset = $l->get("division");
		
		
		if(!in_array($loc_preset, $presets) && $loc_preset != "") array_push($presets, $loc_preset);
	}
	
	sort($presets, SORT_STRING);
	
	foreach($presets as $i=>$p){
		
		unset($presets[$i]);
		$presets[$p] = $p;
	} 
	return $presets;
}




/**
*
*/
function getLocationsForProvince($province, $filterFields = array()){
	
	
	$opts = array(
		"where" => mysql_escapef("province = ?", $province),
		"orderBy" => "title ASC"
	);
	$locations = new MultiRecordType("locations", $opts, true);
	if($locations->meta()["noRecordsFound"]) return null;
	
	
	if(!empty($filterFields)){
		
		$locations->filterRecordFields($filterFields);
		return $locations->records();//new Record("locations", $locations->values());
	}
	
	return $locations->records();

}




/**
*
*/
function getKeywordsForIndustry($industry, $num = 0){
	
	if(!$num) $num = $GLOBALS["DEFAULT_KW_COUNT"];
	
	$opts = array(
		"where" => mysql_escapef("industry = ?", $industry),
		"limit" => 1
	);
	$keywords = new MultiRecordType("industry_keywords", $opts, true);
	
	if($keywords->meta()["noRecordsFound"]) return null;
	
	$record = $keywords->records()[0];
	
	//return only the top kewyords
	//$GLOBALS["DEFAULT_KW_COUNT"]
	
	$terms = array_slice(explode(",", $record->get("terms")), 0, $num);
	return $terms;
	
}




/**
*
*/
function getCheckedLocationValues($selectedLocations){
	
	$checkedLocs = array();
	if(@$selectedLocations){
		foreach($selectedLocations as $loc) if(!$loc->get("preset_text")) array_push($checkedLocs, $loc->get("location"));
	} 
	
	return $checkedLocs;
}




/**
*
*/
function getLocationTitles($records){

	$location_titles = array();
	foreach($records as $l){
		
		$num = $l->get("location");
		$location = new Record("locations", $num);
		//showme($location);
		array_push($location_titles, $location->get("title"));
	} 

	return $location_titles;
}




/**
*
*/
function getDayPartingDetails($cpnNum){
	
	$dayparting = getCampaignDayParting($cpnNum);
		
	$result = array(
		"dp_preset_opt" => ($dayparting)? $dayparting->get("dp_preset_opt") : "none",
		"days"          => array(
			array(
				"name" => "Monday",
				"start" => array(
					"col" => "dp_st_mon",
					"val" => ($dayparting)? $dayparting->get("dp_st_mon") : 6,
				),
				"end" => array(
					"col" => "dp_ed_mon",
					"val" => ($dayparting)? $dayparting->get("dp_ed_mon") : 23,
				),
			),
			array(
				"name" => "Tuesday",
				"start" => array(
					"col" => "dp_st_tue",
					"val" => ($dayparting)? $dayparting->get("dp_st_tue") : 6,
				),
				"end" => array(
					"col" => "dp_ed_tue",
					"val" => ($dayparting)? $dayparting->get("dp_ed_tue") : 23,
				),
			),
			array(
				"name" => "Wednesday",
				"start" => array(
					"col" => "dp_st_wed",
					"val" => ($dayparting)? $dayparting->get("dp_st_wed") : 6,
				),
				"end" => array(
					"col" => "dp_ed_wed",
					"val" => ($dayparting)? $dayparting->get("dp_ed_wed") : 23,
				),
			),
			array(
				"name" => "Thursday",
				"start" => array(
					"col" => "dp_st_thu",
					"val" => ($dayparting)? $dayparting->get("dp_st_thu") : 6,
				),
				"end" => array(
					"col" => "dp_ed_thu",
					"val" => ($dayparting)? $dayparting->get("dp_ed_thu") : 23,
				),
			),
			array(
				"name" => "Friday",
				"start" => array(
					"col" => "dp_st_fri",
					"val" => ($dayparting)? $dayparting->get("dp_st_fri") : 6,
				),
				"end" => array(
					"col" => "dp_ed_fri",
					"val" => ($dayparting)? $dayparting->get("dp_ed_fri") : 23,
				),
			),
			array(
				"name" => "Saturday",
				"start" => array(
					"col" => "dp_st_sat",
					"val" => ($dayparting)? $dayparting->get("dp_st_sat") : 6,
				),
				"end" => array(
					"col" => "dp_ed_sat",
					"val" => ($dayparting)? $dayparting->get("dp_ed_sat") : 23,
				),
			),
			array(
				"name" => "Sunday",
				"start" => array(
					"col" => "dp_st_sun",
					"val" => ($dayparting)? $dayparting->get("dp_st_sun") : 6,
				),
				"end" => array(
					"col" => "dp_ed_sun",
					"val" => ($dayparting)? $dayparting->get("dp_ed_sun") : 23,
				),
			),
		),

	);

	
	return $result;
}




/**
*
*/
function getDayPartingOptions(){
	
	return array(
		"Show all day" => "-2",
		"Do not show"  => "-1",
		"12:00 am"     => "1",
		"1:00 am"      => "2",
		"2:00 am"      => "3",
		"3:00 am"      => "4",
		"4:00 am"      => "5",
		"5:00 am"      => "6",
		"6:00 am"      => "7",
		"7:00 am"      => "8",
		"8:00 am"      => "9",
		"9:00 am"      => "10",
		"10:00 am"     => "11",
		"11:00 am"     => "12",
		"12:00 pm"     => "13",
		"1:00 pm"      => "14",
		"2:00 pm"      => "15",
		"3:00 pm"      => "16",
		"4:00 pm"      => "17",
		"5:00 pm"      => "18",
		"6:00 pm"      => "19",
		"7:00 pm"      => "20",
		"8:00 pm"      => "21",
		"9:00 pm"      => "22",
		"10:00 pm"     => "23",
		"11:00 pm"     => "24",
	);
}




/**
*
*/
function getDayPartingPreset_day(){

	$result = array(
		"dp_preset_opt" => "day",
		"days"          => array(
			array(
				"name" => "Monday",
				"start" => array(
					"col" => "dp_st_mon",
					"val" => 10,
				),
				"end" => array(
					"col" => "dp_ed_mon",
					"val" => 18,
				),
			),
			array(
				"name" => "Tuesday",
				"start" => array(
					"col" => "dp_st_tue",
					"val" => 10,
				),
				"end" => array(
					"col" => "dp_ed_tue",
					"val" => 18,
				),
			),
			array(
				"name" => "Wednesday",
				"start" => array(
					"col" => "dp_st_wed",
					"val" => 10,
				),
				"end" => array(
					"col" => "dp_ed_wed",
					"val" => 18,
				),
			),
			array(
				"name" => "Thursday",
				"start" => array(
					"col" => "dp_st_thu",
					"val" => 10,
				),
				"end" => array(
					"col" => "dp_ed_thu",
					"val" => 18,
				),
			),
			array(
				"name" => "Friday",
				"start" => array(
					"col" => "dp_st_fri",
					"val" => 10,
				),
				"end" => array(
					"col" => "dp_ed_fri",
					"val" => 18,
				),
			),
			array(
				"name" => "Saturday",
				"start" => array(
					"col" => "dp_st_sat",
					"val" => -1,
				),
				"end" => array(
					"col" => "dp_ed_sat",
					"val" => -1,
				),
			),
			array(
				"name" => "Sunday",
				"start" => array(
					"col" => "dp_st_sun",
					"val" => -1,
				),
				"end" => array(
					"col" => "dp_ed_sun",
					"val" => -1,
				),
			),
		),

	);

	
	return $result;
}




/**
*
*/
function getDayPartingPreset_evening(){
	
	$result = array(
		"dp_preset_opt" => "evening",
		"days"          => array(
			array(
				"name" => "Monday",
				"start" => array(
					"col" => "dp_st_mon",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_mon",
					"val" => 23,
				),
			),
			array(
				"name" => "Tuesday",
				"start" => array(
					"col" => "dp_st_tue",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_tue",
					"val" => 23,
				),
			),
			array(
				"name" => "Wednesday",
				"start" => array(
					"col" => "dp_st_wed",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_wed",
					"val" => 23,
				),
			),
			array(
				"name" => "Thursday",
				"start" => array(
					"col" => "dp_st_thu",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_thu",
					"val" => 23,
				),
			),
			array(
				"name" => "Friday",
				"start" => array(
					"col" => "dp_st_fri",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_fri",
					"val" => 23,
				),
			),
			array(
				"name" => "Saturday",
				"start" => array(
					"col" => "dp_st_sat",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_sat",
					"val" => 23,
				),
			),
			array(
				"name" => "Sunday",
				"start" => array(
					"col" => "dp_st_sun",
					"val" => 18,
				),
				"end" => array(
					"col" => "dp_ed_sun",
					"val" => 23,
				),
			),
		),

	);

	
	return $result;
}




/**
*
*/
function getDayPartingOptionNameFromValue($value){
	
	$options = getDayPartingOptions();
	$value .= "";
	
	foreach($options as $i=>$o){
		
		if($o == $value) return $i;
	}
	
}




/**
*
*/
function datepickerTimetoSQLTime($string){
	
	if(!$string) return "0000-00-00 00:00:00";
	$string = explode("/", $string);
	$string = "{$string[2]}-{$string[1]}-{$string[0]} 00:00:00";
	return $string;
}




/**
*
*/
function sqlToDatepickerTime($string){
	
	if(!$string) return "";
	$string = explode(" ", $string)[0];
	$string = explode("-", $string);
	$string = "{$string[2]}/{$string[1]}/{$string[0]}";
	return $string;
}




function campaign_processUpload(){
	
	$f = $_FILES;
	$r = $_REQUEST;
	
	$num = intval($_REQUEST["cpn"]);
	$cpn = new MultiRecordType("campaign_component", array("where" => "num = $num"), true);
	
	$cpn = $cpn->records()[0];
	$vNum = $cpn->get("vendor");
	
	$savePath = $f['file']['tmp_name'];
	
	if($vNum == 1){          //google adwords upload - with remarketing added
		
		$table = "google_remarketing_campaign";
		$rm    = createDefaultRemarketing($num, false, true);
		$rNum  = $rm->get("num");
	}
	else if($vNum == 2){     //display ads - could be remarketing added or display upload
		
		
		//first create or fetch the display record 
		$display = createDefaultDisplay($num);
		
		if($_REQUEST["rm"] != "0"){
			$rm = createDefaultRemarketing($num, false, true);
			
			$table = "google_remarketing_campaign";
			$rNum  = $rm->get("num");
		}
		else{
			$table = "google_display_campaign";
			$rNum  = $display->get("num");
		}
		
		
		
	}
	else if($vNum == 7){       //remarketing only upload
	
		$table = "google_remarketing_campaign";
		$rm    = createDefaultRemarketing($num);
		$rNum  = $rm->get("num");
	}
	
	
	removeUploads(mysql_escapef("tableName = ? AND recordNum = ?", $table, $rNum));
	$imageErrors = saveUploadFromFilepath($table, 'user_display_ad', $rNum, '', $savePath);
	
	
	
	$t = array($f, $r, $vNum, $imageErrors);
	echo json_encode($t);
	exit;
}



function campaign_isReadyForCheckout($cpn){
	
	$num    = $cpn->get("num");
	$vendor = $cpn->get("vendor");
	$add_rm = $cpn->get("remarketing_added");
	
	$required_fields_campaign_component = array(
		"title"         => $GLOBALS["ADROCKET_DEFINITIONS"]["Title Validation Error"],
		"budget"        => $GLOBALS["ADROCKET_DEFINITIONS"]["Budget Validation Error"],
		"vendor"        => $GLOBALS["ADROCKET_DEFINITIONS"]["Vendor Validation Error"],
		"industry"      => $GLOBALS["ADROCKET_DEFINITIONS"]["Industry Validation Error"],
		/*"province"      => $GLOBALS["ADROCKET_DEFINITIONS"]["Province Validation Error"],*/
	);
	
	//evaluate the required fields for campaign component here
	foreach($required_fields_campaign_component as $index=>$r){
		
		if($cpn->get($index)) $required_fields_campaign_component[$index] = true;
	}
	

	$required_fields_campaign_component = array_merge($required_fields_campaign_component, campaign_validateContactInfo($cpn));
	
	
	//evaluate the related records
	$required_fields_campaign_component["locations"] = campaign_validateLocations($num);
	$required_fields_campaign_component["dates"]     = campaign_validateDaysTimes($num);
	$required_fields_campaign_component["ads"]       = campaign_validateAds($num, $vendor, $add_rm);
	
	
	return $required_fields_campaign_component;
}





//validate the contact info for the campaign
function campaign_validateContactInfo($cpn){
	
	$required_fields_campaign_component = array(
		"contact_name"  => false,
		"contact_email" => false,
		"contact_phone" => false,
		"company_name"  => false,
	);
	
	
	//evaluate the required fields for campaign component here
	foreach($required_fields_campaign_component as $index=>$r){
		
		if($cpn->get($index)) $required_fields_campaign_component[$index] = true;
	}
	
	
	//set the contact info missing error
	if(
		!$required_fields_campaign_component["contact_name"]  ||
		!$required_fields_campaign_component["contact_email"] ||
		!$required_fields_campaign_component["contact_phone"] ||
		!$required_fields_campaign_component["company_name"]
	){
		unset($required_fields_campaign_component["contact_name"]);
		unset($required_fields_campaign_component["contact_email"]);
		unset($required_fields_campaign_component["contact_phone"]);
		unset($required_fields_campaign_component["company_name"]);
		$required_fields_campaign_component["contact"] = $GLOBALS["ADROCKET_DEFINITIONS"]["Contact Validation Error"];
	}
	
	
	return $required_fields_campaign_component;
	
}







function campaign_validateLocations($cpnNum){
	
	$valid     = $GLOBALS["ADROCKET_DEFINITIONS"]["Location Validation Error"];
	$opts      = array("where" => "campaign = $cpnNum");
	$locations = new MultiRecordType("campaign_locations", $opts, true);
	
	if(!$locations->meta()["noRecordsFound"]) $valid = true;
	
	return $valid;
}


function campaign_validateDaysTimes($cpnNum){
	
	$valid = $GLOBALS["ADROCKET_DEFINITIONS"]["Dates Validation Error"];
	$opts  = array("where" => "campaign = $cpnNum");
	$daystimes = new MultiRecordType("campaign_days_times", $opts, true);
	
	if($daystimes->meta()["noRecordsFound"]) return $valid;
	
	$daystimes = $daystimes->records()[0];
	$start     = $daystimes->get("start_date");
	$end       = $daystimes->get("end_date");
	$recur     = $daystimes->get("recur");
	
	if(!$start || $start == "0000-00-00 00:00:00") return $valid;            //no start date
	if((!$end || $end == "0000-00-00 00:00:00") && !$recur) return $valid;    //no valid end selection
	
	return true;
}



function campaign_validateAds($cpnNum, $vendorNum, $add_rm){
	
	$valid = $GLOBALS["ADROCKET_DEFINITIONS"]["Ads Validation Error"];
	$opts  = array("where" => "campaign = $cpnNum");
	$table = "";
	
	if(!$vendorNum) return $valid;
	
	if($vendorNum == 1)      $table = "adwords_campaign";
	else if($vendorNum == 2) $table = "google_display_campaign";
	else if($vendorNum == 7) $table = "google_remarketing_campaign";

	$ads = new MultiRecordType($table, $opts, true);
	if($ads->meta()["noRecordsFound"]) return $valid;
	
	if($add_rm){
		
		$ads = new MultiRecordType("google_remarketing_campaign", $opts, true);
		if($ads->meta()["noRecordsFound"]) return $valid;
	}
	
	return true;
}



function vendor_getLogo($vendor_num){
	
	$opts   = array("where" => mysql_escapef("num = ?", $vendor_num));
	$vendor = new MultiRecordType("vendors", $opts, true);
	
	if($vendor->meta()["noRecordsFound"]) return "";
	
	$vendor = $vendor->records()[0];
	
	$logo = $vendor->get("logo");
	return $logo[0]["thumbUrlPath"];
}



function filter_buildFilterWhere($request){
	
	$where = "";
	if(!$request) return $where;
	
	
	foreach($request as $index=>$value){
		
		if(!$value) continue;
		if($index == "omit-user-progress"){
			
			$where .= " AND admin_status <> 1";
		}
		
		if(strpos($index, "filter-") !== FALSE){
			
			$i = explode("-", $index)[1];
			if($i == "title") $where .= " AND title LIKE '%$value%'";
			else              $where .= " AND $i = $value";
		}
	}
	
	return $where;
}




function campaign_Delete($cpnNum){
	
	if(!userOwnsCpn($cpnNum)) return false;
	
	
	resetCampaignAds($cpnNum, true);
	
	
	$opts_cpn = array("where" => "num = $cpnNum");
	$opts_oth = array("where" => "campaign = $cpnNum");
	
	$cpn       = new MultiRecordType("campaign_component",   $opts_cpn, true);
	$locations = new MultiRecordType("campaign_locations",   $opts_oth, true);
	$daystimes = new MultiRecordType("campaign_days_times",  $opts_oth, true);
	$daypart   = new MultiRecordType("campaign_day_parting", $opts_oth, true);
	$keywords  = new MultiRecordType("campaign_keywords",    $opts_oth, true);
	
	
	$toClear = array(
		$cpn->records(),
		$locations->records(),
		$daystimes->records(),
		$daypart->records(),
		$keywords->records(),
	);
	
	foreach($toClear as $records){
		
		if(!$records) continue;
		foreach($records as $record) $record->del();
	}
	
	return true;
}




function manager_UpdateAvailability(){
	
	global $CURRENT_USER;
	
	$user = new MultiRecordType("accounts", array("where" => "num = {$CURRENT_USER["num"]}"), true);
	$user = $user->records()[0];
	$time = null;
	$status = null;
	
	
	if(@$_REQUEST['val']){   //the user makes themself available for campaign
		
		$time = mysql_datetime();
		$status = "yes";
		
		$user->set(array(
			"available_manager" => 1,
			"available_on"      => $time,
		));
	}
	else{   //user makes themself unavailable for campaigns
	
		$status = "no";
		$user->set(array(
			"available_manager" => 0,
			"available_on"      => "0000-00-00 00:00:00",
		));
	}
	
	if($time) $time = date("l, F j Y g:i A", strtotime($time));
	
	$result = array("response" => "success", "status" => $status, "time" => $time, "debug" => $_REQUEST);
	
	echo json_encode($result);
	exit();
}



function admin_getAvailableManagers(){
	
	$opts = array(
		"where" => mysql_escapef("account_type = ? AND available_manager = 1", "Manager"),
		"orderBy" => "available_on ASC"
	);
	
	$managers = new MultiRecordType("accounts", $opts, true);
	
	return $managers->records();
}



function admin_assignCampaignToManager(){
	
	$campaignNum = $_REQUEST['num'];
	$time        = mysql_datetime();
	
	$managers = admin_getAvailableManagers();
	$campaign = new MultiRecordType("campaign_component", array("where" => "num = $campaignNum"), true);
	
	$campaign = $campaign->records()[0];
	
	//showme($managers);
	
	if($managers && $campaign){
		
		$manager     = $managers[0];
		$managerNum  = $manager->get("num");
		$managerName = $manager->get("fullname");
		
		$insert = array(
			"campaign_status"          => 3,
			"admin_status"             => 5,
			"admin_vendor_create"      => $CURRENT_USER['num'],
			"admin_vendor_create_date" => $time,
			"manager_acquired"         => $managerNum,
			"manager_acquired_date"    => $time,
			"management_url"           => $_REQUEST['management_url']
		);
		
		$campaign->set($insert);
		$manager->set(array("available_manager" => 0, "available_on" => "0000-00-00 00:00:00"));
		return "<span class='label label-success'>Successfully Assigned to manager: $managerNum - $managerName.</span><br />";
	}
	else{
		
		return "<span class='label label-danger'>There was an error assigning a manager - contact site admin.</span><br />";
	}
}



function manager_submitCampaignBuild(){
	
	$campaignNum = $_REQUEST['num'];
	$time        = mysql_datetime();
	
	$campaign = new MultiRecordType("campaign_component", array("where" => "num = $campaignNum"), true);
	$campaign = $campaign->records()[0];
	
	if($campaign){
		
		$insert = array(
			"campaign_status"              => 3,
			"admin_status"                 => 6,
			"manager_completed"            => 1,
			"manager_vendor_complete_date" => $time,
		);
		
		$campaign->set($insert);
		return "<span class='label label-success'>Successfully Submitted Build.</span><br />";
	}
	else{
		
		return "<span class='label label-danger'>There was an error submitting this build - contact site admin.</span><br />";
	}
}





function ads_googleSearch_saveAd($ad, $cpnNum){
	
	
	$adNum              = $ad["adNum"];
	$newAd              = ($adNum == "" || $adNum == "0")? true : false;    //no number passed means we create a new ad
	$opts               = null;
	$insert             = @$ad["detail"];
	$insert["campaign"] = $cpnNum;
	
	if(!$newAd) $opts = array("where" => "num = $adNum");                 //a number passed means we set it in the query options
	
	$adw = new MultiRecordType("adwords_campaign", $opts, !$newAd);       //load the records
	
	
	if($newAd){
		
		$newNum = $adw->create($insert);
		$insert["adNum"] = $newNum;
	} 
	else{
		
		$insert    = $ad["detail"];
		$ad_record = $adw->records()[0];
		$ad_record->set($insert);
		
		$insert["adNum"] = $adNum;
	}
	
	
	
	//$insert = $ad;
	
	
	echo json_encode($insert);
	exit;
}



function ads_googleSearch_deleteAd($adNum, $cpnNum){
	
	
	$where              = "num = $adNum";
	$adw                = new MultiRecordType("adwords_campaign");       //load the record
	
	$adw->del($where);	
	
	$r = array("result" => 1);
	
	echo json_encode($r);
	exit;
}





function dashboard_listCampaignPanels($campaigns){
	
	
	GLOBAL $AGENT_TYPE;
	
	foreach($campaigns as $c){


		$num       = $c[0]->get("num");
		$created   = $c[0]->get("createdDate");
		$status    = $c[0]->get("campaign_status:label");
		$stat_num  = $c[0]->get("campaign_status");
		$title     = $c[0]->get("title")?: "[Title not set]";
		$vendor    = $c[0]->get("vendor:label")?: "[Vendor not chosen]";
		$logo      = $c["vendor_logo"];
		$is_active = $c[0]->get("is_active:text");
		$active    = $c[0]->get("is_active");
		$start     = $c[1]->get("start_date");
		$end       = $c[1]->get("end_date");
		
		
		if(!$start || $start == "0000-00-00 00:00:00") $start = "[None]";
		else $start = date("D, M jS Y", strtotime($start));
		
		if(!$end || $end == "0000-00-00 00:00:00") $end = "[None]";
		else $end = date("D, M jS Y", strtotime($end));
		
		if($end == "[None]" && $c[1]->get("recur")) $end = "Recurring";
		
		include $_SERVER["DOCUMENT_ROOT"] . "/assets/_campaign_panel.php";
		
	}
	
	return false;
}



?>