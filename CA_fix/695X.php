<?php
date_default_timezone_set("America/New_York");
include "dbconnect.php";
$file = date('Ymd').'_695.txt';
//$file = "20170206_695.txt";
$local_file = '/var/www/html/CA_feed/695/'.$file;
$stack = array();
//open file
$rhandle = fopen($local_file, 'r');
        if ($rhandle) {
    while (($buffer = fgets($rhandle)) !== false) {
        //echo $buffer.'<br><br><br><br><br>';
        array_push($stack, $buffer);


    }
    if (!feof($rhandle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    }
fclose($rhandle);

//insert edi.processed with filename
/*$process_sql = "INSERT INTO `EDI`.`processed` (`id`, `filename`) VALUES ('', '$value');";
mysqli_query($EDI_link, $process_sql);*/

//process
unset($stack[0]);
$num_rows = count($stack);
//print $num_rows;
unset($stack[$num_rows]);
$num_data_rows = $num_rows - 1;
//print $num_data_rows;
/*print '<pre>';
print_r($stack);
print '</pre>';*/
$header_row = substr_replace($stack[1], "", -2);
$header = explode("\t", $header_row);
//$header = substr_replace($header, "", -2);
$num_fields = count($header)-1;
//print $num_fields;
for ($counter=2; $counter <= $num_data_rows; $counter++)
    {
    $query = 'INSERT INTO `EDI`.`695_txt` (';
    /*print '<pre>';
    print_r($stack[$counter]);
    print '</pre>';*/
    $row = explode("\t", $stack[$counter]);
    //$row = substr_replace($row, "", -2);
    /*print '<pre>';
    print_r($row);
    print '</pre>';*/
    //do a count of $row or $header and loop thru matching $header[$count] and $row[$count]
    $query .= "`id`,";
    for ($row_counter=0; $row_counter <= $num_fields; $row_counter++)
        {
        $query .= "`".$header[$row_counter]."`,";
        //$query .= "`".$header[$row_counter]."` = '".$row[$row_counter]."'" . '\r\n';
        }
    $query = rtrim($query, ",");
    $query = $query.") VALUES ('',";
    for ($row_counter=0; $row_counter <= $num_fields; $row_counter++)
        {
        $query .= "'".addslashes($row[$row_counter])."',";
        //$query .= "`".$header[$row_counter]."` = '".$row[$row_counter]."'" . '\r\n';
        }
    $query = rtrim($query, ",");
    $query = $query.");";
    //print $query;
    mysqli_query($EDI_link, $query);
    //print '<br><br><br>';
    }

//insert edi.processed with filename
/*$process_sql = "INSERT INTO `EDI`.`processed` (`id`, `filename`) VALUES ('', '$file');";
mysqli_query($EDI_link, $process_sql);*/
?>