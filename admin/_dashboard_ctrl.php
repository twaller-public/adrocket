<?php
/**
The user dashboard program control file
*/





require_once "../assets/_app_init.php";

//deterine access permissions
blockAgentType("GUEST", "/user/signin.php");
blockAgentType("USER", "/user/dashboard.php");
blockAgentType("MANAGER", "/manager/dashboard.php");
blockAgentType("CEO", "/ceo/dashboard.php");


$alerts = "";

//assign a campaign to a manager
if(@$_REQUEST['assign']){ $alerts .= admin_assignCampaignToManager(); }



//get all campaigns

$where = "TRUE";   //default where for ADMIN dashboard

if(@$_REQUEST["reset-filter"] || !@$_REQUEST["cpn-filter"]){ $_REQUEST = array("cpn-filter" => 1, "omit-user-progress" => 1); }

if(@$_REQUEST["cpn-filter"]){   $where   .= filter_buildFilterWhere($_REQUEST); }

//showme($_REQUEST);
//echo $where;

$opts = array(
	"where" => $where,
	"orderBy" => "admin_status DESC",
	"perPage" => 10,
);

$campaigns = new MultiRecordType("campaign_component", $opts, true);
$meta      = $campaigns->meta();

//showme($meta);




//load the additional campaign information
if($meta["noRecordsFound"]){
	
	$alerts .= "No Campaigns found.";
	$campaigns = array();
} 
else{
	
	$campaigns = $campaigns->records();
	
	foreach($campaigns as $index=>$c){
		
		$cpnNum     = $c->get("num");
		$vendor_num = $c->get("vendor");
		$userNum    = $c->get("user");
		
		$userName = getNameFromID($userNum);
		
		$daystimes  = getCampaignDaysTimes($cpnNum);
		
		$campaigns[$index]                = array($c, $daystimes);
		$campaigns[$index]["vendor_logo"] = vendor_getLogo($vendor_num);
		$campaigns[$index]["user_name"]   = $userName;
		//$campaigns[$index]->vals = array_merge($c->vals(), $daystimes->vals());
	}
}



//get the vendor options for the filter
$selected_vendor = @$_REQUEST["filter-vendor"]?: 0;

$opts = array(
	"where" => "is_active = 1",
	"orderBy" => "title ASC"
);
$vendor_opts = getHTMLOptionsFromTableName("vendors", $opts, $selected_vendor);



//get the status options for the filter
$selected_status = @$_REQUEST["filter-campaign_status"]?: 0;

$status_array = getListOptions("campaign_component", "admin_status");
//showme($status_array);
$status_opts  = getHTMLOptionsFromArray($status_array, $selected_status, null, null, true);


//get available manager information

$opts = array("where" => mysql_escapef("account_type = ? AND available_manager = 1", "Manager"));
$managers = new MultiRecordType("accounts", $opts, true);


$available_managers = admin_getAvailableManagers();

?>