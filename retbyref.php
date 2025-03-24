<?php
   function &myfunction(){
      static $x=10;
      echo "x Inside function: $x <br />";
      return $x;
   }

   $a = &myfunction(); 
   echo "Returned by Reference: $a <br />";
   $a=$a+10; 
   $a = &myfunction();
?>