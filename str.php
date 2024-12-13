<?php
// https://www.cloudways.com/blog/php-8/#nullsafe
// https://www.geeksforgeeks.org/php-str_starts_with-function/?ref=ml_lbp

$string = 'GFG is awesome';
$endsWith = 'some';

$result = str_ends_with($string, $endsWith) ? 'is' : 'is not';

echo "The string \"$string\" $result ending with $endsWith";

echo '<br />';
$name = 'Saurabh Singh'; 
$beginsWith = 'Saurabh'; 

$result = str_starts_with($name, $beginsWith) ? 'is' : 'is not'; 

echo "The string \"$name\" $result starting with $beginsWith"; 


?>
