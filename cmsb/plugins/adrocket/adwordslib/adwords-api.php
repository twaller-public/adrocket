<?php

//namespace Google\AdsApi\Examples\AdWords\v201710;



$GLOBALS["ADWORDS_API_ACTIVE"]      = FALSE;
$GLOBALS["ADWORDS_API_EXAMPLES"]    = FALSE;
$GLOBALS["ADWORDS_API_EXAMPLE_DIR"] = $_SERVER["DOCUMENT_ROOT"] . "/cmsb/plugins/adrocket/Google/adwords-examples-32.0.0/Adwords/v201710/";

$GLOBALS["ADWORDS_DEVELOPER_TOKEN"] = "asYNtTU53jfoaITSVe1iqw";




use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;




use Google\AdsApi\AdWords\v201710\cm\AdvertisingChannelType;
use Google\AdsApi\AdWords\v201710\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201710\cm\BiddingStrategyType;
use Google\AdsApi\AdWords\v201710\cm\Budget;
use Google\AdsApi\AdWords\v201710\cm\BudgetBudgetDeliveryMethod;
use Google\AdsApi\AdWords\v201710\cm\BudgetOperation;
use Google\AdsApi\AdWords\v201710\cm\BudgetService;
use Google\AdsApi\AdWords\v201710\cm\Campaign;
use Google\AdsApi\AdWords\v201710\cm\CampaignCriterion;
use Google\AdsApi\AdWords\v201710\cm\CampaignCriterionOperation;
use Google\AdsApi\AdWords\v201710\cm\CampaignCriterionService;
use Google\AdsApi\AdWords\v201710\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201710\cm\CampaignService;
use Google\AdsApi\AdWords\v201710\cm\CampaignStatus;
use Google\AdsApi\AdWords\v201710\cm\ConstantOperand;
use Google\AdsApi\AdWords\v201710\cm\ConstantOperandConstantType;
use Google\AdsApi\AdWords\v201710\cm\ConstantOperandUnit;
use Google\AdsApi\AdWords\v201710\cm\FrequencyCap;
use Google\AdsApi\AdWords\v201710\cm\FunctionOperator;
use Google\AdsApi\AdWords\v201710\cm\GeoTargetTypeSetting;
use Google\AdsApi\AdWords\v201710\cm\GeoTargetTypeSettingNegativeGeoTargetType;
use Google\AdsApi\AdWords\v201710\cm\GeoTargetTypeSettingPositiveGeoTargetType;
use Google\AdsApi\AdWords\v201710\cm\Keyword;
use Google\AdsApi\AdWords\v201710\cm\KeywordMatchType;
use Google\AdsApi\AdWords\v201710\cm\Language;
use Google\AdsApi\AdWords\v201710\cm\Level;
use Google\AdsApi\AdWords\v201710\cm\Location;
use Google\AdsApi\AdWords\v201710\cm\LocationCriterionService;
use Google\AdsApi\AdWords\v201710\cm\LocationExtensionOperand;
use Google\AdsApi\AdWords\v201710\cm\LocationGroups;
use Google\AdsApi\AdWords\v201710\cm\ManualCpcBiddingScheme;
use Google\AdsApi\AdWords\v201710\cm\MatchingFunction;
use Google\AdsApi\AdWords\v201710\cm\Money;
use Google\AdsApi\AdWords\v201710\cm\NegativeCampaignCriterion;
use Google\AdsApi\AdWords\v201710\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201710\cm\Operator;
use Google\AdsApi\AdWords\v201710\cm\OrderBy;
use Google\AdsApi\AdWords\v201710\cm\Paging;
use Google\AdsApi\AdWords\v201710\cm\Predicate;
use Google\AdsApi\AdWords\v201710\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201710\cm\Selector;
use Google\AdsApi\AdWords\v201710\cm\SortOrder;
use Google\AdsApi\AdWords\v201710\cm\TimeUnit;



use Google\AdsApi\AdWords\v201710\mcm\ManagedCustomerService;
use Google\AdsApi\AdWords\v201710\mcm\ManagedCustomer;
use Google\AdsApi\AdWords\v201710\mcm\ManagedCustomerOperation;



