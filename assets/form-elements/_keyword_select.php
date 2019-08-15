<?php 
	
	
	//get the currently selected Industry
	$industry  = @$cpnVals["industry"];
	
	//get keyword record
	$keywordData = getCampaignKeywords($cpnVals["num"]);
	
	//get the html display for keywords
	$keywords          = trim($keywordData->get("keywords"),          ", ");
	$negative_keywords = trim($keywordData->get("negative_keywords"), ", ");
	$default_keywords  = trim($keywordData->get("default_keywords"),  ", ");
	$unused_defaults   = trim($keywordData->get("unused_defaults"),   ", ");
	
	$keywords          = $keywords?          explode(",", $keywords)          : array();
	$negative_keywords = $negative_keywords? explode(",", $negative_keywords) : array();
	$default_keywords  = $default_keywords?   explode(",", $default_keywords)  : array();
	$unused_defaults   = $unused_defaults?   explode(",", $unused_defaults)   : array();
	
	$keywordHTML          = getKeywordCheckBoxTableRows($keywords);
	$negative_keywordHTML = getKeywordCheckBoxTableRows($negative_keywords);
	$default_keywordHTML  = getKeywordCheckBoxTableRows($default_keywords);
	$unused_defaultHTML   = getKeywordCheckBoxTableRows($unused_defaults);
	
	$blankCheck           = getCheckBoxElement("kw_blank", "", false, true, "", array("disabled" => "disabled"));
	
?>


<div class="row">

	<input type="hidden" name="keywords"          value="<?php echo implode(",", $keywords); ?>" />
	<input type="hidden" name="negative_keywords" value="<?php echo implode(",", $negative_keywords); ?>" />
	<input type="hidden" name="default_keywords"  value="<?php echo implode(",", $default_keywords); ?>" />
	<input type="hidden" name="unused_defaults"   value="<?php echo implode(",", $unused_defaults); ?>" />

	
	<div class="col-md-12" style="display:none;">
		<?php echo $blankCheck; ?>
	</div>
	
	<!-- default keywords selection -->
	<div id="default_keywords-wrap" class="col-md-12 err-box">	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Default Keywords Label Modal"]; ?></label><br/>
		<p style="font-size:14px;">We have chosen these default keywords based on your industry.</p>
		
		<table class="table table-striped keywords-list" data-table='default_keywords' data-copy='unused_defaults'>
			<tbody>
				<?php echo $default_keywordHTML; ?>
			</tbody>
		</table>
		<?php if(empty($default_keywords)) : ?>
			<p><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Default Keywords List Empty"]; ?></p>
		<?php endif; ?>
		<hr />
	</div>
	
	<!-- unused default keywords selection -->
	<div id="unused_defaults-wrap" class="col-md-12 err-box">	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Unused Defaults Label Modal"]; ?></label><br/>
		<p style="font-size:14px;">Any default keywords you chose not to use appear here. The are not used in your Campaign.</p>
		
		
		<table class="table table-striped keywords-list" data-table='unused_defaults' data-copy='default_keywords'>
			<tbody>
				<?php echo $unused_defaultHTML; ?>
			</tbody>
		</table>
		<?php if(empty($unused_defaults)) : ?>
			<p><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Unused Default List Empty"]; ?></p>
		<?php endif; ?>
		<hr />
	</div>
	
	
	<!-- custom keywords selection -->
	<div id="keywords-wrap" class="col-md-12 err-box">	
		<label>Custom Keywords</label><br/>
		<p style="font-size:14px;">You can add your own keywords to this campaign. Fill out the input below, seperating your keywords with commas.</p>
		
		<table class="table table-striped keywords-list" data-table='keywords' data-copy=''>
			<tbody>
				<?php echo $keywordHTML; ?>
			</tbody>
		</table>
		<?php if(empty($keywords)) : ?>
			<p style="margin-bottom:20px;"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Keyword List Empty"]; ?></em></p>
		<?php endif; ?>
		
		<p style="margin-bottom:5px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Prompt"]; ?></p>
		<div class="input-group">
			<input 
				class="form-control no_sub" 
				type="text" 
				name="keywords" 
				value="<?php echo $title; ?>" 
				placeholder="Your Custom keywords"  
				style="margin-top:0px;"
			/>
			<span class="input-group-btn">
				<button 
					class="btn btn-danger add-keywords" 
					data-type="keywords" 
					data-opp="negative_keywords" 
					type="button"
					style="font-size:21px; padding:12px 18px;"
				><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Button Text"]; ?></button>
			</span>
		</div>
		<hr />
	</div>
	
		
	<!-- negative keywords selection -->
	<div id="negative_keywords-wrap" class="col-md-12 err-box">	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Negative Keywords Label Modal"]; ?></label><br/>
		<p style="font-size:14px;">You can add keywords for your Campaign to avoid. Fill out the input below, seperating your keywords with commas.</p>
		
		<table class="table table-striped keywords-list negative_keywords" data-table='negative_keywords' data-copy=''>
			<tbody>
				<?php echo $negative_keywordHTML; ?>
			</tbody>
		</table>
		<?php if(empty($negative_keywords)) : ?>
			<p style="margin-bottom:20px;"><em><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Negative Keyword List Empty"]; ?></em></p>
		<?php endif; ?>
		
		<p style="margin-bottom:5px;"><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Negative Keyword Prompt"]; ?></p>
		<div class="input-group">
			<input 
				class="form-control no_sub" 
				type="text" 
				name="negative_keywords" 
				value="<?php echo $title; ?>" 
				placeholder="Your Custom keywords"  
				style="margin-top:0px;"
			/>
			<span class="input-group-btn">
				<button 
					class="btn btn-danger add-keywords" 
					data-type="negative_keywords" 
					data-opp="keywords" 
					type="button"
					style="font-size:21px; padding:12px 18px;"
				><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Add Keyword Button Text"]; ?></button>
			</span>
		</div>
	</div>
