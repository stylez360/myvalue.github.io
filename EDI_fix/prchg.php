<?php
include "dbconnect.php";
//$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/25%' AND `exchgcntry` LIKE 'US' AND `liststatus` LIKE 'L' AND `Field20` NOT LIKE '';";
$yesterday = date("Y/m/d", strtotime( '-1 days' ) )."%";
$custom = "2016/08/18%";
$today = date("Y/m/d")."%";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'PRCHG' AND `changed` LIKE '$yesterday' AND `exchgcntry` LIKE 'US';";
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

        //switch for mapping field3
        switch ($field3) {
    case "USNASD":
        $new_field3 = "UQ";
        break;
    case "USAMEX":
        $new_field3 = "UN";
        break;
    case "USPAC":
        $new_field3 = "UN";
        break;
    case "USNYSE":
        $new_field3 = "UN";
        break;
    case "USFNBB":
        $new_field3 = "UV";
        break;
    case "USOTC":
        $new_field3 = "UV";
        break;
    default:

}

        $field2 = $row['Field2'];

        //switch for mapping field2
        switch ($field2) {
    case "USNASD":
        $new_field2 = "UQ";
        break;
    case "USAMEX":
        $new_field2 = "UN";
        break;
    case "USPAC":
        $new_field2 = "UN";
        break;
    case "USNYSE":
        $new_field2 = "UN";
        break;
    case "USFNBB":
        $new_field2 = "UV";
        break;
    case "USOTC":
        $new_field2 = "UV";
        break;
    default:

}

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
        $CP_ADJ = $rationew / $ratioold;
        //get 698 data
        $_698_sql = "SELECT * FROM `698_txt` WHERE `localcode` = '$field20' ORDER BY `id` DESC LIMIT 1;";
        $_698_result = mysqli_query($edi_link, $_698_sql);
        $_698_row = mysqli_fetch_assoc($_698_result);
        $cp_spinoff_name = $_698_row['issuername'];
        $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$date1.'|CHG_LIST|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$changed.'|N.A.|'.$bbgcompositeglobalid.'|'.$_695localcode.'|US|3|CP_OLD_EXCH|'.$new_field2.'|CP_NEW_EXCH|'.$new_field3.'|CP_NOTES|N.A.|' . "\r\n";
        }
//print "hi";
print "<br><br><br><br><br><br><br><br><br><br><br>";
print $csv;
//$file_date =  date("Ymd");
$file_date = "20170206";
$filename = "/var/www/html/EDI/BB_FEED/EDI-BB-PRCHG_translate.cax.".$file_date;
include '/var/www/html/EDI/file_exporter.php';
?>