<?php
	require_once "_create_ctrl.php";
	require_once "../assets/_header.php";
?>

<!-- main page content -->
<div class="container-fluid" style="padding-top:4rem;">

	<?php ?>
	<!-- <div class="col-sm-1 main-content"></div> -->
	
	<div class="col-md-12 col-lg-4 left-sidebar" style="position:sticky; top:0;"><?php include "../assets/_checkout_window.php"; //include the checkout window ?></div>
	
	
	
	<div class="col-md-12 col-lg-7 main-content" style="background-color:#FFF;">
	
		<div style="color: #C00; font-weight: bold; font-size: 13px;">
			<?php echo $alerts; ?>
		</div>
		
		<?php include "../assets/_campaign_summary.php"; //include the campaign summary ?>
		
		
	</div>
	
	<div class="col-sm-2 right-sidebar"></div>
</div>

<div id="page-loading" class="loading text-center" style="/*display:none; */position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(0,0,0,0.6); padding-top:150px;">
	<img src="/img/spinner.gif" alt="" style="width:200px; height:200px;"/>
</div>

<?php 
	
	//footer
	require_once "../assets/_footer.php"; 
	

?>
<!-- Save Campaign create section modal handler -->
<script>

var adwords_created = [<?php echo $adwords_json; ?>];

var recur_label  = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recurring Modal Label"] ; ?>';
var notset_label = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"] ; ?>';

var m_names = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

var d_names = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];


var freshCpn = <?php echo $fresh_campaign? 1 : 0; ?>;

//var adwords_previews = [<?php echo $adwords_json; ?>];




function dateToText(date){
	
	if(!date || date == "00/00/0000") return notset_label;
	date = date.split("/");
	date = date[1] + "/" + date[0] + "/" + date[2];
	
	var d = new Date(date);
	var m = m_names[d.getMonth()];
	var w = d_names[d.getDay()];
	
	var result = w + ", " + m + " " + d.getDate() + " " + d.getFullYear()
	//console.log(result);
	return result;
}



/**
* Send the ajax request with the modal form data
*/
function sendCampaignModalFormSubmit(request){
	
	var send_data = { campaign_update: 1 }
	
	return $.ajax({
		url: "/campaign/create.php?" + request,
		data: send_data,
		dataType: "json",
		method: "POST",
	});
}



function updateAdwordsModalFormSubmit(){

	var subs = serializeAdwords();
	$(".ad-ad").remove();
	console.log(subs);
	
	var send_data = { 
		adwords_update: 1,
		cpn:            $("input[name='num']:hidden").val(),
		adwords:        subs
	}
	
	return $.ajax({
		url:      "/campaign/create.php?",
		data:     send_data,
		dataType: "json",
		method:   "POST",
	});
}




/**
* Update the summary page information based on the given data
*/
function updateSummaryPage(data){
	
	$("span.start-here").hide();
	var section = $("body > div.container-fluid");
	
	$.each(data, function(index, elt){

		var kw_flag = (index == "keywords");
		var dt_flag = (index == "daytime");
		
		$.each(elt, function(index, elt){ 
			index = index.split(":")[0];
			var content = elt;
			
			
			if(index == "budget"){
				
				if(data.general.budget != "") content = "$" + elt;
				else                              content = notset_label;
				
			}
			else if(index == "locations"){
				
				if(data.locations.locations.length == 0) content = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Chosen Locations Text"]; ?>';
				else                                     content = data.locations.locations.join(", ");
				
			}
			else if(kw_flag){  //keywords
				
				if(!elt || elt == ""){ content = notset_label + '<hr />'; }
				else{ content = (elt.split(",").join(",<br />")) + "<hr />"; }
				
				//console.log(index + " : " + content);
				
				section.find("span." + index + "-value").empty(); 
				section.find("span." + index + "-value").append($.parseHTML(content)); 
			}
			else if(dt_flag){  //days and times
				//console.log(index + " : " + content);
				if($.inArray(index, ["day_parting", "expiry_notification", "weekends", "recur"]) !== -1){ content = (parseInt(elt) == 1)? "Yes" : "No"; }
				else if($.inArray(index, ["start_date", "end_date"]) !== -1){ content = dateToText(elt); }
			}
			
			
			if(!kw_flag){ 
				if(!content) content = notset_label;
				console.log(index + " : " + content);
				section.find("span." + index + "-value").text(content); 
			}
		});
		kw_flag = false;
		dt_flag = false;
	})
}




