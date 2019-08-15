<?php 
	$province      = $cpnVals["province"];   //currently selected province
	$province_name = $cpnVals["province:label"];
	$preset        = @$cpnVals["preset"];
	$locations     = @$cpnVals["locations"];
	$coordinates   = array();
	$location_text = "";
	
	if($locations){
		
		foreach($locations as $loc){
		
			$locName = $loc->get("location:label");
			//echo "$locName<br />";
			
			$opts = array("where" => mysql_escapef("title = ?", $locName));
			$coords = new MultiRecordType("map_coordinates", $opts, true);
			if(!$coords->meta()["NoRecordsFound"]){
				
				$coords = $coords->records()[0];
				$coordinates[$locName] = json_decode($coords->get("json_string"));
			}
		}
	}
	
	//record informatin related to province - NOT VALUES FOR CAMPAIGN
	//$preset_opts    = getPresetListForProvince($province);  save for later
	
	//are we showing the presets select element?
	$showPresets    = !empty($preset_opts);
	
	//html elements
	$optionHTML     = getHTMLOptionsFromArray($preset_opts, $preset);
?>


<div class="row">

	<!-- location preset (division) selection -->
	<div id="preset-select-wrap" class="col-md-6 col-md-offset-3 err-box" <?php if(!$showPresets) echo "style='display:none;'"; ?>>	
		<label><?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Presets Label Modal"]; ?><span class="red-ast">*</span></label><br/>
		<select class="form-control" id="preset" name="preset">
			<?php echo $optionHTML; ?>
		</select>
		<div class="col-md-12">
			<p class="text-center">OR</p>
		</div>
	</div>

	
	
	
</div>

<div class="row">
	<div class="col-md-12">
		<label>Click on the map below to highlight the locations where you would like your ad to run.</label>

	</div>
	<div class="col-md-12">
		<div id="map_canvas" style="width:100%; min-height: 400px; float: left;"></div>
	</div>
	
	<div class="col-md-12" style="padding-top:15px;">
		<label>Your Locations: <span id='selected_locations'>None</span>
	</div>
	
	<div class="col-md-12">
		<hr />
		<label>Need More Locations?</label>
		<p style="font-size:14px">Not finding your location? Enter the locations you can't find into the text box and we will do what we can to find them.</p>
		<textarea name="custom_location_text" style="width:100%; height:100px;"><?php echo @$location_text; ?></textarea>
	</div>
</div>

<div id="locations-loading" class="loading text-center" style="position:fixed; width:100%; height:100%; top:0; left:0; background-color:rgba(0,0,0,0.6); padding-top:150px;">
	<img src="/img/spinner.gif" alt="" style="width:200px; height:200px;"/>
</div>


