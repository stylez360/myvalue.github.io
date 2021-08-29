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
$results = substr($results[1], 1, -1);

//$results = substr($results[1], 1, -1);
$pieces = explode('},{', $results);
foreach($pieces as $key => $value)
    {
    $value = '{'.$value.'}';
    //print $value."<br><br>";
    $obj = json_decode($value, TRUE);
    print "<pre>";
    print_r($obj);
    print "</pre>";
    foreach($obj as $key => $value)
            {
            $$key = $value;
            }
    $symbol_insert = "INSERT INTO `feed_data`.`symbol_feed` (
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
    print "<br>-----------------------------------------------------------------------------------------------------<br>";
    }
/*print "<pre>";
print_r($pieces);
print "<pre>";*/
?>