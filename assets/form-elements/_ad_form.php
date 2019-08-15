<?php

	$vNum = $cpnVals["vendor"];
	$ads  = getCampaignAds($cpnVals["num"]);
	//showme($ads);
	//showme($cpnVals);
	
	$display_url          = null;
	$display_preview_img  = null;
	$remarket_preview_img = null;
	
	$adwords_json         = "";
	
	if($ads["display"]){
		
		$display_preview_img = $ads["display"][0]->vals()["user_display_ad"][0]["urlPath"];
		$display_url         = $ads["display"][0]->get("destination_url");
		
	}
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


<div class="row">

	<div class="col-md-12 modal-ads-wrap">
	
		<?php if(!$vNum): ?>
		
			<h3><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Vendor Not Set Text"]; ?></h3>
		
		<?php else: ?>
	

			<!-- display ads -->
			<div class="display-wrap previews" style="<?php echo $vNum == 2? "display:block;" : "display:none;"; ?>">
				<h2><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Ad Section"]; ?></h2>
			
				
				<div class="col-md-12">
					<!-- Upload Button -->
					<button class="btn btn-primary" id="display-browse" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

					&nbsp;&nbsp;
					<!-- Check for contact about ads -->
					<input type="checkbox" name="request_contact" id="request_contact" value="1" />
					&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Request Contact Label"]; ?></label>
					<br />&nbsp;
				</div>
				
				
				<!-- Upload Preview -->
				<div class="col-md-12 display-ad-preview">
					<?php if(!$display_preview_img): ?>
						<p class="no-ads"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Uploaded Label"]; ?></p>
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
					<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["URL Destination Label"]; ?></label>
					<input class="form-control" type="text" name="destination_url" value="<?php echo $display_url?: ''; ?>" />
				</div>
				
				
				<!-- Remarket Add -->
				<div class="col-md-12">
					<hr />
					<input type="checkbox" name="remarketing_added" id="remarketing_added" value="1"<?php if($cpnVals["remarketing_added"]) echo " checked"; ?>/>
					&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Remarketing Label"]; ?></label>
					<br />&nbsp;
					
					<div class="remarket-add-wrap">
						<!-- Upload Button -->
						<button class="btn btn-primary" id="remarket-add-browse" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

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
			
			
			
			
			
			<!-- adwords ads -->
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
					<div class="ad-ad" data-num="1" style="padding:10px; position:relative;">
						
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
			
			
			
			
			
			
			
			<!-- remarketing ads -->
			<div class="remarketing-wrap previews" style="<?php echo $vNum == 7? "display:block;" : "display:none;"; ?>">
				<!-- Upload Button -->
				<button class="btn btn-primary" id="remarket-browse" type="button"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

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
						
						
						<button class="btn btn-success" id="confirm" type="button" style="display:none;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Confirm Button"]; ?></button>
						<button class="btn btn-warning" id="remove" type="button" style="display:none;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Remove Button"]; ?></button>
					</div>
				</div>
			</div>
	
		<?php endif; ?>
	</div>
</div>

<script src="/js/plupload.full.min.js"></script>
<script>

var vNum = <?php echo $vNum?: 0; ?>

var display_uploader  = null;
var remarket_uploader = null;
var remarket_button   = (vNum == 1)? "remarket-adw-browse" : (vNum == 2)? "remarket-add-browse" : "remarket-browse";

var ad_wrap       = "#ad-wrap";
var limited       = ".limited";
var limit_display = $("span.limit");

var adwords_created = [<?php echo $adwords_json; ?>];




function startDisplayUploader(){
	
	display_uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button:   'display-browse', // this can be an id of a DOM element or the DOM element itself
		url:             "/campaign/create.php",
		multi_selection: false,
		multipart_params: { 
			cpn: <?php echo $cpnNum; ?>,
			rm: 0
		},
		filters: {mime_types : [{title : "Image Files", extensions : "jpg,gif,png"}]},
		flash_swf_url : 'upload/Moxie.swf',
		silverlight_xap_url : 'upload/Moxie.xap'
	});
	
	display_uploader.init();
	uploader_addFileHandler(display_uploader, ".display-ad-preview");
	uploader_BindRemoveLink(display_uploader, ".display-ad-preview");
	uploader_BindUploadLink(display_uploader, ".display-ad-preview");
	uploader_BindUploadProgress(display_uploader, ".display-ad-preview");
	uploader_BindFileUpload(display_uploader, ".display-ad-preview");
}



