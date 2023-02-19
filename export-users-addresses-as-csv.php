<?php

// Database credentials
$host		= 'localhost';
$username	= '';
$password	= '';
$database	= '';

// Connect to the database
$conn = mysqli_connect ($host, $username, $password, $database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// In my case, the city field was id 4, state was 5, and country was 7.
// You'll need to find the particular IDs for your site/db.
// Check with this query:
//		select id, name from wp_bp_xprofile_fields;

$city_field_id = 4;
$state_field_id = 5;
$country_field_id = 7;

// Query to retrieve the user address fields.
$query = "select user_id, field_id, value from wp_bp_xprofile_data x, wp_users u where u.id = x.user_id and u.user_status = 0 and field_id in (".$city_field_id.",".$state_field_id.",".$country_field_id.") order by user_id, field_id ";
$result = mysqli_query($conn, $query);

// Save results to a CSV file
$filename = 'results.csv';
$file = fopen($filename, 'w');
while ($row = mysqli_fetch_assoc($result)) {
  // Loop through each column and convert the string to Latin 1
  foreach ($row as $key => $value) {
    $row[$key] = utf8_decode($value);
  }
  // Write the row to the CSV file
  fputcsv($file, $row, ';');
}
fclose($file);

// Close the database connection
mysqli_close($conn);

?>