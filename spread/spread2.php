<?php
   function get_squares() {
      for ($i = 0; $i < 5; $i++) {
         $arr[] = $i**2;
      }
      return $arr;
   }
   $squares = [...get_squares()];
   print_r($squares);
?>