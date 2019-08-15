<?php
/**
The user campaign listing program control file
*/


require_once "../assets/_app_init.php";




/*
* Permissions
*/

//deterine access permissions
blockAgentType("GUEST", "/user/signin.php");
blockAgentType("MANAGER", "/manager/dashboard.php");
blockAgentType("ADMIN", "/admin/dashboard.php");
blockAgentType("CEO", "/ceo/dashboard.php");





/*
* Vars
*/




$alerts = "";



//meta values
$active_cpns  = 0;
$waiting_cpns = 0;
$uconstr_cpns = 0;
$expired_cpns = 0;
$total_cpns   = 0;




/*
* Other Submissions
*/

//user clicked delete campaign button
if(@$_REQUEST['delete']){
	
	if(campaign_Delete(intVal(@$_REQUEST['num']))) $alerts .= "Campaign Deleted.<br />";
	else                                           $alerts .= "Could not delete Campaign.<br />";
}








/*
* Filter info 
*/


//get the vendor options for the filter
$selected_vendor = @$_REQUEST["filter-vendor"]?: 0;

$opts = array(
	"where" => "is_active = 1",
	"orderBy" => "title ASC"
);
$vendor_opts = getHTMLOptionsFromTableName("vendors", $opts, $selected_vendor);


//get the status options for the filter
$selected_status = @$_REQUEST["filter-campaign_status"]?: 0;

$status_array = getListOptions("campaign_component", "campaign_status");
//showme($status_array);
$status_opts  = getHTMLOptionsFromArray($status_array, $selected_status, null, null, true);









/*
* User campaigns
*/


//get all campaigns for the user

$where = "user = {$CURRENT_USER['num']}";   //default where for USER dashboard

if(@$_REQUEST["reset-filter"]){ $_REQUEST = array(); }
if(@$_REQUEST["cpn-filter"]){   $where   .= filter_buildFilterWhere($_REQUEST); }

//showme($_REQUEST);
//echo $where;

$opts = array(
	"where" => $where,
	/*"perPage" => 5,*/
	"orderBy" => "num DESC"
);

$campaigns = new MultiRecordType("campaign_component", $opts, true);
$meta      = $campaigns->meta();

//showme($meta);









/*
* Process campaigns
*/


//load the additional campaign information
if($meta["noRecordsFound"]){
	
	if(@$_REQUEST["cpn-filter"]) $alerts .= "No Campaigns found.";
	else                         $alerts .= "You have no campaigns, to create a campaign click <a href='/campaign/create/title.php'>here</a><br /><br />";
	$campaigns = array();
} 
else{
	
	$campaigns = $campaigns->records();
	//showme($campaigns);
	
	$total_cpns = count($campaigns);
	
	foreach($campaigns as $index=>$c){
		
		switch($c->get("campaign_status")){
			
			case 1:
				$uconstr_cpns++;
				break;
			case 3:
				$waiting_cpns++;
				break;
			case 4:
				$active_cpns++;
				break;
			case 5:
				$expired_cpns++;
				break;
			default:
				break;
		}
		
	}
	
	
	//load campaigns - paging friendly
	$opts = array(
		"where" => $where,
		"perPage" => 10,
		"orderBy" => "num DESC"
	);

	$campaigns = new MultiRecordType("campaign_component", $opts, true);
	$meta      = $campaigns->meta();
	
	
	$campaigns = $campaigns->records();
	
	
	foreach($campaigns as $index=>$c){
		
		$cpnNum     = $c->get("num");
		$vendor_num = $c->get("vendor");
		$daystimes  = getCampaignDaysTimes($cpnNum);
		
		$campaigns[$index]                = array($c, $daystimes);
		$campaigns[$index]["vendor_logo"] = vendor_getLogo($vendor_num);
		
	}
	
	//showme($meta);
}









?>