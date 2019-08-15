<?php
/*

	The viewer page for the time and date selection in the Campaign Creation Wizard Process
	
*/


	require_once "_when_ctrl.php";
	require_once "../../assets/_header.php";
?>


<!-- main page content -->
<div class="jumbotron ec-process">

	
	<?php require "../../assets/_create_breadcrumbs.php"; /*include the breadcrumbs section*/ ?>


	<div class="container text-center" id="main_content">
	
		
		
		<!-- page form -->
		<form action="?" method="POST">
		
			<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />
			<input type="hidden" name="duration" id="duration" value="<?php echo (@$duration)? $duration : "0"; ?>" />
			<input type="hidden" name="summ" value="<?php echo @$_REQUEST['summ']; ?>" />

			<!-- section header -->

			
			<div class="col-lg-10 col-lg-offset-1 text-center" id="ec-process-input-1" style="opacity:0;">

			<div class="col-md-12 text-center" id="ec-process-header-1" style="opacity:0; height:0px;">
				<h2 style="font-size:35px;">When Would You Like To Advertise?</h2>
			</div>

				<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
				
				
				<!-- Start and End Dates -->
				<div class="col-md-12 text-center">
				
					<p class="text-center user-info">Click the Calendar Icons to Choose Your Start and End Dates.</p>
					<br />
				
					<div class="col-sm-6 text-center date-select">
		
						<!-- start date -->
						<div class="err-box" style="display:inline-block; margin-right:0;">
							<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Start Date Modal Label"]; ?><span class="red-ast">*</span></label><br />
							<input 
								id="start-date" 
								type="text" 
								name="start_date" 
								value="<?php if($js_start) echo $js_start; ?>" 
								style="max-width:175px;" 
								readonly 
							/>
						</div>
					</div>
					
					
					<div class="col-sm-6 text-center date-select">
					
						<!-- end date -->
						<div class="err-box" style="display:inline-block;">
							<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["End Date Modal Label"]; ?></label><br />
							<input 
								id="end-date" 
								type="text" 
								name="end_date" 
								value="<?php if($js_end) echo $js_end; ?>" 
								style="max-width:175px;" 
								readonly 
							/>
						</div>
					</div>
				</div>
				
				
				
				<!-- start and duration summary -->
				<div class="col-md-12 text-center">
					<h3><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Your Campaign Heading"]; ?> <span id="dur_msg"><?php echo $duration_summary; ?></span>.</h3>
				</div>

				
				
				
				<!-- checkbox options -->
				<div class="col-md-10 col-md-offset-1 text-center" style="font-size:18px; padding-top:30px;">
		
					<!-- recurring -->
					<div class="text-center">
						<input 
							type="checkbox"
							name="recur"
							value="1"
							id="recur"   
							<?php if($recurring) echo " checked"; ?>
						/>
						&nbsp;&nbsp;<label>Recurring (Run My Campaign Monthly)</label>
					</div>
					
					
					<!-- expiry notification -->
					<div class="expiry-wrap text-center">

							<input 
								type="checkbox" 
								name="campaign_expiry_notification" 
								value="1" 
								<?php if($notify) echo " checked"; ?>
							/>
							&nbsp;&nbsp;<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Expiry Modal Label"]; ?> (By Email)</label>

					</div>
					
					
					<!-- day parting -->
					<div class="expiry-wrap text-center">

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
				
				
			
				<!-- day parting details -->
				<div class="col-md-12 day_parting_details text-center" style="display:none; font-size:18px;">
					<hr />
					<?php require "../../assets/form-elements/_day_parting_select.php"; ?>
				</div>
				
				
				
				<div class="col-md-10 col-md-offset-1 text-center">
					<!-- submit button -->
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
			
			<div class="col-md-4"></div>
		</form>
	</div>

</div>

<!-- loading spinner -->
<div id="page-loading" class="loading text-center load-style-1">
	<img class="load-style-200sq" src="/img/spinner.gif" alt=""/>
</div>




<?php require_once "../../assets/_footer.php"; /*footer*/ ?>




<script>

	<?php /*js variables set from php; */ ?>
	
	
	//general
	var fast_animation       = <?php echo $errors? 1 : 0; ?>;        //do we cause some animations to speed up?
	
	//page specific
	//start/end date info from PHP script
	var start_date  = "<?php echo $js_start; ?>";
	var end_date    = "<?php echo $js_end; ?>";
	var recur_label = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recur Duration Label"]; ?>";
	var fixed_label = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Set Duration Label"]; ?>";
	var length_warn = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Duration Warning Length"]; ?>";
	var start_warn  = "<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Duration Warning Dates"]; ?>";

	fast_animation = (fast_animation || start_date)? true : false;
	//console.log(fast_animation);
	
</script>


<script src="/js/wizard_common_temp.js"></script>
<script src="/js/wizard_timedate.js"></script>

</body>
</html>