<?php


	$vendor_headerText = "Create Your Ads on the Google Search Network";
	$continue_onClick  = "return false";
	$adwords_json      = array();
	
	
	
	if($ads["remarketing"]){
		
		$remarket_preview_img = $ads["remarketing"][0]->vals()["user_display_ad"][0]["urlPath"];
		
	}
	if($ads['adwords']){
		
		foreach($ads['adwords'] as $adw){
			
			$vals  = $adw->vals();
			$adNum = $vals['num'];
			
			unset($vals['_link']);
			unset($vals['_filename']);
			unset($vals['_tableName']);
			unset($vals['createdByUserNum']);
			unset($vals['createdDate']);
			unset($vals['dragSortOrder']);
			unset($vals['updatedByUserNum']);
			unset($vals['updatedDate']);
			unset($vals['num']);
			unset($vals['campaign']);
			unset($vals['campaign:label']);
			unset($vals['use_callouts:text']);
			unset($vals['use_phone:text']);
			unset($vals['use_sitelinks:text']);
			unset($vals['use_sitelinkdesc:text']);
			
			array_push($adwords_json, array("adNum" => $adNum, "detail" => $vals));
			//$adwords_json .= json_encode($vals) . ",";
		}
		
		
		
	}
	
	
	$adwords_json = json_encode($adwords_json);
	$showRemarketing = false;
	//echo $adwords_json;

?>

<!-- ad-type header -->
<div class="col-md-12" id="ec-process-header-1" style="opacity:0; height:0px;">
	<h2 style="font-size:35px;"><?php echo $vendor_headerText; ?></h2>
</div>






<!-- current ad list -->
<div class="google-search-ads-list row" style="display:none;">

	<!-- list and controls -->
	<div class="col-sm-6">

		<!-- user ad list -->
		<div class="col-sm-10 ad-list" style="padding:25px; padding-bottom:5px;">
		
			<h3>Your Ads</h3>

			
		</div>
		
		<!-- section template -->
		<div class="ad-edit-line ad-edit-line-template" style="display:none;" data-order="1">
			
			
			<!-- delete button -->
			<span class="pull-right">
				<button class="btn btn-sm btn-danger ad-delete" type="button" data-num="0">Delete</button>
			</span>
			
			
			<!-- edit button -->
			<span class="pull-right">
				<button class="btn btn-sm btn-primary ad-edit" type="button" data-num="0">Edit</button>
			</span>
			
			
		
			<!-- ad headlines -->
			<h2 class="google-search-headline" style="line-height:28px; margin-right:40px; font-size:16px; white-space:nowrap; overflow:hidden; text-overflow: ellipsis;">
				<span class="headline-1"></span>&nbsp;-
				<span class="headline-2"></span>
			</h2>
			
		</div>
		
		<div class="col-sm-12"></div>
	
		<!-- create new ad and ad count line -->
		<div class="col-sm-10" style="padding:0px 20px;">

			<!-- create new ad -->
			<div class="google-search-ad-create text-center">

				<span class="glyphicon glyphicon-plus" style="font-size:18px; margin-bottom:3px; vertical-align:middle;"></span>
				<span class="create-tag" style="font-size:16px;vertical-align:middle;">create new ad</span>
			</div> 
			
			<!-- ad count/limit -->
			<div class="google-search-ad-count text-right">
			
				<span class="count-tag" style="font-size:16px;vertical-align:middle;">
					<span id="ad-count">1</span>/9
				</span>
			</div> 
		</div>
	</div>
	
	<!-- continue button -->
	<form action="?" method="POST">
	
		<input type="hidden" name="num" value="<?php echo $cpnNum; ?>" />
		<input type="hidden" name="summ" value="<?php echo @$_REQUEST['summ']; ?>" />
	
		<div class="col-sm-6" >
		
			<input 
				class="btn btn-lg btn-danger ad-continue" 
				type="submit" 
				name="Continue" 
				value="Continue" 
				style="margin-top:120px;display:none;/* position:fixed;*/"
			/>
			<!--
			<button 
				class="btn btn-lg btn-danger " 
				type="submit" 
				name="Continue" 
				value="Continue" 
				style="margin-top:120px;display:none; position:fixed;">Continue</button>
			-->
		</div>
	</form>
</div>



