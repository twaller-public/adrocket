
//handles the input events for location selection
	
var map       = null;
var latLng    = null;
var geocoder  = null;



$(function(){
		
		
	//input handlers
	locations_handleMapShowClick();
	locations_handleMapSearchClick();
	
	locations_removeLocationWithCheck();

	
	//some locations are already chosen, skip the first stage
	if(!(Object.keys(selected_locations).length === 0 && selected_locations.constructor === Object)){ $("button.map-show").click(); }
	
	
	
	$("#page-loading").hide();
	
	animateHeader1_show(fast_animation);
	
	
	//console.log(getLocation());
	
});


function locations_handleMapSearchClick(){
	
	//user clicks the search button
	$("button.map-search").on("click", function(){
		
		var locs      = $(this).parents("div.input-group").find("input.locations").first();
		var locs_val  = locs.val();
		var loc_list  = locs_val.split(",");
		var results   = [];
		var not_found = [];
		var send      = [];
		var first     = "";
		var draw      = !($(this).parents("div.map-show-wrap").length && true);
		//map           = new google.maps.Map(document.getElementById("map_canvas"), { mapTypeId: google.maps.MapTypeId.ROADMAP });
		
		if(locs_val == ""){
			
			alert("Enter a location or select 'See Map'.");
			return false;
		}
		
		locs.val("");
		
		console.log(loc_list);
		console.log(draw);
		
		$.each(loc_list, function(index, val){
			
			
			if(!val || val == "") return true;
			var trueVal = val.trim();
			console.log("Search for: " + trueVal);
			
			send[index] = $.ajax({
				url: "?",
				dataType: "json",
				data: {
					location_name: [{"long_name": trueVal}],
					cpn: cpn_num
				}
			});
			
			
			send[index].done(function(data){ 
				
				results.push(data); 
				if(data["Not Found"] == 1) not_found.push(trueVal);
				else{
					
					var coord   = JSON.parse(data.str);
					
					if(first == "") first = data.name;
					
					if(draw){
						
						var outline = new google.maps.Data.Polygon([coord.outer[0]]);
						var feature = new google.maps.Data.Feature();
						 
						feature.setProperty('title', data.name);
						feature.setGeometry(outline);

						map.data.add(feature);
						
						var checks = $.parseHTML(locations_getListChecks(data.locstr));
			
						$("span#selected_locations").empty();
						$("span#selected_locations").append(checks);
						
						//$("span#selected_locations").text(data.locstr);
						$("div.crumbwrap span#location_value").text(data.locstr.split(",").join(", "));
					}
					
					selected_locations[data.name] = coord;
				} 
				
			});
			send[index].fail(function(data){ 
				results.push({"Not Found": 1, "name": val}); 
				not_found.push(val);
			});
		});
		
		console.log(results);
		console.log(not_found);
		
		
		//wait for the last send to complete
		$.when.apply($, send).done(function(data){
			
			not_found = not_found.join("\n");
			
			if(not_found) alert("Could not add the following locations:\n\n" + not_found);
			
			

			if(!draw){
				
				$(".map-show-wrap").hide(
					"fast", 
					function(){ 
						$(".map-wrap").show("fast"); 
						
						var address = first + ", Canada";
						getNewAddressLocation(address, false, null, 9);
					
						
					}
				);
			}
			
			
			
			
			
		});
		
		
		
		//console.log(loc_list);
		//console.log(loc_list);
	});
}




function locations_handleMapShowClick(){
	
	
	//user clicks the show map button
	$("button.map-show").on("click", function(){
		
		
		
		$(".map-show-wrap").hide(
			"fast", 
			function(){ 
			
				var address          = "";
				var current_location = getLocation();
			
				$(".map-wrap").show("fast"); 
				if(Object.keys(selected_locations).length === 0 && selected_locations.constructor === Object){
					
					address = current_location? current_location : "Canada";
					getNewAddressLocation(address, false, null, 5);
					
				}
				else{
					
					address = Object.keys(selected_locations)[0] + ", Canada";
					getNewAddressLocation(address, false, null, 9);
				}
				//alert(address);
			}
		);
	});
}




