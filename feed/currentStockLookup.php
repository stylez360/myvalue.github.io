<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include "dbconnect.php";

$string = "";
//$symbol_sql = "SELECT *FROM `stock_valid_symbols` LIMIT 200;";
//$symbol_sql = "SELECT * FROM `stock_symbols` WHERE `exchange` = 'NYSE' LIMIT 0, 50;";
$symbol_sql = "SELECT * FROM `stock_valid_symbols` LIMIT 0, 300;";
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
//$string = 'T,HES,JCP,F,S,LUV,ETN,AGX,HPQ,DBD,DSX,CSX,BAC,WFT,C,GCA,WG,KEM,GE,PFE,TE,ANF,TR,GWW,MDU,SWN,WMT,TOT,BP,DOM,WFC,GME,BA,REGI,VOXX,MGLN,AXP,ITC,AHL,MFC,LUX,BMO,APOL,RE,RNR,CLX,AGNC,V,TD,BLK,STRA,NYCB,ACE,KIM,OFG,JOY,FIG,OVTI,PFMT,TMK,AUO,WNC,LNC,WRLD,UVE,STS,UBS,UTL,PFG,MKC,AIZ,CS,LBY,GNW,FM,AFG,MRO,AEG,BMA,KNM,REX,LM,GBX,RY,BAM,GES,TRV,CYS,Y,DVN,NTP,UNM,PJC,TEX,CMO,SPW,BFR,ING,EVER,ALK';
$string = $_GET['string'];
$pp_string = '_Token=EF2662FA141B4DC086F6A72B2D15AD2C&IdentifierType=Symbol&Identifiers='.$string;
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
//print_r($opts);
$context = stream_context_create($opts);
$file = fopen('http://globalquotes.xignite.com/v3/xGlobalQuotes.json/GetGlobalDelayedQuotes', 'rb', false, $context) or die ("Xignite API not responding");
$results = @stream_get_contents($file);
print $results;

?>