function startRemarketUploader(button = ""){
	
	if(!button) button = 'remarket-browse';
	
	remarket_uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		browse_button:   button, // this can be an id of a DOM element or the DOM element itself
		url:             "/campaign/create.php",
		multi_selection: false,
		multipart_params: { 
			cpn: <?php echo $cpnNum; ?>,
			rm: 1
		},
		filters: {mime_types : [{title : "Image Files", extensions : "jpg,gif,png"}]},
		flash_swf_url : 'upload/Moxie.swf',
		silverlight_xap_url : 'upload/Moxie.xap'
	});
	
	remarket_uploader.init();
	uploader_addFileHandler(remarket_uploader, ".remarket-ad-preview");
	uploader_BindRemoveLink(remarket_uploader, ".remarket-ad-preview");
	uploader_BindUploadLink(remarket_uploader, ".remarket-ad-preview");
	uploader_BindUploadProgress(remarket_uploader, ".remarket-ad-preview");
	uploader_BindFileUpload(remarket_uploader, ".remarket-ad-preview");
}


//pass the pl uploader
function uploader_addFileHandler(pl, preview_tag){
	
	pl.bind('FilesAdded', function(up, files) {
		
		var img_replace = false;
			
		//we only allow one file to be uploaded to this page. If the user attempts to add another,
		//confirm the replacement, otherwise simply discard the new file.
		if(up.files.length > 1){
			img_replace = confirm("Replace the current Image with this one?");
			
			if(img_replace){ up.removeFile(up.files[0]); }
			else{ up.removeFile(up.files[1]); }
		}
		
		
		//display the image preview on the screen
		var image     = $(preview_tag + " img");
		var preloader = new mOxie.Image();
		
		preloader.onload = function() {
			preloader.downsize( 320, 320 );
			image.prop( "src", preloader.getAsDataURL() );
		};
		
		preloader.load(files[0].getSource());
		$(preview_tag + " button#confirm").toggle(true);
		$(preview_tag + " button#remove").toggle(true);
		$(preview_tag + " div.has-ads").toggle(true);
		$(preview_tag + " p.no-ads").toggle(false);
		return false;
	});
	
	return false;
}



function uploader_BindRemoveLink(pl, preview_tag){
	
	
	$(preview_tag + " #remove").on("click", function(e){
		
		e.preventDefault();
		
		if(confirm("Remove this Image?")){
			pl.removeFile(pl.files[0]);
			
			$(preview_tag + " img").attr("src", "");
			$(preview_tag + " div.has-ads").toggle(false);
			$(preview_tag + " p.no-ads").toggle(true);
		}	
	});
}



function uploader_BindUploadLink(pl, preview_tag){
	
	$(preview_tag + " button#confirm").on("click", function(){

		$(this).prop("disabled", true);
		
		var errors     = "";
		//var msg_sec    = $("#user-messages");
		var new_params = "";
		
		//msg_sec.html("");
		$(preview_tag + ".progress").toggle(true);
		pl.start();
		
		return false;
	});
}



function uploader_BindUploadProgress(pl, preview_tag){
	
	pl.bind('UploadProgress', function(up, file) {  //tw
		//alert();
		$(preview_tag + " .progress .progress-bar").prop("aria-valuenow", file.percent);
		$(preview_tag + " .progress .progress-bar").css("width", file.percent + "%");
		$(preview_tag + " span.sr-only").html(file.percent + "%");
	});
}



function uploader_BindFileUpload(pl, preview_tag){
	
	pl.bind('FileUploaded', function(up, file, info) {
			
		var res = JSON.parse(info.response);
		
		console.log(res);
		$(preview_tag + " #confirm").prop("disabled", false);
		
		/*
		if(res['errors']){ alert(res['errors_text']); }
		else{
			
			var msg_sec     = $(".upload-msg");
			var success_sec = '<div class="alert alert-success" role="alert">';
			var msg         = "<p><strong>Success!</strong> Image Added.</p>";
			
			msg_sec.append(success_sec + msg + "</div>");
		}
		*/
	});
	
}



function toggleRemarketAdd(){
	
	var rm_check = "input[name='remarketing_added']";
	
	if($(rm_check).prop("checked")){ 
		$("div.remarket-add-wrap").toggle(true); 
		//startRemarketUploader();
	}
	else{ $("div.remarket-add-wrap").toggle(false); } 
	
	$("body").on("change", rm_check, function(){
		
		//alert($(this).prop("checked"));
		
		if($(this).prop("checked")){ 
		
			$("div.remarket-add-wrap").toggle(true); 
			//if(!remarket_uploader) startRemarketUploader();
		}
		else{ 
			$("div.remarket-add-wrap").toggle(false); 
			//if(remarket_uploader) remarket_uploader.destroy();
		} 
	});
}




