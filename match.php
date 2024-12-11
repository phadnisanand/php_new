<?php
//Match is php8 feature. You can update php7.4 to 8 or use switch
// https://php.watch/versions/8.0/match-expression
// https://php.watch/
// https://stackoverflow.com/questions/74281839/operator-in-match
// https://www.geeksforgeeks.org/php-match-expression/
// https://www.geeksforgeeks.org/php-match-expression/
//https://www.youtube.com/watch?v=RPUoaHERTRQ
  $sub = 'PHP'; 
  
  $val = match ($sub) { 
      'HTML' => 'HTML Course', 
      'CSS' => 'CSS Course', 
      'PHP' => 'PHP Course', 
      'JavaScript' => 'JS Course', 
      'WebDev' => 'Complete Web Development'
  }; 
  
  var_dump($val); 
?>