<?php
//https://www.tutorialspoint.com/php/php_anonymous_functions.htm
   $add = function ($a, $b) {
      return "a:$a b:$b addition: " . $a+$b; 
   };
   echo $add(5,10);
?>