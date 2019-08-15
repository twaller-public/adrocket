<?php


/** PAGE INFO

	The Adrocket Wizard Time & Date select control file.
	
	The user makes time and date selections, such as start and end dates,
	Dayparting, and other options.

	
	
	On submission, the selections are checked for start and end date selections (or recurring).
	As long as we can determine when the campaign will start and (if applicable,) end - we can continue.
	
	Note: as of now the user can select day-parting, but make no choices and this will not produce an error.
	The campaign will run as if day-parting was not selected.


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
		
	9. Get the campaign-day/time information
	
	10. Get all the day/time options that the user can choose from
		NOTE: This consists of only the parting presets currently.
		
	
	11. Determine if we are showing the day parting selection based on the campaign settings.

	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check that a start date has been submitted and either:
		a. the user has selected an end date or,
		b. the user has selected that their campaign be recurring.
		
		If this information is not available, we set an error


	3. Determine the user destination
	
		a. If there are errors stay on this page, reload the campaign
		
		b. If there are no errors, save the information provided, direct to the stage in the process
		
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
	$error_text = "Please pick a start date, and either pick an end date, or select 'Recurring'.";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted
	$START_DATE_INPUT           = "start_date";
	$END_DATE_INPUT             = "end_date";
	$DURATION_INPUT             = "duration";
	$RECUR_INPUT                = "recur";
	$EXPIRY_NOTIFY_INPUT        = "campaign_expiry_notification";
	$DAYPARTING_INPUT           = "day_parting";
	$DP_PRESET_INPUT            = "dp_preset_opt";
	$DP_SUBSTR_INPUT            = "dp_";
	
	
	
	//record names
	$TIMEDATE_TABLE_NAME         = "campaign_days_times";                      //title of the time&date table/records
	$DAYPART_TABLE_NAME          = "campaign_day_parting";                     //title of the dayparting table/records


	
	//record field names
	
	//days_times fields
	$START_DATE_FIELD_NAME    = "start_date";
	$END_DATE_FIELD_NAME      = "end_date";
	$DURATION_FIELD_NAME      = "duration";
	$RECURRING_FIELD_NAME     = "recur";
	$DAYPARTING_FIELD_NAME    = "day_parting";
	$EXPIRY_NOTIFY_FIELD_NAME = "expiry_notification";
	
	
	//day_parting fields
	$DP_PRESET_FIELD          = "dp_preset_opt";
 
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "ads.php";                                 //the destination when the user has finished the step with no errors

	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "7";                                                         //stage in the wizard process (high to low)






	
	
/**
* load the campaign by either creating a new one, or matching the session id
* or $_POST['num'] to an existing campaign
*/

	$campaign       = getCampaginForCreate(false);
	
	if(!$campaign) die("campaign could not be found");
	
	$cpnNum         = $campaign->get("num");	
	$userNum        = $campaign->get("user");
	//echo $cpnNum . "<br />";
	
	
	//the day&time and dayparting related records
	$daystimes = getCampaignDaysTimes($cpnNum, false);
	$daypart   = getCampaignDayParting($cpnNum, false);   //false to prevent auto-creating a record
	
	
	//showme($daystimes);



