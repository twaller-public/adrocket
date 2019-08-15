<?php
	/**
	* Breadcrumbs for the create campaign 'wizard' process
	*
	*/

	
	//these variables are supposed to be set on the calling page
	
	$bc_title               = @$cpnTitle;
	
	$bc_leads               = @$cpnLeads?: 0;
	
	$bc_budget              = @$cpnBudget;
	
	$bc_industry            = @$industryTitle;
	
	$bc_locations           = @$locationTitles;
	
	$bc_vendor              = @$vendorTitle;
	
	$bc_start               = (@$start == "0000-00-00 00:00:00")? false : date("D, M jS, Y", strtotime($start));
	
	$bc_ads                 = (@$vendor_ads)? count($vendor_ads) : false;
	
	$bc_additional          = @$additional;
	
	$bc_contact             = @$contact["contact"]? false : true;
	
	$additional_settings    = $bc_additional? true : false;
	
	
?>

<div class="col-md-12">

	<h1 style="font-size:45px;">Create Campaign</h1>
	
</div>

<div class="col-md-12 crumbwrap">
	<ol class="breadcrumb">
	
	
		<!-- Review & Checkout 
		<li>
			
			<?php //if(empty(campaign_isReadyForCheckout($campaign))) : ?>
				<a class="readyforcheckout" href="/campaign/create.php?num=<?php //echo $cpnNum; ?>">Review &amp; Checkout</a>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span>&nbsp;&nbsp;Ready for Review</span>
					
				</div>
	
			<?php //else : ?>
				<a href="/campaign/create.php?num=<?php //echo $cpnNum; ?>">Review &amp; Checkout</a>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span>In Progress&nbsp;&nbsp;</span>
					
				</div>
			
			<?php //endif; ?>
			
		</li>
		<br />
		-->
	
		<!-- Title and Budget -->
		<li>
			<a href="/campaign/create/title.php?num=<?php echo $cpnNum; ?>">Title, Budget &amp; Clicks</a>
			<?php if($bc_title && $bc_budget) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="title_value"><?php echo $bc_title; ?>&nbsp;&nbsp;</br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo "\$$bc_budget/$bc_leads"; ?></span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="industry_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php endif; ?>
		</li>
	
	
		<!-- Industry -->
		<li>
			<a href="/campaign/create/what.php?num=<?php echo $cpnNum; ?>">Industry</a>
			<?php if($bc_industry) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="industry_value"><?php echo $bc_industry; ?>&nbsp;&nbsp;</span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="industry_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php endif; ?>
		</li>
		
		
		<!-- Locations -->
		<li>
			<a href="/campaign/create/where.php?num=<?php echo $cpnNum; ?>">Locations</a>
			<?php if($bc_locations) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="location_value">
					
						<?php 
						
							$c = count($bc_locations);
						
							foreach($bc_locations as $index=>$l){
								
								if($index == 4){
									
									echo "...";
									break;
								}
								if($index == ($c - 1)) echo "$l";
								else echo "$l, ";
							} 
						?>
						
						&nbsp;&nbsp;
					</span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="location_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php endif; ?>
		</li>
		
		
		<!-- Vendor 
		<li>
			<a href="/campaign/create/who.php?num=<?php //echo $cpnNum; ?>">Vendor</a>
			<?php //if($bc_vendor) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="_value"><?php //echo $bc_vendor; ?>&nbsp;&nbsp;</span>
					
				</div>
			<?php //else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php //endif; ?>
		</li>
		-->
		
		
		<!-- Start Date -->
		<li>
			<a href="/campaign/create/when.php?num=<?php echo $cpnNum; ?>">Days &amp; Times</a>
			<?php if($bc_start) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="_value"><?php echo $bc_start; ?>&nbsp;&nbsp;</span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php endif; ?> 
		</li>
		
		
		<!-- Ads -->
		<li>
			<a href="/campaign/create/ads.php?num=<?php echo $cpnNum; ?>">Submit Ads</a>
			<?php if($bc_ads) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="_value">Complete&nbsp;&nbsp;</span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
			<?php endif; ?>
		</li>
		
		
		<!-- Additional Settings (keywords etc.) -->
		<?php if(@$additional_settings) : ?>
			<li>
				<a href="/campaign/create/additional.php?num=<?php echo $cpnNum; ?>">Keywords</a>
				<?php if($bc_additional) : ?>
					<div>
						<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
						<span id="_value">Complete&nbsp;&nbsp;</span>
						
					</div>
				<?php else : ?>
					<div>
						<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
						<span id="_value">In Progress&nbsp;&nbsp;</span>
						
					</div>
				<?php endif; ?>
			</li>
		<?php endif; ?>
		
		
		<!-- Contact -->
		<li>
			<a href="/campaign/create/contact.php?num=<?php echo $cpnNum; ?>">Contact</a>
			<?php if($bc_contact) : ?>
				<div>
					<span class="glyphicon glyphicon-ok-circle" style="font-size:18px; color:#2C2;vertical-align:sub;"></span>
					<span id="_value">Complete&nbsp;&nbsp;</span>
					
				</div>
			<?php else : ?>
				<div>
					<span class="glyphicon glyphicon-exclamation-sign" style="font-size:18px; color:#DD0;vertical-align:sub;"></span>
					<span id="_value">In Progress&nbsp;&nbsp;</span>
					
				</div>
				
			<?php endif; ?> 
		</li>
		
		
		
	</ol>
	<!-- 
	<br />
	<h3 class="countdown" style="font-size:40px; margin-left:20px;">Countdown: <span class="countdown-value"><?php echo $countdown; ?></span></h3>
	-->
</div>