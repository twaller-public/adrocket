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
	
	2b. Include the ajax script handlers
	
	3. Initiate the errors variable
	
	4. Set page variables (field names, next step, etc)
	
	5. Set the mini-nav bar to show
	
	6. Set the breadcrumb stage value
	
	7. Load the campaign, or create a new one if no number is found
	
	8. Handle Form Submission
		a. See submission section
		
	9. Get the campaign-location information
	
	10. Get all the location options that the user can choose from
		NOTE: Currently this is empty but may contain regional preset or similar in the future
		
	
	11. Determine if we are showing the input fields, or the interactive map on load
		a. If location selection(s) have been made, show the map.
		b. If no location selections, show the default input box
	
	
	12. Load all data for breadcrumb (cpn settings)



	
Submission:
	
	1. User has hit the continue button, so we process the submission
	
	
	2. Check that any locations have been submitted (locations are added via ajax,
	   so we check the current cpn location records to see if any match our CPN
	
		a. If none, no selections were made. We set an error and the user is not re-directed.
		
		b. If yes, The user has made location submissions, we can proceed to the next step


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
* Page Variables
*/


	//errors - is true or false because there is only one possible error on this page
	$errors     = false;
	$error_text = "Please Choose at least one Location.<br />Or type your location(s) in to the text box, and our team will find it.";
	
	

	//request field names
	$CONTINUE_BUTTON_NAME       = "Continue";                                  //the request value set if the form is submitted
	
	
	//record names
	$LOCATIONS_TABLE_NAME        = "locations";                                //title of the locations table/records
	$LOCATIONS_LABEL_NAME        = "location:label";
	$LOCATION_COORD_TABLE        = "map_coordinates";                          //the table where we store the map coods

	
	//record field names
	$LOCATION_COORD_JSON        = "json_string";
	
 
 
	//next page
	$NEXT_STEP_PAGE             = @$_REQUEST["summ"]? "/campaign/create.php" : "when.php";                                 //the destination when the user has finished the step with no errors

	
	//show the mini nav bar (in header include)
	$miniNav = true;
	
	
	//breadcrumb
	$countdown = "9";                                                         //stage in the wizard process (high to low)





	
	
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
* Get the locations for the campaign, if set 
* we grab the coords of each location to display on the map
*/

	
	$locations     = getCampaignLocations($cpnNum)[$LOCATIONS_TABLE_NAME];
	$coordinates   = array();
	$location_text = "";
	
	if($locations){
		
		//we go through each campaign location, loading the map coords for that location
		//into the array so we can display the locations on the map
		foreach($locations as $loc){
		
			$locName = $loc->get($LOCATIONS_LABEL_NAME);
			
			$opts = array("where" => mysql_escapef("title = ?", $locName));
			$coords = new MultiRecordType($LOCATION_COORD_TABLE, $opts, true);
			
			//if we find the location in the coords, we add those coords to the array
			if(!$coords->meta()["noRecordsFound"]){
				
				$coords = $coords->records()[0];
				$coordinates[$locName] = json_decode($coords->get($LOCATION_COORD_JSON));
			}
		}
	}
	
	
	/* CURRENTLY UNUSED - province is not currently a selection on this screen
	
	$province      = $campaign->get("province"];   //currently selected province
	$province_name = $cpnVals["province:label"];
	$preset        = @$cpnVals["preset"];
	*/
	
	
	
	
	

/**
* Submission
*/

	if(@$_REQUEST[$CONTINUE_BUTTON_NAME]){
		
		//showme($_REQUEST);
		
		$insert = array();
		$loc    = count($locations);
		//OLD LINE - $loc = @$_REQUEST['locations'];                            //grab the location values
		
		if(!$loc) $errors = true;             		                           //error if no location was given
		
		//temporary flow enabler
		if(!$errors) header("Location: $NEXT_STEP_PAGE?num=$cpnNum");                      //send the user to the next step if there are no errors
		
	}
	
	
	
	
	
	
	
/**
* Get The other campaign information 
* will be used in the breadcrumbs
*/
	
	$cpnTitle       = @$_REQUEST["title"]  ?: $campaign->get("title");
	$cpnLeads       = $campaign->get("leads");
	$cpnBudget      = $campaign->get("budget");
	
	$locationTitles   = getLocationTitles($locations);
	
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