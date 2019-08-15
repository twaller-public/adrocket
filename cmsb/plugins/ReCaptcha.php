<?php
/*
Plugin Name: Captcha for CMS Promoter
Description: Add captcha to your forms
Version: 1.00
CMS Version Required: 3.00
*/




$GLOBALS["CAPTCHA_PLUGIN"] = TRUE;

$GLOBALS["CAPTCHA_GOOGLE_SCRIPT"]  = "<script src='https://www.google.com/recaptcha/api.js'></script>";
$GLOBALS["CAPTCHA_GOOGLE_POSTURL"] = "https://www.google.com/recaptcha/api/siteverify";
$GLOBALS["CAPTCHA_REQUEST_VAR"]    = "g-recaptcha-response";
$GLOBALS["CAPTCHA_SCRIPT"]         = "<script src='/cmsb/plugins/captchaPlugin/js/captcha.js'></script>";
$GLOBALS["CAPTCHA_TAG"]            = '<div class="g-recaptcha" data-sitekey="6LdTRLIUAAAAAC69CXkIl4NqlqeOfDjBG1isC5cb"></div>';
$GLOBALS["CAPTCHA_SECRET"]         = "6LdTRLIUAAAAACe9VvrhkvcRrlBS6IqTYBgGaPiN";
$GLOBALS["CAPTCHA_RESPONSE"]       = "";
$GLOBALS["CAPTCHA_IP"]             = "";
//$GLOBALS[""] = ;




//
function captcha_validateSubmission(){
	
	
	$GLOBALS["CAPTCHA_IP"]       = get_client_ip();
	$GLOBALS["CAPTCHA_RESPONSE"] = @$_REQUEST[$GLOBALS["CAPTCHA_REQUEST_VAR"]];
	
	
	$postVars = array(
		"secret"   => $GLOBALS["CAPTCHA_SECRET"],
		"response" => $GLOBALS["CAPTCHA_RESPONSE"],
		"remoteip" => $GLOBALS["CAPTCHA_IP"],
	
	);
	
	//showme($postVars);
	
	$result = json_decode(captcha_sendCurl($postVars), true);
	//if(!$result) die("none");
	//$result = captcha_sendHTTP($postVars);
	
	//showme($result);
	
	
	return $result["success"]; //false;
}





function captcha_sendHTTP($postVars){
	
	
	$post_data = http_build_query($postVars);
	
	
	$options = array(

	   'http' => array(
			'method'  => 'POST',
			'header'  => 'Content-type: application/x-www-form-urlencoded',
			'content' => $post_data
		)
	);
	
	

	$context     = stream_context_create($options);   

	$result_json = file_get_contents($GLOBALS["CAPTCHA_GOOGLE_POSTURL"], false, $context);
	$result      = json_decode($result_json, true);
	
	return $result;
	
}






//
function captcha_sendCurl($postVars){
	
	
	$json    = json_encode($postVars);
	$urlCode = rawurlencode($json);
	
	//echo $urlCode;
	//return null;
	$post_data = "secret=".$postVars["secret"]."&response=".$_POST['g-recaptcha-response']."&remoteip=".$postVars["remoteip"];


	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $GLOBALS["CAPTCHA_GOOGLE_POSTURL"]);
	curl_setopt($curl, CURLOPT_POST,           1);
	//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($curl, CURLOPT_POSTFIELDS,     $post_data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($curl, CURLOPT_HTTPHEADER,     array('Content-Type: application/json'));
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8', 'Content-Length: ' . strlen($post_data)));

	$curl_response = curl_exec($curl);

	return $curl_response;
}


// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}