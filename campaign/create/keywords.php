<?php

/*

	The viewer page for the keywords selection in the Campaign Creation Wizard Process
	
*/
	
	require_once "_additional_ctrl.php";
	require_once "../../assets/_header.php";
	
	
	//array_push($additional_default_keywords, "This ridiculously long keyword that would never be practical");
	//array_push($default_keywords, "This ridiculously long keyword that would never be practical");
?>

<!-- main page content -->
<div class="jumbotron ec-process">

	<?php require "../../assets/_create_breadcrumbs.php"; ?>

	<div class="container" style="background-color:white; padding-bottom:30px;">
		<div class="row keywords-interface">

			<input type="hidden" name="Continue"            value="Continue" />
			<input type="hidden" name="num"                 value="<?php echo $cpnNum; ?>" />
			<input type="hidden" name="keywords"            value="<?php echo implode(",", $keywords);             ?>" />
			<input type="hidden" name="additional_keywords" value="<?php echo implode(",", $additional_keywords);  ?>" />
			<input type="hidden" name="negative_keywords"   value="<?php echo implode(",", $negative_keywords);    ?>" />
			<!-- <input type="hidden" name="custom_keywords"     value="<?php //echo implode(",", $custom_keywords);      ?>" /> -->
			<input type="hidden" name="default_keywords"    value="<?php echo implode(",", $default_keywords);     ?>" />
			<input type="hidden" name="unused_defaults"     value="<?php echo implode(",", $unused_defaults);      ?>" />
			<input type="hidden" name="has_additional"      value="<?php echo !empty($additional_keywords)? 1 : 0; ?>" />
			<input type="hidden" name="summ"                value="<?php echo @$_REQUEST['summ']; ?>" />
			

			
			<div class="col-md-12" style="display:none;">
				<?php echo $blankCheck; ?>
			</div>
			
			
			
			<h2 style="font-size:35px;">Choose Your Keywords</h2>
			
			
			<!-- default keywords selection -->
			<div class="well col-lg-10 err-box kw_wrap" id="default_keywords-wrap" data-table='default_keywords' data-copy='unused_defaults'  data-alert="info">	
				<h3>Default Keywords For This Industry:</h3>
				<p style="font-size:14px;">We have chosen these default keywords based on your industry.</p>
				<br />
				
				<div class="keywords-list">
					<?php foreach($default_keywords as $index=>$kw) : ?>
						<div class="keyword-col">
						
							<div class="keyword-check alert alert-info">
								<input type="checkbox" name="<?php echo $kw; ?>" value="<?php echo $kw; ?>" checked="" class="no_sub">&nbsp;
								<label><?php echo $kw; ?></label>&emsp;
							</div>
						</div>
					
					<?php endforeach; ?>
				</div>
				

				
				<p class="list-empty" <?php if(!empty($default_keywords)) echo "style='display:none;'"; ?>><span class="alert alert-info"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Default Keywords List Empty"]; ?></em></span></p>

				
				
				
				
				<!-- additional keywords -->
				<?php if($additional_default_keywords) : ?>	
					<div class="col-md-12 kw_wrap" style="padding:15px;" data-table='additional_keywords' data-copy=''>
						

						<br />
						<span class="see-more">Show more default keyword options...</span>
						<br />
						<br />
						
						<div class="more-keywords col-sm-12" style="display:none;">
						
							<?php foreach($additional_default_keywords as $index=>$kw) : ?>
							
								<?php 
								
									//if($index % 3 == 0) echo "<div class='row'>";
									$checked = " checked";
								
									if(!$kw) continue; 
									if(!empty($additional_keywords) && !in_array($kw, $additional_keywords)) $checked = "";
								?>
								<div class="keyword-col">
								
									<div class="keyword-check alert alert-info">
										<input type="checkbox" name="<?php echo $kw; ?>" value="<?php echo $kw; ?>" class="no_sub"<?php echo $checked; ?>>&nbsp;
										<label><?php echo $kw; ?></label>&emsp;
									</div>
								</div>
								
								
							
							<?php endforeach; ?>
							
							<?php //if($index % 3 != 2) echo "</div></div>"; ?>
						
						</div>
					</div>
				<?php endif; ?>
				
				<br />
				
				
			</div>
			
			
			
			
			
			<!-- unused default keywords selection -->
			<div class="col-lg-7 err-box kw_wrap" id="unused_defaults-wrap" data-table='unused_defaults' data-copy='default_keywords' data-alert='none'>	
				<h3><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Unused Defaults Label Modal"]; ?></h3>
				<p style="font-size:14px;">Any default keywords you chose not to use appear here. They are not used in your Campaign.</p>
				<br />
				

				<div class="keywords-list">
					<?php foreach($unused_defaults as $index=>$kw) : ?>
						<div class="col-sm-4 keyword-col">
						
							<span class="keyword-check alert alert-none">
								<input type="checkbox" name="<?php echo $kw; ?>" value="<?php echo $kw; ?>" checked="" class="no_sub">&nbsp;
								<label><?php echo $kw; ?></label>&emsp;
							</span>
						</div>
					
					<?php endforeach; ?>
				</div>
				
				
				
				<p class="list-empty" <?php if(!empty($unused_defaults)) echo "style='display:none;'"; ?>><span class="alert alert-info"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Unused Default List Empty"]; ?></em></span></p>

				
				<div class="col-lg-12">
					<br />
					<br />
				</div>
				
			</div>
			
			
			
			
			<!--advanced options -->
			<div class="col-lg-12">
			
			
				<span class="advanced-keyword-opts">Advanced Keyword Options...</span>
			
			
				<div class="col-lg-12 advanced-keywords" style="display:none;">
				
				
					<!-- custom keywords selection -->
					<div class="col-lg-7 err-box kw_wrap" id="keywords-wrap" data-table='keywords' data-copy=''  data-alert="warning">	
						<h3>Custom Keywords</h3>
						<p style="font-size:14px;">You can add your own keywords to this campaign. Fill out the input below, seperating your keywords with commas.</p>
						<br />
						
						
						<div class="keywords-list">
							<?php foreach($keywords as $index=>$kw) : ?>
								<div class="col-sm-4 keyword-col">
								
									<div class="keyword-check alert alert-warning">
										<input type="checkbox" name="<?php echo $kw; ?>" value="<?php echo $kw; ?>" checked="" class="no_sub">&nbsp;
										<label><?php echo $kw; ?></label>&emsp;
									</div>
								</div>
							
							<?php endforeach; ?>
						</div>
						
						
						

						<p class="list-empty" <?php if(!empty($keywords)) echo "style='display:none;'"; ?>><span class="alert alert-info"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Keyword List Empty"]; ?></em></span></p>

						
						
						<br />
						
						<div class="col-md-12">
							<p style="margin-bottom:5px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Prompt"]; ?></p>
							<div class="input-group col-md-8">
								<input 
									class="form-control no_sub" 
									type="text" 
									name="keywords" 
									value="<?php echo ""; ?>" 
									placeholder="Your Custom keywords"  
									style="margin-top:0px;"
								/>
								<span class="input-group-btn">
									<button 
										class="btn btn-danger add-keywords" 
										data-type="keywords" 
										data-opp="custom_keywords" 
										type="button"
										style="font-size:17px; padding:4px 18px;"
									><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Button Text"]; ?></button>
								</span>
							</div>
							<hr />
						</div>
					</div>
					
						
					<!-- negative keywords selection -->
					<div class="col-lg-7 err-box kw_wrap" id="negative_keywords-wrap"  data-table='negative_keywords' data-copy=''  data-alert="danger">	
						<h3><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Negative Keywords Label Modal"]; ?></h3>
						<p style="font-size:14px;">You can add keywords for your Campaign to avoid. Fill out the input below, seperating your keywords with commas.</p>
						<br />
						
						
						
						<div class="keywords-list">
							<?php foreach($negative_keywords as $index=>$kw) : ?>
								<div class="col-sm-4 keyword-col">
								
									<div class="keyword-check alert alert-danger">
										<input type="checkbox" name="<?php echo $kw; ?>" value="<?php echo $kw; ?>" checked="" class="no_sub">&nbsp;
										<label><?php echo $kw; ?></label>&emsp;
									</div>
								</div>
							
							<?php endforeach; ?>
						</div>
						
						

						<p class="list-empty" <?php if(!empty($negative_keywords)) echo "style='display:none;'"; ?>>
						<span class="alert alert-info"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Negative Keyword List Empty"]; ?></em></span></p>

						
						
						<br />
						
						<div class="col-md-12">
							<p style="margin-bottom:5px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Negative Keyword Prompt"]; ?></p>
							<div class="input-group col-md-8">
								<input 
									class="form-control no_sub" 
									type="text" 
									name="negative_keywords" 
									value="<?php echo ""; ?>" 
									placeholder="Your Custom keywords"  
									style="margin-top:0px;"
								/>
								<span class="input-group-btn">
									<button 
										class="btn btn-danger add-keywords" 
										data-type="negative_keywords" 
										data-opp="keywords" 
										type="button"
										style="font-size:17px; padding:4px 18px;"
									><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Button Text"]; ?></button>
								</span>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>	
			
			
			<!-- continue button -->
			<div class="col-lg-8">
			
				<div class="form-group text-right" style="padding-top:30px;">
					<button class="btn btn-lg btn-danger kw-continue" type="button" style="/*margin-top:120px;display:none; position:fixed;*/">Continue</button>
				</div>
			</div>
			
		</div>
	</div>
