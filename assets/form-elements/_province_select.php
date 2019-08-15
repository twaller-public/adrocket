<?php 
	$province   = $cpnVals["province"];
	$records    = $campaign_options["Provinces"]->records(); 
	$optionHTML = getHTMLOptionsFromRecordData($records, $province);
?>


<div class="row">

	<div id="province-select-wrap" class="col-md-6 err-box">	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Province Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		<select class="form-control" id="province" name="province">
			<?php echo $optionHTML; ?>
		</select>
	</div>
