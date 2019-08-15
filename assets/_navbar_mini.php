<nav class="navbar navbar-default navbar-mini" style="padding-top:0px;background-color: red;">
	<div class="container-fluid" style="padding:0;">
	
		<div class="dropdown">
		
			<div class="col-sm-2 text-center visible-sm visible-md visible-lg" style="padding-top:5px; font-size:52px;">
				
				<a href="/home" style="position:relative; z-index:100;">
					<img class="main-logo" src="/img/adrocketlogo-002.png" height="60" alt="" />
				</a>
				
				
			</div>
		
		
			<div class="col-sm-2 text-center visible-xs" style="padding-top:5px; font-size:52px;">
				
				<a href="/home" style="position:relative; z-index:100;">
					<img class="main-logo" src="/img/adrocketlogo-002.png" height="60" alt="" />
				</a>
				<div style="height:30px; width:100%; background-color:red; position:absolute; top:0; right:0; z-index:1;"></div>
				
			</div>
			
			<button class="btn btn-default dropdown-toggle hidden-lg" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="position:absolute; top:56%; right:10px;">
				<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
			</button>
		

      
			<div class="col-sm-10" style=" /*margin-top:40px;*/">
				<!-- <a href="#"><img class="img-responsive hidden-xs" src="/img/adr728x90.jpg" alt="" style="float:right;"/></a><br /> -->
	
				<div id="main-nav" class="navbar navbar-default visible-lg-inline" style="margin-top:8px;float:left; margin-bottom:0; display:inline-block; box-shadow:none; border:none;">

          <?php if(@$AGENT_TYPE == "GUEST") : //user menu for non-logged in users ?>
					
					<ul class="nav navbar-nav">
						 <!-- <li><a href="/terms">Terms of Use</a></li> -->
						<!-- <li><a href="/privacy">Privacy Policy</a></li> -->
						<!-- <li><a href="/privacy">ROI Calculator</a></li> -->
					</ul>
					
					<?php elseif(@$AGENT_TYPE == "USER") : //general user menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/user/dashboard.php">&nbsp;Dashboard</a></li>
              <li><a href="/privacy">ROI Calculator</a></li>
						</ul>
					
					<?php elseif(@$AGENT_TYPE == "MANAGER") : //Manager menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/manager/dashboard.php">&nbsp;Dashboard</a></li>
						</ul>
						
					<?php elseif(@$AGENT_TYPE == "ADMIN") : //Admin menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/admin/dashboard.php">Dashboard</a></li>
						</ul>
						
						
					<?php elseif(@$AGENT_TYPE == "CEO") : //CEO menu ?>
						<ul class="nav navbar-nav">
							<li><a href="/ceo/dashboard.php">Dashboard</a></li>
						</ul>
					<?php endif; ?>
				</div>
			
				<!-- SIGN IN/OUT AREA -->
        <!--
				<?php if(@$AGENT_TYPE != "GUEST") : ?>
					<p class="pull-right" style="margin-bottom:0; position:absolute;/* bottom:0;*/ right:0; color:red; font-weight:700; padding-bottom:8px;">Logged in as: <a href="/user/user-profile.php"><?php echo $CURRENT_USER['fullname']; ?></a>&nbsp;&nbsp;<a href="/index.php?action=logoff">Sign Out</a></p>
				<?php else : ?>
					<ul class="nav nav-pills pull-right visible-lg-block" style="margin-bottom:0; position:absolute; /*bottom:0;*/ right:0;">
						<li role="presentation"><a href="/user/signup.php">Sign Up</a></li>
						<li role="presentation"><a href="/user/signin.php">Sign In</a></li>
					</ul>
				<?php endif; ?>
        -->
			</div>
		
			<!-- MOBILE -->
			
			
			<ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1" style="top:150%;">
		
				<?php if(@$AGENT_TYPE == "GUEST") : //mobile menu for non-logged in users ?>
					<li><a href="/user/signup.php">Sign up</a></li>
					<li><a href="/user/signin.php">Sign in</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
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
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					<li><a href="/index.php?action=logoff">Sign out</a></li>
					
				<?php elseif(@$AGENT_TYPE == "MANAGER") : //Manager menu : ?>
					<li><a href="/manager/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/manager/acquire.php" style="padding:8px 16px;">Acquire</a></li>
					<li><a href="/manager/campaigns.php" style="padding:8px 16px;">My Campaigns</a></li>
					<li><a href="/manager/history.php" style="padding:8px 16px;">Campaign History</a></li>
					<li class="active"><a href="/index.php">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					
				<?php elseif(@$AGENT_TYPE == "ADMIN") : //Admin menu ?>
					<li><a href="/admin/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/admin/campaigns.php" style="padding:8px 16px;">Campaigns</a></li>
					<li><a href="/admin/managers.php" style="padding:8px 16px;">Managers</a></li>
					<li><a href="/admin/users.php" style="padding:8px 16px;">Users</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
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
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
				<?php endif; ?>
			</ul>
			<!-- END MOBILE -->
      
			<button class="btn btn-default dropdown-toggle hidden-md hidden-s hidden-xs" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="position:absolute; top:56%; right:10px; background-color: red;color: white; border: none;">
				Menu <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
			</button>
      
			<ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu2" style="top:150%;">
		
				<?php if(@$AGENT_TYPE == "GUEST") : //mobile menu for non-logged in users ?>
					<li><a href="/user/signup.php">Sign up</a></li>
					<li><a href="/user/signin.php">Sign in</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
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
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					<li><a href="/index.php?action=logoff">Sign out</a></li>
					
				<?php elseif(@$AGENT_TYPE == "MANAGER") : //Manager menu : ?>
					<li><a href="/manager/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/manager/acquire.php" style="padding:8px 16px;">Acquire</a></li>
					<li><a href="/manager/campaigns.php" style="padding:8px 16px;">My Campaigns</a></li>
					<li><a href="/manager/history.php" style="padding:8px 16px;">Campaign History</a></li>
					<li class="active"><a href="/index.php">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
					
				<?php elseif(@$AGENT_TYPE == "ADMIN") : //Admin menu ?>
					<li><a href="/admin/dashboard.php" style="padding:8px 16px;">Dashboard</a></li>
					<li><a href="/admin/campaigns.php" style="padding:8px 16px;">Campaigns</a></li>
					<li><a href="/admin/managers.php" style="padding:8px 16px;">Managers</a></li>
					<li><a href="/admin/users.php" style="padding:8px 16px;">Users</a></li>
					<li class="active"><a href="/home">Home</a></li>
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
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
					<!-- <li><a href="/learning">Learning Center</a></li> -->
					<!-- <li><a href="/about">About</a></li> -->
					<li><a href="/terms">Terms of Use</a></li>
					<li><a href="/privacy">Privacy Policy</a></li>
				<?php endif; ?>
			</ul>
      
		</div>
	</div><!-- /.container-fluid -->
</nav>
