<?php
// https://www.php.net/manual/en/function.array-map.php
// https://www.geeksforgeeks.org/php-array_map-function/
function square($n)
{
    return ($n * $n);
}

$a = [1, 2, 3, 4, 5];
/*$b = array_map('square', $a);
print_r($b);*/

$array = array_map(fn($num) => $num * $num, $a);
print_r($array);
?>