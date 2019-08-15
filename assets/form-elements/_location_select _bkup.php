<?php 
	$province   = $cpnVals["province"];   //currently selected province
	$preset     = @$cpnVals["preset"];
	$locations  = @$cpnVals["locations"];
	
	//record informatin related to province - NOT VALUES FOR CAMPAIGN
	$location_opts  = getLocationsForProvince($province);
	$preset_opts    = getPresetListForProvince($province);
	
	//showme($preset_opts);
	
	//selected location values (from campaign)
	$checkedLocs    = getCheckedLocationValues($locations);
	
	//are we showing the presets select element?
	$showPresets    = !empty($preset_opts);
	
	//html elements
	$optionHTML     = getHTMLOptionsFromArray($preset_opts, $preset);
	
	$checkboxesHTML = getCheckboxesFromFromRecordData(
		$location_opts, 
		$checkedLocs,
		"num",
		"title",
		true,
		"title",
		array("data-preset" => "division")
	);
	//showme($location_opts);

?>


<div class="row">

	<!-- location preset (division) selection -->
	<div id="preset-select-wrap" class="col-md-6 col-md-offset-3 err-box" <?php if(!$showPresets) echo "style='display:none;'"; ?>>	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Presets Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		<select class="form-control" id="preset" name="preset">
			<?php echo $optionHTML; ?>
		</select>
		<div class="col-md-12">
			<p class="text-center">OR</p>
		</div>
	</div>

	
	<!-- location checkboxes -->
	<div id="locations-select-wrap" class="col-md-12 err-box">
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Locations Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		<?php echo $checkboxesHTML; ?>
	</div>

	
</div>


<script>
	//handles the input events for location selection
	
	$(function(){
		
		var preset_select  = $("select#preset");
		var location_boxes = $("div#locations-select-wrap input[type='checkbox']");
		
		
		
		/**
		* When the Preset selection changes:
		*/
		$("body").on("change", "select#preset", function(){
			
			//alert();
			location_boxes = $("div#locations-select-wrap input[type='checkbox']");
			
			var preset = $(this).val();     //the value of the selection (name)
			if(preset == "0") return;       //preset deselection - leave the checks in place for ease-of-use
			
			//console.log(preset);
			
			location_boxes.each(function(index, elt){
				
				//console.log($(elt).attr("data-preset") + " : " + preset);
				$(this).prop("checked", false);                                            //uncheck each box
				if($(this).attr("data-preset") == preset) $(this).prop("checked", true);   //check each box for the preset
			});
		});
		
		
		/**
		* When a user interacts with a checkbox when a preset is selected:
		* - clear the preset selection, keep boxes in their current state
		*/
		location_boxes.on("change", function(){
			
			//remove the preset selection
			preset_select.find("option").prop("selected", false);
			
		})
		
	})

</script>