<script>
	//handles the input events for location selection
	
	var map       = null;
	var latLng    = null;
	var geocoder  = null;
	var selected_locations = {<?php foreach($coordinates as $index=>$coords){ echo "\"$index\": " . json_encode($coords) . ","; }?>};
	
	
	
	
	function getLocation(infoElement, showtags = false, places = null) {
		if (navigator.geolocation) {
			return navigator.geolocation.getCurrentPosition(locateSuccess, locateError);
		} else {
			alert("Geolocation is not supported by this browser.");
		}
	}
	
	
	
	function locateSuccess(position){

		var str = "";
		//var loc_info = $("#loc_info");

		//alert(position.latlon);
		$.each(position.coords, function(index, val) {
			str += index + ":" + val + "<br />";
		});
		var map = initialize(position.coords.latitude, position.coords.longitude, 'map_canvas', 12, null);
		//loc_info.html(str);
		//alert(str);
		//$("#location_auto").toggle();
		
		return map;
	}




	function locateError(error){
		switch(error.code) {
			case error.PERMISSION_DENIED:
				console.log("User denied the request for Geolocation.");
				break;
			case error.POSITION_UNAVAILABLE:
				console.log("Location information is unavailable.");
				break;
			case error.TIMEOUT:
				console.log("The request to get user location timed out.");
				break;
			case error.UNKNOWN_ERROR:
				console.log("An unknown error occurred.");
				break;
		}	
		
		return false;
	}



	function initialize(lat, lon, mapID, zoom, options, showtags = false, places = null) {
		
		
		var latitude    = lat;    //<?php echo floatval(@$record['latitude']); ?>;
		var longitude   = lon;    //<?php echo floatval(@$record['longitude']); ?>;
		var mapCanvasId = mapID;  //'map_canvas';
		
		
		
		if (latitude) {
			
			
			
			if(!options){
				var mapOptions  = { mapTypeId: google.maps.MapTypeId.ROADMAP };
			}else{
				//update with custom options
				var mapOptions  = { mapTypeId: google.maps.MapTypeId.ROADMAP };
			}
			
			
			map         = new google.maps.Map(document.getElementById(mapCanvasId), mapOptions);
			latLng      = new google.maps.LatLng(latitude, longitude);
			geocoder    = new google.maps.Geocoder;
			
			
			if(selected_locations){
				
				var locs_string = "";

				$.each(selected_locations, function(index, elt){
					
					console.log(index);
					locs_string += index + ", "; 
					var loc_name = index;
					
					var outline = new google.maps.Data.Polygon([elt.outer[0]]);
					var feature = new google.maps.Data.Feature();
					feature.setProperty('title', index);
					feature.setGeometry(outline);

					map.data.add(feature);
					
				});
				
				console.log("String: " + locs_string);
				$("span#selected_locations").text(locs_string);
				/*Set the logic for the already selected locations display here*/
			}
			
			map.data.setStyle({
				strokeColor: '#FF0000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity: 0.35
			});
			
			
			
			map.addListener('click', function(e) {
				$("#locations-loading").show();
				//alert();
				//console.log(e.latLng);
				geocoder.geocode({'location': e.latLng}, function(results, status) { 
					
					console.log(results)
					if(!results){
						
						alert("No Location Found here.");
						return false;
					}
					//alert(results[1].address_components[0].long_name); 
					
					var loc_name = results[1].address_components;
					
					var send = $.ajax({
						url: "?",
						dataType: "json",
						data: {
							location_name: loc_name,
							cpn: $("input[name='num']:hidden").val()
							
						}
					});
					
					send.done(function(data){
						
						console.log(data);
						if(data["Not Found"]){
							
							alert("There was no valid location found at that point on the map.\nPlease Try Again.\nContact us if you think this is an error.\n");
							$("#locations-loading").hide();
							return false;
							
						}
						//console.log(JSON.parse(data.str));
						var result = JSON.parse(data.str)
						
						
						
						//old set style was here - replace here if bugged
						
						var outline = new google.maps.Data.Polygon([result.outer[0]]);
						var feature = new google.maps.Data.Feature();
						feature.setProperty('title', data.name);
						feature.setGeometry(outline);

						
						map.data.add(feature);
						
						
						$("span#selected_locations").text(data.locstr);
						$("#locations-loading").hide();
						
						
					});
					send.fail(function(data){console.log(data); $("#page-loading").hide();});
				});
			});
			
			
			//listen for a feature click
			map.data.addListener('click', function(e) {
							
				if(!e.feature) return;
				var this_feature = e.feature;
				var title = e.feature.getProperty('title');
				
				if(confirm("Remove " + title + " from your location selection?")){
					
					
					var send = $.ajax({
						url: "?",
						dataType: "json",
						data: {
							location_remove: title,
							cpn: $("input[name='num']:hidden").val()
							
						}
					});
					
					send.done(function(data){ console.log(data); map.data.remove(this_feature); $("span#selected_locations").text(data.locstr);});
					send.fail(function(data){ console.log(data); });
					
					
				}
				
				//alert();
				//console.log(e.feature);
			});

			map.setCenter(latLng);
			map.setZoom(zoom);
			
			
			
			//if(showtags) addPlaceMarkers(places, map);
		}
		
		
		return map;
	}


	function getNewAddressLocation(address, showtags = false, places = null){

		var geocoder = new google.maps.Geocoder();
		var zoom = 2;

		if (geocoder) {
			geocoder.geocode({ 'address': address }, function (results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				console.log(results[0].address_components);
				
				
				
				var lat = results[0].geometry.location.lat();
				var lon = results[0].geometry.location.lng();
				

				initialize(lat, lon, 'map_canvas', zoom, null, showtags, places);
				
			}
			else {
				alert("Geocoding failed: " + status);
				window.location.reload();
			}
			});
		}
	}
	
	
	
	
	
	$(function(){
		
		$("#locations-loading").hide();
		
		var preset_select  = $("select#preset");
		
		if(!getLocation()){
			
			var address = "Canada";
			getNewAddressLocation(address, false, null);
			
		}
		
		
		
		/**
		* When the Preset selection changes:
		*/
		$("body").on("change", "select#preset", function(){
			
			alert();

		});
		
		
		$("#locationsModal").on("shown.bs.modal", function(){ google.maps.event.trigger(map, 'resize'); })
		
	})

</script>