<?php
//$value = '{"Security":{"CIK":"0001160846","Cusip":"670851104","Symbol":"OIBR.C","ISIN":"US6708511042","Valoren":"18050712","Name":"Oi Shs (Old) Sponsored American Deposit Receipt Repr 1 Sh","Market":"NYSE","CategoryOrIndustry":"TelecomServices_Domestic","Outcome":"Success","Message":null,"Identity":null,"Delay":0.0},"Splits":[{"Security":null,"ExDate":"12/22/2014","Numerator":10.0,"Denominator":1.0,"SplitRatio":0.1,"DataConfidence":"Valid","Outcome":"Success","Message":null,"Identity":null,"Delay":0.0}],"Outcome":"Success","Message":null,"Identity":"Request","Delay":0.079004}}';
$value = '{"this":"1","that":"2"}}';
$obj = json_decode($value, TRUE);
print "<pre>";
print_r($obj);
print "</pre>";
?>