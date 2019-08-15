<?php

	require_once "../../assets/_header.php";


	
	
	
	
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<div class="container" id="main_content" style="min-height:450px;">

	<h1>Please validate with reCaptcha to Continue</h1>
	
	<form action="?" method="POST">
	
		<input type="hidden" name="validate" value="1" />
		<div class="text-center">
			<?php echo $GLOBALS["CAPTCHA_TAG"]; ?>
			<br />
		</div>
		<input class="btn btn-lg btn-danger" type="submit" value="Validate">
    </form>

</div>





<?php require_once "../../assets/_footer.php"; /*footer*/ ?>
