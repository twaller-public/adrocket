<?php
	
	require_once "_contact_ctrl.php";
	require_once "../../assets/_header.php";

	
	$countdown = "2";

?>



<!-- main page content -->
<div class="jumbotron ec-process" style="background-color:white; margin-top: 0px;">

	
	<?php require "../../assets/_create_breadcrumbs.php"; ?>


	<div class="container" id="main_content">
	
	
		<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
		
		
		<h2 style="font-size:35px;" class="text-center">Contact Information</h2>
		
		<div class="row contact-info">
		
			<form action="?" method="POST">
			
				<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />
				<input type="hidden" name="summ" value="<?php echo @$_REQUEST['summ']; ?>" />
			
				<div class="col-md-6 col-md-offset-3 text-center">
						
					<!-- name -->
					<div class="input-group clearfix err-box<?if($errors && !@$_REQUEST["contact_name"]) echo " has-error"; ?>">
						<label class="input-group-addon">Full Name<span class="red-ast">*</span></label>
						<input 
							class="form-control" 
							type="text" 
							name="contact_name"
							value="<?php echo $name; ?>" 
							placeholder="" 
							maxlength="75" 
						/>
					</div>
					
					<!-- company -->
					<div class="input-group clearfix err-box<?if($errors && !@$_REQUEST["company_name"]) echo " has-error"; ?>">
						<label class="input-group-addon">Company Name<span class="red-ast">*</span></label>
						<input 
							class="form-control" 
							type="text" 
							name="company_name" 
							value="<?php echo $company; ?>" 
							placeholder="" 
							maxlength="75" 
						/>
					</div>
					
					<!-- email -->					
					<div class="input-group clearfix err-box<?if($errors && !@$_REQUEST["contact_email"]) echo " has-error"; ?>">
						<label class="input-group-addon">Email Address<span class="red-ast">*</span></label>
						<input 
							class="form-control" 
							type="text" 
							name="contact_email" 
							value="<?php echo $email; ?>" 
							placeholder="" 
							maxlength="75" 
						/>
					</div>
					
					<!-- phone -->
					<div class="input-group clearfix err-box<?if($errors && !@$_REQUEST["contact_phone"]) echo " has-error"; ?>">
						<label class="input-group-addon">Phone Number<span class="red-ast">*</span></label>
						<input 
							class="form-control" 
							type="text" 
							name="contact_phone" 
							value="<?php echo $phone; ?>" 
							placeholder="" 
							maxlength="75" 
						/>
					</div>
					
					<div class="input-group">
						<label class="input-group-addon">Province<span class="red-ast">*</span></label>
						<select class="form-control" name="province">
							<?php echo $provinces; ?>
						</select>
					</div>
				</div>
					
					
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-6 col-md-offset-3 text-center">	
							
							<!-- comments -->
							<div class="form-group clearfix">
								<label>Additional Comments</label><br/>
								<textarea 
									class="form-control" 
									name="comments" 
									rows="8" 
									style="resize:vertical; max-height:200px;"
									maxlength="100"
								><?php echo $comment; ?></textarea>
							</div>
						</div>
					</div>
				</div>
				
				
				<!-- submit button -->
				<div class="col-md-6 col-md-offset-3 text-center">
					<div class="form-group text-center">
					
						<input 
							class="btn btn-lg btn-danger" 
							type="submit" 
							name="Continue" 
							value="Continue" 
						/>
						
					</div>
				</div>
			
			</form>
		</div>
	
	
		

	</div>

</div>

<div id="page-loading" class="loading text-center" style="position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(0,0,0,0.6); padding-top:150px;">
	<img src="/img/spinner.gif" alt="" style="width:200px; height:200px;"/>
</div>

<?php 
	
	//footer
	require_once "../../assets/_footer.php"; 
	
?>



<script src="/js/wizard_common_temp.js"></script>
<script>


	

	$(function(){


	
		//when we are ready to show the page
		$("#page-loading").hide();
		
		animateHeader1_show();
		
		
		
		

		
		
	});


	
	
</script>


</body>
</html>