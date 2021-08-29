<?php
$stack = array();
$handle = @fopen("data.txt", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
        //echo $buffer.'<br><br><br><br><br>';
        array_push($stack, $buffer);
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}
print '<pre>';
print_r($stack);
print '</pre>';
?>