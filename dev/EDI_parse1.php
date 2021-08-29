<?php
$string = file_get_contents('data.txt', true);
print $string;

$pieces = explode('\n', $string);
//$array = explode("\t", $string);
print_r($pieces);
?>