function updateLocationsModalInfo(data){
	
	//alert("updating locations modal");
	var modal           = $("#locationsModal");
	
	var preset_wrap     = modal.find("div#preset-select-wrap");
	var presets         = preset_wrap.find("select");
	var preset_opts     = presets.find("option");
	
	var checkbox_wrap   = modal.find("div#locations-select-wrap");
	var box_spans_wraps = checkbox_wrap.find("span.loc_check_wrap");
	
	checkbox_wrap.hide();
	box_spans_wraps.remove();
	checkbox_wrap.append(data.options.locations.locationOptionBoxes);
	checkbox_wrap.show();
	
	if(data.options.locations.presetsList.length > 0){
		
		preset_wrap.hide();
		preset_opts.remove();
		presets.append(data.options.locations.presetsOptionHTML);
		preset_wrap.show();
	} 
	else{
		preset_wrap.hide();
	}
	
	$("body > div.container-fluid").find("span.locations-value").empty(); 
	$("body > div.container-fluid").find("span.locations-value").append($.parseHTML("<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Chosen Locations Text"]; ?><hr />"));
	
	$("div.locations-info").find("h3 > button.btn").prop("disabled", false);
	$("div.locations-info").find("div.alert-warning").toggle(false);
	return false;
}




//the industry changed so we need to reset all the keyword data
function updateKeywordsModalInfo(data){ 

	//clear the values of the hidden inputs, set the default keywords inputs
	//value to the one passed, and create the tables
	var defaults = data.options.keywords.join(",");
	var def_str  = data.options.keywords.join(",<br />") + "<hr />";
	
	$("#keywordsModal .campaign-create-modal-form div.row input[type='hidden']").val("");
	$("input[name='default_keywords']:hidden").val(defaults);
	
	setKeywordTables();
	
	//content = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?><hr />';
	
	$("body > div.container-fluid").find("span.keywords-value").empty(); 
	$("body > div.container-fluid").find("span.keywords-value").append($.parseHTML("<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Keywords Chosen text"]; ?><hr />"));
	
	$("body > div.container-fluid").find("span.negative_keywords-value").empty(); 
	$("body > div.container-fluid").find("span.negative_keywords-value").append($.parseHTML("<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Negative Keywords Chosen text"]; ?><hr />"));
	
	$("body > div.container-fluid").find("span.default_keywords-value").empty(); 
	$("body > div.container-fluid").find("span.default_keywords-value").append($.parseHTML(def_str));
	
	$("body > div.container-fluid").find("span.unused_defaults-value").empty(); 
	$("body > div.container-fluid").find("span.unused_defaults-value").append($.parseHTML("<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Unused Defaults Text"]; ?><hr />"));
	
	$("div.keywords-info").find("h3 > button.btn").prop("disabled", false);
	$("div.keywords-info").find("div.alert-warning").toggle(false);
	
	return false; 
}




function updateVendorModalInfo(data){
	
	$("div.all-ads-wrap > div.previews").toggle(false);
	$("div.modal-ads-wrap > div.previews").toggle(false);
	
	if(data.selections.general["vendor:label"]  == "Google Adwords"){
		
		$("div.all-ads-wrap > div.adwords-previews").toggle(true);
		$("div.modal-ads-wrap > div.adwords-wrap").toggle(true);
		if(remarket_uploader) remarket_uploader.setOption("browse_button", "remarket-adw-browse");
	}             
	else if(data.selections.general["vendor:label"] == "Google Display Network"){
		
		$("div.all-ads-wrap > div.display-previews").toggle(true);
		$("div.modal-ads-wrap > div.display-wrap").toggle(true);
		if(remarket_uploader) remarket_uploader.setOption("browse_button", "remarket-add-browse");
	} 
	else if(data.selections.general["vendor:label"] == "Google Remarketing" || (data.selections.ads && data.selections.ads["remarketing"])){
		
		$("div.all-ads-wrap > div.remarketing-previews").toggle(true);
		$("div.modal-ads-wrap > div.remarketing-wrap").toggle(true);
		if(remarket_uploader) remarket_uploader.setOption("browse_button", "remarket-browse");
	}    

	$("div.ad-prviews").find("h3 > button.btn").prop("disabled", false);
	$("div.ad-prviews").find("div.alert-warning").toggle(false);
}



function fillOutAdwordsPreviews(){
	
	console.log("PREVIEWS:");
	console.log(adwords_created);
	
	var preview_wrap = $("div.adwords-previews-wrap");
	var preview_temp = $("div.adwords-preview-template");
	
	$.each(adwords_created, function(index, elt){
		
		var preview = preview_temp.clone();
		
		preview.attr("data-num", index + 1);
		
		$.each(elt, function(index, val){
			
			var e = preview.find("[data-val='" + index + "']");
			
			if(index == "dest_url") return true;
			
			
			if(index == "phonenum"){
				
				if(elt.use_phone == "0") return true;
				preview.find("span.phone-ext").show();
			}
			
			if(index == "callout_1"){
				if(elt.use_callouts == "0") return true;
				preview.find("div.callouts-ext").show();
			}
			
			
			if(index == "sitelink_1"){
				if(elt.use_sitelinks == "0") return true;
				preview.find("div.sitelinks-ext").show();
				
				if(elt.use_sitelinkdesc == "1") preview.find("div.sitelink-desc-ext").show();
			}
			
			
			
			
			if(e.hasClass("extension")){ 
				var ext_spl  = index.split("_");
				var ext_link = ext_spl[0] + "_" + ext_spl[2];
				
				e.attr("title", "extension link: /" + elt[ext_link]);
			}
			else if(index == "disp_url"){
				
				e.attr("title", elt.url_prefix + "://" + elt.dest_url);
				val = elt.dest_url;
			} 
			
			if(!val || val == "") e.hide();
			
			e.text(val);
		});
		
		preview.removeClass("adwords-preview-template");
		preview.addClass("adwords-preview");
		preview_wrap.append(preview);
		if(index == 0){
			
			$(".adwords-previews .preview-badges span[data-num='1']").removeClass("label-primary");
			$(".adwords-previews .preview-badges span[data-num='1']").addClass("label-success");
			$(".adwords-previews .preview-badges span[data-num='1']").addClass("current");
			preview.show();
		} 
	});
}




function adwordsPreviewNavs(){
	
	$("body").on("click", ".adwords-previews .preview-badges span", function(elt){
		
		//alert();
		if($(this).hasClass("label-success")) return false;
		
		var current = $(".adwords-previews .preview-badges span.current");
		var num     = $(this).attr("data-num");
		
		$("div.adwords-previews-wrap .adwords-preview").hide("slow");
		
		current.removeClass("label-success");
		current.removeClass("current");
		current.addClass("label-primary");
		
		$(this).removeClass("label-primary");
		$(this).addClass("label-success");
		$(this).addClass("current");
		
		$("div.adwords-previews-wrap .adwords-preview[data-num='"+num+"']").show("slow");
	});
}



sidebarChange = function(){
	
	var width = $(window).width();
		
	if(width < 1200){
		$(".left-sidebar").attr("style", "position: relative; z-index:999;");
	}
	else{
		$(".left-sidebar").attr("style", "position:sticky; top:0;");
	}
}


$(function(){
	
	sidebarChange();
	$(window).resize(function(){ sidebarChange(); });
	
	
	if(freshCpn){
		
		$('#startHere').tooltip(
			{
				'trigger': 'manual',
				'title': 'Start Here!'
			}
		);
		
		$('#startHere').tooltip('show');
	}
	
	$("#page-loading").hide();
	fillOutAdwordsPreviews();
	adwordsPreviewNavs();
	
	
	
	/**
	* When the user clicks 'save' on the campaign create modal
	*/
	$("div.campaign-create-modal button.save-modal").on("click", function(){
		
		$("#page-loading").show();
		
		
		
		
		var cpnNum = $("input[name='num']:hidden").val();
		$(".no_sub").prop("disabled", true);

		var modal = $(this).parents("div.modal");   //the modal wrapper
		var form  = $(this).parents("div.modal-content").find("form.campaign-create-modal-form");   //the form inside the modal
		
		modal.modal("hide");
		
		
		//skip the form submission if location modal - this is done via ajax
		if(modal.attr("id") == "locationsModal"){
			
			//var locsstr = $("span#selected_location").text();
			//$("span.locations-value").text(locsstr)
			
			var custom_location_text = $("textarea[name='custom_location_text']").val();
			
			
			//send the custom location text (is given) via ajax
			var send = $.ajax({
				url:      "?",
				data:     {custom_location: 1, text: custom_location_text, cpn: cpnNum},
				dataType: "json",
				method:   "POST",
			});
			
			
			location.href = "/campaign/create.php?num=" + cpnNum;
			return false;
			
		}
		
		
		//submitting the vendor modal with adwords selected
		if(vNum == 1 && modal.attr("id") == "vendorModal"){
			
			
			//submit the adwords data to the server,
			var ads = updateAdwordsModalFormSubmit();
			//console.log(ads);
			ads.done(function(data){
				
				console.log("ADWORDS SUBMISSION RESULT");
				console.log(data);
				
				//then submit the rest of the form data to the server (to submit any remarketing added to the adwords).
				var inputs = form.serialize();              //the form data
				var submit = sendCampaignModalFormSubmit(inputs);   //send the form data to the server
				
				submit.done(function(data){
					
					console.log("fORM SUBMISSION RESULT - SUCCESS");
					console.log(data);
					//location.reload();
					location.href = "/campaign/create.php?num=" + cpnNum;
				});
				submit.fail(function(data){
					
					console.log("fORM SUBMISSION RESULT - FAILED");
					console.log(data);
					//location.reload();
					location.href = "/campaign/create.php?num=" + cpnNum;
				});
			});
			ads.fail(function(data){ console.log(data); });
		}
		else{
			//just submitting the form data - no adwords were submitted
			var inputs = form.serialize();              //the form data
			
			console.log(inputs);
		
			var submit = sendCampaignModalFormSubmit(inputs);   //send the form data to the server
			submit.done(function(data){
				
				console.log(data);
				//location.reload();
				location.href = "/campaign/create.php?num=" + cpnNum;
			});
			submit.fail(function(data){
				
				console.log(data);
				//location.reload();
				location.href = "/campaign/create.php?num=" + cpnNum;
			});
		}
		
		modal.modal('hide');   //hide the modal
	});

	
	$("button.signup").on("click", function(){
		
		location.href = "/user/signup.php?complete_signup=1";
	});
	
	$("button.checkout").on("click", function(){
		
		location.href = "/campaign/purchase.php?campaign=<?php echo $cpnNum; ?>";
	});
});

</script>
</body>
</html>