<!-- Ad editing interface -->
<div class="google-search-ad-creation-interface row">

	<form id="google-search-ad-creation-form" action="?" method="POST" data-num="0">


		<!-- form inputs -->
		<div class="col-md-6">
		
			<h2>Edit Your Ad</h2>
			<hr />
	
		
			<div class="default-input-wrapper">
				
				
				<!-- Final URL destination -->
				<div class="input-group">
				
				
					<span class="input-group-addon">Your Website URL</span>
					
					
					<!-- http/https specification -->
					<select 
						class="form-control col-sm-5" 
						id="http" 
						name="url_prefix" 
						style="/*width:25%;*/"
						title="Does your site use http or https?"
					>
						<option value="https">HTTPS</option>
						<option value="http">HTTP</option>
					</select>
					
					
					<!-- Website name input -->
					<input 
						type="text" 
						class="form-control input-sync col-sm-7" 
						id="dest_url"
						name="dest_url"
						placeholder="www.yoursitename.com" 
						style="/*width:75%;*/"
						title="Enter the destination page on your website for your ad."
						maxlength="45"
					>
				</div>
				<br />
				
				
				<!-- Ad Headline 1 -->
				<div class="form-group">
					<div class="input-group">
					
						<span class="input-group-addon">Headline 1</span>
						
						<!-- headline 1 input -->
						<input 
							type="text" 
							class="form-control limited-field input-sync" 
							id="headline_1"
							name="headline_1" 
							value="" 
							data-limit="30"
							data-title="Headline 1"
							placeholder="Headline 1"
							title="Enter the primary headline for your ad (up to 30 characters)."
							maxlength="45"
						>
						<!-- input limit display -->
						<span class="input-group-addon text-limiter alert-success"></span>
					</div>
				</div>
				
				
				<!-- Ad Headline 2 -->
				<div class="form-group">
					<div class="input-group">
					
						<span class="input-group-addon">Headline 2</span>
						
						<!-- headline 2 input -->
						<input 
							type="text" 
							class="form-control limited-field input-sync" 
							id="headline_2"
							name="headline_2" 
							value="" 
							data-limit="30"
							data-title="Headline 2"
							placeholder="Headline 2"
							title="Enter the secondary headline for your ad (up to 30 characters)."
							maxlength="45"
						>
						
						<!-- input limit display -->
						<span class="input-group-addon text-limiter alert-success"></span>
					</div>
				</div>
        
				<!-- Ad Headline 3 -->
				<div class="form-group">
					<div class="input-group">
					
						<span class="input-group-addon">Headline 3</span>
						
						<!-- headline 2 input -->
						<input 
							type="text" 
							class="form-control limited-field input-sync" 
							id="headline_3"
							name="headline_3" 
							value="" 
							data-limit="30"
							data-title="Headline 3"
							placeholder="Headline 3"
							title="Enter the tertiary headline for your ad (up to 30 characters)."
							maxlength="30"
						>
						
						<!-- input limit display -->
						<span class="input-group-addon text-limiter alert-success"></span>
					</div>
				</div>
        
				<br />
				
				
				<!-- Display Destination (path)  -->
				<div class="input-group">
					<span class="input-group-addon">Display Path</span>
					
					
					<input 
						id="site_url" 
						type="hidden" 
						placeholder="www.yoursitename.com/"					
					/>
					
					
					<!-- site url as entered above -->
					<span 
						class="input-group-addon input-sync"  
						data-sync="dest_url"
					>www.yoursitename.com/</span>
					
					<span class="input-group-addon">/</span>
				</div>
				<div class="input-group">
					
					<!-- path 1 input -->
					<input 
						type="text" 
						class="form-control input-sync" 
						id="ext_1" 
						name="ext_1" 
						value=""
						placeholder="Path1"
						title="Enter the first display path for your ad."
						maxlength="15"
					/>
					
					<span class="input-group-addon">/</span>
					
					<!-- path 2 input -->
					<input 
						type="text" 
						class="form-control input-sync" 
						id="ext_2" 
						name="ext_2" 
						value=""
						placeholder="Path2"
						title="Enter the second display path for your ad."
						data-title="Path 2"
						maxlength="15"
					/>
					
					
				</div>
				<br />
				
				
				<!-- description -->
				<div class="form-group">
					<div class="input-group">
						<span class="input-group-addon">Description</span>
					</div>
					
					<div class="input-group">
					
						<!-- description input -->
						<textarea
							class="form-control limited-field input-sync" 
							id="description"
							name="description" 
							value=""
							data-limit="80"
							data-title="Description"
							placeholder="The descriptive text for your ad. Up to 80 characters."
							title="Enter the descriptive text for your ad (up to 80 characters)."
							maxlength="100"
						></textarea>
						
						<!-- input limit display --> 
						<span class="input-group-addon text-limiter alert-success"></span>
					</div>
				</div>
		
				<!-- description 2 -->
				<div class="form-group">
					<div class="input-group">
					
						<span class="input-group-addon">Description 2</span>
						

					</div>

					<div class="input-group">

						
						<!-- description input -->
						<textarea
							class="form-control limited-field input-sync" 
							id="description_2"
							name="description_2" 
							value=""
							data-limit="80"
							data-title="Description_2"
							placeholder="The descriptive 2 text for your ad. Up to 80 characters."
							title="Enter the descriptive 2 text for your ad (up to 80 characters)."
							maxlength="100"
						></textarea>
						
						<!-- input limit display --> 
						<span class="input-group-addon text-limiter alert-success"></span>
					</div>
				</div>
		
		
		
				<hr />
				<!-- Advanced options -->
				<h4>Advanced Options</h4>
				
				<!-- toggle sitelinks -->
				<input 
					id="use_sitelinks" 
					type="checkbox" 
					name="use_sitelinks" 
					value="1"
					title="Add sitelink extensions to this ad."
				/>&nbsp;Add Sitelinks&nbsp;&nbsp;&nbsp;
				
				
				<!-- sitelinks -->
				<div class="sitelinks-wrap sitelinks_used" style="display:none;">
				
					<!-- toggle sitelink descriptions -->
					<div class="col-md-12">
						<input id="use_sitelinkdesc" type="checkbox" name="use_sitelinkdesc" value="1" />&nbsp;Add Sitelink Descriptions?
					</div>
				
					<!-- 1 & 2 -->
					<div class="col-md-12">
					
					
						<!-- sitelink 1 -->
						<div class="sitelink sitelink_1">
						
							<label>Sitelink 1</label>
							
							<!-- Text input -->
							<div class="form-group">
							
								
							
								<div class="input-group sl-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Sitelink 1 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="sitelink_1"
										name="sitelink_1" 
										value="" 
										data-limit="25"
										data-title="Sitelink 1 Text"
										placeholder="Sitelink 1"
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
							
							
							<!-- Path input -->
							<div class="input-group">
								<span class="input-group-addon">Path:</span>
								<span class="input-group-addon">
									<span class="input-sync" data-sync="dest_url"></span>/
								</span>
							</div>
							
							<div class="input-group sl-path col-xl-12">
				
								
								
								<!-- Sitelink 1 input -->
								<input 
									type="text" 
									class="form-control limited-field" 
									id="sitelink_1_path"
									name="sitelink_1_path" 
									value="" 
									placeholder="Path1"
									maxlength="15"
								>
							</div>
							
							
							<!-- sitelink 1 descriptions -->
							<div class="sitelink_desc" style="padding:0 0 0 0;display:none;">
							
								<!-- description 1 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 1</span>
									</div>
									<div class="input-group">
										
										<!-- Description 1 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink1_desc_1"
											name="sitelink1_desc_1" 
											value="" 
											data-limit="25"
											data-title="Sitelink 1 Description 1"
											placeholder="Description 1"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
								
								
								<!-- description 2 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 2</span>
									</div>
									<div class="input-group">
										
										<!-- Description 2 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink1_desc_2"
											name="sitelink1_desc_2" 
											value="" 
											data-limit="25"
											data-title="Sitelink 1 Description 2"
											placeholder="Description 2"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
							</div>
							
						</div>
						
						
						
						<!-- sitelink 2 -->
						<div class="sitelink sitelink_2">
						
							<label>Sitelink 2</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group sl-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Sitelink 1 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="sitelink_2"
										name="sitelink_2" 
										value="" 
										data-limit="25"
										data-title="Sitelink 2 Text"
										placeholder="Sitelink 2"
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
							
							
							<!-- Path input -->
							<div class="input-group">
								<span class="input-group-addon">Path:</span>
								<span class="input-group-addon">
									<span class="input-sync" data-sync="dest_url"></span>/
								</span>
							</div>
								
							<div class="input-group sl-path col-sm-12">
								
								<!-- Sitelink 1 input -->
								<input 
									type="text" 
									class="form-control limited-field" 
									id="sitelink_2_path"
									name="sitelink_2_path" 
									value="" 
									placeholder="Path2"
									maxlength="15"
								>
							</div>
							
							
							<!-- sitelink 2 descriptions -->
							<div class="sitelink_desc" style="padding:0 0 0 0;display:none;">
							
								<!-- description 1 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 1</span>
									</div>
									<div class="input-group">
									
										<!-- Description 1 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink2_desc_1"
											name="sitelink2_desc_1" 
											value="" 
											data-limit="25"
											data-title="Sitelink 2 Description 1"
											placeholder="Description 1"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
								
								
								<!-- description 2 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 2</span>
									</div>
									<div class="input-group">
										
										<!-- Description 2 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink2_desc_2"
											name="sitelink2_desc_2" 
											value="" 
											data-limit="25"
											data-title="Sitelink 2 Description 2"
											placeholder="Description 2"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
							</div>
							
						</div>
						
						
					
					
						<!-- sitelink 3 -->
						<div class="sitelink sitelink_3">
						
							<label>Sitelink 3</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group sl-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Sitelink 3 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="sitelink_3"
										name="sitelink_3" 
										value="" 
										data-limit="25"
										data-title="Sitelink 3 Text"
										placeholder="Sitelink 3"
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
							
							
							<!-- Path input -->
							<div class="input-group">
								<span class="input-group-addon">Path:</span>
								<span class="input-group-addon">
									<span class="input-sync" data-sync="dest_url"></span>/
								</span>
							</div>
							
							<div class="input-group sl-path col-sm-12">
								
								<!-- Sitelink 1 input -->
								<input 
									type="text" 
									class="form-control limited-field" 
									id="sitelink_3_path"
									name="sitelink_3_path" 
									value="" 
									placeholder="Path3"
									maxlength="15"
								>
							</div>
							
							
							<!-- sitelink 3 descriptions -->
							<div class="sitelink_desc" style="padding:0 0 0 0;display:none;">
							
								<!-- description 1 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 1</span>
									</div>
									<div class="input-group">
										
										<!-- Description 1 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink3_desc_1"
											name="sitelink3_desc_1" 
											value="" 
											data-limit="25"
											data-title="Sitelink 3 Description 1"
											placeholder="Description 1"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
								
								
								<!-- description 2 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 2</span>
									</div>
									<div class="input-group">
										
										<!-- Description 2 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink3_desc_2"
											name="sitelink3_desc_2" 
											value="" 
											data-limit="25"
											data-title="Sitelink 3 Description 2"
											placeholder="Description 2"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
							</div>
							
						</div>
						
						
						<!-- sitelink 4 -->
						<div class="sitelink sitelink_4">
						
							<label>Sitelink 4</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group sl-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Sitelink 4 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="sitelink_4"
										name="sitelink_4" 
										value="" 
										data-limit="25"
										data-title="Sitelink 4 Text"
										placeholder="Sitelink 4"
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
							
							
							<!-- Path input -->
							<div class="input-group">
								<span class="input-group-addon">Path:</span>
								<span class="input-group-addon">
									<span class="input-sync" data-sync="dest_url"></span>/
								</span>
							</div>
							
							<div class="input-group sl-path col-sm-12">
								
								<!-- Sitelink 4 input -->
								<input 
									type="text" 
									class="form-control limited-field" 
									id="sitelink_4_path"
									name="sitelink_4_path" 
									value="" 
									placeholder="Path4"
									maxlength="15"
								>
							</div>
							
							
							<!-- sitelink 4 descriptions -->
							<div class="sitelink_desc" style="padding:0 0 0 0;display:none;">
							
								<!-- description 1 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 1</span>
										
									</div>
									<div class="input-group">
										<!-- Description 1 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink4_desc_1"
											name="sitelink4_desc_1" 
											value="" 
											data-limit="25"
											data-title="Sitelink 4 Description 1"
											placeholder="Description 1"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
								
								
								<!-- description 2 -->
								<div class="form-group">
									<div class="input-group">
					
										<span class="input-group-addon">Description 2</span>
									</div>
									<div class="input-group">
										
										<!-- Description 2 input -->
										<input 
											type="text" 
											class="form-control limited-field input-sync" 
											id="sitelink4_desc_2"
											name="sitelink4_desc_2" 
											value="" 
											data-limit="25"
											data-title="Sitelink 4 Description 2"
											placeholder="Description 2"
											maxlength="35"
										>
										
										<!-- input limit display -->
										<span class="input-group-addon text-limiter alert-success"></span>
									</div>
								</div>
							</div>
							
						</div>
						
						
					</div>
				
				</div>
				
				
				<!-- toggle callouts -->
				<input 
					id="use_callouts" 
					type="checkbox" 
					name="use_callouts"
					value="1" 
					title="Add callout extensions to this ad."
				/>&nbsp;Add Callouts&nbsp;&nbsp;&nbsp;
				
				<!-- callouts -->
				<div class="callouts-wrap callouts_used" style="display:none;">
				
					<div class="col-md-12">
					
						<!-- callout 1 -->
						<div class="callout callout_1">
						
							<label>Callout 1</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group co-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Callout 1 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="callout_1"
										name="callout_1" 
										value="" 
										data-limit="25"
										data-title="Callout 1"									
										placeholder="Callout 1"
										title="Callout text 1 (up to 25 characters)."
										maxlength="35"
									/>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
						</div>
						
						<!-- callout 2 -->
						<div class="callout callout_2">
						
							<label>Callout 2</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group co-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Callout 2 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="callout_2"
										name="callout_2" 
										value="" 
										data-limit="25"
										data-title="Callout 2"
										placeholder="Callout 2"
										title="Callout text 2 (up to 25 characters)."
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
						</div>
					
						<!-- callout 3 -->
						<div class="callout callout_3">
							
							<label>Callout 3</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group co-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Callout 3 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="callout_3"
										name="callout_3" 
										value="" 
										data-limit="25"
										data-title="Callout 3"
										placeholder="Callout 3"
										title="Callout text 3 (up to 25 characters)."
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
						</div>
						
						<!-- callout 4 -->
						<div class="callout callout_4">
						
							<label>Callout 4</label>
							
							<!-- Text input -->
							<div class="form-group">
								<div class="input-group co-text">
					
									<span class="input-group-addon">Text</span>
									
									<!-- Callout 4 input -->
									<input 
										type="text" 
										class="form-control limited-field input-sync" 
										id="callout_4"
										name="callout_4" 
										value="" 
										data-limit="25"
										data-title="Callout 4"									
										placeholder="Callout 4"
										title="Callout text 4 (up to 25 characters)."
										maxlength="35"
									>
									
									<!-- input limit display -->
									<span class="input-group-addon text-limiter alert-success"></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				
				<!-- toggle phone number -->
				<input 
					id="use_phone" 
					type="checkbox" 
					name="use_phone" 
					value="1" 
					title="Add phone number extension to this ad."
				/>&nbsp;Add Phone Number&nbsp;&nbsp;&nbsp;
				
				<!-- phone number -->
				<div class="phone-wrap phone_used" style="display:none;">
				
					<div class="col-md-12">
						
						<label>Phone Number</label>
							
						<!-- Text input -->
						<div class="input-group ph-text">
			
							<span class="input-group-addon">Text</span>
							
							<!-- Phone # input -->
							<input 
								type="text" 
								class="form-control limited-field input-sync" 
								id="phonenum"
								name="phonenum" 
								value=""
								placeholder="Phone #"
								title="Enter your phone number."
								data-title="Phone Number"
								maxlength="15"
							>
						</div>
					</div>
				</div>
				&nbsp;
				
			</div>
		</div>
	
	
		<!-- The user's ad currently being edited -->
		<div class="google-search-ads-wrapper col-md-6">
		
			<h2>Preview Your Ad</h2>
			<hr />
			
			
		</div>
	
	</form>
