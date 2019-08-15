<?php

/** PAGE INFO

	The Adrocket Wizard Contact Information control file.

	The user fills outs their contact information for the campign
	record. All fields are required except for the 'additional comments' 
	field.


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
		
	9. Get the campaign contact information (ind num,. Title, user-submitted)
	
	
	10. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check if all required values are submitted
	
		a. If any required value is missing, we set an error for each and reload the page
		
		b. otherwise we save the information to the record and proceed to the next page
		
		   
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
	$errors     = false;
	$error_text = "Please fill out all required fields.";


	//record names
	
	
	//field names
	$CONTACT_NAME         = "contact_name";
	$CONTACT_EMAIL        = "contact_email";
	$CONTACT_PHONE        = "contact_phone";
	$CONTACT_COMPANY_NAME = "company_name";
	$CONTACT_COMMENTS     = "comments";
	
	
	//next page
	$NEXT_STEP_PAGE       = @$_REQUEST["summ"]? "/campaign/create.php" : "where.php";
	
	
	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "1"; 

	
	//province options
	$campaign_options = getCampaignOptions();
	$records          = $campaign_options["Provinces"]->records();
	$provinces        = getHTMLOptionsFromRecordData($records, $CURRENT_USER["province"]);


	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/
	$campaign = getCampaginForCreate(false);
	if(!$campaign) die("campaign could not be found");
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	//showme($campaign);
	
	
	
	

	
	

/**
* Submission
*/

	if(@$_REQUEST["Continue"]){
		
		//showme($_REQUEST);
		
		
		if(
			!@$_REQUEST["contact_name"] ||
			!@$_REQUEST["company_name"] ||
			!@$_REQUEST["contact_email"] || 
			!@$_REQUEST["contact_phone"] ||
			!@$_REQUEST["province"]
		) $errors = true;
		
		$invalidEmail = !isValidEmail(@$_REQUEST["contact_email"]);
		if($invalidEmail){
			
			if(!$errors) $error_text  = "Email Address is invalid.";
			else         $error_text .= "<br />Email Address is invalid.";
			
			$errors = true;
		} 
		
		if(!$errors){
			
			$insert = array(
				"contact_name"  => $_REQUEST["contact_name"],
				"company_name"  => $_REQUEST["company_name"],
				"contact_email" => $_REQUEST["contact_email"],
				"contact_phone" => $_REQUEST["contact_phone"],
				"comments"      => @$_REQUEST["comments"],
				"contact_province" => $_REQUEST["province"]
			);
			
			
			//update the contact information record here
			$campaign->set($insert);
			
			
			
			header("Location: /campaign/create.php?num=$cpnNum");
		}
		
		
		
		
		
		
		
		
	}
	
	
	
	
	
	
	
	
/**
* Get the User Information, if logged in
*/

	$name    = @$_REQUEST["contact_name"]  ?: $campaign->get("contact_name");
	$email   = @$_REQUEST["contact_email"] ?: $campaign->get("contact_email");
	$phone   = @$_REQUEST["contact_phone"] ?: $campaign->get("contact_phone");
	$company = @$_REQUEST["company_name"]  ?: $campaign->get("company_name");
	$comment = @$_REQUEST["comments"]      ?: $campaign->get("comments");


	if($AGENT_TYPE == "USER"){
		
		
		$name    = $name    ?: $CURRENT_USER["fullname"];
		$email   = $email   ?: $CURRENT_USER["email"];
		$phone   = $phone   ?: $CURRENT_USER["phone"];
		$company = $company ?: $CURRENT_USER["company"];
	}
	
	
	
	

	
	
	
	
	
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