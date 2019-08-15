<?php
	$budget = $cpnVals["budget"];
?>

<div class="row">

	<div class="col-md-12"><hr /></div>
	<div class="col-xs-6 col-xs-offset-2 err-box center-text">

		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Budget Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		<p style="font-size:14px;margin-bottom:15px;">You can enter your total budget if you know the end date for your Campaign.</p>
		
		<div class="budget-slider"></div><br />
		<div class="budget-scales" style="height:25px; margin-top:-15px">
			<span class="label label-danger pull-left" style="margin-left:-10px;">250</span>
			<span class="label label-danger pull-right" style="margin-right:-15px;">10,000</span>
		</div>
		
		
		<div class="input-group" style="max-width:250px;margin-top:20px;">
			<div class="input-group-addon">$</div>
			<input 
				class="form-control lead-calc" 
				type="text" 
				name="budget" 
				value="<?php echo $budget; ?>" 
			/>
			<div class="input-group-addon">.00</div>
		</div>
	</div>
</div>
<!-- budget slider script -->
<script>

	$(function(){
		
		//the init for the budget slider
		$(".budget-slider").slider({
			range:false,
			min:250,
			max:10000,
			step:10,
			create: null,
			change: null,
		});
		
		$(".budget-slider").slider("value", $("input[name='budget']").val()); 
		
		
		$(".budget-slider").slider("option", "change", function(event, ui){
				
			$("input[name='budget']").attr("value", ui.value);
			$("input[name='budget']").change(); 
		});
		
		$("input[name='budget']").on("keyup", function(){ 
			$(".budget-slider").slider("value", $(this).val()); 
		});

	})
</script>