</div>


<?php 
	
	//footer
	require_once "../../assets/_footer.php"; 
	
?>

<script src="/js/wizard_common_temp.js"></script>
<script>




	var show_add_keywords = <?php echo (!empty($additional_keywords))? 1 : 0; ?>;
	var show_adv_opts     = <?php echo (!empty($keywords) || !empty($negative_keywords))? 1 : 0; ?>;
	var cpnNum = <?php echo $cpnNum; ?>;


	
	function removeChecksFromSubmit(){
			
		$("table.keywords-list input[type='checkbox']").addClass("no_sub");
	}
	
	
	
	//remove duplicates from a CSV string
	function stringCSVRemoveDuplicates(string){
		
		string = string.toLowerCase();
		string = string.split(",");
		unique = [];
		
		//only add one version of each value to the unique array
		$.each(string, function(index, elt){
			
			elt = elt.trim();
			if(jQuery.inArray(elt, unique) == -1) unique.push(elt);
		});
		
		return unique;
	}
	
	
	
	//given two strings/arrays of strings, we remove any from
	//the first that is present in the second.
	function stringCSVRemoveMatches(string, compare, removeFromBoth = false, returnMatches = false){
		
		var item            = null;   //when we remove item from an array
		var matches         = [];     //the matched values
		//var matched_indexes = [];
		var compareIndex    = -1;
		
		//var string_copy  = string.slice();
		//var compare_copy = compare.slice();
		
		
		//console.log(string);
		
		$.each(string, function(index, elt){
			
			if(!elt) return;
			
			
			elt          = elt.trim();
			compareIndex = jQuery.inArray(elt, compare);
			

			
			
			if(compareIndex != -1){  //the elt was found in the compare array - remove it
				
				//matched_indexes.push({"stringIndex": index, "compareIndex": compareIndex});
				
				item       = string[index];                  
				string[index] = "";//remove from 'string'
				if(removeFromBoth) compare[compareIndex] = "";  //remove from 'compare'
				if(returnMatches) matches.push(item);                //add to matches array
			}
			
			
		});
		
		
		//remove empty values
		var si = (string.length - 1);
		var ci = (compare.length - 1);
		
		while(string.length >= si && si > -1){
			
			if(string[si] == ""){
				
				string.splice(si, 1);
				si = (string.length - 1);
			}
			else si--;
		}
		
		
		if(removeFromBoth){
			
			while(compare.length > ci){
				
				if(compare[ci] == "") compare.pop();
				else ci++;
			}
			
			
			while(compare.length >= ci && ci > -1){
				
				if(compare[si] == ""){
					
					compare.splice(ci, 1);
					ci = (compare.length - 1);
				}
				else ci--;
			}
			
		} 

		return [string, compare, matches];
	}
	
	
	
	
	
	function removeCheckboxMatchesFromTable(string, tableName){
			
		var opp_table = $("div.keywords-interface table[data-table='" + tableName + "']");
		return false;
	}
	
	
	
	function removeMatchesFromHiddenInput(string, inputName){
			
		var opp_input = $("div.keywords-interface input[name='" + inputName + "']");
		return false;
	}
	
	
	
	/*
	* We process the keywords as follows
	* 1. Self              : Remove (from string) all duplicates in own string
	* 2. Keywords          : Remove (from string) all duplicates from hidden input - keywords
	* 3. Default keywords  : Remove (from string) all duplicates from hidden input - default_keywords
	* 4. Negative keywords : Remove (from input)  all duplicates from string
	* 5. Unused Defaults   : If exists in Unused, remove and add to default, remove item from string
	* RETURN THE ARRAY OF CSV LITS FOR EACH INPUT VALUE
	*/
	function keywordsScreenUpdate(button){
		
		//input
		var wrap     = button.parents("div.input-group");  //the button/input wrapper
		var inputElt = wrap.find("input[type='text']");     //the input element
		var input_val = inputElt.val();                      //the input value
		var curr_kw   = $("input[name='keywords']:hidden").val();
		
		input_val = input_val + "," + curr_kw;
		
		//hidden inputs
		var hiddens = $("div.keywords-interface input:hidden");  //all the hidden inputs for the modal
		
		
		//console.log(input_val);


		//Remove (from string) all duplicates in own string
		input_val = stringCSVRemoveDuplicates(input_val);
		
		
		/*
		//Remove (from string) all duplicates from hidden input - keywords
		var keywords = hiddens.filter("[name='keywords']");
		keywordsVal  = keywords.val().split(",");
		input_val    = stringCSVRemoveMatches(input_val, keywordsVal)[0];
		*/
		
		
		
		//Remove (from string) all duplicates from hidden input - default_keywords
		var defaults = hiddens.filter("[name='default_keywords']");
		defaultsVal  = defaults.val().split(",");
		input_val    = stringCSVRemoveMatches(input_val, defaultsVal)[0];
		
		
		console.log(input_val);
		
		
		//Remove (from string) all duplicates from hidden input - additional_keywords
		var adds  = hiddens.filter("[name='additional_keywords']");
		addsVal   = adds.val().split(",");
		input_val = stringCSVRemoveMatches(input_val, addsVal)[0];
	
		
		//Remove (from input)  all duplicates from string - negative keywords
		var negkw = hiddens.filter("[name='negative_keywords']");
		negkwVal  = negkw.val().split(",");
		negkwVal  = stringCSVRemoveMatches(negkwVal, input_val);
		
		
		
		negkwVal  = negkwVal[0];
		
		
		//If exists in Unused, remove and add to default, remove item from string
		var input_val_copy = input_val;
		var unukw          = hiddens.filter("[name='unused_defaults']");
		unukwVal           = unukw.val().split(",");

		
		//remove matches from both value sets
		var filter  = stringCSVRemoveMatches(input_val, unukwVal, true, true);
		var matches = filter[2];   //the matches
		input_val   = filter[0];   //the input values (new keywords)
		unukwVal    = filter[1];   //the new unused defaults
		

		//add matches to the default keywords list
		defaultsVal += matches.join(",");
		
		inputElt.val(""); 
		
		return {
			"keywords"         : input_val.join(","),
			"negative_keywords": negkwVal.join(","),
			"default_keywords" : defaultsVal,
			"unused_defaults"  : unukwVal.join(",")
		};
	}
	
	
	
	
	
	/*
	* We process the negative keywords as follows
	* 1. Self              : Remove (from string) all duplicates in own string
	* 2. Negative Keywords : Remove (from string) all duplicates from hidden input - negative keywords
	* 3. Unused Defaults   : Remove (from string) all duplicates from hidden input - unused_defaults
	* 4. Keywords          : Remove (from input)  all duplicates from string
	* 5. Default Keywords  : If exists in Defaults, remove and add to unused, remove item from string
	* RETURN THE ARRAY OF CSV LITS FOR EACH INPUT VALUE
	*/
	function negative_keywordsScreenUpdate(button){
		
		//input
		var wrap      = button.parents("div.input-group");  //the button/input wrapper
		var inputElt  = wrap.find("input[type='text']");     //the input element
		var input_val = inputElt.val();                      //the input value
		var curr_kw   = $("input[name='negative_keywords']:hidden").val();
		
		input_val = input_val + "," + curr_kw;
		
		//hidden inputs
		var hiddens = $("div.keywords-interface input:hidden");  //all the hidden inputs for the modal


		//Remove (from string) all duplicates in own string
		input_val = stringCSVRemoveDuplicates(input_val);
		

		/*
		//Remove (from string) all duplicates from hidden input - negative_keywords
		var negKw = hiddens.filter("[name='negative_keywords']");
		negKwsVal = negKw.val().split(",");
		input_val = stringCSVRemoveMatches(input_val, negKwsVal)[0];
		*/
		
		
		//Remove (from string) all duplicates from hidden input - unused_defaults
		var unu   = hiddens.filter("[name='unused_defaults']");
		unuVal    = unu.val().split(",");
		input_val = stringCSVRemoveMatches(input_val, unuVal)[0];
		

		//Remove (from input)  all duplicates from string - keywords
		var keywords = hiddens.filter("[name='keywords']");
		keywordsVal  = keywords.val().split(",");
		keywordsVal  = stringCSVRemoveMatches(keywordsVal, input_val)[0];
		

		//If exists in Defaults, remove and add to unused, remove item from string
		var input_val_copy = input_val;
		var defaults       = hiddens.filter("[name='default_keywords']");
		defaultsVal        = defaults.val().split(",");


		//remove matches from both value sets
		var filter  = stringCSVRemoveMatches(input_val, defaultsVal, true, true);
		var matches = filter[2];   //the matches
		input_val   = filter[0];   //the input values (new keywords)
		defaultsVal = filter[1];   //the new unused defaults
		

		//add matches to the unused list
		unuVal += matches.join(",");
		
		inputElt.val(""); 
		
		
		return {
			"keywords"         : keywordsVal.join(","),
			"negative_keywords": input_val.join(","),
			"default_keywords" : defaultsVal.join(","),
			"unused_defaults"  : unuVal
		};
	}
	
	
	
	
	
	
	
	
	
	
	
	
	function keywords_updateAdditionalKeywords(doClear = false){
		
		var mk        = $("div.more-keywords");
		var mk_checks = mk.find("input:checkbox");
		var mk_input  = $("input[name='additional_keywords']:hidden");
		var mk_bool   = $("input[name='has_additional']:hidden");
		var new_val   = [];
			
			
		if(!doClear){
			
			mk_checks.each(function(index, elt){
			
				var ch_val = $(elt).val();
				
				if($(elt).is(":checked")) new_val.push(ch_val);
				
			});
			new_val = new_val.join(",");
			
			mk_input.val(new_val);
			mk_bool.val(1);
		}
		else{
			mk_input.val("");
			mk_bool.val(0);
			
		}
	}
	
	
	
	
	
	

	
	
	/*
	Section display functions
	*/
	
	function keywords_toggleAddKeywords(){
		
		$("span.see-more").on("click", function(){
			
			var mk        = $("div.more-keywords");
			//var mk_checks = mk.find("input:checkbox");
			//var mk_input  = $("input[name='additional_keywords']:hidden");
			//var mk_val    = mk_input.val();
			
			var doClear = mk.is(":visible");
			//var new_val   = "";
			
			keywords_updateAdditionalKeywords(doClear);
			
			
			if(!doClear){ $(this).text("Show fewer default keyword options..."); }
			else{ $(this).text("Show more default keyword options..."); }
			
			mk.toggle();
		});
	}
	
	
	function keywords_toggleAdvKeywords(){
		
		$("body").on("click", "span.advanced-keyword-opts", function(){
			
			var ak = $("div.advanced-keywords");
			
			ak.toggle();
			if(ak.is(":visible")){ $(this).text("Hide Advanced Keyword Options..."); }
			else{ $(this).text("Advanced Keyword Options..."); }
		});
	}
	
	
	
	
	
	
	/*
	Check box area display
	*/
	
	function keywords_buildKeywordSectionDisplays(){
		
		
		
		var new_keyword_sections = keywords_getCheckboxSections();
		
		//console.log(new_table_rows);
		
		var lists = $("div.kw_wrap");

		lists.each(function(index, elt){
			
			var kw_sec   = $(this).find("div.keywords-list");
			var name     = $(this).attr("data-table"); //the name of the hidden input the data came from
			var new_html = $.parseHTML(new_keyword_sections[name]);
			
			console.log(new_html.length);
			
			if(new_html.length == 0) $(this).find("p.list-empty").show();
			else $(this).find("p.list-empty").hide();
			
			
			kw_sec.children().remove();
			kw_sec.append(new_html);
		});
		
		removeChecksFromSubmit();
	}
	
	
	
	
	
	function keywords_getCheckboxSections(){
		
		var words = {
			"keywords"            : $("input[name='keywords']:hidden").val(),
			"additional_keywords" : $("input[name='additional_keywords']:hidden").val(),
			"negative_keywords"   : $("input[name='negative_keywords']:hidden").val(),
			"default_keywords"    : $("input[name='default_keywords']:hidden").val(),
			"unused_defaults"     : $("input[name='unused_defaults']:hidden").val(),
		};
		
		var tables = {
			"keywords"            : "",
			"additional_keywords" : "",
			"negative_keywords"   : "",
			"default_keywords"    : "",
			"unused_defaults"     : "",
		}
		
		
		$.each(words, function(index, value){
			
			if(value == "") return;
			
			var type      = $("div#"+index+"-wrap");
			var alrtVal   = type.attr("data-alert");
			var selected  = (index != "unused_defaults");
			
			tables[index] = keywords_getKeywordCheckboxElts(value.split(","), selected, alrtVal);
		});
		
		return tables;
	}
	
	
	function keywords_getKeywordCheckboxElts(values, selected, alrtVal){
		
		var html = "";
		var last_index = 0;
		
		if(!values) return html;
		
		html += "<div class='col-sm-12'>";
		
		$.each(values, function(i, v){
			
			last_index = i;
			
			//if(i%3 == 0) html += "<div class='col-sm-12'>";
			
			var chckd = selected? " checked" : "";  
			
			var kw_html = "<input type='checkbox' name='"+v+"' value='"+v+"'"+chckd+" class='no_sub'>&nbsp;";
			kw_html += "<label>"+v+"</label>&emsp;";
			
			kw_html = "<div class='keyword-check alert alert-" + alrtVal + "'>" + kw_html + "</div>";
			kw_html = "<div class='keyword-col'>" + kw_html + "</div>";
			
			html += kw_html;
			
			//if(i%3 == 2) html += "</div>";
		});
		
		//if(last_index%3 != 2) html += "</div></div>";
		html += "</div>";
		
		return html;
	}
	
	
	
	
	
	
	
	
	
	
	
	$(function(){
		
		//add the class that will stop the checkboxes from being submitted on the page
		removeChecksFromSubmit();
		
		
		//handle toggleing the additional keywords section
		keywords_toggleAddKeywords();
		
		
		//handle toggleing the advanced options
		keywords_toggleAdvKeywords();
		
		
		
		if(show_add_keywords) $("span.see-more").click();
		if(show_adv_opts)     $("span.advanced-keyword-opts").click();
		
		
		
		keywords_buildKeywordSectionDisplays();
		keywords_updateAdditionalKeywords(false);
		
		
		
		
		//when a user clicks a checkbox we find out what table that check box is in
		// - if it is in the default keywords wrap, we uncheck the box and
		//   send the table row to the unused defaults table. If this leaves the
		//   section empty, we show the message for no default keywords
		// - if it is in the unused defaults table, we check the box and send it token_get_all
		//   the default keywords section. I fthis leaves the section empty we show the message
		//   for no unused defaults
		// - if it is in the keywords table or negative keywords table, we uncheck the box but 
		//   leave it there in case the user changes their mind before submitting the form.
		
		
		// 1. find the value of the check box
		// 2. find the inputs for adding and removing the value
		// 3. update the hidden inputs
		// 4. rebuild the tables from hidden inputs
		$("body").on("change", "div.keywords-interface input[type='checkbox']", function(){
			
			var parent_table        = $(this).parents("div.kw_wrap");
			var val                 = $(this).val();                     //value of the current keyword box (the keyword)
			var add_to_hidden_name  = parent_table.attr("data-copy");    //the hidden input we add this value to
			var rm_from_hidden_name = parent_table.attr("data-table");   //the hidden input we remove this value from
			
			
			if(rm_from_hidden_name == "additional_keywords"){
				
				keywords_updateAdditionalKeywords(false);
				return false;
			}
			
			
			var remove_inp = $("input[name='"+rm_from_hidden_name+"']:hidden");   //the hidden input we are removing the value from
			var add_inp    = $("input[name='"+add_to_hidden_name+"']:hidden");    //the hidden input we are adding the value to
			
			
			var rm_old_val  = remove_inp.val();   //current value of the remove-from input (pre-change)
			var add_old_val = add_inp.val();      //current value of the add-to input (pre-change)
			
			
			var add_new_val = add_old_val + ((add_old_val == "")? "" : ",") + val;      //the new value for the add-to input
			var rm_new_val  = stringCSVRemoveMatches(rm_old_val.split(","), [val])[0];  //the new value for the remove-from input
			
			console.log("we are removing this value: " + val);
			console.log("from this list: " + rm_old_val);
			console.log("it will then look like this: " + rm_new_val);
			console.log("we then add the value to this: " + add_old_val);
			console.log("it will then look like this: " + add_new_val);
			
			remove_inp.val(rm_new_val);  //set the values for the inouts
			add_inp.val(add_new_val);
			
			keywords_buildKeywordSectionDisplays();   //build the display sections from the input values
		})
		
		
		
		
		//when the user clicks and '.add-keywords' button, we collect the input
		//as comma separated values, and build an array of string that represents
		//the new values for each category of word (kw, neg-kw, def, unu-def)
		//update the hidden inputs
		$("body").on("click", "button.add-keywords", function(){
			
			var result = null;
			
			var type = $(this).attr("data-type");
			
			if(type == "keywords") result = keywordsScreenUpdate($(this));
			else                   result = negative_keywordsScreenUpdate($(this));
			
			
			//result now contains 4 csv strings, one for each hidden input for KW modal
			//place these values in the inputs
			$.each(result, function(index, value){ $("input[name='" + index + "']:hidden").val(value);});
			
			
			
			keywords_buildKeywordSectionDisplays();
			
			
			//build the html display tables from the new input values
			//setKeywordTables();
		});
		
		
		
		$("button.kw-continue").on("click", function(){
			
			
			var submission = $( ".keywords-interface > input:hidden" ).serialize();
			console.log(submission);

			
			location.href = "?" + submission;
		})
	});
	
	
	
	
	

</script>

</body>
</html>