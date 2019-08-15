<?php 
	
	//get already presetn time/date info
	$daystimes = getCampaignDaysTimes($cpnVals["num"]);
	
	$start       = @$daystimes->get("start_date");
	$end         = @$daystimes->get("end_date");
	$duration    = @$daystimes->get("duration");
	$recurring   = @$daystimes->get("recur");
	//$weekends    = @$daystimes->get("weekends");
	$notify      = @$daystimes->get("expiry_notification");
	$day_parting = @$daystimes->get("day_parting");
	
	
	$duration_summary = $recurring? $GLOBALS["ADROCKET_DEFINITIONS"]["Recur Duration Label"] : $GLOBALS["ADROCKET_DEFINITIONS"]["Set Duration Label"];
	if($duration) $duration_summary = str_replace("--", $duration, $duration_summary);
	
	
	//js information
	$js_start = null;
	$js_end   = null;
	
	if($start){
		$js_start = explode("-", explode(" ", $start)[0]);
		$js_start = "{$js_start[2]}/{$js_start[1]}/{$js_start[0]}";
	}
	
	if($end){
		$js_end = explode("-", explode(" ", $end)[0]);
		$js_end = "{$js_end[2]}/{$js_end[1]}/{$js_end[0]}";
	}
?>

<div class="row">

	<input type="hidden" name="duration" value="<?php echo $duration; ?>" />
	<div class="col-md-4 col-md-offset-2">
		<h3>When will your ad run?</h3>
	</div>
	
	<div class="col-md-12">&nbsp;</div>

	<div class="col-md-4 col-md-offset-2 text-center">
	
		<!-- start date -->
		<div class="err-box" style="max-width:150px; display:inline-block;">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Start Date Modal Label"]; ?><span class="red-ast">*</span></label><br />
			<input id="start-date" type="text" name="start_date" value="<?php if($js_start) echo $js_start; ?>" style="max-width:150px;" readonly />
		</div>
	</div>
	
	
	<div class="col-md-4 text-center">
	
		<!-- end date -->
		<div class="err-box" style="max-width:150px;">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["End Date Modal Label"]; ?></label><br />
			<input id="end-date" type="text" name="end_date" value="<?php //if($js_end) echo $js_end; ?>" style="max-width:150px;" readonly />
		</div>
	</div>
	
	
	<div class="col-md-2"></div>
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-6 col-md-offset-3 dates_error alert alert-danger" style="display:none;"></div>
	<div class="col-md-12">&nbsp;</div>
	
	
	<div class="col-md-12">
		<!-- start and duration summary -->
		<h3 class="text-center"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Your Campaign Heading"]; ?> <span id="dur_msg"><?php echo $duration_summary; ?></span>.</h3>
	</div>
	
	
	<div class="col-md-8 col-md-offset-3">
		
		<!-- recurring -->
		<div class="form-group">
			<input 
				type="checkbox"
				name="recur"
				value="1"
				id="recur"   
				<?php if($recurring) echo " checked"; ?>
			/>
			&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recurring Modal Label"]; ?></label>
		</div>
		
		
		<!-- weekends NOT USED
		<div class="form-group">
			<input
				type="checkbox"
				name="weekends"
				value="1"
				id="weekends" 
				<?php //if($weekends) echo " checked"; ?>
			/>
			&nbsp;&nbsp;<label><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Weekends Modal Label"]; ?></label>
		</div>
		-->
		
		
		<!-- expiry notification -->
		<div class="pull-left expiry-wrap">
			<div class="input-group">
				<input 
					type="checkbox" 
					name="campaign_expiry_notification" 
					value="1" 
					<?php if($notify) echo " checked"; ?>
				/>
				&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Expiry Modal Label"]; ?></label>
			</div>
		</div>
	</div>
	
	
	
	
	<div class="col-md-12">&nbsp;</div>
	<div class="col-md-12">&nbsp;</div>
	
	
	
	<!-- day parting -->
	<div class="col-md-8 col-md-offset-3">
		
		<!-- day parting -->
		<div class="form-group">
			<input 
				type="checkbox"
				name="day_parting"
				value="1"
				id="day_parting"
				<?php if($day_parting) echo " checked"; ?>
			/>
			&nbsp;&nbsp;<label>Run my ad on specific days and/or times.</label>
		</div>
	</div>
	
	
	<div class="col-md-1"></div>
	<div class="col-md-12">&nbsp;</div>
	
	
	
	<!-- day parting details -->
	<div class="col-md-12 day_parting_details" style="display:none;">
		<?php require "../assets/form-elements/_day_parting_select.php"; ?>
	</div>

	
	
</div>

<script>

	//start/end date info from PHP script
	var start_date  = "<?php echo $js_start; ?>";
	var end_date    = "<?php echo $js_end; ?>";
	var recur_label = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recur Duration Label"]; ?>";
	var fixed_label = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Set Duration Label"]; ?>";
	var length_warn = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Duration Warning Length"]; ?>";
	var start_warn  = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Duration Warning Dates"]; ?>";

	//start date datepicker options
	var start_input  = "#start-date";
	var start_min    = 2;
	var start_max    = 30;
	var start_format = "dd/mm/yy";
	
	//end date datepicker options
	var end_input  = "#end-date";
	var end_min    = 7;
	var end_max    = 365;
	var end_format = "dd/mm/yy";
	
	
	//day parting information
	var dp_details_wrap = ".day_parting_details";

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
		
		//console.log(s);
		//console.log(e);
		
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
		if(result < 8 && result > 0) result = 1;    //if result is too short, we return 1
		else if(result < 0)          result = -1;   //if end is before start we return -1
		
		return result;
	}
	
	
	
	function doDateDifferenceOnDateChange(){
		
		$("body").on("change", start_input + ", " + end_input, function(){
			
			var start    = $(start_input).val();
			var end      = $(end_input).val();
			var weekends = $("input[name='weekends']:checkbox").prop("checked");
			
			var diff = dateDifference(start, end, weekends);
			
			
			//console.log("diff: " + diff);
			//console.log("wk: " + weekends);
			$("input[name='duration']:hidden").val(diff);
			$("input[name='duration']:hidden").change();
		});
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
				$(err_wrap).text(msg);
				$(err_wrap).toggle(true);
			}
			else{
				$(err_wrap).toggle(false);
			}
			
			
		});
	}
	
	
	$(function(){
		
		//console.log(start_date);
		//console.log(end_date);
		
		//date pickers
		startDate_datePicker(start_input);
		endDate_datePicker(end_input);
		
		//when recurring check box is toggled
		disableEndDateIfRecurring("input[name='recur']:checkbox");
		
		//when day pating check box is toggled
		showDayPartingTableIfSelected("input[name='day_parting']:checkbox");
		
		//when date selections are made/changed
		doDateDifferenceOnDateChange();
		doDurationLabelUpdate();
		doDateDifferenceOnWeekendsChange();
		
		
		//DAY PARTING
		//when the user chooses a preset option
		$("body").on("change", dp_opts, function(){ handlePresetChange($(this).val()); });
		
		//when the user changes the value of a select (start/finish)
		$("body").on("change", st_ed_selects, function(){ disableNonValidTimes($(this)); });
		
		setDPTableSelections();
	});

</script>