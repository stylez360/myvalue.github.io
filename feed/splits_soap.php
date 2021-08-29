<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// define the SOAP client using the url for the service
$client = new soapclient('http://www.xignite.com/xGlobalHistorical.asmx?WSDL');

// create an array of parameters
$param = array(
               "Exchange" => "NYSE",
               "StartDate" => "12/23/2013",
               "EndDate" => "12/22/2014");

// add authentication info
$xignite_header = new SoapHeader('http://www.xignite.com/services/',
     "Header", array("Username" => "EF2662FA141B4DC086F6A72B2D15AD2C"));
$client->__setSoapHeaders(array($xignite_header));

// call the service, passing the parameters and the name of the operation
$result = $client->GetAllSplitsByExchange($param);
// assess the results
if (is_soap_fault($result)) {
     echo '<h2>Fault</h2><pre>';
     print_r($result);
     echo '</pre>';
} else {
     echo '<h2>Result</h2><pre>';
     print_r($result);
     echo '</pre>';
}
// print the SOAP request
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->__getLastRequest(), ENT_QUOTES) . '</pre>';
// print the SOAP request Headers
echo '<h2>Request Headers</h2><pre>' . htmlspecialchars($client->__getLastRequestHeaders(), ENT_QUOTES) . '</pre>';
// print the SOAP response
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->__getLastResponse(), ENT_QUOTES) . '</pre>';
?>