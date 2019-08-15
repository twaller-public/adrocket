<?php
	require_once "_index_ctrl.php";
	require_once "assets/_header.php";
	
	
	//require "cmsb/plugins/adrocket/Google/adwords-examples-32.0.0/examples/AdWords/v201710/BasicOperations/GetCampaigns.php";
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Above the fold -->
<div class="container-fluid home-section" style="margin-top:0;position:relative; padding:0px; background-color:white; background-image:url(data:image/jpeg;base64,<?php echo $im3data; ?>); background-size:cover; background-repeat:no-repeat; background-position:center center; min-height:100px; /*margin-top:5.5rem*/;">
	<div style="width:100%; height:100%; background-color:#333; opacity:0.4; position:absolute;"></div>
	<div class="col-md-8 col-md-offset-2 text-left intro-section" style="padding:2rem 0px; color:#555;">
		<h2 style="display:inline-block; padding:20px 90px; background-color:rgba(255,255,255,0.7); border-radius:3px;">
			<strong>Welcome to <img src="/img/adrtext.png" width="auto" /></strong><br /><br />
			<strong>The Easy-to-Use, Low-Cost, Smart Choice For Digital Marketing.</strong>
		</h2>
	</div>
</div>


<div class="container-fluid" style="border-top:8px solid #333; background-color:#fff;">
	<?php 
		
		//require "assets/_qb_sec_1.php"; 
		require "assets/_qb_sec_2.php"; 

	?>
</div>

<div style="background-color: #333; width: 100%; padding-top:2rem; padding-bottom:2rem;">
		<h2 style="text-align: center;color:white;">
			<strong>All sorts of businesses love using AdRocket.</strong>
		</h2>
    <h3 style="text-align: center;color:white;">
      The Easy to Use, Low Cost, Smart Choice for Digital Marketing
    </h3>
  <div class="container" style="padding-left: 15%; padding-right: 15%;">
    <div class="row text-center text-lg-left">
      <?php foreach($featuredIndustryRecords as $featuredIndustry): ?>
        <div class="col-lg-4 col-md-6 col-xs-12" style="margin:1rem 0;">
          <a href="<?php echo $featuredIndustry['link']; ?>" class="d-block mb-4 h-100">
            <img style="width: 100%" class="img-fluid" src="<?php echo @$featuredIndustry['image'][0]['urlPath']; ?>" alt="">
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>




<!-- Landing Page Sections -->



<!-- Section 1 -->
<div class="container-fluid" style="border-top:8px solid #333;">

	<div class="row" style="background-color:red; color:#FFF; padding:35px 0px;">
		<div class="col-md-2"></div>
		
		<div class="col-md-8">
			<?php echo $home["content_banner"]; ?>
		</div>
		
		<div class="col-md-2"></div>
	</div>
</div>



<?php require_once "assets/_footer.php"; ?>

<script>
$(function(){
	
	
	$("#province option[value='0']").text("-- Province --");
	
	
	$(".launch-button").hover(
		function(){
			$(this).css("background-color", "rgba(0,0,0,0.5)");
			$(this).find("span").css("color", "white");
		},
		function(){
			$(this).css("background-color", "rgba(255, 255, 255, 0.5)");
			$(this).find("span").css("color", "red");
		}
	);
	
	
	
	//ajax for city list names from province
	$("#province").on("change", function(){
		
		var provNum = $(this).val();
		
		if(provNum == "0"){
			
			$("#city").empty();
			$("#city").append(parseHTML("<option value='0'>-- City --</option>"));
		}
		else{
			
			getCityNamesFromProvince(provNum);
			
		}
		
		
	});
	
	
	
	//add the oher/not listed option to the industry select
	homePage_industryOtherOpt();
	
	//start the budget slider
	homePage_startBudgetSlider();
	
	
	//handler to access the adwords quick build tool
	homePage_quickBuildAccessHandler();
	
	
	//ui interaction and display handler for picking the ad type in the quick build tool
	//homePage_quickBuildTypeSelect();
	
	//$("div.quick-build-section2").show();
	
	
	//hide the loading gif
	$("#qb-loading").hide();
	
	
	//when user changes the inputs that are used to calculate the leads
	homePage_requestLeadsHandler();
	
	
	//user submits the final state of the quick build form.
	//homePage_submitQuickBuild();

});



function getCityNamesFromProvince(provNum){
	
	var result = $.ajax({
		
		url: "index.php",
		dataType: "json",
		data: {
			cityFetch: 1,
			province: provNum
		},
		method: "POST"
	});
	
	
	result.done(function(data){ 
	
		//console.log(data); 
		var html = data.html;
		html     = html.replace("-- Please Select --", "-- City --");
		
		$("select#city").empty();
		$("select#city").append($.parseHTML(html));
		
		
	});
	result.fail(function(data){ console.log(data); });
}



