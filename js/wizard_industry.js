/*
	Javascript for the industry selection wizard interface
*/





$(function(){
		
		
	/*
	Add the option for other/not listed to the selection
	*/
	industry_addOtherToSelection();
	
	
	
	/*
	Initialize the select menu extension
	*/
	industry_initSelectExtension();

	
	
	
	/*
	Hanlder for when the user changes the industry selection
	*/
	industry_selectionMenu_changeHandler();
	
	
	//done loading
	$("#page-loading").hide();
	
	
	/*
	Show the main header and input elements
	*/
	animateHeader1_show(fast_animation);
	
	
	/*
	If the user has selected 'other', show the user industry submission input
	*/
	if(show_user_submission){ industry_toggleSubmissionInput(true, fast_animation) } 
	
	
});







//handles changes to the industry selection menu
function industry_selectionMenu_changeHandler(){
	
	$("#industry").on("selectmenuchange", function(event, ui){ 
		
		var tag        = $("#industry").find("option[value='Other']");     //the option elt for 'other'
		
		industry_toggleSubmissionInput(tag.is(":selected"));
	});
}



//show or hide the user submitted industry input section
function industry_toggleSubmissionInput(show = false, fast = false){

  show = true;
	
	var input_wrap = $("div.other-input");                             //the wrapper for user submitted industry input
	var input_elt  = $("input[name='other-detail']");                  //input elt for user submitted industry
	var input_p    = input_wrap.find("p");                             //p elt in the user submitted industry wrapper
	var visibility = show? "visible" : "hidden";
	

	input_elt.prop("disabled", !show);
	input_elt.css("visibility", visibility);
	input_p.css("visibility", visibility);
	
	industry_animateSubmissionInput(input_wrap, show);
}



//trigger the animation of the user submitted industry section
function industry_animateSubmissionInput(elt, show = false, fast = false){
	
	var newHeight  = show? heightOfChildren("other-input") : 0;
	var newOpacity = show? "100" : "0";
	var speed      = fast? 200 : 800;
	
	elt.animate(
		{
			height: newHeight,
			opacity: newOpacity
		},
		{
			duration: speed,
		}
	);
	
}




//initialize the select menu extension to replace the standard select element
function industry_initSelectExtension(){
	
	//initialize the select menu extension
	$("#industry")
		.selectmenu()
		.selectmenu("menuWidget")
			.addClass("ec-process-selectmenu");
			
	return false;
}



function industry_addOtherToSelection(){
	
	var selected = (industry_num == "Other")? " selected" : "";
	
	$("#industry").find("option[value='0']").after($.parseHTML("<option value='Other'"+selected+">-- Other/Not Listed--</option>"));
}