<?php
  
	require_once "_signin_ctrl.php";
	require_once "../assets/_header.php";
?>

		<div class="container" style="background-color:white;max-width:800px;/*margin:auto;*/padding:10px 80px 40px 80px;margin:110px auto;">
			<h1>Adrocket Login</h1>

			<!-- USER LOGIN FORM -->
			<?php if (@$errorsAndAlerts): ?>
				<div style="color: #C00; font-weight: bold; font-size: 13px;">
					<?php echo $errorsAndAlerts; ?><br/>
				</div>
			<?php endif ?>

			<?php if (!@$CURRENT_USER): ?>
				<form action="?" method="post">
					<input type="hidden" name="action" value="login" />
					
					<div class="form-group">
						<label>Username</label>
						<input class="form-control" type="text" name="username" value="<?php echo htmlencode(@$_REQUEST['username']); ?>" size="30" autocomplete="off" />
					</div>
					
					<div class="form-group">
						<label>Password</label>
						<input class="form-control" type="password" name="password" value="<?php echo htmlencode(@$_REQUEST['password']); ?>" size="30" autocomplete="off" />
					</div>
					
					<div class="form-group">
						<br/><input class="btn btn-danger" type="submit" name="submit" value="Login" />
						<a href="<?php echo $GLOBALS['WEBSITE_LOGIN_SIGNUP_URL'] ?>">or sign-up</a><br/><br/>
						<a href="<?php echo $GLOBALS['WEBSITE_LOGIN_REMINDER_URL'] ?>">Forgot your password?</a>
					</div>
				</form>
			<?php endif ?>
		</div>
		<!-- /USER LOGIN FORM -->
		<?php require_once "../assets/_footer.php"; ?>
	</body>
</html>
