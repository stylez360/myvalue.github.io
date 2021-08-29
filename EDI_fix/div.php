<?php
include "dbconnect.php";
$custom = "2016/08/29%";
$yesterday = date("Y/m/d", strtotime( '-1 days' ) )."%";
$today = date("Y/m/d")."%";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'DIV' AND `changed` LIKE '$yesterday' AND `exchgcntry` LIKE 'US'";
print $sql;
$result = mysqli_query($edi_link, $sql);
for ($counter=1; $counter <= mysqli_num_rows($result); $counter++)
		{
		$row = mysqli_fetch_assoc($result);
        print "<pre>";
        print_r($row);
        print "</pre>";
        $_695localcode = $row['localcode'];
        $_695issuername = $row['issuername'];
        $uscode = $row['uscode'];
        $paytype = $row['Paytype'];
        $field20 = $row['Field20'];
        $id = $row['id'];
        $eventid = $row['eventid'];
        $eventcd = $row['eventcd'];
        $date4 = str_replace("/", "", $row['Date4']);
        $date3 = str_replace("/", "", $row['Date3']);
        $date2 = str_replace("/", "", $row['Date2']);
        $date1 = str_replace("/", "", $row['Date1']);
        $actflag = $row['actflag'];
        $field3 = $row['Field3'];
        $created_array = explode(" ", $row['created']);
        $created = str_replace("/", "", $created_array[0]);
        $changed_array = explode(" ", $row['changed']);
        $changed = str_replace("/", "", $changed_array[0]);
        $rationew = $row['RatioNew'];
        $field20 = $row['Field20'];
        $field3 = $row['Field3'];
        $currency = $row['Currency'];
        $rate1 = $row['Rate1'];
        $rate2 = $row['Rate2'];
        $CP_AMT = $row['RatioNew'] / $row['RatioOld'] * 100;
        $rationew = floor($row['RatioNew']);
        $ratioold = floor($row['RatioOld']);
        //ratio math
        //$CP_AMT = $rationew / $ratioold * 100;
        $bbgcompositeglobalid = $row['bbgcompositeglobalid'];
        switch ($paytype) {
            case "S":
                echo "<br>S<br>";
                //get 698 info related to this action and build csv string
                $_698_sql = "SELECT * FROM `698_txt` WHERE `localcode` = '$field20' AND `actflag` = 'U' ORDER BY `id` DESC LIMIT 1;";
                $_698_result = mysqli_query($edi_link, $_698_sql);
                $num_rows = mysqli_num_rows($_698_result);
                /*if ($num_rows == 0)
                    {
                    print "00000000000000000000000000000000000000";
                    break;
                    }*/
                $_698_row = mysqli_fetch_assoc($_698_result);
                $bbg_prefix_array = explode(" ", $_698_row['bbgcompositeticker']);
                $localcode = $_698_row['localcode'];
                $bbg_prefix = $bbg_prefix_array[0];
                print "------------------------------------------";
                print $bbg_prefix;
                print "<pre>";
                print_r($_698_row);
                print "</pre>";
                print $_698_sql;
                print "<br>------------------------------------------";

                    //create line of csv
                    print "_____MATCHES_____";
                    $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$date3.'|DVD_STOCK|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$changed.'|'.$bbgcompositeglobalid.'||'.$_695localcode.'|US|11|CP_AMT|'.$CP_AMT.'|CP_TKR|'.$_695localcode.'|CP_RECORD_DT|'.$date2.'|CP_PAY_DT|'.$date3.'|CP_FREQ| |CP_ADJ|'.$rationew.'|CP_ADJ_DATE|'.$daate1.'|CP_TAX_AMT|N.A.|CP_DVD_CRNCY|'.$currency.'|CP_DVD_TYP|0|CP_NOTES|N.A.| ' . "\r\n";

            break;
            case "C":
                echo "<br>C<br>";
                //get 698 info related to this action and build csv string
                $_698_sql = "SELECT * FROM `698_txt` WHERE `localcode` = '$field20' AND `actflag` = 'U' ORDER BY `id` DESC LIMIT 1;";
                $_698_result = mysqli_query($edi_link, $_698_sql);
                $num_rows = mysqli_num_rows($_698_result);
                /*if ($num_rows == 0)
                    {
                    print "00000000000000000000000000000000000000";
                    break;
                    }*/
                $_698_row = mysqli_fetch_assoc($_698_result);
                $bbg_prefix_array = explode(" ", $_698_row['bbgcompositeticker']);
                $localcode = $_698_row['localcode'];
                $bbg_prefix = $bbg_prefix_array[0];
                print "------------------------------------------";
                print $bbg_prefix;
                print "<pre>";
                print_r($_698_row);
                print "</pre>";
                print $_698_sql;
                print "<br>------------------------------------------";

                    //create line of csv
                    print "_____MATCHES_____";
                    $csv .= $_695localcode.' US Equity|0|0|0|EDI'.$eventid.'_'.$eventcd.$date3.'|DVD_CASH|'.$actflag.'|'.$_695issuername.'|CUSIP|'.$uscode.'|USD|Equity| |'.$created.'|'.$date1.'|'.$changed.'|'.$bbgcompositeglobalid.'||'.$_695localcode.'|US|9|CP_RECORD_DT|'.$date2.'|CP_PAY_DT|'.$date3.'|CP_GROSS_AMT|'.$rate1.'|CP_NET_AMT|'.$rate2.'|CP_FREQ| |CP_TAX_AMT|N.A.|CP_DVD_CRNCY|'.$currency.'|CP_DVD_TYP|0|CP_NOTES|N.A.|' . "\r\n";

            break;

            }
        }
print "hi";
print "<br><br><br><br><br><br><br><br><br><br><br>";
print $csv;
//$file_date =  date("Ymd");
$file_date = "20170206";
$filename = "/var/www/html/EDI/BB_FEED/EDI-BB-DIV_translate.cax.".$file_date;
include '/var/www/html/EDI/file_exporter.php';
?>