<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//This file is required for beta.marketocracy.com to work. 2/5/2015 SS
include "dbconnect.php";

$string = "";
$symbol = $_GET['symbol'];
$StartDate = $_GET['StartDate'];
$EndDate = $_GET['EndDate'];
$pp_string = '_Token=EF2662FA141B4DC086F6A72B2D15AD2C&IdentifierType=Symbol&AdjustmentMethod=None&Identifier='.$symbol.'&StartDate='.$StartDate.'&EndDate='.$EndDate;
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
$context = stream_context_create($opts);
$file = fopen('http://www.xignite.com/xGlobalHistorical.json/GetGlobalHistoricalQuotesRange', 'rb', false, $context) or die ("Xignite API not responding");
$results = @stream_get_contents($file);

//$results = substr($results, 1, -2);
//print "<pre>";
print $results;
//print "</pre>";

/*$obj = json_decode($results, TRUE);

print "<pre>";
print $obj;
print "</pre>";*/
?>
