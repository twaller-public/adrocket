<nav class="navbar navbar-default">
	<div class="container-fluid">
	
		<!-- SIGN IN/OUT AREA -->
		<div class="uac-wrap pull-right" style="position:relative; top:0; right:0; font-size:18px;">
			<?php if(@$AGENT_TYPE != "GUEST") : ?>
				<p style="color:red; font-weight:700; padding-bottom:8px;">Logged in as: <a href="/user/profile.php"><?php echo $CURRENT_USER['fullname']; ?></a>&nbsp;&nbsp;<a href="/index.php?action=logoff">Sign Out</a></p>
			<?php else : ?>
				<ul class="nav nav-pills pull-right visible-lg-block" style="/*margin-bottom:0; position:absolute; bottom:0; right:0;*/">
					<li role="presentation"><a href="/user/signup.php">Sign Up</a></li>
					<li role="presentation"><a href="/user/signin.php">Sign In</a></li>
				</ul>
			<?php endif; ?>
		</div>
	
		<div class="dropdown">
			<div class="col-sm-2">
				<a href="/home"><img class="main-logo img-responsive" src="/img/adrocketlogo-002.png" alt="" /></a>
			</div>
		
			<div class="col-sm-10" style="/*margin-top:40px;*/">
			
				<img src="/img/google-partner-adwords-analytics.png" height="70" width="auto" class="pull-left" />
				<!-- <a href="#"><img class="img-responsive hidden-xs" src="/img/adr728x90.jpg" alt="" style="float:right;"/></a><br /> -->
	
				<div id="main-nav" class="navbar navbar-default visible-lg-inline" style="float:left; margin-bottom:0; display:inline-block; box-shadow:none; border:none;">
					<?php if(@$AGENT_TYPE == "GUEST") : //user menu for non-logged in users ?>
					
					<ul class="nav navbar-nav">
						<!--
						<li>
							<a class="dropdown-toggle" id="campaign-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="/campaign/create.php"><span class="caret"></span>&nbsp;Campaigns</a>
							<ul class="dropdown-menu pull-left" aria-labelledby="campaign-dropdown" style="padding:0;">
								<li><a href="/campaign/create.php" style="padding:8px 16px;">Create new Campaign</a></li>
								<li><a href="/learning">Learning Center</a></li>
								<li><a href="/vendor/index.php">Our Vendors</a></li>
							</ul>
						</li>
						-->
						<li><a href="/campaign/create/title.php">Create new Campaign</a></li>
						<!-- <li><a href="/about">About</a></li> -->
						<!-- <li><a href="/terms-of-use">Terms of Use</a></li> -->
						<!-- <li><a href="/privacy">Privacy Policy</a></li> -->
						<!-- <li><a href="/roi-calculator">ROI Calculator</a></li> -->
					</ul>
					
					<?php elseif(@$AGENT_TYPE == "USER") : //general user menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/user/dashboard.php"></span>&nbsp;Dashboard</a></li>
							<!-- <li><a href="/about">About</a></li> -->
							<li><a href="/terms-of-use">Terms of Use</a></li>
							<li><a href="/privacy">Privacy Policy</a></li>
							<!-- <li><a href="/roi-calculator">ROI Calculator</a></li> -->
						</ul>
					
					<?php elseif(@$AGENT_TYPE == "MANAGER") : //Manager menu ?>
						<ul class="nav navbar-nav">
							
							<li><a href="/manager/dashboard.php"></span>&nbsp;Dashboard</a></li>
							<li><a href="/manager/campaigns.php">Campaigns</a></li>
							<!--
							<li><a href="/learning">Learning Center</a></li>
							<li><a href="/terms-of-use">Terms of Use</a></li>
							<li><a href="/privacy">Privacy Policy</a></li>
							<li><a href="/roi-calculator">ROI Calculator</a></li>
							-->
						</ul>
						
					<?php elseif(@$AGENT_TYPE == "ADMIN") : //Admin menu ?>
						<ul class="nav navbar-nav">
						<!--
							<li><a href="/learning">Learning Center</a></li>
							<li><a href="/vendor/index.php">Our Vendors</a></li>
						-->
							<li><a href="/admin/dashboard.php">Dashboard</a></li>
							<li><a href="/admin/campaigns.php">Campaigns</a></li>
							<li><a href="/admin/users.php">Users</a></li>
							<!-- <li><a href="/roi-calculator">ROI Calculator</a></li> -->
						<!--
							<li><a href="/about">About</a></li>
							<li><a href="/terms">Terms of Use</a></li>
							<li><a href="/privacy">Privacy Policy</a></li>
						-->
						</ul>
						
						
					<?php elseif(@$AGENT_TYPE == "CEO") : //CEO menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/ceo/dashboard.php">Dashboard</a></li>
							<li><a href="/ceo/campaigns.php">Campaigns</a></li>
							<li><a href="/ceo/revenue.php">Revenue</a></li>
							<li><a href="/ceo/users.php">Users</a></li>
						</ul>
					<?php endif; ?>
				</div>
			
				
			</div>
		
			<!-- MOBILE -->
			<button class="btn btn-default dropdown-toggle hidden-lg" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
			</button>
			
			<ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1" style="margin-top:80px;">
		
				<?php if(@$AGENT_TYPE == "GUEST") : //mobile menu for non-logged in users ?>
					<li><a href="/user/signup.php">Sign up</a></li>
					<li><a href="/user/signin.php">Sign in</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning-center">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms-of-use">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					<!-- <li><a href="/vendor/index.php">Our Vendors</a></li> -->
					<li><a href="/campaign/create.php">Create a Campaign</a></li>
					<li><a href="/user/user-password-reset.php">Password reset request</a></li>
					
				<?php elseif(@$AGENT_TYPE == "USER") : //mobile menu for general users?>
					<li><a href="/user/user-profile.php">Profile</a></li>
					<li><a href="/campaign/create.php?new=1">New Campaign</a></li>
					<li><a href="/campaign/list.php">Edit Campaigns</a></li>
					<li><a href="/campaign/active.php">Active Campaigns</a></li>
					<li class="active"><a href="/index.php">Home</a></li>
					<!-- <li><a href="/learning-center">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms-of-uses">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					<li><a href="/index.php?action=logoff">Sign out</a></li>
					
				<?php elseif(@$AGENT_TYPE == "MANAGER") : //Manager menu : ?>
					<li><a href="/manager/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/manager/acquire.php" style="padding:8px 16px;">Acquire</a></li>
					<li><a href="/manager/campaigns.php" style="padding:8px 16px;">My Campaigns</a></li>
					<li><a href="/manager/history.php" style="padding:8px 16px;">Campaign History</a></li>
					<li class="active"><a href="/index.php">Home</a></li>
					<!-- <li><a href="/learning-center">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms-of-use">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					
				<?php elseif(@$AGENT_TYPE == "ADMIN") : //Admin menu ?>
					<li><a href="/admin/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/admin/campaigns.php" style="padding:8px 16px;">Campaigns</a></li>
					<li><a href="/admin/managers.php" style="padding:8px 16px;">Managers</a></li>
					<li><a href="/admin/users.php" style="padding:8px 16px;">Users</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning-center">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms-of-use">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					<!-- <li><a href="/admin/settings.php" style="padding:8px 16px;">Settings</a></li> -->
					
				<?php elseif(@$AGENT_TYPE == "CEO") : //CEO menu ?>
					<li><a href="/ceo/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/admin/campaigns.php" style="padding:8px 16px;">Campaigns</a></li>
					<li><a href="/ceo/admin.php" style="padding:8px 16px;">Administrators</a></li>
					<li><a href="/admin/managers.php" style="padding:8px 16px;">Managers</a></li>
					<li><a href="/admin/users.php" style="padding:8px 16px;">Users</a></li>
					<!-- <li><a href="/admin/settings.php" style="padding:8px 16px;">Settings</a></li> -->
					<li><a href="/ceo/purchases.php" style="padding:8px 16px;">Purchases</a></li>
					<li><a href="/ceo/revenue.php" style="padding:8px 16px;">Revenue</a></li>
					<li><a href="/ceo/taxes.php" style="padding:8px 16px;">Taxes</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning-center">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms-of-use">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
				<?php endif; ?>
			</ul>
			<!-- END MOBILE -->
		</div>
	</div><!-- /.container-fluid -->
</nav>
