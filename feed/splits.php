<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include "dbconnect.php";

$pp_string = 'Exchange=NYSE&StartDate=12/19/2014&EndDate=12/22/2014&_Token=EF2662FA141B4DC086F6A72B2D15AD2C';
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
//print $results;
/*$results=str_replace(' ','+',$results);
$pairs = explode('&',$results);
foreach($pairs as $value)
	{
	//print $value.'<br><br><br>';
	$pair = explode('=', $value);
	//print_r($pair);
	foreach($pair as $key => $value)
		{
		$$pair['0'] = $pair['1'];
		}
	}*/
//Need to trim the last } off the string as it doesnt have a , after it and therefore does not get removed in the explode() thus the -2 in the substr()
//Also needed to trim the [ and ] from the front and back of the package handed to us from the API
$results = substr($results, 1, -2);
/*print "<pre>";
print $results;
print "</pre>";*/


$json = explode("},", $results);
//$json[0] = $json[0]."}";
foreach($json as $key => $value)
    {
    $value = $value."}}";
    print $value."<br>";
    $obj = json_decode($value, TRUE);
    print "<pre>";
    print_r($obj);
    print "</pre>";
    print "<pre>";
    var_dump(json_decode($value));
    print "</pre>";
    foreach($obj as $key => $value)
            {
            print $key." = ".$value."<br>";
            $$key = $value;
            }
    foreach($obj['Security'] as $key => $value)
            {
            $$key = $value;
            }
    //$CIK = $obj['Security']['CIK'];
    /*print "###############".$Identity."#####################";
    $feed_insert = "INSERT INTO `feed_data`.`stock_feed` (
            `uid`,
            `Outcome`,
            `Message`,
            `Identity`,
            `Delay`,
            `Date`,
            `Time`,
            `UTCOffset`,
            `Open`,
            `Close`,
            `High`,
            `Low`,
            `Last`,
            `LastSize`,
            `Volume`,
            `PreviousClose`,
            `PreviousCloseDate`,
            `ChangeFromPreviousClose`,
            `PercentChangeFromPreviousClose`,
            `Bid`,
            `BidSize`,
            `BidDate`,
            `BidTime`,
            `Ask`,
            `AskSize`,
            `AskDate`,
            `AskTime`,
            `High52Weeks`,
            `Low52Weeks`,
            `Currency`,
            `TradingHalted`,
            `CIK`,
            `CUSIP`,
            `Symbol`,
            `ISIN`,
            `Valoren`,
            `Name`,
            `Market`,
            `MarketIdentificationCode`,
            `MostLiquidExchange`,
            `CategoryOrIndustry`)
            VALUES (
            '',
            '".$Outcome."',
            '".$Message."',
            '".$Identity."',
            '".$Delay."',
            '".$Date."',
            '".$Time."',
            '".$UTCOffset."',
            '".$Open."',
            '".$Close."',
            '".$High."',
            '".$Low."',
            '".$Last."',
            '".$LastSize."',
            '".$Volume."',
            '".$PreviousClose."',
            '".$PreviousCloseDate."',
            '".$ChangeFromPreviousClose."',
            '".$PercentChangeFromPreviousClose."',
            '".$Bid."',
            '".$BidSize."',
            '".$BidDate."',
            '".$BidTime."',
            '".$Ask."',
            '".$AskSize."',
            '".$AskDate."',
            '".$AskTime."',
            '".$High52Weeks."',
            '".$Low52Weeks."',
            '".$Currency."',
            '".$TradingHalted."',
            '".$CIK."',
            '".$CUSIP."',
            '".$Symbol."',
            '".$ISIN."',
            '".$Valoren."',
            '".$Name."',
            '".$Market."',
            '".$MarketIdentificationCode."',
            '".$MostLiquidExchange."',
            '".$CategoryOrIndustry."'
            );";
    print '<br><br>'.$feed_insert.'<br><br>';*/
    //mysqli_query($data_link, $feed_insert);
    print "<br>-----------------------------------------------------------------------------------------------------<br>";
    }
?>