function adjustHeadlineWidths(){
	
	var h1 = $("input[name='headline_1']:visible");
	var h2 = $("input[name='headline_2']:visible");
	
	var hidden_h1 = $("span.hidden-hl1:visible");
	var hidden_h2 = $("span.hidden-hl2:visible");
	
	var h1_w = 0;
	var h2_w = 0;
	
	h1_val = (h1.val() != "")? h1.val() : h1.attr("placeholder");
	h2_val = (h2.val() != "")? h2.val() : h2.attr("placeholder");
	
	//set the text in the hidden spans to the headline text
	hidden_h1.text(h1_val);
	hidden_h2.text(h2_val);
	
	//get the width of the spans (width of text)
	h1_w = hidden_h1.width();
	h2_w = hidden_h2.width();
	
	//set the headline inputs to that width
	h1.css("width", h1_w);
	h2.css("width", h2_w);
	
	
}



function dynamicHeadlineWidthAdjust(){

	$("body").on("keyup", ".headlines input", function(){ adjustHeadlineWidths(); });
}



function serializeAdwords(){
	
	var ads    = $(".ad-ad");
	var result = [];
	
	ads.each(function(){
		
		var ins = $(this).find("input, select, textarea");
		ins     = ins.serialize();
		result.push(ins);
		console.log(ins);
	});
	
	return result;
}



function limitedInputHandler(){
	
	//on focus - set and show the display
	$("body").on("focus", limited, function(){ updateLimitDisplay(this); });
	
	//on focus out - hide the display and reset
	$("body").on("focusout", limited, function(){
		
		limit_display.toggle(false);
		limit_display.text("");
	});
	
	//on key up - update the display
	$("body").on("keyup", limited, function(){ updateLimitDisplay(this); });
}



function updateLimitDisplay(elt){
	
	var limit  = $(elt).attr("data-limit");
	var count  = $(elt).val().length + 1;
	var thresh = parseInt(limit * 0.1);   //the thresh hold for when the limit display changes to orange warning
	
	if(count >= (limit - thresh) && count <= limit){
		
		limit_display.removeClass("label-success");
		limit_display.removeClass("label-danger");
		
		limit_display.addClass("label-warning");
	} 
	else if(count > limit){
		
		limit_display.removeClass("label-success");
		limit_display.removeClass("label-warning");
		
		limit_display.addClass("label-danger");
	} 
	else{
		limit_display.removeClass("label-danger");
		limit_display.removeClass("label-warning");
		
		limit_display.addClass("label-success");
	}
	
	limit_display.text(count + "/" + limit);
	
	limit_display.toggle(true);
}



function badgeClickHandler(){
	
	$("div.badges > span.label").on("click", function(){
		
		
		//var datanum = $(this).attr("data-num");
		
		//alert(datanum + " - " + $(this).attr("class"));
		
		if($(this).hasClass("label-default")){ return false; }
		else if($(this).hasClass("label-primary")){ createNewAdwordsElt(this); }
		else{ changeActiveAdwordDisplay(this); }
		
		adjustHeadlineWidths();
	});
}



function createNewAdwordsElt(badge_elt){
	
	var datanum = $(badge_elt).attr("data-num");
	
	//attached a new adwords element and display it, hide any others
	$("#ad-wrap > .badges > span.label").removeClass("current");
	
	$(badge_elt).removeClass("label-primary");
	$(badge_elt).addClass("label-warning");
	$(badge_elt).addClass("current");
	$(badge_elt).attr("title", "ad in progress");
	
	$("#ad-wrap > .badges > span[data-num='" + (parseInt(datanum) + 1) + "']").removeClass("label-default");
	$("#ad-wrap > .badges > span[data-num='" + (parseInt(datanum) + 1) + "']").addClass("label-primary");
	$("#ad-wrap > .badges > span[data-num='" + (parseInt(datanum) + 1) + "']").attr("title", "create this ad");
	
	var ad_node = $("div.ad-template").clone();
	
	ad_node.removeClass("ad-template");
	ad_node.addClass("ad-ad");
	ad_node.attr("data-num", datanum);
	
	$(".ad-ad[data-num='" + (parseInt(datanum) - 1) + "']").after(ad_node);
	
	$(".ad-ad").hide("slow");
	ad_node.show("slow");
	
	//adjustHeadlineWidths();
	return false;
}



function changeActiveAdwordDisplay(badge_elt){
	
	var datanum = $(badge_elt).attr("data-num");
			
	$(badge_elt).removeClass("label-primary");
	$(badge_elt).addClass("label-warning");
	
	$("#ad-wrap > .badges > span.label").removeClass("current");
	$(badge_elt).addClass("current");
	
	$(".ad-ad").hide("slow");
	$(".ad-ad[data-num='" + datanum + "']").show("slow", adjustHeadlineWidths);
	
	return false;
}




