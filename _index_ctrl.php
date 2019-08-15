<?php

	require_once "assets/_app_init.php";
	
	$home = mysql_get("home_page", 1);
	
	//echo session_id();
	//exit;
	
	//nuf5kgnu4cg2ore0e762qo6dp1
	
	
/**
* Process a request for the city names in a province
*/
if(@$_REQUEST['cityFetch']){
	
	$province  = @$_REQUEST['province'];
	
	if($province == "0"){
		
		$cityOpts = "<option value='0'>-- City --</option>";
	}
	else{
		
		$locations = getLocationsForProvince($province, $filterFields = array());
		$cityOpts  = getHTMLOptionsFromRecordData($locations);
	}
	
	
	$result    = array("html" => $cityOpts);
	
	echo json_encode($result);
	exit();
}
	
	
	
	
/**
* Process a request for leads based on input from the quick build tool
*/
	if(@$_REQUEST['get_leads']){
		
		$r = array("result" => 0);
		
		$province  = @$_REQUEST["province"];
		$industry  = @$_REQUEST["industry"];
		$budget    = @$_REQUEST["budget"];
		$locations = @$_REQUEST["locations"];
		
		//showme($_REQUEST);
		
		if(!$province || !$industry || !$budget){
			
			echo json_encode($r);
			exit();
		}
		
		if($locations && $locations[0]){
			
			$locations[0] = new Record("campaign_locations", array("location" => $locations[0]));
		}
		else $locations = null;
		
		
		$result = calculateCampaignLeads_data($province, $industry, $budget, $locations);
		$r["result"] = $result;
		
		echo json_encode($r);
		exit();
	}
	
	
//$_SESSION["CAPTCHA_VERIFIED"] =  0;
	
if(@$_REQUEST['qb-submit']){
	
	
	if(!$CURRENT_USER){
		$_SESSION["CAPTCHA_VERIFIED"] = (@$_SESSION["CAPTCHA_VERIFIED"] == 1)? 1 : 0;


		if(!$_SESSION["CAPTCHA_VERIFIED"]){
				
			$valid = captcha_validateSubmission();
			if($valid){
				$_SESSION["CAPTCHA_VERIFIED"] = 1;
			}
			
		
		}
		if(!$_SESSION["CAPTCHA_VERIFIED"]) header("Location: /home");
	}
	
	if($_SESSION["CAPTCHA_VERIFIED"] || $CURRENT_USER){
	
		//showme($_REQUEST);
		$campaign = getCampaginForCreate(false);
		$result   = null;
		
		//showme($campaign);
		
		if($campaign) $result = doCampaignUpdate($campaign, false);
		
		//showme($result);
		
		if($result) header("Location: /campaign/create/title.php");
	}
}
	
	
//load large home page images
	$im1     = file_get_contents('img/home-image-1a.png');
    $im1data = base64_encode($im1);
	
	$im2     = file_get_contents('img/stressed_out.jpg');
    $im2data = base64_encode($im2);
	
	//$im3     = file_get_contents('img/office.jpg');
	$im3     = file_get_contents('img/adrocket-image-twokids-helmets-shopped-2500x450.png');
    $im3data = base64_encode($im3);
	
	//echo "TEST: $im1data";
	
	
	
	
	
/**
* Fetch all campaign Industry Options
*/

	$opts            = array("orderBy" => "title ASC");
	$industries      = new MultiRecordType("industries", $opts, true);
	$industryRecords = $industries->records();
	
	//build the option html from the industry options and the current industry selection
	$indOptionHTML = getHTMLOptionsFromRecordData($industryRecords, null);
	
	

/**
* Fetch all campaign Province Options
*/
	$provinces      = new MultiRecordType("provinces", $opts, true);
	$provincesRecords = $provinces->records();
	
	//build the option html from the industry options and the current industry selection
	$proOptionHTML = getHTMLOptionsFromRecordData($provincesRecords, null);

	
	
	
	
	
	
/**
Get the google vendor ad type options
*/


	$search   = new MultiRecordType("vendors", array("where" => "num = 1"), true);
	$display  = new MultiRecordType("vendors", array("where" => "num = 2"), true);
	$remarket = new MultiRecordType("vendors", array("where" => "num = 7"), true);
	
	$search   = $search->records()[0];
	$display  = $display->records()[0];
	$remarket = $remarket->records()[0];
	
	$search_logo   = $search->get("logo")[0]["thumbUrlPath"];
	$display_logo  = $display->get("logo")[0]["thumbUrlPath"];
	$remarket_logo = $remarket->get("logo")[0]["thumbUrlPath"];
	
	
	//showme($search_logo);
  
  
  
  
//Featured Industries
  
  // load records from 'featured_industry'
  list($featuredIndustryRecords, $featured_industryMetaData) = getRecords(array(
    'tableName'   => 'featured_industry',
    'loadUploads' => true,
    'allowSearch' => false,
    'limit'       => '6',
    'orderBy'     => "RAND()"
  ));
  
  
  
  
  
?>