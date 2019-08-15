<?php 
	$vendor  = $cpnVals["vendor"];
	$records    = $campaign_options["Vendors"]->records(); 
	$optionHTML = getHTMLOptionsFromRecordData($records, $vendor);
?>


<div class="row">
	<div class="col-md-8 col-md-offset-2">
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Vendor Label Modal"]; ?><span class="red-ast">*</span></label>
		
		<p style="font-size:14px;margin-bottom:0px;">Select the Vendor you want to run your ads with.</p>
	</div>

	<div id="vendor-select-wrap" class="col-md-8 col-md-offset-2 clearfix err-box">	
		<select class="form-control" id="vendor" name="vendor">
			<?php echo $optionHTML; ?>
		</select>
	</div>

	<div class="col-md-12"><br /></div>
</div>


<?php
	/*
		This page loads the vendor options for users to select and has two sections:
		
		1. The list of vendor options. The user can click on the vendor they choose to use for their campaign.
		
		2. The vendor information section. This section shows the vendor information of the currently selected vendor.
		This information includes:
			- name
			- logo
			- ad type
			- short description
			- preview ad
	*/
?>