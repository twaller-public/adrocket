<?php
/*

	The viewer page for the location selection in the Campaign Creation Wizard Process
	
*/


	require_once "_where_ctrl.php";
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
				<h2 style="font-size:35px;">Where Would You Like To Advertise?</h2>
			</div>
			
			
			<!-- main input section -->
			<div class="col-md-6 col-md-offset-3 text-center" id="ec-process-input-1" style="opacity:0;/*height:0px;*/">

			
				<?php if(@$errors) : /*display errors*/ ?>
				
					<!-- errors section -->
					<div class="w-err">
						<p><?php echo $error_text; ?></p>
					</div>
					
				<?php endif; ?>
				

				
				<!-- Location text input - initialize map -->
				<div class="input-group map-show-wrap text-center">
				
					<!-- location input -->
					<input 
						class="form-control locations" 
						type="text" 
						name="locations" 
						value="" 
						placeholder="Your Locations" 
						style="margin-top:0;" 
					/>
					
					<!-- submission buttons -->
					<span class="input-group-btn">
						<button class="btn btn-danger map-search" type="button">Submit</button>
					</span>


				</div>
				
        
				<div class="input-group map-show-wrap text-center">

					<span class="input-group-btn">
       			<button class="btn btn-danger map-show" type="button" style="background-color:#AF1c19;">Or Select On Map</button>
					</span>
        </div>
				<!-- Map Canvas Wrapper -->
				<div class="col-md-12 map-wrap text-center" style="padding:0; display:none;">
				
					<label><em>Click on the map below to select your location(s).</em></label>
					<br />
					<br />
				
				
					<!-- map canvas -->
					<div id="map_canvas"></div>
					
					
					
					<div style="margin-top:30px;">
						<p class="map-info"><em>OR, add your location(s) below.</em></p>
					</div>
					
					
					<!-- Location text input - user can't find their location -->
					<div class="input-group other-input text-center" id="other-input" style="margin:0;">
						
						<!-- locations input -->
						<input 
							class="form-control locations" 
							type="text" 
							name="locations-show" 
							value="" 
							placeholder="Example One, Example Two, Etc." 
							style="margin-top:0px;" />
						
						<!-- submission -->
						<span class="input-group-btn">
							<button class="btn btn-danger map-search" type="button">Add</button>
						</span>
					</div>
					
					<p><em>Separate multiple locations with commas.</em></p>
					
					
					<!-- selected locations display -->
					<div class="kw_wrap text-center" style="margin:20px 0;">
						<p style="font-size:24px;">
							<strong>Selected Locations:</strong>
							<span id="selected_locations"></span>
						</p>
						
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

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbF3ra8vtQV2ia8fxQrho93Rx90xBaHsA" async defer></script>

<!-- Page-specific Javascript -->
<script>

	<?php /*js variables set from php; */ ?>
	
	
	//general
	var fast_animation       = <?php echo $errors? 1 : 0; ?>;        //do we cause some animations to speed up
	var cpn_num              = <?php echo $cpnNum; ?>;
	
	//page specific
	var selected_locations   = {<?php foreach($coordinates as $index=>$coords){ echo "\"$index\": " . json_encode($coords) . ","; }?>};
	
	
	fast_animation = fast_animation || !jQuery.isEmptyObject(selected_locations);
	
</script>

<script src="/js/wizard_common_temp.js"></script>
<script src="/js/wizard_locations.js"></script>



</body>
</html>