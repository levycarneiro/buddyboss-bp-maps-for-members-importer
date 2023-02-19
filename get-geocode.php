<?php

// You will need to check the ID created in your particular website. In my case, it was "78".
// How to find this?
// Create the Location field in wp-admin > buddyboss > components > profile fields > edit fields > add the Location field > save.
// Then add your location in your profile page.
// Then check in your DB, which id was used by the plugin. Use this query:
// 		SELECT * from wp_usermeta where meta_key like '%geocode%' and user_id = <your_id_here> ;
$geocode_field_id	= "78";
$geocode_field_str	= "geocode_".$geocode_field_id;

// This is optional. When testing this in your Test site, you might want to reset the Location data for all users on the DB.
// print "delete from wp_usermeta where meta_key = '".$geocode_field_str."'; \n";
// print "delete from wp_bp_xprofile_data where field_id = ".$geocode_field_id."; \n";

$lines = file("results.csv");

foreach ($lines as $line) {
	$line = trim($line);
	list ($user_id, $field_id, $value) = explode(",", $line);
	$value = str_replace ('"', '', $value);
	$array_data[$user_id][$field_id] = $value;
}
foreach ($array_data as $user_id => $data) {
	// print "user_id: $user_id \n";

	$city		= isset($data[4]) ? $data[4] : '';
	$state		= isset($data[5]) ? $data[5] : '';
	$country	= isset($data[7]) ? $data[7] : '';
	$city		= trim($city);
	$state		= trim($state);
	$country 	= trim($country);

	// Ignoring user without City.
	if ($city == '') { continue; }

	list ($formatted_address, $geocode) = $geocode = getGeoCode ("$city,$state,$country");
	if ($geocode == 'Error') {
		// print "Not enough data to obtain a geocode.\n";
	}
	else {
		print "INSERT INTO wp_usermeta (user_id, meta_key, meta_value) VALUES ($user_id, '".$geocode_field_str."', '$geocode'); \n";
		print "INSERT INTO wp_bp_xprofile_data (field_id,user_id,value,last_updated) VALUES (".$geocode_field_id.", $user_id, \"$formatted_address\",now()); \n";
	}
}

function getGeoCode($address) {

	$formatted_address = "";

	$address = prepareStringForQuery ($address);

	// Use your Google Maps API key here.
	$api_key = "";

	$url = "https://maps.google.com/maps/api/geocode/json?address=$address&key=".$api_key;
	// print "URL: $url \n";
	$geocode = file_get_contents ($url);
	$json = json_decode ($geocode);

	if (isset($json->results[0]->geometry)) {
		$geocode = $json->results[0]->geometry->location->lat;
		$geocode .= ",".$json->results[0]->geometry->location->lng;

		if (isset($json->results[0]->formatted_address)) {
			$formatted_address = $json->results[0]->formatted_address;
		}
		return array ($formatted_address, $geocode);
	}
	else {
		return array("Error", "Error");
	}
}

function prepareStringForQuery($str) {

	$str = detect_and_replace ($str);

	// Remove any non-ASCII characters
	$str = preg_replace('/[^\x00-\x7F]+/', '', $str);

	// URL encode the string
	$str = urlencode($str);

 	return $str;
}

function detect_and_replace ($str) {
	$enc = mb_detect_encoding ($str, "UTF-8, UTF-16, ISO-8859-1, ISO-8859-2, ISO-8859-5, ISO-8859-9, ISO-8859-15, Windows-1252 ");
	$str = mb_convert_encoding ($str, 'ASCII', $enc);
	return $str;
}
