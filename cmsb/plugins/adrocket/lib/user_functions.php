<?php


/**
* determineAgentType returns the current agent type
* The Agent type detemrines which page access permissions the user has
* 	Types:
*	* GUEST   - non logged in agent
*	* USER    - logged in general user
*	* MANAGER - campaign manager
*	* ADMIN   - system administrator
*	* CEO     - Adrocket CEO account
*/
function determineAgentType(){
	
	global $CURRENT_USER;
	
	//showme($CURRENT_USER);
	
	if(!@$CURRENT_USER) return "GUEST";
	
	switch($CURRENT_USER["account_type"]){
		case "User":
			return "USER";
			break;
		case "Manager":
			return "MANAGER";
			break;
		case "Admin":
			return "ADMIN";
			break;
		case "CEO":
			return "CEO";
			break;
	}
}



/**
* requireAgentType checks that the current agent can view
* if an address is provided for the redirect, the user is
* redirected to that page, otherwise we call die() with a
* general permission message.
*/
function requireAgentType($type, $redirect = ""){
	
	GLOBAL $AGENT_TYPE;
	
	$permission = false;
	
	if($type && $AGENT_TYPE == $type) $permission = true;
	
	if(!$permission){
		if($redirect) header("Location: $redirect");
		else die("You do not have permission to view this page.");
	}
}



/**
* blockAgentType allows page access to every agent type except
* the specified type.
* if an address is provided for the redirect, the user is
* redirected to that page, otherwise we call die() with a
* general permission message.
*/
function blockAgentType($type, $redirect = ""){
	
	GLOBAL $AGENT_TYPE;
	
	$permission = false;
	
	if($type && $AGENT_TYPE != $type) $permission = true;
	
	if(!$permission){
		if($redirect) header("Location: $redirect");
		else die("You do not have permission to view this page.");
	}
}




/**
* getNameFromID get the user's full name from their user id number
* @params:
*   $id : user id number as stored in the database table 'accounts'
* @return
*   String : the full name of the user 
*/
function getNameFromID($id){
  
	if(!$id) return null;
	return mysql_get('accounts', $id)['fullname'];
}



function getUserOptionsList(){
	
	return null;
}













function signupValidate_email(){
	
	$res   = array("status" => "error");
	$email = @$_REQUEST["val"];
	
	if(!$email){
		echo json_encode($res);
		exit;
	}
	
	$valid_email       = isValidEmail($email);
	$emailAlreadyInUse = mysql_count(accountsTable(), mysql_escapef("? IN (`username`, `email`)",$email));
	$result            = ($valid_email && !$emailAlreadyInUse)? 1 : 0;
	
	$res["status"] = "success";
	$res["result"] = $result;
	$res["name"]   = "email";
	if(!$result) $res["message"] = ($valid_email)? "That email address is already in use." : "That is not a valid email address.";
	else $res["message"] = "Email is available";
	
	echo json_encode($res);
	exit;
}



function signupValidate_username(){
	
	$res   = array("status" => "error");
	$username = @$_REQUEST["val"];
	
	if(!$username){
		echo json_encode($res);
		exit;
	}
	
	$valid_username       = !preg_match("/\s+/", $username);
	$usernameAlreadyInUse = mysql_count(accountsTable(), mysql_escapef("? IN (`username`, `email`)", $username));
	$result               = ($valid_username && !$usernameAlreadyInUse)? 1 : 0;
	
	$res["status"] = "success";
	$res["result"] = $result;
	$res["name"]   = "username";
	if(!$result) $res["message"] = ($valid_username)? "That username is already in use." : "Username cannot contain spaces.";
	else $res["message"] = "Username is available";
	
	echo json_encode($res);
	exit;
}



function signupValidate_phone(){
	
	$resp = array("status" => "error", "name" => "phone", "message" => "", "result" => 0);
		

	$isValid = isValidPhone(@$_REQUEST['val']);
	if(!$isValid){
		
		$resp["message"] = @$_REQUEST['val'] . " is not a valid phone number";
	}
	else{
		$resp['result'] = 1;
		$resp["message"] = @$_REQUEST['val'] . " is a valid phone number";
	}

	$resp["status"] = "success";
	echo json_encode($resp);
	exit;
}



function isValidPhone($num){
	
	$number = preg_replace("/[^0-9+]/",  "", $num);
	if(strlen($number) != 10) return false;
	return true;
}




function associateSessionWithAccount($num){
	
	$campaign = getCampaginForCreate();
	$campaign->set(array("user" => $num, "session_id" => ""));
}






?>