<?php $GLOBALS['WEBSITE_MEMBERSHIP_PROFILE_PAGE'] = true; // prevent redirect loops for users missing fields listed in $GLOBALS['WEBSITE_LOGIN_REQUIRED_FIELDS'] ?>
<?php

require_once "../assets/_app_init.php";
# Developer Notes: To add "Agree to Terms of Service" checkbox (or similar checkbox field), just add it to the accounts menu in the CMS and uncomment agree_tos lines

if (!@$GLOBALS['WEBSITE_MEMBERSHIP_PLUGIN']) { die("You must activate the Website Membership plugin before you can access this page."); }

//
$useUsernames   = true; // Set this to false to disallow usernames, email will be used as username instead

// error checking
$errorsAndAlerts = "";
if (@$_REQUEST['missing_fields']) { $errorsAndAlerts = "Please fill out all of the following fields to continue.<br/>\n"; }
if (!$CURRENT_USER) { websiteLogin_redirectToLogin(); }

//get the billing info
$billing_addr = new MultiRecordType("billing_addresses", array("where" => "user_acct = {$CURRENT_USER['num']}"), true);
if(!$billing_addr->meta()["noRecordsFound"]) $billing_addr = $billing_addr->records()[0];
else $billing_addr = null;

//showme($billing_addr);

$campaign_options = getCampaignOptions();
$records          = $campaign_options["Provinces"]->records(); 
$provinces = getHTMLOptionsFromRecordData($records, $CURRENT_USER["province"]);



### Update User Profile
if (@$_POST['save']) {
	
	//showme($_REQUEST);

    // error checking
    $emailAlreadyInUse    = mysql_count(accountsTable(), mysql_escapef("`num` != ?  AND ? IN (`username`, `email`)", $CURRENT_USER['num'], @$_REQUEST['email']));
    $usernameAlreadyInUse = mysql_count(accountsTable(), mysql_escapef("`num` != ?  AND ? IN (`username`, `email`)", $CURRENT_USER['num'], @$_REQUEST['username']));
	$validPhone           = isValidPhone(@$_REQUEST['phone']);

    if     (!@$_REQUEST['fullname'])            { $errorsAndAlerts .= "You must enter your full name!<br/>\n"; }
	if     (!@$_REQUEST['company'])             { $errorsAndAlerts .= "You must enter your company name!<br/>\n"; }
    if     (!@$_REQUEST['email'])               { $errorsAndAlerts .= "You must enter your email!<br/>\n"; }
	if     (@$_REQUEST['phone'] && !$validPhone){ $errorsAndAlerts .= "The phone number you entered is not valid!<br/>\n"; }
    elseif (!isValidEmail(@$_REQUEST['email'])) { $errorsAndAlerts .= "Please enter a valid email (example: user@example.com)<br/>\n"; }
    elseif ($emailAlreadyInUse)                 { $errorsAndAlerts .= "That email is already in use, please choose another!<br/>\n"; }
    if ($useUsernames) {
      if     (!@$_REQUEST['username'])                     { $errorsAndAlerts .= "You must choose a username!<br/>\n"; }
      elseif (preg_match("/\s+/", @$_REQUEST['username'])) { $errorsAndAlerts .= "Username cannot contain spaces!<br/>\n"; }
      elseif ($usernameAlreadyInUse)                       { $errorsAndAlerts .= "That username is already in use, please choose another!<br/>\n"; }
    }
    elseif (!$useUsernames) {
      if (@$_REQUEST['username'])                          { $errorsAndAlerts .= "Usernames are not allowed!<br/>\n"; }
    }
	
	//billing errors
	if(
		!@$_REQUEST["addr_1"]   || 
		!@$_REQUEST["city"]     || 
		!@$_REQUEST["postal"]   || 
		!@$_REQUEST["province"]
	) {$errorsAndAlerts .= "Please fill out all billing information!<br /"; }
    //if (!@$_REQUEST['agree_tos'])               { $errorsAndAlerts .= "You must agree to the Terms of Service!<br/>\n"; }

    // update user
    if (!$errorsAndAlerts) {
		$colsToValues = array();
		$colsToValues['agree_tos']        = $_REQUEST['agree_tos'];
		$colsToValues['fullname']         = $_REQUEST['fullname'];
		$colsToValues['username']         = coalesce( @$_REQUEST['username'], $_REQUEST['email'] ); // email is saved as username if username code (not this line) is commented out
		$colsToValues['email']            = $_REQUEST['email'];
		$colsToValues['phone']            = $_REQUEST['phone'];
		$colsToValues['province']         = $_REQUEST['province'];
		$colsToValues['country']          = $_REQUEST['country'];
		// ... add more form fields here by copying the above line!
		$colsToValues['updatedByUserNum'] = $CURRENT_USER['num'];
		$colsToValues['updatedDate=']     = 'NOW()';
		mysql_update(accountsTable(), $CURRENT_USER['num'], null, $colsToValues);
		
		
		$insert = array(
			"user_acct" => $CURRENT_USER['num'],
			"addr_1"    => @$_REQUEST["addr_1"],
			"addr_2"    => @$_REQUEST["addr_2"],
			"city"      => @$_REQUEST["city"],
			"postal"    => @$_REQUEST["postal"],
			"province"  => @$_REQUEST["province"],
		);
		
		if($billing_addr){
			
			$billing_addr->set($insert);
		}
		else{
			
			//create the billing address records
			$b_addr = new MultiRecordType("billing_addresses");
			$b_addr->create($insert);
		}
		
		
		
		

		// on success
		websiteLogin_setLoginTo( $colsToValues['username'], $CURRENT_USER['password'] );  // update login session username in case use has changed it.
		$errorsAndAlerts = "Thanks, we've updated your profile!<br/>\n";
	}
}


