<?php
//https://www.tutorialspoint.com/php/php_spread_operator.htm
   $arr1 = [1,2,3];
   $arr2 = [4,5,6];
   $arr3 = [...$arr1, ...$arr2];

   print_r($arr3);
?>