function getLocation() {
	if (navigator.geolocation) {
		return navigator.geolocation.getCurrentPosition(locateSuccess, locateError);
	} else {
		return false;
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
		
		map.setCenter(latLng);
		map.setZoom(zoom);
		
		//console.log("HERE");
		//console.log(selected_locations);
		//console.log("HERE");
		
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
			
			var checks = $.parseHTML(locations_getListChecks(locs_string));
			
			$("span#selected_locations").empty();
			$("span#selected_locations").append(checks);
			if(locs_string != "") $("div.crumbwrap span#location_value").text(locs_string.split(",").join(", "));
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
						cpn: cpn_num
						
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
					
					var checks = $.parseHTML(locations_getListChecks(data.locstr));
					
					$("span#selected_locations").empty();
					$("span#selected_locations").append(checks);
					$("div.crumbwrap span#location_value").text(data.locstr.split(",").join(", "));
					//$("#locations-loading").hide();
					selected_locations[data.name] = JSON.parse(data.str);
					
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
						cpn: cpn_num
						
					}
				});
				
				send.done(function(data){ 
					console.log(data); 
					map.data.remove(this_feature); 
					
					
					var checks = $.parseHTML(locations_getListChecks(data.locstr));
			
					$("span#selected_locations").empty();
					$("span#selected_locations").append(checks);
					
					
					//$("span#selected_locations").text(data.locstr);
					$("div.crumbwrap span#location_value").text(data.locstr.split(",").join(", "));
				});
				send.fail(function(data){ console.log(data); });
				
				
			}
			
			//alert();
			//console.log(e.feature);
		});

		
		
		
		
		//if(showtags) addPlaceMarkers(places, map);
	}
	
	
	return map;
}



function locations_removeLocationWithCheck(){
	
	$("body").on("click", "div.keyword-check input[type='checkbox']", function(e){
		
		var title = $(this).val();
		
		if(confirm("Remove " + title + " from your location selection?")){
			
			
			var send = $.ajax({
				url: "?",
				dataType: "json",
				data: {
					location_remove: title,
					cpn: cpn_num
					
				}
			});
			
			send.done(function(data){ 
				console.log(data); 
				//map.data.remove(this_feature); 
				//console.log(map.features.get());
				
				//console.log(selected_locations);
				delete selected_locations[title];
				//console.log(selected_locations);
				
				address = data.locstr.split(",")[0] + ", Canada";
				console.log(address);
				getNewAddressLocation(address, false, null, 9);
				
				var checks = $.parseHTML(locations_getListChecks(data.locstr));
				
				console.log(checks);
			
				$("span#selected_locations").empty();
				//$("span#selected_locations").append(checks);
				
				
				//$("span#selected_locations").text(data.locstr);
				$("div.crumbwrap span#location_value").text(data.locstr.split(",").join(", "));
				
				
				
			});
			send.fail(function(data){ console.log(data); });
			
			
		}
		else{
			$(e.target).prop("checked", true);
			
		}

		
	});
}


function locations_getListChecks(locstr){
	
	console.log("CALLED");
	//console.log(locstr);
	if(!locstr) return "";
	
	var html = "";
	var last_index = 0;
	
	var locs = locstr.split(",");
	//console.log(locs);
	
	html += "<div class='col-sm-12'>";
	
	$.each(locs, function(i, v){
		
		//console.log(v);
		if(!v || v == " ") return true;
		last_index = i;
		
		//if(i%3 == 0) html += "<div class='col-sm-12'>";
		
		var chckd = " checked";  
		
		var kw_html = "<input type='checkbox' name='"+v.trim()+"' value='"+v.trim()+"'"+chckd+" class='no_sub'>&nbsp;";
		kw_html += "<label>"+v+"</label>&emsp;";
		
		kw_html = "<div class='keyword-check alert alert-primary'>" + kw_html + "</div>";
		kw_html = "<div class='keyword-col'>" + kw_html + "</div>";
		
		//console.log(kw_html);
		html += kw_html;
		
		//if(i%3 == 2) html += "</div>";
	});
	
	//if(last_index%3 != 2) html += "</div></div>";
	html += "</div>";
	//console.log(html);
	return html;
}


function getNewAddressLocation(address, showtags = false, places = null, zoom = 3){

	var geocoder = new google.maps.Geocoder();
	//var zoom = 3;

	if (geocoder) {
		geocoder.geocode({ 'address': address }, function (results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			console.log(results[0].address_components);
			
			
			
			var lat = results[0].geometry.location.lat();
			var lon = results[0].geometry.location.lng();
			

			initialize(lat, lon, 'map_canvas', zoom, null, showtags, places);
			google.maps.event.trigger(map, 'resize');
			
		}
		else {
			alert("Geocoding failed: " + status + "\n" + address);
			//window.location.reload();
		}
		});
	}
}