</div>




<!-- Ad preview -->
<div class="google-search-ad-template" style="display:none;" title="Edit this ad.">


	<!-- extension statuses
	<input type="hidden" name="sitelinks" value="0" />
	 -->


	<!-- headlines -->
	<h2 class="google-search-headline">
		<span 
			class="headline-1 input-sync" 
			data-sync="headline_1"
		></span>&nbsp;-
			
		<span 
			class="headline-2 input-sync" 
			data-sync="headline_2"
		></span>&nbsp;-

		<span 
			class="headline-3 input-sync" 
			data-sync="headline_3"
		></span>
    
	</h2>
	
	
	
	<!-- displayed destination address -->
	<p class="google-search-displayed-dest">
	
		<span class="ad-tag">Ad</span>
		
		<span class="dest input-sync" data-sync="dest_url"></span>/<span class="path1 input-sync" data-sync="ext_1"></span>/<span class="path2 input-sync" data-sync="ext_2"></span>
		
		<span class="glyphicon glyphicon-chevron-down" style="font-size:10px;"></span>
		
		
		<!-- phone number (optional) -->
		<span class="phone-ext" style="display:none;">
			<span class="phone input-sync" data-sync="phonenum"></span>
		</span>
		
	</p>
	
	

	<!-- description -->
	<p class="google-search-description input-sync" data-sync="description"></p>
	<p class="google-search-description input-sync" data-sync="description_2"></p>
	
	
	<!-- OPTIONAL PARAMETERS -->
	
	
	<!-- callouts -->
	<div class="callouts-ext" style="display:none;">
		<ul class="google-search-callouts">
			<li class="callout input-sync" data-sync="callout_1"></li>
			<li class="callout input-sync" data-sync="callout_2"></li>
			<li class="callout input-sync" data-sync="callout_3"></li>
			<li class="callout input-sync" data-sync="callout_4"></li>
		</ul>
	</div>
	
	
	<!-- sitelinks & sitelink descriptions -->
	<div class="sitelinks-ext" style="display:none;">
		<ul class="google-search-sitelinks">
		
		
			<!-- sitelink 1 -->
			<li class="sitelink">
			
				<span class="input-sync" data-sync="sitelink_1"></span>
				
				<!-- descriptions-->
				<div class="sitelink-desc-ext" style="display:none;">
					<p class="sl-desc1 input-sync" data-sync="sitelink1_desc_1"></p>
					<p class="sl-desc2 input-sync" data-sync="sitelink1_desc_2"></p>
				</div>
			</li>
			
			
			<!-- sitelink 2 -->
			<li class="sitelink">
			
				<span class="input-sync" data-sync="sitelink_2"></span>
				
				<!-- descriptions-->
				<div class="sitelink-desc-ext" style="display:none;">
					<p class="sl-desc1 input-sync" data-sync="sitelink2_desc_1"></p>
					<p class="sl-desc2 input-sync" data-sync="sitelink2_desc_2"></p>
				</div>
			</li>
			
			
			<!-- sitelink 3 -->
			<li class="sitelink">
			
				<span class="input-sync" data-sync="sitelink_3"></span>
				
				<!-- descriptions-->
				<div class="sitelink-desc-ext" style="display:none;">
					<p class="sl-desc1 input-sync" data-sync="sitelink3_desc_1"></p>
					<p class="sl-desc2 input-sync" data-sync="sitelink3_desc_2"></p>
				</div>
			</li>
			
			
			<!-- sitelink 4 -->
			<li class="sitelink">
			
				<span class="input-sync" data-sync="sitelink_4"></span>
				
				<!-- descriptions-->
				<div class="sitelink-desc-ext" style="display:none;">
					<p class="sl-desc1 input-sync" data-sync="sitelink4_desc_1"></p>
					<p class="sl-desc2 input-sync" data-sync="sitelink4_desc_2"></p>
				</div>
			</li>
		</ul>
	</div>
	

	<div class="ad-submit-wrap">
		<hr />
		<button class="btn btn-lg btn-primary" id="ad-submit" name="ad-submit" type="button" title="Save Ad">Save Ad</button>
	</div>

