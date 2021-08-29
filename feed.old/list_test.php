<?php
$pp_string = 'Exchange=XNYS&StartSymbol=A&EndSymbol=B&_Token=EF2662FA141B4DC086F6A72B2D15AD2C';
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
//print_r($opts);
$context = stream_context_create($opts);
$file = fopen('http://globalquotes.xignite.com/v3/xGlobalQuotes.json/ListSymbols', 'rb', false, $context) or die ("Merchant Services Not Responding");
$results = @stream_get_contents($file);
//print $results;
//$results=str_replace(' ','+',$results);
$results = substr($results, 1, -2);
$results = explode('[',$results);
//print$results[1];
//$results[1] =  substr($results[1], 1, -1);
$pairs = explode(',',$results[1]);
print_r($pairs);
foreach($pairs as $value)
    {
    $value = str_replace('"', '', $value);
    $pieces = explode(',', $value);
    //print $value."<br><br>";
    //print_r($pieces);
    foreach($pieces as $key => $value)
        {
        $set= explode(':', $value);
        /*print "<pre>";
        print_r($set);
        print "<pre>";*/
        foreach($set as $KEY => $VALUE)
            {
            //print $KEY;
            $$KEY = $VALUE;
            }
        }
    /*$symbol_insert = "INSERT INTO `feed_data`.`symbol_feed` (
            `uid`,
            `Symbol`,
            `Currency`,
            `Name`)
            VALUES (
            '',
            '".$Symbol."',
            '".$Currency."',
            '".$Name."'
            );";
    print $symbol_insert;
    print "<br>-----------------------------------------------------------------------------------------------------<br>";*/
    }











?>