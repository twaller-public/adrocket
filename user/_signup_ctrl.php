<?php
	require_once "../assets/_app_init.php";
	if (!@$GLOBALS['WEBSITE_MEMBERSHIP_PLUGIN']) { die("You must activate the Website Membership plugin before you can access this page."); }
	//session_start();
	
	if(@$_REQUEST["type"] == 'email_validation'){
		//validate the input email against other records in the database, and proper email format
		signupValidate_email();
	}
	
	if(@$_REQUEST["type"] == 'username_validation'){
		//validate the input username against other records in the database, and proper username format
		signupValidate_username();
	}
	
	if(@$_REQUEST["type"] == 'phone_validation'){
		
		signupValidate_phone();
	}
	
	
	
	//
	$useUsernames   = true; // Set this to false to disallow usernames, email will be used as username instead
	$showSignupForm = true; // don't change this value
	$pageTitle = "Create Your AdRocket Account";
	
	
	/**
* Fetch all campaign Province Options
*/
	$provinces      = new MultiRecordType("provinces", null, true);
	$provincesRecords = $provinces->records();
	
	
	
	
	/*
	$campaign_options = getCampaignOptions();
	$records          = $campaign_options["Provinces"]->records(); 
	$provinces        = getHTMLOptionsFromRecordData($records, );
	*/


	// error checking for already singed in user
	$errorsAndAlerts = "";
	if (@$CURRENT_USER) {
		$errorsAndAlerts .= "You are already signed up! <a href='/'>Click here to continue</a>.<br/>\n";
		$showSignupForm = false;
	}



	// process form
	if (@$_POST['save']) {
		
		// redirect to profile page after after signing up
		setPrefixedCookie('lastUrl', $GLOBALS['WEBSITE_LOGIN_PROFILE_URL']);

		$errorsAndAlerts = "";
		// error checking
		$emailAlreadyInUse    = mysql_count(accountsTable(), mysql_escapef("? IN (`username`, `email`)", @$_REQUEST['email']));
		$usernameAlreadyInUse = mysql_count(accountsTable(), mysql_escapef("? IN (`username`, `email`)", @$_REQUEST['username']));
		$validPhone           = isValidPhone(@$_REQUEST['phone']);

		if     (!@$_REQUEST['fullname'])                       { $errorsAndAlerts .= "You must enter your full name!<br/>\n"; }
		if     (!@$_REQUEST['company'])                        { $errorsAndAlerts .= "You must enter your company name!<br/>\n"; }
		if     (!@$_REQUEST['email'])                          { $errorsAndAlerts .= "You must enter your email!<br/>\n"; }
		if     (@$_REQUEST['phone'] && !$validPhone)           { $errorsAndAlerts .= "The phone number you entered is not valid!<br/>\n"; }
		elseif (!isValidEmail(@$_REQUEST['email']))            { $errorsAndAlerts .= "Please enter a valid email (example: user@example.com)<br/>\n"; }
		elseif ($emailAlreadyInUse)                            { $errorsAndAlerts .= "That email is already in use, please choose another!<br/>\n"; }
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
		
		
		
		
		if(!@$_REQUEST['agree_tos'])                             { $errorsAndAlerts .= "You must agree to the Terms of Use!<br/>\n"; }
		
		// add user
		if (!$errorsAndAlerts) {
			
			// generate password
			$passwordText = wsm_generatePassword();
			$passwordHash = getPasswordDigest($passwordText);

			//
			$colsToValues = array();
			$colsToValues['createdDate=']     = 'NOW()';
			$colsToValues['updatedDate=']     = 'NOW()';
			$colsToValues['createdByUserNum'] = 0;
			$colsToValues['updatedByUserNum'] = 0;

			// fields defined by form:
			$colsToValues['agree_tos']        = $_REQUEST['agree_tos'];
			$colsToValues['fullname']         = $_REQUEST['fullname'];
			$colsToValues['company']          = $_REQUEST['company'];
			$colsToValues['email']            = $_REQUEST['email'];
			$colsToValues['phone']            = $_REQUEST['phone'];
			$colsToValues['province']         = $_REQUEST['province'];
			$colsToValues['country']          = $_REQUEST['country'];
			$colsToValues['username']         = coalesce( @$_REQUEST['username'], $_REQUEST['email'] ); // email is saved as username if usernames not supported
			$colsToValues['password']         = $passwordHash;
			$colsToValues['account_type']     = "User";
      
      
			
			if(@$_REQUEST['complete_signup']){
        $colsToValues["direct_to_campaign"] = 1;
        $colsToValues["signup_campaign"] = @$_REQUEST['campaignNum'];
      
      }
			// ... add more form fields here by copying the above line!
			$userNum = mysql_insert(accountsTable(), $colsToValues, true);	
			

			// set access rights for CMS so new users can access some CMS sections
			$setAccessRights = false; // set to true and set access tables below to use this
			
			if ($setAccessRights && accountsTable() == "accounts") { // this is only relevant if you're adding users to the CMS accounts table

				// NOTE: You can repeat this block to grant access to multiple sections
				mysql_insert('_accesslist', array(
					'userNum'      => $userNum,
					'tableName'    => '_sample',   // insert tablename you want to grant access to, or 'all' for all sections
					'accessLevel'  => '0',         // access level allowed: 0=none, 6=author, 9=editor
					'maxRecords'   => '',          // max listings allowed (leave blank for unlimited)
					'randomSaveId' => '123456789', // ignore - for internal use
				));
			}
			
			
			//create the billing address records
			$b_addr = new MultiRecordType("billing_addresses");
			$insert = array(
				"user_acct" => $userNum,
				"addr_1"    => @$_REQUEST["addr_1"],
				"addr_2"    => @$_REQUEST["addr_2"],
				"city"      => @$_REQUEST["city"],
				"postal"    => @$_REQUEST["postal"],
			);
			
			
			
			$b_addr->create($insert);
			
			
			
			
			
			//if the user has been directed here after creating a campaign without an account,
			//associate that campaign with the new account they just created
			if(@$_REQUEST['complete_signup']){ associateSessionWithAccount($userNum); }

			// send message
			list($mailErrors, $fromEmail) = wsm_sendSignupEmail($userNum, $passwordText);
			if ($mailErrors) { alert("Mail Error: $mailErrors"); }

			// show thanks
			$errorsAndAlerts  = "Thanks, We've created an account for you and emailed you your password.<br/><br/>\n";
			$errorsAndAlerts .= "If you don't receive an email from us within a few minutes check your spam filter for messages from {$fromEmail}<br/><br/>\n";
			$errorsAndAlerts .= "<a href='{$GLOBALS['WEBSITE_LOGIN_LOGIN_FORM_URL']}'>Click here to login</a>.";
			$pageTitle = "";

			$_REQUEST        = array(); // clear form values
			$showSignupForm  = false;
		}
	}
  else {
    $campaign = getCampaginForCreate(false);

    $_REQUEST['campaignNum'] = $campaign->get("num");
    $_REQUEST['fullname'] = $campaign->get("contact_name");
    $_REQUEST['company'] = $campaign->get("contact_name");
    
    
    $_REQUEST['email']    =  $campaign->get("contact_email");        
    $_REQUEST['phone']    =  $campaign->get("contact_phone");    
    $_REQUEST['province'] =  $campaign->get("contact_province");    

	//build the option html from the industry options and the current industry selection
	$provinces = getHTMLOptionsFromRecordData($provincesRecords, @$_REQUEST["province"]);

  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  