</div>




<!-- Remarket Add		DISABLED
<div class="col-md-12">
	<hr />
	<input type="checkbox" name="remarketing_added" id="remarketing_added" value="1"<?php //if($showRemarketing) echo " checked"; ?>/>
	&nbsp;&nbsp;<label><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Remarketing Label"]; ?></label>
	<br />&nbsp;
	
	<div class="remarket-add-wrap">
		<!-- Upload Button 
		<button class="btn btn-primary" id="remarket-adw-browse" type="button"><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Display Upload Button"]; ?></button>

		&nbsp;&nbsp;
		<!-- Check for contact about ads 
		<input type="checkbox" name="custom_ad" id="custom_ad" value="1" />
		&nbsp;&nbsp;<label><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Request Contact Label"]; ?></label>
		<br />&nbsp;
		
		<!-- Upload Preview 
		<div class="col-md-12 remarket-ad-preview">
			<?php //if(!$remarket_preview_img): ?>
				<p class="no-ads"><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Ads Uploaded Label"]; ?></p>
			<?php //endif; ?>
			
			<div class="has-ads" style="<?php //echo $remarket_preview_img? "display:block;" : "display:none;"; ?>">
				<img src="<?php //echo $remarket_preview_img?: ""; ?> " alt=""/><br /><br/>
				
				
				<div class="progress" style="display:none; width:320px; height:60px; /*margin:auto;*/ border:1px solid #333;">
					<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						<span class="sr-only"></span>
					</div>
				</div>
				
				
				<button class="btn btn-success" id="confirm" type="button"><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Confirm Button"]; ?></button>
				<button class="btn btn-warning" id="remove" type="button"><?php //echo $GLOBALS["ADROCKET_DEFINITIONS"]["Remove Button"]; ?></button>
			</div>
		</div>
	</div>
</div>
 -->






<!-- JS VARS -->
<script>

	//the user ads object
	var user_ads = {
		"userNum": null,
		"campaignNum": null,
		"ads": <?php echo $adwords_json; ?>,
	};
	
	
	var num_ads = user_ads.ads.length;
	
	
	//individual ad json template (ad.detail = [json derived from form serialize])
	const ad_json = {
		"adNum": null,
		"detail": {}
	};


	//on-load settings
	var page_loaded_with_ads_given = false;


	
	//ad interface section vars
	
	
	//limited text field values
	var text_limited_field_class = ".limited-field";
	var text_limiter_class       = ".text-limiter";
	var text_limit_attr          = "data-limit";
	var headline_max             = 30;
	var description_max          = 80;
	
	

	
	//ad display section vars
	var user_ad_wrapper_class      = "google-search-ads-wrapper";          //wrapper class for currently being edited ad

	var search_ad_parent_class     = "google-search-ad-preview";           //we replace the template class with this class when created a new ad  
	var search_ad_template_class   = "google-search-ad-template";          //the class that marks the ad template html wqe copy to create new ads
	
	var ad_parent_html             = "div." + search_ad_parent_class;      //the jquery selection strings for the above classes
	var ad_template_html           = "div." + search_ad_template_class;
	var ads_wrap_html              = "div." + user_ad_wrapper_class;
	
	
	//the form used for inputting ad values
	var creation_form_id           = "google-search-ad-creation-form";
	var creation_form_html         = "form#" + creation_form_id;
	
	
	//the ad list that shows already created ads
	var ad_list_wrapper            = "ad-list";
	var ad_list_wrapper_html       = "div." + ad_list_wrapper;
	
	var ads_list                   = "google-search-ads-list";
	var ads_list_html              = "div." + ads_list
	
	var ad_list_row_template_class = "ad-edit-line-template";
	var ad_list_row_template_html  = "div." + ad_list_row_template_class;
	
	var ad_list_row_class          = "ad-edit-line"
	var ad_list_row_html           = "." + ad_list_row_class;
	
	
	
	//the entire creation interface (form/preview/list)
	var ad_interface_class         = "google-search-ad-creation-interface";
	var ad_interface_html          = "div." + ad_interface_class;
	
	
	
	//buttons
	var btn_edit = "button.ad-edit";
	var btn_del  = "button.ad-delete"
	
	
	
	
	
	//the ad display elements that match the input elements
	var ad_headline1_class   = "span.headline-1";
	var ad_headline2_class   = "span.headline-2";
	var ad_displayPath_class = "span.dest";
	var ad_description_class = "p.google-search-description";
	
	
	//other attributes and values
	var ad_data_num         = "data-num";                            //the attribute used to indicate ad number in an html elt 
	var default_ad_data_num = "0";	
	var ad_list_order       = "data-order";
	var data_sync_class     = ".input-sync";                         //the class that indicates the data-syncing property
	
