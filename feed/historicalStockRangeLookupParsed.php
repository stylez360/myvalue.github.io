<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

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
//print $results;
print '<br><br><br>';
$meat = explode("[{", $results);
//print_r($meat[1]);
$goods = explode("}]", $meat[1]);
//print "</pre>";
$pieces = explode("},{", $goods[0]);
/*print "<pre>";
print_r($pieces);
print "</pre>";*/

foreach ($pieces as $value)
    {
    $value = '{'.$value.'}';
    $row = json_decode($value);
    print "<pre>";
    print_r($row);
    print "</pre>";
    }
/*$obj = json_decode($results, TRUE);

print "<pre>";
print $obj;
print "</pre>";*/
?>
