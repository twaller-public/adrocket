<?php
	$title = $cpnVals["title"];
?>

<div class="row add-type-choice">
			
	<div class="col-md-8 col-md-offset-2 clearfix err-box">
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Title Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		
		<p style="font-size:14px;">We will use this title to identify your Campaign on Adrocket.</p>
		
		<input 
			class="form-control" 
			type="text" 
			name="title" 
			value="<?php echo $title; ?>" 
			placeholder="Your Campaign Title" 
			maxlength="75" 
		/>
		
	</div>
	<div class="col-md-12"><br /></div>
	
	
</div>