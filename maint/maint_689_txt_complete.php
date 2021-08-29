<?php
// Tell me when things go sideways
error_reporting(E_ALL);
ini_set('display_errors', '1');

//set local time zone
date_default_timezone_set("America/New_York");

//include DB connections
include "PDO_dbconnect.php";

//get date one month ago
$date = date("Y/m/d", strtotime( '-1 month' ) )."%";

//This Attribute allows for multiple PDO queries to be run simutaniously
$fLink->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

//make fetch default to assoc only instead of both
$fLink->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$query = "SELECT * FROM `EDI_test`.`689_txt` WHERE `changed` < '$date' LIMIT 10";

try{
        $rs_689_txt = $fLink->prepare($query);
        $rs_689_txt->execute();
}
catch(PDOException $error){
        // Log any error
        //file_put_contents($pdo_log, "-----\rDate: ".date('Y-m-d H:i:s')."\rFile$
        //$aErrors[] = $error;
}
//example of a while statement
//while($_689_txt = $rs_689_txt->fetch(PDO::FETCH_ASSOC))

//loop through the results of $query and do stuff with the data
foreach($rs_689_txt as $_689_txt)
		{
		$err = 0;
        $k = "";
        $v = "";
        foreach($_689_txt as $key=>$value)
            {
            $k .= "`".$key."`,";
            $v .= "'".$value."',";
            }
        $k = rtrim($k, ",");
        $v = rtrim($v, ",");

        //insert data into archive
        $insert_query = 'INSERT INTO `EDI_archive`.`689_txt` ('.$k.') VALUES ('.$v.')';
        $id = $_689_txt['id'];
        try{
            $insert_689_txt = $fLink->prepare($insert_query);
            $insert_689_txt->execute();
            }
        catch(PDOException $error){
            // Log any error
            //file_put_contents($pdo_log, "-----\rDate: ".date('Y-m-d H:i:s')."\rFile$
            //$aErrors[] = $error;
            $err = 1;
            }
        //if $err != 1 then delete from table where id = $id'
        if($err != 1)
            {
            //delete from source table where `id` = $id;
            $del_query = "
                DELETE FROM `EDI_test`.`689_txt` WHERE `id` = '$id'
                ";
            //print $del_query;
            try{
                $del_689_txt = $fLink->prepare($del_query);
                $del_689_txt->execute();
                }
                catch(PDOException $error){
                // Log any error
                //file_put_contents($pdo_log, "-----\rDate: ".date('Y-m-d H:i:s')."\rFile$
                //$aErrors[] = $error;
                    }
            }
        //print $id;
        }
?>