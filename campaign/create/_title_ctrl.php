<?php

/** PAGE INFO

	The Adrocket Wizard Title and Budget select control file.

	The user specifies a title and budget for their campaign

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
		
	
	
	9. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check that a title and budget have been submitted
	
		set an error if either are missing

	
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
	//showme($_SESSION);
	require("_captcha-intercept_ctrl.php");
	
	
	
	
/**
* Page Variables
*/


	//errors - is true or false because there is only one possible error on this page
	$errors     = false;
	$error_text = "";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted

	
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "what.php";                                 //the destination when the user has finished the step with no errors

	
	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "10";                                                         //stage in the wizard process (high to low)

	
	
	
	
	
/**
* Process a request for leads based on input from the quick build tool
*/
	if(@$_REQUEST['get_leads']){
		
		$r = array("result" => 0);
		
		$cpn    = @$_REQUEST["cpnNum"];
		$budget = @$_REQUEST["bud"];

		
		if(!$cpn){
			
			echo json_encode($r);
			exit();
		} 
		
		
		$result = calculateCampaignLeads_num($cpn, $budget, true);
		$r["result"] = $result;
		
		echo json_encode($r);
		exit();
	}
	
	
	
	
	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/

	$campaign       = getCampaginForCreate();
	
	if(!$campaign) die("campaign could not be found");
	
	//showme($campaign);
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	
	$cpnTitle       = @$_REQUEST["title"]  ?: $campaign->get("title");
	$cpnLeads       = $campaign->get("leads");
	$cpnBudget      = $campaign->get("budget");
	

	
	

	
	
	

/**
* Form Submission
*/

	if(@$_REQUEST[$CONTINUE_BUTTON_NAME]){
		
		//showme($_REQUEST);
		
		if(
			!@$_REQUEST["title"] ||
			!@$_REQUEST["budget"]
		) $errors = true;
		
		if(!@$_REQUEST["title"])  $error_text .= "Please Pick A Title For This Campaign.<br />";
		if(!@$_REQUEST["budget"]) $error_text .= "Please Pick A Budget For This Campaign.<br />";
		
		
		if(!$errors){ 
		
			$insert = array(
				"title"   => $_REQUEST["title"],
				"budget"  => $_REQUEST["budget"],
				"leads"   => calculateCampaignLeads_num($cpnNum, $_REQUEST["budget"]),
			);
			
			$insert["vendor"] = 1;   //google adwords
			
			//showme($insert);
		 
			$campaign->set($insert);			
			if(!$errors) header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                  //send the user to the next step if there are no errors
		}                     
		//else $campaign = getCampaginForCreate(false);                               //re-load the campaign
	} 
	
	
	
	
	
	





	
/**
* Get The other campaign information 
* will be used in the breadcrumbs
*/
	
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