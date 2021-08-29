<?php
date_default_timezone_set("America/New_York");
if(!($mLink = mysqli_connect("192.168.111.211", "root", "KfabyZcbE3", "portfolio")))
        {
        print"<h3>could not connect to database</h3>\n";
        exit;
        }
/*$date = date('Y-m-d', $timestamp);
	$query = "
		SELECT *
		FROM system_holidays
		WHERE date = '$date'
	";
print $query;*/
$closed = "N";
$timestamp = "1409569200";
$date = date('Y-m-d', $timestamp);
$query = "SELECT * FROM system_holidays WHERE date = '$date'";
print $query;
$holiday_result = mysqli_query($mLink, $query);
if(mysqli_num_rows($holiday_result) > 0)
    {
    $holiday_row = mysqli_fetch_assoc($holiday_result);
    $closed = $holiday_row['closed'];
	}
print $closed;
?>