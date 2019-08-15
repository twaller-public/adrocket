<?php


	$vendor_headerText = "Create Your Ads on the Google Display Network";
	$continue_onClick  = "return false";
	
	
	if($ads["display"]){
		
		$display_preview_img = $ads["display"][0]->vals()["user_display_ad"][0]["urlPath"];
		$display_url         = $ads["display"][0]->get("destination_url");
		
	}
	if($ads["remarketing"]){
		
		$remarket_preview_img = $ads["remarketing"][0]->vals()["user_display_ad"][0]["urlPath"];
		
	}


?>

<!-- ad-type header -->
<div class="col-md-12" id="ec-process-header-1" style="opacity:0; height:0px;">
	<h2 style="font-size:35px;"><?php echo $vendor_headerText; ?><h2>
</div>



<!-- Display network ad creation -->
<div class="col-md-8 display-wrap previews" style="<?php echo $vNum == 2? "display:block;" : "display:none;"; ?>">
	<!-- <h2><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Ad Section"]; ?></h2> -->

	
	<div class="col-md-12">
	
		<p><em>You can upload up to 8 banner ads of <a href="#">pre-formatted dimensions</a>. When customers click on your Ad, they are brought to your web page, entered in 'Destination URL'</em></p>
	
		<br />
		<br />
		<br />
	
		<!-- Upload Button -->
		<button class="btn btn-primary" id="display-browse" type="button" style="font-size:24px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

		&nbsp;&nbsp;
		
		<!-- upload count -->
		<div class="alert alert-info" style="vertical-align:top; padding:6px; display:inline-block; font-weight: bold; font-size: 24px;">
			<span>0/8</span>
		</div>
		
		
		<br />
		<br />
		<br />
		<!-- Check for contact about ads -->
		<input type="checkbox" name="request_contact" id="request_contact" value="1" />
		&nbsp;&nbsp;<label style="font-size:20px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Request Contact Label"]; ?></label>
		
	</div>
	
	
	<!-- Upload Preview -->
	<div class="col-md-12 display-ad-preview">
		<?php if(!$display_preview_img): ?>
			<p class="no-ads" style="color: #C00; font-weight: bold; font-size: 24px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Uploaded Label"]; ?></p>
		<?php endif; ?>
		
		<div class="has-ads" style="<?php echo $display_preview_img? "display:block;" : "display:none;"; ?>">
			<img src="<?php echo $display_preview_img?: ""; ?> " alt=""/><br /><br/>
			
			
			<div class="progress" style="display:none; width:320px; height:60px; /*margin:auto;*/ border:1px solid #333;">
				<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
					<span class="sr-only"></span>
				</div>
			</div>
			
			
			<button class="btn btn-success" id="confirm" type="button" style="display:none;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Confirm Button"]; ?></button>
			<button class="btn btn-warning" id="remove" type="button" style="display:none;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Remove Button"]; ?></button>
		</div>
	</div>
	
	
	<!-- URL Destination -->
	<div class="col-md-6 form-group">
		<label  style="font-size:20px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["URL Destination Label"]; ?></label>
		<input class="form-control" type="text" name="destination_url" value="<?php echo $display_url?: ''; ?>" style="margin-top:2px;" />
	</div>
	
	
	<!-- Remarket Add -->
	<div class="col-md-12">
		<hr />
		<input type="checkbox" name="remarketing_added" id="remarketing_added" value="1"<?php if($cpnVals["remarketing_added"]) echo " checked"; ?>/>
		&nbsp;&nbsp;<label  style="font-size:20px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Remarketing Label"]; ?></label>
		<br />&nbsp;
		
		<div class="remarket-add-wrap">
		
			<h2>Remarketing</h2>
			
			<p><em>You can upload up to 8 banner ads of <a href="#">pre-formatted dimensions</a>. We will develop your Campaign and email you a snippet of code that will need to be placed on your web site for the Remarketing to take effect.</em></p>
			
			<br />
			<br />
			<br />
		
		
			<!-- Upload Button -->
			<button class="btn btn-primary" id="remarket-add-browse" type="button" style="font-size:24px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

			&nbsp;&nbsp;
		
			<!-- upload count -->
			<div class="alert alert-info" style="vertical-align:top; padding:6px; display:inline-block; font-weight: bold; font-size: 24px;">
				<span>0/8</span>
			</div>
			
			
			<br />
			<br />
			<br />
			
			
			<!-- Check for contact about ads -->
			<input type="checkbox" name="custom_ad" id="custom_ad" value="1" />
			&nbsp;&nbsp;<label style="font-size:20px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Request Contact Label"]; ?></label>

			
			
			
			<!-- Upload Preview -->
			<div class="col-md-12 remarket-ad-preview">
				<?php if(!$remarket_preview_img): ?>
					<p class="no-ads" style="color: #C00; font-weight: bold; font-size: 24px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Uploaded Label"]; ?></p>
				<?php endif; ?>
				
				<div class="has-ads" style="<?php echo $remarket_preview_img? "display:block;" : "display:none;"; ?>">
					<img src="<?php echo $remarket_preview_img?: ""; ?> " alt=""/><br /><br/>
					
					
					<div class="progress" style="display:none; width:320px; height:60px; /*margin:auto;*/ border:1px solid #333;">
						<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
							<span class="sr-only"></span>
						</div>
					</div>
					
					
					<button class="btn btn-success" id="confirm" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Confirm Button"]; ?></button>
					<button class="btn btn-warning" id="remove" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Remove Button"]; ?></button>
				</div>
			</div>
		</div>
	</div>

</div>



<!-- CONTINUE BUTTON -->
<div class="col-md-8 text-right" id="ec-process-input-1" style="opacity:0;/*height:0px;*/">
	
	<div class="form-group text-right">
		<input class="btn btn-lg btn-danger" type="button" name="Continue" value="Continue" onClick="<?php echo $continue_onClick; ?>" />
	</div>
</div>