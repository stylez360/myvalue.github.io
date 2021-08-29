<?php
$string = '{"Security":{"CIK":"0000092380","Cusip":"844741108","Symbol":"LUV","ISIN":"US8447411088","Valoren":"971801","Name":"Southwest Airlines Co.","Market":"NYSE","CategoryOrIndustry":"SERVICES","Outcome":"Success","Message":null,"Identity":null,"Delay":0.0}}';
$obj = json_decode($string, TRUE);
print "<pre>";
print_r($obj);
print "</pre>";
print "done";
?>