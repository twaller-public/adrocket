<?php
/*

	The viewer page for the industry selection in the Campaign Creation Wizard Process
	
*/


	require_once "_what_ctrl.php";
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
				<h2 style="font-size:35px;">What Are You Advertising?<h2>
			</div>
			
			
			<!-- main input section -->
			<div class="col-md-6 col-md-offset-3 text-center" id="ec-process-input-1" style="opacity:0;">

			
				<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
				
				
				
				<!-- industry selection -->
				<div class="form-group text-center">
					<select class="form-control" id="industry" name="industry">
							<?php echo $optionHTML; ?>
					</select>
				</div>
				
				
				
				<!-- user submitted industry wrapper -->
				<div class="form-group other-input" id="other-input">
				
					<p>
						<em>Or type the insustry you're looking for.<br />
						We will contact you for this information if left blank.
						</em>
					</p>
					
					<!-- user submitted industry input -->
					<input 
						class="form-control" 
						type="text" 
						name="other-detail" 
						id="other-detail" 
						value="<?php echo @$user_submitted_industry; ?>" 
						placeholder="Your Industry" 
					/>
				</div>
				
				
				
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
	
	
	//general
	var fast_animation       = <?php echo $errors? 1 : 0; ?>;        //do we cause some animations to speed up

	//page specific
	var show_user_submission = <?php echo $showUserSubmission; ?>;   //do we show the user submitted industry input on load
	var industry_num         = "<?php echo $industry; ?>";       	 //industry number of the campaign
	
	fast_animation = fast_animation || (industry_num && industry_num != "0");
 
</script>

<script src="/js/wizard_common_temp.js"></script>
<script src="/js/wizard_industry.js"></script>


<script>
  $( "#other-detail" ).autocomplete({
    source: "_what_callback.php",
    minLength: 2,
    select: function( event, ui ) {
      $( "#other-detail" ).val(ui.item.value);
      $('#industry').val(ui.item.id);
      $('#industry').selectmenu('refresh');
    }
  });
  
  
</script>




</body>
</html>