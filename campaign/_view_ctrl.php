<?php
/**
The campaign view program control file
*/


require_once "../assets/_app_init.php";

//deterine access permissions
blockAgentType("GUEST", "/user/signin.php");


$showCpn = false;
$alerts = "";

if(!@$_REQUEST['num'])                   $alerts .= "No Campaign Number Found! <a href='/user/dashboard.php'>Return To Dashboard</a><br />";
else if(!userOwnsCpn(@$_REQUEST['num'])) $alerts .= "Cannot Access this Campaign. <a href='/user/dashboard.php'>Return To Dashboard</a><br />";

else{
	
	if(@$_REQUEST["admin-edit-cpn"]){
		
		blockAgentType("USER", "/user/dashboard.php");
		blockAgentType("MANAGER", "/user/dashboard.php");
		
		showme($_REQUEST);
		$campaign = getCampaginForCreate();
		
		$insert = array(
			"user"            => @$_REQUEST["cpnUser"],
			"campaign_status" => @$_REQUEST["cpnStatus"],
			"admin_status"    => @$_REQUEST["admStatus"],
		);
		
		$campaign->set($insert);
		
		unset($campaign);
	}
	
	
	
	
	$showCpn        = true;
	$fresh_campaign = false;
	
	
	/**
	* load the campaign by either creating a new one, or matching the session id
	* or $_POST['num'] to an existing campaign
	*/
	$campaign = getCampaginForCreate();
	$alerts .= "Campaign is " . $campaign->get("campaign_status:label") . ".<br />";
	
	//showme($campaign);
	
	/**
	* Set some general campaign information variables
	*/
	$cpnNum         = $campaign->get("num");
	$userNum        = $campaign->get("user");
	$sessionID      = $campaign->get("session_id");
	$title          = $campaign->get("title");
	$vendor         = $campaign->get("vendor");
	$vendorTitle    = $campaign->get("vendor:label");
	$province       = $campaign->get("province");
	$provinceTitle  = $campaign->get("province:label");
	$industry       = $campaign->get("industry");
	$industryTitle  = $campaign->get("industry:label");
	$budget         = $campaign->get("budget");
	$leads          = $campaign->get("leads");

	$c_name    = @$campaign->get("contact_name")  ?: /*$CURRENT_USER["fullname"] ?: */"";
	$comp_name = @$campaign->get("company_name")  ?: /*$CURRENT_USER["company"]  ?: */"";
	$c_phone   = @$campaign->get("contact_phone") ?: /*$CURRENT_USER["phone"]    ?: */"";
	$c_email   = @$campaign->get("contact_email") ?: /*$CURRENT_USER["email"]    ?: */"";
	$comments  = @$campaign->get("comments");

	$user_completed = $campaign->get("user_completed");
	
	
	$cpn_status = $campaign->get("campaign_status");
	$adm_status = $campaign->get("admin_status");
	
	
	//loading the related records for campaigns
	$locations  = getCampaignLocations($cpnNum);
	$keywords   = getCampaignKeywords($cpnNum);
	$daystimes  = getCampaignDaysTimes($cpnNum);
	$dayparting = getCampaignDayParting($cpnNum);




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
	
	
	
	if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO"){
		//load the admin options for the campaign
		
		
		//users
		$opts  = array("where" => "account_type = 'User'", "orderBy" => "num ASC");
		$users = new MultiRecordType("accounts", $opts, true);
		
		if(!$users->meta()["noRecordsFound"]){ $users = $users->records(); }

		
		//campaign_status list options
		$cpnStatus = getListOptions("campaign_component", "campaign_status");
		$admStatus = getListOptions("campaign_component", "admin_status");
		
		//showme($cpnStatus);
		//showme($admStatus);
		
	}
}

?>