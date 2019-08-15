<?php 


	//parting details
	$dp_details        = getDayPartingDetails($cpnNum);
	//showme($dp_details);
	$dp_preset         = $dp_details["dp_preset_opt"];
	$day_parting_table = getDaypartingTableHTML($dp_details);
	
	//str_replace("##NAME##", "", $time_select_template);
	
	
	
?>

<p class="text-center" style="color: #C00; font-weight: bold; font-size: 18px;"><label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Day Parting Section Title"]; ?></label></p>

<div class="col-md-6 col-md-offset-3 ">
	<input class="daypart_opt" type="radio" name="dp_preset_opt" value="day"<?php     echo ($dp_preset == "day")? " checked" : ""; ?>/><label>&nbsp;&nbsp;Run during business hours.&nbsp;&nbsp;&nbsp;&nbsp;</label><br />
	<input class="daypart_opt" type="radio" name="dp_preset_opt" value="evening"<?php echo ($dp_preset == "evening")? " checked" : ""; ?>/><label>&nbsp;&nbsp;Run during evenings.&nbsp;&nbsp;&nbsp;&nbsp;</label><br />
	<input class="daypart_opt" type="radio" name="dp_preset_opt" value="custom"<?php  echo ($dp_preset == "none" || $dp_preset == "custom")? " checked" : ""; ?>/><label>&nbsp;&nbsp;Specifiy a time range for each day.</label>
</div>

<div class="col-md-12">&nbsp;</div>

<div class="col-md-12">
	<table class="table table-striped dp_times_wrap">
		<tr>
			<th></th>
			<th>Start</th>
			<th>End</th>
		</tr>
		<?php echo $day_parting_table;?>
	</table>
</div>


<script>


var dp_opts       = "input.daypart_opt:radio";
var st_ed_selects = "table.dp_times_wrap select";
var presets       = {
	day:     <?php echo json_encode(getDayPartingPreset_day());     ?>,
	evening: <?php echo json_encode(getDayPartingPreset_evening()); ?>
}

var cpn_dp = <?php echo json_encode($dp_details); ?>;

/*
$(function(){

	//when the user chooses a preset option
	$("body").on("change", dp_opts, function(){ handlePresetChange($(this).val()); });
	
	//when the user changes the value of a select (start/finish)
	$("body").on("change", st_ed_selects, function(){ disableNonValidTimes($(this)); });
	
	setDPTableSelections();
});
*/

function setDPTableSelections(){
	
	var dets = cpn_dp.days;
	
	$.each(dets, function(index, elt){
		
		var st_col = elt.start.col;
		var st_val = elt.start.val;
		var ed_col = elt.end.col;
		var ed_val = elt.end.val;
		
		$("select[name='"+st_col+"'] option[value='"+st_val+"']").prop("selected", true);
		$("select[name='"+ed_col+"'] option[value='"+ed_val+"']").prop("selected", true);
		
		$("select[name='"+st_col+"']").change();
		$("select[name='"+ed_col+"']").change();
		handlePresetChange(cpn_dp.dp_preset_opt);
	});
}



function disableNonValidTimes(elt){
	
	var time     = elt.val();
	var name     = elt.attr("name");
	var is_start = name.indexOf("_st") !== -1;
	
	var elts = elt.parents("tr").find("select");
	
	if(time == "-1" || time == "-2"){
		//we are showing all day or not at all - disable the end input and set both input to same
		elts.find("option").prop("selected", false);
		elts.find("option[value='"+time+"']").prop("selected", true);
		elts.last().find("option").prop("disabled", true);
		elts.last().find("option").toggle(false);
	}
	else{
		//an actual time was chosen
		
		
		
		if(time == "0"){
			
			elts.find("option").prop("disabled", false);   //enable all options
			elts.last().find("option").toggle(true);       //show any hidden options
			return false;  //just show/enable all options if the "select" option is set
		} 
		
		var int_time = parseInt(time);
		
		if(is_start){
			//disable all the options for end that come before start
			
			elts.last().find("option").each(function(index, elt){
				
				var val = parseInt($(elt).val());
				
				if(val <= int_time && val > 1){
					
					$(elt).prop("disabled", true);
					$(elt).toggle(false);
				}
				else{
					$(elt).prop("disabled", false);
					$(elt).toggle(true);
				}
			});
		}
		else{
			//disable all the options for start that come after end
			
			elts.first().find("option").each(function(index, elt){
				
				var val = parseInt($(elt).val());
				
				if(val >= int_time && val > 0){
					
					$(elt).prop("disabled", true);
					$(elt).toggle(false);
				}
				else{
					$(elt).prop("disabled", false);
					$(elt).toggle(true);
				}
			});
		}
	}
}



function handlePresetChange(opt){
	
	//alert(opt);
	
	var preset_data = presets[opt];
	if(!preset_data && opt != "custom") return;
		
	if(opt == "custom"){  //re-enable the select inputs for start/end
		
		
		$(st_ed_selects + " option").prop("disabled", false);
		$(st_ed_selects).change();
	}
	else{
		
		$.each(preset_data.days, function(index, val){  //set the select values for the preset, disable changes
			
			var start = val.start;
			var end   = val.end;
			
			$("select[name='"+start.col+"'] option").prop("selected", false);
			$("select[name='"+start.col+"'] option").prop("disabled", true);
			$("select[name='"+start.col+"'] option[value='"+start.val+"']").prop("disabled", false);
			$("select[name='"+start.col+"'] option[value='"+start.val+"']").prop("selected", true);
			
			$("select[name='"+end.col+"'] option").prop("selected", false);
			$("select[name='"+end.col+"'] option").prop("disabled", true);
			$("select[name='"+end.col+"'] option[value='"+end.val+"']").prop("disabled", false);
			$("select[name='"+end.col+"'] option[value='"+end.val+"']").prop("selected", true);
			
		});
	}
}





</script>