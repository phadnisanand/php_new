<?php  
   function change_name($nm) {
      echo "Initially the name is $nm <br />";
      $nm = $nm."_new";
      echo "This function changes the name to $nm <br />";
   }

   $name = "John";
   echo "My name is $name <br />";
   change_name($name);
   echo "My name is still $name";
?>