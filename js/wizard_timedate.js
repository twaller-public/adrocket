
//start date datepicker options
var start_input   = "#start-date";
var start_min     = 2;
var start_max     = 30;
var start_format  = "dd/mm/yy";

var current_start = "";

//end date datepicker options
var end_input   = "#end-date";
var end_min     = 9;
var end_max     = 365;
var end_format  = "dd/mm/yy";

var current_end = "";


//day parting information
var dp_details_wrap = ".day_parting_details";





$(function(){
		
		
	//date pickers
	startDate_datePicker(start_input);
	endDate_datePicker(end_input);
	

	datepicker_fixCalendarPositions();
	
	
	
	//when recurring check box is toggled
	disableEndDateIfRecurring("input[name='recur']:checkbox");
	
	//when day pating check box is toggled
	showDayPartingTableIfSelected("input[name='day_parting']:checkbox");
	
	//when date selections are made/changed
	//doDateDifferenceOnDateChange();
	doDurationLabelUpdate();
	doDateDifferenceOnWeekendsChange();
	
	$("input[name='duration']:hidden").change();


	//DAY PARTING
	//when the user chooses a preset option
	$("body").on("change", dp_opts, function(){ handlePresetChange($(this).val()); });
	
	//when the user changes the value of a select (start/finish)
	$("body").on("change", st_ed_selects, function(){ disableNonValidTimes($(this)); });

	setDPTableSelections();
	
	current_start = $(start_input).val();
	current_end   = $(end_input).val();

	$("#page-loading").hide();
	
	animateHeader1_show(fast_animation);
	
	$(start_input).datepicker( "refresh" );
	$(end_input).datepicker( "refresh" );
	


});






function datepicker_fixCalendarPositions(){
	
	var start_in              = $(start_input);
	var end_in                = $(end_input);
	var datepicker_icon_start = start_in.siblings(".ui-datepicker-trigger");
	var datepicker_icon_end   = end_in.siblings(".ui-datepicker-trigger");

	start_in.before(datepicker_icon_start);
	end_in.before(datepicker_icon_end);
}






function startDate_datePicker(id_string){
	
	
	
	$(id_string).datepicker({
		maxDate:         start_max,
		minDate:         start_min,
		changeMonth:     true,
		changeYear:      true,
		dateFormat:      start_format,
		beforeShowDay:   $.datepicker.noWeekends,
		showOn:          "button",
		buttonImage:     "/img/calendar-icon.png",
		buttonImageOnly: true,
		buttonText:      "Select start date",
		onSelect:        function(t, i){
			
			doDateDifferenceOnDateChangeNoHandler();
			
		}
		/*defaultDate:     "<?php echo ($js_start)?: "0"; ?>"*/
	});
	
	
}


function endDate_datePicker(id_string){
	
	$(id_string).datepicker({
		maxDate:       end_max,
		minDate:       end_min,
		changeMonth:   true,
		changeYear:    true,
		dateFormat:    start_format,
		beforeShowDay: $.datepicker.noWeekends,
		showOn:          "button",
		buttonImage:     "/img/calendar-icon.png",
		buttonImageOnly: true,
		buttonText:      "Select end date",
		onSelect:        function(t, i){ doDateDifferenceOnDateChangeNoHandler();; }
		/*defaultDate:     "<?php echo ($js_end)?: "+7"; ?>"*/
	});
}


function disableEndDateIfRecurring(input){
	
	var msg_wrap = "span#dur_msg";
	
	if($(input).prop("checked")){ 
	
		$(end_input).datepicker("option", "disabled", true); 
		$(end_input).siblings("img.ui-datepicker-trigger").css("cursor", "default");
		//$(msg_wrap).text(recur_label);
	}
	else{ 
		//$(end_input).datepicker("option", "disabled", false); 
		$(end_input).siblings("img.ui-datepicker-trigger").css("cursor", "pointer");
		//$(msg_wrap).text(fixed_label);
	}

	
	$("body").on("change", input, function(){
		
		if($(this).prop("checked")){
			
			$(end_input).datepicker("option", "disabled", true);
			$(end_input).val("");
			$(end_input).siblings("img.ui-datepicker-trigger").css("cursor", "default");
			$(msg_wrap).text(recur_label);
		} 
		else{ 
			$(end_input).datepicker("option", "disabled", false); 
			$(end_input).siblings("img.ui-datepicker-trigger").css("cursor", "pointer");
			$(msg_wrap).text(fixed_label);
		} 
		
		datepicker_fixCalendarPositions();

	});
}



