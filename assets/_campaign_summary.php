<div class="row summary-sections">

	<?php if($AGENT_TYPE == "ADMIN" || $AGENT_TYPE == "CEO"): ?>
		<!-- set the user for this campaign only is admin or CEO -->
		<div class="col-sm-12 ad-previews">
			<h3>Admin Section</h3>
			
			<form class="form-horizontal" id="admin-form" action="/campaign/view.php" method="POST">
			
				<input type="hidden" name="admin-edit-cpn" value="1" />
				<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />

					
				<!-- User -->
				<div class="form-group">
					<label for="fname" class="col-md-4 control-label">Campaign Owner</label>
					<div class="col-md-4">
						<select class="form-control" name="cpnUser" style="font-size:21px; height:42px;">
							<?php foreach($users as $u) : ?>
							
								<option value="<?php echo $u->get("num"); ?>" <?php if($userNum == $u->get("num")) echo " selected"; ?>><?php echo $u->get("num"); ?> - <?php echo $u->get("username"); ?> - <?php echo $u->get("email"); ?></option>
							<?php endforeach; ?>
						</select>
					
					</div>
				</div>
				
				
				<!-- Force Campaign Status-->
				<div class="form-group">
					<label for="cpnStatus" class="col-md-4 control-label">Force Campaign Status</label>
					<div class="col-md-4">
						<select class="form-control" name="cpnStatus" style="font-size:21px; height:42px;">
							<?php foreach($cpnStatus as $i=>$s) : ?>
							
								<option value="<?php echo $i; ?>" <?php if($cpn_status == $i) echo " selected"; ?>><?php echo $i; ?> - <?php echo $s; ?></option>
							<?php endforeach; ?>
						</select>
					
					</div>
				</div>
				
				<!-- Force Admin Status-->
				<div class="form-group">
					<label for="admStatus" class="col-md-4 control-label">Force Admin Status</label>
					<div class="col-md-4">
						<select class="form-control" name="admStatus" style="font-size:21px; height:42px;">
							<?php foreach($admStatus as $i=>$s) : ?>
							
								<option value="<?php echo $i; ?>" <?php if($adm_status == $i) echo " selected"; ?>><?php echo $i; ?> - <?php echo $s; ?></option>
							<?php endforeach; ?>
						</select>
					
					</div>
				</div>
				
				
				<!-- submit -->
				<div class="form-group">
					<div class="col-md-offset-4 col-md-10">
						<button type="submit" class="btn btn-danger" name="amin-edit-submit" value="Submit">Submit</button>
					</div>
				</div>

			
			</form>
			
		</div>
	<?php endif; ?>
	
	
	
	
	<!-- General Info -->
	<div class="col-sm-12 general-info">
		<h3>
			<span style="margin-right:20px;">
				<button id="startHere" class="btn btn-success" onClick="location.href = '/campaign/create/title.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			<?php 
				//if($fresh_campaign) :
				//echo "<span class='start-here alert alert-danger' style='font-size:14px;margin:0px 10px;'><strong>" . $GLOBALS["ADROCKET_DEFINITIONS"]["Fresh Campaign Text"] . "</strong></span>"; 
				//else echo "&nbsp;&nbsp;";
			?>
			
			<?php //endif; ?>
			General Information
			
		</h3>
		<hr />
		
		<div class="col-sm-10">
		
			<!-- Title -->
			<div class="summary-row">
				<label class="title-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Title Label"]; ?>:</label>
				<span class="title-value pull-right"><?php echo ($title? $title : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
			

			<!-- Budget -->
			<div class="summary-row">
				<label class="budget-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Budget Label"]; ?>:</label>
				<span class="budget-value pull-right"><?php echo ($budget? "$".$budget : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
				<br />
			</div>
			
			
			<!-- Leads -->
			<div class="summary-row">
			<label class="leads-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Leads Label"]; ?>:</label>
			<span class="leads-value pull-right"><?php echo ($leads?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Leads Text"]); ?></span>
			<br />
			</div>
		</div>
	</div>
	
	
	
	
	<!-- Industry Info -->
	<div class="col-sm-12 industry-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/what.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Industry
		</h3>
		<hr />
		
		<div class="col-sm-10">
	
			<!-- Industry -->
			<div class="summary-row">
				<label class="industry-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Industry Label"]; ?>:</label>
				<span class="industry-value pull-right"><?php echo ($industry? $industryTitle : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
				<br />
			</div>
		</div>
	</div>
	
	
	
	
	<!-- Locations Info -->
	<div class="col-sm-12 locations-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/where.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Locations
		</h3>
		<hr />
		<div class="col-sm-10">
			<?php
				
				$locationsList = "";
				foreach($locations["locations"] as $l) $locationsList .= $l->get("location:label") . ", ";
				$locationsList = trim($locationsList, ", ");
				//echo $locationsList;
			?>
					
			<div class="summary-row">
				<label class="locations-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Locations Label"]; ?>:</label><br />
				<span class="locations-value pull-right"><?php echo ($locationsList? $locationsList : $GLOBALS["ADROCKET_DEFINITIONS"]["No Chosen Locations Text"]); ?></span>
				<br />
			</div>
		</div>
	</div>
	
	
	
	
	<!-- Vendor Info -->
	<div class="col-sm-12 vendor-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/who.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Vendor
		</h3>
		<hr />
		
		<div class="col-sm-10">
	
			<!-- Vendor -->
			<div class="summary-row">
				<label class="vendor-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Vendor Label"]; ?>:</label>
				<span class="vendor-value pull-right"><?php echo ($vendor? $vendorTitle : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]); ?></span>
				<br />
			</div>
		</div>
	</div>
	
	
	
	
	<!-- Ads -->
	<div class="col-sm-12 ad-previews">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/ads.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Your Ads
		</h3>
		<hr />
		
		<div class="all-ads-wrap col-sm-12">
			<?php if(!$vendor): ?>
				<div class='alert alert-warning'><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Vendor Not Set Text"]; ?></div>
			<?php else: ?>
				<?php if(!$ads): ?><div class='alert alert-primary'><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Created Text"]; ?></div>
				<?php else: ?>
				
					
					<!-- Adwords Previews -->
					<div class="adwords-previews previews" style="max-width:400px;margin:auto;<?php if(!$adwords) echo " display:none"; ?>">
						<h4><?php echo $GLOBALS['ADROCKET_DEFINITIONS']['Adwords Section']; ?></h4>
						
						
						
						<div class="adwords-previews-wrap text-center">
						
							<div class="adwords-preview-template text-left" data-num="0" style="display:none;padding:10px;">
							
								<!-- ad headlines -->
								<div class="headlines">
									<span class="adword-preview headline" data-val="headline_1"></span> - 
									<span class="adword-preview headline" data-val="headline_2"></span> -
									<span class="adword-preview headline" data-val="headline_3"></span> 
                  
								</div>
								
								<!-- url and phone -->
								<div class="url-line">
									<span title=""><span class="adword-preview" data-val="disp_url"></span></span>
									
									<!-- phone number (optional) -->
									<span class="phone-ext" style="display:none;">
										<span class="adword-preview phone" data-val="phonenum"></span>
									</span>
								</div>
								
								<!-- description -->
								<div class="desc">
									<p class="adword-preview" data-val="description"></p>
									<p class="adword-preview" data-val="description_2"></p>
								</div>
								
								<!-- callouts -->
								<div class="callouts-ext" style="display:none;">
									<ul class="google-search-callouts" style="font-size:16px;">
										<li class="callout adword-preview" data-val="callout_1"></li>
										<li class="callout adword-preview" data-val="callout_2"></li>
										<li class="callout adword-preview" data-val="callout_3"></li>
										<li class="callout adword-preview" data-val="callout_4"></li>
									</ul>
								</div>
							
							
								<!-- sitelinks & sitelink descriptions -->
								<div class="sitelinks-ext" style="display:none;">
									<ul class="google-search-sitelinks">
									
									
										<!-- sitelink 1 -->
										<li class="sitelink">
										
											<span class="adword-preview" data-val="sitelink_1"></span>
											
											<!-- descriptions-->
											<div class="sitelink-desc-ext" style="display:none;">
												<p class="sl-desc1 adword-preview" data-val="sitelink1_desc_1"></p>
												<p class="sl-desc2 adword-preview" data-val="sitelink1_desc_2"></p>
											</div>
										</li>
										
										
										<!-- sitelink 2 -->
										<li class="sitelink">
										
											<span class="adword-preview" data-val="sitelink_2"></span>
											
											<!-- descriptions-->
											<div class="sitelink-desc-ext" style="display:none;">
												<p class="sl-desc1 adword-preview" data-val="sitelink2_desc_1"></p>
												<p class="sl-desc2 adword-preview" data-val="sitelink2_desc_2"></p>
											</div>
										</li>
										
										
										<!-- sitelink 3 -->
										<li class="sitelink">
										
											<span class="adword-preview" data-val="sitelink_3"></span>
											
											<!-- descriptions-->
											<div class="sitelink-desc-ext" style="display:none;">
												<p class="sl-desc1 adword-preview" data-val="sitelink3_desc_1"></p>
												<p class="sl-desc2 adword-preview" data-val="sitelink3_desc_2"></p>
											</div>
										</li>
										
										
										<!-- sitelink 4 -->
										<li class="sitelink">
										
											<span class="adword-preview" data-val="sitelink_4"></span>
											
											<!-- descriptions-->
											<div class="sitelink-desc-ext" style="display:none;">
												<p class="sl-desc1 adword-preview" data-val="sitelink4_desc_1"></p>
												<p class="sl-desc2 adword-preview" data-val="sitelink4_desc_2"></p>
											</div>
										</li>
									</ul>
								</div>
							
							</div>
						</div>
						
						
						
						<div class="preview-badges text-center">
							<?php foreach($adwords as $index=>$a) echo "<span class='label label-primary' data-num='".($index+1)."'>".($index+1)."</span>&nbsp;"; ?>
						</div>
					</div>
					
					
					
					
					
					<!-- Display Previews -->
					<div class="display-previews previews" style="<?php if(!$display) echo "display:none"; ?>">
						<h4><?php echo $GLOBALS['ADROCKET_DEFINITIONS']['Display Ad Section']; ?></h4>
						<img class="img-responsive display-ad-img-preview" src="<?php echo $display_preview_img; ?>" alt="" />
						<label class="destination_url-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["URL Destination Label"]; ?>:</label>
						<span class="destination_url-value pull-right"><?php echo $display_url; ?></span>
					</div>
					
					
					
					
					
					<!-- Remarketing Previews -->
					<div class="remarketing-previews previews" style="<?php if(!$remarket) echo "display:none"; ?>">
						<h4><?php echo $GLOBALS['ADROCKET_DEFINITIONS']['Remarketing Section']; ?></h4>
						<img class="img-responsive remarket-ad-img-preview" src="<?php echo $remarket_preview_img; ?>" alt="" />
					</div>
				
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	
	
	
	<?php if(@$vendor == 1) : ?>
	<!-- Keywords Info -->
	<div class="col-sm-12 keywords-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/additional.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Your Keywords
		</h3>
		<hr />
		
		<div class="col-sm-10">
			<?php
				//if the industry is not set 
				if(!$industry) echo "<div class='alert alert-warning'>" . $GLOBALS["ADROCKET_DEFINITIONS"]["Industry not set text"] . "</div>";
				else { 
					//showme($keywords);
					$keywordsList       = implode(",<br /> ", (explode(",", $keywords->get("keywords"))));
					$additionalKeywords = implode(",<br /> ", (explode(",", $keywords->get("additional_keywords"))));
					$negKeywordList     = implode(",<br /> ", (explode(",", $keywords->get("negative_keywords"))));
					$defKeywordList     = implode(",<br /> ", (explode(",", $keywords->get("default_keywords"))));
					$unudefKeywordList  = implode(",<br /> ", (explode(",", $keywords->get("unused_defaults"))));
					
					$keywordsList      = trim($keywordsList, ", ");
					$keywordsList      = trim($keywordsList, ",");
					$negKeywordList    = trim($negKeywordList, ", ");
					$defKeywordList    = trim($defKeywordList, ", ") . trim($additionalKeywords, ", ");
					$unudefKeywordList = trim($unudefKeywordList, ", ");
				?>
					<div class="summary-row">
						<label class="default_keywords-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Default Keywords Label"]; ?>:</label><br />
						<span class="default_keywords-value pull-right"><?php echo $defKeywordList?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Default Keywords Text"]; ?><hr /></span>
						<div style="clear:both;">&nbsp;</div>
					</div>
					
					<div class="summary-row">
						<label class="unused_defaults-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Unused Defaults Label"]; ?>:</label><br />
						<span class="unused_defaults-value pull-right"><?php echo $unudefKeywordList?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Unused Defaults Text"]; ?><hr /></span>
						<div style="clear:both;">&nbsp;</div>
					</div>
				
					<div class="summary-row">
						<label class="keywords-label"><?php echo "Your Custom Keywords"; ?>:</label><br />
						<span class="keywords-value pull-right"><?php echo $keywordsList?: "No Custom Keywords Have Been Chosen."; ?><hr /></span>
						<div style="clear:both;">&nbsp;</div>
					</div>
					
					<div class="summary-row">
						<label class="negative_keywords-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Negative Keywords Label"]; ?>:</label><br />
						<span class="negative_keywords-value pull-right"><?php echo $negKeywordList?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Negative Keywords Chosen text"]; ?><hr /></span>
						<br />
					</div>
					
				<?php } 
			?>
		</div>
		
	</div>
	<?php endif; ?>
	
	
	
	
	<!-- Days & Times Info -->
	<div class="col-sm-12 daytime-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/when.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Days &amp; Times
		</h3>
		<hr />
		
		<div class="col-sm-10">
		
			<?php //showme($daystimes); ?>
			<!-- date("l, F j Y", strtotime($daystimes->get("start_date"))) -->
		
			<!-- Start -->
			<div class="summary-row">
				<label class="start_date-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Start Date Label"]; ?>:</label>
				<span class="start_date-value pull-right"><?php echo (!in_array($daystimes->get("start_date"), array(null, "0000-00-00 00:00:00")))? date("l, F j Y", strtotime($daystimes->get("start_date"))) : $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<br />
			</div>
			
			<!-- End -->
			<div class="summary-row">
				<label class="end_date-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["End Date Label"]; ?>:</label>
				<span class="end_date-value pull-right">
					<?php 
						
						if(!in_array($daystimes->get("end_date"), array(null, "0000-00-00 00:00:00"))){echo date("l, F j Y", strtotime($daystimes->get("end_date"))); }
						else if(@$daystimes->get("recur")){ echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recurring Modal Label"]; }
						else{ echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; }
					?>
				</span>
				<br />
			</div>

			
			<!-- Expiry Notify -->
			<div class="summary-row">
				<label class="expiry_notification-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Expiry Notification Label"]; ?>:</label>
				<span class="expiry_notification-value pull-right"><?php echo $daystimes->get("expiry_notification:text")?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<br />
			</div>
			
			<!-- Day Parting -->
			<div class="summary-row">
				<label class="day_parting-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Day Parting Label"]; ?>:</label>
				<span class="day_parting-value pull-right"><?php echo $daystimes->get("day_parting:text") ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<br />
			</div>
		</div>
		
		<?php if($dayparting && $daystimes->get("day_parting")) : ?>
			<div class="col-sm-8">
				<table class="table table-striped dp_times_wrap">
					<tr>
						<th></th>
						<th>Start</th>
						<th>End</th>
					</tr>
					<?php echo $dp_table; ?>
				</table>
			</div>
		<?php endif; ?>
		
	</div>
	
	
	
	
	<!-- Contact Info -->
	<div class="col-sm-12 contact-info">
		<h3>
			<span style="margin-right:20px;">
				<button class="btn btn-success" onClick="location.href = '/campaign/create/contact.php?num=<?php echo $cpnNum; ?>&summ=true'">Edit</button>
			</span>
			Contact Information
		</h3>
		<hr />
		
		<div class="col-sm-10">
		
			<!-- Name -->
			<div class="summary-row">
				<label class="contact_name-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Contact Name Label"]; ?>:</label>
				<span class="contact_name-value pull-right"><?php echo $c_name ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
			
			<!-- Phone -->
			<div class="summary-row">
				<label class="contact_phone-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Contact Phone Label"]; ?>:</label>
				<span class="contact_phone-value pull-right"><?php echo $c_phone ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
			
			<!-- Email -->
			<div class="summary-row">
				<label class="contact_email-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Contact Email Label"]; ?>:</label>
				<span class="contact_email-value pull-right"><?php echo $c_email ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
			
			<!-- Company -->
			<div class="summary-row">
				<label class="contact_company-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Contact Company Label"]; ?>:</label>
				<span class="company_name-value pull-right"><?php echo $comp_name ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
			
			<!-- Comments -->
			<div class="summary-row">
				<label class="comments-label"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Comments Label"]; ?>:</label><br />
				<span class="comments-value pull-right"><?php echo $comments ?: $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"]; ?></span>
				<div style="clear:both;">&nbsp;</div>
			</div>
		</div>
	</div>
	

</div>