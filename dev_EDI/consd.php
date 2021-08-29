<?php
include "dbconnect.php";
//$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/25%' AND `exchgcntry` LIKE 'US' AND `liststatus` LIKE 'L' AND `Field20` NOT LIKE '';";
$yesterday = date("Y/m/d", strtotime( '-1 days' ) )."%";
$custom = "2016/08/18%";
$today = date("Y/m/d")."%";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'CONSD' AND `changed` LIKE '$today' AND `exchgcntry` LIKE 'US';";
$result = mysqli_query($edi_link, $sql);
for ($counter=1; $counter <= mysqli_num_rows($result); $counter++)
		{
		$row = mysqli_fetch_assoc($result);
        print "<pre>";
        print_r($row);
        print "</pre>";
        $_695localcode = $row['localcode'];
        $_695issuername = $row['issuername'];
        $localcode = $row['localcode'];
        $paytype = $row['Paytype'];
        $field20 = $row['Field20'];
        $id = $row['id'];
        $eventid = $row['eventid'];
        $eventcd = $row['eventcd'];
        $date1 = str_replace("/", "", $row['Date1']);
        $date3 = str_replace("/", "", $row['Date3']);
        $date2 = str_replace("/", "", $row['Date2']);
        $date4 = str_replace("/", "", $row['Date4']);
        $actflag = $row['actflag'];
        $issuername = $row['issuername'];
        $uscode = $row['uscode'];
        $field3 = $row['Field3'];
        $field4 = $row['Field4'];
        $field5 = $row['Field5'];
        $field2 = $row['Field2'];
        $created_array = explode(" ", $row['created']);
        $created = str_replace("/", "", $created_array[0]);
        $changed_array = explode(" ", $row['changed']);
        $bbgcompositeglobalid = $row['bbgcompositeglobalid'];
        $changed = str_replace("/", "", $changed_array[0]);
        $rationew = $row['RatioNew'];
        $currency = $row['Currency'];
        $rate2type = $row['Rate2Type'];
        $exchgcd = $row['exchgcd'];
        $rationew = floor($row['RatioNew']);
        $ratioold = floor($row['RatioOld']);
        //ratio math
        $CP_RATIO = $row['RatioNew'] / $row['RatioOld'];
        $CP_ADJ = $row['RatioNew'] / $row['RatioOld'];
        //get 698 data
        $_698_sql = "SELECT * FROM `698_txt` WHERE `localcode` = '$field20' ORDER BY `id` DESC LIMIT 1;";
        $_698_result = mysqli_query($edi_link, $_698_sql);
        $_698_row = mysqli_fetch_assoc($_698_result);
        $cp_spinoff_name = $_698_row['issuername'];
        $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$date1.'|STOCK_SPLT|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$changed.'|N.A.|'.$bbgcompositeglobalid.'|'.$_695localcode.'|US|9|CP_TERMS|'.$rationew.' for '.$ratioold.'|CP_RATIO|'.$CP_RATIO.'|CP_RECORD_DT|N.A.|CP_PAY_DT|N.A.|CP_STOCK_SPLT_TYP|3000|CP_ADJ|'.$CP_ADJ.'|CP_ADJ_DT|'.$date1.'| CP_SH_FRACTIONAL|N.A.|CP_NOTES|N.A.|   ' . "\r\n";
        }
//print "hi";
print "<br><br><br><br><br><br><br><br><br><br><br>";
print $csv;
$file_date =  date("Ymd");
$filename = "/var/www/html/EDI/BB_FEED/EDI-BB-CONSD_translate.cax.".$file_date;
include'file_exporter.php';
?>