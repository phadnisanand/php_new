<?php
   $nums = [1, 2, 3, 4];
   $squared = array_map(fn($n) => $n * $n, $nums);

   print_r($squared);
?> 