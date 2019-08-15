<?php
/*

	The viewer page for the industry selection in the Campaign Creation Wizard Process
	
*/


	require_once "_title_ctrl.php";
	require_once "../../assets/_header.php";
?>


<!-- main page content -->
<div class="jumbotron ec-process">

	
	<?php require "../../assets/_create_breadcrumbs.php"; /*include the breadcrumbs section*/ ?>


	<div class="container" id="main_content">
	
		<!-- page form -->
		<form action="?" method="POST">

		
			<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />
			<input type="hidden" name="summ" value="<?php echo @$_REQUEST['summ']; ?>" />
		
			<!-- section header -->
			<div class="col-md-12 text-center" id="ec-process-header-1" style="opacity:0; height:0px;">
				<h2 style="font-size:35px;">Give Your Campaign A Title And Budget<h2>
			</div>
			
			
			<!-- main input section -->
			<div class="col-md-6 col-md-offset-3 text-center" id="ec-process-input-1" style="opacity:0;">

			
				<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
				


					
				<!-- title -->
				<div class="form-group clearfix err-box text-center <?if($errors && !@$_REQUEST["title"]) echo " has-error"; ?>">
					<label style="font-size:18px;">Campaign Title<span class="red-ast">*</span></label>
					<input 
						class="form-control" 
						type="text" 
						name="title"
						value="<?php echo $cpnTitle; ?>" 
						placeholder="" 
						maxlength="75" 
					/>
				</div>
					
				
				<hr />
				
				
				
				<!-- Budget -->
				<?php  //display CPM (clicks) is budget x six  ?>
				
				
				<div class="col-md-12 text-center" style="position:relative;">
			
					<div class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-3 err-box text-center" style="padding:0;">

						<h3><label>What is your Monthy Budget?</label></h3>
						
						<div class="budget-slider"></div><br />
						<div class="budget-scales" style="height:32px; margin-top:-15px">
							<span class="label label-danger pull-left" style="font-size:16px;margin-left:-10px;">250</span>
							<span class="label label-danger pull-right" style="font-size:16px;margin-right:-15px;">10,000</span>
						</div>
					</div>	
					
        </div>
        <div class="col-md-12 text-center" style="position:relative;">

					
					<div class="col-lg-8 col-lg-offset-2 col-md-6 col-md-offset-4 err-box text-center" style="padding:0;">
						<div class="col-lg-12 text-center" style="padding:0;">
							<div style="display:inline-block; width:150px;">
								<div class="input-group" style="/*min-width:130px; max-width:140px;*/margin-top:5px;">
									<div class="input-group-addon" style="padding:6px;">$</div>
									<input 
										class="form-control lead-calc"
										id="budget"
										type="text" 
										name="budget" 
										value="<?php echo $cpnBudget; ?>" 
										style="margin-top:0px; padding:5px 8px; text-align:right;"
									/>
									<div class="input-group-addon" style="padding:6px; font-size:18px;">.00</div>
								</div>
							</div>
						</div>
						
						<div class="col-lg-12" style="padding:0;">
							<span style="font-size:24px;"><label style="padding-top:10px;">Website Traffic/Visits:</label></h3>
							<?php if(!$industry || !$locationTitles) : ?>
								<span class="pull-right" style="font-size:16px;">Select your Locations, Industry and Budget to see this value</span>
							<?php else : ?>
								<span class="pull-right" id="expected-clicks" style="text-decoration:none; font-size:40px; color: #C00; font-weight: bold;">&nbsp;<?php echo $cpnLeads; ?></span>&nbsp;&nbsp;&nbsp;
							
							<?php endif; ?>
						</div>
						
						
						<div class="col-md-12">
							<br />
							<!-- <p style="font-size:16px;"><em>Not sure what you should be spending? Use our <a href="#">ROI calculator</a> to figure out your budget!</em></p> -->
						</div>
					</div>
					
					
				</div>

				
				
				
				<!-- submit button -->
				<div class="col-md-12" style="position:relative;">
					<div class="form-group text-center">
					
						<input 
							class="btn btn-lg btn-danger" 
							type="submit" 
							name="Continue" 
							value="Continue" 
						/>
						
					</div>
				</div>
			</div>
			<div class="col-md-5"></div>
		</form>
	</div>
</div>


<!-- loading spinner -->
<div id="page-loading" class="loading text-center load-style-1">
	<img class="load-style-200sq" src="/img/spinner.gif" alt=""/>
</div>




<?php require_once "../../assets/_footer.php"; /*footer*/ ?>




<!-- Page-specific Javascript -->
<script>

	<?php /*js variables set from php; */ ?>
	
	
	cpnNum  = <?php echo $cpnNum ?: 0; ?>;
	
	
	//general
	var fast_animation       = <?php echo $errors? 1 : 0; ?>;        //do we cause some animations to speed up


	
	$(function(){

	
	
		//start the budget slider
		homePage_startBudgetSlider();
	
		//when user changes the inputs that are used to calculate the leads
		homePage_requestLeadsHandler();

	
		//when we are ready to show the page
		$("#page-loading").hide();
		
		animateHeader1_show();
		
		
		
		
		
		
		
	});
	
	
	
	//start the budget slider
function homePage_startBudgetSlider(){
	
	//the init for the budget slider
	$(".budget-slider").slider({
		range:false,
		min:250,
		max:10000,
		step:10,
		create: null,
		change: null,
		slide: function( event, ui ) {
			
			$("input[name='budget']").attr("value", ui.value);
			$("input[name='budget']").change(); 
		}
	});

	$(".budget-slider").slider("value", $("input[name='budget']").val()); 


	$(".budget-slider").slider("option", "change", function(event, ui){
			
		$("input[name='budget']").attr("value", ui.value);
		$("input[name='budget']").change(); 
	});

	$("input[name='budget']").on("keyup", function(){ 
		$(".budget-slider").slider("value", $(this).val()); 
	});
	
	return false;
}





function budget_requestLeads(budget){
	
	var result = $.ajax({
		url: "?",
		dataType: "json",
		data: {
			get_leads: 1,
			cpnNum: cpnNum,
			bud: budget
		},
		method: "POST"
	});
	
	result.done(function(data){
		console.log(data);
		$("span#expected-clicks").text(data.result);
	});
	result.fail(function(data){
		console.log(data);
	});
}




function homePage_requestLeadsHandler(){
	
	$("#budget").on("change", function(){
		

		var bud  = $("#budget").val();
		
		budget_requestLeads(bud);
	});
}
 
</script>

<script src="/js/wizard_common_temp.js"></script>




</body>
</html>