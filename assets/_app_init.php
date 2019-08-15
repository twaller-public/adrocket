<?php
	/**
	* This app_init file is included on every page on the Adrocket
	* site that interfaces with the CMS. That is, anything not a
	* completely static page.
	*
	* This file loads the viewer library from the CMS
	*
	*/
	
	/**
	* ADROCKET
	*/



	
	
	/**
	* Load the viewer library from the CMS
	*/
	$libraryPath = '/cmsb/lib/viewer_functions.php';
	
	include_once $_SERVER["DOCUMENT_ROOT"] . $libraryPath;
	//$dirsToCheck = array('W:/s/shoppingcartservices.com/htdocs/','','../','../../','../../../');
	//foreach ($dirsToCheck as $dir) { if (@include_once("$dir$libraryPath")) { break; }}
	if (!function_exists('getRecords')) { die("Couldn't load viewer library, check filepath in sourcecode."); }
	
	
	session_start();
	
	
	//die("made it here 10");
	
	
	
	/**
	* Set the current agent type
	* The Agent type detemrines which page access permissions the user has
	* 	Types:
	*	* GUEST   - non logged in agent
	*	* USER    - logged in general user
	*	* MANAGER - campaign manager
	*	* ADMIN   - system administrator
	*	* CEO     - Adrocket CEO account
	*/
	$AGENT_TYPE = determineAgentType();
	
	
	
	
//initiate the mininav var
	$miniNav = false;
	
	
	
	function calculateMgmtFee($budget){
		
		
		$fee = 50;
		
		if((float)$budget > 333.33) $fee = ceil((float)$budget * 0.15);
		
		return $fee;
		
	}
	
	
	
	
?>