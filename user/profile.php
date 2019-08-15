<?php

	require_once "_profile_ctrl.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php require_once "../assets/_header.php"; ?>
	<div class="container" style="max-width:800px;margin:auto;padding:10px 80px 40px 80px;margin-top:15px; margin-bottom:50px; background-color:#FFF;">

		<h1>Your AdRocket Profile</h1>

		<!-- EDIT PROFILE FORM -->
		<?php if (@$errorsAndAlerts): ?>
			<div style="color: #C00; font-weight: bold; font-size: 13px;">
				<?php echo $errorsAndAlerts; ?><br/>
			</div>
		<?php endif ?>

		<form method="post" action="?">
			<input type="hidden" name="save" value="1" />

			<div class="form-group">
				<label>Full Name<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="fullname" value="<?php echo htmlencode(@$_REQUEST['fullname']); ?>" />
			</div>
			
			<div class="form-group">
				<label>Company Name<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="company" value="<?php echo htmlencode(@$_REQUEST['company']); ?>" />
			</div>
			
			<div class="form-group">
				<label>Email<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="email" value="<?php echo htmlencode(@$_REQUEST['email']); ?>" />
				<p class="bg-danger" id="error-email" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
				<p class="bg-success" id="success-email" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
			</div>
			
			<div class="form-group">
				<label>Phone Number</label>
				<input class="form-control" type="text" name="phone" value="<?php echo htmlencode(@$_REQUEST['phone']); ?>" />
				<p class="bg-danger" id="error-phone" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
				<p class="bg-success" id="success-phone" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
				
			</div>
			
			<?php if ($useUsernames): ?>

				<div class="form-group">
					<label>User Name<span class="red-ast">*</span></label>
					<input class="form-control" type="text" name="username" value="<?php echo htmlencode(@$_REQUEST['username']); ?>" />
					<p class="bg-danger" id="error-username" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
					<p class="bg-success" id="success-username" style="display:none; margin-top:5px; padding:5px 10px; font-weight:700;"></p>
				</div>
			<?php endif ?>
			
			<h3>Billing Address Information: </h3>
			
			<?php require "../assets/_billing_address.php"; ?>
			<!--
			<div class="form-group">
				<label>Address 1<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="addr_1"  value="<?php //echo htmlencode(@$_REQUEST['addr_1']); ?>" />
			</div>
			
			<div class="form-group">
				<label>Address 2</label>
				<input class="form-control" type="text" name="addr_2"  value="<?php //echo htmlencode(@$_REQUEST['addr_2']); ?>" />
			</div>
			
			<div class="form-group">
				<label>City<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="city"  value="<?php //echo htmlencode(@$_REQUEST['city']); ?>" style="width:200px;" />
			</div>
			
			<div class="form-group">
				<label>Potal Code<span class="red-ast">*</span></label>
				<input class="form-control" type="text" name="postal"  value="<?php //echo htmlencode(@$_REQUEST['postal']); ?>" style="width:120px;" />
			</div>
			
			<div class="form-group">
				<label>Country<span class="red-ast">*</span></label>
				<select class="form-control" name="country" readonly>

						<option value="Canada" selected='selected'>Canada</option>
				</select>
			</div>
			
			<div class="form-group">
				<label>Province<span class="red-ast">*</span></label>
				<select class="form-control" name="province">
					<?php //echo $provinces; ?>
				</select>
			</div>
			-->
			
			<div class="form-group">
				<label>Agree to terms of service:</label><br />
				<input type="checkbox" name="agree_tos" value="1" <?php checkedIf('1', @$_REQUEST['agree_tos']); ?> />
				<label>I agree to the <a href="#">terms of service</a>.</label>
			</div>
			<input type="submit" name="profile_update" value="Update" />
		</form><br/>
		<!-- /EDIT PROFILE FORM -->


		<!-- CHANGE PASSWORD FORM -->
		<div style="border: 1px solid #000; background-color: #EEE; padding: 10px;">
			<b>Change Password</b><br/>

			<form method="post" action="?">
				<input type="hidden" name="changePassword" value="1" />
				
				<div class="form-group">
					<label>Current Password</label>
					<input class="form-control" type="password" name="oldPassword" value="<?php echo htmlencode(@$_REQUEST['oldPassword']); ?>" size="40" autocomplete="off" />
				</div>
				
				<div class="form-group">
					<label>New Password</label>
					<input class="form-control" type="password" name="newPassword1" value="<?php echo htmlencode(@$_REQUEST['newPassword1']); ?>" size="40" autocomplete="off" />
				</div>
				
				<div class="form-group">
					<label>New Password (again)</label>
					<input class="form-control" type="password" name="newPassword2" value="<?php echo htmlencode(@$_REQUEST['newPassword2']); ?>" size="40" autocomplete="off" />
				</div>
				
				<input class="button" type="submit" name="submit" value="Change Password &gt;&gt;" />
			</form>
		</div><br/>
		<!-- /CHANGE PASSWORD -->
	</div>
	<?php require_once "../assets/_footer.php"; ?>
	</body>
</html>