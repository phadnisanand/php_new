<?php
// // https://php.watch/versions/8.0/null-safe-operator  ?->
// https://www.tutorialspoint.com/nullsafe-operator-in-php-8
// The ?-> null-safe operator can help reduce excessive isset() and ternary checks.
 class Emp{
      public function getAddress() {}
   }
   $emp = new Emp();
   $dept = $emp?->getAddress()?->dept?->iso_code;
   print_r($dept);