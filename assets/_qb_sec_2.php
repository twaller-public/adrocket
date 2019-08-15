<div class="row quick-build-section2 quick-build-section1" style="/*display:none; */position:relative;background-color:rgba(0,0,0,0);">
	<div class="col-md-2"></div>
	
	<div class="col-md-8" style="/*margin-left:-15px;*/">
		<div class="panel quick-build-wrap" style="margin:30px 0px; /*position:relative;*/min-height:400px; border:1px solid #CCC; border-radius:2px; /*box-shadow:none;*/">
			<div class="panel-body">
				<h2 style="font-size:30px; text-decoration:none;">
					<strong>
						<img src="/img/adrtext.png" height="25" width="auto" style="padding:0 0 5px 0;" /> 
						<img src="/img/image-logo-google-ads.png" height="35" width="auto" style="padding:0 0 5px 0;" /> 
						<!-- <span>Google Ads</span> -->
						<span class="pull-right" style="font-size:16px;margin:10px 0;"><em>Not sure what you should be spending? Use our Ads Click calculator to figure out your budget!</em></span>
					</strong>
				</h2>
				
				<form action="?" method="POST">
				
					
				
					<input type="hidden" name="vendor-num" id="vendor-num" value="" />
					
				
					
					<div class="col-md-5">
					
						<!-- Industry -->
						<div class="form-group">
							<h3><label>What are you Advertising?</label></h3>
							<select class="form-control" id="industry" name="industry">
								<?php echo $indOptionHTML; ?>
							</select>
						</div>
						
						<!-- Province -->
						<div class="form-group">
							<h3><label>Where are you Advertising?</label></h3>
							<select class="form-control" id="province" name="province">
								<?php echo $proOptionHTML; ?>
							</select>
						</div>
						
						<!-- City -->
						<div class="form-group">
							<select class="form-control" id="city" name="city" style="margin-bottom:2px;">
								<option value="0">-- City --</option>
							</select>
							<p style="font-size:14px; margin:2px 0;"><em>You can add more cities later in the process.</em></p>
						</div>
						
						<p style="padding:6px;border:2px solid #337ab7; border-radius:4px; display:inline-block;"><a href="/user/dashboard.php" style=""><strong>Already started?<br />Click Here</strong></a></p>
					</div>
					
					
					
					
					<!-- Budget --> <?php //display CPM (clicks) is budget x six ?>
					<div class="col-md-7" style="position:relative;">
				
						<div class="col-lg-8 col-md-offset-2 err-box center-text" style="padding:0;">

							<h3><label>What is your Monthy Budget?</label></h3>
							
							<div class="budget-slider"></div><br />
							<div class="budget-scales" style="height:32px; margin-top:-15px">
								<span class="label label-danger pull-left" style="font-size:16px;margin-left:-10px;">250</span>
								<span class="label label-danger pull-right" style="font-size:16px;margin-right:-15px;">10,000</span>
							</div>
						</div>	
						
						<div class="col-lg-2"></div>
						
						<div class="col-lg-8 col-md-offset-2 err-box center-text" style="padding:0;">
							<div class="col-lg-12 text-right" style="padding:0;">
								<div style="display:inline-block; width:150px;">
									<div class="input-group" style="/*min-width:130px; max-width:140px;*/margin-top:5px;">
										<div class="input-group-addon" style="padding:6px;">$</div>
										<input 
											class="form-control lead-calc"
											id="budget"
											type="text" 
											name="budget" 
											value="" 
											style="margin-top:0px; padding:5px 8px; text-align:right;"
										/>
										<div class="input-group-addon" style="padding:6px;">.00</div>
									</div>
								</div>
							</div>
							
							<!-- visit estimate -->
							<div class="col-lg-12" style="padding:0;">
								<span style="font-size:24px;"><label style="padding-top:10px;">Website Traffic/Visits:</label></h3>
								<span class="pull-right" id="expected-clicks" style="text-decoration:none; font-size:40px; color: #C00; font-weight: bold;">&nbsp;0</span>&nbsp;&nbsp;&nbsp;
							</div>
							
							
							<div class="col-md-12">
								<br />
								<!-- <p style="font-size:16px;"><em>Not sure what you should be spending? Use our <a href="#">ROI calculator</a> to figure out your budget!</em></p> -->
							</div>
						</div>
						
						<div class="col-lg-2"></div>
						
						<div class="col-md-11">
							<?php if(!@$_SESSION["CAPTCHA_VERIFIED"]) : ?>
								<span style="display:inline-block; float:right;"><?php echo $GLOBALS["CAPTCHA_TAG"]; ?></span>
							<?php endif; ?>
						</div>
						
						<div class="col-md-11 text-right" style="padding-right:0px;">
						
							<button class="launch-button text-center" type="submit" name="qb-submit" value="1" style="padding:5px 10px; background-color:rgba(255, 255, 255, 0.5); border-radius:4px; border:1px solid #333;">
								<div style="display:inline-block; height:60px; width:147px; background-image:url(/img/adr-cropped.png);"></div>
								<div style="display:inline-block; color:red; font-size:18px; font-weight:bold;/*height:60px;*/ width:147px;">CLICK HERE<br />TO GET<br />STARTED</span>
							</button>
						
							<!--
							<div class="launch-button text-right" style="display:inline-block;cursor:pointer; width:177px; margin:auto; padding:5px 10px; background-color:rgba(255, 255, 255, 0.5); border-radius:4px; border:1px solid #333;" onClick="$(this).closest('form').submit; return false;">
								<div style="display:inline-block; height:60px; width:147px; background-image:url(/img/adr-cropped.png);"></div><br />
								<span style="color:red; font-size:18px; font-weight:bold;">LAUNCH CAMPAIGN</span>
							</div>
							-->
						</div>
						
					</div>
					

					
					
					
					
					
					
				
				</form>
			</div>
			
			<div id="qb-loading" class="loading text-center" style="position:absolute; width:100%; height:100%; top:0; left:0; background-color:rgba(155,155,155,0.4); padding-top:150px;">
				<img src="/img/spinner.gif" alt="" style="width:200px; height:200px;"/>
			</div>
			
			
			
		</div>
		
	</div>
	
	<div class="col-md-2"></div>
</div>