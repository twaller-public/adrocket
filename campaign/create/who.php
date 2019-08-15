<?php
/*

	The viewer page for the vendor selection in the Campaign Creation Wizard Process
	
*/


	require_once "_who_ctrl.php";
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
			<div class="col-md-12" id="ec-process-header-1" style="opacity:0; height:0px;">
				<h2 style="font-size:35px;">How Would You Like To Advertise?</h2>
			</div>
			
			
			<!-- main input section -->
			<div class="col-md-12" id="ec-process-input-1" style="opacity:0;/*height:0px;*/">

			
				<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
				
				
				
				<!-- vendor choice interface -->
				<div class="panel vendor-select">
				
				
					<div class="panel-body">

					
						<!-- page form -->
						<form action="?" method="POST">
						
						
							<!-- vendor num hidden input -->
							<input type="hidden" name="vendor-num" id="vendor-num" value="" />
						
						
							<!-- ad selection -->
							<div class="col-md-12 ad-type-wrap">
								<h3><label>Choose Your Ad Type</label></h3>
								
								
								<!-- Icons for ad-type -->
								<div class="col-md-12 text-center">
								
								
									<!-- Google Search -->
									<div class="ad-type" data-type="search" data-num="1">
										<img src="<?php echo $search_logo; ?>" alt="Search Network" height="100" width="100" /><br /><br />
										<p><strong>Google <br />Ads</strong></p>
									</div>
									
									
									<!-- Google Display -->
									<div class="ad-type" data-type="display" data-num="2" style="margin:0px 20px;">
										<img src="<?php echo $display_logo; ?>" alt="Display Network" height="100" width="100" /><br /><br />
										<p><strong>Google <br />Display Network</strong></p>
									</div>
									
									
									<!-- Google Remarketing -->
									<div class="ad-type" data-type="remarket" data-num="7">
										<img src="<?php echo $remarket_logo; ?>" alt="Remarketing Network" height="100" width="100" /><br /><br />
										<p><strong>Google <br />Remarketing</strong></p>
									</div>
									
									
									
									
									
									<!-- descriptive text for ad-type -->
									<div class="col-md-8 col-md-offset-2 text-left vendor-details">
									
									
										<!-- selection -->
										<h3 class="selected-vendor user-info">Selected: <span class="ad-selection">None</span></h3>
										
										
										<!-- Google Search -->
										<p class="explanation search" style="display:none;">
											<span class="vendor-title">Google Search Network:</span>
											<span>The Google search network shows your advertisement on the Google search results page when users look for your product or service by searching Google.</span>
										</p>
										
										
										<!-- Google Display -->
										<p class="explanation display" style="display:none;">
											<span class="vendor-title">Google Display Network:</span>
											The Google display network shows your banners ads on the google display network. Your banner ads will appear on Google display network web sites that match the type of industry you are advertising.
										</p>
										
										
										<!-- Google Remarketing -->
										<p class="explanation remarket" style="display:none;">
											<span class="vendor-title">Google Remarketing:</span>
											Google remarketing ads will re-target users that have visited your site and show them your banner ad when they visit related google network web sites.
										</p>
										
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
				
				
				<!-- submit button -->
				<div class="form-group text-right">
				
					<input 
						class="btn btn-lg btn-danger" 
						type="submit" 
						name="Continue" 
						value="Continue" 
					/>
					
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
	var fast_animation       = <?php echo $errors? 1 : 0; ?>;        //do we cause some animations to speed up

	//page specific
	var vendor_num = <?php echo ($vendor)? $vendor : 0; ?>;
	
	fast_animation = fast_animation || vendor_num;

</script>


<script src="/js/wizard_common_temp.js"></script>
<script src="/js/wizard_vendor.js"></script>


</body>
</html>