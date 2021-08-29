<?php
include "dbconnect.php";
$sql = "SELECT * FROM `695_txt` WHERE `eventcd` LIKE 'TKOVR' AND `changed` LIKE '2016/07/15%' AND `exchgcntry` LIKE 'US' AND `liststatus` LIKE 'L' AND `Field20` NOT LIKE '';";
$result = mysqli_query($edi_link, $sql);
for ($counter=1; $counter <= mysqli_num_rows($result); $counter++)
		{
		$row = mysqli_fetch_assoc($result);
        print "<pre>";
        print_r($row);
        print "</pre>";
        $paytype = $row['Paytype'];
        $field20 = $row['Field20'];
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
                    }
            break;
            case "C":
                echo "<br>C<br>";
            break;
            case "B":
                echo "<br>B<br>";
            break;
            }
        }
//print "hi";
?>