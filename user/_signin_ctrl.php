<?php

	require_once "../assets/_app_init.php";

	if (!@$GLOBALS['WEBSITE_MEMBERSHIP_PLUGIN']) { die("You must activate the Website Membership plugin before you can access this page."); }

	// error checking
	$errorsAndAlerts = alert();
	if (@$CURRENT_USER)                                { $errorsAndAlerts .= "You are already logged in! <a href='/'>Click here to continue</a> or <a href='?action=logoff'>Logoff</a>.<br/>\n"; }
	if (!$CURRENT_USER && @$_REQUEST['loginRequired']) { $errorsAndAlerts .= "Please login to continue.<br/>\n"; }

	// save url of referring page so we can redirect user there after login
	// if (!getPrefixedCookie('lastUrl')) { setPrefixedCookie('lastUrl', @$_SERVER['HTTP_REFERER'] ); }

?>