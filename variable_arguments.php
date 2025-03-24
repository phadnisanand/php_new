<?php 
//https://www.tutorialspoint.com/php/php_variable_arguments.htm
   function  myfunction(...$numbers) {
      $avg = array_sum($numbers)/count($numbers);
      return $avg;
   }
   $avg = myfunction(5, 12, 9, 23, 8, 66);
   echo "average = $avg";
?>