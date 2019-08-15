/**
Functions found in wizard files that are probably common to all steps
*/



$(function(){
	

	
	setActiveBreadCrumb();
});



function setActiveBreadCrumb(){
	
	//.ec-process .breadcrumb li.active a
	
	var l = window.location.href;
	var s = ".ec-process .breadcrumb li a[href^='"+l+"']";
	
	s = s.replace("https://www.adrocket.ca", "");
	
	console.log(s);
	
	$(s).parent().addClass("active");
}







//return the total height of all the direct children in the parent element
//pass the element ID
function heightOfChildren(parentID){
		
	var totalHeight = 0;
	
	$("#" + parentID).children().each(function(){
		totalHeight = totalHeight + $(this).outerHeight(true);
	});
	
	return totalHeight;
}



//return the height of all child elements in the parent element
//pass the element ID
function heightOfAllChildren(parentID){
	
	var totalHeight = 0;
	
	$("#" + parentID).find().each(function(){
		totalHeight = totalHeight + $(this).outerHeight(true);
	});
	
	//alert(totalHeight);
	
	return totalHeight;
}





//animate the display of the main input for the wizard page
function animateInput1_show(fast = false){
	
	var input_wrap  = $("#ec-process-input-1");
	var totalHeight = heightOfChildren("ec-process-input-1");
	var speed       = fast? 0 : 1200;

	input_wrap.animate(
		{
			/*height: totalHeight, */
			opacity: "100"
		},
		{
			duration: speed, 
			queue:false,
			always: function(){ input_wrap.css("height", "auto") },
		}
	);
}





//animate the display of the main header for the wizard page
function animateHeader1_show(fast = false){
	
	var header_elt = $("#ec-process-header-1");
	var speed      = fast? 0 : 1200;
	
	header_elt.animate(
		{
			height: header_elt.find("h1, h2").outerHeight(), 
			opacity: "100"
		},
		{
			duration: speed,
			always: function(){
				
				animateInput1_show(fast);
				header_elt.css("height", "auto");
			},
		}
	);
}






//found on who.php (vendor select) but was unused
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