### Change Password
if (@$_POST['changePassword']) {

    // error checking
    $_REQUEST['oldPassword'] = preg_replace("/^\s+|\s+$/s", '', @$_REQUEST['oldPassword']); // v1.10 remove leading and trailing whitespace
    $oldPasswordHash  = getPasswordDigest(@$_REQUEST['oldPassword']);
    if     (!@$_REQUEST['oldPassword'])                             { $errorsAndAlerts .= "Please enter your current password<br/>\n"; }
    elseif ($oldPasswordHash != $CURRENT_USER['password'])          { $errorsAndAlerts .= "Current password isn't correct!<br/>\n"; }
    $newPasswordErrors = getNewPasswordErrors(@$_REQUEST['newPassword1'], @$_REQUEST['newPassword2'], $CURRENT_USER['username']); // v2.52
    $errorsAndAlerts  .= nl2br(htmlencode($newPasswordErrors));

    // change password
    if (!$errorsAndAlerts) {
		$passwordHash = getPasswordDigest($_REQUEST['newPassword2']);
		mysql_update( accountsTable(), $CURRENT_USER['num'], null, array('password' => $passwordHash)); // update password
		websiteLogin_setLoginTo( $CURRENT_USER['username'], $_REQUEST['newPassword2'] );                // update current login session
		unset($_REQUEST['oldPassword'], $_REQUEST['newPassword1'], $_REQUEST['newPassword2']);          // clear form password fields
		$errorsAndAlerts = "Thanks, we've updated your password!<br/>\n";
    }
} ### END: Change Password


### Delete Account
if (@$_POST['deleteAccount']) {
	if ($CURRENT_USER['isAdmin']) { die("Error: Deleting admin accounts is not permitted!"); }
	removeUploads( mysql_escapef("tableName = ? AND recordNum = ?", accountsTable(), $CURRENT_USER['num']) );  // delete uploads
	mysql_delete(accountsTable(), $CURRENT_USER['num']); // delete user record
	websiteLogin_redirectToLogin(); // redirect to login
} ### END: Delete Account


// prepopulate form with current user values
foreach ($CURRENT_USER as $name => $value) {
	if (array_key_exists($name, $_REQUEST)) { continue; }
	$_REQUEST[$name] = $value;
}


if($billing_addr){
	
	$billing_vals = $billing_addr->vals();

	unset($billing_vals["_filename"]);
	unset($billing_vals["_link"]);
	unset($billing_vals["_tableName"]);
	unset($billing_vals["num"]);
	unset($billing_vals["createdByUserNum"]);
	unset($billing_vals["createdDate"]);
	unset($billing_vals["dragSortOrder"]);
	unset($billing_vals["province:label"]);
	//unset($billing_vals["province"]);
	unset($billing_vals["updatedByUserNum"]);
	unset($billing_vals["updatedDate"]);
	unset($billing_vals["user_acct"]);
	unset($billing_vals["user_acct:label"]);
	
	//showme($billing_vals);

	foreach ($billing_vals as $name => $value) {
		if (array_key_exists($name, $_REQUEST)) { continue; }
		$_REQUEST[$name] = $value;
	}
}

//showme($_REQUEST);
?>

