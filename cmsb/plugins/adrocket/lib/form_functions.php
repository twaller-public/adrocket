<?php



/**
* campaignSectionEditButton returns the html for a modal activating button
* for the campaign create page
*/
function campaignSectionEditButton($section, $disabled = false){
	
	$onClick = '$("#' . $section . 'Modal").modal();';
	$html    = "<button class='btn btn-success' onClick='$onClick'";
	
	if($disabled) $html .= " disabled";
	$html .= ">Edit</button>";
	return $html;
}




function getHTMLOptionsFromTableName($table, $opts = array("where" => "TRUE"), $selectedVal = 0, $valueCol = "num", $textCol = "title", $emptyFirstOpt = true){
	
	$records = new MultiRecordType($table, $opts, true);   //load the records
	
	if(!$records->table())                 return "";     //invalid table?
	if($records->meta()["noRecordsFound"]) return "";     //no records found?
	
	$records = $records->records();
	
	return getHTMLOptionsFromRecordData($records, $selectedVal, $valueCol, $textCol, $emptyFirstOpt);
}




function getHTMLOptionsFromRecordData($records, $selectedVal = 0, $valueCol = "num", $textCol = "title", $emptyFirstOpt = true){
	

	$html = ($emptyFirstOpt)? "<option value='0'>-- Please Select --</option>" : "";
	
	if(!$records) return $html;
	
	$record = $records[0];
	
	if(!@$record->get($valueCol)) return $html;
	if(!@$record->get($textCol))  return $html;
	
	foreach($records as $r){
		
		$value    = $r->get("$valueCol");
		$text     = $r->get("$textCol");
		$selected = ($value == $selectedVal)? " selected" : "";

		$html    .= "<option value='$value'$selected>$text</option>";
	}
	
	
	return $html;
}



function getHTMLOptionsFromArray($data, $selectedVal = 0, $valueCol = 0, $textCol = 0, $emptyFirstOpt = true){
	
	$html = ($emptyFirstOpt)? "<option value='0'>-- Please Select --</option>" : "";
	
	if(!$data) return $html;
	
	if($valueCol && !$data[0][$valueCol]) return $html;
	if($textCol  && !$data[0][$textCol])  return $html;
	
	foreach($data as $index=>$d){
		
		$value    = $valueCol? $data[$index][$valueCol] : $index;
		$text     = $textCol?  $data[$index][$textCol]  : $d;
		$selected = ($value == $selectedVal)? " selected" : "";
		
		$html    .= "<option value='$value'$selected>$text</option>";
	}
	
	return $html;
}




function getCheckboxesFromFromRecordData($records, $checkedVals = array(), $valueCol = "num", $nameCol = "title", $doLabel = true, $labelCol = "title", $otherAttr = array()){
	
	$html = "";
	
	if(!$records) return $html;
	
	$record = $records[0];
	
	if(!@$record->get($valueCol))             return $html;
	if(!@$record->get($nameCol))              return $html;
	if($doLabel && !@$record->get($labelCol)) return $html;
	
	
	foreach($records as $r){
		
		$value              = $r->get("$valueCol");
		$name               = $r->get("$nameCol");
		$checked            = in_array($value, $checkedVals);
		if($doLabel) $label = $r->get("$labelCol");
		$otatt              = array();
		
		foreach($otherAttr as $attr=>$attrCol){
			
			//if the attribute field does not exist in the record - just skip
			
			if(!@$r->get($attrCol)) continue;
			
			$val         = $r->get($attrCol);
			$otatt[$attr] = $val;
		}
		
		$html .= "<span class='loc_check_wrap' style='display:inline-block'>";
		$html .= getCheckBoxElement($name, $value, $checked, $doLabel, $label, $otatt);
		$html .= "</span>";
	}
	
	return $html;
}



function getCheckBoxElement($name = "", $value = "", $checked = false, $doLabel = false, $labelText = "", $otherAttr = array()){
	
	$html          = "";
	$checked       = $checked? " checked" : "";
	$otherAttrText = "";
	
	foreach($otherAttr as $attr=>$val){ $otherAttrText .= " $attr='$val'"; }
	
	$html              .= "<input type='checkbox' name='$name' value='$value'$otherAttrText$checked/>";
	if($doLabel) $html .= "&nbsp;<label>$labelText</label>&emsp;";
	
	return $html;
}