function homePage_submitQuickBuild(){
	
	$("button[name='qb-submit']").on("click", function(){
		
		
		var prov   = $("#province").val();
		var ind    = $("#industry").val();
		var bud    = $("#budget").val();
		var vendor = $("#vendor-num").val();
		
		
		alert("submission:\n province " + prov + " - industry " + ind + " - budget " + bud + " - vendor " + vendor);
		
		/*
		var result = $.ajax({
			url: "/campaign/create.php",
			dataType: "json",
			data: {
				campaign_update: 1,
				generalModal: 1,
				province: prov,
				industry: ind,
				budget: bud,
				vendor: vendor
			},
			method: "POST"
		});
		
		result.done(function(data){
			console.log(data);
		});
		result.fail(function(data){
			console.log(data);
		});
		*/
	});
}



function homePage_requestLeadsHandler(){
	
	$("#industry, #province, #budget, #city").on("change", function(){
		
		var prov = $("#province").val();
		var ind  = $("#industry").val();
		var bud  = $("#budget").val();
		var city = $("#city").val();
		
		homePage_requestLeads(prov, ind, bud, city);
	});
}



function homePage_requestLeads(prov, ind, bud, city){
	
	var result = $.ajax({
		url: "?",
		dataType: "json",
		data: {
			get_leads: 1,
			province: prov,
			industry: ind,
			budget: bud,
			locations: [city]
		},
		method: "POST"
	});
	
	result.done(function(data){
		$("span#expected-clicks").text(data.result);
	});
	result.fail(function(data){
		console.log(data);
	});
}




//handles ui interactions with the user and the ad-type selection
function homePage_quickBuildTypeSelect(){
	
	var atClass  = "ad-type";
	var atElts   = $("." + atClass);
	
	var detClass = "explanation";
	var detElts  = $(".ad-type-wrap ." + detClass);
	
	var adSel    = "ad-selected";
	
	var vendorInput = $("input[name='vendor-num']");
	
	

	//the user is selecting this ad-type
	atElts.on("click", function(){
		
		var adText     = $(this).find("p").find("strong").text();   //the ad-type text
		var dataType   = $(this).attr("data-type");                 //type of search             
		var detailWrap = $(".ad-type-wrap ." + dataType);           //the wrap that shows the details
		
		atElts.removeClass(adSel);                             //remove selection from all elts
		$(this).addClass(adSel);                               //add the selection class
		$("span.ad-selection").text(adText);                   //see the selection text
		
		
		$(".ad-type-wrap .explanation").hide();               //hide all text details for as types
		detailWrap.show();                                    //show this ad-type text detail
		

		vendorInput.val($(this).attr("data-num"));   //update the input (hidden)
		
	});
	
	
	//mouseover events for ad-type icons
	atElts.hover(
		function(){  //mouse enters - show the detail information
			
			var dataType = $(this).attr("data-type");
			var detailWrap = $(".ad-type-wrap ." + dataType);
			
			detailWrap.show();
			
		}, 
		function(){  //mouse leave - hide the detail information
			
			var dataType = $(this).attr("data-type");
			var detailWrap = $(".ad-type-wrap ." + dataType);
			
			//only hide the text if the ad-type is not selected
			if(!$(this).hasClass("ad-selected")) detailWrap.hide(); 
		}
	);
	
	/*
	$("button[name='qb-next']").on("click", function(){
		
		//alert(vendorInput.val());
		if(!vendorInput.val()) $("span.no-vendor").show();
		else{
			
			$("div.quick-build-section1").fadeOut("600", function(){ $("div.quick-build-section2").fadeIn("400"); });
		}
		
		return false;
	});
	*/
	
}




function homePage_quickBuildAccessHandler(){
	
	//handler to access the adwords quick build tool
	$(".intro-toggle").on("click", function(){
		

		$(".intro-section").fadeOut("600", function(){ $("div.quick-build-section1").fadeIn("400"); });
	});
	
	return false;
}




function homePage_industryOtherOpt(){
	
	var ind    = $("#industry");
	var locate = "option[value='0']";
	var html   = "<option value='Other'>-- Other/Not Listed--</option>";
	
	var optZero = ind.find(locate);
	optZero.after($.parseHTML(html));
	
	return false;
}




//start the budget slider
function homePage_startBudgetSlider(){
	
	//the init for the budget slider
	$(".budget-slider").slider({
		range:false,
		min:250,
		max:10000,
		step:10,
		create: null,
		change: null,
		slide: function( event, ui ) {
			
			$("input[name='budget']").attr("value", ui.value);
			$("input[name='budget']").change(); 
		}
	});

	$(".budget-slider").slider("value", $("input[name='budget']").val()); 


	$(".budget-slider").slider("option", "change", function(event, ui){
			
		$("input[name='budget']").attr("value", ui.value);
		$("input[name='budget']").change(); 
	});

	$("input[name='budget']").on("keyup", function(){ 
		$(".budget-slider").slider("value", $(this).val()); 
	});
	
	return false;
}
</script>


</body>
</html>