function showDayPartingTableIfSelected(input){
	
	if($(input).prop("checked")){ $(dp_details_wrap).toggle(true); }
	else{ $(dp_details_wrap).toggle(false); }
	
	$("body").on("change", input, function(){
		
		if($(this).prop("checked")){ $(dp_details_wrap).toggle(true); } 
		else{ $(dp_details_wrap).toggle(false); }
	});
}



function dateDifference(start, end, countWeekends = false){
	
	if(!start || start == "00/00/0000" || start == "") return 0;
	if(!end   || end   == "00/00/0000" || end   == "") return 0;
	if(start == end)                                   return 1;
	
	start = start.split("/");
	start = start[1] + "/" + start[0] + "/" + start[2];
	
	end = end.split("/");
	end = end[1] + "/" + end[0] + "/" + end[2];

	var s        = new Date(start);
	var e        = new Date(end);
	var raw_diff = Math.floor((e - s) / (1000*60*60*24));
	var result   = 0;
	
	console.log(s);
	console.log(e);
	console.log(raw_diff);
	
	if(countWeekends){ result = raw_diff; }
	else{
		
		//chack for valid range
		if(raw_diff < 8 && raw_diff > 0) return 1;
		if(raw_diff < 0)                 return -1;
		
		var count = 0;
		var day   = 0;
		
		for(var x = 0; x < raw_diff; x++ ){
			
			day = s.getDay();
			s.setDate(s.getDate() + 1);
			if(day != 0 && day != 6) count++;
		}
		//count the days not including weekends
		result = count;  //temp
	}
	
	//if result is a valid number return it
	//result is 0 return that as well
	if(result < 7 && result > 0) result = 1;    //if result is too short, we return 1
	else if(result < 0)          result = -1;   //if end is before start we return -1
	
	return result;
}



function doDateDifferenceOnDateChange(){
	
	$("body").on("change", start_input + ", " + end_input, function(){
		
		var start    = $(start_input).val();
		var end      = $(end_input).val();
		var weekends = true; //$("input[name='weekends']:checkbox").prop("checked");
		var is_start = $(this).attr("id") == "start-date";
		
		var diff = dateDifference(start, end, weekends);
		
		console.log("diff: " + diff);
		if(diff < 8) $(this).val(is_start? current_start : current_end);
		
		current_start = start;
		current_end   = end;
		
		//console.log("diff: " + diff);
		//console.log("wk: " + weekends);
		$("input[name='duration']:hidden").val(diff);
		$("input[name='duration']:hidden").change();
	});
}





function doDateDifferenceOnDateChangeNoHandler(){
	

		
	var start    = $(start_input).val();
	var end      = $(end_input).val();
	var weekends = true; //$("input[name='weekends']:checkbox").prop("checked");
	var is_start = $(this).attr("id") == "start-date";
	
	var diff = dateDifference(start, end, weekends);
	
	console.log("diff: " + diff);
	if(diff < 8) $(this).val(is_start? current_start : current_end);
	
	current_start = start;
	current_end   = end;
	
	//console.log("diff: " + diff);
	//console.log("wk: " + weekends);
	$("input[name='duration']:hidden").val(diff);
	$("input[name='duration']:hidden").change();

}



function doDateDifferenceOnWeekendsChange(){
	
	$("body").on("change", "input[name='weekends']:checkbox", function(){ $(start_input).change(); });  //trigger the date update procedure
}


function doDurationLabelUpdate(){
	
	var msg_wrap = "span#dur_msg";
	var err_wrap = "div.dates_error";
	var msg      = "";
	
	$("body").on("change", "input[name='duration']:hidden", function(){
		
		//duration value will either a value greater than 7, 0, 1, or -1
		//1 and -1 indicate errors with the selected dates, so we will display an error msg
		
		var duration        = $("input[name='duration']:hidden").val();
		var fixed_label_new = "";
		
		if(!duration || duration == "0"){
			//the selection is totally invlalid (as in they have only filled in one date - no error will show)
			msg = "";
		}
		else if(duration == "1"){
			//duration is too short
			msg = length_warn;
		}
		else if(duration == "-1"){
			//end comes before start
			msg = start_warn;
		}
		else{
			//duration  is valid
			fixed_label_new = fixed_label.replace("--", duration);
			$(msg_wrap).text(fixed_label_new);
			msg = "";
		}
		
		console.log(msg);
		//if the duration as not valid, show a message
		if(msg != ""){
			//$(err_wrap).text(msg);
			//$(err_wrap).toggle(true);
			alert(msg);
			$("input[name='duration']:hidden").val("0");
		}
		else{
			//$(err_wrap).toggle(false);
		}
		
		
	});
}