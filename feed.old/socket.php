<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$host = "198.190.11.21";
$port = "4010";
$socket = socket_create(AF_UNIX, SOCK_STREAM, 0)
or die("Unable to create socket\n");
socket_connect($socket, $host, $port);

print 'end of script';
?>