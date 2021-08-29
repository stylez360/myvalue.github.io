<?php
include "dbconnect.php";
//truncate `feed_data`.`stockfeed`
$sql = "truncate `feed_data`.`symbol_feed`;";
$result = mysqli_query($data_link, $sql);
//print_r($result);
include "symbol_list.php";
include "stocks_daily_setup.php";
?>