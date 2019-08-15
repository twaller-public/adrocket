<?php 
	//get already present contact info
	$c_name  = @$cpnVals["contact_name"];
	$c_comp  = @$cpnVals["company_name"];
	$c_phone = @$cpnVals["contact_phone"];
	$c_email = @$cpnVals["contact_email"];
	$c_comt  = @$cpnVals["comments"];
?>

<div class="row">
	<div class="col-md-8 col-md-offset-2">
			
		<!-- name -->
		<div class="form-group clearfix err-box">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["C Name Modal Label"]; ?><span class="red-ast">*</span></label><br/>
			<input 
				class="form-control" 
				type="text" 
				name="contact_name"
				value="<?php echo $c_name ?: @$CURRENT_USER['fullname'] ?: ""; ?>" 
				placeholder="" 
				maxlength="75" 
			/>
		</div>
		
		<!-- company -->
		<div class="form-group clearfix err-box">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["C Company Modal Label"]; ?><span class="red-ast">*</span></label><br/>
			<input 
				class="form-control" 
				type="text" 
				name="company_name" 
				value="<?php echo $c_comp ?: @$CURRENT_USER['company'] ?: ""; ?>" 
				placeholder="" 
				maxlength="75" 
			/>
		</div>
		
		<!-- email -->					
		<div class="form-group clearfix err-box">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["C Email Modal Label"]; ?><span class="red-ast">*</span></label><br/>
			<input 
				class="form-control" 
				type="text" 
				name="contact_email" 
				value="<?php echo $c_email ?: @$CURRENT_USER['email'] ?: ""; ?>" 
				placeholder="" 
				maxlength="75" 
			/>
		</div>
		
		<!-- phone -->
		<div class="form-group clearfix err-box">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["C Phone Modal Label"]; ?><span class="red-ast">*</span></label><br/>
			<input 
				class="form-control" 
				type="text" 
				name="contact_phone" 
				value="<?php echo $c_phone ?: @$CURRENT_USER['phone'] ?: ""; ?>" 
				placeholder="" 
				maxlength="75" 
			/>
		</div>
	</div>
		
	<div class="col-md-8">	
		
		<!-- comments -->
		<div class="form-group clearfix">
			<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Comments Modal Label"]; ?></label><br/>
			<textarea 
				class="form-control" 
				name="comments" 
				rows="6" 
				style="resize:vertical; max-height:200px;"
			><?php echo $c_comt; ?></textarea>
		</div>
	</div>
</div>