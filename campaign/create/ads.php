<?php
/*

	The viewer page for the ad creation in the Campaign Creation Wizard Process
	
*/
	
	require_once "_ads_ctrl.php";
	require_once "../../assets/_header.php";
	
	$vendor_headerText = "Create Your Ads";
	$continue_onClick  = "return false;";
?>


<!-- main page content -->
<div class="jumbotron ec-process" style="background-color:white; margin-top: 0px;">

	
	<?php require "../../assets/_create_breadcrumbs.php"; ?>


	<div class="container text-center" id="main_content">
	
	
		<?php if(@$errors) : ?>
			<div style="color: #C00; font-weight: bold; font-size: 16px;">
				<p>Placeholder Error</p>
			</div>
		<?php endif; ?>
	
	

		<?php require_once $ad_interface; ?>
		

	</div>

</div>

<div id="page-loading" class="loading text-center" style="position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(0,0,0,0.6); padding-top:150px;">
	<img src="/img/spinner.gif" alt="" style="width:200px; height:200px;"/>
</div>

<?php 
	
	//footer
	require_once "../../assets/_footer.php"; 
	
?>


<script src="/js/plupload.full.min.js"></script>
<script src="/js/wizard_common_temp.js"></script>
<script>

	
	
	var vNum    = 0;
	var cpnNum  = 0;
	var userNum = 0;

	var display_uploader  = null;
	var remarket_uploader = null;
	var remarket_button   = (vNum == 1)? "remarket-adw-browse" : (vNum == 2)? "remarket-add-browse" : "remarket-browse";

	/*
	var ad_wrap       = "#ad-wrap";
	var limited       = ".limited";
	var limit_display = $("span.limit");

	var adwords_created = ""; //[<?php //echo $adwords_json; ?>];
	*/


	

	$(function(){
		
		vNum    = <?php echo $vNum   ?: 0; ?>;
		cpnNum  = <?php echo $cpnNum ?: 0; ?>;
		userNum = <?php echo $userNum?: 0; ?>;
	
		//do these things while the spinner goes
		
		//startDisplayUploader();                                 //always start the display uploader
		//startRemarketUploader(remarket_button);                 //always start the remarket uploader by default
		//toggleRemarketAdd();                                    //when the user toggles the adding of remarketing
		
		/*
		dynamicHeadlineWidthAdjust();                           //adwords headline width adjustments
		limitedInputHandler();                                  //length-limited inputs for adwords
		
		badgeClickHandler();                                    //creating new adwords ad
		adDeleteHandler();                                      //deleting an adwords ad
		
		
		//modal only usage
		$("#vendorModal").on("shown.bs.modal", function(){
			
			adjustHeadlineWidths();
		});
		
		fillOutCreatedAdwords();
		*/
		
		init_ads();
		
		
		//when we are ready to show the page
		$("#page-loading").hide();
		
		animateHeader1_show();
		

		
		
	});
	
	
	
	
	function init_ads(){
		
		startRemarketUploader(remarket_button);                 //always start the remarket uploader by default
		
		if(vNum == 1) init_googleSearch();
		if(vNum == 2) init_googleDisplay();
		if(vNum == 7) init_googleRemarket();
		
		return false;
	}
	
	
	function init_googleDisplay(){
		
		startDisplayUploader();                                 //always start the display uploader
		toggleRemarketAdd();                                    //when the user toggles the adding of remarketing
		
		return false;
	}



	
	
	
	
	/*
	function heightOfChildren(parentID){
		
		var totalHeight = 0;
		
		$("#" + parentID).children().each(function(){
			totalHeight = totalHeight + $(this).outerHeight(true);
		});
		
		return totalHeight;
	}



	function heightOfAllChildren(parentID){
		
		var totalHeight = 0;
		
		$("#" + parentID).find().each(function(){
			totalHeight = totalHeight + $(this).outerHeight(true);
		});
		
		alert(totalHeight);
		
		return totalHeight;
	}




	function animateInput1_show(){
		
		var input_wrap = $("#ec-process-input-1");
		
		var totalHeight = heightOfChildren("ec-process-input-1");

		input_wrap.animate(
			{
				
				opacity: "100"
			},
			{
				duration: 1200, 
				queue:false,
				always: function(){ input_wrap.css("height", "auto") },
			}
		);
	}






	function animateHeader1_show(){
		
		var header_elt = $("#ec-process-header-1");
		
		header_elt.animate(
			{
				height: header_elt.find("h1").outerHeight(), 
				opacity: "100"
			},
			{
				duration: 1200,
				always: function(){
					
					animateInput1_show();
					header_elt.css("height", "auto");
				},
			}
		);
	}

	


	function showOtherInput_handler(){
		
		$("#industry").on("selectmenuchange", function(event, ui){ 
			
			var tag        = $("#industry").find("option[value='Other']");
			var input_wrap = $("div.other-input");
			var input_elt  = $("input[name='other-detail']");
			var input_p    = input_wrap.find("p");
			
			if(tag.is(":selected")){
				
				input_elt.prop("disabled", false);
				input_elt.css("visibility", "visible");
				input_p.css("visibility", "visible");
				
				var newHeight = heightOfChildren("other-input");
				
				input_wrap.animate(
					{
						height: newHeight, //input_wrap.find("input").outerHeight(), 
						opacity: "100"
					},
					{
						duration: 800,
					}
				);
			}
			else{
				
				if(!input_elt.prop("disabled")){
					
					input_elt.prop("disabled", true);
					input_elt.css("visibility", "hidden");
					input_p.css("visibility", "hidden");
					
					input_wrap.animate(
						{
							height: 0, 
							opacity: "0"
						},
						{
							duration: 800,
						}
					);
				}
			}
		});
	}
	
	
	
	
	*/
	
	
	
	
	
	
	
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



	
	
</script>


</body>
</html>