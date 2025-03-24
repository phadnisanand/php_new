<?php  
   function  greeting($arg1="Hello", $arg2="world") {
      echo $arg1 . " ". $arg2 . PHP_EOL;
   }

   greeting();
   greeting("Thank you");
   greeting("Welcome", "back");
   greeting("PHP");
?>