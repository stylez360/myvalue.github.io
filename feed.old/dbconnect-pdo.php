<?php
//Set Database Variables
$dbHost = "192.168.111.211";
$dbName = "portfolio";
$dbUser = "marketocracy";
$dbPass = "KfabyZcbE3";

//Connect to portfolio DB / MySQL with PDO_MYSQL
try{
	$mLink = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
	$mLink->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(PDOException $error){
	// Log any error to /var/log/httpd/alpha-pdo_log
	file_put_contents("/var/log/httpd/alpha-pdo_log", "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
	die($error->getMessage());
}

$dbName = "feed_data";

//Connect to feed_data DB / MySQL with PDO_MYSQL
try{
        $fLink = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
        $fLink->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
}
catch(PDOException $error){
        // Log any error to /var/log/httpd/alpha-pdo_log
        file_put_contents("/var/log/httpd/alpha-pdo_log", "-----\rDate: ".date('Y-m-d H:i:s')."\rFile: ". __FILE__ ."\rLine Number: ". __LINE__ ."\rVars:\r".dump_vars(get_defined_vars())."\r", FILE_APPEND);
        die($error->getMessage());
}


?>
