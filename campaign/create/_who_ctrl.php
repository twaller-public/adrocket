<?php


/** PAGE INFO

	The Adrocket Wizard Location select control file.

	The first screen shown depends on the state of the campaign location records.

		If the campaign has no locations already set, the user is shown an input
		box, and two buttons. The user can enter locations into the box and press
		"Submit", or just click "See Map" to be shown the location selectin interface.
		
		If the campaign has locations already set, the user is shown the location
		selection interfact as if they had clicked "See Map" on the first page.
		
	A user can submit Locations as follows:
	
		On the first screen, they enter comma-seperated-values into the input box
		and click submit.
		
		On the location selection interface, they enter comma-seperated-values 
		into the input box and click search.
		
		On the displayed map, the user clicks the location they desire.
		
	Location selection submissions are processed by ajax, and the page is updated
	when calls to change the location list are completed.
	
	On submission, the capaign locations are checked to see if at least one is present
	if there are no locations, the user is given an error, otherwise, they continue to
	the next step.


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
		b. Reload the campaign
		
	9. Get the campaign-vendor information
	
	10. Get all the vendor options that the user can choose from	
	
	11. -nostep-
	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check that a vendor selection has been submitted
	
		a. If no selection was made. We set an error and the user is not re-directed.
		
		b. If a selection was made, save the selection, proceed to the next step


	3. Determine the user destination
	
		a. If there are errors stay on this page, reload the campaign
		
		b. If there are no errors, direct to the stage in the process
		
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
* Page Variables
*/


	//errors - is true or false because there is only one possible error on this page
	$errors     = false;
	$error_text = "Please Select A Vendor.";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted
	$VENDOR_INPUT               = "vendor-num";                                //name of the vendor input elt
	
	
	//record names
	$VENDOR_TABLE_NAME          = "vendors";                                   //title of the vendors table/records

	
	//record field names
	$VENDOR_FIELD_NAME          = "vendor";                                    //name of the vendor field in campaign record
	$VENDOR_LABEL_NAME          = "vendor:label";                              //name of the vendor text name in campaign record
	$VENDOR_LOGO_FIELD          = "logo";                                      //field name for the vendor logo
	$VENDOR_LOGO_URL            = "thumbUrlPath";                              //field name for the logo image URL
	
 
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "when.php";                                  //the destination when the user has finished the step with no errors
	
	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "8";

	
	
	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/

	$campaign       = getCampaginForCreate(false);
	
	if(!$campaign) die("campaign could not be found");
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	
	

	
	

/**
* Submission
*/

	if(@$_REQUEST[$CONTINUE_BUTTON_NAME]){
		
		//showme($_REQUEST);
		
		$insert = array();
		$vendor = @$_REQUEST[$VENDOR_INPUT];                                        //grab the vendor value
		
		if(!$vendor) $errors = true;             		                       //error if no vendor was given
		else{                                                                  //user selected a vendor
			
			//set up the insert array for the user selection
			$insert[$VENDOR_FIELD_NAME] = $vendor;
			$campaign->set($insert);
		}
		
		if(!$errors) header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
		else $campaign = getCampaginForCreate();                               //re-load the campaign
	}
	
	
	
	
	
	
	
	
/**
* Get the vendor for the campaign, if set
*/


	$vendor           = $campaign->get($VENDOR_FIELD_NAME);
	$vendorTitle      = $campaign->get($VENDOR_LABEL_NAME);
	
	
	
	
	
	
/**
* Get the google vendor ad type options
*/


	$search   = new MultiRecordType($VENDOR_TABLE_NAME, array("where" => "num = 1"), true);
	$display  = new MultiRecordType($VENDOR_TABLE_NAME, array("where" => "num = 2"), true);
	$remarket = new MultiRecordType($VENDOR_TABLE_NAME, array("where" => "num = 7"), true);
	
	$search   = $search->records()[0];
	$display  = $display->records()[0];
	$remarket = $remarket->records()[0];
	
	$search_logo   = $search->get($VENDOR_LOGO_FIELD)[0][$VENDOR_LOGO_URL];
	$display_logo  = $display->get($VENDOR_LOGO_FIELD)[0][$VENDOR_LOGO_URL];
	$remarket_logo = $remarket->get($VENDOR_LOGO_FIELD)[0][$VENDOR_LOGO_URL];
	
	
	
	
	

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