<?php
//$path = '/var/www/html/EDI/BB_FEED/';


//$csv = 'ADRND US Equity|0|0|0|222728_TKOVR|ACQUIS|U|Koninklijke Ahold Delhaize N.V.|N.A.|N.A.|N.A.| | |20150625||20160725|N.A.| |N.A.|N.A.|13|CP_TYP|1|CP_CASH|0|CP_CASH_FLAG| |CP_SH|4.7500000|CP_SH_FLAG|1|CP_FLAG|1|CP_TKR|ADRND US|CP_NAME|Koninklijke Ahold Delhaize N.V.|CP_STAT|3|CP_CRNCY||CP_DEBT|0|' . "\r\n";

$fhandle = fopen($filename,"wb");

if( $fhandle == false )
    {print "File Not Opened!";
    }else{
    fwrite($fhandle,$csv);
    fclose($fhandle);
    }


?>