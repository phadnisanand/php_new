<?php
// type juggling, type casting, type hinting
// https://www.tutorialspoint.com/php/php_type_juggling.htm
// https://mahafuz.medium.com/understanding-type-juggling-in-php-48347f929aa0
// https://www.w3schools.com/php/php_casting.asp
// https://flatcoding.com/tutorials/php-programming/php-type-juggling/
// https://dev.to/rocksheep/the-way-stricttypes-works-in-php-eb7
// https://dev.to/rocksheep/the-way-stricttypes-works-in-php-eb7
// https://www.youtube.com/watch?v=0MYqGSplVQs
// https://ashallendesign.co.uk/blog/using-declare-strict_types-1-for-more-robust-php-code
// https://www.phptutorial.net/php-tutorial/php-strict_types/
// https://www.youtube.com/watch?v=SIOfVm4wwwE&t=537s
// https://www.youtube.com/watch?v=5e9HsiOG8kc
// https://www.youtube.com/watch?v=9VbuI7Lveuw
declare(strict_types=1);

function dump(int $value): void  
{  
 var_dump($value);  
}  

//dump('13.37');
//dump(19.42);
dump(19);