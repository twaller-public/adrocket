<?php

/*
Plugin Name: ZLibrary Functions for AdRocket
Description: Library Functions for AdRocket.
Version: 1.00
CMS Version Required: 3.00
*/




$GLOBALS["ADROCKET"] = TRUE;
include "definitions.php";

$GLOBALS["DEFAULT_KW_COUNT"] = 15;


adrocket_libInclude();
adrocket_adwordslibInclude();

//require_once $_SERVER["DOCUMENT_ROOT"] . '/cmsb/plugins/adrocket/Google/adwords-examples-32.0.0/vendor/autoload.php';




/**
* Adrocket Specific Cron Jobs
*/



//clear any orphaned campaigns that are not assciated with a user or an active session
//run every day at 1:00 am
addCronJob('campaign_clearOrphanedCpn', "Ophaned Campaign Clear", '0 1 * * *'); 
pluginAction_addHandlerAndLink(t('Ophaned Campaign Clear - Manual'), 'campaign_clearOrphanedCpn', 'admins');


//send daily status email to all admins
//run every day at 6:00 am
//addCronJob('email_dailyAdminTasks', "Daily Admin Tasks Email", '0 6 * * *'); 
pluginAction_addHandlerAndLink(t('Daily Admin Tasks Email - Manual'), 'email_dailyAdminTasks', 'admins');


//send daily status email to each manager
//run every day at 6:00 am
//addCronJob('email_dailyManagerTasks', "Daily Manager Tasks Email", '0 6 * * *'); 
pluginAction_addHandlerAndLink(t('Daily Manager Tasks Email - Manual'), 'email_dailyManagerTasks', 'admins');



/**
* adrocket_libInclude includes the library files provided with the plugin
*/
function adrocket_libInclude(){
	
	//include the lib files
	$dir = SCRIPT_DIR . "/plugins/adrocket/lib/";
	//echo is_dir($dir)? "Yes" : "No";
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				//echo "filename:" . $file . "<br>";
				@include_once($dir.$file);
			}
			closedir($dh);
		}
	}
}



/**
* adrocket_libInclude includes the library files provided with the plugin
*/
function adrocket_adwordslibInclude(){
	
	//include the lib files
	$dir = SCRIPT_DIR . "/plugins/adrocket/adwordslib/";
	//echo is_dir($dir)? "Yes" : "No";
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				//echo "filename:" . $file . "<br>";
				@include_once($dir.$file);
			}
			closedir($dh);
		}
	}
}






/**
* campaign_clearOrphanedCpn erases any campaign records that
* have been created by non-logged-in users whose sessions have
* expired.
*
* Cron Function.
* 
*/
function campaign_clearOrphanedCpn(){
	
	
	
	
	
	//$records = mysql_select("campaign_component", mysql_escapef("user = ? AND title = ? AND DATEDIFF(NOW(), createdDate) > 1", "", ""));
	//$records = mysql_select("campaign_component", "user = '' AND DATEDIFF(NOW(), createdDate) > 1");
	
	//echo mysql_count("campaign_component", "user IS NULL AND DATEDIFF(NOW(), createdDate) > 1");

	
	//showme($records);
	
	$deleteCount = mysql_count("campaign_component", "user IS NULL AND title IS NULL AND DATEDIFF(NOW(), createdDate) > 1");
	mysql_delete("campaign_component", null, "user IS NULL AND title IS NULL AND DATEDIFF(NOW(), createdDate) > 1");
	
	
	//$records = mysql_select("campaign_component", "user = '' AND title = '' AND DATEDIFF(NOW(), createdDate) > 1");
	//showme($records);
	
	return $deleteCount;
	
}

?>