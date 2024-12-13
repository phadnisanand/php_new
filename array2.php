<?php
// determine if an array's keys are in numerical order, starting from index 0.

$list = ["a", "b", "c"];

array_is_list($list); // true

$notAList = [1 => "a", 2 => "b", 3 => "c"];

array_is_list($notAList); // false

$alsoNotAList = ["a" => "a", "b" => "b", "c" => "c"];

array_is_list($alsoNotAList); // false