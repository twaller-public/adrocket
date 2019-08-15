<?php

/** PAGE INFO

	The Adrocket Wizard Industry select control file.

	The user selected an industry from a drop down list. 
	If they select Other/Not Listed, they are given another text box. 
	The text box allows the user to submit their own industry 
	for our account managers to use when creating a campaign.


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
		
	9. Get the campaign-industry information (ind num,. Title, user-submitted)
	
	10. Get all the industry options that the user can choose from
		a. Build the results into an html options list
		
	
	11. Determine if we are showing a user-submitted industry when the page loads (we do not wait for a user to select other/not listed)
	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check the value of the industry submitted
	
		a. If 0, no selections were made. We set an error and the user is not re-directed.
		
		b. If "Other", the user has not found their campaign on the list. 
		   Check for text being submitted by the user which indicates the industry they want. 
		   Store the industry selection and the text in the record.
		   
		c. Else the user has selected an industry on the list. 
		   We receive a number as submission. 
		   Store the industry in the record.

		   
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
	$error_text = "Please Choose an Industry.<br />Select 'Other' if you can't find your industry.";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted
	$INDUSTRY_SELECT_INPUT      = "industry";                                  //name of the industry selection elt
	$USER_SUBMITTED_INDUSTRY    = "other-detail";                              //name of the user submitted industry field
	
	
	//record names
	$INDUSTRY_TABLE_NAME        = "industries";                                //title of the industries table/records

	
	//record field names
	$INDUSTRY_FIELD_NAME        = "industry";                                  //name of the industry field in campaign record
	$INDUSTRY_LABEL_NAME        = "industry:label";                            //name of the industry text name in campaign record
	$USER_SUBMITTED_FIELD_NAME  = "user_submitted_industry";                   //name of the user submitted industry field in campaign record
	
	
	//important submission values
	$NO_SELECTION_VALUE         = "0";                                         //the user did not make any selection - we show an error
	$NOT_LISTED_VALUE           = "Other";                                     //Value of the "Not Listed" option in the $INDUSTRY_SELECT_INPUT elt
	$EMPTY_USER_SUBMITTED_VALUE = "[None Specified]";                          //The value to use when the user has select "Not Listed", but also didn't submit their own industry
 
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "where.php";                                 //the destination when the user has finished the step with no errors

	
	//other
	$showUserSubmission         = FALSE;                                       //do we show the user submitted industry (yes if has value) - we determine this later     
	
	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "10";                                                         //stage in the wizard process (high to low)

	
	
	
	
	
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
* Get the industry for the campaign, if set
*/

	$industry                = $campaign->get($INDUSTRY_FIELD_NAME);
	$industryTitle           = $campaign->get($INDUSTRY_LABEL_NAME);
	$user_submitted_industry = $campaign->get($USER_SUBMITTED_FIELD_NAME);
	
	
	

/**
* Form Submission
*/

	if(@$_REQUEST[$CONTINUE_BUTTON_NAME]){
		
		//showme($_REQUEST);
		
		$insert = array();
		$ind    = @$_REQUEST[$INDUSTRY_SELECT_INPUT];                          //grab the industy value
		
		if(!$ind || $ind == $NO_SELECTION_VALUE){ 
		
			$errors = true;                                                    //error if no industry was given
			
		}            		 
		else if($ind == $NOT_LISTED_VALUE){                                    //user selected 'other/not listed'
			
			
			//determine the other text to put in the record
			$other_text = @$_REQUEST[$USER_SUBMITTED_INDUSTRY]?: $EMPTY_USER_SUBMITTED_VALUE;
			
			//set up the insert array for the user selection
			$insert[$USER_SUBMITTED_FIELD_NAME] = $other_text;
			$insert[$INDUSTRY_FIELD_NAME]       = $NOT_LISTED_VALUE;           //no 'official' industry chosen, enter a placeholder
			$campaign->set($insert);
		}
		else{                                                                  //user selected an industry
			
			//set up the insert array for the user selection
			$insert[$INDUSTRY_FIELD_NAME]       = $ind;
			$insert[$USER_SUBMITTED_FIELD_NAME] = "";                          //ensure previous submission is cleared   
			$campaign->set($insert);
			
			//change of industry
			if($ind != $industry) resetCampaignKeywords($cpnNum, $ind);
		}
		
		if(!$errors) header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
		else $campaign = getCampaginForCreate();                               //re-load the campaign
		
		$industry                = $campaign->get($INDUSTRY_FIELD_NAME);
		$industryTitle           = $campaign->get($INDUSTRY_LABEL_NAME);
		$user_submitted_industry = $campaign->get($USER_SUBMITTED_FIELD_NAME);
	} 
	
	
	
	


	
	
	
	
	

/**
* Fetch all campaign Industry Options
* Set the selected industry as the default choice, if it is set
*/

	$industries      = new MultiRecordType($INDUSTRY_TABLE_NAME, null, true);
	$industryRecords = $industries->records();
	
	//build the option html from the industry options and the current industry selection
	$optionHTML      = getHTMLOptionsFromRecordData($industryRecords, $industry);
	
	
	
	
	
	
/**
* show the user submission?
*/

	$showUserSubmission = ($user_submitted_industry != "")? 1: 0;
	
	





	
/**
* Get The other campaign information 
* will be used in the breadcrumbs
*/
	
	$cpnTitle       = @$_REQUEST["title"]  ?: $campaign->get("title");
	$cpnLeads       = $campaign->get("leads");
	$cpnBudget      = $campaign->get("budget");
	
	$locationTitles   = getLocationTitles(getCampaignLocations($cpnNum)["locations"]);
	
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