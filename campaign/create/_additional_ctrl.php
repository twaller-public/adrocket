<?php


/** PAGE INFO

	The Adrocket Wizard Addtional Settings control file.
	
	The user makes selection for any addtional parameters specific to the chosen vendor.
	
	Currently only in use for the Google search network as a keyword selection tool.
	
	
	Once a user selects an Industry, and the google search network as a vendor a keywords record
	is automatically created for that campaign. The user has the option of adjusting the default
	settings to suit their campaign, or they can just continue with the defaults.
	
	There are four keyword types:
	
		- Default keywords: The keywords selected by adrocket to be the
		  best for that industry in general.
		  
		- Unused Default keywords: The default keywords that the user has
		  unselected, and does not want to be part of their campaign.
		  
		- Custom Keywords: Additonal keywords that the user wishes to add 
		  to their campaign that are not in the default list -- Advanced Option
		  
		- Negative Keywords: Keywords the user has selected that will be
		purposfully avoided for matching search result -- Advanced Option

	
	On Submission we store the submitted keyword data in the record and pass the user on to the next step.
	


Loading:
	
	1. Load app init
	
	2. Check agent permissions
	
	3. Initiate the errors variable
	
	4. Set page variables (field names, next step, etc)
	
	5. Set the mini-nav bar to show
	
	6. Set the breadcrumb stage value
	
	7. Load the campaign, or create a new one if no number is found
	
	8. Handle Form Submission
		a. See submission section
		
	9. Get the campaign keyword information
	
	10. Get all the keyword default options that the user can choose from
		NOTE: These are the default keywords for the selected industry
		
	
	11. Determine if we are showing the advanced options section on load.

	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check that a keyword record for the campaign exists
		
		If this information is not available, we set an error

		
	3. If no error, we save the keyword data given in the submission


	4. Determine the user destination
	
		a. If there are errors stay on this page, reload the campaign, create a default keyword record.
		
		b. If there are no errors, save the information provided, direct to the nerxt stage in the process
		
*/



/**
* Load app init
*/
	require_once "../../assets/_app_init.php";
	
	
	
/**
* Deterine access permissions - managers may not create campaigns
*/
	blockAgentType("MANAGER");
	
	
/**
*	Determine whether the user needs to captcha verify before they can create a campaign
*/
	require("_captcha-intercept_ctrl.php");
	
	
/**
* Page Variables
*/

	$errors = false;


	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";
	
	
	//record names
	$CPN_KEYWORD_TABLE_NAME = "campaign_keywords";
	$IND_KEYWORD_TABLE_NAME = "industry_keywords";
	
	
	//record field names
	$IND_KW_TERMS_FIELD = "terms";
	
	$CPN_KW_DEFAULTS_FIELD   = "default_keywords";
	$CPN_KW_UNUSED_DEF_FIELD = "unused_defaults";
	$CPN_KW_ADDITIONAL_FIELD = "additional_keywords";
	$CPN_KW_NEGATIVE_FIELD   = "negative_keywords";
	
	
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "contact.php";


	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "5"; 	
	


	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/

	$campaign = getCampaginForCreate(false);
	if(!$campaign) die("campaign could not be found");
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	
	
	
	

	
	

/**
* Submission
*/

	if(@$_REQUEST["Continue"]){
		
		//showme($_REQUEST);
		
		$contAct = $_REQUEST["Continue"];
		
		unset($_REQUEST["Continue"]);
		unset($_REQUEST["custom-keywords"]);
		unset($_REQUEST["negative-keywords"]);
		
		
		//echo $contAct;
		
		
		//if(!@$_REQUEST["has_additional"] || $_REQUEST["has_additional"] == "0") $_REQUEST["additional_keywords"] = "";
		//unset($_REQUEST["has_additional"]);
		updateCampaignKeywords($cpnNum, $_REQUEST);
		
		//$errors = true;
		if(!$errors && $contAct == "Continue") header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
		

	}
	
	
	
	
	
	
	
	
/**
* Get the keywords for the campaign/industry, if set
*/

	$industry                = $campaign->get("industry");
	$industryTitle           = $campaign->get("industry:label");
	$user_submitted_industry = $campaign->get("user_submitted_industry");
	
	//echo $user_submitted_industry;
	
	//DUMMY SETTINGS - REPLACE ME
	//$industry = null;
	
	if(!$industry ) header("Location: what.php?num=$cpnNum");
	
	

