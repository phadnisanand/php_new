<?php
//https://www.geeksforgeeks.org/php-str_contains-function/
// https://www.droptica.com/blog/combining-string-literals-and-variables-php/
// https://www.cloudways.com/blog/php-8/#nullsafe
$sentence = 'GFG is Awesome';
$word = 'Awesome';
 
$result = str_contains($sentence, $word) ? 'is' : 'is not';
 
echo "The word {$word} {$result} present in the sentence \"{$sentence}\" ";
