<?php
   function increment($num) {
      echo "The initial value: $num <br/>";
      $num++;
      echo "This function increments the number by 1 to $num <br/>";
   }
   $x = 10;
   increment($x);
   echo "Number has not changed: $x";
?>