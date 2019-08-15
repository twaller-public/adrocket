<?php 
	$industry   = $cpnVals["industry"];
	$records    = $campaign_options["Industries"]->records(); 
	$optionHTML = getHTMLOptionsFromRecordData($records, $industry);
?>



<div class="row">
	<div id="province-select-wrap" class="col-md-8 col-md-offset-2 err-box">


	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Industry Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		
		<p style="font-size:14px;">Select the type of business you will be advetising for.</p>
		
		<select class="form-control" id="industry" name="industry">
			<?php echo $optionHTML; ?>
		</select>
	</div>
</div>