/**
* Submission - complete this for time/date settings
*/

	if(@$_REQUEST["Continue"]){
		
		//showme($_REQUEST);
		//exit;
		
		$dt_insert = array();   //days & times
		$dp_insert = array();   //day parting
		
		$start     = @$_REQUEST[$START_DATE_INPUT];
		$end       = @$_REQUEST[$END_DATE_INPUT];
		$recur     = @$_REQUEST[$RECUR_INPUT]?: 0;
		$duration  = (@$_REQUEST[$DURATION_INPUT] && !$recur)? $_REQUEST[$DURATION_INPUT] : 0;
		
		if(!$start || (!$end && !$recur)) $errors = true;             		 //error if no vendor was given
		else{
			
			//set all the simple inputs to their field values
			$dt_insert["campaign"] = $cpnNum;
			
			$dt_insert[$START_DATE_FIELD_NAME]    = datepickerTimetoSQLTime($start);
			$dt_insert[$END_DATE_FIELD_NAME]      = datepickerTimetoSQLTime($end);
			$dt_insert[$DURATION_FIELD_NAME]      = $duration;
			$dt_insert[$RECURRING_FIELD_NAME]     = $recur;
			
			$dt_insert[$EXPIRY_NOTIFY_FIELD_NAME] = @$_REQUEST[$EXPIRY_NOTIFY_INPUT]?: 0;
			$dt_insert[$DAYPARTING_FIELD_NAME]    = @$_REQUEST[$DAYPARTING_INPUT]?: 0;
			
			//update the day parting details if DP was selected
			if($dt_insert[$DAYPARTING_FIELD_NAME]){
				
				//general info
				$dp_insert["campaign"]       = $cpnNum;
				$dp_insert[$DP_PRESET_FIELD] = @$_REQUEST[$DP_PRESET_INPUT];
				
				//info for each day
				foreach($_REQUEST as $index=>$rq){
					
					if(strpos($index, $DP_SUBSTR_INPUT) === FALSE) continue;
					
					$dp_insert[$index] = $rq;
				}
				
				//showme($dp_insert);
				$daypart->set($dp_insert);
			}
			
			//showme($dt_insert);
			$daystimes->set($dt_insert);
			
		}
		
		if(!$errors) echo header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
		else{
			
			$campaign  = getCampaginForCreate();                               //re-load the campaign
			$cpnNum    = $campaign->get("num");
			
			//the day&time and dayparting related records
			$daystimes = getCampaignDaysTimes($cpnNum, false);
			$daypart   = getCampaignDayParting($cpnNum, false);                //false to prevent auto-creating a record
		} 
		
		
		/* enable the input storage here

		*/
	}
	
	
	
	
	
	
	
	
/**
* Get the time/date settings for the campaign, if set
*/
	//showme($daystimes);

	$start       = @$daystimes->get("start_date");
	$end         = @$daystimes->get("end_date");
	$duration    = @$daystimes->get("duration");
	$recurring   = @$daystimes->get("recur");
	//$weekends    = @$daystimes->get("weekends");
	$notify      = @$daystimes->get("expiry_notification");
	$day_parting = @$daystimes->get("day_parting");
	
	
	$duration_summary = $recurring? $GLOBALS["ADROCKET_DEFINITIONS"]["Recur Duration Label"] : $GLOBALS["ADROCKET_DEFINITIONS"]["Set Duration Label"];
	if($duration) $duration_summary = str_replace("--", $duration, $duration_summary);

	
	//js information
	$js_start = null;
	$js_end   = null;
	
	if($start && $start != "0000-00-00 00:00:00"){
		$js_start = explode("-", explode(" ", $start)[0]);
		$js_start = "{$js_start[2]}/{$js_start[1]}/{$js_start[0]}";
	}
	
	if($end && $end != "0000-00-00 00:00:00"){
		$js_end = explode("-", explode(" ", $end)[0]);
		$js_end = "{$js_end[2]}/{$js_end[1]}/{$js_end[0]}";
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
	
	
	//determining which ads we use
	$vendor_ads              = getCampaignAds($cpnNum);
	
	if(!$vendor) $vendor_ads = null;
	
	else if($vendor == 1){ $vendor_ads = $vendor_ads["adwords"];     }
	else if($vendor == 2){ $vendor_ads = $vendor_ads["display"];     }
	else if($vendor == 7){ $vendor_ads = $vendor_ads["remarketing"]; }
	
	
	
	$additional = ($vendor == 1)? getCampaignKeywords($cpnNum) : null;
	
	$contact    = campaign_validateContactInfo($campaign);

?>