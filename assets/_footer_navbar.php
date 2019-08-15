
<div class="row footer-nav">
	<div class="main-nav col-md-9">


		<?php if(@$CURRENT_USER) : ?>
			<!-- User only Menu -->
			<div class="col-md-4">
				<h3>My Account</h3>
				<ul>
					<li><a href="/user/profile.php">My Profile</a></li>
					<li><a href="/campaign/create.php">Start New Campaign</a></li>
					<li><a href="/campaign/list.php">My Campaigns</a></li>
					<li>&nbsp;</li>
				</ul>
			</div>
		<?php endif; ?>

		
		
		
		<!-- All Links 
		<div class="col-md-4 text-center">
			<div class="text-left" style="display:inline-block;">
				<h3>Useful Information</h3>
				<ul>
					<li><a href="/learning-center">Learning Center</a></li>
					<li><a href="vendors">Our Vendors</a></li>
					<li><a href="/roi-calculator">ROI Calculator</a></li>
					<li><a href="/faq">FAQ</a></li>
				
				</ul>
			</div>
		</div>
		-->
		
		<div class="col-md-4 text-center">
			<div class="text-left" style="display:inline-block;">
				<h3>About Adrocket</h3>
				<ul>
					<!-- <li><a href="/about">About Us</a></li> -->
					<li><a href="/privacy">Privacy Policy</a></li>
					<li><a href="/terms-of-use">Terms of Use</a></li>
					<!-- <li><a href="/contact">Contact Us</a></li> -->
				</ul>
			</div>
		</div>

	</div>



	<?php if(!@$CURRENT_USER) : ?>
		<div class="right-nav col-md-3 text-center">
			<div class="text-left" style="display:inline-block;">
				<h3>Adrocket Accounts</h3>
				<ul>
					<li><a href="/user/signup.php">Sign up</a></li>
					<li><a href="/user/signin.php">Sign in</a></li>
					<li><a href="/user/pw_reset_request.php">Password reset request</a></li>
					<!-- <li><a href="/user/learning-center.php">Learning Center</a></li> -->
				</ul>
			</div>
		</div>
	<?php endif; ?>
</div>