</div>


<script>
	//handles the input events for location selection
	
	//build the html display tables from the new input values
	function setKeywordTables(){
		
		
		var new_table_rows = buildKeywordTablesRows();
		
		//console.log(new_table_rows);
		
		var tables = $("table.keywords-list");
		tables.each(function(index, elt){
			
			var name     = $(this).attr("data-table"); //the name of the hidden input the data came from
			var new_html = $.parseHTML(new_table_rows[name]);
			var tbody    = $(this).find("tbody");
			
			
			tbody.children().remove();
			tbody.append(new_html);
		});
		
		removeChecksFromSubmit();
	}
	
	
	
	
	
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
		
		var item         = null;   //when we remove item from an array
		var matches      = [];     //the matched values
		var compareIndex = -1;
		
		
		
		$.each(string, function(index, elt){
			
			if(!elt) return;
			
			elt = elt.trim();
			var compareIndex = jQuery.inArray(elt, compare);
			if(compareIndex != -1){
				
				item = string.splice(index, 1)[0];                   //remove from 'string'
				if(removeFromBoth) compare.splice(compareIndex, 1);  //remove from 'compare'
				if(returnMatches) matches.push(item);                //add to matches array
			}
		});
		
		return [string, compare, matches];
	}
	
	
	
	
	
	function removeCheckboxMatchesFromTable(string, tableName){
			
		var opp_table = $("div#keywordsModal table[data-table='" + tableName + "']");
		return false;
	}
	
	
	
	function removeMatchesFromHiddenInput(string, inputName){
			
		var opp_input = $("div#keywordsModal input[name='" + inputName + "']");
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
		
		//hidden inputs
		var hiddens = $("div#keywordsModal input:hidden");  //all the hidden inputs for the modal


		//Remove (from string) all duplicates in own string
		input_val = stringCSVRemoveDuplicates(input_val);
		
		
		//Remove (from string) all duplicates from hidden input - keywords
		var keywords = hiddens.filter("[name='keywords']");
		keywordsVal  = keywords.val().split(",");
		input_val    = stringCSVRemoveMatches(input_val, keywordsVal)[0];
		
		
		//Remove (from string) all duplicates from hidden input - default_keywords
		var defaults = hiddens.filter("[name='default_keywords']");
		defaultsVal  = defaults.val().split(",");
		input_val    = stringCSVRemoveMatches(input_val, defaultsVal)[0];
		
		
		//Remove (from input)  all duplicates from string - negative keywords
		var negkw = hiddens.filter("[name='negative_keywords']");
		negkwVal  = negkw.val().split(",");
		negkwVal  = stringCSVRemoveMatches(negkwVal, input_val)[0];
		
		
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
		
		//hidden inputs
		var hiddens = $("div#keywordsModal input:hidden");  //all the hidden inputs for the modal


		//Remove (from string) all duplicates in own string
		input_val = stringCSVRemoveDuplicates(input_val);
		

		//Remove (from string) all duplicates from hidden input - negative_keywords
		var negKw = hiddens.filter("[name='negative_keywords']");
		negKwsVal = negKw.val().split(",");
		input_val = stringCSVRemoveMatches(input_val, negKwsVal)[0];
		
		
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
	
	
	
	
	
	
	/*
	* Collect keyword data from the hidden inputs and build display tables
	* from those values
	*/
	function buildKeywordTablesRows(){
		
		var words = {
			"keywords"          : $("input[name='keywords']:hidden").val(),
			"negative_keywords" : $("input[name='negative_keywords']:hidden").val(),
			"default_keywords"  : $("input[name='default_keywords']:hidden").val(),
			"unused_defaults"   : $("input[name='unused_defaults']:hidden").val(),
		};
		
		var tables = {
			"keywords"          : "",
			"negative_keywords" : "",
			"default_keywords"  : "",
			"unused_defaults"   : "",
		}
		
		//console.log(words);
		
		$.each(words, function(index, value){
			
			if(value == "") return;
			
			var selected = (index != "unused_defaults");
			tables[index] = buildKeywordTableRows(value.split(","), selected);
		});
		
		return tables;
	}	
	
	
	
	
	function buildKeywordTableRows(values, selected = false){
			
		var html = "";
		
		
		$.each(values, function(index, value){
			
			var rowStart = (index % 2 == 0);  //2 columns: if true we create a tr open, otherwise a tr close
			
			html += (rowStart)? "<tr class='kw_check_wrap'><td>" : "<td>";
			html += buildKeywordCheckBox(value, selected);
			html += (rowStart)? "</td>" : "</td></tr>";
		});
		
		return html;
	}
	
	
	
	function buildKeywordCheckBox(val, selected){
			
		var html = "";
		var selected = (selected)? " checked" : "";
		var label = val.replace("_", " ");
		
		html += "<input type='checkbox' name='"+val+"' value='"+val+"'"+selected+"/>&nbsp;<label>"+label+"</label>&emsp;";
		
		return html;
	}
	
	
	
	
	
	
	
	
	
	$(function(){
		
		//add the class that will stop the checkboxes from being submitted on the page
		removeChecksFromSubmit();
		
		
		
		
		
		
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
		$("body").on("change", "div#keywordsModal input[type='checkbox']", function(){
			
			var parent_table        = $(this).parents("table.keywords-list")
			var val                 = $(this).val();
			var add_to_hidden_name  = parent_table.attr("data-copy");   //the hidden input we add this value to
			var rm_from_hidden_name = parent_table.attr("data-table");  //the hidden input we remove this value from
			var row_node            = $(this).parents("td");            //the td elt the box is in
			
			
			var remove_inp = $("input[name='"+rm_from_hidden_name+"']:hidden");
			var add_inp    = $("input[name='"+add_to_hidden_name+"']:hidden");
			
			var rm_old_val  = remove_inp.val();
			var add_old_val = add_inp.val();
			
			var add_new_val = add_old_val + ((add_old_val == "")? "" : ",") + val;
			var rm_new_val  = stringCSVRemoveMatches(rm_old_val.split(","), [val])[0];
			
			//console.log("rm_old: " + rm_old_val);
			//console.log("rm_new: " + rm_new_val);
			//console.log("add_old: " + add_old_val);
			//console.log("add_new: " + add_new_val);
			
			remove_inp.val(rm_new_val);
			add_inp.val(add_new_val);
			
			setKeywordTables();
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
			
			//build the html display tables from the new input values
			setKeywordTables();
		});
	});

</script>