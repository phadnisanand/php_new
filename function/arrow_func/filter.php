<?php
   $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
   $evenNumbers = array_filter($numbers, fn($n) => $n % 2 === 0);

   foreach ($evenNumbers as $num) {
      echo $num . " ";
   }
?> 