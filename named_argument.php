<?php
// https://www.tutorialspoint.com/php/php_named_arguments.htm
// https://php.watch/versions/8.0/named-parameters
// https://stitcher.io/blog/php-8-named-arguments
   function  myfunction($x, $y) {
      echo "x = $x  y = $y";
   }

   myfunction(x:10, y:20);  echo '<br />';
   myfunction(y:20, x:10);
?>