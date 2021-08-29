<?php
$json = '{"query":{"count":2,"created":"2015-01-20T19:08:12Z","lang":"en-US","results":{"quote":[{"symbol":"LUV","AverageDailyVolume":"8781180","Change":"+0.98","DaysLow":"39.72","DaysHigh":"40.76","YearLow":"20.22","YearHigh":"43.19","MarketCapitalization":"27.462B","LastTradePriceOnly":"40.46","DaysRange":"39.72 - 40.76","Name":"Southwest Airline","Symbol":"LUV","Volume":"6169040","StockExchange":"NYSE"},{"symbol":"AAPL","AverageDailyVolume":"50187000","Change":"+1.5798","DaysLow":"106.50","DaysHigh":"108.78","YearLow":"70.5071","YearHigh":"119.75","MarketCapitalization":"630.9B","LastTradePriceOnly":"107.5698","DaysRange":"106.50 - 108.78","Name":"Apple Inc.","Symbol":"AAPL","Volume":"28270052","StockExchange":"NasdaqNM"}]}}}';
$phpObj =  json_decode($json);

print "<pre>";
print_r($phpObj);
print "</pre>";

?>