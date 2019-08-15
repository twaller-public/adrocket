<?php


	$vendor_headerText = "Create Your Ads on the Google Search Network";
	$continue_onClick  = "return false";
	
	
	
	if($ads["remarketing"]){
		
		$remarket_preview_img = $ads["remarketing"][0]->vals()["user_display_ad"][0]["urlPath"];
		
	}
	if($ads['adwords']){
		
		foreach($ads['adwords'] as $adw){
			
			$vals = $adw->vals();
			
			unset($vals['_link']);
			unset($vals['_filename']);
			unset($vals['_tableName']);
			unset($vals['createdByUserNum']);
			unset($vals['createdDate']);
			unset($vals['dragSortOrder']);
			unset($vals['updatedByUserNum']);
			unset($vals['updatedDate']);
			unset($vals['num']);
			unset($vals['campaign']);
			unset($vals['campaign:label']);
			
			//array_push($adwords_json, json_encode($vals));
			$adwords_json .= json_encode($vals) . ",";
		}
		
	}

?>

<!-- ad-type header -->
<div class="col-md-12" id="ec-process-header-1" style="opacity:0; height:0px;">
	<h1><?php echo $vendor_headerText; ?><h1>
</div>


<!-- Search Network Ad Creation -->
<div class="adwords-wrap previews text-center" style="<?php echo $vNum == 1? "display:block;" : "display:none;"; ?>">

	<!-- Ads wrap -->
	<div class="text-left" id="ad-wrap" style="border:1px solid black; padding:10px 80px; display:inline-block; position:relative;">

		<div class="text-center badges" style="font-size:16px;">
			<span class="label label-warning current" title="ad in progress" data-num="1">1</span>
			<span class="label label-primary" title="create this ad" data-num="2">2</span>
			<span class="label label-default" title="" data-num="3">3</span>
			<span class="label label-default" title="" data-num="4">4</span>
			<span class="label label-default" title="" data-num="5">5</span>
			<span class="label label-default" title="" data-num="6">6</span>
			<span class="label label-default" title="" data-num="7">7</span>
			<span class="label label-default" title="" data-num="8">8</span>
			<span class="label label-default" title="" data-num="9">9</span>
		</div>
		
		<span class="limit label label-success" style="display:none;"></span>
		
		<!--
		<span class="ad-arrow-left" title="view the previous ad"><span class="glyphicon glyphicon-chevron-left"></span></span>
		<span class="ad-arrow-right" title="view the next ad"><span class="glyphicon glyphicon-chevron-right"></span></span>
		-->
		
		<!-- Ad Start -->
		<div class="col-md-6 ad-ad" data-num="1" style="padding:10px; position:relative;">
			
			<p><strong>Ad Preview:</strong></p>
			
			<p style="font-size:14px;">Fill out the form below in order to create your ads</p>
			
			
			<!-- ad headlines -->
			<div class="headlines">
			
				<input class="limited" type="text" name="headline_1" value="" placeholder="Headline 1" data-limit="30" title="The First Headline for your ad" /> - 
				<input class="limited" type="text" name="headline_2" value="" placeholder="Headline 2" data-limit="30" />&#10;
				
			</div>
			
			<!-- url and phone -->
			<div class="url-line">
				<span>www.</span><input type="text" name="disp_url" value="" placeholder="address.com/destination" />
			</div>
			
			<!-- description -->
			<div class="desc">
				<textarea name="description" class="limited" rows="3" style="width:100%;" data-limit="80">The descriptive text for your ad. Up to 80 characters.</textarea>
			</div>
			
			<!-- extensions -->
			<div class="text-center exts">
			
				<input class="limited" type="text" name="ext_txt_1" value="" placeholder="extension text 1" maxlength="35" data-limit="25"/>
				<input class="limited" type="text" name="ext_txt_2" value="" placeholder="extension text 2" maxlength="35" data-limit="25"/><br />
				<input class="limited" type="text" name="ext_txt_3" value="" placeholder="extension text 3" maxlength="35" data-limit="25"/>
				<input class="limited" type="text" name="ext_txt_4" value="" placeholder="extension text 4" maxlength="35" data-limit="25"/>
			
			</div>
			
		</div>
		<div class="col-md-6 ad-ad">
			<!-- <hr /> -->
			
			<!-- extension links -->
			<div class="extlinks">
				<p><strong>Extension Links:</strong></p>
				<p style="font-size:14px">Enter the sub-domains you want each extension to link to on your site.</p>
				<span>/</span>
				<input type="text" name="ext_1" value="" placeholder="extension link 1" /><br />
				
				<span>/</span>
				<input type="text" name="ext_2" value="" placeholder="extension link 2" /><br />
				
				<span>/</span>
				<input type="text" name="ext_3" value="" placeholder="extension link 3" /><br />
				
				<span>/</span>
				<input type="text" name="ext_4" value="" placeholder="extension link 4" /><br />
			</div>
			
			<hr />
			
			<!-- destination url -->
			<div class="dest">
				<p><strong>Destination URL:</strong></p>
				<select name="url_prefix">
					<option value="http">HTTP</option>
					<option value="https">HTTPS</option>
				</select><span>://www.</span><input type="text" name="dest_url" value="" placeholder="address.com/destination" style="width:250px;" />
			</div>
			
			
			<span class="hidden-hl hidden-hl1" style="height:0px; visibility:hidden;"></span>
			<span class="hidden-hl hidden-hl2" style="height:0px; visibility:hidden;"></span>
		</div>
		
	</div>
	
	<!-- Blank Template -->
	<div class="ad-template" data-num="1" style="padding:10px; position:relative; display:none">

		<p><strong>Ad Preview:</strong></p>
		<span class="glyphicon glyphicon-remove ad-remove" title="delete this ad"></span>
		
		<!-- ad headlines -->
		<div class="headlines">
		
			<input class="limited" type="text" name="headline_1" value="" placeholder="Headline 1" data-limit="30" /> - 
			<input class="limited" type="text" name="headline_2" value="" placeholder="Headline 2" data-limit="30" />&#10;
			
		</div>
		
		<!-- url and phone -->
		<div class="url-line">
			<span>www.</span><input type="text" name="disp_url" value="" placeholder="address.com/destination" />
		</div>
		
		<!-- description -->
		<div class="description">
			<textarea name="description" class="limited" rows="3" style="width:100%;" data-limit="80">The descriptive text for your ad. Up to 80 characters.</textarea>
		</div>
		
		<!-- extensions -->
		<div class="text-center exts">
		
			<input class="limited" type="text" name="ext_txt_1" value="" placeholder="extension text 1" maxlength="35" data-limit="25"/>
			<input class="limited" type="text" name="ext_txt_2" value="" placeholder="extension text 2" maxlength="35" data-limit="25"/><br />
			<input class="limited" type="text" name="ext_txt_3" value="" placeholder="extension text 3" maxlength="35" data-limit="25"/>
			<input class="limited" type="text" name="ext_txt_4" value="" placeholder="extension text 4" maxlength="35" data-limit="25"/>
		
		</div>
		
		<hr />
		
		<!-- extension links -->
		<div class="extlinks">
			<p><strong>Extension Links:</strong></p>
			<p style="font-size:14px">Enter the sub-domains you want each extension to link to on your site.</p>
			<span>/</span>
			<input type="text" name="ext_1" value="" placeholder="extension link 1" /><br />
				
			<span>/</span>
			<input type="text" name="ext_2" value="" placeholder="extension link 2" /><br />
			
			<span>/</span>
			<input type="text" name="ext_3" value="" placeholder="extension link 3" /><br />
			
			<span>/</span>
			<input type="text" name="ext_4" value="" placeholder="extension link 4" /><br />
		</div>
		
		<hr />
		
		<!-- destination url -->
		<div class="dest">
			<p><strong>Destination URL:</strong></p>
			<select name="url_prefix">
				<option value="http">HTTP</option>
				<option value="https">HTTPS</option>
			</select><span>://www.</span><input type="text" name="dest_url" value="" placeholder="address.com/destination" style="width:250px;" />
		</div>
		
		
		
		
		<span class="hidden-hl hidden-hl1" style="height:0px; visibility:hidden; font-size:20px;"></span>
		<span class="hidden-hl hidden-hl2" style="height:0px; visibility:hidden; font-size:20px;"></span>
	</div>
	
	
	<!-- Remarket Add -->
	<div class="col-md-12">
		<hr />
		<input type="checkbox" name="remarketing_added" id="remarketing_added" value="1"<?php if($cpnVals["remarketing_added"]) echo " checked"; ?>/>
		&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Remarketing Label"]; ?></label>
		<br />&nbsp;
		
		<div class="remarket-add-wrap">
			<!-- Upload Button -->
			<button class="btn btn-primary" id="remarket-adw-browse" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

			&nbsp;&nbsp;
			<!-- Check for contact about ads -->
			<input type="checkbox" name="custom_ad" id="custom_ad" value="1" />
			&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Request Contact Label"]; ?></label>
			<br />&nbsp;
			
			<!-- Upload Preview -->
			<div class="col-md-12 remarket-ad-preview">
				<?php if(!$remarket_preview_img): ?>
					<p class="no-ads"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Uploaded Label"]; ?></p>
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
<div class="col-md-12 text-right" id="ec-process-input-1" style="opacity:0;/*height:0px;*/">
	
	<div class="form-group text-right">
		<input class="btn btn-lg btn-danger" type="button" name="Continue" value="Continue" onClick="<?php echo $continue_onClick; ?>" />
	</div>
</div>