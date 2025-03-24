<?php
// https://www.w3schools.com/php/keyword_fn.asp
// https://www.tutorialspoint.com/php/php_arrow_functions.htm
//The fn keyword is used to create arrow functions. Arrow functions are only available in PHP versions 7.4 and up.
// Arrow functions have access to all variables from the scope in which they were created.


   $nums = [1, 2, 3, 4];
   $squared = array_map(fn($n) => $n * $n, $nums);

   print_r($squared);
?> 