function adDeleteHandler(){
	
	
	$("body").on("click", "span.ad-remove", function(){
		
		
		var all_ads   = null;
		var ad_parent = $(this).parents(".ad-ad");
		var ad_num    = ad_parent.attr("data-num");
		var ad_nums   = [];
		
		var reIndexAds  = false;
		var next_active = 1;
		
		if(ad_num == "1") return false;

		
		if(confirm("Really delete this ad? Its contents will be lost.")){
			
			//remove the ad elt for the number
			ad_parent.hide("slow");
			ad_parent.remove();
			next_active = parseInt(ad_num) - 1;
			
			//at this point we have ad #1 left, which cannot be removed
			//AND possibly some ads after the removed number that need to be re-indexed
			all_ads = $(".ad-ad");
			
			all_ads.each(function(){
			
				var num = $(this).attr("data-num");
				
				if(num == "1") return;
				if(parseInt(num) > parseInt(ad_num)) reIndexAds = true;
				
				if(reIndexAds) $(this).attr("data-num", parseInt(num) - 1);
				
				ad_nums.push(num);
			});
			
			console.log("Remove ad num: " + ad_num);
			console.log("Ad Num list: ");
			console.log(ad_nums);
			
			//display the previous ad
			$(".ad-ad[data-num='"+next_active+"']").show("slow");
			
			//now we need to make sure the ad selection section is properly displaying
			//which ads are in progress, not created, etc.
			if(reIndexAds){
				
				//we need to shift the appearance of the badges to the left 1 elt
				var last      = $(".badges span.label[data-num='" + (parseInt(ad_nums[ad_nums.length - 1]) + 1) + "']");
				var next_last = $(".badges span.label[data-num='" + ad_nums[ad_nums.length - 1] + "']");
			}
			else{
				
				//we only had 2 ads,
				var last      = $(".badges span.label[data-num='3']");
				var next_last = $(".badges span.label[data-num='2']");
			}
			
			//the highest badge get turned back into grey
			last.removeClass("label-primary");
			last.addClass("label-default");
			last.attr("title", "");
			
			//the second highest badge gets turned to blue
			next_last.removeClass("label-warning");
			next_last.addClass("label-primary");
			next_last.attr("title", "create this ad");
			
			$("#ad-wrap > .badges > span.label").removeClass("current");
			$("span.label[data-num='"+next_active+"']").addClass("current");
		}
		
	});
}





function fillOutCreatedAdwords(){
	
	console.log(adwords_created);
	
	if(adwords_created.length == 0) return false;
	
	//fillOutFirstAdwords(adwords_created[0]);
	
	$.each(adwords_created, function(index, val){
		
		if(index == 0){ fillOutFirstAdwords(val); } 
		else{ fillOutAdwords(index + 1, val); }
	});
	
	adjustHeadlineWidths();
}



function fillOutFirstAdwords(data){
	
	//console.log(data);
	
	var ad_wrap = $(".ad-ad[data-num='1']");
	
	$.each(data, function(index, val){
		
		ad_wrap.find("[name='"+index+"']").val(val);
		
		if(index == "url_prefix" && val == "https"){
			
			ad_wrap.find("select[name='url_prefix'] option[value='http']").prop("selected", false);
			ad_wrap.find("select[name='url_prefix'] option[value='https']").prop("selected", true);
		}
	});
}



function fillOutAdwords(index, data){
	
	
	
	var badge = $("div.badges > span.label[data-num='"+index+"']");
	createNewAdwordsElt(badge);
	
	//console.log(badge);
	
	var ad_wrap = $(".ad-ad[data-num='"+index+"']");
	
	$.each(data, function(index, val){
		
		ad_wrap.find("[name='"+index+"']").val(val);
		
		if(index == "url_prefix" && val == "https"){
			
			ad_wrap.find("select[name='url_prefix'] option[value='http']").prop("selected", false);
			ad_wrap.find("select[name='url_prefix'] option[value='https']").prop("selected", true);
		}
	});
}





$(function(){
	
	startDisplayUploader();                                 //always start the display uploader
	startRemarketUploader(remarket_button);                 //always start the remarket uploader by default
	toggleRemarketAdd();                                    //when the user toggles the adding of remarketing
	
	dynamicHeadlineWidthAdjust();                           //adwords headline width adjustments
	limitedInputHandler();                                  //length-limited inputs for adwords
	
	badgeClickHandler();                                    //creating new adwords ad
	adDeleteHandler();                                      //deleting an adwords ad
	
	$("#vendorModal").on("shown.bs.modal", function(){
		
		adjustHeadlineWidths();
	});
	
	fillOutCreatedAdwords();
	
	//console.log(remarket_button);
	//console.log(remarket_uploader.getOption("browse_button"));
	//remarket_uploader.setOption("browse_button", "remarket-add-browse");
	//console.log(remarket_uploader.getOption("browse_button"));
	
});


</script>