function getKeywordCheckBoxTableRows($words = array(), $cols = 3, $checked = false, $doLabel = true, $otherAttr = array()){
	
	$html = "";
	
	if(!$words) return $html;

	//showme($words);
	
	
	foreach($words as $index=>$w){
		
		$value              = $w;
		$name               = $w;
		$checked            = true;
		if($doLabel) $label = $w;
		$rowStart           = ($index%$cols == 0);
		$rowEnd             = ($index%$cols == ($cols - 1));
		
		echo "$index:$cols:$rowStart:$rowEnd<br />";
		
		foreach($otherAttr as $attr=>$attrCol){
			
			//if the attribute field does not exist in the record - just skip
			if(!@$r->get($attrCol)) continue;
			
			$val              = $r->get($attrCol);
			$otherAttr[$attr] = $val;
		}
		
		$html .= $rowStart? "<tr class='kw_check_wrap'><td><span class='keyword-check'>" : "<td><span class='keyword-check'>";
		$html .= getCheckBoxElement($name, $value, $checked, $doLabel, $label, $otherAttr);
		$html .= $rowEnd? "</span></td></tr>" : "</span></td>";
	}
	
	return $html;
	
}


function getDayPartTimeSelect($name, $options){
	
	$html = "<select class='form-control' name='$name'>";
	$html .= "<option value='0'>-- Select --</option>";
	
	foreach($options as $key=>$val){
		
		$html .= "<option value='$val'>$key</option>";
	}
	
	$html .= "</select>";
	return $html;
}



function getDaypartingTableHTML($dp_details, $displayOnly = false){
		
	$html = "";
	
	$dp_time_options      = getDayPartingOptions();
	$time_select_template = getDayPartTimeSelect("##NAME##", $dp_time_options);
	
	foreach($dp_details["days"] as $day){
		
		$start_content = ($displayOnly)? getDayPartingOptionNameFromValue($day["start"]["val"]) : str_replace("##NAME##", $day["start"]["col"], $time_select_template);
		$end_content   = ($displayOnly)? getDayPartingOptionNameFromValue($day["end"]["val"])   : str_replace("##NAME##", $day["end"]["col"],   $time_select_template);
		
		$html .= "<tr><td>{$day["name"]}</td><td>$start_content</td><td>$end_content</td></tr>";
	}
	
	return $html;
}





function getViewerTable($tableName, $fieldFilters = array(), $searchOpts = array("loadPseudoFields" => 0, "loadCreatedBy" => 0)){
	
	$table = new MultiRecordType($tableName, $searchOpts, true);
	$html  = "";

	if($table->init_results()["hasError"]) $html = "Table Name Not Valid";
	else{
		
		$html  = getViewerTableHeaders($tableName, $fieldFilters);
		$html .= getViewerTableRows($table, $fieldFilters);
	}
	
	
	$html = "<table class='table table-striped'>$html</table>";
	return $html;
}


function getViewerTableHeaders($tableName, $fieldFilters = array()){
	
	$cols = getMySqlColsAndType(getTableNameWithPrefix($tableName));
	$names = array();
	
	$html = "";
	//showme($cols);
	
	
	foreach($cols as $i=>$c){
		
		if(!in_array($i, $fieldFilters)) $html .= "<th>$i</th>";
	}
	
	$html = "<tr>$html</tr>";
	return $html;
}


function getViewerTableRows($records, $fieldFilters = array()){
	
	$html = "";
	
	if($records->meta()["noRecordsFound"]) return "<tr><th>No Records Found</th></tr>";
	
	$records = $records->records();
	
	foreach($records as $record){
		
		$html .= getViewerTableRow($record, $fieldFilters);
	}
	
	return $html;
}



function getViewerTableRow($record, $fieldFilters = array()){
	
	
	$html = "";
	
	
	//showme($record);
	$values = $record->vals();
	
	foreach($values as $i=>$v){
		
		if(in_array($i, $fieldFilters)) continue;
		//echo "$i - ";
		$html .= "<td>$v</td>";
	}
	
	$html = "<tr>$html</tr>";
	
	return $html;
}

?>