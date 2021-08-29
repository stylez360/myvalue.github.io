<?php
include "dbconnect.php";
//$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/25%' AND `exchgcntry` LIKE 'US' AND `liststatus` LIKE 'L' AND `Field20` NOT LIKE '';";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/25%' AND `exchgcntry` LIKE 'US' AND `Field20` NOT LIKE '';";
$result = mysqli_query($edi_link, $sql);
for ($counter=1; $counter <= mysqli_num_rows($result); $counter++)
		{
		$row = mysqli_fetch_assoc($result);
        print "<pre>";
        print_r($row);
        print "</pre>";
        $paytype = $row['Paytype'];
        $field20 = $row['Field20'];
        $id = $row['id'];
        $eventcd = $row['eventcd'];
        $date4 = str_replace("/", "", $row['Date4']);
        $actflag = $row['actflag'];
        $field3 = $row['Field3'];
        $created = str_replace("/", "", $row['created']);
        $changed = str_replace("/", "", $row['changed']);
        $rationew = $row['RatioNew'];
        $field20 = $row['Field20'];
        $field3 = $row['Field3'];
        $currency = $row['Currency'];
        $rate2type = $row['Rate2Type'];
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
                if ($bbg_prefix == $localcode && $num_rows == 1)
                    {
                    //create line of csv
                    print "_____MATCHES_____";
                    $csv .= $localcode.' US Equity|0|0|0|'.$id.'_'.$eventcd.$date4.'|ACQUIS|'.$actflag.'|'.$field3.'|N.A.|N.A.|N.A.| | |'.$created.'|'.$date4.'|'.$changed.'|N.A.| |N.A.|N.A.|13|CP_TYP|1|CP_CASH|0|CP_CASH_FLAG| |CP_SH|'.$rationew.'|CP_SH_FLAG|1|CP_FLAG|1|CP_TKR|'.$field20.' US|CP_NAME|'.$field3.'|CP_STAT|3|CP_CRNCY|'.$currency.'|CP_DEBT|0|' . "\r\n<br><br>";
                    }
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
                if ($bbg_prefix == $localcode && $num_rows == 1)
                    {
                    //create line of csv
                    print "_____MATCHES_____";
                    $csv .= $localcode.' US Equity|0|0|0|'.$id.'_ '.$eventcd.$date4.'|ACQUIS|'.$actflag.'|'.$field3.'|N.A.|N.A.|N.A.| | |'.$created.'|'.$date4.'|'.$changed.'|N.A.| |N.A.|N.A.|13|CP_TYP|2|CP_CASH|'.$rate2type.'|CP_CASH_FLAG|1|CP_SH|0|CP_SH_FLAG|N.A.|CP_FLAG|1|CP_TKR|'.$field20.' US|CP_NAME|'.$field3.'|CP_STAT|3|CP_CRNCY|'.$currency.'|CP_DEBT|0|CP_DEBT_FLAG| |CP_NOTES|N.A.|' . "\r\n<br><br>";
                    }
            break;
            case "B":
                echo "<br>B<br>";
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
                if ($bbg_prefix == $localcode && $num_rows == 1)
                    {
                    //create line of csv
                    print "_____MATCHES_____";
                    $csv .= $localcode.' US Equity|0|0|0|'.$id.'_ '.$eventcd.$date4.'|ACQUIS|'.$actflag.'|'.$field3.'|N.A.|N.A.|N.A.| | |'.$created.'|'.$date4.'|'.$changed.'|N.A.| |N.A.|N.A.|13|CP_TYP|3|CP_CASH|'.$rate2type.'|CP_CASH_FLAG|1|CP_SH|'.$rationew.'|CP_SH_FLAG|N.A.|CP_FLAG|1|CP_TKR|'.$field20.' US|CP_NAME|'.$field3.'|CP_STAT|3|CP_CRNCY|'.$currency.'|CP_DEBT|0|CP_DEBT_FLAG| |CP_NOTES|N.A.|' . "\r\n<br><br>";
                    }
            break;
            }
        }
//print "hi";
print "<br><br><br><br><br><br><br><br><br><br><br>";
print $csv;
?>