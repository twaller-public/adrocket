$(function(){
		
	homePage_quickBuildTypeSelect();
	
	if(vendor_num != 0) {
		
		var elt = $("div.ad-type[data-num='"+vendor_num+"']");
		elt.click();
	}

	$("#page-loading").hide();
	
	animateHeader1_show(fast_animation);
	

});




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
		
			if($(".ad-selected").length > 0) return;
			
			var dataType = $(this).attr("data-type");
			var detailWrap = $(".ad-type-wrap ." + dataType);
			
			detailWrap.show();
			
		}, 
		function(){  //mouse leave - hide the detail information
		
			if($(".ad-selected").length > 0) return;
			
			var dataType = $(this).attr("data-type");
			var detailWrap = $(".ad-type-wrap ." + dataType);
			
			//only hide the text if the ad-type is not selected
			if(!$(this).hasClass("ad-selected")) detailWrap.hide(); 
		}
	);
	
	
	$("button[name='qb-next']").on("click", function(){
		
		//alert(vendorInput.val());
		if(!vendorInput.val()) $("span.no-vendor").show();
		else{
			
			$("div.quick-build-section1").fadeOut("600", function(){ $("div.quick-build-section2").fadeIn("400"); });
		}
		
		return false;
	});
	
}