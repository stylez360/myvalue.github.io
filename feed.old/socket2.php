<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$host = "198.190.11.31";
$port = "4009";
  if (isset($port) and
      ($socket=socket_create(AF_INET, SOCK_RAW, SOL_TCP)) and
      (socket_connect($socket, $host, $port)))
    {
      $text="Connection successful on IP $host, port $port";
      print $text;
      //$login_string = '5022=LoginUser|5028=marketo|5029=marketo|5026=1';
      //$login_string = '<0x04><0x20><0x2f>5022=LoginUser|5028=marketo|5029=marketo|5026=1<0x03>';
      $login_string = '<0x04><0x20><0x00><0x00><0x00><0x2f>5022=LoginUser|5028=marketo|5029=marketo|5026=1<0x03>';

      socket_write($socket, $login_string, strlen($login_string));
      echo ' Result: '.socket_read($socket, 2048);
      //print $login;
      socket_close($socket);
    }
  else
    {$text="Unable to connect<pre>".socket_strerror(socket_last_error())."</pre>";}

  /*echo "<html><head></head><body>".
       $text.
       "</body></html>";*/
?>

