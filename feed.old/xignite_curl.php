<?
$pp_string = '_Token=94C0A5DD9097460ABCB2D49C686185CF&Exchange=XNAS&StartSymbol=A&EndSymbol=B&InstrumentClass=Stock&AsOfDate=9/5/2014';
$opts = array('http'=>
        array('method' =>'POST',
                'port' =>'443',
                'header' =>'Content-type: application/x-www-form-urlencoded',
                'content' =>$pp_string
                )
			);
//print_r($opts);
$context = stream_context_create($opts);
$file = fopen('http://globalmaster.xignite.com/xglobalmaster.csv/GetMasterByExchange', 'rb', false, $context) or die ("Merchant Services Not Responding");
$results = @stream_get_contents($file);
/*$results=str_replace(' ','+',$results);
$pairs = explode('&',$results);
foreach($pairs as $value)
	{
	//print $value.'<br><br><br>';
	$pair = explode('=', $value);
	//print_r($pair);
	foreach($pair as $key => $value)
		{
		$$pair['0'] = $pair['1'];
		}
	}*/
print "<pre>";
print $results;
print "</pre>";
?>