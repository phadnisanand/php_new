<?php
// solves this by following the semantics of array_merge
$array1 = ["a" => 1];

$array2 = ["b" => 2];

$array = ["a" => 0, ...$array1, ...$array2];

print_r($array); // ["a" => 1, "b" => 2]