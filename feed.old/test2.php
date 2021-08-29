<?php
date_default_timezone_set("America/New_York");
/*$date = date('Y-m-d', $timestamp);
	$query = "
		SELECT *
		FROM system_holidays
		WHERE date = '$date'
	";
print $query;*/
if(!($mLink = mysqli_connect("192.168.111.211", "marketocracy", "KfabyZcbE3", "portfolio")))
        {
        print"<h3>could not connect to database</h3>\n";
        exit;
        }

function isMarketHoliday($timestamp, $mLink)
{
$closed = "N";
$date = date('Y-m-d', $timestamp);
$query = "SELECT * FROM system_holidays WHERE date = '$date'";
//print $query;
$holiday_result = mysqli_query($mLink, $query);
if(mysqli_num_rows($holiday_result) > 0)
    {
    $holiday_row = mysqli_fetch_assoc($holiday_result);
    $closed = $holiday_row['closed'];
	}
return $closed;
}
$timestamp = "1409569200";
$timestamp = time();


$holiday = isMarketHoliday($timestamp, $mLink);
print $holiday;
?>