</script>



<!-- JS FUNCTIONS -->
<script>	
	
	function init_googleSearch(){
		
		
		
		user_ads.campaignNum = cpnNum;     //set the cpn num
		user_ads.userNum     = userNum;    //set the user num
		
		
		/*
		handles to toggling of the sitelinks extension usage per ad
		*/
		google_search_handleEnableSitelinksOnAd();
		
		
		
		/*
		handles to toggling of the callouts extension usage per ad
		*/
		google_search_handleEnableCalloutsOnAd();
		
		
		/*
		handles to toggling of the callouts extension usage per ad
		*/
		google_search_handleEnablePhoneOnAd();
		
		
		
		/*
		handles to toggling of the sitelink description extension usage per ad
		*/
		google_search_handleEnableSitelinkDescOnAd();
		
		
		/*
		handles the user switching between editing sitelink display text, 
		and the sitelink path text
		*/
		google_search_handleSiteLinksTextPathSwitch();
		
		
		/*
		display the already created ads for this campaign
		*/
		google_search_initailizeUserAdList();
		
		
		
		
		/*
		initalize the ad creation/editing interface
		*/
		google_search_initializeAdEditInterface();
		//google_search_autoCreateBlankFirstAd();
		
		
		
		
		/*
		enable the ad to scroll wth the user
		*/
		google_search_scrollAd();
		
		
		
		/* MOVED TO google_search_initializeAdEditInterface()
		
		The event handler for when text limited displays are 
		manipulated by the user
		
		google_search_handleDisplayLimitChange();
		*/
		
		
		
		
		/* MOVED TO google_search_initializeAdEditInterface()
		
		event handler for when synced display/editing inputs
		are manipulated by the user
		
		google_search_handleSyncedInputChange();
		*/
		
		
		
		
		
		
		
		
		/*
		handles the user pressing the save ad button
		*/
		google_search_handleAdSaveButton();
		
		
		
		/*
		handles when the user clicks the create new ad button
		*/
		google_search_handleCreateAdClick();
		
		
		/*
		handles when the user clicks the delete button
		*/
		google_search_handleAdDelete();
		
		
		/*
		Handle the toggling of remarketing ads addition
		*/
		toggleRemarketAdd();
	}
	
	
	
	/* Ad initialization */
	
	
	//Initialize the ad editing interface for google adwords
	function google_search_initializeAdEditInterface(){
		
		
		var new_ad                = $(ad_template_html).clone();    //clone the template html
		var ads_wrap              = $(ads_wrap_html);               //locate the wrapper for all ads
		
		num_ads                   = user_ads.ads.length;            //number of ads created
		
		new_ad.addClass(search_ad_parent_class);                    //replace the template class with the ad class
		new_ad.removeClass(search_ad_template_class);
		
		ads_wrap.append(new_ad);                                    //append the ad to the ads list wrapper
		
		
		
		if(num_ads == 0){
			
			//if no ads have been created yet, we initialize the page for
			//ad creation with a blank ad
			
			ads_wrap.parents("form").attr(ad_data_num, default_ad_data_num);   //default num is 0
			google_search_initializeAdForEditing(new_ad);
			new_ad.show("fast", google_search_resetAdScroll());
		} 
		else{
			
			//we load the last ad in the user ad list into the
			//editing interface
			
			var ad = user_ads.ads[(user_ads.ads.length - 1)];
			
			ads_wrap.parents("form").attr(ad_data_num, ad.adNum);    //set the ad number 
			
			google_search_loadAdForEditing(ad);                     //load last ad created
			new_ad.show("fast", google_search_resetAdScroll());     //show the ad
		}
		
		
		//handles the changing of the input limit display
		google_search_handleDisplayLimitChange();
		
		
		//handles the synced input updating
		google_search_handleSyncedInputChange();
		
		
		//hanlder for user clicking ad-edit button
		google_search_handleEditAd();
		
	}
	
	

	
	//we call this function to set up the interface elements that
	//sync user inputs, and count input length
	function google_search_initializeAdForEditing(ad){
		
		
		
		//the add editing interface inputs need to be synced with the ad we are about to edit
		var syncing_elts = ad.find(data_sync_class);
		
		syncing_elts.each(function(){ google_search_InitializeSyncingDisplay($(this)) });
		
		
		//the text limited display needs to be initialized for the input areas
		var text_limited_inputs = $(text_limited_field_class);
		
		text_limited_inputs.each(function(){ google_search_initializeLimitDisplay($(this)); });
	}
	
	
	
	
	
	//creates the user ad list that will display at the top of the interface
	//allowing users to select a previously made ad for further editing or deleting
	function google_search_initailizeUserAdList(){
		
		if(num_ads < 1) return false;                                      //quit if the are no ads to list
		
		var ad_wrap = $(ad_list_wrapper_html);                             //the wrapper for the ads list
		var ad_list = $(ads_list_html);                                    //the list itself
		
		//console.log(user_ads);
		
		
		//for each ad, we clone the ad list row template and replace the
		//placeholders with details for the ad. The list row is appended to
		//the list wrapper
		$.each(user_ads.ads, function(index, elt){
			
			var ad        = $(ad_list_row_template_html).clone();          //clone the template
			var ad_detail = elt.detail;                                    //the ad information
			
			//console.log(ad);
			//set the headlines
			var h_1_ph    = $("input[name='headline_1']").attr("placeholder");
			var h_2_ph    = $("input[name='headline_2']").attr("placeholder");
			var h_1       = (ad_detail["headline_1"] == "")? h_1_ph : ad_detail["headline_1"];
			var h_2       = (ad_detail["headline_2"] == "")? h_2_ph : ad_detail["headline_2"];
			
			ad.removeClass(ad_list_row_template_class);                    //remove the template class
			ad.attr(ad_list_order, index);                                 //set the order attr to the ad index
			ad.find(ad_headline1_class).text(h_1);                         //set the headline values    
			ad.find(ad_headline2_class).text(h_2);
			ad.find(btn_edit).attr(ad_data_num, elt.adNum);                //set the buttons with the correct data number
			ad.find(btn_del).attr(ad_data_num, elt.adNum);
			
			
			if(index == 0){                          //on the first ad
				
				//remove the delete button because the user needs at least 1 ad
				ad.find(btn_del).remove();

			} 
			
			if(index == (user_ads.ads.length - 1)){  //on the last ad
				
				//disable the edit button - the last ad is loaded automatically
				ad.find(btn_edit).prop("disabled", true);
				
				//ad the active class to the ad to show it is selected for editng
				ad.addClass("active-ad");
			}
			
			
			
			ad_wrap.append(ad);                                            //append and display the list row
			ad.show();
		});
		
		ad_list.show();                                                    //display the entire list
		
		return false;
	}
	
	

	
	//adding a newly saved ad to the user ad list
	function google_search_addToUserAdList(new_ad, ad_index){
		
		//console.log("Index: " + ad_index);
		if(num_ads < 1) return false;                                      //quit if there are no ads
		
		var ad_wrap     = $(ad_list_wrapper_html);                           //
		var ad_list     = $(ads_list_html);
		var ad          = $(ad_list_row_template_html).clone();
		var ad_detail   = new_ad.detail;
		var prev_ad     = ad_wrap.find(ad_list_row_html + "[" + ad_list_order + "='" + (ad_index - 1) + "']"); // the ad line we will update, or ad behind
		var prev_ad_num = prev_ad.find(btn_edit).attr(ad_data_num);
		
		
		//set the headlines
		var h_1_ph    = $("input[name='headline_1']").attr("placeholder");
		var h_2_ph    = $("input[name='headline_2']").attr("placeholder");
		var h_1       = (ad_detail["headline_1"] == "")? h_1_ph : ad_detail["headline_1"];
		var h_2       = (ad_detail["headline_2"] == "")? h_2_ph : ad_detail["headline_2"];
		
		ad.removeClass(ad_list_row_template_class);
		ad.attr(ad_list_order, ad_index);
		ad.find(ad_headline1_class).text(h_1);
		ad.find(ad_headline2_class).text(h_2);
		ad.find(btn_edit).attr(ad_data_num, new_ad.adNum);
		
		

		if(ad_index == 0 || typeof prev_ad_num === "undefined") {
			
			ad.find(btn_del).remove();
			if(num_ads > 1) ad_wrap.find(ad_list_row_html).first().before(ad);
			else ad_wrap.append(ad);
		}
		else{
			
			ad.find(btn_del).attr(ad_data_num, new_ad.adNum);
			
			console.log("add to list:");
			console.log(prev_ad_num);
			console.log(new_ad.adNum);
			
			prev_ad.after(ad);   //append the new ad
			
			if(prev_ad_num == new_ad.adNum){  //ad was edited and not newly created
				
				//delete the prev_ad as it is the ad before the edit
				prev_ad.remove();
				
				//decrement the ad index
				ad.attr(ad_list_order, ad_index - 1);
				
			}
			
			
			
		}
		
		
		
		ad.show();
		ad_list.show();
		
		return false;
	}
	
	
	
	
	
	
	
	/* Input Syncing */
	
	
	//init the pairs of syncing inputs and displays when we switch which add we edit
	//so the dislayed info and the input values match when we start
	function google_search_InitializeSyncingDisplay(elt){
		

		var syncing_id = elt.attr("data-sync");
		
		if(typeof syncing_id !== 'undefined'){
		
			var sync_value = $("#" + syncing_id).val();
			
			if(syncing_id == "dest_url"){
				
				sync_value = sync_value.split("/")[0];
			}
			
			
			if(!sync_value || sync_value == ""){
				
				if(syncing_id != "ext_1" && syncing_id != "ext_2") sync_value = $("#" + syncing_id).attr("placeholder");
			}
			
			elt.text(sync_value);
		}
	}
	
	
	
	//when the user inputs into a synced field, we update the display of the ad to match
	function google_search_handleSyncedInputChange(quickTrigger = true){
		
		$("body").on("keyup", "form#google-search-ad-creation-form .input-sync", function(){
			
			
			
			var elt  = $(this);
			var ad   = $(ads_wrap_html).find(ad_parent_html).first();
			var form = $("form#google-search-ad-creation-form");
			
			//console.log(ad);
			
			var syncing_elts = $.merge(
				ad.find(".input-sync[data-sync='"+elt.attr("id")+"']"),
				form.find(".input-sync[data-sync='"+elt.attr("id")+"']")
			);
			
			
			google_search_InitializeSyncingDisplay(syncing_elts);
			google_search_resetAdScroll();
		});
		
		if(quickTrigger) $("form#google-search-ad-creation-form .input-sync").trigger("keyup");  //trigger the handler immediate
	}
	
	
	
	
	
	
	
	
	/* Character limit input display */
	
	//update the alert color of the limited display
	function google_search_updateLimitDisplay(input, limit, wrap, form_group){
		
		wrap.removeClass("alert-success");
		wrap.removeClass("alert-warning");
		wrap.removeClass("alert-danger");
		
		form_group.removeClass("has-error");
		
		if(input <  limit) wrap.addClass("alert-success");
		if(input == limit) wrap.addClass("alert-warning");
		if(input >  limit){
			
			wrap.addClass("alert-danger");
			form_group.addClass("has-error");
		}
	}
	
	
	
	//when a character is entered or removed we update the character limit display
	function google_search_handleDisplayLimitChange(quickTrigger = true){
		
		
		$("body").on("keyup", text_limited_field_class, function(){
			
			google_search_initializeLimitDisplay($(this));
		});
		
		
		if(quickTrigger) $(text_limited_field_class).trigger("keyup");  //trigger the handler immediate
	}
	
	
	//initialize a limit display to show the current char count of an input field
	function google_search_initializeLimitDisplay(elt){
		
		var limit_display_wrap = elt.siblings(text_limiter_class);   //where we display the char/limit count
		var input_length       = elt.val().length;                   //the current length of the input
		var input_limit        = elt.attr(text_limit_attr);          //the limit of the input
		var form_group         = elt.parents(".form-group, .input-group");
		
		
		limit_display_wrap.text(input_length + "/" + input_limit);   //set the text
		
		//determine the alert color
		google_search_updateLimitDisplay(input_length, input_limit, limit_display_wrap, form_group);
	}
	
	
	
	
	
	
	
	
	
	/*Sitelinks Functions*/
	
	function google_search_handleEnableSitelinksOnAd(){
		
		$("body").on("change", "input[name='use_sitelinks']", function(){
			
			var show = false;
			
			if($(this).is(":checked")) show = true;
			var ad = $(ads_wrap_html).find(ad_parent_html).first();
			ad.find(".sitelinks-ext").toggle(show);
			
			$(".google-search-ad-creation-interface .sitelinks_used").toggle(show);
			google_search_resetAdScroll();
		});
	}
	
	
	function google_search_handleEnableSitelinkDescOnAd(){
		
		$("body").on("change", "input[name='use_sitelinkdesc']", function(){
			
			var show = false;
			
			if($(this).is(":checked")) show = true;
			var ad = $(ads_wrap_html).find(ad_parent_html).first();
			ad.find(".sitelink-desc-ext").toggle(show);
			
			$(".google-search-ad-creation-interface .sitelink_desc").toggle(show);
			
			google_search_resetAdScroll();
		});
	}
	
	
	//when the user toggles the sitelink path/text display
	function google_search_handleSiteLinksTextPathSwitch(){
		
		$("body").on("change", "input#slTextToggle",function(){
			
			alert($(this).is("checked"));
			
			if($(this).is("checked")){
				
				$(".google-search-ad-creation-interface .sl-text").hide(
					"fast", 
					function(){
						$(".google-search-ad-creation-interface .sl-path").show("fast");
					}
				);
			}
			else{
				
				$(".google-search-ad-creation-interface .sl-path").hide(
					"fast", 
					function(){
						$(".google-search-ad-creation-interface .sl-text").show("fast");
					}
				);
			}
		});
	}
	
	
	
	
	
	
	
	/* Callout Functions */
	
	function google_search_handleEnableCalloutsOnAd(){
		
		$("body").on("change", "input[name='use_callouts']", function(){
			
			var show = false;
			
			if($(this).is(":checked")) show = true;
			
			var ad = $(ads_wrap_html).find(ad_parent_html).first();
			ad.find(".callouts-ext").toggle(show);
			
			$(".google-search-ad-creation-interface .callouts_used").toggle(show);
			google_search_resetAdScroll();
		});
	}
	
	
	
	
	
	
	
	/* Phone Extension */
	
	function google_search_handleEnablePhoneOnAd(){
		
		$("body").on("change", "input[name='use_phone']", function(){
			
			var ad = $(ads_wrap_html).find(ad_parent_html).first();
			if($(this).is(":checked")){
				
				ad.find(".phone-ext").show();
				$(".google-search-ad-creation-interface .phone_used").show();
			}
			else{
				ad.find(".phone-ext").hide();
				$(".google-search-ad-creation-interface .phone_used").hide();
			}
			
			//$(".google-search-ad-creation-interface .phone_used").toggle();
			google_search_resetAdScroll();
		});
	}
	
	
	
	
	
	
	
	
	
	
	/*Ad Creation*/
	
	
	function google_search_handleCreateAdClick(){
		
		$("body").on("click", "div.google-search-ad-create", function(){
			
			if(num_ads == 9) alert("You may only create up to 9 ads.\nPlease Delete or edit an exisitng ad.");
			else google_search_createNewAd();
			
			return false;
		});
	}
	
	
	
	function google_search_createNewAd(){
		
		//clear the form
		
		//console.log("create");
		//console.log(ad_json);
		
		var ad    = $.extend(true, {}, ad_json);   //copy a version of the ad json
		var a_num = user_ads.ads.length + 1;       //until a number is given from the database (we know the ad number and ads index + 1 are the same number)
		
		ad.adNum = 0;
		
		console.log(ad);
		//console.log(ad_json);
		var result = google_search_saveAd(ad);
		
		
		result.done(function(data){
			
			
			console.log(data);
			adNum = data.adNum;
			
			//update the display
			num_ads = user_ads.ads.length;     //update the ads length
			$("span#ad-count").text(num_ads);
			$("div.ad-list div.ad-edit-line").remove();
			google_search_initailizeUserAdList();
			
			$.each(user_ads.ads, function(index, elt){
					
				if(elt.adNum == adNum){
					
					google_search_loadAdForEditing(elt);
					return false;
				}
			});
			
			
			google_search_resetAdScroll()
			//$("div.ad-list div.ad-edit-line").last().remove();
			
			
		});
		
		result.fail(function(data){ console.log(data); });
		
		
		
		return false;
	}
	
	
	
	
	
	
	
	
	/*Ad dislplay*/
	
	//enable the google search ad preview to scroll down the window to a certain extent
	function google_search_scrollAd(){
		
		
		$(window).scroll(function(){
			
			google_search_resetAdScroll();
		});
		
		
		$(window).resize(function(){
			
			google_search_resetAdScroll();
		});
		
		
	}
	
	
	function google_search_resetAdScroll(){
		
		
		var w = window.innerWidth;

		var ad_list        = $("div.google-search-ads-list");
		var ad_list_height = 0;
		
		if(ad_list.is(":visible")){
			
			ad_list_height = ad_list.height();
		}
		
		//console.log(ad_list_height);
		
		var height_adjust   = 120 + ad_list_height;
		
		var ad = $("div.google-search-ads-wrapper div.google-search-ad-preview");
		
		
		if(w < 992){
			
			ad.css("margin-top", 50);
			return false;
		}
		
		
		var ah = ad.height();
		
		var wd  = $(window).scrollTop() - height_adjust;
		var ih  = $("div.default-input-wrapper").height();
		
		var max_top_margin = ih - ah;
		if(ih == 0 || ah == 0) return false;
		
		if(wd < 20) wd = 20;
		
		/*
		console.log(ih);
		console.log(ah);
		console.log(max_top_margin);
		console.log(wd);
		*/
		
		if(wd >= max_top_margin){
			ad.css("margin-top", max_top_margin);
		} 
		else{
			ad.css("margin-top", wd);
		} 
		
		return false;
	}
	
	
	
	
	
	
	/* Ad Validation */
	function google_search_validateAdSubmission(ad_fields){
		
		//alert();
		
		
		var vald_fields = {
			1: {"dest_url"    : "Your Website URL"},
			2: {"headline_1"  : "Headline 1"},
			3: {"headline_2"  : "Headline 2"},
			7: {"description" : "Description"},
		};
		
		var al = "";    //alert text for errors
		
		console.log("Fields:");
		console.log(ad_fields);
		
		
		$.each(ad_fields, function(index, elt){
			
			var n = elt["name"];
			var v = elt["value"];
			var e = $("[name='"+n+"']"); //the input element from the dom
			var l = 0;                   //the character limit - 0 is unlimited
			var c = v.length;
			var t = "";                  //title/name of the input
			
			console.log("Check " + n + ": " + v + " " + index);
			console.log(vald_fields[index]);
			
			//check that all limited inputs are within the limits
			if(e.hasClass("limited-field")){
				
				
				
				t = e.attr("data-title");
				l = e.attr("data-limit");
				
				console.log(n + " - " + v + " - " + c + " - " + l + " - " + t);
				
				if(c > l) al += "- " + t + " field has too many characters.\n";
			}
			
			//check required fields are not blank
			if(typeof vald_fields[index] === "undefined"){ return true; } 
			else if((!v || v == "")){
				console.log("ERROR - " + n);
				al += "- " + vald_fields[index][n] + "\n";
			}
			
			
			

		});
		
		if(al != "") al = "Please fill in the following fields before submitting:\n\n" + al;
		
		return al;
	}
	
	
	
	
	
	
	
	
	/* Ad Saving */
	
	//the user clicks the save ad button
	function google_search_handleAdSaveButton(){
		
		$("body").on("click", "button#ad-submit", function(){
			
			
			console.log("ad json");
			console.log(ad_json);
			
			var form       = $("form#google-search-ad-creation-form");
			var submission = form.serializeArray();
			var json_sub   = {};
			var ad         = $.extend(true, {}, ad_json);   //copy a version of the ad json

			var valid = google_search_validateAdSubmission(submission);
			
			var current_ad_num = $("form#google-search-ad-creation-form").attr("data-num");
			
			ad.adNum = parseInt(current_ad_num);
			
			
			if(valid != ""){
				
				alert(valid);
				return false;
			}
			
			console.log(submission);
			
			
			//json-ify the array
			$.each(submission, function(index, elt){ 
			
				var name = elt['name'];
				var val  = elt['value'];
				var search = "input[name='" + name + "'], select[name='" + name + "'], textarea[name='" + name + "']";
				
				//if(val == "") val = $(search).attr("placeholder");
				ad.detail[name] = val; 
				
			});
			
			console.log(ad);
			
			//save the ad to the records and ad it to the user ad list if successful
			google_search_saveAd(ad);
		});
	}
	
	
	
	//send the result to the server and update the ad interface
	function google_search_saveAd(ad){
		
		var result = google_search_submitAdAjax(ad);
		
		
		result.done(function(data){
			
			console.log(data);
			
			ad.adNum     = data.adNum;             //set the ad num as given by the server
			var found    = false;
			var ad_index = null;
			
			//check if the ad num exists in the user_ads array
			$.each(user_ads.ads, function(index, elt){
				
				if(found) return false;
				
				if(elt.adNum == data.adNum){
					
					found    = true;
					ad_index = index;
				} 
			});
			
			if(ad_index === null) ad_index = user_ads.ads.length;
			
			
			if(found){  //ad with that number exists in our js array
				
				user_ads.ads[ad_index].detail = ad.detail;
				
			}else{      //ad was newly created on server
				
				user_ads.ads.push(ad);             //push the ad to the user ads array
			}
			
			
			num_ads = user_ads.ads.length;     //update the ads length
			console.log(user_ads);
			console.log(num_ads);
			
			$("span#ad-count").text(num_ads);
			
			//update the screen display to show the user ad list
			google_search_addToUserAdList(ad, ad_index + 1);
			
			//hide the ad editing interface
			$("div.google-search-ad-creation-interface").hide("slow");
			
			//show the continue button
			$("input.ad-continue").show();
			
			//reset the editing interface
			google_search_clearFormInputs();
			$("form#google-search-ad-creation-form .input-sync").trigger("keyup");
			
		});
		
		
		result.fail(function(data){
			
			console.log(data);
			
		});
		
		return result;
	}
	
	
	
	//submit the json for a user ad to the server
	function google_search_submitAdAjax(ad){
		
		//return true;
		console.log("ajax:");
		
		return $.ajax({
			url: "?",
			dataType: "json",
			method: "POST",
			data:{
				google_search_ad_submit: 1,
				ad: ad,
				cpnNum: cpnNum
			}
			
		});
	}
	
	
	
	
	
	
	
	
	/* Ad Loading */
	
	//user clicks edit ad button
	function google_search_handleEditAd(){
		
		$("body").on("click", "button.ad-edit", function(){
			
			var ad_num = parseInt($(this).attr("data-num"));
			var ad     = null;
			var ad_row = $(this).parents("div.ad-edit-line");
			var loaded = false;
			
			console.log(ad_num);
			console.log(user_ads);
			
			$.each(user_ads.ads, function(index, elt){
				
				if(elt.adNum == ad_num){
					
					google_search_loadAdForEditing(elt);
					loaded = true;
					return false;
				}
			});
			
			$(btn_edit).prop("disabled", false);
			$(ad_list_row_html).removeClass("active-ad");
			
			if(loaded){
				
				ad_row.addClass("active-ad");
				ad_row.find(btn_edit).prop("disabled", true);
			}
			
			
			else alert("Error Loading Ad");
		});
	}
	

	
	
	//load an ad for editing into the interface
	function google_search_loadAdForEditing(ad){
		
		console.log(ad);
		
		var ad_form      = $(creation_form_html);                    //the form element
		var ad_interface = $(ad_interface_html);                     //the creation interface
		var details      = ad.detail;                                //we are concerned only with the ad details
		
		
		ad_form.attr("data-num", ad.adNum);                          //set the form data-num to the ad number
		
		
		//set the input elements to the values in the ad details
		$.each(details, function(index, val){ 
		
			var form_elt = $(creation_form_html + " #" + index);
			
			
			
			if(form_elt.attr("type") == "checkbox"){
				
				console.log(index + " - " + val);
				//we set the checked state based on val
				form_elt.prop("checked", parseInt(val));
				form_elt.trigger("change");
			}
			else{ form_elt.val(val); }
			
		});
		
		$(creation_form_html + " .input-sync").trigger("keyup");     //manually trigger a keyup to set the inout syncing elements' action
		
		ad_interface.show("slow");                                   //show the ad editing interface
		
		$("input.ad-continue").hide();                              //hide the continue button
	}
	
	
	
	
	
	
	
	
	
	/* Ad Deleteing */
	
	//user clicks the delete button
	function google_search_handleAdDelete(){
		
		$("body").on("click", "button.ad-delete", function(){
			
			var ad_num = $(this).attr("data-num");
			
			if(confirm("Are you sure you want to delete this ad?")){
				
				console.log(ad_num);
				google_search_deleteAd(ad_num);
			}
			
		});
	}
	
	
	//delete the ad
	function google_search_deleteAd(ad_num){
		
		
		var result = google_search_deleteAdAjax(ad_num);
		
		result.done(function(data){
			
			console.log(data);
			
			
			//remove the ad from the json object
			$.each(user_ads.ads, function(index, elt){

				if(elt.adNum == ad_num) user_ads.ads.splice(index, 1);
			});
			
			
			//update the display
			num_ads = user_ads.ads.length;     //update the ads length
			$("span#ad-count").text(num_ads);
			$("div.ad-list div.ad-edit-line").remove();
			google_search_initailizeUserAdList();
			
			
			//hide the ad editing interface
			$("div.google-search-ad-creation-interface").hide("slow");
			
			//show the continue button
			$("input.ad-continue").show();
			
			//reset the editing interface
			google_search_clearFormInputs();
			$("form#google-search-ad-creation-form .input-sync").trigger("keyup");
		});
		
		result.fail(function(data){
			
			console.log(data);
		});
		
	}
	
	
	//ajax for deleting ad
	function google_search_deleteAdAjax(ad_num){
		
		return $.ajax({
			url: "?",
			dataType: "json",
			method: "POST",
			data:{
				google_search_ad_delete: 1,
				adNum: ad_num,
				cpnNum: cpnNum
			}
			
		});
	}
	
	
	
	
	
	
	/* Interface Form */
	
	function google_search_clearFormInputs(){
		
		$("form#google-search-ad-creation-form")[0].reset();
	}
	
	
	
	/* Remarketing */
	
	function toggleRemarketAdd(){
	
		var rm_check = "input[name='remarketing_added']";
		
		if($(rm_check).prop("checked")){ 
			$("div.remarket-add-wrap").toggle(true); 
			//startRemarketUploader();
		}
		else{ $("div.remarket-add-wrap").toggle(false); } 
		
		$("body").on("change", rm_check, function(){
			
			//alert($(this).prop("checked"));
			
			if($(this).prop("checked")){ 
			
				$("div.remarket-add-wrap").toggle(true); 
				//if(!remarket_uploader) startRemarketUploader();
			}
			else{ 
				$("div.remarket-add-wrap").toggle(false); 
				//if(remarket_uploader) remarket_uploader.destroy();
			} 
		});
	}
	

</script>