use Google\AdsApi\Common\OAuth2TokenBuilder;





/**
* Call this function to initialize thre adwords API use for a file the uses the extension
*/
function adwords_useAPI($useExamples = false){
	
	
	$errors = false;
	
	$errors = !((include_once $_SERVER["DOCUMENT_ROOT"] . "/cmsb/plugins/adrocket/Google/adwords-examples-32.0.0/vendor/autoload.php") == TRUE);
	
	
	if($useExamples){
		
		$GLOBALS["ADWORDS_API_EXAMPLES"] = TRUE;

	}
	
	if(!$errors){
		
		//echo "Starting<br />";
		$GLOBALS["ADWORDS_API_ACTIVE"] = TRUE;
	}
	
	return $errors;
	
}





/**
* returns a simple status text of the state of the api library
*/
function adwords_testFile(){
	
	//adwords_useAPI(true);
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) return "We are a go.";
	else return "API not active";
}






function adwords_managedCustomerServiceList($session){
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	

	$adWordsServices = new AdWordsServices();
	$mcs = $adWordsServices->get($session, ManagedCustomerService::class);
	
	// Create selector.
    $selector = new Selector();
    $selector->setFields(['CustomerId', 'Name']);
    $selector->setOrdering([new OrderBy('CustomerId', SortOrder::ASCENDING)]);
    $selector->setPaging(new Paging(0, 500));
	
	$entries = $mcs->get($selector);
	
	return $entries->getEntries();
}


























/**
* get the OAuth2 token for the top level manager account
*/
function adwords_getOAuth2Token(){
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	
	
	// Generate a refreshable OAuth2 credential for authentication.
	$oAuth2Credential = (new OAuth2TokenBuilder())
        ->fromFile()
        ->build();
	
	return $oAuth2Credential;
}









function adwords_getAPISession($oAuth2Credential){
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	
	
	// Construct an API session configured from a properties file and the OAuth2
    // credentials above.
    $session = (new AdWordsSessionBuilder())
        ->fromFile()
        ->withOAuth2Credential($oAuth2Credential)
        ->build();
		
		
	return $session;
    //self::runExample(new AdWordsServices(), $session);
}



function adwords_createManagedCustomerSession($customerID, $oAuth2Credential){
	
	
	$session = (new AdWordsSessionBuilder()) 
		->withClientCustomerId($customerID) 
		->withDeveloperToken($GLOBALS["ADWORDS_DEVELOPER_TOKEN"]) 
		->withOAuth2Credential($oAuth2Credential) 
		->build();
	
	return $session;
}






/**
* This function creates the adwords account for the adwrocket customer through the API.
* 
* We pass the user information needed to identify the account on Adwords, and set up the account
*
* The Newly created account ID number is returned
*/
function adwords_createManagedCustomerAccount($session, $companyName, $userName){
	
	//8868018642
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	$success = false;
	
	if(!$companyName || !$userName){
		
		return $success;
	}
	
	$accountTitle = $companyName." - ".$userName." [".time()."]";
	
	
	$adWordsServices = new AdWordsServices();
	$managedCustomerService = $adWordsServices->get($session, ManagedCustomerService::class);
	
	
	// Create a managed customer.
    $customer = new ManagedCustomer();
    $customer->setName($accountTitle);
    $customer->setCurrencyCode('CAD');
    $customer->setDateTimeZone('America/Toronto');
	
	//showme($customer);
	
	
	// Create a managed customer operation and add it to the list.
    $operations = [];
    $operation = new ManagedCustomerOperation();
    $operation->setOperator(Operator::ADD);
    $operation->setOperand($customer);
    $operations[] = $operation;
	
	// Create a managed customer on the server
    $customer = $managedCustomerService->mutate($operations)->getValue()[0];
	
	$customerID = $customer->getCustomerID();
	
	return $customerID;
}











