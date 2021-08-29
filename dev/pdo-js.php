<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Set Database Variables
$dbHost = "192.168.111.211";
$dbUser = "marketocracy";
$dbPass = "KfabyZcbE3";
$dbName = "feed2";

//Connect to feed_data DB / MySQL with PDO_MYSQL
try{
        $fLink = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $fLink->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(PDOException $error){
        // Log any error to /var/log/httpd/beta-pdo_log
        file_put_contents("/var/log/httpd/beta-pdo_log", "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
        die($error->getMessage());
}




    $sym = "PGN,TMK,MPC,KBAL,DTSI,STRZA,KE,MNK,VLO,LUV,PEIX,PJC,ALK,LRCX,X,AIZ,HLX,SAFM,ACCO,TSN,NR,PFG,UIHC,HPQ,CMO,WRB,CMC,LEA,ONNN,CXP,UVE,DDS";
    //Create array from comma delimited string
	$aSymbols = explode(",", $sym);
    //Make symbols into a comma seperated string surround by SINGLE quotes for mysql
	$symbols = "'".implode("','", $aSymbols)."'";
/*
    $query = "SELECT Name as companyName, Symbol as symbol, Last as CurrentPrice, ChangeFromPreviousClose as chang
                FROM `feed2`.`stock_feed`
                WHERE `Symbol` in (".$symbols.")";

				try{
					$rsSymbols = $fLink->prepare($query);

					$aValues = array(
						'companyName'   => $companyName,
						'symbol'        => $symbol,
                        'currentPrice'  => $currentPrice,
                        'change'        => $chang
					);
				}
				catch(PDOException $error){
					// Log any error
					file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
				}
*/
	$query = "
		SELECT 	Name as companyName,
				Symbol as symbol,
				Last as CurrentPrice,
				ChangeFromPreviousClose as chang
		FROM `feed2`.`stock_feed`
		WHERE `Symbol` IN (:symbols)
	";
//echo substr(substr($symbols, 1), 0, -1);
	try{
		$rsSymbols = $fLink->prepare($query);
		$aValues = array(
			':symbols' => substr(substr($symbols, 1), 0, -1)
		);
echo $fLink->quote($query);
// I'LL EXPLAIN THESE NEXT 2 LINES SOME OTHER TIME.  THEY ARE NOT NEEDED FOR PDO TO WORK, THEY ARE FOR DEBUGGING
		$preparedQuery = str_replace(array_keys($aValues), array_values($aValues), $query); //Debug
		//die($preparedQuery);

		$rsSymbols->execute($aValues);
	}
	catch(PDOException $error){
// THIS WON'T WORK AS-IS, IT CALLS A CUSTOM FUNCTION YOU ARE NOT INCLUDING.  JUST COMMENT IT OUT FOR NOW.
		// Log any error
//			file_put_contents($_SESSION['pdo_log'], "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	}
//	echo $rsSymbols->rowCount();
	while($foo = $rsSymbols->fetch(PDO::FETCH_ASSOC)){



print "<pre>";
print_r($foo);
print "</pre>";

	}

?>