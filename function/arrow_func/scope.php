<?php
   $maxmarks=300;
   $percent=fn ($marks) => $marks*100/$maxmarks;

   $m = 250;
   echo "Marks = $m Percentage = ". $percent($m);
?>