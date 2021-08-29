<?php
$people = array("Peter", "Joe", "Glenn", "Cleveland");
if (array_search("Glenn", $people))
{
echo "Match found";
}
else
{
echo "Match not found";
}

$key = array_search("Glenn", $people);

print $key;
//print_r($people);
?>