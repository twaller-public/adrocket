<?php
	require_once "_signup_ctrl.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 
	<?php require_once "../assets/_header.php"; ?>

	<script> //signup = true; </script>
	
	<!-- USER SIGNUP FORM -->
	
	<div class="container" style="background-color:white;max-width:800px;margin:auto;padding:10px 80px 40px 80px;margin-top:15px; margin-bottom:50px;">
		<div class="col-sm-10">
			<h1><?php echo $pageTitle; ?></h1>
			
			<?php if (@$errorsAndAlerts): ?>
				<div style="color: #C00; font-weight: bold; font-size: 13px;">
					<?php echo $errorsAndAlerts; ?><br/>
				</div>
			<?php endif ?>
			<?php if ($showSignupForm): ?>	
				<form method="post" action="?">
					<input type="hidden" name="save" value="1" />
					<input type="hidden" name="complete_signup" value="<?php echo htmlencode(@$_REQUEST["complete_signup"]); ?>" />
					<input type="hidden" name="campaignNum" value="<?php echo htmlencode(@$_REQUEST["campaignNum"]); ?>" />
					
          
          
					<div class="form-group">
						<label>Full Name<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="fullname" value="<?php echo htmlencode(@$_REQUEST['fullname']); ?>" style="width:300px;" />
					</div>
					
					<div class="form-group">
						<label>Company Name<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="company" value="<?php echo htmlencode(@$_REQUEST['company']); ?>" style="width:300px;" />
					</div>
					
					<div class="form-group">
						<label>Email<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="email" value="<?php echo htmlencode(@$_REQUEST['email']); ?>" style="width:300px;" />
						<p class="bg-danger" id="error-email" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
						<p class="bg-success" id="success-email" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
					</div>
					
					<div class="form-group">
						<label>Phone Number</label>
						<input class="form-control" type="text" name="phone" value="<?php echo htmlencode(@$_REQUEST['phone']); ?>" style="width:160px;" />
						<p class="bg-danger" id="error-phone" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
						<p class="bg-success" id="success-phone" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
						
					</div>
					
					<?php if ($useUsernames): ?>

						<div class="form-group">
							<label>User Name<span class="red-ast">*</span></label>
							<input class="form-control" type="text" name="username" value="<?php echo htmlencode(@$_REQUEST['username']); ?>" style="width:200px;" />
							<p class="bg-danger" id="error-username" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
							<p class="bg-success" id="success-username" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
						</div>
					<?php endif ?>
					
					<h3>Billing Address Information: </h3>
					
					<div class="form-group">
						<label>Address 1<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="addr_1"  value="<?php echo htmlencode(@$_REQUEST['addr_1']); ?>" />
					</div>
					
					<div class="form-group">
						<label>Address 2</label>
						<input class="form-control" type="text" name="addr_2"  value="<?php echo htmlencode(@$_REQUEST['addr_2']); ?>" />
					</div>
					
					<div class="form-group">
						<label>City<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="city"  value="<?php echo htmlencode(@$_REQUEST['city']); ?>" style="width:200px;" />
					</div>
					
					<div class="form-group">
						<label>Postal Code<span class="red-ast">*</span></label>
						<input class="form-control" type="text" name="postal"  value="<?php echo htmlencode(@$_REQUEST['postal']); ?>" style="width:120px;" />
					</div>
					
					
					<!-- Country -->
					<div class="form-group" style="width:200px;">
						<label>Country<span class="red-ast">*</span></label>
						<select class="form-control" name="country" readonly>

								<option value="Canada" selected='selected'>Canada</option>
						</select>
					</div>
					
					<!-- Province -->
					<div class="form-group" style="width:200px;">
						<label>Province<span class="red-ast">*</span></label>
						<select class="form-control" name="province">
							<?php echo $provinces; ?>
						</select>
					</div>

					
					<!-- TOS -->
					<div class="form-group">
						<label>Agree to <span id="tou-show" style="color:blue; cursor:pointer;">Terms of Use</span><span class="red-ast">*</span></label>
						<input type="checkbox" name="agree_tos" value="1" <?php if(@$_REQUEST['agree_tos']) echo "checked='checked'"; ?>/>
					</div>

					<input class="button btn btn-lg btn-danger" type="submit" name="submit" value="Sign up &gt;&gt;" />
				</form>
			<?php endif ?>
		</div>
	</div>
	<!-- /USER SIGNUP FORM -->
	
	<?php require_once "../assets/_footer.php"; ?>
	
	
	<!-- Modal -->
	<div class="modal fade tou-modal" id="tou-modal" tabindex="-1" role="dialog" aria-labelledby="tou-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
			
				<div class="modal-header">
					<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
					<h4 class="modal-title" id="myModalLabel">Adrocket Terms of Use</h4>
				</div>
				
				<div class="modal-body">
					<p>Terms of Use Placeholder</p>
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				
			</div>
		</div>
	</div>
	
	
	<script>
		$(function(){
			
			$("span#tou-show").on("click", function(){
				
				$("#tou-modal").modal();
			})
		})
	
	</script>
	
	
	
	
	
	
	
	
	</body>
</html>