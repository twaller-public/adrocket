<?php

	if($CURRENT_USER) return;
	//showme($_REQUEST);

	$_SESSION["CAPTCHA_VERIFIED"] = (@$_SESSION["CAPTCHA_VERIFIED"] == 1)? 1 : 0;


	if(!$_SESSION["CAPTCHA_VERIFIED"]){
		
		if(@$_REQUEST["validate"]){
			
			
			$valid = captcha_validateSubmission();
			if($valid){
				$_SESSION["CAPTCHA_VERIFIED"] = 1;
				header("Location: /campaign/create/title.php");
			}
		}
		//
		
		if(!$_SESSION["CAPTCHA_VERIFIED"]){
			require "captcha-intercept.php";
			exit;
		}
	}
	
	
?>