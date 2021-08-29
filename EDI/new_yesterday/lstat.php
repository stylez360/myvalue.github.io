<?php
//include "dbconnect.php";
//$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/25%' AND `exchgcntry` LIKE 'US' AND `liststatus` LIKE 'L' AND `Field20` NOT LIKE '';";
$yesterday = date("Y/m/d", strtotime( '-1 days' ) )."%";
$custom = "2016/09/23%";
$today = date("Y/m/d")."%";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'LSTAT' AND `liststatus` = 'D' AND `changed` LIKE '$yesterday' AND `Field1` NOT LIKE 'PRCHG' AND `exchgcntry` LIKE 'US';";
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
        $created_array = explode(" ", $row['created']);
        $created = str_replace("/", "", $created_array[0]);
        $changed_array = explode(" ", $row['changed']);
        $bbgcompositeglobalid = $row['bbgcompositeglobalid'];
        $changed = str_replace("/", "", $changed_array[0]);
        $rationew = $row['RatioNew'];
        $field20 = $row['Field20'];
        $field3 = $row['Field3'];
        $currency = $row['Currency'];
        $rate2type = $row['Rate2Type'];
        $exchgcd = $row['exchgcd'];

        //switch for mapping exchcd
        switch ($exchgcd) {
    case "USNASD":
        $new_exchcd = "UQ";
        break;
    case "USAMEX":
        $new_exchcd = "UN";
        break;
    case "USPAC":
        $new_exchcd = "UN";
        break;
    case "USNYSE":
        $new_exchcd = "UN";
        break;
    case "USFNBB":
        $new_exchcd = "UV";
        break;
    case "USOTC":
        $new_exchcd = "UV";
        break;
    default:

}

        $rationew = floor($row['RatioNew']);
        $ratioold = floor($row['RatioOld']);
        //ratio math
        $CP_ADJ = $rationew / $ratioold;

        $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$date1.'|DELIST|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$chnged.'|'.$bbgcompositeglobalid.'| |'.$_695localcode.'|US|4|CP_EXCH|'.$new_exchcd.'|CP_TKR|'.$_695localcode.'|CP_DELIST_REASON|17|CP_NOTES|N.A.|' . "\r\n";
        }

//delists?
$dsql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'LSTAT' AND `liststatus` = 'L' AND `Field3` = 'D' AND (`Field1` = 'REDEM' OR `Field1` = 'LIQ') AND `changed` LIKE '$today' AND `exchgcntry` LIKE 'US';";
$dresult = mysqli_query($edi_link, $dsql);
for ($counter=1; $counter <= mysqli_num_rows($dresult); $counter++)
		{
		$row = mysqli_fetch_assoc($dresult);
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
        $created_array = explode(" ", $row['created']);
        $created = str_replace("/", "", $created_array[0]);
        $changed_array = explode(" ", $row['changed']);
        $bbgcompositeglobalid = $row['bbgcompositeglobalid'];
        $changed = str_replace("/", "", $changed_array[0]);
        $rationew = $row['RatioNew'];
        $field20 = $row['Field20'];
        $field3 = $row['Field3'];
        $currency = $row['Currency'];
        $rate2type = $row['Rate2Type'];
        $exchgcd = $row['exchgcd'];

        //switch for mapping exchcd
        switch ($exchgcd) {
    case "USNASD":
        $new_exchcd = "UQ";
        break;
    case "USAMEX":
        $new_exchcd = "UN";
        break;
    case "USPAC":
        $new_exchcd = "UN";
        break;
    case "USNYSE":
        $new_exchcd = "UN";
        break;
    case "USFNBB":
        $new_exchcd = "UV";
        break;
    case "USOTC":
        $new_exchcd = "UV";
        break;
    default:

}

        $rationew = floor($row['RatioNew']);
        $ratioold = floor($row['RatioOld']);
        //ratio math
        $CP_ADJ = $rationew / $ratioold;

        $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$created.'|DELIST|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$chnged.'|'.$bbgcompositeglobalid.'| |'.$_695localcode.'|US|4|CP_EXCH|'.$new_exchcd.'|CP_TKR|'.$_695localcode.'|CP_DELIST_REASON|17|CP_NOTES|N.A.|' . "\r\n";
        }


//print "hi";
print "<br><br><br><br><br><br><br><br><br><br><br>";
print $csv;
$file_date =  date("Ymd");
$filename = "/var/www/html/EDI/BB_FEED/EDI-BB-LSTAT_translate.cax.".$file_date;
include '/var/www/html/EDI/file_exporter.php';
$csv = "";
?>
