<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include "dbconnect.php";

$string = "";
//$symbol_sql = "SELECT *FROM `stock_valid_symbols` LIMIT 200;";
$symbol_sql = "SELECT * FROM `stock_symbols` WHERE `exchange` = 'NYSE' LIMIT 100;";
$symbol_result = mysqli_query($mylink, $symbol_sql);
for ($counter=1; $counter <= mysqli_num_rows($symbol_result); $counter++)
		{
		$symbol_row = mysqli_fetch_assoc($symbol_result);
        /*print "<pre>";
        print_r($symbol_row);
        print "</pre>";*/
        $symbol = $symbol_row['symbol'];
        $string .=  $symbol.",";
        }
$string = rtrim($string, ",");
//print $string;
$url = 'http://globalquotes.xignite.com/v3/xGlobalQuotes.xml/GetGlobalDelayedQuotes?_Token=10AD0B9C336E475B8595C5748559613D&IdentifierType=Symbol&Identifiers='.$string;
print $url;
?>


