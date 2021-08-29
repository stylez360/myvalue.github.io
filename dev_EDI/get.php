<?php
//print $_GET['date'];
//print $_REQUEST['date'];
//print_r($_GET);
$d = strip_tags($_GET['date']);
if (!empty($d))
    {
    $date = strip_tags($d)."%";
    $manual = "1";
    }
    else
        {
        $date = date("Y/m/d")."%";
        }
//print $date;
$file_date = rtrim($date, "%");
$file_date = str_replace("/", "", $file_date);
print $file_date;
?>