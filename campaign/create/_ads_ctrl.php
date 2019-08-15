<?php

/** PAGE INFO

	The Adrocket Wizard Ad Creation Interface control file.

	We determine which vendor type the campaign is using. We then load
	the ad creation interface for that ad type, and all the ads the user
	has made for the campaign (if any). The user can create and submit
	additional ads, or edit/delete exiting ads.
	
	Ads are submitted via ajax, so submission will check the presence of
	ad records for the given ad type and campaign number.
	
	If the user has not selected a Vendor yet, we re-recirdt the user to
	the vendor selection page.


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
		
	9. Get the campaign vendopr and ad information, if any
	
	10. Determine the ad interface the user will use based on the vendor
		
	
	11. --no step--
	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check if the user has selected a vendor and has created ads for the vendor type
	
		a. If no vendor is set, or no ads created we set an error.
		
		b. If there is a vendor set and ads created, we continue

	
	3. Determine the user destination
	
		a. If there are errors stay on this page, reload the campaign
		
		b. If there are no errors, direct to the next stage in the process
		
*/



/* PROCEDURE (LOADING) */


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
* Ajax handler for Google Search network ad submissions
*/
if(@$_REQUEST["google_search_ad_submit"]){
	
	$ad     = @$_REQUEST["ad"];
	$cpnNum = $_REQUEST["cpnNum"];
	
	
	ads_googleSearch_saveAd($ad, $cpnNum);
}




/**
* Ajax handler for Google Search network ad deletions
*/
if(@$_REQUEST["google_search_ad_delete"]){
	
	$adNum  = @$_REQUEST["adNum"];
	$cpnNum = $_REQUEST["cpnNum"];
	
	
	ads_googleSearch_deleteAd($adNum, $cpnNum);
}



	
	
	
/**
* Page Variables
*/


	//errors - is true or false because there is only one possible error on this page
	$errors     = false;
	$error_text = "Please create some ads for your Vendor.";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted
	
	
	//record names
	$GOOGLE_SEARCH_TABLE_NAME     = "adwords_campaign";                                //title of the Google Search network table/records
	$GOOGLE_DISPLAY_TABLE_NAME    = "google_display_campaign";                         //title of the Google Display Network table/records
	$GOOGLE_REMARKET_TABLE_NAME   = "google_remarketing_campaign";                     //title of the Google Remarketing table/records

	
	//record field names
	$VENDOR_FIELD_NAME          = "vendor";                                    //name of the vendor field in campaign record
	$INDUSTRY_LABEL_NAME        = "industry:label";                            //name of the industry text name in campaign record
	$USER_SUBMITTED_FIELD_NAME  = "user_submitted_industry";                   //name of the user submitted industry field in campaign record
 
 
	//vendor labels
	$GOOGLE_SEARCH_LABEL   = "adwords";
	$GOOGLE_DISPLAY_LABEL  = "display";
	$GOOGLE_REMARKET_LABEL = "remarketing";
	
	
	//vendor forms
	$GOOGLE_SEARCH_FORM   = "_google_search.php";
	$GOOGLE_DISPLAY_FORM  = "_google_display.php";
	$GOOGLE_REMARKET_FORM = "_google_remarket.php";
	
	
 
 
	//ad-type field labels
	$AD_DATA = array(
		1 => array(
			"label" => $GOOGLE_SEARCH_LABEL,
			"table" => $GOOGLE_SEARCH_TABLE_NAME,
			"form"  => $GOOGLE_SEARCH_FORM
		),
		2 => array(
			"label" => $GOOGLE_DISPLAY_LABEL,
			"table" => $GOOGLE_DISPLAY_TABLE_NAME,
			"form"  => $GOOGLE_DISPLAY_FORM
		),
		7 => array(
			"label" => $GOOGLE_REMARKET_LABEL,
			"table" => $GOOGLE_REMARKET_TABLE_NAME,
			"form"  => $GOOGLE_REMARKET_FORM
		),
	);

 
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "contact.php";                                 //the destination when the user has finished the step with no errors
	
	//vendor not chosen redirect page
	$NO_VENDOR_PAGE             = "who.php";
	
	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "6";                                                         //stage in the wizard process (high to low)

	
	
	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/

	$campaign       = getCampaginForCreate(false);
	
	if(!$campaign) die("campaign could not be found");
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	
	//fetch the user's ads
	
	
	
	
	
	
	
	

/**
* Get the ad data for the campaign
*/
	
	$vNum                 = $campaign->get($VENDOR_FIELD_NAME);
	$ads                  = getCampaignAds($cpnNum);
	$display_url          = null;
	$display_preview_img  = null;
	$remarket_preview_img = null;
	
	//$vNum = 1;
	//showme($ads);
	
	if(!$vNum) header("Location: $NO_VENDOR_PAGE?num=$cpnNum");
	
	
	
	

/**
* Submission
*/

	if(@$_REQUEST[$CONTINUE_BUTTON_NAME]){
		
		$insert     = array();
		$vendor_ads = $ads[$AD_DATA[$vNum]["label"]];
		$ads_found  = count($vendor_ads);
		
		if(!$ads_found) $errors = true;             		                           //error if no location was given
		if($vNum == 1 && !@$_REQUEST['summ']) $NEXT_STEP_PAGE = "additional.php";
		
		//temporary flow enabler
		if(!$errors) header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
	}
	
	
	

	
	
	
/**
* Determine which ad creation interface to display
*/

	$ad_interface = $AD_DATA[$vNum]["form"];

	if($vNum == 1) $additional_settings = true;



	
	
/**
* Get The other campaign information 
* will be used in the breadcrumbs
*/
	
	$cpnTitle       = @$_REQUEST["title"]  ?: $campaign->get("title");
	$cpnLeads       = $campaign->get("leads");
	$cpnBudget      = $campaign->get("budget");
	
	$industry         = $campaign->get("industry");
	$industryTitle    = $campaign->get("industry:label");
	
	$locationTitles   = getLocationTitles(getCampaignLocations($cpnNum)["locations"]);
	
	$vendor           = $campaign->get("vendor");
	$vendorTitle      = $campaign->get("vendor:label");
	
	$start            = getCampaignDaysTimes($cpnNum, false)->get("start_date");
	
	
	//determining which ads we use
	if(!$vendor) $vendor_ads = null;
	else $vendor_ads = $ads[$AD_DATA[$vNum]["label"]];
	
	
	
	$additional = ($vendor == 1)? getCampaignKeywords($cpnNum) : null;
	
	$contact    = campaign_validateContactInfo($campaign);


?>