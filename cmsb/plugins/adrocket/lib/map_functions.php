<?php





function removeLocationFromMap(){
	
	$r = $_REQUEST['location_remove'];
	$cpnNum = @$_REQUEST['cpn'];
	
	if(!$cpnNum){
		
		$r = array("Not Found" => 1, "Detail" => "No Campaign Number passed");
	
		echo json_encode($r);
		exit();
	}
	
	
	
	$opts = array("where" => mysql_escapef("title = ?", $r));
	$location = new MultiRecordType("locations", $opts, true);
	
	if($location->meta()["noRecordsFound"]){
		
		$r = array("Not Found" => 1, "Detail" => "Not found in locations table");
	}
	else{
		
		$location = $location->records()[0];
		$locNum   = $location->get('num');
		
		$opts    = array("where" => mysql_escapef("location = ? AND campaign = ?", $locNum, $cpnNum));
		$cpnLocs = new MultiRecordType("campaign_locations", $opts, true);
		
		if($cpnLocs->meta()["noRecordsFound"]){
					
			//locations doesnt exist for campaign, do nothing
			$r = array("Not Found" => 1, "Detail" => "Not found in campaign locations table");
		}
		else{
			//delete the location
			$loc = $cpnLocs->records()[0];
			$loc->del();
			
			
			//load all the locations for the campaign and list their titles into a string
			$opts    = array("where" => mysql_escapef("campaign = ?", $cpnNum));
			$cpnLocs = new MultiRecordType("campaign_locations", $opts, true);
			
			$locString = "";
			
			if(!$cpnLocs->meta()["noRecordsFound"]){
				
				$cpnLocs = $cpnLocs->records();
				
				foreach($cpnLocs as $loc){
					
					$locString .= "{$loc->get('location:label')},";
				}
			}
			
			
			
			$r = array(
				"Not Found" => 0, 
				"num" => $cpnNum,
				"locstr" => $locString,
			);
		}
	}
	
	
	if(!$r) $r = array("Not Found" => 1, "Detail" => "Not found in coordinates Table");
	
	echo json_encode($r);
	exit();
	
	
	
}



function getLocationCoords($location_name){
	
	$r = $_REQUEST['location_name'];
	$cpnNum = @$_REQUEST['cpn'];
	$names = array();
	$detail = "";
	
	if(!$cpnNum){
		
		$r = array("Not Found" => 1, "Detail" => "No Campaign Number passed");
	
		echo json_encode($r);
		exit();
	}
	
	foreach($r as $l){
		
		array_push($names, $l["long_name"]);
	}
	
	$r = array();
	
	//echo json_encode($names);
	
	foreach($names as $n){
		
		$opts = array("where" => mysql_escapef("title = ?", $n));
		$coords = new MultiRecordType("map_coordinates", $opts, true);
		
		if(!$coords->meta()["noRecordsFound"]){
			
			$coords = $coords->records()[0];
			
			
			//find a location that matches the coords
			$location = new MultiRecordType("locations", $opts, true);
			if($location->meta()["noRecordsFound"]){
				
				$r = array("Not Found" => 1, "Detail" => "Not found in locations table");
			}
			else{
				
				$location = $location->records()[0];
				$locNum   = $location->get('num');
				
				$opts    = array("where" => mysql_escapef("location = ? AND campaign = ?", $locNum, $cpnNum));
				$cpnLocs = new MultiRecordType("campaign_locations", $opts, true);
				if($cpnLocs->meta()["noRecordsFound"]){
					
					//locations doesnt exist for campaign, add it
					
					$insert = array(
						"location" => $locNum,
						"campaign" => $cpnNum
					);
					
					$cpnLocs->create($insert);
					
				}
				else{
					//user already added location, do not add
					$detail = "Location is already added";
				}
				
				//load all the locations for the campaign and list their titles into a string
				$opts    = array("where" => mysql_escapef("campaign = ?", $cpnNum));
				$cpnLocs = new MultiRecordType("campaign_locations", $opts, true);
				
				$locString = "";
				
				if(!$cpnLocs->meta()["noRecordsFound"]){
					
					$cpnLocs = $cpnLocs->records();
					
					foreach($cpnLocs as $loc){
						
						$locString .= "{$loc->get('location:label')},";
					}
				}
				
				$r = array(
					"Not Found" => 0, 
					"name" => $coords->get("title"),
					"str" => $coords->get("json_string"),
					"num" => $cpnNum,
					"locstr" => $locString,
					"Detail" => $detail
				);
			}
			
			
			break;
		} 
	}
	
	if(!$r) $r = array("Not Found" => 1, "Detail" => "Not found in coordinates Table");
	
	echo json_encode($r);
	exit();
}



?>