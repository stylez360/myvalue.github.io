<?php
$xml = '<GlobalQuote>
<Outcome>Success</Outcome>
<Message>Delay times are 15 mins for NYSE.</Message>
<Identity>Request</Identity>
<Delay>0.0150076</Delay>
<Security>
<CIK>0000732717</CIK>
<Symbol>T</Symbol>
<Valoren>2342429</Valoren>
<Name>AT&T Inc</Name>
<Market>NYSE</Market>
<MarketIdentificationCode>XNYS</MarketIdentificationCode>
<MostLiquidExchange>true</MostLiquidExchange>
<CategoryOrIndustry>TelecomServices_Domestic</CategoryOrIndustry>
</Security>
<Date>9/11/2014</Date>
<Time>2:20:12 PM</Time>
<UTCOffset>-4</UTCOffset>
<Open>34.61</Open>
<Close>0</Close>
<High>34.84</High>
<Low>34.5</Low>
<Last>34.81</Last>
<LastSize>100</LastSize>
<Volume>10861593</Volume>
<PreviousClose>34.7</PreviousClose>
<PreviousCloseDate>9/10/2014</PreviousCloseDate>
<ChangeFromPreviousClose>0.11</ChangeFromPreviousClose>
<PercentChangeFromPreviousClose>0.317</PercentChangeFromPreviousClose>
<Bid>34.81</Bid>
<BidSize>3700</BidSize>
<BidDate>9/11/2014</BidDate>
<BidTime>2:20:12 PM</BidTime>
<Ask>34.82</Ask>
<AskSize>10100</AskSize>
<AskDate>9/11/2014</AskDate>
<AskTime>2:20:12 PM</AskTime>
<High52Weeks>37.48</High52Weeks>
<Low52Weeks>31.74</Low52Weeks>
<Currency>USD</Currency>
<TradingHalted>false</TradingHalted>
</GlobalQuote>
<GlobalQuote>
<Outcome>Success</Outcome>
<Message>Delay times are 15 mins for NYSE.</Message>
<Delay>0</Delay>
<Security>
<CIK>0000004447</CIK>
<Symbol>HES</Symbol>
<Valoren>2552729</Valoren>
<Name>Hess Corp</Name>
<Market>NYSE</Market>
<MarketIdentificationCode>XNYS</MarketIdentificationCode>
<MostLiquidExchange>true</MostLiquidExchange>
<CategoryOrIndustry>MajorIntegratedOilAndGas</CategoryOrIndustry>
</Security>
<Date>9/11/2014</Date>
<Time>2:19:43 PM</Time>
<UTCOffset>-4</UTCOffset>
<Open>98.2</Open>
<Close>0</Close>
<High>98.91</High>
<Low>97.71</Low>
<Last>98.75</Last>
<LastSize>100</LastSize>
<Volume>1144530</Volume>
<PreviousClose>99.14</PreviousClose>
<PreviousCloseDate>9/10/2014</PreviousCloseDate>
<ChangeFromPreviousClose>-0.39</ChangeFromPreviousClose>
<PercentChangeFromPreviousClose>-0.393</PercentChangeFromPreviousClose>
<Bid>98.72</Bid>
<BidSize>600</BidSize>
<BidDate>9/11/2014</BidDate>
<BidTime>2:20:08 PM</BidTime>
<Ask>98.75</Ask>
<AskSize>300</AskSize>
<AskDate>9/11/2014</AskDate>
<AskTime>2:20:08 PM</AskTime>
<High52Weeks>104.5</High52Weeks>
<Low52Weeks>73.36</Low52Weeks>
<Currency>USD</Currency>
<TradingHalted>false</TradingHalted>
</GlobalQuote>';
/*$xml = '<ArrayOfGlobalQuote xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.xignite.com/services/">
<GlobalQuote>
<Outcome>Success</Outcome>
<Message>Delay times are 15 mins for NYSE.</Message>
<Identity>Request</Identity>
<Delay>0.0150076</Delay>
<Security>
<CIK>0000732717</CIK>
<Symbol>T</Symbol>
<Valoren>2342429</Valoren>
<Name>AT&T Inc</Name>
<Market>NYSE</Market>
<MarketIdentificationCode>XNYS</MarketIdentificationCode>
<MostLiquidExchange>true</MostLiquidExchange>
<CategoryOrIndustry>TelecomServices_Domestic</CategoryOrIndustry>
</Security>
<Date>9/11/2014</Date>
<Time>2:20:12 PM</Time>
<UTCOffset>-4</UTCOffset>
<Open>34.61</Open>
<Close>0</Close>
<High>34.84</High>
<Low>34.5</Low>
<Last>34.81</Last>
<LastSize>100</LastSize>
<Volume>10861593</Volume>
<PreviousClose>34.7</PreviousClose>
<PreviousCloseDate>9/10/2014</PreviousCloseDate>
<ChangeFromPreviousClose>0.11</ChangeFromPreviousClose>
<PercentChangeFromPreviousClose>0.317</PercentChangeFromPreviousClose>
<Bid>34.81</Bid>
<BidSize>3700</BidSize>
<BidDate>9/11/2014</BidDate>
<BidTime>2:20:12 PM</BidTime>
<Ask>34.82</Ask>
<AskSize>10100</AskSize>
<AskDate>9/11/2014</AskDate>
<AskTime>2:20:12 PM</AskTime>
<High52Weeks>37.48</High52Weeks>
<Low52Weeks>31.74</Low52Weeks>
<Currency>USD</Currency>
<TradingHalted>false</TradingHalted>
</GlobalQuote>
<GlobalQuote>
<Outcome>Success</Outcome>
<Message>Delay times are 15 mins for NYSE.</Message>
<Delay>0</Delay>
<Security>
<CIK>0000004447</CIK>
<Symbol>HES</Symbol>
<Valoren>2552729</Valoren>
<Name>Hess Corp</Name>
<Market>NYSE</Market>
<MarketIdentificationCode>XNYS</MarketIdentificationCode>
<MostLiquidExchange>true</MostLiquidExchange>
<CategoryOrIndustry>MajorIntegratedOilAndGas</CategoryOrIndustry>
</Security>
<Date>9/11/2014</Date>
<Time>2:19:43 PM</Time>
<UTCOffset>-4</UTCOffset>
<Open>98.2</Open>
<Close>0</Close>
<High>98.91</High>
<Low>97.71</Low>
<Last>98.75</Last>
<LastSize>100</LastSize>
<Volume>1144530</Volume>
<PreviousClose>99.14</PreviousClose>
<PreviousCloseDate>9/10/2014</PreviousCloseDate>
<ChangeFromPreviousClose>-0.39</ChangeFromPreviousClose>
<PercentChangeFromPreviousClose>-0.393</PercentChangeFromPreviousClose>
<Bid>98.72</Bid>
<BidSize>600</BidSize>
<BidDate>9/11/2014</BidDate>
<BidTime>2:20:08 PM</BidTime>
<Ask>98.75</Ask>
<AskSize>300</AskSize>
<AskDate>9/11/2014</AskDate>
<AskTime>2:20:08 PM</AskTime>
<High52Weeks>104.5</High52Weeks>
<Low52Weeks>73.36</Low52Weeks>
<Currency>USD</Currency>
<TradingHalted>false</TradingHalted>
</GlobalQuote>
</ArrayOfGlobalQuote>';*/


/*$pieces = explode('</GlobalQuote>
<GlobalQuote>', $xml);
print_r($pieces);

die();*/



$simple = "<para><note>simple note</note></para>";
$p = xml_parser_create();
xml_parse_into_struct($p, $xml, $vals, $index);
xml_parser_free($p);
/*echo "Index array\n";
print_r($index);*/
//echo "\nVals array\n";
print "<pre>";
print_r($vals);
print "</pre>";
?>