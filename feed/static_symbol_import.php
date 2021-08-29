<?php
include "dbconnect.php";
$sql = "SELECT * from `symbol_static`;";
$result = mysqli_query($data_link, $sql);
for ($count=1; $count <=mysqli_num_rows($result); $count++)
    {
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    $symbol = $row['symbol'];
    //insert into symbol_feed
    $insert_sql = "INSERT INTO `stock_feed` (
            `uid`,
            `symbol`,
            `Outcome`,
            `Date`)
            VALUES (
            '',
            '".$symbol."',
            'Success',
            '11/25/2014');";
    //print $insert_sql;
    mysqli_query($data_link, $insert_sql);
    }


?>