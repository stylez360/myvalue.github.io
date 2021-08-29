<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set("America/New_York");
include "dbconnect.php";

$pp_string = 'Exchange=NYSEARCA&StartDate=01/01/2000&EndDate=12/24/2014&_Token=EF2662FA141B4DC086F6A72B2D15AD2C';
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
//print_r($opts);
$context = stream_context_create($opts);
$file = fopen('http://www.xignite.com/xGlobalHistorical.json/GetAllSplitsByExchange', 'rb', false, $context) or die ("Xignite API not responding");
$results = @stream_get_contents($file);
$results = substr($results, 2, -2);
print $results;
$json = explode("},{", $results);
print "<pre>";
print_r($json);
print "</pre>";
foreach($json as $key => $value)
    {
    $value = "{".$value."}";
    print "<pre>";
    print $value;
    print "</pre>";
    $obj = json_decode($value, TRUE);
    print "<pre>";
    print_r($obj);
    //print_r($obj['Security'][0]);
    //print_r($obj['Splits'][0]);
    print "</pre>";
    print "<pre>";
    var_dump(json_decode($value));
    print "</pre>";
    foreach($obj['Security'] as $key => $value)
            {
            print $key."<br>";
            $$key = $value;
            }
    foreach($obj['Splits'][0] as $key => $value)
            {
            print $key."<br>";
            $$key = $value;
            }
    foreach($obj as $key => $value)
            {
            print $key."<br>";
            $$key = $value;
            }
    $splits_insert = "INSERT INTO `feed2`.`splits` (
            `uid`,
            `CIK`,
            `Cusip`,
            `Symbol`,
            `ISIN`,
            `Valoren`,
            `Name`,
            `Market`,
            `CategoryOrIndustry`,
            `ExDate`,
            `Numerator`,
            `Denominator`,
            `SplitRatio`,
            `DataConfidence`,
            `Outcome`,
            `Message`,
            `Identity`,
            `Delay`)
            VALUES (
            '',
            '".$CIK."',
            '".$Cusip."',
            '".$Symbol."',
            '".$ISIN."',
            '".$Valoren."',
            '".$Name."',
            '".$Market."',
            '".$CategoryOrIndustry."',
            '".$ExDate."',
            '".$Numerator."',
            '".$Denominator."',
            '".$SplitRatio."',
            '".$DataConfidence."',
            '".$Outcome."',
            '".$Message."',
            '".$Identity."',
            '".$Delay."'
            );";
    print $splits_insert;
    mysqli_query($data_link, $splits_insert);
    }
?>