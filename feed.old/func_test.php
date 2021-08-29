<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include "dbconnect.php";
//-----
// Determine if day is a weekday
// Pass time
function isWeekday($timestamp) {
	return (date('N', $timestamp) < 6); // ISO DoW (7 = Sunday)
}

//-----
// Determine if day is a market holiday
// Pass time & DB link
// Returns false if not, "Y" if it is, "E" if it's an early closing day
function isMarketHoliday($timestamp, $mLink) {
    $date = date('Y-m-d', $timestamp);
	$query = "SELECT * FROM system_holidays WHERE date = '$date'";
    $holiday_result = mysqli_query($query, $mLink);
    if(mysqli_num_rows($holiday_result) > 0)
        {
        $holiday_row = mysqli_num_rows($holiday_result);
        $closed = $holiday_row['closed'];
        }
		return $closed; // "Y" if it is a holiday, "E" if it closes early
	}

//-----
// Determine if market is open
// Pass time, DB link (for holiday lookup), and whether to pad start and end times
// Returns true or false
function isMarketOpen($timestamp, $mLink, $fudge='none') {
	// Is it a weekday?
	if (isWeekday($timestamp)){
		switch($fudge){
			case 'none': // ACTUAL market hours (9:30 to 4:00 ET, 1:00 if it's an early close day)
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
				break;

			case 'before':  // Start 30 minutes early, end on time
				$begin = "9:00 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
				break;

			case 'after': // Start on time, end 30 minutes late
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:30 PM" : "4:30 PM");
				break;

			case 'both':  // Start 30 minutes early, end 30 minutes late
				$begin = "9:00 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:30 PM" : "4:30 PM");
				break;

			default: // Use actual market hours if not properly specified
				$begin = "9:30 AM";
				$end = (isMarketHoliday($timestamp, $mLink) == "E" ? "1:01 PM" : "4:01 PM");
		}
		if (isMarketHoliday($timestamp, $mLink) == "Y"){  // Closed all day
			return false;
		}else{ // Open today
			if ($timestamp > strtotime(date('j-n-Y', $timestamp).' '.$begin.' America/New_York') && $timestamp < strtotime(date('j-n-Y', $timestamp).' '.$end.' America/New_York')) {
				return true;
			}
		}
	}
	return false;
}

//code


/*$result = isWeekday(time());
print $result;*/

$result = isMarketHoliday("1409529666", $mLink);
print $result;
print "<br>hi<br>";
?>