/**
* Fetch all keyword options for the Industry
* Set the selected industry as the default choice, if it is set
*/


	//if the industry is user submtted, we won't have any default keywords to load. skip that step
	if(!$user_submitted_industry){
		
		
	}
	
	$keywordData = getCampaignKeywords($cpnNum);
	//showme($keywordData);
	
	//reset the keyword data if it has not been filled in
	if(
		!$keywordData->get("default_keywords")    &&
		!$keywordData->get("additional_keywords") &&
		!$keywordData->get("keywords")
	)
	{ 
		resetCampaignKeywords($cpnNum, $industry); 
		$keywordData = getCampaignKeywords($cpnNum);
	}
	
	
	
	//get the html display for keywords
	$keywords            = trim($keywordData->get("keywords"),            ", ");
	$additional_keywords = trim($keywordData->get("additional_keywords"), ", ");
	$negative_keywords   = trim($keywordData->get("negative_keywords"),   ", ");
	//$custom_keywords     = trim($keywordData->get("custom_keywords"),     ", ");
	$default_keywords    = trim($keywordData->get("default_keywords"),    ", ");
	$unused_defaults     = trim($keywordData->get("unused_defaults"),     ", ");

	$keywords            = $keywords?            explode(",", $keywords)            : array();
	$additional_keywords = $additional_keywords? explode(",", $additional_keywords) : array();
	$negative_keywords   = $negative_keywords?   explode(",", $negative_keywords)   : array();
	//$custom_keywords     = $custom_keywords?     explode(",", $custom_keywords)     : array();
	$default_keywords    = $default_keywords?    explode(",", $default_keywords)    : array();
	$unused_defaults     = $unused_defaults?     explode(",", $unused_defaults)     : array();


	$additional_default_keywords = getKeywordsForIndustry($industry, 25);
	$additional_default_keywords = @array_slice($additional_default_keywords, $GLOBALS["DEFAULT_KW_COUNT"], 10, null);

	if(empty($unused_defaults)) $unused_defaults = $additional_default_keywords;
	
	foreach($unused_defaults as $i=>$ud){
		
		if(in_array($ud, $default_keywords)) unset($unused_defaults[$i]);
	}

	//$keywordHTML          = getKeywordCheckBoxTableRows($keywords);
	//$negative_keywordHTML = getKeywordCheckBoxTableRows($negative_keywords);
	//$default_keywordHTML  = getKeywordCheckBoxTableRows($default_keywords);
	//$unused_defaultHTML   = getKeywordCheckBoxTableRows($unused_defaults);
	
	$blankCheck           = getCheckBoxElement("kw_blank", "", false, true, "", array("disabled" => "disabled"));
	
	//showme($default_keywords);
	
	
	
/**
* Get The other campaign information 
* will be used in the breadcrumbs
*/
	
	$cpnTitle       = @$_REQUEST["title"]  ?: $campaign->get("title");
	$cpnLeads       = $campaign->get("leads");
	$cpnBudget      = $campaign->get("budget");
	
	$locationTitles   = getLocationTitles(getCampaignLocations($cpnNum)["locations"]);
	
	$industry         = $campaign->get("industry");
	$industryTitle    = $campaign->get("industry:label");
	
	$vendor           = $campaign->get("vendor");
	$vendorTitle      = $campaign->get("vendor:label");
	
	$start            = getCampaignDaysTimes($cpnNum, false)->get("start_date");
	
	//determining which ads we use
	$vendor_ads              = getCampaignAds($cpnNum);
	
	if(!$vendor) $vendor_ads = null;
	
	else if($vendor == 1){ $vendor_ads = $vendor_ads["adwords"];     }
	else if($vendor == 2){ $vendor_ads = $vendor_ads["display"];     }
	else if($vendor == 7){ $vendor_ads = $vendor_ads["remarketing"]; }
	
	
	
	$additional = ($vendor == 1)? getCampaignKeywords($cpnNum) : null;
	
	$contact    = campaign_validateContactInfo($campaign);	
	

	
	

?>