<?php
// need to use the NuSoap extension
require_once('../lib/nusoap.php');

// if you access the internet through a proxy server
$proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';

// define the SOAP client using the url for the service
$client = new soapclient('http://globalcurrencies.xignite.com/xGlobalCurrencies.asmx?WSDL',
               true, $proxyhost, $proxyport, $proxyusername, $proxypassword);

// assess the results
$err = $client->getError();
if ($err) {
     echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

// create an array of parameters
$param = array(
               "From" => "EUR",
               "To" => "USD",
               "Amount" => "1500");
// call the service, passing the parameters and the name of the operation
$result = $client->call('ConvertRealTimeValue', array('parameters' => $param), '', '', false, true);
// assess the results
if ($client->fault) {
     echo '<h2>Fault</h2><pre>';
     print_r($result);
     echo '</pre>';
} else {
     $err = $client->getError();
     if ($err) {
          echo '<h2>Error</h2><pre>' . $err . '</pre>';
     } else {
// display the results
          echo '<h2>Result</h2><pre>';
// this function exposes the complete structure of the return class
          print_r($result);
          echo '</pre>';
     }
}
// print the SOAP request
echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
// print the SOAP response
echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// print the PHP debugging trace
echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
?>