/**
* create a new campaign under the customer session (create the managed customer session from custoerm id for this to work)
*
* return the id number for the new campaign
*/
function adwords_createCampaign($session, $campaignInfo = array(), $skipMutate = false){
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	
	
	$success = false;
	if(!$session) { return $success; }
	
	if(!$campaignInfo){
		
		$time = time();
		
		$campaignInfo = array(
			"title"     => "TEST TITLE " . $time,
			"budget"    => "2000.00",
			"type"      => "SEARCH",
			"start"     => date('Ymd', strtotime('+1 month')),
			"end"       => date('Ymd', strtotime('+2 month')),
			"locations" => array("Ajax")
		);
	}
	
	$microAmount     = floor((floatval($campaignInfo["budget"]) * 1000000)/30);
	$title           = $campaignInfo["title"] . ' Budget - ' . $time;
	$budget          = adwords_createSharedBudget($session, $title, $microAmount, $skipMutate);
	$adWordsServices = new AdWordsServices();
	
	
	
	
	// Create a campaign with only required settings.
    $campaign = new Campaign();
    $campaign->setName($campaignInfo["title"]);
    $campaign->setAdvertisingChannelType($campaignInfo["type"]);

	
    // Set shared budget (required).
    $campaign->setBudget(new Budget());
    $campaign->getBudget()->setBudgetId($budget->getBudgetId());

	
    // Set bidding strategy (required).
    $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
    $biddingStrategyConfiguration->setBiddingStrategyType(
        BiddingStrategyType::MANUAL_CPC);
    $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

	
	//set the status, start end end times
    $campaign->setStatus(CampaignStatus::PAUSED);
	$campaign->setStartDate($campaignInfo["start"]);
    $campaign->setEndDate($campaignInfo["end"]);
	
	
	//set location targetting
	//if()

	
	$result = null;

	
	
	
	if(!$skipMutate){
		
		$campaignService = $adWordsServices->get($session, CampaignService::class);
	
		// Create a campaign operation and add it to the operations list.
		$operations   = [];
		$operation    = new CampaignOperation();
		$operation    ->setOperand($campaign);
		$operation    ->setOperator(Operator::ADD);
		$operations[] = $operation;
		
		$result = $campaignService->mutate($operations);
		
		return $result;
	
	}
	else{ showme($campaign); }
	
	return $campaign;
}






/**
* Create and save shared budget - used for creating campigns
*/
function adwords_createSharedBudget($session, $title, $amount, $skipMutate = false){
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	
	
	if(!$title) $title   = "Unspecified - " . time();
	if(!$amount) $amount = 0;
	//$amount = 50000000;
	
	$amount = roundUpToAny($amount,5000);
	
	$adWordsServices = new AdWordsServices();
	$budgetService   = $adWordsServices->get($session, BudgetService::class);
	
	
	// Create the shared budget (required).
    $budget = new Budget();
    $budget ->setName($title);
    $money  = new Money();
    $money  ->setMicroAmount($amount);
    $budget ->setAmount($money);
    $budget ->setDeliveryMethod(BudgetBudgetDeliveryMethod::STANDARD);
	
	
	if(!$skipMutate){
		
		$operations = [];

		// Create a budget operation.
		$operation = new BudgetOperation();
		$operation->setOperand($budget);
		$operation->setOperator(Operator::ADD);
		$operations[] = $operation;
		
		// Create the budget on the server.
		$result = $budgetService->mutate($operations);
		$budget = $result->getValue()[0];
		
	}
	else{ showme($budget); }
	
	
	return $budget;
}


	
	
	
	
	
	
function adwords_getLocationCriterion($session, $locations){
	
	
	if(!$GLOBALS["ADWORDS_API_ACTIVE"]) die("The API was not activated for this page.<br />");
	
	
	$adWordsServices          = new AdWordsServices();
	$locationCriterionService = $adWordsServices->get($session, LocationCriterionService::class);
	
	
	$selector = new Selector();
	$selector->setFields(array(
		"Id",
		"LocationName",
		"CanonicalName",
		"DisplayType",
		"ParentLocations",
		"Reach",
	));
	
	$selector->setPredicates(array(
		new Predicate("LocationName", PredicateOperator::IN, $locations),
		new Predicate("Locale", PredicateOperator::EQUALS, array("en")),
	));
	
	$locationCriteria = $locationCriterionService->get($selector);
	
	return $locationCriteria;
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
function roundUpToAny($n,$x=5) {
    return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
}
?>